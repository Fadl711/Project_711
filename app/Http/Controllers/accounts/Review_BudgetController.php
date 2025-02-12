<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;//+
use Illuminate\Support\Facades\DB;//+

class Review_BudgetController extends Controller
{
   
    public function review_budget($year)
    {
       // استرجاع الفترة المحاسبية المفتوحة
       $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
 if ($accountingPeriod) {
    $customerMainAccount = MainAccount::whereIn('typeAccount',[1,2,5] )->get();
    // foreach ($customerMainAccount as $customerMai) {
        // $idaccounn = $customerMai->main_account_id;
        $SumDebtor_amount =0;
        $SumCredit_amount =0;
        foreach ($customerMainAccount as $balance)
        {
           $customerMainAccount = SubAccount::where('Main_id', $balance->main_account_id)->first();
               $total_debit=DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
               ->where('account_debit_id',$customerMainAccount->sub_account_id)
               ->sum('Amount_debit')
               ;
               $total_credit=DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
               ->where('account_Credit_id',$customerMainAccount->sub_account_id)
               ->sum('Amount_Credit');
   
           $Sum_amount = ($total_debit ?? 0) - ($total_credit ?? 0);
           
           if ($Sum_amount !== 0) {
               if ($Sum_amount > 0) {
                   $SumDebtor_amount += $Sum_amount;
                   $SumDebtoramount = $Sum_amount;
                   $SumCreditamount = 0;
               }
               if ($Sum_amount < 0) {
                   $SumCredit_amount += $Sum_amount;
                   $SumCreditamount = $Sum_amount;
                   $SumDebtoramount = 0;
               }
           }
       }
        $balances = DailyEntrie::selectRaw(
            'sub_accounts.sub_account_id,
            sub_accounts.sub_name,
            sub_accounts.Phone,
            sub_accounts.AccountClass,
            sub_accounts.typeAccount,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
        )
        ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
        ->join('sub_accounts', function ($join) {
            $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                 ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
        })->whereIn('sub_accounts.typeAccount',[1,2,5])
        ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
        ->get();
        // حساب الإجماليات (بناءً على البيانات المسترجعة)
  

        // حساب الفرق (الربح/الخسارة)
        $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);

        $balances2 = DailyEntrie::selectRaw(
            'sub_accounts.sub_account_id,
            sub_accounts.sub_name,
            sub_accounts.Phone,
            sub_accounts.AccountClass,
            sub_accounts.typeAccount,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit2,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit2'
        )
        ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
        ->join('sub_accounts', function ($join) {
            $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                 ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
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
               ->where('account_Credit_id',$balanc2->sub_account_id)
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
        $Sale_priceSum2 = abs($SumDebtor_amount2 - $SumCredit_amount2);

 } else {
}
       
        // تمرير البيانات إلى العرض
        return view('accounts.review-budget', [
            'accountingPeriod' => $accountingPeriod,
         
            'balances' => $balances,
            'SumDebtor_amount' => $SumDebtor_amount,
            'SumCredit_amount' => $SumCredit_amount,
            'balances2' => $balances2,
            'SumDebtor_amount2' => $SumDebtor_amount2,
            'SumCredit_amount2' => $SumCredit_amount2,
        ]);
    }
    
    
    }
