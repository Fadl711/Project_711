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
use Illuminate\Support\Facades\DB;

class ProductCoctroller extends Controller
{

    private function convertArabicNumbersToEnglish($value)
    {
        $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($arabicNumbers, $englishNumbers, $value);
    }

    public function index()
    {
        $curr = Currency::all();
        $prod = Product::paginate(50);
        return view('products.index', ['prod' => $prod, 'curr' => $curr,]);
    }
    public function create()
    {
        $curr = Currency::all();
        $products = Product::all();


        return view('products.create', [
            'curr' => $curr,
            'products' => $products
        ]);
    }

    public function updateDefaultCategory(Product $product, Request $request)
    {
        try {
            $product->update(['Categorie_id' => $request->unit_id]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function print(Request $request, $id)
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
                $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

      $warehouse_to_id =   (int)$validated['warehouseid'];
        // $accountingPeriod =$validated['accountingPeriodData'];
        $warehouse_to_id = $this->convertArabicNumbersToEnglish($warehouse_to_id);
        $productname = $validated['productname'];
        $productname = $this->convertArabicNumbersToEnglish($productname);
        $Quantit = $validated['Quantit'];
        $DisplayMethod = $validated['DisplayMethod'];

        if ($Quantit == "ExcessQuantitiesCostsAllStores" ) {
            return   $this->allQuantityCosts($warehouse_to_id, $accountingPeriod, $Quantit);
        }
        if ($Quantit == "ExcessQuantitiesCostsFristStore" ) {
            return   $this->allQuantityCosts($warehouse_to_id, $accountingPeriod, $Quantit);
        }

        if ($Quantit === "QuantityCostsSupplier") {
            return $this->QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }
        if ($Quantit === "QuantitySupplier") {
            return $this->QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }

        if ($validated['Quantit'] === "QuantityCosts" ) {
            return $this->ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }
        if ($validated['Quantit'] === "QuantityNotAvailable") {
            return $this->ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }
        // يي
        if ($validated['Quantit'] === "Incomplete") {
            return $this->ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }

        if ($validated['Quantit'] === "Quantityonly" || $validated['Quantit'] === "QuantityAllStores" || $validated['Quantit'] === "QuantityCostsAllStores" || $validated['Quantit'] === "ExcessQuantitiesCostsAllStores"  ) {
            return $this->ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }
        if ($validated['Quantit'] === "inventoryList") {
            return $this->ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }

        if ($validated['DisplayMethod'] === "ShowAllProducts") {
            if ($validated['Quantit'] === "QuantityCosts") {
                dd(22);
                return $this->QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
            }
        }
        if ($validated['DisplayMethod'] === "SelectedProduct") {
            if ($validated['Quantit'] === "QuantityCosts") {
                dd(22);
                return $this->Quantityonly($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
            }
        }
    }
   public function allQuantityCosts($warehouse_to_id, $accountingPeriod, $Quantit)
{
    $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');
    $productname = "تقرير مخزني لكل الاصناف في " . $warehouseName;
$warehouse_ids = SubAccount::where('account_class', 3)
    ->pluck('sub_account_id')
    ->toArray();

// بناء الاستعلام الأساسي
$query = Product::query()
    ->leftJoin('categories', 'products.Categorie_id', '=', 'categories.categorie_id')
    ->select([
        'products.product_id',
        'products.product_name',
        'products.Purchase_price',
        'categories.Purchase_price as unit_price',
        'categories.Categorie_name as category_name',
        'categories.Quantityprice as unit_quantity',
        DB::raw('COALESCE(saleQuantity4.sum_quantity, 0) as saleQuantity4'),
        DB::raw('COALESCE(saleQuantity5.sum_quantity, 0) as saleQuantity5'),
        DB::raw('COALESCE(warehouseFromQuantity3.sum_quantity, 0) as warehouseFromQuantity3'),
        DB::raw('COALESCE(warehouseFromQuantity.sum_quantity, 0) as warehouseFromQuantity'),
        DB::raw('COALESCE(purchaseToQuantity.sum_quantity, 0) as purchaseToQuantity')
    ]);

if ($Quantit == "QuantityAllStores" ||$Quantit == "QuantityNotAvailable" || $Quantit == "QuantityCostsAllStores" || $Quantit=== "ExcessQuantitiesCostsAllStores" ) {
    // dd(22);    // إضافة الجداول الفرعية مع معالجة خاصة لعامل IN
    $query->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as sum_quantity 
        FROM sales 
        WHERE transaction_type = 4 
        AND warehouse_to_id IN ('.implode(',', array_fill(0, count($warehouse_ids), '?')).') 
        AND accounting_period_id = ? 
        GROUP BY product_id) as saleQuantity4'), 
        function ($join) use ($warehouse_ids, $accountingPeriod) {
            $join->on('products.product_id', '=', 'saleQuantity4.product_id');
            $bindings = array_merge($warehouse_ids, [$accountingPeriod->accounting_period_id]);
            $join->addBinding($bindings, 'join');
        })
        ->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as sum_quantity 
        FROM sales 
        WHERE transaction_type = 5 
        AND warehouse_to_id IN ('.implode(',', array_fill(0, count($warehouse_ids), '?')).') 
        AND accounting_period_id = ? 
        GROUP BY product_id) as saleQuantity5'), 
        function ($join) use ($warehouse_ids, $accountingPeriod) {
            $join->on('products.product_id', '=', 'saleQuantity5.product_id');
            $bindings = array_merge($warehouse_ids, [$accountingPeriod->accounting_period_id]);
            $join->addBinding($bindings, 'join');
        })
        ->leftJoin(DB::raw('(SELECT product_id, SUM(Quantityprice) as sum_quantity 
        FROM purchases 
        WHERE transaction_type IN (1, 3, 6, 7, 8, 15, 16, 17) 
        AND warehouse_to_id IN ('.implode(',', array_fill(0, count($warehouse_ids), '?')).') 
        AND accounting_period_id = ? 
        GROUP BY product_id) as purchaseToQuantity'), 
        function ($join) use ($warehouse_ids, $accountingPeriod) {
            $join->on('products.product_id', '=', 'purchaseToQuantity.product_id');
            $bindings = array_merge($warehouse_ids, [$accountingPeriod->accounting_period_id]);
            $join->addBinding($bindings, 'join');
        });

    // الجداول الفرعية التي لا تستخدم IN
    $query->leftJoin(DB::raw('(SELECT product_id, SUM(Quantityprice) as sum_quantity 
        FROM purchases 
        WHERE transaction_type = 2 
        AND warehouse_from_id IN ('.implode(',', array_fill(0, count($warehouse_ids), '?')).')
        AND accounting_period_id = ? 
        GROUP BY product_id) as warehouseFromQuantity'), 
        function ($join) use ($warehouse_ids, $accountingPeriod) {
            $join->on('products.product_id', '=', 'warehouseFromQuantity.product_id');
            $bindings = array_merge($warehouse_ids, [$accountingPeriod->accounting_period_id]);
            $join->addBinding($bindings, 'join');
        })
        ->leftJoin(DB::raw('(SELECT product_id, SUM(Quantityprice) as sum_quantity 
        FROM purchases 
        WHERE transaction_type IN (3,9,10,14,18,19,20)
        AND warehouse_from_id IN ('.implode(',', array_fill(0, count($warehouse_ids), '?')).')
        AND accounting_period_id = ? 
        GROUP BY product_id) as warehouseFromQuantity3'), 
        function ($join) use ($warehouse_ids, $accountingPeriod) {
            $join->on('products.product_id', '=', 'warehouseFromQuantity3.product_id');
            $bindings = array_merge($warehouse_ids, [$accountingPeriod->accounting_period_id]);
            $join->addBinding($bindings, 'join');
        });
}
    else{


    // إضافة الجداول الفرعية
    $query->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as sum_quantity 
    FROM sales WHERE transaction_type = 4
     AND warehouse_to_id = ?
      AND accounting_period_id = ? 
      GROUP BY product_id) as saleQuantity4'), 
        function ($join) use ($warehouse_to_id, $accountingPeriod) {
            $join->on('products.product_id', '=', 'saleQuantity4.product_id')
                ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
        })
        ->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as sum_quantity FROM sales WHERE transaction_type = 5 AND warehouse_to_id = ? AND accounting_period_id = ? GROUP BY product_id) as saleQuantity5'), 
        function ($join) use ($warehouse_to_id, $accountingPeriod) {
            $join->on('products.product_id', '=', 'saleQuantity5.product_id')
                ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
        })
        ->leftJoin(DB::raw('(SELECT product_id, SUM(Quantityprice) as sum_quantity
         FROM purchases WHERE transaction_type IN (3,9,10,14,18,19,20) 
         AND warehouse_from_id = ? 
        AND accounting_period_id = ?
         GROUP BY product_id) as warehouseFromQuantity3'), 
        function ($join) use ($warehouse_to_id, $accountingPeriod) {
            $join->on('products.product_id', '=', 'warehouseFromQuantity3.product_id')
                ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
        })
        ->leftJoin(DB::raw('(SELECT product_id, SUM(Quantityprice) as sum_quantity
         FROM purchases WHERE transaction_type = 2
         AND warehouse_from_id = ? 
        AND accounting_period_id = ?
         GROUP BY product_id) as warehouseFromQuantity'), 
        function ($join) use ($warehouse_to_id, $accountingPeriod) {
            $join->on('products.product_id', '=', 'warehouseFromQuantity.product_id')
                ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
        })
      
        ->leftJoin(DB::raw('(SELECT product_id, SUM(Quantityprice) as sum_quantity
         FROM purchases WHERE transaction_type IN (1, 3, 6, 7, 8,15,16,17)
          AND warehouse_to_id = ?
           AND accounting_period_id = ? 
          GROUP BY product_id) as purchaseToQuantity'), 
        function ($join) use ($warehouse_to_id, $accountingPeriod) {
            $join->on('products.product_id', '=', 'purchaseToQuantity.product_id')
                ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
        });
        }

    $allQuantityCosts = $query->get();



    $accountingPeriodCreatedAtFormatted = Carbon::parse($accountingPeriod->created_at)->format('Y-m-d');
    $accountingPeriod = Carbon::now()->format('Y-m-d');

    if ($Quantit == "QuantityCosts") {
        $Myanalysis = "تقرير مخزني - الكمية والتكاليف - من " . $accountingPeriodCreatedAtFormatted . " الى " . $accountingPeriod;
        return view('report.print', compact('allQuantityCosts', 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    } 
    if ($Quantit == "QuantityCostsAllStores") {
        $Myanalysis = "تقرير مخزني - الكمية والتكاليف المتوفرة في جميع المخازن - من " . $accountingPeriodCreatedAtFormatted . " الى " . $accountingPeriod;
        return view('report.print', compact('allQuantityCosts', 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    } 
    if ($Quantit == "ExcessQuantitiesCostsFristStore") {
        $Myanalysis = "تقرير مخزني -  الكميات والتكاليف الزائدة  او لم تدخل النظام عند الشراء والتي تم بيعها - من  مخزن : ".$warehouseName ." - من " . $accountingPeriodCreatedAtFormatted . " الى " . $accountingPeriod;
        return view('report.print',['excessQuantitiesCostsAllStores'=>$allQuantityCosts] ,compact( 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    } 
    if ($Quantit == "ExcessQuantitiesCostsAllStores") {
        $Myanalysis = "تقرير مخزني -  الكميات والتكاليف الزائدة  او لم تدخل النظام عند الشراء والتي تم بيعها - من جميع المخازن - من " . $accountingPeriodCreatedAtFormatted . " الى " . $accountingPeriod;
        return view('report.print',['excessQuantitiesCostsAllStores'=>$allQuantityCosts] ,compact( 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    } 
    
    if ($Quantit == "Quantityonly") {
        $Myanalysis = "تقرير مخزني - الكميات المتوفرة - " . $accountingPeriod;
        return view('report.print',['allQuantityonly'=>$allQuantityCosts] ,compact( 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    }
    if ($Quantit == "QuantityAllStores") {
        $Myanalysis = "تقرير مخزني -  الكميات المتوفرة في جميع المخازن- " . $accountingPeriod;
        return view('report.print',['allQuantityonly'=>$allQuantityCosts] ,compact( 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    }
    if ($Quantit == "QuantityNotAvailable") {

        $Myanalysis = "تقرير مخزني - للكميات  الغير متوفرة في جميع المخازن - " . $accountingPeriod;
        return view('report.print',['QuantityNotAvailable'=>$allQuantityCosts] ,compact( 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    }

}
    public function selectedProduct($warehouse_to_id, $accountingPeriod, $productname, $Quantit)
    {
        $firstQuantityCosts = Product::where('product_id', (int)$productname)

            ->with(['categories','sales', 'purchases' => function ($query) use ($accountingPeriod, $warehouse_to_id) {
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
            }])
            ->withSum([
                'sales as saleQuantity4' => function ($query)  use ($warehouse_to_id, $accountingPeriod) {
                    $query->where('transaction_type', 4)
                        ->where('warehouse_to_id', $warehouse_to_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                },
                'sales as saleQuantity5' => function ($query)  use ($warehouse_to_id, $accountingPeriod) {
                    $query->where('transaction_type', 5)
                        ->where('warehouse_to_id', $warehouse_to_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                }
            ], 'quantity')
            ->withSum([
                'purchases as warehouseFromQuantity3' => function ($query) use ($warehouse_to_id, $accountingPeriod) {
                    $query->whereIn('transaction_type', [3,9,10,14,18,19,20])
                        ->where('warehouse_from_id', $warehouse_to_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                },
                'purchases as warehouseFromQuantity' => function ($query) use ($warehouse_to_id, $accountingPeriod) {
                    $query->where('transaction_type', 2)
                        ->where('warehouse_from_id', $warehouse_to_id)


                    ;
                },
                'purchases as purchaseToQuantity' => function ($query) use ($warehouse_to_id, $accountingPeriod) {
                    $query->whereIn('transaction_type', [1, 3, 6, 7, 8,15,16,17])
                        ->where('warehouse_to_id', $warehouse_to_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                }
            ], 'Quantityprice')
            ->first();

        $accountingPeriodCreatedAtFormatted = Carbon::parse($accountingPeriod->created_at)->format('Y-m-d');
        $accountingPeri = Carbon::now()->format('Y-m-d');

        if ($Quantit == "Quantityonly") {
            $Myanalysis = "تقرير مخزني - الكمية المتوفرة  - " . " " . "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeri;
            //allQuantityonly
            return view('report.print', ['firstQuantityonly'=>$firstQuantityCosts] , compact( 'productname', 'accountingPeriod', 'Myanalysis'))->render(); // إرجاع المحتوى كـ HTML
        }
        if ($Quantit == "QuantityAvailableInAllStores") {
        $firstQuantityCosts = Product::where('product_id', (int)$productname)

            ->with(['categories','sales', 'purchases' => function ($query) use ($accountingPeriod, $warehouse_to_id) {
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
            }])
            ->withSum([
                'sales as saleQuantity4' => function ($query)  use ($warehouse_to_id, $accountingPeriod) {
                    $query->where('transaction_type', 4)
                        ->where('warehouse_to_id', '!=' ,null)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                },
                'sales as saleQuantity5' => function ($query)  use ($warehouse_to_id, $accountingPeriod) {
                    $query->where('transaction_type', 5)
                        ->where('warehouse_to_id',  '!=' ,null)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                }
            ], 'quantity')
            ->withSum([
                'purchases as warehouseFromQuantity3' => function ($query) use ($warehouse_to_id, $accountingPeriod) {
                    $query->whereIn('transaction_type', [3,9,10,14,18,19,20])
                        ->where('warehouse_from_id', '!=' ,null)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                },
                'purchases as warehouseFromQuantity' => function ($query) use ($warehouse_to_id, $accountingPeriod) {
                    $query->where('transaction_type', 2)
                        ->where('warehouse_from_id', '!=' ,null)


                    ;
                },
                'purchases as purchaseToQuantity' => function ($query) use ($warehouse_to_id, $accountingPeriod) {
                    $query->whereIn('transaction_type', [1, 3, 6, 7, 8,15,16,17])
                        ->where('warehouse_to_id', '!=' ,null)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                }
            ], 'Quantityprice')
            ->first();



            $Myanalysis = "تقرير مخزني - الكمية المتوفرة في جميع المخازن  - " . " " . "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeri;
            //allQuantityonly
            return view('report.print', ['firstQuantityonly'=>$firstQuantityCosts] , compact( 'productname', 'accountingPeriod', 'Myanalysis'))->render(); // إرجاع المحتوى كـ HTML
        }
        if ($Quantit == "QuantityCosts") {
            $Myanalysis = "تقرير مخزني - الكمية والتكاليف - " . " " . "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeri;
            return view('report.print', compact('firstQuantityCosts', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
        }
    }

    public function ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod)
    {
        $warehouse_to_id = (int)$warehouse_to_id;

        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
      
        // $Myanalysis="الكمية والتكاليف من تاريخ";
        if ($Quantit == "inventoryList") {
            $Myanalysis = " امر جرد  ";
        }


        $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');
        if ($DisplayMethod == "ShowAllProducts" ) {
            return   $this->allQuantityCosts($warehouse_to_id, $accountingPeriod, $Quantit);
        }
        
        if ($DisplayMethod == "QuantityNotAvailable") {
            return   $this->allQuantityCosts($warehouse_to_id, $accountingPeriod, $Quantit);
        }

        if ($DisplayMethod == "SelectedProduct") {
            return   $this->selectedProduct($warehouse_to_id, $accountingPeriod, $productname, $Quantit);
        }

        if ($DisplayMethod == "SelectedProduct") {
            $uniqueProduct = [];
            $productname = explode(',', $productname);
            foreach ($productname as $produ) {

                $uniqueProduc = Product::where('product_id', $produ)->get();
                $product = Product::where('product_id', (int)$produ)->first();
                $uniqueProducts = [
                    'product_id' => $product->product_id,
                ];
            }
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();


            $QuantityIncomplete = []; // تخزين المنتجات
            $allQuantityonly = [];
            $allQuantityCosts = []; // تخزين المنتجات
            $QuantityCosts1 = []; // تخزين المنتجات
            $inventoryList = []; // تخزين المنتجات
            $inventoryList1 = [];
            foreach ($uniqueProducts as $products) {
                if ($DisplayMethod == "SelectedProduct") {
                    if (is_object($products) || is_array($products)) {
                        $product_id = $products->product_id ?? (int)$products->product_id ?? $product->product_id;
                    } else {
                        $productname = $productname;
                        $product_id = $products["product_id"]  ?? $product->product_id;
                    }
                    $product = Product::where('product_id', $product_id)->first();
                } else {
                    $product = Product::where('product_id', $products->product_id)->first();
                    $product_id = $product->product_id;
                }

                if ($product) {
                  $QuantityCostsSupplier = DB::table('products')
    ->leftJoin('categories', 'products.Categorie_id', '=', 'categories.categorie_id')
    ->leftJoin('purchases', function ($join) use ($accountingPeriod) {
        $join->on('products.product_id', '=', 'purchases.product_id')
            ->where('purchases.accounting_period_id', $accountingPeriod->accounting_period_id)
            ->whereNotNull('purchases.Supplier_id');
    })
    ->leftJoin('sales', function ($join) use ($accountingPeriod) {
        $join->on('products.product_id', '=', 'sales.product_id')
            ->where('sales.accounting_period_id', $accountingPeriod->accounting_period_id);
    })
    ->leftJoin('sub_accounts', 'purchases.Supplier_id', '=', 'sub_accounts.sub_account_id')
    ->select([
        'products.product_id',
        'products.product_name',
        'products.Purchase_price',
        'sub_accounts.sub_account_id',
        'sub_accounts.sub_name',
        'categories.Purchase_price as unit_price',
        'categories.Categorie_name as category_name',
        'categories.Quantityprice as unit_quantity',
        // حركات المشتريات
        DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type IN (1, 6, 15, 17) THEN purchases.Quantityprice ELSE 0 END), 0) as purchaseToQuantity'),
        DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type IN (1, 6) THEN purchases.Quantityprice ELSE 0 END), 0) as lastQuantity'),
        DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 3 THEN purchases.Quantityprice ELSE 0 END), 0) as warehouseFromQuantity3'),
        DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type IN (1, 6) THEN purchases.Quantityprice ELSE 0 END), 0) as lastPurchase'),
        DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type IN (1, 6) THEN purchases.Total ELSE 0 END), 0) as lastTotal'),
        DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 2 THEN purchases.Quantityprice ELSE 0 END), 0) as returnPurchaseToQuantity'),
        DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 8 THEN purchases.Quantityprice ELSE 0 END), 0) as excess_quantities'),
        DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 9 THEN purchases.Quantityprice ELSE 0 END), 0) as missing_quantities'),
        DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 10 THEN purchases.Quantityprice ELSE 0 END), 0) as damaged_quantity'),
        // حركات المبيعات
        DB::raw('COALESCE(SUM(CASE WHEN sales.transaction_type = 5 THEN sales.quantity ELSE 0 END), 0) as saleQuantity5'),
        DB::raw('COALESCE(SUM(CASE WHEN sales.transaction_type = 5 THEN sales.Selling_price ELSE 0 END), 0) as astsaleQuantity'),
        DB::raw('COALESCE(SUM(CASE WHEN sales.transaction_type = 4 THEN sales.quantity ELSE 0 END), 0) as saleQuantity4')
    ])
    ->groupBy([
        'products.product_id',
        'products.product_name',
        'products.Purchase_price',
        'products.Categorie_id',
        'sub_accounts.sub_account_id',
        'sub_accounts.sub_name',
        'categories.Purchase_price',
        'categories.Categorie_name',
        'categories.Quantityprice'
    ])
    ->orderBy('products.product_name')
    ->get();


                }
               

                $allQuantityonly[] = [
                    'product_id' => $product_id,
                    'product_name' => $product->product_name,
                    'note' => $product->note,
                    'categories' => $categories,
                    'warehouse_name' => $warehouseName,
                    'SumQuantity' => $productPurchase,
                    'accountingPeriod' => $accountingPeriod->created_at,
                    'Myanalysis' => $Myanalysis,


                ];
            }
        }



        $accountingPeriod = $accountingPeriod->created_at;

        $accountingPeriod = Carbon::now()->format('Y-m-d');
        if ($Quantit == "Incomplete") {
            return view('report.print', compact('QuantityIncomplete', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
        }
        if ($Quantit == "inventoryList") {
            if ($DisplayMethod == "ShowAllProducts") {
                $productname = "امر جرد لكل الاصناف  " . " في المخزن: " . $warehouseName;
            }
            if ($DisplayMethod == "SelectedProduct") {
                $productname = "امر جرد للاصناف  المذكورة في الجدول  " . " /في المخزن: " . $warehouseName;
            }
            $toDate = now()->toDateString();

            $Myanalysis = " امر جرد  ";

            return view('inventory.print', compact('inventoryList', 'productname', 'toDate', 'accountingPeriod', 'Myanalysis', 'warehouseName'))->render(); // إرجاع المحتوى كـ HTML
        }
        if ($Quantit == "Quantityonly") {
            return view('report.print', compact('allQuantityonly', 'productname', 'accountingPeriod', 'Myanalysis'))->render(); // إرجاع المحتوى كـ HTML
        }
    }


    public function QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id, $productname, $Quantit, $DisplayMethod)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');
    $accountingPeriodCreatedAtFormatted = Carbon::parse($accountingPeriod->created_at)->format('Y-m-d');
        $accountingPeriod = Carbon::now()->format('Y-m-d');
        if ($Quantit == "QuantityCostsSupplier") {
            $Myanalysis = "الكمية والتكاليف حسب حركة الموردين من تاريخ ". "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeriod;
        }
        if ($Quantit == "QuantitySupplier") {
            $Myanalysis = "للكمية  حسب حركة الموردين من تاريخ ". "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeriod;;
        }
        if ($DisplayMethod == "ShowAllProducts") {
            return   $this->QuantityCostsSupplier($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }
        if ($DisplayMethod == "SelectedProduct") {
            return   $this->QuantitySupplier($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
            if ($Quantit == "QuantityCostsSupplier") {
                return view('report.print', compact('QuantityCostsSupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
            }
            if ($Quantit == "QuantitySupplier") {
                return view('report.print', compact('QuantitySupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
            }
            }
       

         
      
    }
 public function QuantityCostsSupplier($warehouse_to_id, $productname, $Quantit, $DisplayMethod)
{
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    
    if (!$accountingPeriod) {
        return response()->json(['error' => 'No open accounting period found'], 404);
    }

    $query = Product::query()
        ->leftJoin('categories', 'products.Categorie_id', '=', 'categories.categorie_id')
        ->leftJoin('purchases', function ($join) use ($accountingPeriod) {
            $join->on('products.product_id', '=', 'purchases.product_id')
                ->where('purchases.accounting_period_id', $accountingPeriod->accounting_period_id)
                ->whereNotNull('purchases.Supplier_id');
        })
        ->leftJoin('sales', function ($join) use ($accountingPeriod) {
            $join->on('products.product_id', '=', 'sales.product_id')
                ->where('sales.accounting_period_id', $accountingPeriod->accounting_period_id);
        })
        ->leftJoin('sub_accounts', 'purchases.Supplier_id', '=', 'sub_accounts.sub_account_id')
        ->select([
            'products.product_id',
            'products.product_name',
            'products.Purchase_price',
            'sub_accounts.sub_account_id',
            'sub_accounts.sub_name',
            'categories.Purchase_price as unit_price',
            'categories.Categorie_name as category_name',
            'categories.Quantityprice as unit_quantity',
            DB::raw('COALESCE(saleQuantity4.sum_quantity, 0) as saleQuantity4'),
            DB::raw('COALESCE(saleQuantity5.sum_quantity, 0) as saleQuantity5'),
            DB::raw('COALESCE(returnPurchaseToQuantity.sum_quantity, 0) as returnPurchaseToQuantity'),
            DB::raw('COALESCE(purchaseToQuantity.sum_quantity, 0) as purchaseToQuantity'),
            DB::raw('COALESCE(lastTotal.sum_quantity, 0) as lastTotal'),
        ]);

    // إضافة الجداول الفرعية مع تحسين شروط الربط
    $query->leftJoin(DB::raw('(SELECT product_id, supplier_id, SUM(quantity) as sum_quantity 
        FROM sales 
        WHERE transaction_type = 4 
        AND warehouse_to_id = ? 
        AND accounting_period_id = ? 
        GROUP BY product_id, supplier_id) as saleQuantity4'), 
        function ($join) use ($warehouse_to_id, $accountingPeriod) {
            $join->on('products.product_id', '=', 'saleQuantity4.product_id')
                ->on('sub_accounts.sub_account_id', '=', 'saleQuantity4.supplier_id')
                ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
        })
        ->leftJoin(DB::raw('(SELECT product_id, supplier_id, SUM(quantity) as sum_quantity 
        FROM sales 
        WHERE transaction_type = 5 
        AND warehouse_to_id = ? 
        AND accounting_period_id = ? 
        GROUP BY product_id, supplier_id) as saleQuantity5'), 
        function ($join) use ($warehouse_to_id, $accountingPeriod) {
            $join->on('products.product_id', '=', 'saleQuantity5.product_id')
                ->on('sub_accounts.sub_account_id', '=', 'saleQuantity5.supplier_id')
                ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
        })
        ->leftJoin(DB::raw('(SELECT product_id, Supplier_id, SUM(Quantityprice) as sum_quantity 
        FROM purchases 
        WHERE transaction_type = 2 
        AND accounting_period_id = ?
        GROUP BY product_id, Supplier_id) as returnPurchaseToQuantity'), 
        function ($join) use ($accountingPeriod) {
            $join->on('products.product_id', '=', 'returnPurchaseToQuantity.product_id')
                ->on('sub_accounts.sub_account_id', '=', 'returnPurchaseToQuantity.Supplier_id')
                ->addBinding($accountingPeriod->accounting_period_id);
        })
        ->leftJoin(DB::raw('(SELECT product_id, Supplier_id, SUM(Quantityprice) as sum_quantity 
        FROM purchases 
        WHERE transaction_type IN (1, 6, 15, 17) 
        AND warehouse_to_id = ? 
        AND accounting_period_id = ? 
        GROUP BY product_id, Supplier_id) as purchaseToQuantity'), 
        function ($join) use ($warehouse_to_id, $accountingPeriod) {
            $join->on('products.product_id', '=', 'purchaseToQuantity.product_id')
                ->on('sub_accounts.sub_account_id', '=', 'purchaseToQuantity.Supplier_id')
                ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
        })
        ->leftJoin(DB::raw('(SELECT product_id, Supplier_id, SUM(Total) as sum_quantity 
        FROM purchases 
        WHERE transaction_type IN (1, 6, 15, 17) 
        AND warehouse_to_id = ? 
        AND accounting_period_id = ? 
        GROUP BY product_id, Supplier_id) as lastTotal'), 
        function ($join) use ($warehouse_to_id, $accountingPeriod) {
            $join->on('products.product_id', '=', 'lastTotal.product_id')
                ->on('sub_accounts.sub_account_id', '=', 'lastTotal.Supplier_id')
                ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
        });

    $QuantityCostsSupplier = $query
        ->groupBy([
            'products.product_id',
            'products.product_name',
            'products.Purchase_price',
            'products.Categorie_id',
            'sub_accounts.sub_account_id',
            'sub_accounts.sub_name',
            'categories.Purchase_price',
            'categories.Categorie_name',
            'categories.Quantityprice'
        ])
        ->orderBy('products.product_name')
        ->get();

    $productname = "تقرير المخزني لكل الاصناف";
  $accountingPeriodCreatedAtFormatted = Carbon::parse($accountingPeriod->created_at)->format('Y-m-d');
        $accountingPeriod = Carbon::now()->format('Y-m-d');
     
    if ($Quantit == "QuantityCostsSupplier") {
                    $Myanalysis = "الكمية والتكاليف حسب حركة الموردين من تاريخ ". "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeriod;

        return view('report.print', compact('QuantityCostsSupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    }
    
    if ($Quantit == "QuantitySupplier") {
                    $Myanalysis = "للكمية  حسب حركة الموردين من تاريخ ". "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeriod;;

        return view('report.print', [
            'QuantitySupplier' => $QuantityCostsSupplier,
            'productname' => $productname,
            'Myanalysis' => $Myanalysis,
            'accountingPeriod' => $accountingPeriod
        ])->render();
    }
}
  public function QuantitySupplier($warehouse_to_id, $productname, $Quantit, $DisplayMethod)
{
    $product_id = (int)$productname;
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    
    if (!$accountingPeriod) {
        return response()->json(['error' => 'لا توجد فترة محاسبية مفتوحة'], 404);
    }

    $QuantitySupplier = DB::table('sub_accounts')
        ->leftJoin('purchases', function($join) use ($accountingPeriod, $product_id) {
            $join->on('sub_accounts.sub_account_id', '=', 'purchases.Supplier_id')
                ->where('purchases.accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('purchases.product_id', $product_id)
                        ->whereIn('purchases.transaction_type', [1,2, 6, 15, 17]);
                         // إضافة شرط نوع الحركة هنا

        })
      ->leftJoin('sales', function($join) use ($accountingPeriod, $product_id) {
    $join->on('sub_accounts.sub_account_id', '=', 'sales.supplier_id')
        ->where('sales.accounting_period_id', $accountingPeriod->accounting_period_id)
        ->where('sales.product_id', $product_id)
        ->where('sales.transaction_type', 5); // إضافة شرط نوع الحركة هنا
})

        ->leftJoin('products', function($join) use ($product_id) {
            $join->on('products.product_id', '=', 'purchases.product_id')
                ->orOn('products.product_id', '=', 'sales.product_id')
                ->where('products.product_id', $product_id);
        })
        ->leftJoin('categories', 'products.Categorie_id', '=', 'categories.categorie_id')
        ->where(function($query) {
            $query->whereNotNull('purchases.Supplier_id')
                  ->orWhereNotNull('sales.supplier_id')
                  ;
        })
        ->select([
            'sub_accounts.sub_name',
            'products.product_id',
            'products.product_name',
            'products.Purchase_price',
            'categories.Purchase_price as unit_price',
            'categories.Categorie_name as category_name',
            'categories.Quantityprice as unit_quantity',
            // حركات المشتريات
            DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type IN (1, 6, 15, 17) THEN purchases.Quantityprice ELSE 0 END), 0) as purchaseToQuantity'),
            DB::raw('COALESCE(SUM(DISTINCT CASE WHEN purchases.transaction_type = 3 THEN purchases.Quantityprice ELSE 0 END), 0) as warehouseFromQuantity3'),
            DB::raw('COALESCE(SUM(DISTINCT CASE WHEN purchases.transaction_type IN (1, 6, 15, 17) THEN purchases.Total ELSE 0 END), 0) as lastTotal'),
            DB::raw('COALESCE(SUM(DISTINCT CASE WHEN purchases.transaction_type = 2 THEN purchases.Quantityprice ELSE 0 END), 0) as returnPurchaseToQuantity'),
            DB::raw('COALESCE(SUM(DISTINCT CASE WHEN purchases.transaction_type = 8 THEN purchases.Quantityprice ELSE 0 END), 0) as excess_quantities'),
            DB::raw('COALESCE(SUM(DISTINCT CASE WHEN purchases.transaction_type = 9 THEN purchases.Quantityprice ELSE 0 END), 0) as missing_quantities'),
            DB::raw('COALESCE(SUM(DISTINCT CASE WHEN purchases.transaction_type = 10 THEN purchases.Quantityprice ELSE 0 END), 0) as damaged_quantity'),
            // حركات المبيعات
          DB::raw('COALESCE(SUM(DISTINCT CASE WHEN sales.transaction_type = 5 THEN sales.quantity ELSE 0 END), 0) as saleQuantity5'),        ])
        ->groupBy(
            'sub_accounts.sub_name',
            'products.product_id',
            'products.product_name',
            'products.Purchase_price',
            'categories.Purchase_price',
            'categories.Categorie_name',
            'categories.Quantityprice'
        )
        ->orderBy('sub_accounts.sub_name')
        ->get();

  $accountingPeriodCreatedAtFormatted = Carbon::parse($accountingPeriod->created_at)->format('Y-m-d');
        $accountingPeriod = Carbon::now()->format('Y-m-d');
    if ($Quantit == "QuantityCostsSupplier") {
                    $Myanalysis = "الكمية والتكاليف حسب حركة الموردين من تاريخ ". "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeriod;
        return view('report.print',['SelecetQuantityCostsSupplier'=>$QuantitySupplier] , 
        compact( 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    }
    
    if ($Quantit == "QuantitySupplier") {
        $productname = "تقرير المخزني: ";
                            $Myanalysis = "للكمية  حسب حركة الموردين من تاريخ ". "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeriod;;

        return view('report.print', compact('QuantitySupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render();
    }
}



    public function Quantityonly($Quantit, $warehouse_to_id, $productname)
    {
        if ($Quantit == "QuantityCosts") {
            $Myanalysis = "الكمية والتكاليف  ";
        }
        if ($Quantit == "Quantityonly") {
            $Myanalysis = "الكمية فقط  ";
        }

        $product_id = (int)$productname; // استبدل هذا بقيمة المنتج الذي تريد تصفيته
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $QuantityCostsSupplier = DB::table('products')
            ->select([
                'products.product_id',
                'products.product_name',
                'products.Purchase_price',
                'products.Categorie_id',
                'sub_accounts.sub_account_id',
                'sub_accounts.sub_name',
                // حركات المشتريات
                DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type IN (1, 6) THEN purchases.Quantityprice ELSE 0 END), 0) as purchaseToQuantity'),
                DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type IN (1,6) THEN purchases.Quantityprice ELSE 0 END), 0) as lastQuantity'),
                DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 3 THEN purchases.Quantityprice ELSE 0 END), 0) as warehouseFromQuantity3'),
                DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type IN (1, 6) THEN purchases.Quantityprice ELSE 0 END), 0) as lastPurchase'),
                DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type IN (1, 6) THEN purchases.Total ELSE 0 END), 0) as lastTotal'),
                DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 2 THEN purchases.Quantityprice ELSE 0 END), 0) as returnPurchaseToQuantity'),
                DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 8 THEN purchases.Quantityprice ELSE 0 END), 0) as excess_quantities'),
                DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 9 THEN purchases.Quantityprice ELSE 0 END), 0) as missing_quantities'),
                DB::raw('COALESCE(SUM(CASE WHEN purchases.transaction_type = 10 THEN purchases.Quantityprice ELSE 0 END), 0) as damaged_quantity'),
                // حركات المبيعات
                DB::raw('COALESCE(SUM(CASE WHEN sales.transaction_type = 5 THEN sales.quantity ELSE 0 END), 0) as saleQuantity5'),
                DB::raw('COALESCE(SUM(CASE WHEN sales.transaction_type = 5 THEN sales.Selling_price ELSE 0 END), 0) as astsaleQuantity'),
                DB::raw('COALESCE(SUM(CASE WHEN sales.transaction_type = 4 THEN sales.quantity ELSE 0 END), 0) as saleQuantity4'),


            ])
            ->leftJoin('purchases', function ($join) use ($accountingPeriod) {
                $join->on('products.product_id', '=', 'purchases.product_id')
                    ->where('purchases.accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->where('purchases.Supplier_id', "!=", null);
            })
            ->leftJoin('sales', function ($join) use ($accountingPeriod) {
                $join->on('products.product_id', '=', 'sales.product_id')
                    ->where('sales.accounting_period_id', $accountingPeriod->accounting_period_id);
            })
            ->leftJoin('sub_accounts', 'purchases.Supplier_id', '=', 'sub_accounts.sub_account_id')
            ->where('products.product_id', $product_id) // هنا تمت إضافة التصفية حسب المنتج

            ->groupBy(
                'products.product_id',
                'products.product_name',
                'products.Purchase_price',
                'products.Categorie_id',


                'sub_accounts.sub_account_id',
                'sub_accounts.sub_name'
            )
            ->orderBy('products.product_name')
            ->orderBy('sub_accounts.sub_name')
            ->get();
        $accountingPeriod = $accountingPeriod->created_at;
        // التحقق من وجود المنتج
        $productData = Product::where('product_id', $productname)->first();

        $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');
        $categories = Category::where()
            ->first();

        if (!$productData) {
            return response()->json(['success' => false, 'message' => 'المنتج غير موجود']);
        }
        

        $accountingPeriod = $accountingPeriod->created_at;
        if ($Quantit == "Quantityonly") {
            return view('report.print', compact('productData', 'Myanalysis', 'accountingPeriod', 'productPurchase', 'categories', 'warehouseName'))->render(); // إرجاع المحتوى كـ HTML
        }
        if ($Quantit == "QuantityCosts") {
            $productDataCosts = $productData;
            return view('report.print', compact('productDataCosts', 'Myanalysis', 'accountingPeriod', 'productPurchase', 'categories', 'warehouseName'))->render(); // إرجاع المحتوى كـ HTML

        }
    }


    public function store(Request $request)
    {


        if (!$request->product_name) {
            return response()->json([
                'success' => false,
                'message' => 'يجب عليك تحديد اسم المنتج.'
            ]);
        }
        if (!$request->producid && !$request->cate) {
            return response()->json([
                'success' => false,
                'message' => 'يجب عليك ان شاء   وحدة.'
            ]);
        }
        // dd($request->product_name);

        // تحويل الأرقام العربية إلى الإنجليزية
        $Quantity = $this->convertArabicNumbersToEnglish($request->input('Quantity'));
        $Selling_price = $this->convertArabicNumbersToEnglish($request->input('Selling_price'));
        $Purchase_price = $this->convertArabicNumbersToEnglish($request->input('Purchase_price'));
        $Regular_discount = $this->convertArabicNumbersToEnglish($request->input('Regular_discount'));
        $Special_discount = $this->convertArabicNumbersToEnglish($request->input('Special_discount'));
        $Quantityprice = $this->convertArabicNumbersToEnglish($request->input('Quantityprice'));
        $product_idUpdate = $this->convertArabicNumbersToEnglish($request->input('producid'));
        $product_idUpdate = intval($product_idUpdate);

        if (!$product_idUpdate) {
            $productname = Product::where('product_name', $request->product_name)->first();
            if ($productname) {
                return response()->json([
                    'success' => false,
                    'message' => 'يوجد نفس هذا الاسم من قبل.'
                ]);
            }
        }
        if ($Quantity > 0) {
            // التحقق من وجود المخزن وسعر الشراء وسعر البيع
            if (!$request->account_debitid) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب عليك تحديد مخزن.'
                ]);
            }
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
                'message' => 'يجب عليك تحديد سعر البيع.'
            ]);
        }

        if ($request->expiry_date) {

            if (strtotime($request->expiry_date) < strtotime('today')) {
                return response()->json([
                    'success' => false,
                    'message' => 'تاريخ الانتهاء غير صالح!'
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
                'expiry_date' => $request->expiry_date,
                'warehouse_id' => $request->account_debitid,
            ]
        );

        $product_name = Product::where('product_id', $ProductNew->product_id)->value('product_name');
        $produc = Product::where('product_id', $ProductNew->product_id)->first();

        // التحقق من كمية المنتج وإنشاء سجل في جدول المشتريات
        if ($request->cate) {
            $Post = new Category;

            $Post->Categorie_name = $request->cate;
            $Post->product_id = $produc->product_id;
            $Post->Purchase_price = $produc->Purchase_price;
            $Post->Selling_price = $produc->Selling_price;
            $Post->Quantityprice = $request->Quantityprice ?? 1;
            $Post->user_id = $request->user_id;
            $Post->save();
            $ProductNew->update([
                'Categorie_id' => $Post->categorie_id,
            ]);
            if (!$Post->save()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حفظ الفئة.'
                ]);
            }
        }

        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد فترة محاسبية مفتوحة.'
            ]);
        }


        $entrie_id = Purchase::where('purchase_id', $request->purchase_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->whereIn('transaction_type', [6, 7])
            ->first();


        $transaction_type = $entrie_id->transaction_type ?? 6;


        if ($Quantity > 0) {

            try {
                $purchase = Purchase::updateOrCreate(
                    [
                        'accounting_period_id' => $accountingPeriod->accounting_period_id,
                        'purchase_id' => $request->purchase_id,
                    ],
                    [
                        'transaction_type' =>   $entrie_id->transaction_type ?? 6,
                        'product_id' => $produc->product_id,
                        'Purchase_invoice_id' => null,
                        'Product_name' => $product_name,
                        'Barcode' => $produc->Barcode ?? 0,
                        'quantity' => $Quantity * $request->Quantityprice,
                        'Quantityprice' => $Quantity ?? 0,
                        'Purchase_price' => $Purchase_price,
                        'Selling_price' => $Selling_price,
                        'Total' => $Purchase_price * $Quantity,
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
                        'categorie_id' => null,
                    ]
                );
                return response()->json([
                    'success' => true,
                    'message' => 'تم الحفظ بنجاح-تم تحديث مخزن-تم حفظ الوحدة.',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إنشاء أو تحديث السجل: ' . $e->getMessage()
                ]);
            }
        }
        if ($product_idUpdate) {
            return response()->json([
                'success' => true,
                'message' => 'تم تعديل الصنف بنجاح.',
            ]);
        }
        if ($Post) {
            return response()->json([
                'success' => true,
                'message' => 'تم الحفظ بنجاح-تم حفظ الوحدة.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم الحفظ بنجاح.',
        ]);
    }
    public function edit($id)
    {
        $prod = Product::where('product_id', $id)->first();
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $purchaseid = Purchase::where('product_id', $id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->whereIn('transaction_type', [6, 7])
            ->first();
        $purchaseid = $purchaseid->purchase_id ?? null;
        $editProduct = "تعديل الصنف";
        return view(
            'products.create',
            [
                'prod' => $prod,
                'editProduct' => $editProduct,
                'purchaseid' => $purchaseid,
            ]
        );
    }
    public function update(Request $request, $id)
    {
        Product::where('product_id', $id)->update([
            'Barcode' => $request->Barcode,
            'product_name' => $request->product_name,
            'Quantity' => $request->Quantity,
            'Purchase_price' => $request->Purchase_price,
            'Selling_price' => $request->Selling_price,
            'Regular_discount' => $request->Regular_discount,
            'Special_discount' => $request->Special_discount,
            'User_id' => auth()->id(),
            'Total' => $request->Total,
            'Cost' => $request->Cost,
            'Profit' => $request->Profit,
            'note' => $request->note,
            'warehouse_id' => $request->warehouse_id,
        ]);
        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        Product::where('product_id', $id)->delete();
        return response()->json(['success' => 'success', 'message' => 'تم   حذف المنتج بنجاح!']);
    }
    public function price($id)
    {
        $prod = Category::where('categorie_id', $id)->first();
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
                    ->orWhere('transaction_type', 8)
                    ->orWhere('transaction_type', 3);
            })
            ->select('product_id', 'Product_name') // اختيار الأعمدة المطلوبة
            ->distinct() // التأكد من جلب القيم المميزة
            ->get(); // جلب النتائج كمجموعة بيانات
        return response()->json($uniqueProducts);
    }
}
