<?php

namespace App\Http\Controllers\purchases;

use App\Enum\AccountClass;
use App\Enum\AccountType;
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
use App\Models\DailyEntrie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;//+

use function PHPUnit\Framework\isNull;

class PurchaseController extends Controller
{
    //
    public function bills_purchase_show()
    {
        return view('invoice_purchases.bills_purchase_show');
    }
  



    public function create() {
        $mainAccount= MainAccount::all();
        $latestInvoice1 = PurchaseInvoice::latest('purchase_invoice_id')->first();

        $Currency_name=Currency::all();
        $products = Product::all();
        $mainAccount_Warehouse=MainAccount::where('AccountClass',AccountClass::STORE->value)->first();
        if ($mainAccount_Warehouse) 
        {
            $subAccount=SubAccount::where('Main_id',$mainAccount_Warehouse->main_account_id)->get();

        }
        else
        {
            $subAccount=null; 
        }
        $mainAccount_supplier=MainAccount::where('AccountClass',AccountClass::SUPPLIER->value)->first();
        if(  $latestInvoice1)
        {
            $latestInvoices=   $latestInvoice1->purchase_invoice_id;

        }
 
       

            $allSubAccounts = SubAccount::all();
                return view('Purchases.create',
                 ['AllSubAccounts'=>$allSubAccounts,
                'mainAccount_supplier'=>$mainAccount_supplier,
                'products' => $products,
                'Currency_name'=>$Currency_name,
                'Warehouse'=>$subAccount,
                'mainAccounts'=>$mainAccount,


            ]);

                 
        return view('Purchases.create');

    }

