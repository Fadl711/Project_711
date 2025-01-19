<?php

namespace App\Http\Controllers\purchases;

use App\Enum\AccountClass;
use App\Enum\AccountType;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\MainAccount;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use App\Models\SubAccount;
use App\Models\Warehouse;
use App\Http\Controllers\purchases\AccountingPeriod;
use App\Models\AccountingPeriod as ModelsAccountingPeriod;
use App\Models\Category;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;//+
use NumberToWords\NumberToWords;

use function PHPUnit\Framework\isNull;

class PurchaseController extends Controller
{
    //
    public function bills_purchase_show()
    {
        return view('invoice_purchases.bills_purchase_show');
    }
  
    public function create() {
        $Currency_name=Currency::all();
    
            $allSubAccounts = SubAccount::all();
                return view('Purchases.create',
                 ['AllSubAccounts'=>$allSubAccounts,
                'Currency_name'=>$Currency_name,
            ]);
        return view('Purchases.create');
    }
  

    public function getMainAccounts(Request $request,$id)
    {

        $accountType = AccountType::tryFrom($id );
        if (!$accountType) {
            return response()->json(['error' => 'نوع الحساب غير موجود'], 404);
        }

        // استرجاع الحسابات الرئيسية المرتبطة بالنوع
        $subAccounts = MainAccount::where('typeAccount',$accountType->value )
        ->where('typeAccount',$accountType->value )->get();

        if ($subAccounts->isEmpty()) {
            return response()->json(['error' => 'لا توجد حسابات رئيسية متاحة لهذا النوع'], 404);
        }
    // dd($mainAccounts);
    return response()->json($subAccounts);}
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Payment_type' => 'required|numeric',
            'transaction_type' => 'required|string',
            'main_account_debit_id' => 'required|numeric',
            'Supplier_id' => 'required|numeric',
            'Currency_id' => 'required|numeric',
        ], [
            'Payment_type.required' => 'حقل نوع الدفع مطلوب.',
            'transaction_type.required' => 'حقل نوع المعاملة مطلوب.',
            'Supplier_id.required' => 'حقل اسم المورد مطلوب.',
            'main_account_debit_id.required' => 'حقل حساب التصدير مطلوب.',
            'Currency_id.required' => 'حقل  العملة الفاتورة مطلوب.',
            'Supplier_id' => 'حساب التصدير المحدد غير موجود.',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->messages()
            ], 422);
        }
    
        $accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد فترة محاسبية مفتوحة'
            ], 400);
        }
        try {
            $purchaseInvoice = new PurchaseInvoice();
            $purchaseInvoice->Receipt_number = str_replace(',', '', $request->Receipt_number ?? 0);
            $purchaseInvoice->Total_invoice = str_replace(',', '', $request->Total_invoice ?? 0);
            $purchaseInvoice->Total_cost = str_replace(',', '', $request->Total_cost ?? 0);
            $purchaseInvoice->Paid = 0;
            $purchaseInvoice->User_id = $request->User_id ?? auth()->id();
            $purchaseInvoice->accounting_period_id = $accountingPeriod->accounting_period_id;
            $purchaseInvoice->Invoice_type = $request->Payment_type;
            $purchaseInvoice->account_id = $request->sub_account_debit_id;
            $purchaseInvoice->Supplier_id = $request->Supplier_id;
            $purchaseInvoice ->Currency_id= $request->Currency_id;
            $purchaseInvoice->transaction_type = $request->transaction_type;
            $purchaseInvoice->save();
            return response()->json([
                'success' => true,
                'message' => 'تم الحفظ بنجاح',
                'invoice_number' => $purchaseInvoice->purchase_invoice_id,
                'supplier_id' => $purchaseInvoice->Supplier_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ أثناء الحفظ: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function storc(Request $request)
    {
        // التحقق من عدم تطابق الحسابات
        $accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();

        $purchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', $request->purchase_invoice_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->first();
    if (!$purchaseInvoice) {
        return response()->json([
            'success' => false,
            'message' => 'الفاتورة غير موجودة.'
        ], 404);
    }
        // التحقق من وجود المنتج في النظام
        $product = Product::where('product_id', $request->product_id)->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'هذا المنتج غير موجود في النظام. يجب عليك إضافته من صفحة المنتجات.'
            ]);
        }
    
        // التحقق من صحة الحقول
        $validator = Validator::make($request->all(), [
            'account_debitid' => 'required',
            'Quantity' => 'required|numeric|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
    
        // الحصول على الفترة المحاسبية المفتوحة
        if (!$accountingPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد فترة محاسبية مفتوحة.'
            ]);
        }
    
        // التحقق من وجود الفاتورة
      
        if ($request->account_debitid === $purchaseInvoice->account_id) {
            return response()->json([
                'success' => false,
                'message' => 'يجب عليك تحديد مخازن مختلفة.'
            ]);
        }
        // تعيين الحسابات والمستودعات بناءً على نوع العملية
        $transactionType = $purchaseInvoice->transaction_type;
        $warehouse_to_id = $warehouse_from_id = $account_id = null;
        $Product = Product::where('product_id', $request->product_id)->first();
        $Productquantity =0;
        switch ($transactionType) {
            case 1: // عملية دخول المخزون
             $Productquantity=   $Product->Quantity + $request->Quantity;

                $supplier_id= $purchaseInvoice->Supplier_id;
                $warehouse_to_id = $request->account_debitid;
                $account_id = $purchaseInvoice->account_id;
                $warehouse_from_id = null;
                break;
        
            case 2: // عملية خروج المخزون
                $Productquantity=   $Product->Quantity - $request->Quantity;

                $supplier_id= $purchaseInvoice->Supplier_id;
                $warehouse_from_id = $request->account_debitid;
            $account_id = $purchaseInvoice->account_id;
            $warehouse_to_id = null;
                break;
            case 3: // عملية تحويل المخزون

                if ($request->account_debitid === $purchaseInvoice->account_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'يجب عليك تحديد مخازن مختلفة.'
                    ]);
                }
                $supplier_id= $purchaseInvoice->Supplier_id;
                $Productquantity=   $Product->Quantity - $request->Quantity;
                $warehouse_to_id = $request->account_debitid;
                $warehouse_from_id = $purchaseInvoice->account_id;
                $account_id = null;
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'نوع العملية غير معروف.'
                ]);
        }
        // التحقق من فئة الحساب
        $subAccount = SubAccount::where('sub_account_id', $purchaseInvoice->account_id)->first();
        if (($transactionType == 3 && $subAccount->AccountClass != 3) ||
            ($transactionType == 1 && $subAccount->AccountClass == 3)) {
            return response()->json([
                'success' => false,
                'message' => $transactionType == 3 
                    ? 'نوع العملية غير معروف.' 
                    : 'يجب عليك تحديد حساب التصدير الدائن آخر غير حساب المخازن لإنك تقوم بعملية شراء.'
            ]);
        }
        $purchasePrice = $this->removeCommas($request->Purchase_price);
        $Selling_price = $this->removeCommas($request->Selling_price);
        $TotalPurchase = $this->removeCommas($request->TotalPurchase);
        $Cost = $this->removeCommas($request->Cost);
        $Profit = $this->removeCommas($request->Profit);
     
        $categorieId = Category::where('product_id', $request->product_id)
        ->where('categorie_id', $request->Categorie_name)
        ->orwhere('Categorie_name', $request->Categorie_name)
        ->value('Categorie_name');
        // إنشاء أو تحديث سجل الشراء
        $purchase = Purchase::updateOrCreate(
            [
                'Product_name' => $Product->product_name,
                'categorie_id' => $categorieId,
                'product_id' => $Product->product_id,

                // 'purchase_id' => $request->purchase_id,
                'Purchase_invoice_id' => $purchaseInvoice->purchase_invoice_id,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
            ],
            [
                'Supplier_id' => $supplier_id,
                'Barcode' => $Product->Barcode ?? 0,
                'quantity' => $request->Quantity,
                'Purchase_price' => $purchasePrice,
                'Selling_price' => $Selling_price,
                'Quantityprice' => $request->Quantity,
                'Total' => $TotalPurchase,
                'Cost' => $Cost,
                'Currency_id' => $purchaseInvoice->Currency_id ??null,
                'User_id' => $request->User_id ?? auth()->id(),
                'warehouse_to_id' => $warehouse_to_id,
                'warehouse_from_id' => $warehouse_from_id,
                'Discount_earned' => $request->Discount_earned ?? 0,
                'Profit' => $Profit ?? 0,
                'Exchange_rate' => $request->Exchange_rate ?? 1.0,
                'note' => $request->note ?? '',
                'account_id' => $account_id,
                'transaction_type' => $transactionType,
            ]
        );

         
        // تحديث الفاتورة
        try {
            $Purchasesum = Purchase::where('Purchase_invoice_id', $purchaseInvoice->purchase_invoice_id )->sum('Total');
            // $Purchasesum = $purchase->sum('Total');
            $Invoice_type = $request->Payment_type;
            $pamyment = in_array($Invoice_type, [1, 3, 4]) ? $Purchasesum : 0;

            $Currency=   $request->Currency_id;
            
            $purchaseInvoice->update([
                // 'Invoice_type' => $Invoice_type,
                'Receipt_number' => $request->Receipt_number,
                'Total_invoice' => $Purchasesum,
                'Paid' => $pamyment,                
                'Total_cost' => $request->Total_cost,
            ]);
            Product::where('product_id',$Product->product_id)->update([
                'Quantity'=>$Productquantity,
                'supplier_id'=>$supplier_id,
            ]);     
         // الحصول على اسم الفئة
$categoryName = Category::where('categorie_id', $purchase->categorie_id)->pluck('Categorie_name')->first();

// إضافة اسم الفئة إلى المصفوفة $purchase
       $purchase->category_name = $categoryName;

            return response()->json([
                'success' => true,
                'message' => 'تم الحفظ بنجاح وتحديث الفاتورة.',
                'purchase' => $purchase,
                // 'categories' => $categories,
                'Purchasesum' =>  $Purchasesum,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحديث.'
            ]);
        }
    }
    private function removeCommas($value)
    {
        return floatval(str_replace(',', '', $value)); // إزالة الفواصل وتحويل إلى float
    }
    
 private function convertArabicNumbersToEnglish($value)
    {
        $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($arabicNumbers, $englishNumbers, $value);
    }

