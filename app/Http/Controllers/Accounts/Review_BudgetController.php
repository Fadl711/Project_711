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
        sub_accounts."Phone",
        sub_accounts.typeAccount,
        SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
        SUM(CASE WHEN daily_entries.account_credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit,
        SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_debit ELSE 0 END) as total_debits,
        SUM(CASE WHEN daily_entries.account_credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credits,
        SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار امريكي" THEN daily_entries.Amount_debit ELSE 0 END) as total_debitd,
        SUM(CASE WHEN daily_entries.account_credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار امريكي"  THEN daily_entries.Amount_Credit ELSE 0 END) as total_creditd
    ')
    ->join('sub_accounts', function ($join) {
        $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
             ->orOn('daily_entries.account_credit_id', '=', 'sub_accounts.sub_account_id');
    })
    ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
    ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone','typeAccount')
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

        $balances2 = DailyEntrie::selectRaw(
            'sub_accounts.sub_account_id,
            sub_accounts.sub_name,
            sub_accounts.Phone,
            sub_accounts.AccountClass,
            sub_accounts.typeAccount,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit2,
            SUM(CASE WHEN daily_entries.account_credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit2'
        )
        ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
        ->join('sub_accounts', function ($join) {
            $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                 ->orOn('daily_entries.account_credit_id', '=', 'sub_accounts.sub_account_id');
        })->where('sub_accounts.typeAccount',3)
        ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
        ->get();

 $SumDebtor_amount2 =0;
        $SumCredit_amount2 =0;
        $customerMainAccounts = MainAccount::where('typeAccount',4 )->get();

        foreach ($customerMainAccounts as $balanc)
        {
           $customerMainAccoun = SubAccount::where('Main_id', $balanc->main_account_id)->get();
           foreach ($customerMainAccoun as $balanc2)
           {
               $total_debit2=DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
               ->where('account_debit_id',$balanc2->sub_account_id)
               ->sum('Amount_debit')
               ;
               $total_credit2=DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
               ->where('account_credit_id',$balanc2->sub_account_id)
               ->sum('Amount_Credit');

           $Sum_amount = ($total_debit2 ?? 0) - ($total_credit2 ?? 0);

           if ($Sum_amount !== 0) {
               if ($Sum_amount > 0) {
                   $SumDebtor_amount2 += $Sum_amount;
                   $SumDebtor_amount2 = $Sum_amount;
                   $SumCredit_amount2 = 0;
               }
               if ($Sum_amount < 0) {
                   $SumCredit_amount2 += $Sum_amount;
                   $SumCredit_amount2 = $Sum_amount;
                   $SumDebtor_amount2 = 0;
               }
           }
           }

       }
        // حساب الفرق (الربح/الخسارة)
        // $Sale_priceSum2 = abs($SumDebtor_amount2 - $SumCredit_amount2);


// dd($debit_YER);

        // تمرير البيانات إلى العرض
        return view('accounts.review-budget', compact(
            'accountingPeriod',

            'balances',
            'SumDebtor_amount',
            'SumCredit_amount' ,
            'balances2',
            'SumDebtor_amount2',
            'SumCredit_amount2',

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
