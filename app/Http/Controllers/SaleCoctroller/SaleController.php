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
use App\Models\GeneralEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\Operation;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleInvoice;
use App\Models\SubAccount;
use App\Models\User;
use App\Models\UserPermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    //
    public function create()
    {
        $customers = SubAccount::where('account_class', 1)->get();
        $financialt = SubAccount::where('account_class', 5)->get();
        $DefaultCustomer  = Default_customer::where('id', 1)->first();
        $financial_account = Default_customer::where('id', 1)->pluck('financial_account_id')->first();
        $Currency_name = Currency::all();
        $MainAccounts = MainAccount::all();
        $user = Auth::user();

        // التحقق من إذن القراءةcanRead
        if (! $user->hasPermission('المبيعات')) {
            abort(403, 'غير مصرح لك بعرض الصفحة.');
        }


        return view('sales.create', [
            'customers' => $customers,
            'DefaultCustomer' => $DefaultCustomer,
            'Currency_name' => $Currency_name,
            'MainAccounts' => $MainAccounts,
            'financial_account' => $financial_account,
            'financialts' => $financialt,
        ]);
    }
    private function removeCommas($value)
    {
        return floatval(str_replace(',', '', $value)); // إزالة الفواصل وتحويل إلى float
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        if (! $user->hasPermission('المبيعات')) {
            abort(403, 'غير مصرح لك بعرض الصفحة.');
        }

        if (!$user->canWrite('المبيعات')) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد لديك صلاحية',
                403
            ]);

            // abort(403, 'غير مصرح لك بتعديل المنشورات.');
        }

        // إزالة الفواصل من الأرقام المدخلة
        $purchasePrice = $this->removeCommas($request->Purchase_price);
        $Selling_price = $this->removeCommas($request->Selling_price);
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
            'total_price' => 'required|string',
            'total_discount_rate' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            // استرجاع البيانات من الجداول الأخرى
            $Product = Product::where('product_id', $request->product_id)->first();
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

            if (!$accountingPeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد فترة محاسبية مفتوحة.'
                ]);
            }

            $saleInvoice = SaleInvoice::where('sales_invoice_id', $request->sales_invoice_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->first();

            if (!$saleInvoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'الفاتورة غير موجودة.'
                ]);
            }

            if ($accountingPeriod->accounting_period_id !== $saleInvoice->accounting_period_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'أصبحت هذه الفاتورة لسنة قديمة، لا يمكنك إضافة أي منتج إليها.'
                ]);
            }

            // حساب الأرباح والأسعار
            $purchasePrice = Category::where('product_id', $request->product_id)
                ->where('categorie_id', $request->Categorie_name)
                ->value('Purchase_price') ?? 0;

            $Profit1 = $Selling_price - $purchasePrice;
            $total_Profit = $request->Quantity * $Profit1;
            $Transaction_type =  $saleInvoice->transaction_type;
            $Purchase = Category::where('product_id', $request->product_id)
                ->where('categorie_id', $request->Categorie_name)
                ->first();
            $categorie = Category::where('product_id', $request->product_id)
                ->where('categorie_id', $request->Categorie_name)
                ->orwhere('Categorie_name', $request->Categorie_name)
                ->value('Categorie_name');
            // الحصول على اسم المنتج
            $Product = Product::where('product_id', $request->product_id)->first();
            // تحديث أو إدخال عملية البيع
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
                    'Purchase_price' =>  $Purchase->Purchase_price ?? 0,
                    'Profit' => $Profit1 ?? 0,
                    'total_Profit' => $total_Profit ?? 0,
                    'Quantityprice' => $request->Quantity,
                    'quantity' => $request->Quantityprice,
                    'discount_rate' => $discount_rate ?? 0,
                    'discount' => $total_discount_rate ?? 0,
                    'total_amount' => $request->Quantity * $Selling_price,
                    'total_purchasePrice' => $request->Quantity * $Purchase->Purchase_price,
                    'total_price' => $total_price,
                    'currency' => $saleInvoice->currency_id ?? null,
                    'transaction_type' => $Transaction_type,
                    'supplier_id' => $request->Supplier ?? null,
                    'Customer_id' => $saleInvoice->Customer_id,
                    'User_id' => auth()->id(),
                    'warehouse_to_id' => $request->account_debitid,
                    'financial_account_id' => $saleInvoice->account_id,
                    'shipping_cost' => $request->shipping_cost ?? 0,
                    'note' => $request->note ?? '',
                ]
            );

            DB::table('sales')
                ->where('sale_id', $sales->sale_id)
                ->update(['created_at' => $saleInvoice->created_at]);

            // تحديث إجمالي الفاتورة
            $qurye = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('Invoice_id', $saleInvoice->sales_invoice_id);

            $total_price_sale = $qurye->sum('total_amount');
            $discount = $saleInvoice->discount ?? 0;
            $net_total_after_discount = $total_price_sale - $discount;
            $ProfitTotal = $net_total_after_discount - $qurye->sum('Purchase_price');

            $Selling_price = $qurye->sum('total_amount');
            $Purchase_price2 = $qurye->sum('total_purchasePrice');
            // حساب الخصم والأرباح
            $Profit2 =  $Selling_price - $discount ?? 0;
            $ProfitTotal2 = $Profit2 - $Purchase_price2;
            // تحديث بيانات الفاتورة
            $saleInvoice->update([
                'total_price_sale' => $total_price_sale,
                'net_total_after_discount' => $net_total_after_discount,
                'discount' => $discount,
                'remaining_amount' => $net_total_after_discount - $saleInvoice->paid_amount,
            ]);
            if (in_array($saleInvoice->transaction_type, [4, 5])) {
                $this->SaleInvoiceupdate($saleInvoice, $accountingPeriod);
            }


            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة عملية البيع بنجاح!',
                'purchase' => $sales,
                'total_price_sale' => number_format($total_price_sale, 2),
                'net_total_after_discount' => number_format($net_total_after_discount, 2),
                'discount' => $discount,
                'Profit' => number_format($ProfitTotal2, 2),
            ]);
        } catch (\Exception $e) {
            Log::error("خطأ أثناء الحفظ: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء الحفظ.']);
        }
    }


    private function createOrUpdateDailyEntry($saleInvoice, $accountingPeriod, $account_Credit, $account_debit, $Getentrie_id, $amountDebit, $amountCredit)
    {
        $accountingPeriod = AccountingPeriod::where('accounting_period_id', $accountingPeriod->accounting_period_id)->first();

        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::where('accounting_period_id', $accountingPeriod->accounting_period_id)->whereDate('created_at', $today)->latest()->first();

        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
            ]);
        }
        // التحقق من وجود الصفحة اليومية
        if (!$dailyPage || !$dailyPage->page_id) {
            return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
        }
        if ($saleInvoice->transaction_type === 4) {
            if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
                $commint = "لكم";
            } elseif ($saleInvoice->payment_type === 2) {
                $commint = "عليكم فاتورة";
            }
        }
        if ($saleInvoice->transaction_type === 5) {
            if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
                $commint = " فاتورة";
            }
            if ($saleInvoice->payment_type === 2) {
                $commint = "لكم فاتورة";
            }
        }
        if ($saleInvoice->note) {
            $note = "/" . $saleInvoice->note ?? '';
        } else {
            $note = '';
        }
        $Getentrieid = $Getentrie_id->entrie_id ?? null;
        $daily_page_id = $Getentrie_id->daily_page_id ?? $dailyPage->page_id;
        $transactiontype =   TransactionType::fromValue($saleInvoice->transaction_type)?->label(); // إنشاء أو تحديث الإدخالات اليومية
        $curre = Currency::where('currency_id', $saleInvoice->currency_id)->first();

        $dailyEntrie = DailyEntrie::updateOrCreate(
            [
                'entrie_id' => $Getentrieid,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
                'invoice_id' => $saleInvoice->sales_invoice_id,
            ],
            [
                'daily_entries_type' => $transactiontype,
                'account_credit_id' => $account_Credit,
                'created_at' => $saleInvoice->created_at,
                'account_debit_id' => $account_debit,
                'amount_credit' => $amountCredit ?: 0,
                'amount_debit' => $amountDebit ?: 0,
                'statement' => $commint . " " . $transactiontype . " " . PaymentType::tryFrom($saleInvoice->payment_type)?->label() . $note,
                'daily_page_id' => $daily_page_id ?? $dailyPage->page_id,
                'invoice_type' => $saleInvoice->payment_type,
                'currency_name' =>  $curre->currency_name,
                'user_id' => auth()->user()->id,
                'status_debit' => 'غير مرحل',
                'status' => 'غير مرحل',
            ]
        );
        $date = Carbon::parse($saleInvoice->created_at)->format('Y-m-d');
        $dailyEntrie->created_at = $date; // تأكد من أن هذا هو العمود الصحيح
        $dailyEntrie->save();
        return;
    }

    public function edit($id)
    {
        $user = Auth::user();

        // التحقق من إذن الكتابة
        if (! $user->canModify('المبيعات')) {
            abort(403, 'غير مصرح لك بتعديل المنشورات.');
        }
        $sales = Sale::where('sale_id', $id)->first();
        return response()->json($sales);
    }


    public function SaleInvoiceupdate($saleInvoice, $accountingPeriod)
    {
        // استرجاع البيانات المطلوبة
        $qurye = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('Invoice_id', $saleInvoice->sales_invoice_id);
        $Selling_price = $qurye->sum('total_amount');
        $total_price_sale = $qurye->sum('total_price');
        $discount = $qurye->sum('discount');
        $Purchase_price = $qurye->sum('total_purchasePrice');
        // حساب الخصم والأرباح

        $Profit =  $Selling_price - $saleInvoice->discount ?? $discount ?? 0;
        $ProfitTotal = $Profit - $Purchase_price;
        $net_total_after_discount = $total_price_sale;

        $saleInvoice->update([
            'total_price_sale' => $Selling_price,
            'net_total_after_discount' => $Profit,
            'discount' => $discount,
            'remaining_amount' => $net_total_after_discount - $saleInvoice->paid_amount,
        ]);

        // التحقق من وجود الحساب الافتراضي
        $DefaultCustomer = Default_customer::find(1);
        if (!$DefaultCustomer) {
            return response()->json(['success' => false, 'message' => 'الحساب الافتراضي غير موجود']);
        }

        $warehouse_id = SubAccount::where('sub_account_id', $DefaultCustomer->warehouse_id)->value('sub_account_id');
        // تحديد الحسابات بناءً على نوع المعاملة
        $paid_amount = 0;
        if ($saleInvoice->transaction_type == 4) {
            $amountDeditTotal = ($saleInvoice->net_total_after_discount == 0) ? 0 : $saleInvoice->net_total_after_discount;
            $amountCreditTotal = ($Purchase_price == 0) ? 0 : $Purchase_price;
            if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
                $account_Credit = $warehouse_id;
                $account_debit = $saleInvoice->account_id;
                $paid_amount = $net_total_after_discount;
            } elseif ($saleInvoice->payment_type == 2) {
                $account_Credit = $warehouse_id;
                $account_debit = $saleInvoice->Customer_id;
            }

            $amountCredit = $ProfitTotal > 0 ? $ProfitTotal : $ProfitTotal = 0 ? 0 : 0;
            $amountDebit = $ProfitTotal < 0 ? abs($ProfitTotal) : $ProfitTotal = 0 ? 0 : 0;
        } elseif ($saleInvoice->transaction_type == 5) {

            $amountCreditTotal = $saleInvoice->net_total_after_discount = 0 ? 0 :  $saleInvoice->net_total_after_discount;
            $amountDeditTotal = $Purchase_price = 0 ? 0 : $Purchase_price;

            if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
                $account_Credit = $saleInvoice->account_id;
                $account_debit = $warehouse_id;
                $paid_amount = $net_total_after_discount;
            } elseif ($saleInvoice->payment_type === 2) {
                $account_Credit = $saleInvoice->Customer_id;
                $account_debit = $warehouse_id;
            }
            $amountDebit = $ProfitTotal > 0 ? $ProfitTotal : $ProfitTotal = 0 ? 0 : 0;
            $amountCredit = $ProfitTotal < 0 ? abs($ProfitTotal) : $ProfitTotal = 0 ? 0 : 0;
        }
        $saleInvoice->update(['paid_amount' => $paid_amount]); // تحديث بيانات الفاتورة

        // $transactiontype = TransactionType::fromValue($saleInvoice->transaction_type)?->label();
        $accountCredit = SubAccount::where('sub_account_id', 2)->first();
        $entrie_id = DailyEntrie::where('invoice_id', $saleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->whereIn('daily_entries_type', ["مردود مبيعات", "مبيعات"])
            ->where('account_credit_id', '!=', $accountCredit->sub_account_id)
            ->first();
        $entrieid = DailyEntrie::where('invoice_id', $saleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->whereIn('daily_entries_type', ["مردود مبيعات", "مبيعات"])
            ->where('account_credit_id', $accountCredit->sub_account_id)
            ->first();
        if (in_array($saleInvoice->transaction_type, [4, 5])) {
            $this->createOrUpdateDailyEntry($saleInvoice, $accountingPeriod, $account_Credit, $account_debit, $entrie_id, $amountDeditTotal ?? 0, $amountCreditTotal ?? 0);
            // if($amountDebit!=0 ||$amountCredit!=0)
            // {

            $this->createOrUpdateDailyEntry($saleInvoice, $accountingPeriod, $accountCredit->sub_account_id, $accountCredit->sub_account_id, $entrieid, $amountDebit ?? 0, $amountCredit ?? 0);
            // }

        }
        // إنشاء أو تحديث الإدخالات اليومية

    }

    public function destroy($id)
    {
        $user = Auth::user();

        // التحقق من إذن بحذف

        if (!$user->canDelete('المبيعات')) {
            return response()->json(['success' => false, 'message' =>  'غير مصرح لك بحذف المنتج.'], 404);
        }
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
        }
        $sal = Sale::where('sale_id', $id)->first();

        $saleInvoice = SaleInvoice::where('sales_invoice_id', $sal->Invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->first();
        Sale::where('sale_id', $id)->delete();
        if (!$saleInvoice) {
            return response()->json(['success' => false, 'message' => 'الفاتورة غير موجودة.'], 404);
        }

        $qurye = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('Invoice_id', $saleInvoice->sales_invoice_id);
        $this->SaleInvoiceupdate($saleInvoice, $accountingPeriod);
        $Purchase_price = $qurye->sum('Purchase_price');
        $total_price_sale = $qurye->sum('total_amount');
        $net_total_after_discount = $total_price_sale - $saleInvoice->discount;
        $Selling_price = $qurye->sum('total_amount');
        $total_price_sale = $qurye->sum('total_price');
        $discount = $qurye->sum('discount');
        $Purchase_price = $qurye->sum('total_purchasePrice');
        // حساب الخصم والأرباح
        $Profit =  $Selling_price - $saleInvoice->discount ?? $discount ?? 0;
        $ProfitTotal = $Profit - $Purchase_price;
        $net_total_after_discount = $total_price_sale;
        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف الصنف بنجاح.',
            'total_price_sale' => $total_price_sale ?? 0,
            'discount' => $saleInvoice->discount ?? 0,
            'net_total_after_discount' => $net_total_after_discount ?? 0,
            'Profit' => $ProfitTotal ?? 0,
        ]);
    }
    public function deleteInvoice($id)
    {
        $user = Auth::user();

        // التحقق من إذن بحذف

        if (!$user->canDelete('الفواتير المبيعات')) {
            return response()->json(['success' => false, 'message' =>  'غير مصرح لك بحذف الفاتورة.'], 404);
        }
        try {
            // البحث عن الفاتورة
            $invoice = SaleInvoice::where('sales_invoice_id', $id)->first();
            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على معرف الفاتورة.'
                ]);
            }
            $transactiontype =   TransactionType::fromValue($invoice->transaction_type)?->label();
            // حذف جميع المشتريات المرتبطة إن وجدت
            if ($invoice->sales()->exists()) {
                $invoice->sales()->delete();
            }

            // البحث عن فترة محاسبية مفتوحة
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            if (!$accountingPeriod) {
                return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
            }
            Operation::createOpertion($invoice->sales_invoice_id, 'حذف', 'فاتورة مبيعات');
            $invoice->delete();
            // البحث عن السجل المرتبط في DailyEntrie
            $GetentrieIds = DailyEntrie::where('invoice_id', $id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('daily_entries_type', $transactiontype)
                ->get();
            foreach ($GetentrieIds as $GetentrieId) {


                $generalEntrieaccount_debit_id = GeneralEntrie::where([
                    'daily_entry_id' => $GetentrieId->entrie_id,
                    'accounting_period_id' => $GetentrieId->accounting_period_id,
                    'sub_id' => $GetentrieId->account_debit_id,
                ])->delete();
                $generalEntrieaccount_debit_id = GeneralEntrie::where([
                    'daily_entry_id' => $GetentrieId->entrie_id,
                    'accounting_period_id' => $GetentrieId->accounting_period_id,
                    'sub_id' => $GetentrieId->account_credit_id,
                ])->delete();
                // التحقق مما إذا كان السجل موجودًا قبل الحذف
                if ($GetentrieId) {
                    $GetentrieId->delete(); // حذف السجل المرتبط
                }
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
    public function Saleupdate()
    {
        // DB::beginTransaction();


        try {

            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

            $saleInvoices = SaleInvoice::where('accounting_period_id', $accountingPeriod->accounting_period_id)->get();
            foreach ($saleInvoices as $saleInvoice) {
                if ($saleInvoice->sales()->exists()) {
                    $qurye = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)
                        ->where('Invoice_id', $saleInvoice->sales_invoice_id);
                    $Selling_price = $qurye->sum('total_amount');
                    // $total_price_sale = $qurye->sum('total_price');
                    $discount = $qurye->sum('discount');
                    $quryes = $qurye->get();
                    $discountInvoices = $saleInvoice->discount;
                    $turr = $discountInvoices / $Selling_price;
                    // dd( $turr);

                    if ($discount != 0 || $discountInvoices != 0) {
                        if ($discount != $discountInvoices) {

                            foreach ($quryes as $qury) {
                                if ($qury) {

                                    $totalAmount = $qury->total_amount ?? 0;
                                    $turrValue = $turr ?? 0;

                                    $qury->update([
                                        'discount' => $totalAmount * $turrValue,
                                        'discount_rate' => $turrValue,
                                    ]);
                                    DB::table('sales')
                                        ->where('sale_id', $qury->sale_id)
                                        ->update(['created_at' => $saleInvoice->created_at]);
                                }
                            }
                        }




                        // $this->Invoiceupdate($saleInvoice, $accountingPer);

                    }
                }
            }
            return redirect()->back()->with('success',  'تم  تحديث   القيود  المرتبطة بافواتير المبعات وامردود المبيعات بها بنجاح');




            // DB::commit();

        } catch (\Exception $e) {
            // DB::rollBack();
            return redirect()->back()->with('error',  'فشل  تحديث   القيود  المرتبطة بافواتير المبعات وامردود المبيعات  ');
        }
    }

    public function Invoiceupdate($saleInvoice, $accountingPeriod)
    {


        // استرجاع البيانات المطلوبة
        $qurye = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('Invoice_id', $saleInvoice->sales_invoice_id);

        $Selling_price = $qurye->sum('total_amount');
        $total_price_sale = $qurye->sum('total_price');
        $discount = $qurye->sum('discount');
        $Purchase_price = $qurye->sum('total_purchasePrice');
        // حساب الخصم والأرباح
        $Profit =  $Selling_price - $saleInvoice->discount ?? $discount ?? 0;
        $ProfitTotal = $Profit - $Purchase_price;
        $net_total_after_discount = $total_price_sale;


        // التحقق من وجود الحساب الافتراضي
        $DefaultCustomer = Default_customer::find(1);
        if (!$DefaultCustomer) {
            return response()->json(['success' => false, 'message' => 'الحساب الافتراضي غير موجود']);
        }

        $warehouse_id = SubAccount::where('sub_account_id', $DefaultCustomer->warehouse_id)->value('sub_account_id');
        // تحديد الحسابات بناءً على نوع المعاملة
        $paid_amount = 0;
        if ($saleInvoice->transaction_type == 4) {
            $amountDeditTotal = ($saleInvoice->net_total_after_discount == 0) ? 0 : $saleInvoice->net_total_after_discount;
            $amountCreditTotal = ($Purchase_price == 0) ? 0 : $Purchase_price;
            if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
                $account_Credit = $warehouse_id;
                $account_debit = $saleInvoice->account_id;
                $paid_amount = $net_total_after_discount;
            } elseif ($saleInvoice->payment_type == 2) {
                $account_Credit = $warehouse_id;
                $account_debit = $saleInvoice->Customer_id;
            }

            $amountCredit = $ProfitTotal > 0 ? $ProfitTotal : $ProfitTotal = 0 ? 0 : 0;
            $amountDebit = $ProfitTotal < 0 ? abs($ProfitTotal) : $ProfitTotal = 0 ? 0 : 0;
        } elseif ($saleInvoice->transaction_type == 5) {
            $amountCreditTotal = $saleInvoice->net_total_after_discount = 0 ? 0 :  $saleInvoice->net_total_after_discount;
            $amountDeditTotal = $Purchase_price = 0 ? 0 : $Purchase_price;

            if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
                $account_Credit = $saleInvoice->account_id;
                $account_debit = $warehouse_id;
                $paid_amount = $net_total_after_discount;
            } elseif ($saleInvoice->payment_type === 2) {
                $account_Credit = $saleInvoice->Customer_id;
                $account_debit = $warehouse_id;
            }
            $amountDebit = $ProfitTotal > 0 ? $ProfitTotal : $ProfitTotal = 0 ? 0 : 0;
            $amountCredit = $ProfitTotal < 0 ? abs($ProfitTotal) : $ProfitTotal = 0 ? 0 : 0;
        }

        $transactiontype = TransactionType::fromValue($saleInvoice->transaction_type)?->label();
        $accountCredit = SubAccount::where('sub_account_id', 2)->first();
        if ($saleInvoice->sales()->exists()) {

            $entrie_id = DailyEntrie::where('invoice_id', $saleInvoice->sales_invoice_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('daily_entries_type', $transactiontype)
                ->where('account_credit_id', '!=', $accountCredit->sub_account_id)
                ->first();
            $entrieid = DailyEntrie::where('invoice_id', $saleInvoice->sales_invoice_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('daily_entries_type', $transactiontype)
                ->where('account_credit_id', $accountCredit->sub_account_id)
                ->first();
            // إنشاء أو تحديث الإدخالات اليومية
            $this->createOrUpdateDailyEntry($saleInvoice, $accountingPeriod, $account_Credit, $account_debit, $entrie_id, $amountDeditTotal ?? 0, $amountCreditTotal ?? 0);
            $this->createOrUpdateDailyEntry($saleInvoice, $accountingPeriod, $accountCredit->sub_account_id, $accountCredit->sub_account_id, $entrieid, $amountDebit ?? 0, $amountCredit ?? 0);
        }
    }
    public function getSalesByInvoiceArrowLeft(Request $request)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        $invoiceId = $request->input('sales_invoice_id');
        $user_id = auth()->id();
        // جلب أول فاتورة أكبر من الفاتورة الحالية
        $SaleInvoice = SaleInvoice::where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('sales_invoice_id', '>', $invoiceId)
            ->orderBy('sales_invoice_id', 'asc') // ترتيب تنازلي

            ->first();


        $total_price_sale = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_amount');
        $net_total_after_discount = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_price');
        $discount = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('discount');
        $Profits = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_Profit');
        $total_Profit = $Profits - $discount;
        $SubAccount = SubAccount::where('sub_account_id', $SaleInvoice->Customer_id)->first();

        $customers = SubAccount::where('account_class', 1)->where('sub_account_id', '!=', $SaleInvoice->Customer_id)
            ->get();

        $Customer_name = $SubAccount->sub_name;
        $Customer_id = $SubAccount->sub_account_id;
        if (!$SaleInvoice) {
            return response()->json(['message' => 'لا توجد فاتورة سابقة.']);
        }
        // جلب المبيعات المرتبطة بالفاتورة المحددة
        $sales = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->get();
        $TransactionTypes = [];
        $TransactionTyS = TransactionType::cases();

        $label = TransactionType::fromValue($SaleInvoice->transaction_type)?->label() ?? 'غير معروف';
        $valueType = TransactionType::fromValue($SaleInvoice->transaction_type)?->value;
        foreach ($TransactionTyS as $TransactionType) {
            if (in_array($TransactionType->value, [4, 5, 6]) && $TransactionType->value != $valueType) {


                $TransactionTypes[] = [
                    'value' => $TransactionType->value,
                    'label' => $TransactionType->label(),

                ];
            }
        }
        //    dd($TransactionTypes);
        return response()->json([
            'sales' => $sales,
            'customers' => $customers,
            'Customer_name' => $Customer_name,
            'Customer_id' => $Customer_id,
            'transaction_typelabel' => $label,
            'transaction_valueType' => $valueType,
            'last_invoice_id' => $SaleInvoice->sales_invoice_id,
            'note' => $SaleInvoice->note ?? '',
            'payment_type' => $SaleInvoice->payment_type,

            'created_at' => $SaleInvoice->created_at->format('Y-m-d'),

            'total_price_sale' => number_format($total_price_sale, 2),
            'net_total_after_discount' => number_format($net_total_after_discount, 2),
            'discount' => $SaleInvoice->discount,
            'Profit' => number_format($total_Profit, 2) ?? 0,
            'TransactionTypes' => $TransactionTypes,
        ]);
    }


    public function getSalesByInvoiceArrowRight(Request $request)
    {
        $invoiceId = $request->input('sales_invoice_id');
        $user_id = auth()->id();
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        // جلب أول فاتورة أصغر من الفاتورة الحالية
        $SaleInvoice = SaleInvoice::where('User_id', $user_id)
            ->where('sales_invoice_id', '<', $invoiceId)
            ->orderBy('sales_invoice_id', 'desc') // ترتيب تنازلي
            ->first();
        $total_price_sale = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_amount');
        $net_total_after_discount = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_price');
        $discount = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('discount');
        $Profits = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->sum('total_Profit');
        $total_Profit = $Profits - $discount;
        $SubAccount = SubAccount::where('sub_account_id', $SaleInvoice->Customer_id)->first();
        $customers = SubAccount::where('account_class', 1)->where('sub_account_id', '!=', $SaleInvoice->Customer_id)
            ->get();
        $Customer_name = $SubAccount->sub_name;
        $Customer_id = $SubAccount->sub_account_id;
        if (!$SaleInvoice) {
            return response()->json(['message' => 'لا توجد فاتورة سابقة.']);
        }
        // جلب المبيعات المرتبطة بالفاتورة المحددة
        $sales = Sale::where('Invoice_id', $SaleInvoice->sales_invoice_id)
            ->get();

        // if ($sales->isEmpty()) {
        //     return response()->json(['message' => 'لا توجد مبيعات مرتبطة بهذه الفاتورة.']);
        // }
        $TransactionTypes = [];
        $TransactionTyS = TransactionType::cases();

        $label = TransactionType::fromValue($SaleInvoice->transaction_type)?->label() ?? 'غير معروف';
        $valueType = TransactionType::fromValue($SaleInvoice->transaction_type)?->value;
        foreach ($TransactionTyS as $TransactionType) {
            if (in_array($TransactionType->value, [4, 5, 6]) && $TransactionType->value != $valueType) {
                // التحقق من أن الكائن ليس null
                if ($TransactionType->label() && $TransactionType->value) {
                    $TransactionTypes[] = [
                        'value' => $TransactionType->value,
                        'label' => $TransactionType->label(),
                    ];
                }
            }
        }
        // dd($TransactionTypes);
        return response()->json([
            'sales' => $sales,
            'customers' => $customers,

            'Customer_name' => $Customer_name,
            'Customer_id' => $Customer_id,
            'transaction_typelabel' => $label,
            'transaction_valueType' => $valueType,
            'last_invoice_id' => $SaleInvoice->sales_invoice_id,
            'note' => $SaleInvoice->note ?? '',
            'payment_type' => $SaleInvoice->payment_type,
            'created_at' => $SaleInvoice->created_at->format('Y-m-d'),
            'total_price_sale' => number_format($total_price_sale, 2),
            'net_total_after_discount' => number_format($net_total_after_discount, 2),
            'discount' => $SaleInvoice->discount,
            'Profit' => number_format($total_Profit, 2) ?? 0,
            'TransactionTypes' => $TransactionTypes,

        ]);
    }
}
