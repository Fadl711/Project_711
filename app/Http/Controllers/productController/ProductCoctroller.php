<?php

namespace App\Http\Controllers\productController;

use App\Enum\AccountClass;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SubAccount;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class ProductCoctroller extends Controller
{
    public function index(){
        $Warehouses=Warehouse::all();
        $curr=Currency::all();
        $prod=Product::all();
        return view('products.index',['prod'=>$prod,'curr'=>$curr,'Warehouses'=>$Warehouses]);
    }
    public function create(){
        $curr=Currency::all();
        $Warehouses=Warehouse::all();
        return view('products.create',['curr'=>$curr,'Warehouses'=>$Warehouses]);
    }
    private function convertArabicNumbersToEnglish($value)
    {
        $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($arabicNumbers, $englishNumbers, $value);
    }
    public function print(Request $request,$id)
    {
   // التحقق من المدخلات
   $validated = $request->validate([
    'warehouseid' => 'nullable|',
    // 'dateList' => 'nullable|',
    'productname' => 'nullable|',
    // 'accountingPeriodData' => 'nullable|',
    'Quantit' => 'nullable|',
    'DisplayMethod' => 'nullable|string|max:255',
]);
        $warehouse_to_id = $validated['warehouseid'];
        // $accountingPeriod =$validated['accountingPeriodData'];
        $warehouse_to_id = $this->convertArabicNumbersToEnglish($warehouse_to_id);
        $productname = $validated['productname'];
        $productname = $this->convertArabicNumbersToEnglish($productname);
        $Quantit = $validated['Quantit'];
        $DisplayMethod = $validated['DisplayMethod'];

        if ($Quantit=== "QuantityCostsSupplier")
        {
            return $this->QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id,$productname,$Quantit,$DisplayMethod  );
        }
        if ($Quantit === "QuantitySupplier")
        {
            return $this->QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id,$productname,$Quantit,$DisplayMethod  );
        }
        
        if ($validated['Quantit'] === "QuantityCosts")
        {
            return $this->ShowAllProducts($warehouse_to_id,$productname,$Quantit,$DisplayMethod  );

        }
        if ($validated['Quantit'] === "Incomplete")
        {
            return $this->ShowAllProducts($warehouse_to_id,$productname,$Quantit,$DisplayMethod  );

        }

        if ($validated['Quantit'] === "Quantityonly") 
        {
            return $this->ShowAllProducts($warehouse_to_id,$productname,$Quantit,$DisplayMethod  );

        }
        if ($validated['Quantit'] === "inventoryList") 
        {
            return $this->ShowAllProducts($warehouse_to_id,$productname,$Quantit,$DisplayMethod  );
        }

    if ($validated['DisplayMethod'] === "ShowAllProducts") 
    {
      
        if ($validated['Quantit'] === "QuantityCosts")
        {
            return $this->QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id,$productname,$Quantit,$DisplayMethod  );

        }
    }
    if ($validated['DisplayMethod'] === "SelectedProduct") 
    {
      
        if ($validated['Quantit'] === "QuantityCosts")
        {
            return $this->Quantityonly($warehouse_to_id,$productname,$Quantit,$DisplayMethod );
            
        }
      


    } 


    }
  
    public function ShowAllProducts( $warehouse_to_id,$productname,$Quantit,$DisplayMethod )
    {
                    //    dd($Quantit);
                    // $Myanalysis="الكمية والتكاليف من تاريخ";


        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if($Quantit=="QuantityCosts")
        {
            $Myanalysis="الكمية والتكاليف من تاريخ";
        }
        if($Quantit=="Quantityonly")
        {
            $Myanalysis="للكمية فقط  من تاريخ ";
        }
        if($Quantit=="inventoryList")
        {
            $Myanalysis=" امر جرد  ";
        }
        $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');

        if($DisplayMethod =="ShowAllProducts")
            {
                $productname=null;
            }
            if($DisplayMethod =="ShowAllProducts")
            {
            // $uniqueProducts = Purchase::where('warehouse_to_id', $warehouse_to_id)
            // ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            // ->where(function ($query) {
            //     $query->whereIn('transaction_type', [1, 6, 3,7]);

            // })
            // ->select('product_id', 'Product_name') // اختيار الأعمدة المطلوبة
            // ->distinct() // التأكد من جلب القيم المميزة
            // ->get();// جلب النتائج كمجموعة بيانات
            $uniqueProducts = Product::all();

        }
        if( $DisplayMethod=="SelectedProduct")
        {
            $uniqueProduct=[] ;
            $productname = explode(',', $productname);
            //    dd($productname);
                foreach ($productname as $produ) 
                {
            // $uniqueProduc = Purchase::where('warehouse_to_id', $warehouse_to_id)
            // ->where('product_id', $produ)
            // ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            // ->select('product_id', 'Product_name','Quantityprice') // اختيار الأعمدة المطلوبة
            // ->distinct() 
            // ->get();
            $uniqueProduc = Product::where('product_id',  $produ)->get();
            $product = Product::where('product_id',  $produ)->first();
             $uniqueProducts[] = [
                        'product_id' => $product->product_id,
                    ];
        }
                        
        }
        $QuantityIncomplete = []; // تخزين المنتجات

        $allQuantityonly = []; 
        // تخزين المنتجات
        $allQuantityCosts = []; // تخزين المنتجات
        $QuantityCosts1 = []; // تخزين المنتجات
        $inventoryList = []; // تخزين المنتجات
        $inventoryList1=[] ;

        foreach ($uniqueProducts as $products) {
            if( $DisplayMethod=="SelectedProduct")
            {
            $product_id = intval($products['product_id']);        
            }
            else
            {
                $product_id=$products->product_id ?? $productname;
            }

            $product = Product::where('product_id',  $product_id)->first();
            if( $product)
            {
            $categories = Category::where('product_id', $product_id)->first();  
                      // حساب الكميات بناءً على نوع المعاملة والمخزن
          // حساب الكميات بناءً على نوع المعاملة والمخزن
          $purchaseToQuantity = Purchase::where('product_id', $product_id)
          ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
          ->where('warehouse_to_id', $warehouse_to_id)
          ->whereIn('transaction_type', [1, 6, 3,7])
          ->sum('quantity');

          $warehouseFromQuantity = Purchase::where('product_id', $product_id)
              ->where('warehouse_from_id', $warehouse_to_id)
              ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
              ->where('transaction_type', 2)
              ->sum('quantity');
             
          $warehouseFromQuantity3 = Purchase::where('product_id', $product_id)
              ->where('warehouse_from_id', $warehouse_to_id)
              ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
              ->where('transaction_type', 3)
              ->sum('quantity');
  
          $saleQuantity5 = Sale::where('product_id', $product_id)
              ->where('warehouse_to_id', $warehouse_to_id)
              ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
              ->where('transaction_type', 5)
              ->sum('quantity');
  
          $saleQuantity4 = Sale::where('product_id', $product_id)
              ->where('warehouse_to_id', $warehouse_to_id)
              ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
              ->where('transaction_type', 4)
              ->sum('quantity');
             

          $productPurchase =( $purchaseToQuantity+$saleQuantity5 )- $warehouseFromQuantity - $warehouseFromQuantity3- $saleQuantity4 ;
          $InventoryQuantity=Inventory::where('product_id',$product_id)
          ->where('StoreId', $warehouse_to_id)
          ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
          ->sum('quantity');
          $QuantityDifference= $productPurchase-$InventoryQuantity;
        
       
            if($Quantit=="QuantityCosts")
           {
            if (!in_array($product_id, $QuantityCosts1))
            {

            
                $QuantityCosts1[] = $product_id;
        $allQuantityCosts[] = [
            '$accountingPeriod' => $accountingPeriod->created_at,
            'product_id' => $product_id,
            'product_name' => $product->product_name,
            'Purchase_price' => $product->Purchase_price,
            'note' => $product->note,
            'categories' => $categories,
            'warehouse_name' => $warehouseName,
            'SumQuantity' => $productPurchase,
            'Myanalysis' => $Myanalysis,
        ];
        }
        }
        if($Quantit=="inventoryList")
        {
            if (!in_array($product_id, $inventoryList1))
            {

            
                $inventoryList1[] = $product_id;
         $accountingPerio = Carbon::today()->format('Y-m-d');

         $inventoryList[] = [
             'product_id' => $product_id,
             'product_name' => $product->product_name,
             'note' => $product->note,
             'categories' => $categories,
             'warehouse_name' => $warehouseName,
             'SumQuantity' => $productPurchase,
             '$accountingPeriod' => $accountingPerio,
         ];
        }

     }  
          
             $allQuantityonly[] = [
                'product_id' => $product_id,
                'product_name' => $product->product_name,
                'note' => $product->note,
                'categories' => $categories,
                'warehouse_name' => $warehouseName,
                'SumQuantity' => $productPurchase,
                '$accountingPeriod' => $accountingPeriod->created_at,
                'Myanalysis' => $Myanalysis,


            ];

           
        }
        }
    // dd($QuantityIncomplete);
    if($DisplayMethod =="ShowAllProducts")
    {
        $productname=" تقرير  المخزني   لكل الاصناف في مخزن  "."  ".$warehouseName;
        
    }
if($DisplayMethod =="SelectedProduct")
    {
        $productname="تقرير  المخزني لصنف :  ".$product->product_name." /في المخزن: ".$warehouseName;
        
    }
    
     
    $accountingPeriod = $accountingPeriod->created_at;

$accountingPeriod = Carbon::now()->format('Y-m-d');
if($Quantit=="Incomplete")
{
    // dd($inventoryData);

 return view('report.print', compact('QuantityIncomplete','productname','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
}
    if($Quantit=="QuantityCosts")
    {
     return view('report.print', compact('allQuantityCosts','productname','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
    }
    if($Quantit=="inventoryList")
    {
        if($DisplayMethod =="ShowAllProducts")
    {
        $productname="امر جرد لكل الاصناف  "." في المخزن: ".$warehouseName;
        
    }
        if($DisplayMethod =="SelectedProduct")
    {
        $productname="امر جرد للاصناف  المذكورة في الجدول  "." /في المخزن: ".$warehouseName;
        
    }
    $toDate = now()->toDateString();
    
    $Myanalysis=" امر جرد  ";
    
     return view('inventory.print', compact('inventoryList','productname','toDate','accountingPeriod','Myanalysis','warehouseName'))->render(); // إرجاع المحتوى كـ HTML
    }
    if($Quantit=="Quantityonly")
    {
     return view('report.print', compact('allQuantityonly','productname','accountingPeriod','Myanalysis'))->render(); // إرجاع المحتوى كـ HTML
    }
   
    }
    public function QuantityAndCostsAccordingToSuppliersMovement( $warehouse_to_id,$productname,$Quantit,$DisplayMethod )
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');
        $Suppliers = SubAccount::where('AccountClass',2)->get();
        if($Quantit=="QuantityCostsSupplier")
        {
            $Myanalysis="الكمية والتكاليف حسب حركة الموردين من تاريخ ";
        }
        if($Quantit=="QuantitySupplier")
        {
            $Myanalysis="للكمية  حسب حركة الموردين من تاريخ "; 
        }
        if( $DisplayMethod=="ShowAllProducts")
        {
            if($DisplayMethod =="ShowAllProducts")
            {
                $productname=null;
            }
            $uniqueProducts = Product::all();

            // $uniqueProducts = Purchase::where('warehouse_to_id', $warehouse_to_id)
            // ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            // ->where(function ($query) {
            //     $query->where('transaction_type', 1)
            //           ->orWhere('transaction_type', 6)
            //           ->orWhere('transaction_type', 7)
            //           ;
            // })
            // ->select('product_id', 'Product_name') // اختيار الأعمدة المطلوبة
            // ->distinct() // التأكد من جلب القيم المميزة
            // ->get(); // جلب النتائج كمجموعة بيانات
        }

        if( $DisplayMethod=="SelectedProduct")
        {
            $uniqueProducts = Purchase::where('warehouse_to_id', $warehouse_to_id)
            ->where('product_id', $productname)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->select('product_id', 'Product_name') // اختيار الأعمدة المطلوبة
            ->distinct() 
            ->get();
          // جلب النتائج كمجموعة بيانات
        }


      
   
        $QuantitySupplier = []; // تخزين المنتجات
        $QuantityCostsSupplier = []; // تخزين المنتجات
        foreach ($uniqueProducts as $products) {
            // حساب الكميات بناءً على نوع المعاملة والمخزن
            $product_id=$products->product_id ?? $productname;

            $product = Product::where('product_id',  $product_id)->first();
            foreach($Suppliers as $Supplier)
            {
                    if( $product)
                    {
                    
                          $categories = Category::where('product_id', $product_id)->first();  
                        $SupplierData = SubAccount::where('sub_account_id',$Supplier->sub_account_id)->first();
                      $purchaseToQuantity = Purchase::where('product_id', $product_id)
                      ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                      ->where('warehouse_to_id', $warehouse_to_id)
                      ->where('Supplier_id', $Supplier->sub_account_id)
                      ->whereIn('transaction_type', [1, 6,7])
                      ->sum('quantity');
                   
                      $lastPurchase = Purchase::where('product_id', $product_id)
                        ->where('Supplier_id', $Supplier->sub_account_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                        ->where('warehouse_to_id', $warehouse_to_id)
                        ->whereIn('transaction_type', [1, 6])
                        ->sum('Purchase_price');

            $warehouseFromQuantity = Purchase::where('product_id', $product_id)
                ->where('warehouse_from_id', $warehouse_to_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('transaction_type', 2)
                ->where('Supplier_id', $Supplier->sub_account_id)
                ->sum('quantity');
    
            $warehouseFromQuantity3 = Purchase::where('product_id', $product_id)
                ->where('warehouse_from_id', $warehouse_to_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('transaction_type', 3)
                ->where('Supplier_id', $Supplier->sub_account_id)
                ->sum('quantity');
    
            $saleQuantity5 = Sale::where('product_id', $product_id)
                ->where('warehouse_to_id', $warehouse_to_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('transaction_type', 5)
                ->where('supplier_id', $Supplier->sub_account_id)
                ->sum('quantity');
            $astsaleQuantity = Sale::where('product_id', $product_id)
                ->where('warehouse_to_id', $warehouse_to_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('transaction_type', 5)
                ->where('supplier_id', $Supplier->sub_account_id)
                ->sum('Selling_price');
    
            $saleQuantity4 = Sale::where('product_id', $product_id)
                ->where('warehouse_to_id', $warehouse_to_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('transaction_type', 4)
                ->where('supplier_id', $Supplier->sub_account_id)
                ->sum('quantity');
            $productPurchase =( $purchaseToQuantity+$saleQuantity4 ) - $warehouseFromQuantity - $warehouseFromQuantity3- $saleQuantity4 ;
            // تخزين البيانات في مصفوفة
            if($purchaseToQuantity)
            {
             $QuantitySupplier[] = [
                'product_id' => $product->product_id,
                'Purchase_price' => $product->Purchase_price,
                'astPurchase' => $lastPurchase,
                'accountingPeriod' => $accountingPeriod->created_at,
                'product_name' => $product->product_name,
                'note' => $product->note,
                'SupplierData' => $SupplierData,
                'categories' => $categories,
                'warehouse_name' => $warehouseName,
                'SumQuantity' => $productPurchase,
                'saleQuantity5'=>$saleQuantity5,
                'purchaseToQuantity' => $purchaseToQuantity,
                'returnPurchaseToQuantity' => $warehouseFromQuantity,
                'warehouseFromQuantity3' => $warehouseFromQuantity3,
                'Myanalysis'=>  $Myanalysis,

            ];
             $QuantityCostsSupplier[] = [
                'product_id' => $product->product_id,
                'Purchase_price' => $product->Purchase_price,
                'astPurchase' => $lastPurchase,
                'astsaleQuantity' => $astsaleQuantity,
                'accountingPeriod' => $accountingPeriod->created_at,
                'product_name' => $product->product_name,
                'saleQuantity5'=>$saleQuantity5,
                'note' => $product->note,
                'SupplierData' => $SupplierData,
                'categories' => $categories,
                'warehouse_name' => $warehouseName,
                'SumQuantity' => $productPurchase,
                'purchaseToQuantity' => $purchaseToQuantity,
                'returnPurchaseToQuantity' => $warehouseFromQuantity,
                'warehouseFromQuantity3' => $warehouseFromQuantity3,
                'Myanalysis'=>  $Myanalysis,
            ];
        }
        }
        }
    }
    $accountingPeriod=$accountingPeriod->created_at;
    if($DisplayMethod =="ShowAllProducts")
        {
            $productname=" تقرير  المخزني   لكل الاصناف في مخزن  "."  ".$warehouseName;
            
        }
    if($DisplayMethod =="SelectedProduct")
        {
            $productname="تقرير  المخزني لصنف :  ".$product->product_name." /في المخزن: ".$warehouseName;
            
        }
    if($Quantit=="QuantityCostsSupplier")
    {
      
     return view('report.print', compact('QuantityCostsSupplier','productname','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
    }
    if($Quantit=="QuantitySupplier")
    {
     return view('report.print', compact('QuantitySupplier','productname','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML

    }
    
    }
    public function Quantityonly($Quantit,$warehouse_to_id,$id)
    {
        if($Quantit=="QuantityCosts")
        {
            $Myanalysis="الكمية والتكاليف  ";
        }
        if($Quantit=="Quantityonly")
        {
            $Myanalysis="الكمية فقط  ";
    
        }
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
 // التحقق من وجود المنتج
 $productData = Product::where('product_id', $id)->first();

 $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');
 $categories = Category::where('product_id', $productData->product_id)
 ->first();      
    
 if (!$productData) {
     return response()->json(['success' => false, 'message' => 'المنتج غير موجود']);
 }
 // حساب الكميات بناءً على نوع المعاملة والمخزن
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

 $accountingPeriod = $accountingPeriod->created_at;
if($Quantit=="Quantityonly")
{
    return view('report.print', compact('productData','Myanalysis','accountingPeriod','productPurchase','categories','warehouseName'))->render(); // إرجاع المحتوى كـ HTML
}
if($Quantit=="QuantityCosts")
{
    $productDataCosts=$productData;
    return view('report.print', compact('productDataCosts','Myanalysis','accountingPeriod','productPurchase','categories','warehouseName'))->render(); // إرجاع المحتوى كـ HTML

}


    }

    public function store(Request $request)
    {
        if (!$request->product_name) {
            return response()->json([
                'success' => false,
                'message' => 'يجب عليك تحديد اسم المنتج .'
            ]);
        }
        // تحويل الأرقام العربية إلى الإنجليزية
        $Quantity = $this->convertArabicNumbersToEnglish($request->input('Quantity'));
        $Selling_price = $this->convertArabicNumbersToEnglish($request->input('Selling_price'));
        $Purchase_price = $this->convertArabicNumbersToEnglish($request->input('Purchase_price'));
        $Regular_discount = $this->convertArabicNumbersToEnglish($request->input('Regular_discount'));
        $Special_discount = $this->convertArabicNumbersToEnglish($request->input('Special_discount'));
        $Quantityprice = $this->convertArabicNumbersToEnglish($request->input('Quantityprice'));
        $product_idUpdate = $this->convertArabicNumbersToEnglish($request->input('producid'));
// dd($product_idUpdate);
        if(!$product_idUpdate)
        {
            $productname=Product::where('product_name', $request->product_name)->first();
            if ($productname) {
                return response()->json([
                    'success' => false,
                    'message' => 'يوجد نفس هذا الاسم من قبل.'
                ]);
            }
        }
        // الحصول على الفترة المحاسبية المفتوحة
        if ($Quantity > 0) {
            if (!$request->account_debitid) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب عليك تحديد مخزن.'
                ]);
            }
            if (!$Purchase_price) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب عليك تحديد سعر الشراء.'
                ]);
            }
            if (!$Selling_price) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب عليك تحديد سعر البيع .'
                ]);
            }
           

            if ($request->cate)
             {
                if (!$Quantityprice) {
                    return response()->json([
                        'success' => false,
                        'message' => 'يجب عليك تحديد سعر البيع .'
                    ]);
                }

            }

// إنشاء منتج جديد أو تحديثه
$ProductNew = Product::updateOrCreate(
    [
        'product_id' => $product_idUpdate, // شرط التحديث أو الإنشاء
    ],
    [
        'Barcode' => $request->Barcode,
        'product_name' => $request->product_name,
        'Quantity' => $Quantity,
        'supplier_id' => $request->Supplier_id,
        'Purchase_price' => $Purchase_price,
        'Selling_price' => $Selling_price,
        'Categorie_id' => $request->Categorie_id,
        'Regular_discount' => $Regular_discount,
        'Special_discount' => $Special_discount,
        'User_id' => auth()->id(),
        'currency_id' => $request->currency_id,
        'Total' => $request->Total,
        'Cost' => $request->Cost,
        'Profit' => $request->Profit,
        'note' => $request->note,
        'warehouse_id' => $request->account_debitid,
    ]
);

        $Post = new Category;
        $product_name = Product::where('product_id', $ProductNew->product_id)->value('product_name');
        $produc = Product::where('product_id', $ProductNew->product_id)->first();
        // التحقق من كمية المنتج وإنشاء سجل في جدول المشتريات
          if ($request->cate) {
            $Post->Categorie_name=$request->cate;
            $Post->product_id=$produc->product_id;
            $Post->Purchase_price=$produc->Purchase_price;
            $Post->Selling_price=$produc->Selling_price ;
            $Post->Quantityprice=$request->Quantityprice ??1 ;
            $Post->user_id=$request->user_id;
            $Post->save();
          
            }
       
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            if (!$accountingPeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد فترة محاسبية مفتوحة.'
                ]);
            }
            $purchase = Purchase::updateOrCreate(
                [
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'purchase_id' => $request->purchase_id,
                ],
                [
                    'product_id' => $ProductNew->product_id,
                    'Purchase_invoice_id' =>null,
                    'Product_name' => $product_name,
                    'Barcode' => $produc->Barcode ?? 0,
                    'quantity' => $Quantity,
                    'Quantityprice' => $Quantity ??0,

                    'Purchase_price' => $Purchase_price,
                    'Selling_price' => $Selling_price,
                    'Total' => $Purchase_price* $Quantity,
                    'Cost' => 0,
                    'Currency_id' => null,
                    'Supplier_id' => $produc->supplier_id,
                    'User_id' => auth()->id(),
                    'warehouse_to_id' => $request->account_debitid,
                    'warehouse_from_id' => null,
                    'Discount_earned' => 0,
                    'Profit' => $request->Profit ?? 0,
                    'Exchange_rate' => 1.0,
                    'note' => $request->note ?? '',
                    'account_id' => $produc->supplier_id,
                    'transaction_type' => 6,
                    'categorie_id' =>null,
                ]
            );
        if($product_idUpdate)
        {
                return response()->json([
                    'success' => true,
                    'message' => ' تم تعديل الصنف بنجاح  .',
                ]);
            }
            if($Post->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => ' تم الحفظ بنجاح-تم تحديث مخزن-تم حفظ الوحدة .',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم الحفظ بنجاح وتحديث مخزن.',
            ]);
        }
        
       
    
        return response()->json([
            'success' => true,
            'message' => 'تم الحفظ بنجاح.',
        ]);
    }
    
    public function edit($id){
        $prod= Product::where('product_id',$id)->first();
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $purchaseid= Purchase::where('product_id', $id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->where('transaction_type', 6)
        ->first();
 $purchaseid=$purchaseid->purchase_id?? null;
        $curr=Currency::all();
        $editProduct="تعديل الصنف";
         return view('products.create',
         ['prod'=>$prod,
         'editProduct'=>$editProduct,
         'purchaseid'=>$purchaseid, ]);
     }
    public function update(Request $request,$id){
        Product::where('product_id',$id)->update([
            'Barcode'=>$request->Barcode,
            'product_name'=>$request->product_name,
            'Quantity'=>$request->Quantity,
            'Purchase_price'=>$request->Purchase_price,
            'Selling_price'=>$request->Selling_price,
            'Regular_discount'=>$request->Regular_discount,
            'Special_discount'=>$request->Special_discount,
            'User_id' => auth()->id(),
            'Total'=>$request->Total,
            'Cost'=>$request->Cost,
            'Profit'=>$request->Profit,
            'note'=>$request->note,
            'warehouse_id'=>$request->warehouse_id,
        ]);
         return redirect()->route('products.index');
    }

    public function destroy($id){
        Product::where('product_id',$id)->delete();
        return response()->json(['success' => 'success','message'=> 'تم   حذف القيد بنجاح!']);

        return back();
    }
    public function price($id)
    {
        $prod= Category::where('categorie_id',$id)->first();
        return response()->json($prod);
    }
    
    public function allProducts($id)
    {
        
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $uniqueProducts = Purchase::where('warehouse_to_id', $id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where(function ($query) {
                $query->where('transaction_type', 1)
                      ->orWhere('transaction_type', 6)
                      ->orWhere('transaction_type', 7)
                      ->orWhere('transaction_type', 3);
            })
            ->select('product_id', 'Product_name') // اختيار الأعمدة المطلوبة
            ->distinct() // التأكد من جلب القيم المميزة
            ->get(); // جلب النتائج كمجموعة بيانات
        return response()->json($uniqueProducts);
    }


    public function search(Request $request)
    {
        if ($request->ajax()) {
            $cate=Category::all();
            $curr=Currency::all();
            $Warehouses=Warehouse::all();
            $output = '';
            $query = $request->get('search');
            if ($query != '') {
                $products = Product::where('product_name', 'LIKE', '%'.$query.'%')
                    ->orWhere('Barcode', 'LIKE', '%'.$query.'%') // أضف أي حقل آخر تريد البحث فيه
                    ->get();

                if ($products) {
                    foreach ($products as $product) {
                            $categoryName = '';

                        foreach ($cate as $cat) {
                            if ($cat->categorie_id == $product->Categorie_id) {
                                $categoryName = $cat->Categorie_name;
                                break;
                            }
                        }
                        $currName = '';
                        foreach ($curr as $cur) {
                            if ($cur->currency_id == $product->currency_id) {
                                $currName = $cur->currency_name;
                                break;
                            }
                        }
                        $WarehouseName = '';
                        foreach ($Warehouses as $Warehouse) {
                            if ($Warehouse->warehouse_id == $product->warehouse_id) {
                                $WarehouseName = $Warehouse->Store_name;
                                break;
                            }
                        }
                        $output .= '<tr class="bg-white transition-all duration-500 hover:bg-gray-50 text-right"">'.
                        '<td class="tagTd">'.$product->Barcode.'</td>'.
                        '<td class="tagTd">'.$product->product_name.'</td>'.
                        '<td class="tagTd">'.$product->Quantity.'</td>'.
                        '<td class="tagTd">'.$categoryName.'</td>'.
                        '<td class="tagTd">'.$product->Purchase_price.'</td>'.
                        '<td class="tagTd">'.$product->Selling_price.'</td>'.
                        '<td class="tagTd">'.$product->Cost.'</td>'.
                        '<td class="tagTd">'.$product->Total.'</td>'.
                        '<td class="tagTd">'.$product->Profit.'</td>'.
                        '<td class="tagTd">'.$product->Regular_discount.'</td>'.
                        '<td class="tagTd">'.$product->Special_discount.'</td>'.
                        '<td class="tagTd">'.$currName.'</td>'.
                        '<td class="tagTd">'.$WarehouseName.'</td>'.
                        '<td class="tagTd">'.$product->note.'</td>'.
                        '<td class="p-1 tagTd">'.
                            '<div class="flex items-center gap-1">'.
                            '<a href="'.route('products.edit', $product->product_id).'" class="p-1 rounded-full group transition-all duration-500 flex item-center">'.                                    '<svg class="cursor-pointer" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">'.
                                        '<path class="fill-indigo-500" d="M9.53414 8.15675L8.96459 7.59496L9.53414 8.15675ZM13.8911 3.73968L13.3215 3.17789L13.8911 3.73968ZM16.3154 3.75892L15.7367 4.31126L16.3154 3.75892ZM16.38 3.82658L16.9587 3.27423L16.38 3.82658ZM16.3401 6.13595L15.7803 5.56438L16.3401 6.13595ZM11.9186 10.4658L12.4784 11.0374L11.9186 10.4658ZM11.1223 10.9017L10.9404 10.1226L11.1223 10.9017ZM9.07259 10.9951L8.52556 11.5788L9.07259 10.9951ZM9.09713 8.9664L9.87963 9.1328L9.09713 8.9664ZM9.05721 10.9803L8.49542 11.5498L9.05721 10.9803ZM17.1679 4.99458L16.368 4.98075L17.1679 4.99458ZM15.1107 2.8693L15.1171 2.06932L15.1107 2.8693ZM9.22851 8.51246L8.52589 8.12992L9.22851 8.51246ZM9.22567 8.51772L8.52168 8.13773L9.22567 8.51772ZM11.5684 10.7654L11.9531 11.4668L11.5684 10.7654ZM11.5669 10.7662L11.9507 11.4681L11.5669 10.7662ZM11.3235 3.30005C11.7654 3.30005 12.1235 2.94188 12.1235 2.50005C12.1235 2.05822 11.7654 1.70005 11.3235 1.70005V3.30005ZM18.3 9.55887C18.3 9.11705 17.9418 8.75887 17.5 8.75887C17.0582 8.75887 16.7 9.11705 16.7 9.55887H18.3ZM3.47631 16.5237L4.042 15.9581L3.47631 16.5237ZM16.5237 16.5237L15.958 15.9581L16.5237 16.5237ZM10.1037 8.71855L14.4606 4.30148L13.3215 3.17789L8.96459 7.59496L10.1037 8.71855ZM15.7367 4.31127L15.8013 4.37893L16.9587 3.27423L16.8941 3.20657L15.7367 4.31127ZM15.7803 5.56438L11.3589 9.89426L12.4784 11.0374L16.8998 6.70753L15.7803 5.56438ZM10.9404 10.1226C10.3417 10.2624 9.97854 10.3452 9.72166 10.3675C9.47476 10.3888 9.53559 10.3326 9.61962 10.4113L8.52556 11.5788C8.9387 11.966 9.45086 11.9969 9.85978 11.9615C10.2587 11.9269 10.7558 11.8088 11.3042 11.6807L10.9404 10.1226ZM8.31462 8.8C8.19986 9.33969 8.09269 9.83345 8.0681 10.2293C8.04264 10.6393 8.08994 11.1499 8.49542 11.5498L9.619 10.4107C9.70348 10.494 9.65043 10.5635 9.66503 10.3285C9.6805 10.0795 9.75378 9.72461 9.87963 9.1328L8.31462 8.8ZM9.61962 10.4113C9.61941 10.4111 9.6192 10.4109 9.619 10.4107L8.49542 11.5498C8.50534 11.5596 8.51539 11.5693 8.52556 11.5788L9.61962 10.4113ZM15.8013 4.37892C16.0813 4.67236 16.2351 4.83583 16.3279 4.96331C16.4073 5.07234 16.3667 5.05597 16.368 4.98075L17.9678 5.00841C17.9749 4.59682 17.805 4.27366 17.6213 4.02139C17.451 3.78756 17.2078 3.53522 16.9587 3.27423L15.8013 4.37892ZM16.8998 6.70753C17.1578 6.45486 17.4095 6.21077 17.5876 5.98281C17.7798 5.73698 17.9607 5.41987 17.9678 5.00841L16.368 4.98075C16.3693 4.90565 16.4103 4.8909 16.327 4.99749C16.2297 5.12196 16.0703 5.28038 15.7803 5.56438L16.8998 6.70753ZM14.4606 4.30148C14.7639 3.99402 14.9352 3.82285 15.0703 3.71873C15.1866 3.62905 15.1757 3.66984 15.1044 3.66927L15.1171 2.06932C14.6874 2.06591 14.3538 2.25081 14.0935 2.45151C13.8518 2.63775 13.5925 2.9032 13.3215 3.17789L14.4606 4.30148ZM16.8941 3.20657C16.6279 2.92765 16.373 2.65804 16.1345 2.46792C15.8774 2.26298 15.5468 2.07273 15.1171 2.06932L15.1044 3.66927C15.033 3.66871 15.0226 3.62768 15.1372 3.71904C15.2704 3.82522 15.4387 3.999 15.7367 4.31126L16.8941 3.20657ZM8.96459 7.59496C8.82923 7.73218 8.64795 7.90575 8.5259 8.12993L9.93113 8.895C9.92075 8.91406 9.91465 8.91711 9.93926 8.88927C9.97002 8.85445 10.0145 8.80893 10.1037 8.71854L8.96459 7.59496ZM9.87963 9.1328C9.9059 9.00925 9.91925 8.94785 9.93124 8.90366C9.94073 8.86868 9.94137 8.87585 9.93104 8.89515L8.5203 8.1403C8.39951 8.36605 8.35444 8.61274 8.31462 8.8L9.87963 9.1328ZM8.52452 8.13247L8.52168 8.13773L9.92967 8.89772L9.9325 8.89246L8.52452 8.13247ZM11.3589 9.89426C11.27 9.98132 11.2252 10.0248 11.1909 10.055C11.1635 10.0791 11.1658 10.0738 11.1832 10.0642L11.9536 11.4666C12.1727 11.3462 12.3427 11.1703 12.4784 11.0374L11.3589 9.89426ZM11.3042 11.6807C11.4912 11.6371 11.7319 11.5878 11.9507 11.4681L11.1831 10.0643C11.2007 10.0547 11.206 10.0557 11.1697 10.0663C11.1248 10.0793 11.0628 10.0941 10.9404 10.1226L11.3042 11.6807ZM11.1837 10.064L11.1822 10.0648L11.9516 11.4676L11.9531 11.4668L11.1837 10.064ZM16.399 6.10097L13.8984 3.60094L12.7672 4.73243L15.2677 7.23246L16.399 6.10097ZM10.8333 16.7001H9.16667V18.3001H10.8333V16.7001ZM3.3 10.8334V9.16672H1.7V10.8334H3.3ZM9.16667 3.30005H11.3235V1.70005H9.16667V3.30005ZM16.7 9.55887V10.8334H18.3V9.55887H16.7ZM9.16667 16.7001C7.5727 16.7001 6.45771 16.6984 5.61569 16.5851C4.79669 16.475 4.35674 16.2728 4.042 15.9581L2.91063 17.0894C3.5722 17.751 4.40607 18.0369 5.4025 18.1709C6.37591 18.3018 7.61793 18.3001 9.16667 18.3001V16.7001ZM1.7 10.8334C1.7 12.3821 1.6983 13.6241 1.82917 14.5976C1.96314 15.594 2.24905 16.4279 2.91063 17.0894L4.042 15.9581C3.72726 15.6433 3.52502 15.2034 3.41491 14.3844C3.3017 13.5423 3.3 12.4273 3.3 10.8334H1.7ZM10.8333 18.3001C12.3821 18.3001 13.6241 18.3018 14.5975 18.1709C15.5939 18.0369 16.4278 17.751 17.0894 17.0894L15.958 15.9581C15.6433 16.2728 15.2033 16.475 14.3843 16.5851C13.5423 16.6984 12.4273 16.7001 10.8333 16.7001V18.3001ZM16.7 10.8334C16.7 12.4274 16.6983 13.5423 16.5851 14.3844C16.475 15.2034 16.2727 15.6433 15.958 15.9581L17.0894 17.0894C17.7509 16.4279 18.0369 15.594 18.1708 14.5976C18.3017 13.6241 18.3 12.3821 18.3 10.8334H16.7ZM3.3 9.16672C3.3 7.57275 3.3017 6.45776 3.41491 5.61574C3.52502 4.79674 3.72726 4.35679 4.042 4.04205L2.91063 2.91068C2.24905 3.57225 1.96314 4.40612 1.82917 5.40255C1.6983 6.37596 1.7 7.61798 1.7 9.16672H3.3ZM9.16667 1.70005C7.61793 1.70005 6.37591 1.69835 5.4025 1.82922C4.40607 1.96319 3.5722 2.24911 2.91063 2.91068L4.042 4.04205C4.35674 3.72731 4.79669 3.52507 5.61569 3.41496C6.45771 3.30175 7.5727 3.30005 9.16667 3.30005V1.70005Z" fill="#818CF8"></path>'.
                                    '</svg>'.
                                '</a>'.
                                '<button class=" focus:outline-none    group transition-all duration-500  flex item-center" data-toggle="modal" data-target="#delete-modal-'.$product->product_id.'">'.
                                '<svg class="m-0 p-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">'
                                    .'<path class="fill-red-600" d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z" fill="#F87171"></path>'
                                .'</svg>'.
                                '</button>'.
                            '</td>'.
                            '<div class="modal" tabindex="-1" role="dialog" id="delete-modal-'.$product->product_id.'">'.
                            '<div class="modal-dialog" role="document">'.
                                '<div class="modal-content bg-white rounded shadow-md">'.
                                    '<div class="modal-header">'.
                                        '<h5 class="modal-title text-lg font-bold">  حذف المنتاج</h5>'.
                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'.
                                            '<span aria-hidden="true">&times;</span>'.
                                        '</button>'.
                                    '</div>'.
                                    '<div class="modal-body">'.
                                        '<p class="text-gray-600 text-center font-bold">هل انت متاكد من حذف هذه المنتاج?</p>'.
                                    '</div>'.
                                    '<div class="modal-footer">'.
                                        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>'.
                                        '<form action="'.route('products.destroy', $product->product_id).'" method="POST"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" >'.
                                            csrf_field().
                                                method_field('DELETE').
                                            '<button type="submit" class="btn btn-danger">Delete</button>'.
                                        '</form>'.
                                    '</div>'.
                                '</div>'.
                            '</div>'.
                        '</div>'.
                        '</td>'.
                                '</tr>';


                    }
                    return Response($output);
                }
            } else {
                // إذا كان الحقل فارغًا، أرجع جميع المنتجات
                $products = Product::all();
                $Warehouses=Warehouse::all();

                foreach ($products as $product) {
                    $categoryName = '';

                    foreach ($cate as $cat) {
                        if ($cat->categorie_id == $product->Categorie_id) {
                            $categoryName = $cat->Categorie_name;
                            break;
                        }
                    }
                    $currName = '';

                    foreach ($curr as $cur) {
                        if ($cur->currency_id == $product->currency_id) {
                            $currName = $cur->currency_name;
                            break;
                        }
                    }
                    $WarehouseName = '';
                    foreach ($Warehouses as $Warehouse) {
                        if ($Warehouse->warehouse_id == $product->warehouse_id) {
                            $WarehouseName = $Warehouse->Store_name;
                            break;
                        }
                    }
                    $output .= '<tr class="bg-white transition-all duration-500 hover:bg-gray-50 text-right">'.
                    '<td class="tagTd">'.$product->Barcode.'</td>'.
                    '<td class="tagTd">'.$product->product_name.'</td>'.
                    '<td class="tagTd">'.$product->Quantity.'</td>'.
                    '<td class="tagTd">'.$categoryName.'</td>'.
                    '<td class="tagTd">'.$product->Purchase_price.'</td>'.
                    '<td class="tagTd">'.$product->Selling_price.'</td>'.
                    '<td class="tagTd">'.$product->Cost.'</td>'.
                    '<td class="tagTd">'.$product->Total.'</td>'.
                    '<td class="tagTd">'.$product->Profit.'</td>'.
                    '<td class="tagTd">'.$product->Regular_discount.'</td>'.
                    '<td class="tagTd">'.$product->Special_discount.'</td>'.
                    '<td class="tagTd">'.$currName.'</td>'.
                    '<td class="tagTd">'.$WarehouseName.'</td>'.
                    '<td class="tagTd">'.$product->note.'</td>'.
                    '<td class="p-1 tagTd">'.
                        '<div class="flex items-center gap-1">'.
                        '<a href="'.route('products.edit', $product->product_id).'" class="p-1 rounded-full group transition-all duration-500 flex item-center">'.                                    '<svg class="cursor-pointer" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">'.
                                    '<path class="fill-indigo-500" d="M9.53414 8.15675L8.96459 7.59496L9.53414 8.15675ZM13.8911 3.73968L13.3215 3.17789L13.8911 3.73968ZM16.3154 3.75892L15.7367 4.31126L16.3154 3.75892ZM16.38 3.82658L16.9587 3.27423L16.38 3.82658ZM16.3401 6.13595L15.7803 5.56438L16.3401 6.13595ZM11.9186 10.4658L12.4784 11.0374L11.9186 10.4658ZM11.1223 10.9017L10.9404 10.1226L11.1223 10.9017ZM9.07259 10.9951L8.52556 11.5788L9.07259 10.9951ZM9.09713 8.9664L9.87963 9.1328L9.09713 8.9664ZM9.05721 10.9803L8.49542 11.5498L9.05721 10.9803ZM17.1679 4.99458L16.368 4.98075L17.1679 4.99458ZM15.1107 2.8693L15.1171 2.06932L15.1107 2.8693ZM9.22851 8.51246L8.52589 8.12992L9.22851 8.51246ZM9.22567 8.51772L8.52168 8.13773L9.22567 8.51772ZM11.5684 10.7654L11.9531 11.4668L11.5684 10.7654ZM11.5669 10.7662L11.9507 11.4681L11.5669 10.7662ZM11.3235 3.30005C11.7654 3.30005 12.1235 2.94188 12.1235 2.50005C12.1235 2.05822 11.7654 1.70005 11.3235 1.70005V3.30005ZM18.3 9.55887C18.3 9.11705 17.9418 8.75887 17.5 8.75887C17.0582 8.75887 16.7 9.11705 16.7 9.55887H18.3ZM3.47631 16.5237L4.042 15.9581L3.47631 16.5237ZM16.5237 16.5237L15.958 15.9581L16.5237 16.5237ZM10.1037 8.71855L14.4606 4.30148L13.3215 3.17789L8.96459 7.59496L10.1037 8.71855ZM15.7367 4.31127L15.8013 4.37893L16.9587 3.27423L16.8941 3.20657L15.7367 4.31127ZM15.7803 5.56438L11.3589 9.89426L12.4784 11.0374L16.8998 6.70753L15.7803 5.56438ZM10.9404 10.1226C10.3417 10.2624 9.97854 10.3452 9.72166 10.3675C9.47476 10.3888 9.53559 10.3326 9.61962 10.4113L8.52556 11.5788C8.9387 11.966 9.45086 11.9969 9.85978 11.9615C10.2587 11.9269 10.7558 11.8088 11.3042 11.6807L10.9404 10.1226ZM8.31462 8.8C8.19986 9.33969 8.09269 9.83345 8.0681 10.2293C8.04264 10.6393 8.08994 11.1499 8.49542 11.5498L9.619 10.4107C9.70348 10.494 9.65043 10.5635 9.66503 10.3285C9.6805 10.0795 9.75378 9.72461 9.87963 9.1328L8.31462 8.8ZM9.61962 10.4113C9.61941 10.4111 9.6192 10.4109 9.619 10.4107L8.49542 11.5498C8.50534 11.5596 8.51539 11.5693 8.52556 11.5788L9.61962 10.4113ZM15.8013 4.37892C16.0813 4.67236 16.2351 4.83583 16.3279 4.96331C16.4073 5.07234 16.3667 5.05597 16.368 4.98075L17.9678 5.00841C17.9749 4.59682 17.805 4.27366 17.6213 4.02139C17.451 3.78756 17.2078 3.53522 16.9587 3.27423L15.8013 4.37892ZM16.8998 6.70753C17.1578 6.45486 17.4095 6.21077 17.5876 5.98281C17.7798 5.73698 17.9607 5.41987 17.9678 5.00841L16.368 4.98075C16.3693 4.90565 16.4103 4.8909 16.327 4.99749C16.2297 5.12196 16.0703 5.28038 15.7803 5.56438L16.8998 6.70753ZM14.4606 4.30148C14.7639 3.99402 14.9352 3.82285 15.0703 3.71873C15.1866 3.62905 15.1757 3.66984 15.1044 3.66927L15.1171 2.06932C14.6874 2.06591 14.3538 2.25081 14.0935 2.45151C13.8518 2.63775 13.5925 2.9032 13.3215 3.17789L14.4606 4.30148ZM16.8941 3.20657C16.6279 2.92765 16.373 2.65804 16.1345 2.46792C15.8774 2.26298 15.5468 2.07273 15.1171 2.06932L15.1044 3.66927C15.033 3.66871 15.0226 3.62768 15.1372 3.71904C15.2704 3.82522 15.4387 3.999 15.7367 4.31126L16.8941 3.20657ZM8.96459 7.59496C8.82923 7.73218 8.64795 7.90575 8.5259 8.12993L9.93113 8.895C9.92075 8.91406 9.91465 8.91711 9.93926 8.88927C9.97002 8.85445 10.0145 8.80893 10.1037 8.71854L8.96459 7.59496ZM9.87963 9.1328C9.9059 9.00925 9.91925 8.94785 9.93124 8.90366C9.94073 8.86868 9.94137 8.87585 9.93104 8.89515L8.5203 8.1403C8.39951 8.36605 8.35444 8.61274 8.31462 8.8L9.87963 9.1328ZM8.52452 8.13247L8.52168 8.13773L9.92967 8.89772L9.9325 8.89246L8.52452 8.13247ZM11.3589 9.89426C11.27 9.98132 11.2252 10.0248 11.1909 10.055C11.1635 10.0791 11.1658 10.0738 11.1832 10.0642L11.9536 11.4666C12.1727 11.3462 12.3427 11.1703 12.4784 11.0374L11.3589 9.89426ZM11.3042 11.6807C11.4912 11.6371 11.7319 11.5878 11.9507 11.4681L11.1831 10.0643C11.2007 10.0547 11.206 10.0557 11.1697 10.0663C11.1248 10.0793 11.0628 10.0941 10.9404 10.1226L11.3042 11.6807ZM11.1837 10.064L11.1822 10.0648L11.9516 11.4676L11.9531 11.4668L11.1837 10.064ZM16.399 6.10097L13.8984 3.60094L12.7672 4.73243L15.2677 7.23246L16.399 6.10097ZM10.8333 16.7001H9.16667V18.3001H10.8333V16.7001ZM3.3 10.8334V9.16672H1.7V10.8334H3.3ZM9.16667 3.30005H11.3235V1.70005H9.16667V3.30005ZM16.7 9.55887V10.8334H18.3V9.55887H16.7ZM9.16667 16.7001C7.5727 16.7001 6.45771 16.6984 5.61569 16.5851C4.79669 16.475 4.35674 16.2728 4.042 15.9581L2.91063 17.0894C3.5722 17.751 4.40607 18.0369 5.4025 18.1709C6.37591 18.3018 7.61793 18.3001 9.16667 18.3001V16.7001ZM1.7 10.8334C1.7 12.3821 1.6983 13.6241 1.82917 14.5976C1.96314 15.594 2.24905 16.4279 2.91063 17.0894L4.042 15.9581C3.72726 15.6433 3.52502 15.2034 3.41491 14.3844C3.3017 13.5423 3.3 12.4273 3.3 10.8334H1.7ZM10.8333 18.3001C12.3821 18.3001 13.6241 18.3018 14.5975 18.1709C15.5939 18.0369 16.4278 17.751 17.0894 17.0894L15.958 15.9581C15.6433 16.2728 15.2033 16.475 14.3843 16.5851C13.5423 16.6984 12.4273 16.7001 10.8333 16.7001V18.3001ZM16.7 10.8334C16.7 12.4274 16.6983 13.5423 16.5851 14.3844C16.475 15.2034 16.2727 15.6433 15.958 15.9581L17.0894 17.0894C17.7509 16.4279 18.0369 15.594 18.1708 14.5976C18.3017 13.6241 18.3 12.3821 18.3 10.8334H16.7ZM3.3 9.16672C3.3 7.57275 3.3017 6.45776 3.41491 5.61574C3.52502 4.79674 3.72726 4.35679 4.042 4.04205L2.91063 2.91068C2.24905 3.57225 1.96314 4.40612 1.82917 5.40255C1.6983 6.37596 1.7 7.61798 1.7 9.16672H3.3ZM9.16667 1.70005C7.61793 1.70005 6.37591 1.69835 5.4025 1.82922C4.40607 1.96319 3.5722 2.24911 2.91063 2.91068L4.042 4.04205C4.35674 3.72731 4.79669 3.52507 5.61569 3.41496C6.45771 3.30175 7.5727 3.30005 9.16667 3.30005V1.70005Z" fill="#818CF8"></path>'.
                                '</svg>'.
                            '</a>'.
                            '<button class=" focus:outline-none    group transition-all duration-500  flex item-center" data-toggle="modal" data-target="#delete-modal-'.$product->product_id.'">'.
                            '<svg class="m-0 p-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">'
                                .'<path class="fill-red-600" d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z" fill="#F87171"></path>'
                            .'</svg>'.
                            '</button>'.
                        '</td>'.
                        '<div class="modal" tabindex="-1" role="dialog" id="delete-modal-'.$product->product_id.'">'.
                        '<div class="modal-dialog" role="document">'.
                            '<div class="modal-content bg-white rounded shadow-md">'.
                                '<div class="modal-header">'.
                                '<h5 class="modal-title text-lg font-bold">  حذف المنتاج</h5>'.
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'.
                                    '<span aria-hidden="true">&times;</span>'.
                                '</button>'.
                            '</div>'.
                            '<div class="modal-body">'.
                                '<p class="text-gray-600 text-center font-bold">هل انت متاكد من حذف هذه المنتاج?</p>'.
                                '</div>'.
                                '<div class="modal-footer">'.
                                    '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>'.
                                    '<form action="'.route('products.destroy', $product->product_id).'" method="POST"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" >'.
                                        csrf_field().
                                            method_field('DELETE').
                                        '<button type="submit" class="btn btn-danger">Delete</button>'.
                                    '</form>'.
                                '</div>'.
                            '</div>'.
                        '</div>'.
                    '</div>'.
                    '</td>'.
                            '</tr>';
                }
                return Response($output);
            }
        }
    }
    }


    //

