<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;//+
use Illuminate\Support\Facades\DB;//+
use NumberToWords\NumberToWords;

class Review_BudgetController extends Controller
{

    public function review_budget($year)
    {

       // استرجاع الفترة المحاسبية المفتوحة
       $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
       $balances = DailyEntrie::selectRaw(
        'sub_accounts.sub_account_id,
        sub_accounts.sub_name,
        sub_accounts.phone,
        sub_accounts.type_account,
      SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.currency_name = \'ريال.يمني\' THEN daily_entries."amount_debit" ELSE 0 END) as total_debit,
    SUM(CASE WHEN daily_entries.account_credit_id = sub_accounts.sub_account_id AND daily_entries.currency_name = \'ريال.يمني\' THEN daily_entries."amount_credit" ELSE 0 END) as total_credit,
    SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.currency_name = \'ريال سعودي\' THEN daily_entries."amount_debit" ELSE 0 END) as total_debits,
    SUM(CASE WHEN daily_entries.account_credit_id = sub_accounts.sub_account_id AND daily_entries.currency_name = \'ريال سعودي\' THEN daily_entries."amount_credit" ELSE 0 END) as total_credits,
    SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.currency_name = \'دولار امريكي\' THEN daily_entries."amount_debit" ELSE 0 END) as total_debitd,
    SUM(CASE WHEN daily_entries.account_credit_id = sub_accounts.sub_account_id AND daily_entries.currency_name = \'دولار امريكي\' THEN daily_entries."amount_credit" ELSE 0 END) as total_creditd'
)
    ->join('sub_accounts', function ($join) {
        $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
             ->orOn('daily_entries.account_credit_id', '=', 'sub_accounts.sub_account_id');
    })
    ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
    ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.phone','sub_accounts.type_account')
    ->get();
    $total_balance_YER =0;
    $total_balance_SAD = 0;
    $total_balance_USD = 0;
    $debit_YER   = 0;
    $credit_YER  = 0;
    $debits_SAD  = 0;
    $credits_SAD = 0;
    $debitd_USD  = 0;
    $credits_USD = 0;
    foreach($balances as $balance)
    {
        $debitd_USD+=$balance->total_debitd;
        $credits_USD+=$balance->total_creditd;
        $debit_YER+=$balance->total_debit;
        $credit_YER+=$balance->total_credit;
        $debits_SAD+=$balance->total_debits;
        $credits_SAD+=$balance->total_credits;
    }
        $SumDebtor_amount = 0;
        $SumCredit_amount = 0;
        $total_debits_SAD = 0;
        $total_credits_SAD = 0;
        $YER="ريال.يمني";
        $SAD="ريال سعودي";
        $USD="دولار امريكي";
        $total_balance_YER=$debit_YER- $credit_YER;
        $total_balance_SAD=$debits_SAD- $credits_SAD;
        $total_balance_USD=$debitd_USD- $credits_USD;
    $startDate = $accountingPeriod->created_at->format('Y-m-d') ?? 'غير متوفر';
    $endDate = now()->toDateString();
   // معالجة البيانات لإضافة الفارق ونوع
    $Sale_priceSum = $SumDebtor_amount -abs( $SumCredit_amount);
    $SumAmount = abs($SumDebtor_amount - $SumCredit_amount);
   $numberToWords = new NumberToWords();
    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
    $priceInWordsYER=is_numeric($total_balance_YER)
    ? $numberTransformer->toWords(abs( $total_balance_YER)) .' '.$YER
    : 'القيمة غير صالحة';
    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
    $priceInWordsSAD=is_numeric($total_balance_SAD)
    ? $numberTransformer->toWords(abs($total_balance_SAD)) . ' ' . $SAD
    : 'القيمة غير صالحة';
    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
    $priceInWordsUSD=is_numeric($total_balance_USD)
    ? $numberTransformer->toWords(abs($total_balance_USD)) . ' ' . $USD
    : 'القيمة غير صالحة';

      
 $SumDebtor_amount2 =0;
        $SumCredit_amount2 =0;
       
        // حساب الفرق (الربح/الخسارة)
        // $Sale_priceSum2 = abs($SumDebtor_amount2 - $SumCredit_amount2);


// dd($debit_YER);

        // تمرير البيانات إلى العرض
        return view('accounts.review-budget', compact(
            'accountingPeriod',

            'balances',
            'SumDebtor_amount',
            'SumCredit_amount' ,
        

            'accountingPeriod',
            'priceInWordsYER',
            'priceInWordsUSD',
            'priceInWordsSAD',
            'startDate',
            'endDate',
            'debit_YER' ,
            'credit_YER',
            'debits_SAD' ,
            'credits_SAD' ,
            'debitd_USD' ,
            'credits_USD' ,
            'total_balance_YER',
            'total_balance_SAD',
            'total_balance_USD',
             'YER',
             'SAD',
            'USD',
));
    }


    }
