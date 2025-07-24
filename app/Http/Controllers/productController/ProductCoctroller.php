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
        $warehouse_to_id = $validated['warehouseid'];
        // $accountingPeriod =$validated['accountingPeriodData'];
        $warehouse_to_id = $this->convertArabicNumbersToEnglish($warehouse_to_id);
        $productname = $validated['productname'];
        $productname = $this->convertArabicNumbersToEnglish($productname);
        $Quantit = $validated['Quantit'];
        $DisplayMethod = $validated['DisplayMethod'];

        if ($Quantit === "QuantityCostsSupplier") {
            return $this->QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }
        if ($Quantit === "QuantitySupplier") {
            return $this->QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }

        if ($validated['Quantit'] === "QuantityCosts") {
            return $this->ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }
        if ($validated['Quantit'] === "Incomplete") {
            return $this->ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }

        if ($validated['Quantit'] === "Quantityonly") {
            return $this->ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }
        if ($validated['Quantit'] === "inventoryList") {
            return $this->ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }

        if ($validated['DisplayMethod'] === "ShowAllProducts") {
            if ($validated['Quantit'] === "QuantityCosts") {
                return $this->QuantityAndCostsAccordingToSuppliersMovement($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
            }
        }
        if ($validated['DisplayMethod'] === "SelectedProduct") {
            if ($validated['Quantit'] === "QuantityCosts") {
                return $this->Quantityonly($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
            }
        }
    }
    public function allQuantityCosts($warehouse_to_id, $accountingPeriod, $Quantit)
    {
        $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');
        $productname = " تقرير  مخزني   لكل الاصناف في   " . "  " . $warehouseName;
        $allQuantityCosts = DB::table('products')
            ->leftJoin('categories', 'products.Categorie_id', '=', 'categories.categorie_id')

            ->select([
                'products.product_id',
                'products.product_name',
                'products.Purchase_price',
                'categories.Purchase_price as unit_price ',
                'categories.Categorie_name  as category_name',
                'categories.Quantityprice  as unit_quantity',

                DB::raw('COALESCE(saleQuantity4.sum_quantity, 0) as saleQuantity4'),
                DB::raw('COALESCE(saleQuantity5.sum_quantity, 0) as saleQuantity5'),
                DB::raw('COALESCE(warehouseFromQuantity3.sum_quantity, 0) as warehouseFromQuantity3'),
                DB::raw('COALESCE(warehouseFromQuantity.sum_quantity, 0) as warehouseFromQuantity'),
                DB::raw('COALESCE(purchaseToQuantity.sum_quantity, 0) as purchaseToQuantity')
            ])
            ->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as sum_quantity FROM sales WHERE transaction_type = 4 AND warehouse_to_id = ? AND accounting_period_id = ? GROUP BY product_id) as saleQuantity4'), function ($join) use ($warehouse_to_id, $accountingPeriod) {
                $join->on('products.product_id', '=', 'saleQuantity4.product_id')
                    ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
            })
            ->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as sum_quantity FROM sales WHERE transaction_type = 5 AND warehouse_to_id = ? AND accounting_period_id = ? GROUP BY product_id) as saleQuantity5'), function ($join) use ($warehouse_to_id, $accountingPeriod) {
                $join->on('products.product_id', '=', 'saleQuantity5.product_id')
                    ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
            })
            ->leftJoin(DB::raw('(SELECT product_id, SUM(Quantityprice) as sum_quantity FROM purchases WHERE transaction_type IN (3,9,10) AND warehouse_from_id = ? AND accounting_period_id = ? GROUP BY product_id) as warehouseFromQuantity3'), function ($join) use ($warehouse_to_id, $accountingPeriod) {
                $join->on('products.product_id', '=', 'warehouseFromQuantity3.product_id')
                    ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
            })
            ->leftJoin(DB::raw('(SELECT product_id, SUM(Quantityprice) as sum_quantity FROM purchases WHERE transaction_type = 2 GROUP BY product_id) as warehouseFromQuantity'), function ($join) {
                $join->on('products.product_id', '=', 'warehouseFromQuantity.product_id');
            })
            ->leftJoin(DB::raw('(SELECT product_id, SUM(Quantityprice) as sum_quantity FROM purchases WHERE transaction_type IN (1, 6, 3, 7,8) AND warehouse_to_id = ? AND accounting_period_id = ? GROUP BY product_id) as purchaseToQuantity'), function ($join) use ($warehouse_to_id, $accountingPeriod) {
                $join->on('products.product_id', '=', 'purchaseToQuantity.product_id')
                    ->addBinding([$warehouse_to_id, $accountingPeriod->accounting_period_id]);
            })
            ->get();

        $accountingPeriodCreatedAtFormatted = Carbon::parse($accountingPeriod->created_at)->format('Y-m-d');
        $accountingPeriod = Carbon::now()->format('Y-m-d');


        // dd($Quantit);
        if ($Quantit == "QuantityCosts") {
            $Myanalysis = "تقرير مخزني - الكمية والتكاليف - " . " " . "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeriod;
            return view('report.print', compact('allQuantityCosts', 'productname', 'Myanalysis', 'accountingPeriod'))->render();
        } else if ($Quantit == "Quantityonly") {

            $allQuantityonly = $allQuantityCosts;
            $Myanalysis = "تقرير مخزني - الكميات المتوفرة  - " . $accountingPeriod;
            //allQuantityonly
            return view('report.print', compact('allQuantityonly', 'productname', 'Myanalysis', 'accountingPeriod'))->render();
        }
        // إرجاع المحتوى كـ HTML


    }

    public function selectedProduct($warehouse_to_id, $accountingPeriod, $productname, $Quantit)
    {
        $firstQuantityCosts = Product::where('product_id', (int)$productname)

            ->with(['sales', 'purchases' => function ($query) use ($accountingPeriod, $warehouse_to_id) {
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
                    $query->whereIn('transaction_type', [3, 9, 10])
                        ->where('warehouse_from_id', $warehouse_to_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                },
                'purchases as warehouseFromQuantity' => function ($query) use ($warehouse_to_id, $accountingPeriod) {
                    $query->where('transaction_type', 2)
                        ->where('warehouse_from_id', $warehouse_to_id)


                    ;
                },
                'purchases as purchaseToQuantity' => function ($query) use ($warehouse_to_id, $accountingPeriod) {
                    $query->whereIn('transaction_type', [1, 3, 6, 7, 8])
                        ->where('warehouse_to_id', $warehouse_to_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                }
            ], 'Quantityprice')
            ->first();
        $accountingPeriodCreatedAtFormatted = Carbon::parse($accountingPeriod->created_at)->format('Y-m-d');
        $accountingPeriod = Carbon::now()->format('Y-m-d');

        if ($Quantit == "Quantityonly") {
            $Myanalysis = "تقرير مخزني - الكمية المتوفرة  - " . " " . "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeriod;
            $firstQuantityonly = $firstQuantityCosts;
            //allQuantityonly
            return view('report.print', compact('firstQuantityonly', 'productname', 'accountingPeriod', 'Myanalysis'))->render(); // إرجاع المحتوى كـ HTML
        }
        if ($Quantit == "QuantityCosts") {
            $Myanalysis = "تقرير مخزني - الكمية والتكاليف - " . " " . "من " . $accountingPeriodCreatedAtFormatted . " " . "الى " . $accountingPeriod;
            return view('report.print', compact('firstQuantityCosts', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
        }
    }

    public function ShowAllProducts($warehouse_to_id, $productname, $Quantit, $DisplayMethod)
    {
        $warehouse_to_id = (int)$warehouse_to_id;

        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        // dd($warehouse_to_id);

        //     $balances = Sale::selectRaw('

        //     SUM(CASE WHEN sales.transaction_type = 4 THEN sales.quantity ELSE 0 END) as total_out,
        //     SUM(CASE WHEN sales.transaction_type = 5 THEN sales.quantity ELSE 0 END) as total_transfer
        // ')
        // ->where('warehouse_to_id', $warehouse_to_id)
        // ->where('product_id', $productname)
        // ->get();

        //
        // $Myanalysis="الكمية والتكاليف من تاريخ";
        if ($Quantit == "inventoryList") {
            $Myanalysis = " امر جرد  ";
        }


        $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');
        if ($DisplayMethod == "ShowAllProducts") {
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
                    $categories = Category::where('product_id', $product_id ?? $product->product_id)->first();
                    // حساب الكميات بناءً على نوع المعاملة والمخزن
                    $purchaseToQuantity = Purchase::where('product_id', $product_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                        ->where('warehouse_to_id', $warehouse_to_id)
                        ->whereIn('transaction_type', [1, 6, 3, 7, 8])
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
                    //


                    $saleQuantity4 = Sale::where('product_id', $product_id)
                        ->where('warehouse_to_id', $warehouse_to_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                        ->where('transaction_type', 4)
                        ->sum('quantity');


                    $productPurchase = ($purchaseToQuantity + $saleQuantity5) - $warehouseFromQuantity - $warehouseFromQuantity3 - $saleQuantity4;
                    // dd( $purchaseToQuantity);
                    $InventoryQuantity = Inventory::where('product_id', $product_id)
                        ->where('StoreId', $warehouse_to_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                        ->sum('quantity');
                    $QuantityDifference = $productPurchase - $InventoryQuantity;


                    if ($Quantit == "QuantityCosts") {
                        if (!in_array($product_id, $QuantityCosts1)) {


                            $QuantityCosts1[] = $product_id;
                            $allQuantityCosts[] = [
                                'accountingPeriod' => $accountingPeriod->created_at,
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
                }
                if ($Quantit == "inventoryList") {
                    if (!in_array($product_id, $inventoryList1)) {


                        $inventoryList1[] = $product_id;
                        $accountingPerio = Carbon::today()->format('Y-m-d');

                        $inventoryList[] = [
                            'product_id' => $product_id,
                            'product_name' => $product->product_name,
                            'note' => $product->note,
                            'categories' => $categories,
                            'warehouse_name' => $warehouseName,
                            'SumQuantity' => $productPurchase,
                            'accountingPeriod' => $accountingPerio,
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
                    'accountingPeriod' => $accountingPeriod->created_at,
                    'Myanalysis' => $Myanalysis,


                ];
            }
        }



        $accountingPeriod = $accountingPeriod->created_at;

        $accountingPeriod = Carbon::now()->format('Y-m-d');
        if ($Quantit == "Incomplete") {
            // dd($inventoryData);

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

        if ($Quantit == "QuantityCostsSupplier") {
            $Myanalysis = "الكمية والتكاليف حسب حركة الموردين من تاريخ ";
        }
        if ($Quantit == "QuantitySupplier") {
            $Myanalysis = "للكمية  حسب حركة الموردين من تاريخ ";
        }


        $accountingPeriod = $accountingPeriod->created_at;
        if ($DisplayMethod == "ShowAllProducts") {
            return   $this->QuantityCostsSupplier($warehouse_to_id, $productname, $Quantit, $DisplayMethod);
        }
        if ($DisplayMethod == "SelectedProduct") {

            // إرجاع المحتوى كـ HTML

            return   $this->QuantitySupplier($warehouse_to_id, $productname, $Quantit, $DisplayMethod);




            if ($Quantit == "QuantityCostsSupplier") {

                return view('report.print', compact('QuantityCostsSupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
            }
            if ($Quantit == "QuantitySupplier") {
                return view('report.print', compact('QuantitySupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML

            }
            // dd($allQuantityCosts);
            $warehouseName = SubAccount::where('sub_account_id', $warehouse_to_id)->value('sub_name');
            $Suppliers = SubAccount::where('account_class', 2)->get();

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

        if ($DisplayMethod == "SelectedProduct") {
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
            $product_id = $products->product_id ?? $productname;

            $product = Product::where('product_id',  $product_id)->first();
            foreach ($Suppliers as $Supplier) {
                if ($product) {

                    $categories = Category::where('product_id', $product_id)->first();
                    $SupplierData = SubAccount::where('sub_account_id', $Supplier->sub_account_id)->first();
                    // $purchaseToQuantity = Purchase::where('product_id', $product_id)
                    //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    //     ->where('Supplier_id', $Supplier->sub_account_id)
                    //     ->whereIn('transaction_type', [1, 6,7,8])
                    //     ->sum('quantity');

                    // $lastPurchase = Purchase::where('product_id', $product_id)
                    //     ->where('Supplier_id', $Supplier->sub_account_id)
                    //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    //     ->whereIn('transaction_type', [1, 6])
                    //     ->sum('Purchase_price');

                    // $warehouseFromQuantity = Purchase::where('product_id', $product_id)
                    //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    //     ->where('transaction_type', 2)
                    //     ->where('Supplier_id', $Supplier->sub_account_id)
                    //     ->sum('quantity');

                    // $warehouseFromQuantity3 = Purchase::where('product_id', $product_id)
                    //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    //     ->where('transaction_type', 3)
                    //     ->where('Supplier_id', $Supplier->sub_account_id)
                    //     ->sum('quantity');

                    // $saleQuantity5 = Sale::where('product_id', $product_id)
                    //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    //     ->where('transaction_type', 5)
                    //     ->where('supplier_id', $Supplier->sub_account_id)
                    //     ->sum('quantity');
                    // $astsaleQuantity = Sale::where('product_id', $product_id)
                    //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    //     ->where('transaction_type', 5)
                    //     ->where('supplier_id', $Supplier->sub_account_id)
                    //     ->sum('Selling_price');

                    // $saleQuantity4 = Sale::where('product_id', $product_id)
                    //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    //     ->where('transaction_type', 4)
                    //     ->where('supplier_id', $Supplier->sub_account_id)
                    //     ->sum('quantity');
                    $SumQuantity = ($purchaseToQuantity + $saleQuantity4) - $warehouseFromQuantity - $warehouseFromQuantity3 - $saleQuantity4;
                    // تخزين البيانات في مصفوفة
                    if ($purchaseToQuantity) {
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
                            'SumQuantity' => $SumQuantity,
                            'saleQuantity5' => $saleQuantity5,
                            'purchaseToQuantity' => $purchaseToQuantity,
                            'returnPurchaseToQuantity' => $warehouseFromQuantity,
                            'warehouseFromQuantity3' => $warehouseFromQuantity3,
                            'Myanalysis' =>  $Myanalysis,

                        ];
                        $QuantityCostsSupplier[] = [
                            'product_id' => $product->product_id,
                            'Purchase_price' => $product->Purchase_price,
                            'astPurchase' => $lastPurchase,
                            'astsaleQuantity' => $astsaleQuantity,
                            'accountingPeriod' => $accountingPeriod->created_at,
                            'product_name' => $product->product_name,
                            'saleQuantity5' => $saleQuantity5,
                            'note' => $product->note,
                            'SupplierData' => $SupplierData,
                            'categories' => $categories,
                            'warehouse_name' => $warehouseName,
                            'SumQuantity' => $SumQuantity,
                            'purchaseToQuantity' => $purchaseToQuantity,
                            'returnPurchaseToQuantity' => $warehouseFromQuantity,
                            'warehouseFromQuantity3' => $warehouseFromQuantity3,
                            'Myanalysis' =>  $Myanalysis,
                        ];
                    }
                }
            }
        }
        $accountingPeriod = $accountingPeriod->created_at;
        if ($DisplayMethod == "ShowAllProducts") {
            $productname = " تقرير  المخزني   لكل الاصناف في مخزن  " . "  " . $warehouseName;
        }
        if ($DisplayMethod == "SelectedProduct") {
            $productname = "تقرير  المخزني لصنف :  " . $product->product_name . " /في المخزن: " . $warehouseName;
        }
        if ($Quantit == "QuantityCostsSupplier") {

            return view('report.print', compact('QuantityCostsSupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML
        }
        if ($Quantit == "QuantitySupplier") {
            return view('report.print', compact('QuantitySupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML

        }
    }
    public function QuantityCostsSupplier($warehouse_to_id, $productname, $Quantit, $DisplayMethod)
    {


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
        $productname = " تقرير  المخزني   لكل الاصناف  ";

        if ($Quantit == "QuantityCostsSupplier") {
            $Myanalysis = "الكمية والتكاليف حسب حركة الموردين من تاريخ " . $accountingPeriod;
            return view('report.print', compact('QuantityCostsSupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML

        }
        if ($Quantit == "QuantitySupplier") {
            $Myanalysis = "للكمية  حسب حركة الموردين من تاريخ " . $accountingPeriod;
        }
    }
    public function QuantitySupplier($warehouse_to_id, $productname, $Quantit, $DisplayMethod)
    {


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

        if ($Quantit == "QuantityCostsSupplier") {
            $Myanalysis = "الكمية والتكاليف حسب حركة الموردين من تاريخ ";

            return view('report.print', compact('QuantityCostsSupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render(); // إرجاع المحتوى كـ HTML

        }
        if ($Quantit == "QuantitySupplier") {
            $Myanalysis = "للكمية  حسب حركة الموردين من تاريخ ";

            $productname = "تقرير  المخزني  : ";
            return view('report.print', compact('QuantityCostsSupplier', 'productname', 'Myanalysis', 'accountingPeriod'))->render();
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
        // حساب الكميات بناءً على نوع المعاملة والمخزن
        // $purchaseToQuantity = Purchase::where('product_id', 758)
        //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        //     ->whereIn('transaction_type', [1, 6, 3, 7,8])
        //     ->sum('Quantityprice');

        // $warehouseFromQuantity = Purchase::where('product_id', $productname)
        //     ->where('warehouse_from_id', $warehouse_to_id)
        //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        //     ->where('transaction_type', 2)
        //     ->sum('Quantityprice');

        // $warehouseFromQuantity3 = Purchase::where('product_id', $productname)
        //     ->where('warehouse_from_id', $warehouse_to_id)
        //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        //     ->where('transaction_type', 3)
        //     ->sum('Quantityprice');

        // $saleQuantity5 = Sale::where('product_id', $productname)
        //     ->where('warehouse_to_id', $warehouse_to_id)
        //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        //     ->where('transaction_type', 5)
        //     ->sum('quantity');

        // $saleQuantity4 = Sale::where('product_id', $productname)
        //     ->where('warehouse_to_id', $warehouse_to_id)
        //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        //     ->where('transaction_type', 4)
        //     ->sum('quantity');


        // $productPurchase = ($purchaseToQuantity + $saleQuantity5) - $warehouseFromQuantity - $warehouseFromQuantity3 - $saleQuantity4;

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
