<?php

namespace App\Http\Controllers;

use App\Models\AccountingPeriod;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryInvoice;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SubAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {

        return view('inventory.index');
    }
    private function removeCommas($value)
    {
        return floatval(str_replace(',', '', $value)); // إزالة الفواصل وتحويل إلى float
    }
    public function newInventory(Request $request)
    {
        $Uesr=auth()->id();
        if (!$Uesr) {
            return response()->json([
                'success' => false,
                'message' => '  قم بتسجيل الدخول  .'
            ]);
        }
      
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            $Product = Product::where('product_id', $request->product_id)->first();

        $purchasePrice = $this->removeCommas($request->Purchase_price);
        // $Selling_price = $this->removeCommas($request->Selling_price);
        $TotalPurchase = $this->removeCommas($request->TotalPurchase);
        $InventoryInvoice = InventoryInvoice::where('id', $request->sales_invoice_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->first();
    if (!$InventoryInvoice) {
        return response()->json([
            'success' => false,
            'message' => 'الفاتورة الجرد غير موجودة.'
        ]);
    }
          // التحقق من وجود المنتج في النظام
          $product = Product::where('product_id', $Product->product_id)->first();
          if (!$product) {
              return response()->json([
                  'success' => false,
                  'message' => 'هذا المنتج غير موجود في النظام. يجب عليك إضافته من صفحة المنتجات.'
              ]);
          }
          $request->Categorie_name;
        // الحصول على الفترة المحاسبية المفتوحة
        if (!$accountingPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد فترة محاسبية مفتوحة.'
            ]);
        }
            $productName = Product::where('product_id', $Product->product_id)->value('Product_name');
            $inventory = Inventory::updateOrCreate(
                [
                    'id' => $request->InventoryId,
                    'InventoryInvoiceId' =>$InventoryInvoice->id,
                ],
                [
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'CostPrice' =>$purchasePrice,
                    'categorie_id' =>$request->Categorie_name,
                    'product_id' =>$product->product_id,
                    'StoreId' =>$InventoryInvoice->StoreId ?? null,
                    'InventoryOfficerId' =>$InventoryInvoice->InventoryOfficerId ?? null,
                    'Quantityprice' => $request->Quantityprice,
                    'quantity' => $request->Quantity,
                    'TotalCost' => $TotalPurchase,
                    'User_id' => auth()->id(),
                ]
            );
            $categorieId = Category::where('product_id', $inventory->product_id)
            ->where('categorie_id', $inventory->categorie_id)
            ->orwhere('Categorie_name', $request->Categorie_name)
            ->value('Categorie_name');
            $sumTotalCost=Inventory::where('InventoryInvoiceId',$InventoryInvoice->id)->sum('TotalCost');
            $inventoryData=Inventory::where('id',$inventory->id)->first();
            $inventoryData=[
                'CostPrice' => $inventoryData->CostPrice,
                'product_id' => $inventoryData->product_id,
                'id' => $inventoryData->id,
                'warehouse_to_id' => $inventoryData->StoreId,
                'InventoryOfficerId' => $inventoryData->InventoryOfficerId,
                'Quantityprice' => $inventoryData->Quantityprice,
                'quantity' => $inventoryData->quantity,
                'TotalCost' => $inventoryData->TotalCost,
                'User_id' => $inventoryData->User_id,
                'id' => $inventoryData->id,
                'InventoryInvoiceId' => $inventoryData->InventoryInvoiceId,
                'accounting_period_id' => $inventoryData->accounting_period_id,
                'Product_name' => $productName,
                'Category_name' => $categorieId,
            ]
            ;
            return response()->json([
                'success' => true,
                'message' => 'تم الحفظ بنجاح.',
                'InventoryInvoice' => $InventoryInvoice->id,
                'TotalCost'=>$sumTotalCost,
                'inventoryData'=>$inventoryData,
         
            ]);
        
}  
private function convertArabicNumbersToEnglish($value)
{
    $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($arabicNumbers, $englishNumbers, $value);
}
public function ShowAllProducts(Request $request,$id)
{
    $validated = $request->validate([
        'warehouseid' => 'nullable|',
        'productname' => 'nullable|',
        'accountingPeriodData' => 'nullable|',
        'Quantit' => 'nullable|',
        'DisplayMethod' => 'nullable|string|max:255',
    ]);
            $warehouse_to_id = $validated['warehouseid'];
            $accountingPeriodData =$validated['accountingPeriodData'];
            $warehouse_to_id = $this->convertArabicNumbersToEnglish($warehouse_to_id);
            $productname = $validated['productname'];
            $productname = $this->convertArabicNumbersToEnglish($productname);
            $Quantit = $validated['Quantit'];
            $DisplayMethod = $validated['DisplayMethod'];
            if( $DisplayMethod=="SelectedProduct")
            {
            }
            if( $accountingPeriodData )
            {
                $accountingPeriod = AccountingPeriod::where('accounting_period_id', $accountingPeriodData)->first();
            }
            else
            {
                $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            }
            $QuantityAppendix = []; // تخزين المنتجات
            $QuantityIncomplete = []; 
            $inventoryList = []; 
            $CostIncomplete = []; 
            $AllQuantitiyCosts = []; 
            $Appendix =[]; 
    $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');

    if($DisplayMethod =="ShowAllProducts")
        {
            $productname=null;
        }
        if($DisplayMethod =="ShowAllProducts")
        {
        $uniqueProducts = Inventory::where('StoreId', $warehouse_to_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->select('product_id') // اختيار الأعمدة المطلوبة
        ->distinct() // التأكد من جلب القيم المميزة
        ->get();// جلب النتائج كمجموعة بيانات
    }
    if( $DisplayMethod=="SelectedProduct")
    {
        $uniqueProduct=[] ;
        $productname = explode(',', $productname);
            foreach ($productname as $produ) 
            {
        $uniqueProduc = Inventory::where('StoreId', $warehouse_to_id)
        ->where('product_id', $produ)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->select('product_id') // اختيار الأعمدة المطلوبة
        ->distinct() 
        ->get();
        $product = Product::where('product_id',  $produ)->first();
         $uniqueProducts[] = [
                    'product_id' => $product->product_id,
                ];
    }

    }
    
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
          $QuantityDifference= $InventoryQuantity-$productPurchase;
          $inventoryData=Inventory::where('product_id', $product_id)->first();
          $TotalCost=Inventory::where('product_id',$product_id)->sum('TotalCost');
                   // 'inventoryList' => 'امر جرد',
        if($Quantit=="inventoryList")
        {
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

        //   'appendix' => 'فارق الجرد للكميات الزائدة',
          if($Quantit=="appendix")
          {
          if($QuantityDifference>0  && $inventoryData!==null )
          {
            $QuantityAppendix[]=[
               'product_id' => $inventoryData->product_id,
                'id' => $inventoryData->id,
                'warehouse_name' => $inventoryData->StoreId,
                'InventoryOfficerId' => $inventoryData->InventoryOfficerId,
                'Quantityprice' => $inventoryData->Quantityprice,
                'AvailableQuantity' => $productPurchase,
                'InventoryQuantity' => $InventoryQuantity,
                'QuantityDifference' => $QuantityDifference,
                'InventoryInvoiceId' => $inventoryData->InventoryInvoiceId,
                'accounting_period_id' => $inventoryData->accounting_period_id,
                'product_name' => $product->product_name,
                'categories' => $categories,
                ];
          }
          }
        //   'Costappendix' => 'فارق الجرد للكميات الزائدة مع التكاليف',
          if($Quantit=="Costappendix")
          {
          if($QuantityDifference>0  && $inventoryData!==null )
          {
            $CostIncomplete[]=[
                'CostPrice' => $inventoryData->CostPrice ??0,
                'TotalCost' => $TotalCost,
                'product_id' => $inventoryData->product_id,
                'id' => $inventoryData->id,
                'TotalInventoryCost'=>$QuantityDifference * $inventoryData->CostPrice,
                'TotalCostQuantityAvailable'=>$InventoryQuantity * $inventoryData->CostPrice,
                'warehouse_name' => $inventoryData->StoreId,
                'InventoryOfficerId' => $inventoryData->InventoryOfficerId,
                'AvailableQuantity' => $productPurchase,
                'InventoryQuantity' => $InventoryQuantity,
                'QuantityDifference' => $QuantityDifference,
                'User_id' => $inventoryData->User_id,
                'id' => $inventoryData->id,
                'InventoryInvoiceId' => $inventoryData->InventoryInvoiceId,
                'accounting_period_id' => $inventoryData->accounting_period_id,
                'product_name' => $product->product_name,
                'categories' => $categories,
                ]
            ;
          }
          }
          // 'AllAbstractQuantities' => 'كل الكميات المجرودة',
          if($Quantit=="AllAbstractQuantities")
          {
          if($QuantityDifference!==0  && $inventoryData!==null )
          {
            $QuantityIncomplete[]=[
                'product_id' => $inventoryData->product_id,
                'id' => $inventoryData->id,
                'warehouse_name' => $inventoryData->StoreId,
                'InventoryOfficerId' => $inventoryData->InventoryOfficerId,
                'Quantityprice' => $inventoryData->Quantityprice,
                'AvailableQuantity' => $productPurchase,
                'InventoryQuantity' => $InventoryQuantity,
                'QuantityDifference' => $QuantityDifference,
                'InventoryInvoiceId' => $inventoryData->InventoryInvoiceId,
                'accounting_period_id' => $inventoryData->accounting_period_id,
                'product_name' => $product->product_name,
                'categories' => $categories,
                ];
          }
          }
         // 'AllAbstractQuantitiesWithCosts' => 'كل الكميات المجرودة مع التكاليف',
          if($Quantit=="AllAbstractQuantitiesWithCosts")
          {
            if($QuantityDifference!==0  && $inventoryData!==null )
            {
                $producta = Product::where('product_id',  $inventoryData->product_id)->first();
                $CostIncomplete[]=[
                    'CostPrice' => $inventoryData->CostPrice,
                    'TotalCost' => $TotalCost,
                    'product_id' => $inventoryData->product_id,
                    'id' => $inventoryData->id,
                    'TotalInventoryCost'=>$QuantityDifference * $inventoryData->CostPrice,
                    'TotalCostQuantityAvailable'=>$InventoryQuantity * $inventoryData->CostPrice,
                    'warehouse_name' => $inventoryData->StoreId,
                    'InventoryOfficerId' => $inventoryData->InventoryOfficerId,
                    'AvailableQuantity' => $productPurchase,
                    'InventoryQuantity' => $InventoryQuantity,
                    'QuantityDifference' => $QuantityDifference,
                    'User_id' => $inventoryData->User_id,
                    'id' => $inventoryData->id,
                    'InventoryInvoiceId' => $inventoryData->InventoryInvoiceId,
                    'accounting_period_id' => $inventoryData->accounting_period_id,
                    'product_name' => $product->product_name,
                    'categories' => $categories,
                    ]
                ;
          }
          }

        //   'MissingQuantitiesInventoryTeams' => 'فارق الجرد للكميات الناقصة',
          if($Quantit=="MissingQuantitiesInventoryTeams")
          {
          if($QuantityDifference<0 && $inventoryData!==null )
          {
        $producta = Product::where('product_id',  $inventoryData->product_id)->first();
            $QuantityIncomplete[]=[
                'product_id' => $inventoryData->product_id,
                'id' => $inventoryData->id,
                'warehouse_name' => $inventoryData->StoreId,
                'InventoryOfficerId' => $inventoryData->InventoryOfficerId,
                'AvailableQuantity' => $productPurchase,
                'InventoryQuantity' => $InventoryQuantity,
                'QuantityDifference' => $QuantityDifference,
                'User_id' => $inventoryData->User_id,
                'InventoryInvoiceId' => $inventoryData->InventoryInvoiceId,
                'accounting_period_id' => $inventoryData->accounting_period_id,
                'product_name' => $product->product_name,
                'categories' => $categories,
                ]
            ;
          }
          }
        //   'InventoryDifferenceMissingQuantitiesWithCosts' => ' فارق الجرد للكميات الناقصة مع التكاليف',
          if($Quantit=="InventoryDifferenceMissingQuantitiesWithCosts")
          {
          if($QuantityDifference<0 && $inventoryData!==null )
          {
        $producta = Product::where('product_id',  $inventoryData->product_id)->first();

            $CostIncomplete[]=[
                'CostPrice' => $inventoryData->CostPrice,
                'TotalCost' => $TotalCost,
                'product_id' => $inventoryData->product_id,
                'id' => $inventoryData->id,
                'TotalInventoryCost'=>$QuantityDifference * $inventoryData->CostPrice,
                'TotalCostQuantityAvailable'=>$InventoryQuantity * $inventoryData->CostPrice,
                'warehouse_name' => $inventoryData->StoreId,
                'InventoryOfficerId' => $inventoryData->InventoryOfficerId,
                'AvailableQuantity' => $productPurchase,
                'InventoryQuantity' => $InventoryQuantity,
                'QuantityDifference' => $QuantityDifference,
                'User_id' => $inventoryData->User_id,
                'id' => $inventoryData->id,
                'InventoryInvoiceId' => $inventoryData->InventoryInvoiceId,
                'accounting_period_id' => $inventoryData->accounting_period_id,
                'product_name' => $product->product_name,
                'categories' => $categories,
                ]
            ;
          }
          }

                 
    }
    }

if($DisplayMethod =="ShowAllProducts")
{
    $productname=" تقرير  المخزني   لكل الاصناف في   "."  ".$warehouseName;   
}
if($DisplayMethod =="SelectedProduct")
{
    $productname="تقرير  المخزني لصنف :  ".$product->product_name." /في : ".$warehouseName;
    
}
 
$accountingPeriod = $accountingPeriod->created_at;
$accountingPeriod = Carbon::now()->format('Y-m-d');

if($Quantit=="MissingQuantitiesInventoryTeams")
{
    $Myanalysis=" فارق الجرد للكميات الناقصة  ";

 return view('inventory.print', ['QuantityIncomplete'=>$QuantityIncomplete],compact('productname','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
}
if($Quantit=="AllAbstractQuantities")
{
    $Myanalysis=" كل الكميات المجرودة ";

 return view('inventory.print', ['QuantityIncomplete'=>$QuantityIncomplete],compact('productname','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
}
if($Quantit=="InventoryDifferenceMissingQuantitiesWithCosts")
{
    $Myanalysis=" فارق الجرد للكميات الناقصة مع التكاليف";

    $TotalCostQuantityAvailable = collect($CostIncomplete)->sum('TotalCostQuantityAvailable');  
    $TotalInventoryCostVariance = collect($CostIncomplete)->sum('TotalInventoryCost'); 
 return view('inventory.print', compact('CostIncomplete','productname','TotalInventoryCostVariance','TotalCostQuantityAvailable','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
}
if($Quantit=="AllAbstractQuantitiesWithCosts")
{
    $Myanalysis=" كل الكميات المجرودة مع التكاليف   ";

    $TotalCostQuantityAvailable = collect($CostIncomplete)->sum('TotalCostQuantityAvailable');  
    $TotalInventoryCostVariance = collect($CostIncomplete)->sum('TotalInventoryCost'); 
 return view('inventory.print',['CostIncomplete'=>$CostIncomplete], compact('productname','TotalInventoryCostVariance','TotalCostQuantityAvailable','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
}
if($Quantit=="Costappendix")
{

    $Myanalysis="فارق الجرد للكميات الزائدة مع التكاليف";

    $TotalCostQuantityAvailable = collect($CostIncomplete)->sum('TotalCostQuantityAvailable');  
    $TotalInventoryCostVariance = collect($CostIncomplete)->sum('TotalInventoryCost'); 
 return view('inventory.print',['CostIncomplete'=>$CostIncomplete], compact('productname','TotalInventoryCostVariance','TotalCostQuantityAvailable','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
}
if($Quantit=="appendix")
{
    $Myanalysis=" فارق الجرد للكميات الزائدة ";

    return view('inventory.print',compact('QuantityAppendix','productname','Myanalysis','accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
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
    
}
public function edit($id)
    {
        $inventory = Inventory::where('id',$id)->first();
        $QuantityCategorie = Category::where('product_id', $inventory->product_id)
        ->where('categorie_id', $inventory->categorie_id)
        ->first();
        $inventory =[
            'QuantityCategorie'=>$QuantityCategorie->Purchase_price,
            'CostPrice' => $inventory->CostPrice,
            'product_id' => $inventory->product_id,
            'id' => $inventory->id,
            'warehouse_to_id' => $inventory->StoreId,
            'InventoryOfficerId' => $inventory->InventoryOfficerId,
            'Quantityprice' => $inventory->Quantityprice,
            'quantity' => $inventory->quantity,
            'TotalCost' => $inventory->TotalCost,
            'User_id' => $inventory->User_id,
            'id' => $inventory->id,
            'InventoryInvoiceId' => $inventory->InventoryInvoiceId,
            'accounting_period_id' => $inventory->accounting_period_id,
            'categorie_id' =>$QuantityCategorie->categorie_id,
            'Categorie_name' =>$QuantityCategorie->Categorie_name,
        ];

        return response()->json($inventory);
    }
    public function store(Request $request)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $StoreId= $request->StoreId;
        $InventoryTitle= $request->InventoryTitle;
        $InventoryOfficerId= $request->InventoryOfficerId;
        if(!$StoreId)
        {
            return response()->json([
                'success' => false,
                'message' => ' قم بختيار مخزن.',
            ]);
        }
        if(!$InventoryTitle)
        {
            return response()->json([
                'success' => false,
                'message' => 'حقل عنوان الجرد مطلوب  .',
            ]);
        }
    $InventoryInvoice = InventoryInvoice::create([
        'InventoryTitle' => $InventoryTitle?? '',
        'InventoryOfficerId' => $InventoryOfficerId??null,
        'accounting_period_id' => $accountingPeriod->accounting_period_id,
       'User_id' => auth()->id(),
    'StoreId' =>$StoreId,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'تم الحفظ بنجاح.',
        'InventoryInvoice' => $InventoryInvoice->id,
    ]);
    }
    // destroy_invoice
    public function destroy_invoice($id)
    {
        // dd($id);
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
        }
        $InventoryInvoice = InventoryInvoice::where('id', $id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->first();
    if (!$InventoryInvoice) {
        return response()->json([
            'success' => false,
            'message' => 'الفاتورة الجرد غير موجودة.'
        ]);
    }
        $invoice = InventoryInvoice::where('id', $id)->first();
            if (!$InventoryInvoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على معرف الفاتورة.'
                ]);      
              }
        try {
            // حذف جميع المشتريات المرتبطة إن وجدت
            if ($invoice->inventoryItems()->exists())
             {
                $invoice->inventoryItems()->delete();
            }
          
            // حذف الفاتورة نفسها
            $invoice->delete();
           
    
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الفاتورة وجميع الجرد المرتبطة بها بنجاح'
            ]);
    
        } catch (\Exception $e) {
            // DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()
            ]);
        }


      
    }
    public function destroy($id){
        $inventory = Inventory::where('id',$id)->first();
        if (!$inventory) {
            return response()->json([
                'status' => 'error',
                'message' => 'العنصر غير موجود.'
            ], 404);
        }
        try {
            // حذف السجل
            Inventory::where('id',$id)->delete();
            // تحديث الإجمالي
            $TotalCost = Inventory::where('InventoryInvoiceId', $inventory->InventoryInvoiceId)->sum('TotalCost');
            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف العنصر بنجاح.',
                'TotalCost' => $TotalCost
            ]);
    
        } catch (\Exception $e) {
            // في حالة حدوث خطأ أثناء الحذف
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف البيانات. يرجى المحاولة لاحقًا.',
                'error' => $e->getMessage()
            ], 500);
        }
        return back();
    }
    
    public function create(){

        $users=User::all();
        return view('inventory.create',['users'=>$users])
        ;
    }
    public function createList(){
        $accountingPeriod = AccountingPeriod::all();
        $accountingPeriodOpen = AccountingPeriod::where('is_closed', false)->first();

        // التحقق من وجود فترة محاسبية مفتوحة
        if (!$accountingPeriod) {
            return redirect()->back()->with('error', 'لا توجد فترة محاسبية مفتوحة.');
        }
        return view('inventory.Create-an-inventory-list', ['accountingPeriod' => $accountingPeriod,'accountingPeriodOpen'=>$accountingPeriodOpen]);

    }
    public function show_inventoryAccountingPeriod() {
        $accountingPeriod = AccountingPeriod::all();
        $accountingPeriodOpen = AccountingPeriod::where('is_closed', false)->first();

        // التحقق من وجود فترة محاسبية مفتوحة
        if (!$accountingPeriod) {
            return redirect()->back()->with('error', 'لا توجد فترة محاسبية مفتوحة.');
        }
        // جلب الفواتير مع العلاقات
        
        // عرض البيانات في العرض
        return view('inventory.show_inventory', ['accountingPeriodOpen'=>$accountingPeriodOpen,'accountingPeriod' => $accountingPeriod]);
    }
    public function show_inventory($id) {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        // التحقق من وجود فترة محاسبية مفتوحة
        if (!$accountingPeriod) {
            return redirect()->back()->with('error', 'لا توجد فترة محاسبية مفتوحة.');
        }
        // جلب الفواتير مع العلاقات
        $inventorys = InventoryInvoice::with(['store', 'user','employee'])
            ->where('accounting_period_id', $id)
            ->get()
            ->transform(function ($invoice) {
                return [
                    'id'=>$invoice->id,
                    'InventoryTitle'=>$invoice->InventoryTitle ??'',
                    'StoreId' => optional($invoice->store)->sub_name ?? 'غير معروف', // عرض اسم المخزن
                    'employee' => optional($invoice->employee)->name ?? 'غير معروف', // عرض اسم المخزن
                    'User_id' => optional($invoice->user)->name ?? 'غير معروف', // استخدام optional لتجنب الأخطاء
                    'created_at' => optional($invoice->created_at)->format('Y-m-d') ?? 'غير متاح',
                ];
            });
    
        // عرض البيانات في العرض
        return view('inventory.show', ['inventorys' => $inventorys]);
    }


    public function  show(){

        return view('inventory.show');
    }

}
