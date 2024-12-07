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
use Carbon\Carbon;
use Illuminate\Http\Request;


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
        return view('sales.create',['customers'=>$customers,
        'DefaultCustomer'=>$DefaultCustomer
        ,'Currency_name'=>$Currency_name,
        'MainAccounts'=>$MainAccounts,
        'financial_account'=>$financial_account,
        'financialts'=>$financialt,
    ]);
    }
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'product_id' => 'required|integer|exists:products,product_id',
            'Customer_id' => 'required|integer|exists:sub_accounts,sub_account_id',
            'sales_invoice_id' => 'required|integer|exists:sales_invoices,sales_invoice_id',
            'Quantity' => 'required|numeric|min:0',
            'Selling_price' => 'required|numeric|min:0',
            'Category_name' => 'nullable|string|max:255',
            'account_debitid' => 'required|integer|exists:sub_accounts,sub_account_id',
            'financial_account_id' => 'required|integer|exists:sub_accounts,sub_account_id',
            'Barcode' => 'nullable|numeric',
            'total_price' => 'required|numeric|min:0',
            'total_discount_rate' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);
        
        try {
          
            $Categorie_name=Category::where('categorie_id',$request->Categorie_name)->value('Categorie_name');
            // الحصول على اسم المنتج
            $Product = Product::where('product_id', $request->product_id)->first();

            // التحقق من وجود الفترة المحاسبية
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            if (!$accountingPeriod) {
                return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
            }
            // التحقق من وجود الفاتورة
            $saleInvoice = SaleInvoice::where('sales_invoice_id', $request->sales_invoice_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->first();
            if (!$saleInvoice) {
                return response()->json(['success' => false, 'message' => 'الفاتورة غير موجودة.'], 404);
            }
    
            // حفظ أو تحديث عملية البيع
            $sales = Sale::updateOrCreate(
                [
                    'sale_id' => $request->sale_id,
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'Invoice_id' => $saleInvoice->sales_invoice_id,

                ],
                [
                    'Product_name' => $Product->product_name,
                    'product_id' => $Product->product_id,
                    'Barcode' => $Product->Barcode ?? '',
                    'Quantityprice' => $request->Quantity,
                    'quantity' => $request->Quantityprice,
                    'Selling_price' => $request->Selling_price,
                    'discount_rate' => $request->discount_rate ?? 0,
                    'discount' => $request->total_discount_rate ?? 0,
                    'total_amount' => $request->Quantity * $request->Selling_price,
                    'total_price' => $request->total_price,
                    'currency' => $saleInvoice->currency,
                    'transaction_type' => $saleInvoice->transaction_type,
                    'supplier_id' => $Product->supplier_id,
                    'Customer_id' => $saleInvoice->Customer_id ?? null,
                    'User_id' => auth()->id(),
                    'warehouse_to_id' => $request->account_debitid,
                    'financial_account_id' => $request->financial_account_id,
                    'shipping_cost' => $request->shipping_cost ?? 0,
                    'note' => $request->note ?? '',
                    'Category_name' => $Categorie_name,
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
            $paid_amount = 0;
            $account_debit = null;
            $account_Credit = null;
            
            // تحديد الحساب المدين والمبلغ المدفوع بناءً على نوع الدفع
            if ($saleInvoice->transaction_type ===4) {

           
        }          

        if (in_array($saleInvoice->payment_type, [1, 3, 4])){
        $payment_type="نقدا";
                $account_Credit= $request->account_debitid;
                $account_debit = $request->financial_account_id;
                $paid_amount = $net_total_after_discount;

            } elseif ($saleInvoice->payment_type === 2) {
                $payment_type="اجل";

                $account_Credit= $request->account_debitid;
                $account_debit= $saleInvoice->Customer_id;
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
            $today = Carbon::now()->toDateString();
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();
            if (!$dailyPage) {
                $dailyPage = GeneralJournal::create([]);
            }
            $transactiontype=   TransactionType::fromValue($saleInvoice->transaction_type)?->label();
  // الاستجابة بنجاح
//   return response()->json([
//     'success' => true,
//     'message' => 'تمت إضافة عملية البيع بنجاح!'.$transactiontype,
   
// ]);
            // إعداد بيانات الإدخالات اليومية
            $entrie_id = DailyEntrie::where('Invoice_id',$saleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type',$transactiontype)
            ->first();
             $Getentrie_id= $entrie_id->entrie_id ?? null;
                $daily_page_id = $entrie_id->Daily_page_id ?? $dailyPage->page_id;
            
                if ($saleInvoice->transaction_type ==4) {

                    $this->createOrUpdateDailyEntry($saleInvoice, $accountingPeriod,$account_Credit, $account_debit, $net_total_after_discount,$Getentrie_id,$payment_type,$daily_page_id);

                }          
        

            // الاستجابة بنجاح
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة عملية البيع بنجاح!',
                'purchase' => $sales,
                'total_price_sale' => $total_price_sale,
                'net_total_after_discount' => $net_total_after_discount,
                'discount' => $discount,
            ]);
        } catch (\Exception $e) {
            // تسجيل الخطأ في السجل
    
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحفظ. حاول مجددًا.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    private function createOrUpdateDailyEntry($saleInvoice, $accountingPeriod,$account_Credit, $account_debit, $net_total_after_discount,$Getentrie_id,$payment_type,$daily_page_id)
    {
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first() ?? GeneralJournal::create([]);
            // التحقق من وجود الصفحة اليومية
            if (!$dailyPage || !$dailyPage->page_id) {
                return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
            }


            $commint="لكم";
                if (in_array($saleInvoice->payment_type, [1, 3, 4]))
                 {


            } elseif ($saleInvoice->payment_type ===2) {
                $commint="عليكم فاتورة";

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
                    'account_debit_id' => $account_debit,
                    'Amount_Credit' => $net_total_after_discount ?: 0,
                    'Amount_debit' => $net_total_after_discount ?: 0,
                    'account_Credit_id' => $account_Credit,
                    'Statement' => $commint." ".$transactiontype." ".PaymentType::tryFrom($saleInvoice->payment_type)?->label() ,
                    'Daily_page_id' => $daily_page_id,
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
return response()->json(['message' => 'تم حذف البيانات بنجاح']);
}
public function deleteInvoice($id)
{
    try {
        $invoice = SaleInvoice::where('sales_invoice_id', $id)->first();
        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على معرف الفاتورة.'
            ]);      
          }
          $transactiontype=   TransactionType::fromValue($invoice->transaction_type)?->label();

        // حذف جميع المشتريات المرتبطة إن وجدت
        if ($invoice->sales()->exists()) {
            $invoice->sales()->delete();
        }
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
        }
        // حذف الفاتورة نفسها
        $invoice->delete();
        $Getentrie_id = DailyEntrie::where('Invoice_id',$invoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type',$transactiontype)
            ->first();
            $Getentrie_id->delete();

        // DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'تم حذف الفاتورة وجميع المشتريات المرتبطة بها بنجاح'
        ]);

    } catch (\Exception $e) {
        // DB::rollBack();

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
        return response()->json(['message' => 'لا توجد فاتورة سابقة.'], 404);
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
    ]);
}



}
