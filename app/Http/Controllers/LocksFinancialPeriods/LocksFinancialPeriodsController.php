<?php

namespace App\Http\Controllers\LocksFinancialPeriods;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\GeneralEntrie;
use Illuminate\Http\Request;

class LocksFinancialPeriodsController extends Controller
{
    //
    public function index(){
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        $credit="credit";
    $debit="debit";
    $RevenueDebit = GeneralEntrie::where('typeAccount',5)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type',$debit)
    ->sum('amount');
    $RevenueCredit = GeneralEntrie::where('typeAccount',5)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type', $credit)
    ->sum('amount');
    $totalRevenue = $RevenueCredit - $RevenueDebit;

    // ______________________________
   
    $ExpensesDebit = GeneralEntrie::where('typeAccount',4)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type',$debit)
    ->sum('amount');
    $ExpensesCredit = GeneralEntrie::where('typeAccount',4)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type', $credit)
    ->sum('amount');


  $totalExpenses = $ExpensesCredit - $ExpensesDebit;
       
        return view('locks_financial_period.index');
    }

    public function getProfitAndLossData()
{
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    
    // $totalRevenue = GeneralEntrie::where('account_type', 'revenue')
    //     ->where('accounting_period_id', $accountingPeriod->id)
    //     ->sum('amount');
    // $totalExpenses = GeneralEntrie::where('account_type', 'expense')
    //     ->where('accounting_period_id', $accountingPeriod->id)
    //     ->sum('amount');
  
    $credit="credit";
    $debit="debit";
    $RevenueDebit = GeneralEntrie::where('typeAccount',5)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type',$debit)
    ->sum('amount');
    $RevenueCredit = GeneralEntrie::where('typeAccount',5)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type', $credit)
    ->sum('amount');
    $totalRevenue =  abs($RevenueDebit)-abs($RevenueCredit );

    // ______________________________
   
    $ExpensesDebit = GeneralEntrie::where('typeAccount',4)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type',$debit)
    ->sum('amount');
    $ExpensesCredit = GeneralEntrie::where('typeAccount',4)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type', $credit)
    ->sum('amount');
    $AssetsDebit = GeneralEntrie::where('typeAccount',4)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type',$debit)
    ->sum('amount');
    $AssetsCredit = GeneralEntrie::where('typeAccount',4)
    -> where('accounting_period_id', $accountingPeriod->accounting_period_id)
    -> where('entry_type', $credit)
    ->sum('amount');
  $totalExpenses =  abs($ExpensesDebit) -abs($ExpensesCredit );
  $totalAssets =  abs($AssetsDebit) -abs($AssetsCredit );

$sum= $totalRevenue-$totalExpenses;
$netProfitOrLoss=$totalAssets+$sum;
    // $liabilities = GeneralEntrie::where('account_type', 'liability')
    //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    //     ->select('sub_account_id', DB::raw('SUM(amount) as total'))
    //     ->groupBy('sub_account_id')
    //     ->get();

    return response()->json([
        'totalRevenue' => $totalRevenue,
        'totalExpenses' => $totalExpenses,
        'netProfitOrLoss' => $netProfitOrLoss,
        'assets' => 0,
        'liabilities' => 0,
    ]);
}

}
