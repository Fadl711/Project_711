<?php

namespace App\Http\Controllers\purchases;

use App\Enum\AccountClass;
use App\Http\Controllers\Controller;
use App\Models\DefaultSupplier;
use App\Models\MainAccount;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    //
    public function bills_purchase_show(){
    
        
        return view('invoice_purchases.bills_purchase_show');
    
    }
    
    public function create() {
        // $ff="ff";  
     
        $products = Product::all(); // جلب جميع المنتجات

        $mainAccount_supplier=MainAccount::where('AccountClass',AccountClass::SUPPLIER->value)->first();
        if($mainAccount_supplier)
        {
            $allSubAccounts = SubAccount::all();
           
                // $subAccounts = SubAccount::where('Main_id', $mainAccount->main_account_id)->get();
                // dd($subAccounts);
                return view('Purchases.create', ['AllSubAccounts'=>$allSubAccounts,'mainAccount_supplier'=>$mainAccount_supplier,'products' => $products]);
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
'invoice_number' => $purchaseInvoice->id
], 201);

    }
    
    public function searchProdact(Request $request)
    {   
        $query = $request->input('query');
        $subAccounts = SubAccount::where('sub_account_id', '!=', null)
                                 ->where('sub_name', 'LIKE', "%{$query}%")
                                 ->Orwhere('sub_account_id', 'LIKE', "%{$query}%") ->get();
   return response()->json($subAccounts);
                                                 
                                                  
}
public function list() {
    
    $products = Product::all(); // جلب جميع المنتجات

    return response()->json(['products' => $products]);
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
}