    public function getMainAccounts(Request $request,$id)
    {
        // $mainAccountId = $request->input('Payment_type');

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
            'Payment_type' => 'required|string',
            'transaction_type' => 'required|string',
            'mainaccount_debit_id' => 'required|numeric',
            'Supplier_id' => 'required|numeric',
        ], [
            'Payment_type.required' => 'حقل نوع الدفع مطلوب.',
            'transaction_type.required' => 'حقل نوع المعاملة مطلوب.',
            'Supplier_id.required' => 'حقل اسم المورد مطلوب.',
            'mainaccount_debit_id.required' => 'حقل حساب التصدير مطلوب.',
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
        if ($request->account_debitid === $request->sub_account_debit_id) {
            return response()->json([
                'success' => false,
                'message' => 'يجب عليك تحديد مخازن مختلفة.'
            ]);
        }
    
        // التحقق من وجود المنتج في النظام
        $product = Product::where('product_name', $request->product_name)->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'هذا المنتج غير موجود في النظام. يجب عليك إضافته من صفحة المنتجات.'
            ]);
        }
    
        // التحقق من صحة الحقول
        $validator = Validator::make($request->all(), [
            'account_debitid' => 'required',
            'sub_account_debit_id' => 'required',
            'Quantity' => 'required|numeric|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
    
        // الحصول على الفترة المحاسبية المفتوحة
        $accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد فترة محاسبية مفتوحة.'
            ]);
        }
    
        // التحقق من وجود الفاتورة
        $purchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', $request->purchase_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->first();
        if (!$purchaseInvoice) {
            return response()->json([
                'success' => false,
                'message' => 'الفاتورة غير موجودة.'
            ], 404);
        }
    
        // تعيين الحسابات والمستودعات بناءً على نوع العملية
        $transactionType = $purchaseInvoice->transaction_type;
        $warehouse_to_id = $warehouse_from_id = $account_id = null;
    
        switch ($transactionType) {
            case 1: // عملية دخول المخزون
                $warehouse_to_id = $request->account_debitid;
                $account_id = $request->sub_account_debit_id;
                $warehouse_from_id = null;
                break;
            case 2: // عملية خروج المخزون
                $warehouse_from_id = $request->account_debitid;
            $account_id = $request->sub_account_debit_id;
            $warehouse_to_id = null;
                break;
            case 3: // عملية تحويل المخزون
                if ($request->account_debitid === $request->sub_account_debit_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'يجب عليك تحديد مخازن مختلفة.'
                    ]);
                }
                $warehouse_to_id = $request->account_debitid;
                $warehouse_from_id = $request->sub_account_debit_id;
                $account_id = null;

                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'نوع العملية غير معروف.'
                ]);
        }
    
        // التحقق من فئة الحساب
        $subAccount = SubAccount::where('sub_account_id', $request->sub_account_debit_id)->first();
        if (($transactionType == 3 && $subAccount->AccountClass != 3) ||
            ($transactionType == 1 && $subAccount->AccountClass == 3)) {
            return response()->json([
                'success' => false,
                'message' => $transactionType == 3 
                    ? 'نوع العملية غير معروف.' 
                    : 'يجب عليك تحديد حساب التصدير الدائن آخر غير حساب المخازن لإنك تقوم بعملية شراء.'
            ]);
        }
    
        // إنشاء أو تحديث سجل الشراء
        $purchase = Purchase::updateOrCreate(
            [
                'purchase_id' => $request->purchase_id,
                'Purchase_invoice_id' => $purchaseInvoice->purchase_invoice_id,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
            ],
            [
                'Product_name' => $request->product_name,
                'Barcode' => $request->Barcode ?? '',
                'quantity' => $request->Quantity,
                'Purchase_price' => $request->Purchase_price,
                'Selling_price' => $request->Selling_price,
                'Total' => $request->Total,
                'Cost' => $request->Cost,
                'Currency_id' => $purchaseInvoice->Currency_id,
                'Supplier_id' => $purchaseInvoice->Supplier_id ?? null,
                'User_id' => $request->User_id ?? auth()->id(),
                'warehouse_to_id' => $warehouse_to_id,
                'warehouse_from_id' => $warehouse_from_id,
                'Discount_earned' => $request->Discount_earned ?? 0,
                'Profit' => $request->Profit ?? 0,
                'Exchange_rate' => $request->Exchange_rate ?? 1.0,
                'note' => $request->note ?? '',
                'product_id' => $request->product_id,
                'account_id' => $account_id,
                'transaction_type' => $transactionType,
                'categorie_id' => $request->Categorie_name,
            ]
        );
    
        // تحديث الفاتورة
        try {
            $Purchasesum = $purchase->sum('Total');
            $Invoice_type = $request->Payment_type;
            $pamyment = $Invoice_type === "نقدا" ? $Purchasesum : 0;
    
            $purchaseInvoice->update([
                'Invoice_type' => $Invoice_type,
                'Receipt_number' => $request->Receipt_number,
                'Total_invoice' => $Purchasesum,
                'Paid' => $pamyment,
                'Currency_id' => $request->Currency_id,
                'Total_cost' => $request->Total_cost,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'تم الحفظ بنجاح وتحديث الفاتورة.',
                'purchase' => $purchase,
                'Purchasesum' => $Purchasesum,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحديث.'
            ]);
        }
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
    
    // التحقق من وجود المنتج
    $productData = Product::where('product_id', $id)
    ->orWhere('Barcode',$id)

    ->first();
    if (!$productData) {
        return response()->json(['success' => false, 'message' => 'المنتج غير موجود'], 404);
    }
    
    // حساب الكميات بناءً على نوع المعاملة والمخزن
    $Purchase_warehouse_to_id = Purchase::where('product_id', $id)
        ->where('warehouse_to_id', $warehouse_to_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->where('transaction_type', 1)
        ->sum('quantity');
    
    $warehouseFromid = Purchase::where('product_id', $id)
        ->where('warehouse_from_id', $warehouse_to_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->where('transaction_type', 2)
        ->sum('quantity');
    
    $warehouse_Fromid = Purchase::where('product_id', $id)
        ->where('warehouse_from_id', $warehouse_to_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->where('transaction_type', 3)
        ->sum('quantity');

        $warehouse_Fromid2 = Purchase::where('product_id', $id)
        ->where('warehouse_to_id', $warehouse_to_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->where('transaction_type', 3)
        ->sum('quantity');
 // جلب الفئات مع ID
 $categories = Category::where('product_id', $id)
 ->select('categorie_id', 'Categorie_name')
 ->get();        // حساب الكمية النهائية المتاحة في المخزن
    $productPurchase = $Purchase_warehouse_to_id - $warehouseFromid - $warehouse_Fromid+$warehouse_Fromid2;
    

    if ($productData) {
        $product = [
            'product_name' => $productData->product_name,
            'Barcode' => $productData->Barcode, // لا حاجة لتحويله هنا
            'Selling_price' => $productData->Selling_price, // لا حاجة لتحويله هنا
            'Purchase_price' => $productData->Purchase_price, // لا حاجة لتحويله هنا
            'Categorie_id' => $productData->Categorie_id, // لا حاجة لتحويله هنا
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
    Purchase::where('purchase_id',$id)->delete();
    return response()->json(['message' => 'تم حذف البيانات بنجاح']);
}


public function print($id) {
    $DataPurchaseInvoice = PurchaseInvoice::where('purchase_invoice_id',  $id)->first();
    $accountType=TransactionType::cases();
    $DataPurchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', $id)->first();
    $SubAccount = SubAccount::where('sub_account_id', $DataPurchaseInvoice->Supplier_id)->get();
    $DataPurchase = Purchase::where('Purchase_invoice_id', $id)->get();
    $Categorys = Category::all();

    $Purchase_priceSum = Purchase::where('purchase_invoice_id', $id)->sum('Total');
    $Purchase_CostSum = Purchase::where('purchase_invoice_id', $id)->sum('Cost');

    return view('invoice_purchases.bills_purchase_show', [
        'DataPurchaseInvoice' => $DataPurchaseInvoice,
        'DataPurchase' => $DataPurchase,
        'SubAccounts' => $SubAccount,
        'Purchase_CostSum' => $Purchase_CostSum,
        'Purchase_priceSum' => $Purchase_priceSum,
        'accountType' =>  $accountType,
        'Categorys' =>  $Categorys,

    ]);
}
public function saveAndPrint(Request $request)
{

    $DataPurchaseInvoice = PurchaseInvoice::where('purchase_invoice_id',  $request->purchase_invoice_id)->first();
    if(!$DataPurchaseInvoice ){
        return response()->json([
           'success' => 'لا توجد فاتورة موجودة!',
        ]);
    }
    return response()->json([
        'success' => 'تم الحفظ بنجاح!',
        'dailyEntrie' => $DataPurchaseInvoice ]);
}
public function getPurchasesByInvoice(Request $request)
{
    $invoiceId = $request->input('purchase_invoice_id'); // الحصول على purchase_invoice_id من الطلب
// جلب الفاتورة بناءً على المعرف
$user_id = auth()->id();

// التحقق من وجود الفاتورة السابقة
$previousInvoice = PurchaseInvoice::where('User_id', $user_id)
                    ->where('purchase_invoice_id', '=', $invoiceId)
                    ->orderBy('purchase_invoice_id', 'desc')
                    ->first();

// جلب المشتريات بناءً على المعرف
$purchases = Purchase::where('User_id', $user_id)
                    ->where('purchase_invoice_id', '=', $invoiceId)
                    ->orderBy('purchase_invoice_id', 'desc')
                    ->get();

// إرجاع المشتريات مع الفاتورة السابقة بتنسيق JSON
return response()->json($purchases
);
}
public function deleteInvoice($id)
{
    try {
      
        $invoice = PurchaseInvoice::where('purchase_invoice_id', $id)->first();
        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على معرف الفاتورة.'
            ]);        }

        // حذف جميع المشتريات المرتبطة إن وجدت
        if ($invoice->purchases()->exists()) {
            $invoice->purchases()->delete();
        }

        // حذف الفاتورة نفسها
        $invoice->delete();

        // DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'تم حذف الفاتورة وجميع المشتريات المرتبطة بها بنجاح'
        ]);

    } catch (\Exception $e) {
        // DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()
        ]);
    }
}

}