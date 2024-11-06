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
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $Product_name=$request->product_name;
        $product = Product::where('product_name', $Product_name)->first();

       if (!$product) {
    return response()->json([
        'success' => false,
        'message' => 'هذا المنتج غير موجود في النظام. يجب عليك إضافته من صفحة المنتجات.'
    ]);


        }  
           
            if ($request->account_debitid == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب عليك تحديد حساب مخزن.'
                ],201);
            }
        if ($request->sub_account_debit_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'يجب عليك تحديد حساب الدائن.'
            ],201);
        }
        if ($request->Quantity == null) {
            return response()->json([
                'success' => false,
                'message' => 'يجب عليك تحديد الكمية المواد.'
            ]);
        }
     
        // الحصول على الفترة المحاسبية المفتوحة
        $accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();
        
        // التحقق من وجود الفاتورة
        $purchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', $request->purchase_invoice_id)->first();
        if (!$purchaseInvoice) {
            return response()->json([
                'success' => false,
                'message' => 'الفاتورة غير موجودة.'
            ], 201);
        }
    
        // تعيين الحسابات والمستودعات حسب نوع العملية
        // $account_id = $request->sub_account_debit_id;
      
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
                ], 201);
            } 
                $warehouse_from_id= $request->account_debitid;
                $warehouse_to_id = $request->sub_account_debit_id;
                $account_id = null;
        }    
        // إنشاء سجل الشراء الجديد
        
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        if($request->purchase_id)
        {
         
            $purchase = Purchase::where('purchase_id', $request->purchase_id)->first();
            $NewPurchase = Purchase::updateOrCreate(
                [
                    'created_at' => $purchase->created_at,
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'purchase_id' => $request->purchase_id,
                ],
                [
                'Purchase_invoice_id' => $purchaseInvoice->purchase_invoice_id,
                'Product_name' => $request->product_name,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
                'Barcode' => $request->input('Barcode', ''),
                'quantity' => $request->input('Quantity', 0),
                'Purchase_price' => $request->input('Purchase_price', 0),
                'Selling_price' => $request->input('Selling_price', 0),
                'Total' => $request->input('Total', 0),
                'Cost' => $request->input('Cost', 0),
                'Currency_id' => $request->input('Currency_id', null),
                'Supplier_id' => $purchaseInvoice->Supplier_id ?? null,
                'User_id' => $request->input('User_id', auth()->id()),
                'warehouse_to_id' => $warehouse_to_id,
                'warehouse_from_id' => $warehouse_from_id,
                'Discount_earned' => $request->input('Discount_earned', 0),
                'Profit' => $request->input('Profit', 0),
                'Exchange_rate' => $request->input('Exchange_rate', 1.0),
                'note' => $request->input('note', ''),
                'product_id' => $request->product_id,
                'account_id' => $account_id,
                'transaction_type' => $purchaseInvoice->transaction_type
            ]);
        
            $Purchasesum = Purchase::where('purchase_invoice_id', $purchaseInvoice->purchase_invoice_id)->sum('Total');
            
        
            return response()->json([
                'success' => true,
                'message' => 'تم التحديث بنجاح',
                'purchase' => $NewPurchase,
                'Purchasesum' => $Purchasesum,
                'created_at' => $currentDateTime,
            ], 201);
      
    }
    else {

$NewPurchase = Purchase::create(
    [
        'Purchase_invoice_id' => $purchaseInvoice->purchase_invoice_id,
        'Product_name' => $request->product_name,
        'purchase_id' => $request->purchase_id ,
        'accounting_period_id' => $accountingPeriod->accounting_period_id,
        'Barcode' => $request->Barcode ?? '',
        'quantity' => $request->Quantity ?? 0,
        'Purchase_price' => $request->Purchase_price ?? 0,
        'Selling_price' => $request->Selling_price ?? 0,
        'Total' => $request->Total ?? 0,
        'Cost' => $request->Cost ?? 0,
        'Currency_id' => $request->Currency_id ?? null,
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
        'transaction_type' => $purchaseInvoice->transaction_type
    ]
);
    }
    try {
        $Purchasesum = Purchase::where('purchase_invoice_id', $purchaseInvoice->purchase_invoice_id)->sum('Total');
        // $PurchaseInvoice = Purchase::where('purchase_invoice_id', $purchaseInvoice->purchase_invoice_id)->sum('Total');
      PurchaseInvoice::where('purchase_invoice_id', $purchaseInvoice->purchase_invoice_id)
        ->update([
            'Invoice_type' => $request->Payment_type,
            'Receipt_number' => $request->Receipt_number,
            'Total_invoice' => $Purchasesum,
            // 'Total_cost' => $request->Total_cost,
           
        ]);
    
    
        return response()->json([
            'success' => true,
            'message' => 'تم الحفظ بنجاح والتحديث الفاتوره',
            'purchase' => $NewPurchase,
            'Purchasesum' => $Purchasesum,
            'created_at' => $currentDateTime,
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء التحديث.']);
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


public function print($id) {
    $DataPurchaseInvoice = PurchaseInvoice::where('purchase_invoice_id',  $id)->first();
 
    $mainc = MainAccount::all();
    $suba = SubAccount::all();
    $DataPurchase = Purchase::where('Purchase_invoice_id', $id)->get();
    $DataPurchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', $id)->first();

    return view('invoice_purchases.bills_purchase_show', [
        'DataPurchaseInvoice' => $DataPurchaseInvoice,
        'DataPurchase' => $DataPurchase,
        'suba' => $suba
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
}