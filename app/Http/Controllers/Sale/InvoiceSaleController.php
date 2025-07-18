<?php

namespace App\Http\Controllers\Sale;

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
use App\Models\Sale;
use App\Models\SaleInvoice;
use App\Models\SubAccount;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use NumberToWords\NumberToWords;

class InvoiceSaleController extends Controller
{
    //
    public function store(Request $request)
    {
        $user = auth()->id();
        $AuthorityName = "الفواتير المبيعات";
        $us = UserPermission::where('User_id', $user)
            ->where('Authority_Name', $AuthorityName)
            ->first();
        if (optional($us)->Writing_ability == 1) {

            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            if (!$accountingPeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد فترة محاسبية مفتوحة'
                ], 400);
            }
            // التحقق من صحة البيانات المدخلة
            $validatedData = $request->validate([
                'date' => 'nullable|date', // تأكد من إضافة هذا الحقل
                'listRadio' => 'required|in:1,2', // تحديث القيم هنا
                'Customer_name_id' => 'nullable|exists:sub_accounts,sub_account_id',
                'User_id' => 'required|exists:users,id',
                'payment_type' => 'required|numeric',
                'invoice_id' => 'nullable',
                'note' => 'nullable',
                'financial_account_id' => 'required|numeric',
                'currency_id' => 'required|exists:currencies,currency_id', // assuming there's a currencies table
                'shipping_bearer' => 'required|in:customer,merchant',
                'transaction_type' => 'required|numeric',
            ]);




