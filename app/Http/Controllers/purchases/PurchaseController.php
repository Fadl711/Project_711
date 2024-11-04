<?php

namespace App\Http\Controllers\purchases;

use App\Enum\AccountClass;
use App\Enum\AccountType;
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
use App\Models\DailyEntrie;
use Illuminate\Http\Request;

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
$subAccount=SubAccount::where('Main_id',$mainAccount_Warehouse->main_account_id)->get();
        $mainAccount_supplier=MainAccount::where('AccountClass',AccountClass::SUPPLIER->value)->first();
        if(  $latestInvoice1)
        {
            $latestInvoices=   $latestInvoice1->purchase_invoice_id;

        }
 
        if($mainAccount_supplier)
        {

            $allSubAccounts = SubAccount::all();
                return view('Purchases.create',
                 ['AllSubAccounts'=>$allSubAccounts,
                'mainAccount_supplier'=>$mainAccount_supplier,
                'products' => $products,
                'Currency_name'=>$Currency_name,
                'Warehouse'=>$subAccount,
                'mainAccounts'=>$mainAccount,


            ]);

                 }
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
        $accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();
        $purchaseInvoice = new PurchaseInvoice();
        $purchaseInvoice->Receipt_number = $request->Receipt_number ??0;
        $purchaseInvoice->Total_invoice = $request->Total_invoice ?? 0;
        $purchaseInvoice->Total_cost = $request->Total_cost ?? 0;
        $purchaseInvoice->User_id = $request->User_id ?? auth()->id();
        $purchaseInvoice->accounting_period_id = $accountingPeriod->accounting_period_id ;
        $purchaseInvoice->Invoice_type = $request->Payment_type ?? null;
        $purchaseInvoice->Supplier_id = $request->Supplier_id;
        $purchaseInvoice->transaction_type = $request->transaction_type;
    
        try {
            $purchaseInvoice->save();
            return response()->json([
                'success' => true,
                'message' => 'تم الحفظ بنجاح',
                'invoice_number' => $purchaseInvoice->id,
                'supplier_id' => $purchaseInvoice->Supplier_id
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ أثناء الحفظ: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function storc(Request $request)
    {
        // الحصول على الفترة المحاسبية المفتوحة
        $accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();
        
        // التحقق من وجود الفاتورة
        $purchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', $request->purchase_invoice_id)->first();
        if (!$purchaseInvoice) {
            return response()->json([
                'success' => false,
                'message' => 'الفاتورة غير موجودة.'
            ], 404);
        }
    
        // تعيين الحسابات والمستودعات حسب نوع العملية
        $account_id = $request->sub_account_debit_id;
        $classs=3;
        $mainAccount = MainAccount::where('AccountClass',$classs )->first();
    
        $getwarehouse_from_id = SubAccount::where('sub_account_id', $request->sub_account_debit_id)->first();
        $getwarehouse_to_id = SubAccount::where('sub_account_id', $request->main_account_debit_id)->first();
    
        // تحديد المستودعات بناءً على نوع العملية
        if ($purchaseInvoice->transaction_type == 1) {
            $warehouse_to_id = $request->account_debitid;
            $account_id = $request->sub_account_debit_id;
            $warehouse_from_id = null;
        } 
        if ($purchaseInvoice->transaction_type == 2) {
            $warehouse_from_id = $request->account_debitid;
            $account_id = $request->sub_account_debit_id;
            $warehouse_to_id = null;
        } 
        if ($purchaseInvoice->transaction_type == 3) {
          
            if ($request->account_debitid == $request->sub_account_debit_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب عليك تحديد مخازن مختلفة.'
                ], 400);
            } 
                $warehouse_from_id= $request->account_debitid;
                $warehouse_to_id = $request->sub_account_debit_id;
                $account_id = null;
            
        

         

           
        }
        // إنشاء سجل الشراء الجديد
        $NewPurchase = Purchase::create([
            'Purchase_invoice_id' => $purchaseInvoice->purchase_invoice_id,
            'Product_name' => $request->product_name,
            'Barcode' => $request->Barcode ?? '',
            'quantity' => $request->Quantity ?? 0, // تعديل الحرف الأول ليتطابق مع fillable
            'Purchase_price' => $request->Purchase_price ?? 0,
            'Selling_price' => $request->Selling_price ?? 0,
            'Total' => $request->Total ?? 0,
            'Cost' => $request->Cost ?? 0,
            'Currency_id' => $request->Currency_id ?? null,
            'Supplier_id' => $request->supplier_name ?? null,
            'User_id' => $request->User_id ?? auth()->id(),
            'warehouse_to_id' => $warehouse_to_id,
            'warehouse_from_id' => $warehouse_from_id,
            'Discount_earned' => $request->Discount_earned ?? 0,
            'Profit' => $request->Profit ?? 0,
            'Exchange_rate' => $request->Exchange_rate ?? 1.0,
            'note' => $request->note ?? '',
            'product_id' => $request->product_id,
            'account_id' => $account_id,
            'accounting_period_id' => $accountingPeriod->accounting_period_id ?? null,
            'transaction_type' => $purchaseInvoice->transaction_type // إضافة transaction_type إذا كان ضروريًا
        ]);
    
        // حساب مجموع المشتريات بناءً على الفاتورة
        $Purchasesum = Purchase::where('purchase_invoice_id', $purchaseInvoice->purchase_invoice_id)->sum('Total');
        // $NewPurchase = Purchase::where('purchase_invoice_id', $purchaseInvoice->purchase_invoice_id)->get();

    
        return response()->json([
            'success' => true,
            'message' => 'تم الحفظ بنجاح',
            'purchase' => $NewPurchase,
            'Purchasesum' => $Purchasesum
        ], 201);
    }
    
 private function convertArabicNumbersToEnglish($value)
    {
        $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($arabicNumbers, $englishNumbers, $value);
    }

// في ProductController
public function search(Request $request)
{$accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();
    if (!$accountingPeriod) {
        return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة'], 404);
    }
    
    // الحصول على id المنتج من الطلب
    $id = $request->query('id');
    $warehouse_to_id = $request->account_debitid;
    
    // التحقق من وجود المنتج
    $productData = Product::where('product_id', $id)->first();
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
    
    
    // حساب الكمية النهائية المتاحة في المخزن
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
}