public function search(Request $request)
{$accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();

    if (!$accountingPeriod) {
        return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة'], 404);
    }
    
    // الحصول على id المنتج من الطلب
    $id = $request->query('id');
   
        $warehouse_to_id = $request->account_debitid;
        // return response()->json();

    // التحقق من وجود المنتج
    $productData = Product::where('product_id', $id)->first();
    $product_id= $productData->product_id;
    if (!$productData) {
        return response()->json(['success' => false, 'message' => 'المنتج غير موجود'], 404);
    }

 $purchaseToQuantity = Purchase::where('product_id', $id)
->where('accounting_period_id', $accountingPeriod->accounting_period_id)
->where('warehouse_to_id', $warehouse_to_id)
->whereIn('transaction_type', [1, 6, 3,7])
->sum('quantity');

$warehouseFromQuantity = Purchase::where('product_id', $id)
    ->where('warehouse_from_id', $warehouse_to_id)
    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    ->where('transaction_type', 2)
    ->sum('quantity');
   
$warehouseFromQuantity3 = Purchase::where('product_id', $id)
    ->where('warehouse_from_id', $warehouse_to_id)
    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    ->where('transaction_type', 3)
    ->sum('quantity');

$saleQuantity5 = Sale::where('product_id', $id)
    ->where('warehouse_to_id', $warehouse_to_id)
    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    ->where('transaction_type', 5)
    ->sum('quantity');

$saleQuantity4 = Sale::where('product_id', $id)
    ->where('warehouse_to_id', $warehouse_to_id)
    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    ->where('transaction_type', 4)
    ->sum('quantity');
   

$productPurchase =( $purchaseToQuantity+$saleQuantity5 )- $warehouseFromQuantity - $warehouseFromQuantity3- $saleQuantity4 ;

// $productPurchase = $totalQuantity - $warehouseFromQuantity3 - $purchasesReturnEndSales;
            // تخزين البيانات في مصفوفة
       
 $categories = Category::where('product_id', $id)
 ->select('categorie_id', 'Categorie_name')
 ->get();        // حساب الكمية النهائية المتاحة في المخزن
    if ($productData) {
        $product = [
            'product_name' => $productData->product_name,
            'Barcode' => $productData->Barcode, // لا حاجة لتحويله هنا
            'Selling_price' => $productData->Selling_price, // لا حاجة لتحويله هنا
            'Purchase_price' => $productData->Purchase_price, // لا حاجة لتحويله هنا
            'Categorie_id' => $productData->Categorie_id, // لا حاجة لتحويله هنا
            'Special_discount' => $productData->Special_discount, // لا حاجة لتحويله هنا
            'Regular_discount' => $productData->Regular_discount, // لا حاجة لتحويله هنا
            'Quantity' => $productData->quantity,
            'QuantityPurchase'=> $productPurchase,
            'Categorie_names' => $categories, // أسماء الفئات كقائمة
        ];
        // تحويل الأرقام العربية إلى إنجليزية
        $product['Barcode'] = $this->convertArabicNumbersToEnglish($product['Barcode']);
        $product['Selling_price'] = $this->convertArabicNumbersToEnglish($product['Selling_price']);
        $product['Purchase_price'] = $this->convertArabicNumbersToEnglish($product['Purchase_price']);
        $product['Categorie_id'] = $this->convertArabicNumbersToEnglish($product['Categorie_id']);
        $product['quantity'] = $this->convertArabicNumbersToEnglish($product['Quantity']);
        $product['QuantityPurchase'] = $this->convertArabicNumbersToEnglish($product['QuantityPurchase']);
        $product['Special_discount'] = $this->convertArabicNumbersToEnglish($product['Special_discount']);
        return response()->json($product); // إرجاع تفاصيل المنتج إذا تم العثور عليه
    }

    return response()->json(['message' => 'Product not found'], 404); // إرجاع رسالة خطأ إذا لم يتم العثور على المنتج
}
public function edit($id)
{
    $purchase = Purchase::where('purchase_id',$id)->first();
    return response()->json($purchase);
}
public function destroy($id)
{
    // dd($id);
    $accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();

    // التحقق من وجود السجل
    $purchase = Purchase::where('purchase_id', $id)
    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    ->whereIn('transaction_type', [1,2,3,6])
    ->first();

    if (!$purchase) {
        return response()->json([
            'status' => 'error',
            'message' => 'العنصر غير موجود.'
        ], 404);
    }

    try {
        if($purchase->transaction_type!==7)
        {

            $purchase->delete();
        }
        // حذف السجل
        // تحديث الإجمالي
        $Purchasesum = Purchase::where('Purchase_invoice_id', $purchase->Purchase_invoice_id)->sum('Total');
        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف البيانات بنجاح.',
            'Purchasesum' => $Purchasesum
        ]);

    } catch (\Exception $e) {
        // في حالة حدوث خطأ أثناء الحذف
        return response()->json([
            'status' => 'error',
            'message' => 'حدث خطأ أثناء حذف البيانات. يرجى المحاولة لاحقًا.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function print($id)
{
    $DataPurchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', $id)->first();
    $SubAccount = SubAccount::where('sub_account_id', $DataPurchaseInvoice->Supplier_id)->first();
    $UserName = User::where('id', $DataPurchaseInvoice->User_id)->pluck('name')->first();

    if (!$UserName) {
        $UserName = 'اسم غير موجود';
    }
        $SubName = SubAccount::all();
    if($SubAccount->AccountClass===1)
    {
        $AccountClassName="العميل";
    }
    if($SubAccount->AccountClass===2)
    {
        $AccountClassName="المورد";
    }
    if($SubAccount->AccountClass===3)
    {
        $AccountClassName="المخزن";
    }
    if($SubAccount->AccountClass===4)
    {
        $AccountClassName="الحساب";
    }
    

    $DataPurchase = Purchase::where('Purchase_invoice_id', $id)->get();
    $Categorys = Category::all();
   $currency=Currency::where('currency_id', $DataPurchaseInvoice->Currency_id)->first();
   $curre=Currency::where('currency_id', $DataPurchaseInvoice->Currency_id)->pluck('currency_name')->first();
   $Invoice_type=PaymentType::tryFrom($DataPurchaseInvoice->Invoice_type)?->label() ?? 'غير معروف';
    // حساب مجموع السعر والتكلفة
    $Purchase_priceSum = Purchase::where('Purchase_invoice_id', $id)->sum('Total');
    $Purchase_CostSum = Purchase::where('Purchase_invoice_id', $id)->sum('Cost');
  // جلب البيانات وتحويلها
  $numberToWords = new NumberToWords();
  $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
  
// dd($DataPurchaseInvoice->Invoice_type);
    // تحويل القيمة إلى نص مكتوب
    return view('invoice_purchases.bills_purchase_show', [
        'DataPurchaseInvoice' => $DataPurchaseInvoice,
        'DataPurchase' => $DataPurchase,
        'SubAccounts' => $SubAccount,
        'Purchase_CostSum' => $Purchase_CostSum,
        'Purchase_priceSum' => $Purchase_priceSum,
        'Invoice_type' => $Invoice_type,
        'transaction_type' => TransactionType::fromValue($DataPurchaseInvoice->transaction_type)?->label() ?? 'غير معروف',
        'priceInWords' =>  is_numeric($Purchase_priceSum) 
        ? $numberTransformer->toWords($Purchase_priceSum) . ' ' . $curre
        : 'ريال', // القيمة النصية
        // 'accountType' => $accountType,
        'Categorys' => $Categorys,
        'currency' => $currency,
        'warehouses' => $SubName,
        'UserName' => $UserName,
        'accountCla' => $AccountClassName,
    ]);
}



}