            // عملية الحفظ
            try {
                $salesInvoice = new SaleInvoice();

                if ($validatedData['invoice_id']) {
                    $invoice_id = SaleInvoice::where('sales_invoice_id', $validatedData['invoice_id'])->first();

                    if ($invoice_id) {
                        return response()->json([
                            'success' => false,
                            'message' => 'الفاتورة موجودة من قبل',

                        ], 201);
                    } else {
                        $salesInvoice->sales_invoice_id = $validatedData['invoice_id'];
                    }
                }


                if ($validatedData['listRadio'] == 2) {
                    $salesInvoice->created_at =  $validatedData['date'];
                }
                $salesInvoice->Customer_id = $validatedData['Customer_name_id'];
                // $salesInvoice->payment_status = $validatedData['payment_status'];
                $salesInvoice->total_price = 0;
                $salesInvoice->total_price_sale = 0;
                $salesInvoice->User_id = $validatedData['User_id'];
                $salesInvoice->paid_amount = 0;
                $salesInvoice->discount =  0;
                $salesInvoice->shipping_amount =  0;
                $salesInvoice->remaining_amount = 0;
                $salesInvoice->payment_type = $validatedData['payment_type'];
                $salesInvoice->note = $validatedData['note'] ?? '';
                $salesInvoice->account_id = $validatedData['financial_account_id'];
                $salesInvoice->currency_id = $validatedData['currency_id'];
                $salesInvoice->exchange_rate = 0;
                $salesInvoice->transaction_type = $validatedData['transaction_type'];
                $salesInvoice->shipping_bearer = $validatedData['shipping_bearer'];
                $salesInvoice->accounting_period_id = $accountingPeriod->accounting_period_id;
                $salesInvoice->save();

                return response()->json([
                    'success' => true,
                    'message' => 'تم الحفظ بنجاح',
                    'invoice_number' => $salesInvoice->sales_invoice_id,
                    'customer_number' => $salesInvoice->Customer_id,

                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to save the invoice.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد لديك صلاحية لإضافة فاتورة',
            ], 500);
            return view('auth.login');
        }
    }
    public function update(Request $request)
    {
        // dd( $request->sales_invoice_id);
        $user = auth()->id();
        $AuthorityName = "الفواتير المبيعات";
        $transactionType = intval($request->transaction_type); // أو (int)$request->transaction_type
        // التحقق من وجود الفترة المحاسبية
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
        }
        // التحقق من صحة البيانات المدخلة
        $validatedData = $request->validate([
            'Customer_name_id' => 'nullable|exists:sub_accounts,sub_account_id',
            'discount' => 'nullable|numeric|min:0',
            'date' => 'nullable|date', // تأكد من إضافة هذا الحقل
            'listRadio' => 'required|in:1,2',
            'sales_invoice_id' => 'nullable|numeric|min:0',
            'payment_type' => 'required|numeric',
            'financial_account_id' => 'required|numeric',
            'currency_id' => 'required|exists:currencies,currency_id',
            'exchange_rate' => 'nullable|numeric|min:0',
            'invoice_id' => 'nullable|numeric',
            'shipping_bearer' => 'required|in:customer,merchant',
            'transaction_type' => 'required|numeric',
            'note' => 'nullable',
            'shipping_amount' => 'nullable|numeric|min:0',
        ]);

        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();

        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
            ]);
        }

        if (!$dailyPage || !$dailyPage->page_id) {
            return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
        }

        $transactiontype = TransactionType::fromValue($validatedData['transaction_type'])?->label();

        $saleInvoice = SaleInvoice::where('sales_invoice_id', $request->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->first();

        if (!$saleInvoice) {
            return response()->json(['success' => false, 'message' => 'الفاتورة غير موجودة.'], 404);
        }

        if ($accountingPeriod->accounting_period_id !== $saleInvoice->accounting_period_id) {
            return response()->json(['success' => false, 'message' => 'فترة محاسبية مغلقة.']);
        }

        $net_total_after_discount = $this->calculateNetTotalAfterDiscount($saleInvoice, $validatedData) ?? 0;
        $discount = $validatedData['discount'] ?? $this->calculateDiscount($saleInvoice);
        $paid_amount = 0;
        $account_debit = null;
        $account_Credit = null;
        $updateSale = Sale::where('Invoice_id', $request->sales_invoice_id)->get();
        $DefaultCustomer = Default_customer::where('id', 1)->first();
        $warehouse_id = SubAccount::where('sub_account_id', $DefaultCustomer->warehouse_id)->value('sub_account_id'); // استخدام value بدلاً من pluck

        $saleInvoice->update([
            'Customer_id' => $validatedData['Customer_name_id'],
            'paid_amount' => $paid_amount ?? 0,
            'discount' => $validatedData['discount'] ?? 0,
            'shipping_amount' => $validatedData['shipping_amount'] ?? 0,
            'remaining_amount' => $saleInvoice->net_total_after_discount - ($paid_amount ?? 0),
            'financial_account_id' => $validatedData['financial_account_id'],
            'payment_type' => $validatedData['payment_type'],
            'note' => $validatedData['note'],
            'account_id' => $validatedData['financial_account_id'],
            'currency_id' => $validatedData['currency_id'],
            'exchange_rate' => $validatedData['exchange_rate'] ?? 0,
            'transaction_type' => $validatedData['transaction_type'],
            'shipping_bearer' => $validatedData['shipping_bearer'] ?? 0,
        ]);

        if ($request->transaction_type == 4 || $request->transaction_type == 6) {
            $this->handleTransactionType4($saleInvoice, $validatedData, $DefaultCustomer, $net_total_after_discount, $transactiontype, $updateSale);
        } elseif ($request->transaction_type == 5) {
            $this->handleTransactionType5($saleInvoice, $validatedData, $DefaultCustomer, $warehouse_id, $net_total_after_discount, $transactiontype, $updateSale);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الفاتورة بنجاح.',
            'net_total_after_discount' => $saleInvoice->net_total_after_discount,
            'discount' => $saleInvoice->discount,
        ], 200);
    }

    private function calculateNetTotalAfterDiscount($saleInvoice, $validatedData)
    {
        $total_price_sale = Sale::where('Invoice_id', $saleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $saleInvoice->accounting_period_id)
            ->sum('total_amount');

        return $total_price_sale - ($validatedData['discount'] ?? 0);
    }

    private function calculateDiscount($saleInvoice)
    {
        return Sale::where('Invoice_id', $saleInvoice->sales_invoice_id)
            ->where('accounting_period_id', $saleInvoice->accounting_period_id)
            ->sum('discount');
    }

    private function handleTransactionType4($saleInvoice, $validatedData, $DefaultCustomer, $net_total_after_discount, $transactiontype, $updateSale)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $qurye = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('Invoice_id', $saleInvoice->sales_invoice_id);

        $Selling_price = $qurye->sum('total_amount');
        $total_price_sale = $qurye->sum('total_price');
        $discount = $qurye->sum('discount');
        $Purchase_price = $qurye->sum('total_purchasePrice');

        $Profit = $Selling_price - ($saleInvoice->discount ?? $discount ?? 0);
        $ProfitTotal = $Profit - $Purchase_price;
        $net_total_after_discount = $total_price_sale;
        $amountCreditTotal = $Purchase_price;
        $amountDeditTotal =  $saleInvoice->net_total_after_discount;
        $paid_amount = 0;
        $account_debit = null;
        $account_Credit = null;
        switch ($validatedData['payment_type']) {
            case 1:
            case 3:
            case 4:
                $account_debit = $validatedData['financial_account_id'];
                $account_Credit = $DefaultCustomer->warehouse_id;
                $paid_amount = $net_total_after_discount;
                break;

            case 2:
                $account_Credit = $DefaultCustomer->warehouse_id;
                $account_debit = $validatedData['Customer_name_id'];
                $paid_amount = 0;
                break;
            default:
                break;
        }

        $amountCredit = $ProfitTotal > 0 ? $ProfitTotal : 0;
        $amountDebit = $ProfitTotal < 0 ? abs($ProfitTotal) : 0;

        $accountCredit = SubAccount::where('sub_account_id', 2)->first();
        $transaction_type = TransactionType::fromValue($saleInvoice->transaction_type)?->label();
        $this->updateSales($updateSale, $validatedData, $DefaultCustomer, $saleInvoice, $paid_amount);
        $inv = SaleInvoice::where('sales_invoice_id', $saleInvoice->sales_invoice_id)->first();
        $entrie_id = DailyEntrie::where('invoice_id', $inv->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->whereIn('daily_entries_type', ["مردود مبيعات", "مبيعات"])
            ->where('account_credit_id', '!=', $accountCredit->sub_account_id) // هنا يجب التأكد من وجود قيمة صالحة لـ $accountCredit->sub_account_id
            ->first();
        $this->saleInvoiceupdate($validatedData, $inv, $accountingPeriod, $account_Credit, $account_debit, $entrie_id, $amountDeditTotal, $amountCreditTotal, $validatedData['payment_type'], $paid_amount ?? 0);

        $entrieid = DailyEntrie::where('invoice_id', $inv->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->whereIn('daily_entries_type', ["مردود مبيعات", "مبيعات"])
            ->where('account_credit_id', $accountCredit->sub_account_id)
            ->first();
        $this->saleInvoiceupdate($validatedData, $inv, $accountingPeriod, $accountCredit->sub_account_id, $accountCredit->sub_account_id, $entrieid, $amountDebit, $amountCredit, $validatedData['payment_type'], $paid_amount ?? 0);
    }


    private function handleTransactionType5($saleInvoice, $validatedData, $DefaultCustomer, $net_total_after_discount, $transactiontype, $updateSale)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $qurye = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('Invoice_id', $saleInvoice->sales_invoice_id);

        $Selling_price = $qurye->sum('total_amount');
        $total_price_sale = $qurye->sum('total_price');
        $discount = $qurye->sum('discount');
        $Purchase_price = $qurye->sum('total_purchasePrice');
        // حساب الخصم والأرباح
        $Profit =  $Selling_price - $validatedData['discount'] ?? $saleInvoice->discount ?? $discount ?? 0;
        $ProfitTotal = $Profit - $Purchase_price;
        $net_total_after_discount = $total_price_sale;

        $amountCreditTotal =  $saleInvoice->net_total_after_discount;
        $amountDeditTotal = $Purchase_price;
        $paid_amount = 0;
        $account_debit = null;
        $account_Credit = null;
        switch ($validatedData['payment_type']) {
            case 1:
            case 3:
            case 4:
                $account_Credit = $validatedData['financial_account_id'];
                $account_debit = intval($DefaultCustomer->warehouse_id);
                $paid_amount = $net_total_after_discount;
                break;

            case 2:
                $account_Credit = $validatedData['Customer_name_id'];
                $account_debit = intval($DefaultCustomer->warehouse_id);
                break;

            default:
                break;
        }

        $amountDebit = $ProfitTotal > 0 ? $ProfitTotal : 0;
        $amountCredit = $ProfitTotal < 0 ? abs($ProfitTotal) : 0;
        $transaction_type = TransactionType::fromValue($saleInvoice->transaction_type)?->label();
        $this->updateSales($updateSale, $validatedData, $DefaultCustomer, $saleInvoice, $paid_amount);
        $accountCredit = SubAccount::where('sub_account_id', 2)->first();
        $inv = SaleInvoice::where('sales_invoice_id', $saleInvoice->sales_invoice_id)->first();;
        $entrie_id = DailyEntrie::where('invoice_id', $inv->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->whereIn('daily_entries_type', ["مردود مبيعات", "مبيعات"])
            ->where('account_credit_id', '!=', $accountCredit->sub_account_id) // هنا يجب التأكد من وجود قيمة صالحة لـ $accountCredit->sub_account_id
            ->first();


        $this->saleInvoiceupdate($validatedData, $inv, $accountingPeriod, $account_Credit, $account_debit, $entrie_id, $amountDeditTotal, $amountCreditTotal, $validatedData['payment_type'], $paid_amount ?? 0);
        $entrieid = DailyEntrie::where('invoice_id', $inv->sales_invoice_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->whereIn('daily_entries_type', ["مردود مبيعات", "مبيعات"])
            ->where('account_credit_id', $accountCredit->sub_account_id) // هنا يجب التأكد من وجود قيمة صالحة لـ $accountCredit->sub_account_id
            ->first();
        $this->saleInvoiceupdate($validatedData, $inv, $accountingPeriod, $accountCredit->sub_account_id, $accountCredit->sub_account_id, $entrieid, $amountDebit, $amountCredit, $validatedData['payment_type'], $paid_amount ?? 0);
    }

    private function saleInvoiceupdate($validatedData, $saleInvoice, $accountingPeriod, $account_Credit, $account_debit, $Getentrie_id, $amountDeditTotal, $amountCreditTotal, $payment_type, $paid_amount)
    {


        // DB::beginTransaction();
        try {
            // تحديث تاريخ الفاتورة إذا كان listRadio == 2
            if ($validatedData['transaction_type'] != 6) {

                if ($validatedData['listRadio'] == 2) {
                    $date = Carbon::createFromFormat('Y-m-d', $validatedData['date']);
                    DB::table('sales_invoices')->where('sales_invoice_id', $saleInvoice->sales_invoice_id)->update(['created_at' => $date]);
                }

                // تحديث بيانات الفاتورة


                // التحقق من وجود صفحة يومية
                $today = Carbon::now()->toDateString();
                $dailyPage = GeneralJournal::where('accounting_period_id', $accountingPeriod->accounting_period_id)->whereDate('created_at', $today)->latest()->first();
                if (!$dailyPage) {
                    $dailyPage = GeneralJournal::create(['accounting_period_id' => $accountingPeriod->accounting_period_id]);
                }

                if (!$dailyPage || !$dailyPage->page_id) {
                    // DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
                }

                // التعامل مع نوع الدفع
                $payment_types = [
                    1 => 'نقدا',
                    2 => 'اجل',
                    3 => 'تحويل بنكي',
                    4 => 'شيك'
                ];
                $paymenttype = $payment_types[$payment_type] ?? 'غير معروف';

                $commint = $this->getComment($saleInvoice);
                $note = !empty($validatedData['note']) ? "/" . $validatedData['note'] : '';
                $Getentrieid = $Getentrie_id->entrie_id ?? null;
                $daily_page_id = $Getentrie_id->daily_page_id ?? $dailyPage->page_id;
                $transaction_type  = TransactionType::fromValue($validatedData['transaction_type'])?->label();
                // إنشاء أو تحديث القيد اليومي
                $curre = Currency::where('currency_id', $saleInvoice->currency_id)->first();

                $dailyEntrie = DailyEntrie::updateOrCreate(
                    [
                        'entrie_id' => $Getentrieid,
                        'invoice_id' => $saleInvoice->sales_invoice_id,
                        'accounting_period_id' => $accountingPeriod->accounting_period_id,

                    ],

                    [
                        'daily_entries_type' => $transaction_type,
                        'account_credit_id' => $account_Credit,
                        'account_debit_id' => $account_debit,
                        'amount_credit' => $amountCreditTotal ?? 0,
                        'created_at' => $saleInvoice->created_at,

                        'amount_debit' => $amountDeditTotal ?: 0,
                        'statement' => $commint . " " . $transaction_type . " " . PaymentType::tryFrom($saleInvoice->payment_type)?->label() . $note,
                        'daily_page_id' => $daily_page_id ?? $dailyPage->page_id,
                        'invoice_type' => $saleInvoice->payment_type,
                        'currency_name' => $curre->currency_name,
                        'user_id' => auth()->user()->id,
                        'status_debit' => 'غير مرحل',
                        'status' => 'غير مرحل',
                    ]
                );
                //    dd($dailyEntrie);


                // تحديث تاريخ القيد اليومي
                DB::table('daily_entries')
                    ->where('entrie_id', $Getentrie_id->entrie_id)
                    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->where('invoice_id', $saleInvoice->sales_invoice_id)
                    ->update(['created_at' => Carbon::createFromFormat('Y-m-d', $validatedData['date'])]);

                // DB::commit();
            }
            if ($validatedData['transaction_type'] == 6) {


                if ($Getentrie_id) {
                    DB::table('daily_entries')
                        ->where('entrie_id', $Getentrie_id->entrie_id)
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                        ->where('invoice_id', $saleInvoice->sales_invoice_id)
                        ->delete();
                    $generalEntrieaccount_debit_id = GeneralEntrie::where([
                        'daily_entry_id' => $Getentrie_id->entrie_id,
                        'accounting_period_id' => $Getentrie_id->accounting_period_id,
                        'sub_id' => $Getentrie_id->account_debit_id,
                    ])->delete();
                    $generalEntrieaccount_debit_id = GeneralEntrie::where([
                        'daily_entry_id' => $Getentrie_id->entrie_id,
                        'accounting_period_id' => $Getentrie_id->accounting_period_id,
                        'sub_id' => $Getentrie_id->account_credit_id,
                    ])->delete();
                }
            }
        } catch (\Exception $e) {
            // DB::rollBack();
            return response()->json(['success' => false, 'message' => 'حدث خطأ.']);
        }
    }

    private function updateSales($updateSale, $validatedData, $DefaultCustomer, $saleInvoice, $paid_amount)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        // تحقق من أن $updateSale هو مصفوفة أو كائن قابل للتكرار
        if (is_array($updateSale) || is_object($updateSale)) {
            foreach ($updateSale as $sale) {
                $sale->update([
                    'transaction_type' => $validatedData['transaction_type'],
                    'Customer_id' => $validatedData['Customer_name_id'],
                    'financial_account_id' => $validatedData['financial_account_id'],
                ]);
            }
            $qurye = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('Invoice_id', $saleInvoice->sales_invoice_id);
            $Selling_price = $qurye->sum('total_amount');
            $discount = $qurye->sum('discount');
            $quryes = $qurye->get();
            $discountInvoices = $saleInvoice->discount;
            $turr = $discountInvoices / $Selling_price;
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
                        }
                    }
                }
            }
        } else {
            return response()->json(['success' => false, 'message' => 'بيانات المبيعات غير صحيحة']);
        }
    }



    private function getComment($saleInvoice)
    {
        if ($saleInvoice->transaction_type == 4) {
            if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
                return "فاتورة";
            } elseif ($saleInvoice->payment_type == 2) {

                return "عليكم فاتورة";
            }
        }
        if ($saleInvoice->transaction_type == 5) {
            if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
                return "فاتورة";
            } elseif ($saleInvoice->payment_type == 2) {

                return "لكم فاتورة";
            }
        }
    }

    public function searchInvoices(Request $request)
    {
        $accountingPeriod = Cache::remember('active_accounting_period', 3600, function () {
            return AccountingPeriod::where('is_closed', false)->first();
        });

        if (!$accountingPeriod) {
            return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
        }
        $validated = $request->validate([
            'searchType' => 'nullable|string|in:كل الفواتير,أول فاتورة,آخر فاتورة',
            'searchQuery' => 'nullable|string|max:255',
            'fromDate' => 'nullable',
            'toDate' => 'nullable',
        ]);
        // بناء الاستعلام مع تحديد العلاقات المطلوبة
        $query = SaleInvoice::with([
            'customer:sub_account_id,sub_name,main_id', // لا نحتاج account_class هنا
            'customer.mainAccount:main_account_id,AccountClass', // استخدام AccountClass بدلاً من account_class
            'user:id,name'
        ]);

        if ($validated['searchQuery'] ?? false) {
            $searchQuery = $validated['searchQuery'];

            $query->where(function ($query) use ($searchQuery) {
                // البحث باستخدام رقم الفاتورة
                $query->where('sales_invoice_id', 'like', $searchQuery . '%')
                    // البحث باستخدام اسم المورد
                    ->orWhereHas('customer', function ($query) use ($searchQuery) {
                        $query->where('sub_name', 'like', $searchQuery . '%'); // البحث عن الأسماء التي تبدأ بالقيمة المدخلة
                    });
            });
        }

        // ترتيب الفواتير حسب نوع البحث
        if ($request->filled(['fromDate', 'toDate'])) {
            // dd($validated['fromDate'], $validated['toDate']);

            $query->whereBetween('created_at', [$validated['fromDate'], $validated['toDate']]);
        } else {
            $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
            if ($validated['searchType'] && $validated['searchType'] !== 'كل الفواتير') {
                $orderDirection = ($validated['searchType'] === 'أول فاتورة') ? 'asc' : 'desc';
                $query->orderBy('created_at', $orderDirection);
            }
        }

        $saleInvoices = $query->paginate(50);

        if ($request->filled('searchQuery') || $request->filled('fromDate') || $request->filled('toDate')) {
            // إعادة حساب الترحيم بناءً على نتائج البحث المصفاة
            $saleInvoices = $query->paginate(50);
        }

        // تحويل البيانات
        $transformedInvoices = $saleInvoices->getCollection()->map(function ($invoice) {
            return [
                'formatted_date' => $invoice->created_at->format('d/m/Y'),
                'Customer_name' => $invoice->customer->sub_name ?? 'غير معروف',
                'main_account_class' => optional($invoice->customer?->mainAccount)->accountClassLabel() ?? 'غير معروف',
                'transaction_type' => TransactionType::fromValue($invoice->transaction_type)?->label() ?? 'غير معروف',
                'invoice_number' => $invoice->sales_invoice_id,
                'discount' => $invoice->discount,
                'payment_type' => PaymentType::tryFrom($invoice->payment_type)?->label() ?? 'غير معروف',
                'shipping_bearer' => $invoice->shipping_bearer,
                'shipping_amount' => number_format($invoice->shipping_amount, 2),
                'total_price_sale' => number_format($invoice->total_price_sale, 2),
                'net_total_after_discount' => $invoice->net_total_after_discount,
                'paid_amount' => $invoice->paid_amount,
                'remaining_amount' => $invoice->remaining_amount,
                'user_name' => $invoice->user->name ?? 'غير معروف',
                'updated_at' => $invoice->updated_at->format('Y-m-d'),
                'view_url' => route('searchInvoices', $invoice->sales_invoice_id),
                'destroy_url' => route('sales-invoice.delete', $invoice->sales_invoice_id),
            ];
        });

        return response()->json([
            'saleInvoice' => $transformedInvoices,
            'pagination' => [
                'current_page' => $saleInvoices->currentPage(),
                'last_page' => $saleInvoices->lastPage(),
                'per_page' => $saleInvoices->perPage(),
                'total' => $saleInvoices->total(),
            ]
        ]);
    }
    public function getSaleInvoice(Request $request, $filterType)
    {
        // تحقق من الفترة المحاسبية مع التخزين المؤقت
        $accountingPeriod = Cache::remember('active_accounting_period', 3600, function () {
            return AccountingPeriod::where('is_closed', false)->first();
        });

        if (!$accountingPeriod) {
            return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
        }

        // بناء الاستعلام مع تحديد العلاقات المطلوبة
        $query = SaleInvoice::with([
            'customer:sub_account_id,sub_name,main_id', // لا نحتاج account_class هنا
            'customer.mainAccount:main_account_id,AccountClass', // استخدام AccountClass بدلاً من account_class
            'user:id,name'
        ]);

        // تطبيق الفلاتر
        switch ($filterType) {
            case '1':
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                break;
            case '2':
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->whereDate('created_at', now()->toDateString());
                break;
            case '3':
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case '4':
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case '5':
                if ($request->filled(['fromDate', 'toDate'])) {
                    $query->whereBetween('created_at', [
                        $request->input('fromDate') . ' 00:00:00',
                        $request->input('toDate') . ' 23:59:59'
                    ]);
                }
                break;
        }

        // التحقق من الصلاحيات
        $user = auth()->id();
        $authorityName = "الفواتير المبيعات";
        $permission = UserPermission::where('User_id', $user)
            ->where('Authority_Name', $authorityName)
            ->first();

        if (!$permission || $permission->Readability != 1) {
            return response()->json(['error' => 'غير مصرح بالوصول'], 403);
        }

        // استخدام الترحيم
        $saleInvoices = $query->paginate(50);

        // تحويل البيانات
        $transformedInvoices = $saleInvoices->getCollection()->map(function ($invoice) {
            return [
                'formatted_date' => $invoice->created_at->format('d/m/Y'),
                'Customer_name' => $invoice->customer->sub_name ?? 'غير معروف',
                'main_account_class' => optional($invoice->customer?->mainAccount)->accountClassLabel() ?? 'غير معروف',
                'transaction_type' => TransactionType::fromValue($invoice->transaction_type)?->label() ?? 'غير معروف',
                'invoice_number' => $invoice->sales_invoice_id,
                'discount' => $invoice->discount,
                'payment_type' => PaymentType::tryFrom($invoice->payment_type)?->label() ?? 'غير معروف',
                'shipping_bearer' => $invoice->shipping_bearer,
                'shipping_amount' => number_format($invoice->shipping_amount, 2),
                'total_price_sale' => number_format($invoice->total_price_sale, 2),
                'net_total_after_discount' => $invoice->net_total_after_discount,
                'paid_amount' => $invoice->paid_amount,
                'remaining_amount' => $invoice->remaining_amount,
                'user_name' => $invoice->user->name ?? 'غير معروف',
                'updated_at' => $invoice->updated_at->format('Y-m-d'),
                'view_url' => route('searchInvoices', $invoice->sales_invoice_id),
                'destroy_url' => route('sales-invoice.delete', $invoice->sales_invoice_id),
            ];
        });

        return response()->json([
            'saleInvoice' => $transformedInvoices,
            'pagination' => [
                'current_page' => $saleInvoices->currentPage(),
                'last_page' => $saleInvoices->lastPage(),
                'per_page' => $saleInvoices->perPage(),
                'total' => $saleInvoices->total(),
            ]
        ]);
    }


    public function print(Request $request, $id)
    {
        $validated = $request->validate([

            'analysis' => 'required|numeric',
            'size' => 'required|numeric',
        ]);

        if ($validated['size'] == 1) {
            $urlprint = 'invoice_sales.bills_sale_show_small';
        } else {
            $urlprint = 'invoice_sales.bills_sale_show';
        }
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        $DataPurchaseInvoice = SaleInvoice::where('sales_invoice_id', $id)->first();
        $SubAccount = SubAccount::where('sub_account_id', $DataPurchaseInvoice->Customer_id)->first();
        $UserName = User::where('id', $DataPurchaseInvoice->User_id)->pluck('name')->first();


        if (!$UserName) {
            $UserName = 'اسم غير موجود';
        }
        $SubName = SubAccount::all();
        if ($SubAccount->account_class === 1) {
            $AccountClassName = "العميل";
        }

        if ($SubAccount->account_class === 2) {
            $AccountClassName = "المورد";
        }
        if ($SubAccount->account_class === 3) {
            $AccountClassName = "المخزن";
        }
        if ($SubAccount->account_class === 4) {
            $AccountClassName = "الحساب";
        }

        if ($DataPurchaseInvoice->payment_type === 1) {
            $paymentype = "نقداً";
        }
        $saleInvoice = SaleInvoice::where('sales_invoice_id', $id)
            ->first();
        $note = $saleInvoice->note ?? '';

        $Categorys = Category::all();
        $curre = Currency::where('currency_id', $DataPurchaseInvoice->currency_id)->first();
        // حساب مجموع السعر والتكلفة

        $DataSale = Sale::where('Invoice_id', $id)->get();
        if ($DataSale->isEmpty()) {
            $Sale_priceSum = 0;
            $Sale_CostSum = 0;
        } else {
            $Sale_priceSum = $DataSale->sum('total_price');
            $Sale_CostSum = $DataSale->sum('total_amount');
            $total_Profit = $DataSale->sum('total_Profit');
        }
        // $Sale_CostSum = Sale::where('Invoice_id', $id)->sum('total_amount');
        // $discount=   $Sale_CostSum -  $saleInvoice->net_total_after_discount ??0;
        $SumDebtor_amount = DailyEntrie::where('account_debit_id', $SubAccount->sub_account_id)->sum('amount_debit');
        $SumCredit_amount = DailyEntrie::where('account_credit_id', $SubAccount->sub_account_id)->sum('amount_credit');
        $query = DailyEntrie::with(['debitAccount', 'debitAccount.mainAccount', 'creditAccount', 'creditAccount.mainAccount'])
            ->selectRaw(
                '
         SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.amount_debit ELSE 0 END) as total_debit,
         SUM(CASE WHEN daily_entries.account_credit_id = sub_accounts.sub_account_id THEN daily_entries.amount_credit ELSE 0 END) as total_credit'
            )
            ->join('sub_accounts', function ($join) {
                $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                    ->orOn('daily_entries.account_credit_id', '=', 'sub_accounts.sub_account_id');
            })
            ->where('sub_accounts.sub_account_id', $SubAccount->sub_account_id);
        // إضافة الشرط للحساب الفرعي
        $query->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id);
        $entriesTotally = $query->get();
        $SumDebtor_amount = $entriesTotally->sum('total_debit');
        $SumCredit_amount = $entriesTotally->sum('total_credit');
        $Sum_amount = $SumDebtor_amount - $SumCredit_amount;
        // تحويل القيمة إلى نص مكتوب
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('ar');
        $numeric = is_numeric($saleInvoice->net_total_after_discount)
            ? $numberTransformer->toWords($saleInvoice->net_total_after_discount) . ' ' . $curre->currency_name
            : 'القيمة غير صالحة';
        // اللغة العربية
        $thanks = "شكراً لتعاملك معنا";
        // dd($numeric);
        $user = auth()->id();
        $AuthorityName = "الفواتير المبيعات";
        $us = UserPermission::where('User_id', $user)
            ->where('Authority_Name', $AuthorityName)
            ->first();
        // Analytical-sales-invoice
        if (optional($us)->Readability == 1) {
            if ($validated['analysis'] == 1) {
                return view($urlprint, [
                    'DataPurchaseInvoice' => $DataPurchaseInvoice,
                    'DataSale' => $DataSale,
                    'SubAccounts' => $SubAccount,
                    'Sale_priceSum' => $Sale_priceSum,
                    'Sale_CostSum' => $Sale_CostSum,
                    'priceInWords' => $numeric,
                    'Categorys' => $Categorys,
                    'currency' => $curre->currency_name,
                    'payment_type' => PaymentType::tryFrom($DataPurchaseInvoice->payment_type)?->label() ?? 'غير معروف',
                    'transaction_type' => TransactionType::fromValue($DataPurchaseInvoice->transaction_type)?->label() ?? 'غير معروف',
                    'warehouses' => $SubName,
                    'UserName' => $UserName,
                    'accountCla' => $AccountClassName,
                    'Sum_amount' => $Sum_amount,
                    'net_total_after_discount' =>  $saleInvoice->net_total_after_discount,
                    'thanks' => $thanks,
                    'note' => $note ?? '',
                    'discount' => $saleInvoice->discount ?? 0,

                ]);
            }

            if ($validated['analysis'] == 2) {
                return view('invoice_sales.Analytical-sales-invoice', [
                    'DataPurchaseInvoice' => $DataPurchaseInvoice,
                    'DataSale' => $DataSale,
                    'SubAccounts' => $SubAccount,
                    'Sale_priceSum' => $Sale_priceSum,
                    'Sale_CostSum' => $Sale_CostSum,
                    'priceInWords' => $numeric,
                    'total_Profit' => $total_Profit ?? 0,
                    'Categorys' => $Categorys,
                    'currency' => $curre->currency_name,
                    'payment_type' => PaymentType::tryFrom($DataPurchaseInvoice->payment_type)?->label() ?? 'غير معروف',
                    'transaction_type' => TransactionType::fromValue($DataPurchaseInvoice->transaction_type)?->label() ?? 'غير معروف',
                    'warehouses' => $SubName,
                    'UserName' => $UserName,
                    'accountCla' => $AccountClassName,
                    'Sum_amount' => $Sum_amount,
                    'net_total_after_discount' =>  $saleInvoice->net_total_after_discount,
                    'thanks' => $thanks,
                    'note' => $note ?? '',
                    'discount' => $saleInvoice->discount ?? 0,

                ]);
            }
        } else {
            return view('auth.login');
        }
    }
}
