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
    public function bills_purchase_show(){


        return view('invoice_purchases.bills_purchase_show');

    }




    public function create() {
        $mainAccount= MainAccount::all();

        $Currency_name=Currency::all();
        $products = Product::all();
        $Warehouse=Warehouse::all();
        $mainAccount_Warehouse=MainAccount::where('AccountClass',AccountClass::STORE->value)->first();

        $mainAccount_supplier=MainAccount::where('AccountClass',AccountClass::SUPPLIER->value)->first();

        if($mainAccount_supplier)
        {
            $allSubAccounts = SubAccount::all();
                return view('Purchases.create',
                 ['AllSubAccounts'=>$allSubAccounts,
                'mainAccount_supplier'=>$mainAccount_supplier,
                'products' => $products,
                'Currency_name'=>$Currency_name,
                'Warehouse'=>$mainAccount_Warehouse,
                'mainAccounts'=>$mainAccount


            ]);

                 }
        return view('Purchases.create',['mainAccounts'=>$mainAccount]);

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
{        $accountingPeriod = ModelsAccountingPeriod::where('is_closed', false)->first();

    $purchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', $request->purchase_invoice_id)->first();

    // تحقق من صحة الإدخالات وتعيين قيم افتراضية للقيم الفارغة
//   $NewPurchase=  Purchase::create(
//         ['
//         purchase_invoice_id' => $request->purchase_invoice_id, // condition to check if the invoice exists

//             'Product_name' => $request->product_name,
//             'Barcode' => $request->Barcode ?? '', // باركود افتراضي إذا كان فارغًا
//             'incoming_quantity' => $request->Quantity ?? 0, // تعيين 0 إذا كانت الكمية فارغة
//             'spent_quantity ' => $request->Quantity ?? 0, // تعيين 0 إذا كانت الكمية فارغة
//             'Purchase_price' => $request->Purchase_price ?? 0, // تعيين 0 إذا كان سعر الشراء فارغًا
//             'Selling_price' => $request->Selling_price ?? 0, // تعيين 0 إذا كان سعر البيع فارغًا
//             'Total' => $request->Total ?? 0, // تعيين 0 إذا كان الإجمالي فارغًا
//             'Cost' => $request->Cost ?? 0, // تعيين 0 إذا كانت التكلفة فارغة
//             'Currency_id' => $request->Currency_id?? null, // تعيين null إذا كان Currency_id فارغًا
//             'Supplier_id' => $request->supplier_name ?? null, // تعيين null إذا كان Supplier_id فارغًا
//             'User_id' => $request->User_id, // تعيين معرف المستخدم الحالي إذا كان فارغًا
//             'Purchase_invoice_id' => $request->purchase_invoice_id, // تعيين null إذا كان Purchase_invoice_id فارغًا
//             'account_debit_id' => $request->account_debitid, // تعيين null إذا كان Store_id فارغًا
//             'account_debit_id' => $request->account_Credit_id, // تعيين null إذا كان Store_id فارغًا
//             'Discount_earned' => $request->Discount_earned ?? 0, // تعيين 0 إذا كان الخصم المكتسب فارغًا
//             'Profit' => $request->Profit ?? 0, // تعيين 0 إذا كان الربح فارغًا
//             'Exchange_rate' => $request->Exchange_rate ?? 1.0, // تعيين 1.0 كمعدل صرف افتراضي
//             'note' => $request->note ?? null, // تعيين فارغ إذا كانت الملاحظة فارغة
//             'product_id' => $request->product_id,
//             'account_debit_id' => $purchaseInvoice->sub_account_id ,

//             'accounting_period_id' => $accountingPeriod->accounting_period_id // تعيين 0 إذا كانت القيمة فارغة
//             ]
//         );
   // التحقق من المدخلات
   $validatedData = $request->validate([
    'Product_name' => 'required|string|max:255',
    'product_id' => 'required|integer',
    'Barcode' => 'required|string|max:100',
    'Purchase_price' => 'nullable|numeric',
    'Selling_price' => 'nullable|numeric',
    'Total' => 'required|numeric',
    'Cost' => 'nullable|numeric',
    'Discount_earned' => 'nullable|numeric',
    'Profit' => 'nullable|numeric',
    'Exchange_rate' => 'nullable|numeric',
    'note' => 'nullable|string',
    'Currency_id' => 'nullable|integer',
    'User_id' => 'required|integer',
    'quantity' => 'required|integer',
    'Purchase_invoice_id' => 'required|integer',
    'accounting_period_id' => 'required|integer',
    'account_id' => 'nullable|integer',
    'transaction_type' => 'required|in:purchase,return,inventory_transfer',
    'warehouse_from_id' => 'nullable|integer',
    'warehouse_to_id' => 'nullable|integer',
    'Supplier_id' => 'required|integer',
]);

// حفظ البيانات
Purchase::create($validatedData);
        $purchase=Purchase::latest()->first();
        $Purchasesum = Purchase::where('Purchase_invoice_id', $purchase->Purchase_invoice_id)->sum('Total');
    //     if($purchaseInvoice->Total_invoice){
    //         if($purchaseInvoice->Payment_type=="نقدا")
    //         {
    //             $dailyEntry = DailyEntrie::updateOrCreate(
    //                 ['account_debit_id' => $purchaseInvoice->sub_account_id, 'accounting_period_id' => $accountingPeriod->accounting_period_id], // الشروط
    //                 [
    //                     'Amount_debit' => $purchaseInvoice->debtor_amount ?: 0,
    //                     'Amount_Credit' => $purchaseInvoice->creditor_amount ?: 0,
    //                     'account_Credit_id' => $purchaseInvoice->sub_account_id,
    //                     'Statement' => 'إدخال رصيد افتتاحي',
    //                     'Daily_page_id' => 2,
    //                     'Currency_name' => 'ر',
    //                     'User_id' => 1,
    //                     'Invoice_type' => 'رصيد افتتاحي',
    //                     'Invoice_id' => null,
    //                     'status_debit' => 'غير مرحل',
    //                     'status' => 'غير مرحل',
    //                 ]
    //             );

    //         }


    // }

    return response()->json([
        'success' =>true,'message'=> 'تم الحفظ بنجاح',
        'purchase' => $purchase,
        'Purchasesum'=>$Purchasesum
        ],201 );
}


 private function convertArabicNumbersToEnglish($value)
    {
        $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($arabicNumbers, $englishNumbers, $value);
    }

// في ProductController
public function search(Request $request)
{
    $id = $request->query('id');
     // الحصول على id المنتج من الطلب
    $productData = Product::where('product_id', $id)->first(); // البحث عن المنتج باستخدام id

    if ($productData) {
        $product = [
            'product_name' => $productData->product_name,
            'Barcode' => $productData->Barcode, // لا حاجة لتحويله هنا
            'Selling_price' => $productData->Selling_price, // لا حاجة لتحويله هنا
            'Purchase_price' => $productData->Purchase_price, // لا حاجة لتحويله هنا
            'Categorie_id' => $productData->Categorie_id, // لا حاجة لتحويله هنا
            'Quantity' => $productData->Quantity, // لا حاجة لتحويله هنا
        ];

        // تحويل الأرقام العربية إلى إنجليزية
        $product['Barcode'] = $this->convertArabicNumbersToEnglish($product['Barcode']);
        $product['Selling_price'] = $this->convertArabicNumbersToEnglish($product['Selling_price']);
        $product['Purchase_price'] = $this->convertArabicNumbersToEnglish($product['Purchase_price']);
        $product['Categorie_id'] = $this->convertArabicNumbersToEnglish($product['Categorie_id']);
        $product['Quantity'] = $this->convertArabicNumbersToEnglish($product['Quantity']);

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
