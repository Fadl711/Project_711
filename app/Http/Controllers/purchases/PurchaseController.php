<?php

namespace App\Http\Controllers\purchases;

use App\Enum\AccountClass;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\MainAccount;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use App\Models\SubAccount;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    //
    public function bills_purchase_show(){


        return view('invoice_purchases.bills_purchase_show');

    }

    public function create() {
        $Currency_name=Currency::all();
        $products = Product::all();
        $Warehouse=Warehouse::all();
        $mainAccount_supplier=MainAccount::where('AccountClass',AccountClass::SUPPLIER->value)->first();

        if($mainAccount_supplier)
        {
            $allSubAccounts = SubAccount::all();
                return view('Purchases.create', ['AllSubAccounts'=>$allSubAccounts,'mainAccount_supplier'=>$mainAccount_supplier,
                'products' => $products,
                'Currency_name'=>$Currency_name,
                'Warehouse'=>$Warehouse


            ]);
                 }
        return view('Purchases.create');

    }


    public function store(Request $request)
    {

        // // التحقق من البيانات المطلوبة


        $purchaseInvoice =new PurchaseInvoice();
        $purchaseInvoice->Receipt_number = $request->Receipt_number;
        $purchaseInvoice->Total_invoice = !empty($request->Total_invoice) ? $request->Total_invoice : 0;
        $purchaseInvoice->Total_cost = !empty($request->Total_cost) ? $request->Total_cost : 0;
        $purchaseInvoice->User_id =$request->User_id; // تعيين المستخدم الحالي إذا كان فارغاً
        $purchaseInvoice->Payment_type = !empty($request->Payment_type) ? $request->Payment_type_id : null; // يمكن تعيين قيمة null إذا كان فارغاً
        $purchaseInvoice->Supplier_id = $request->Supplier_id;
        $purchaseInvoice->save();
// إعادة البيانات كاستجابة
return response()->json([
    'success' => true,
    'message' => 'تم الحفظ بنجاح',
    'invoice_number' => $purchaseInvoice->id,
    'supplier_id' => $purchaseInvoice->Supplier_id
], 201);
    }


public function storc(Request $request)
{

    // تحقق من صحة الإدخالات وتعيين قيم افتراضية للقيم الفارغة

    Purchase::updateOrCreate(
        ['purchase_invoice_id' => $request->purchase_invoice_id], // condition to check if the invoice exists
        [
            'Product_name' => $request->product_name,
            'Barcode' => $request->Barcode ?? '', // باركود افتراضي إذا كان فارغًا
            'Quantity' => $request->Quantity ?? 0, // تعيين 0 إذا كانت الكمية فارغة
            'Purchase_price' => $request->Purchase_price ?? 0, // تعيين 0 إذا كان سعر الشراء فارغًا
            'Selling_price' => $request->Selling_price ?? 0, // تعيين 0 إذا كان سعر البيع فارغًا
            'Total' => $request->Total ?? 0, // تعيين 0 إذا كان الإجمالي فارغًا
            'Cost' => $request->Cost ?? 0, // تعيين 0 إذا كانت التكلفة فارغة
            'Currency_id' => $request->Currency_id, // تعيين null إذا كان Currency_id فارغًا
            'Supplier_id' => $request->supplier_name ?? null, // تعيين null إذا كان Supplier_id فارغًا
            'User_id' => $request->User_id, // تعيين معرف المستخدم الحالي إذا كان فارغًا
            'Purchase_invoice_id' => $request->purchase_invoice_id, // تعيين null إذا كان Purchase_invoice_id فارغًا
            'Store_id' => $request->Store_id, // تعيين null إذا كان Store_id فارغًا
            'Discount_earned' => $request->Discount_earned ?? 0, // تعيين 0 إذا كان الخصم المكتسب فارغًا
            'Profit' => $request->Profit ?? 0, // تعيين 0 إذا كان الربح فارغًا
            'Exchange_rate' => $request->Exchange_rate ?? 1.0, // تعيين 1.0 كمعدل صرف افتراضي
            'note' => $request->note, // تعيين فارغ إذا كانت الملاحظة فارغة
            'product_id' => $request->product_id // تعيين 0 إذا كانت القيمة فارغة
            ]
        );
        $purchase=Purchase::latest()->first();
        $Purchasesum = Purchase::where('Purchase_invoice_id', $purchase->Purchase_invoice_id)->sum('Total');
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
