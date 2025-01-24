<?php

namespace App\Http\Controllers\SaleCoctroller;

use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Category;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\Default_customer;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleInvoice;
use App\Models\SubAccount;
use App\Models\User;
use App\Models\UserPermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    //
    public function create(){
        $customers=SubAccount::where('AccountClass',1)->get();
        $financialt=SubAccount::where('AccountClass',5)->get();
        $DefaultCustomer  = Default_customer::where('id',1)->first();
        $financial_account = Default_customer::where('id',1)->pluck('financial_account_id')->first();
       
        $Currency_name=Currency::all();
        $MainAccounts= MainAccount::all();
        $user=auth()->id();
        $AuthorityName="المبيعات";
        $us=UserPermission::where('User_id', $user)
        ->where('Authority_Name',$AuthorityName)
        ->first();
        if ( $us) {
            return view('sales.create', [
                'customers' => $customers,
                'DefaultCustomer' => $DefaultCustomer,
                'Currency_name' => $Currency_name,
                'MainAccounts' => $MainAccounts,
                'financial_account' => $financial_account,
                'financialts' => $financialt,
            ]);
        } else {
            return view('auth.login');
        }
       
    }
    private function removeCommas($value)
    {
        return floatval(str_replace(',', '', $value)); // إزالة الفواصل وتحويل إلى float
    }
    public function store(Request $request)
    {
        
 $user=auth()->id();
 $AuthorityName="المبيعات";
 $us=UserPermission::where('User_id', $user)
 ->where('Authority_Name',$AuthorityName)
 ->first();
 if (optional($us)->Readability == 1) {
        $purchasePrice = $this->removeCommas($request->Purchase_price);
        $Selling_price = $this->removeCommas($request->Selling_price);
        $total_price = $this->removeCommas($request->total_price);
        $total_price = $this->removeCommas($request->total_price);
        $Cost = $this->removeCommas($request->Cost);
        $discount_rate = $this->removeCommas($request->discount_rate);
        $Profit = $this->removeCommas($request->Profit);
        $total_discount_rate = $this->removeCommas($request->total_discount_rate);
        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'product_id' => 'required|integer|exists:products,product_id',
            'sales_invoice_id' => 'required|integer|exists:sales_invoices,sales_invoice_id',
            'Quantity' => 'required|numeric|min:0',
            'Selling_price' => 'required',
            'account_debitid' => 'required|integer|exists:sub_accounts,sub_account_id',
            'Barcode' => 'nullable|numeric',
            'total_price' => 'required|string|',
            'total_discount_rate' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);
        
        try {
          
            $categorie = Category::where('product_id', $request->product_id)
        ->where('categorie_id', $request->Categorie_name)
        ->orwhere('Categorie_name', $request->Categorie_name)
        ->value('Categorie_name');
          


            // الحصول على اسم المنتج
            $Product = Product::where('product_id', $request->product_id)->first();
            // التحقق من وجود الفترة المحاسبية
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            // التحقق من وجود الفاتورة
            $saleInvoice = SaleInvoice::where('sales_invoice_id', $request->sales_invoice_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->first();
               
               // التحقق من تطابق الفترات المحاسبية
if ($accountingPeriod->accounting_period_id !== $saleInvoice->accounting_period_id) {
    Log::info('أصبحت هذه الفاتورة لسنة قديمة، لا يمكنك إضافة أي منتج إليها.');
    return response()->json([
        'success' => false,
     'message' => 'أصبحت هذه الفاتورة لسنة قديمة، لا يمكنك إضافة أي منتج إليها.']);
}

               if (!$accountingPeriod) {
                return response()->json([
                    'success' => false,
                     'message' => 'لا توجد فترة محاسبية مفتوحة.']);
            }
            if (!$saleInvoice) {
                return response()->json(['success' => false, 'message' => 'الفاتورة غير موجودة.']);
            } 
           // حفظ أو تحديث عملية البيع
           $Transaction_type=  $saleInvoice->transaction_type;
           $Purchase = Category::where('product_id', $request->product_id)
           ->where('categorie_id', $request->Categorie_name)
           ->first();
          $purchasePrice=$Purchase->Purchase_price ??0;
          $SellingPrice=$Selling_price ??0;
           $Profit1=$SellingPrice-$purchasePrice;
            $total_Profit = $request->Quantity * $Profit1;

        //    $request->Quantity*
        //    dd( $request->Purchase_price);
            $sales = Sale::updateOrCreate(
                [
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'Invoice_id' => $saleInvoice->sales_invoice_id,
                    'Product_name' => $Product->product_name,
                    'product_id' => $Product->product_id,
                    'Category_name' => $categorie,
                ],
                [
                    'Barcode' => $Product->Barcode ?? '',
                    'Selling_price' => $Selling_price,
                    'Purchase_price' =>  $Purchase->Purchase_price?? 0,
                    'Profit' => $Profit1??0,
                    'total_Profit' => $total_Profit??0,
                    'Quantityprice' => $request->Quantity,
                    'quantity' => $request->Quantityprice,
                    'discount_rate' => $discount_rate ?? 0,
                    'discount' => $total_discount_rate ?? 0,
                    'total_amount' => $request->Quantity * $Selling_price,
                    'total_price' => $total_price,
                    'currency' =>$saleInvoice->currency_id?? null,
                    'transaction_type' => $Transaction_type,
                    'supplier_id' => $request->Supplier?? null ,
                    'Customer_id' => $saleInvoice->Customer_id ,
                    'User_id' => auth()->id(),
                    'warehouse_to_id' => $request->account_debitid,
                    'financial_account_id' => $saleInvoice->account_id,
                    'shipping_cost' => $request->shipping_cost ?? 0,
                    'note' => $request->note ?? '',
                ]
            );
           

            $total_price_sale = Sale::where('Invoice_id', $saleInvoice->sales_invoice_id )
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_amount');
            $net_total_after_discount = Sale::where('Invoice_id', $saleInvoice->sales_invoice_id )
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_price');
            $discount = Sale::where('Invoice_id', $saleInvoice->sales_invoice_id )
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('discount');

           
            $Profits = Sale::where('Invoice_id', $saleInvoice->sales_invoice_id )
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_Profit');
            $total_Profit=$Profits-$discount;

            $paid_amount = 0;
            $account_debit = null;
            $account_Credit = null;
            $DefaultCustomer = Default_customer::where('id', 1)->first();
            $warehouse_id = SubAccount::where('sub_account_id', $DefaultCustomer->warehouse_id)->value('sub_account_id'); 
            // تحديد الحساب المدين والمبلغ المدفوع بناءً على نوع الدفع
            if ($saleInvoice->transaction_type ===4) 
            {
        if (in_array($saleInvoice->payment_type, [1, 3, 4])){
                $account_Credit= $request->account_debitid;
                $account_debit = $saleInvoice->account_id;
                $paid_amount = $net_total_after_discount;

            } elseif ($saleInvoice->payment_type === 2)
             {
                $account_Credit= $warehouse_id ;
                $account_debit= $saleInvoice->Customer_id;
                $paid_amount = $net_total_after_discount;
            
            }
        }  

            if ($saleInvoice->transaction_type ===5) 
            {
              
        if (in_array($saleInvoice->payment_type, [1, 3, 4])){
                $account_Credit=$warehouse_id ;
                $account_debit = $saleInvoice->account_id;
                $paid_amount = $net_total_after_discount;

            } elseif ($saleInvoice->payment_type === 2)
             {
                $account_Credit=$saleInvoice->Customer_id ;
                $account_debit= $saleInvoice->account_id ;
                $paid_amount = $net_total_after_discount;
            }
        } 
            
            // تحديث بيانات الفاتورة
            $saleInvoice->update([
                'total_price_sale' => $total_price_sale,
                'net_total_after_discount' => $net_total_after_discount,
                'discount' => $discount,
                'paid_amount' => $paid_amount,
                'remaining_amount' => $net_total_after_discount - $paid_amount,
            ]);
            $today = Carbon::now()->toDateString();
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();
            
            if (!$dailyPage) {
                $dailyPage = GeneralJournal::create([
                    'accounting_period_id'=>$accountingPeriod->accounting_period_id,
                ]);
            }
            if (!$dailyPage || !$dailyPage->page_id) {
                return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
            }
            $transactiontype=   TransactionType::fromValue($saleInvoice->transaction_type)?->label();
            // إعداد بيانات الإدخالات اليومية
            $entrie_id = DailyEntrie::where('Invoice_id',$saleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type',$transactiontype)
            ->first();
             $Getentrie_id= $entrie_id->entrie_id ?? null;
                $daily_page_id = $entrie_id->Daily_page_id ?? $dailyPage->page_id;
            
                    $this->createOrUpdateDailyEntry($saleInvoice, $accountingPeriod,$account_Credit, $account_debit, $net_total_after_discount,$Getentrie_id,$daily_page_id);
                     
        

            // الاستجابة بنجاح
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة عملية البيع بنجاح!',
                'purchase' => $sales,
                'total_price_sale' =>number_format($total_price_sale,2),
                'net_total_after_discount' => number_format($net_total_after_discount,2),
                'discount' => $discount,
                'Profit' => number_format($total_Profit,2)??0,
            ]);
        }  
        
      catch (\Exception $e) {
            Log::error($e->getMessage());
 
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحفظ. حاول مجددًا.'.$e->getMessage(),
                'error' => $e->getMessage(),
            ]);      
          }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد لديك صلاحية ',
            ]); 
            return view('auth.login');
        }
    }
    private function createOrUpdateDailyEntry($saleInvoice, $accountingPeriod,$account_Credit, $account_debit, $net_total_after_discount,$Getentrie_id,$daily_page_id)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();

        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([
                'accounting_period_id'=>$accountingPeriod->accounting_period_id,
            ]);
        }
            // التحقق من وجود الصفحة اليومية
            if (!$dailyPage || !$dailyPage->page_id) {
                return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
            }
            if($saleInvoice->transaction_type ===4)
            {
                 if (in_array($saleInvoice->payment_type, [1, 3, 4]))
            {
                     $commint="لكم";


            }
            elseif
             ($saleInvoice->payment_type ===2)
              {
               
                $commint="عليكم فاتورة";

            }
            }

            
               
                if($saleInvoice->transaction_type ===5)
                {
                    if (in_array($saleInvoice->payment_type, [1, 3, 4]))
                      {
                          $commint=" فاتورة";
                      }
                    if($saleInvoice->payment_type ===2)
              {
                  $commint="لكم فاتورة";

              }

                }
                if($saleInvoice->note)
                {
                    $note="/".$saleInvoice->note ??'';
                }
                else
                {
                    $note='';
                }
         $transactiontype=   TransactionType::fromValue($saleInvoice->transaction_type)?->label();
                         // إنشاء أو تحديث الإدخالات اليومية
            $dailyEntrie = DailyEntrie::updateOrCreate(
                [
                    'entrie_id'=> $Getentrie_id,
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'Invoice_id' => $saleInvoice->sales_invoice_id,
                    'daily_entries_type' =>$transactiontype,
                ],
                [
                    'account_Credit_id' => $account_Credit,
                    'created_at' => $saleInvoice->created_at,
                    'account_debit_id' => $account_debit, 
                    'Amount_Credit' => $net_total_after_discount ?: 0,
                    'Amount_debit' => $net_total_after_discount ?: 0,
                    'Statement' => $commint." ".$transactiontype." ".PaymentType::tryFrom($saleInvoice->payment_type)?->label().$note ,
                    'Daily_page_id' => $daily_page_id ?? $dailyPage->page_id,
                    'Invoice_type' => $saleInvoice->payment_type,
                    'Currency_name' => 'ر',
                    'User_id' =>auth()->user()->id,
                    'status_debit' => 'غير مرحل',
                    'status' => 'غير مرحل',
                ]
            );
    return ; }
    
    public function edit($id)
{
    $sales = Sale::where('sale_id',$id)->first();
    return response()->json($sales);
}
public function destroy($id)
{


    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    if (!$accountingPeriod) {
        return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
    }
    $sal=Sale::where('sale_id',$id)->first();
    Sale::where('sale_id',$id)->delete();
    $saleInvoice = SaleInvoice::where('sales_invoice_id', $sal->Invoice_id)
    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    ->first();
if (!$saleInvoice) {
    return response()->json(['success' => false, 'message' => 'الفاتورة غير موجودة.'], 404);
}
$total_price_sale = Sale::where('Invoice_id', $saleInvoice->sales_invoice_id )
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_amount');
            $net_total_after_discount = Sale::where('Invoice_id', $saleInvoice->sales_invoice_id )
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_price');
            $discount = Sale::where('Invoice_id', $saleInvoice->sales_invoice_id )
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('discount');
            $paid_amount = 0;
            // تحديد الحساب المدين والمبلغ المدفوع بناءً على نوع الدفع
            if (in_array($saleInvoice->payment_type, [1, 3, 4])){
            $paid_amount = $net_total_after_discount;
            } elseif ($saleInvoice->payment_type === 2) {

                $paid_amount = $net_total_after_discount - $discount;
                $paid_amount = 0;
            }
            // تحديث بيانات الفاتورة
            $saleInvoice->update([
                'total_price_sale' => $total_price_sale,
                'net_total_after_discount' => $net_total_after_discount,

                'discount' => $discount,
                'paid_amount' => $paid_amount,
                'remaining_amount' => $net_total_after_discount - $paid_amount,
            ]);   
            $transactiontype=   TransactionType::fromValue($saleInvoice->transaction_type)?->label();

            // إعداد بيانات الإدخالات اليومية
            $Getentrie_id = DailyEntrie::where('Invoice_id',$saleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type',$transactiontype)
                ->first();

         // تحديث بيانات القيد اليومي
         $Getentrie_id->update([
            'Amount_Credit' => $net_total_after_discount ?: 0,
            'Amount_debit' => $net_total_after_discount ?: 0,
        ]);
         $total_price_sale = $total_price_sale;
         $discount = $discount;
         $net_total_after_discount =$total_price_sale-$discount;

         if( $total_price_sale)
         {
            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الصنف بنجاح.',
                'total_price_sale' => $total_price_sale,
                'discount' => $discount,
                'net_total_after_discount' => $net_total_after_discount,
            ]);
         }
         return response()->json([
            'status' => 'success',
            'message' => 'تم حذف الصنف بنجاح.',
               ]);
}
public function deleteInvoice($id)
{
    try {
        // البحث عن الفاتورة
        $invoice = SaleInvoice::where('sales_invoice_id', $id)->first();
        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على معرف الفاتورة.'
            ]);      
        }
        
        // $transactionType = $invoice->transaction_type;
        $transactiontype=   TransactionType::fromValue($invoice->transaction_type)?->label();
        // حذف جميع المشتريات المرتبطة إن وجدت
        if ($invoice->sales()->exists()) {
            $invoice->sales()->delete();
        }

        // البحث عن فترة محاسبية مفتوحة
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
        }
        $invoice->delete();
        // البحث عن السجل المرتبط في DailyEntrie
        $GetentrieId = DailyEntrie::where('Invoice_id', $id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type', $transactiontype)
            ->first();
        // التحقق مما إذا كان السجل موجودًا قبل الحذف
        if ($GetentrieId) {
            $GetentrieId->delete(); // حذف السجل المرتبط
        }

        // حذف الفاتورة نفسها
        
        return response()->json([
            'success' => true,
            'message' => 'تم حذف الفاتورة وجميع المشتريات والقيود المرتبطة بها بنجاح'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()
        ]);
    }
}
public function getSalesByInvoiceArrowLeft(Request $request)
{
    $invoiceId = $request->input('sales_invoice_id');
    $user_id = auth()->id();

    // جلب أول فاتورة أكبر من الفاتورة الحالية
    $SaleInvoice = SaleInvoice::where('User_id', $user_id)
        ->where('sales_invoice_id', '>', $invoiceId)
        ->orderBy('sales_invoice_id', 'asc') // ترتيب تصاعدي
        ->first();

    if (!$SaleInvoice) {
        return response()->json(['message' => 'لا توجد فاتورة لاحقة.'], 404);
    }
    // جلب المبيعات المرتبطة بالفاتورة المحددة
    $sales = Sale::where('User_id', $user_id)
        ->where('Invoice_id', $SaleInvoice->sales_invoice_id)
        ->get();

    if ($sales->isEmpty()) {
        return response()->json(['message' => 'لا توجد مبيعات مرتبطة بهذه الفاتورة.'], 404);
    }
    return response()->json([
        'sales' => $sales,
        'last_invoice_id' => $SaleInvoice->sales_invoice_id,
        'SaleInvoice' => $SaleInvoice,
    ]);
}


public function getSalesByInvoiceArrowRight(Request $request)
{
    $invoiceId = $request->input('sales_invoice_id');
    $user_id = auth()->id();
    // جلب أول فاتورة أصغر من الفاتورة الحالية
    $SaleInvoice = SaleInvoice::where('User_id', $user_id)
        ->where('sales_invoice_id', '<', $invoiceId)
        ->orderBy('sales_invoice_id', 'desc') // ترتيب تنازلي
        ->first();

    if (!$SaleInvoice) {
        return response()->json(['message' => 'لا توجد فاتورة سابقة.']);
    }
    // جلب المبيعات المرتبطة بالفاتورة المحددة
    $sales = Sale::where('User_id', $user_id)
        ->where('Invoice_id', $SaleInvoice->sales_invoice_id)
        ->get();

    if ($sales->isEmpty()) {
        return response()->json(['message' => 'لا توجد مبيعات مرتبطة بهذه الفاتورة.']);
    }
    return response()->json([
        'sales' => $sales,
        'last_invoice_id' => $SaleInvoice->sales_invoice_id,
    ]);
}



}
