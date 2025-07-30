<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\SubAccount;
use Illuminate\Support\Facades\DB;

class Review_BudgetController extends Controller
{
    public function review_budget($year)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();
        
        $assets = $this->getAccountsData([1, 2, 5], $accountingPeriod);
        $liabilities = $this->getAccountsData(3, $accountingPeriod);
        
        return view('accounts.review-budget', [
            'accountingPeriod' => $accountingPeriod,
            'assets' => $assets,
            'liabilities' => $liabilities,
            'startDate' => $accountingPeriod->created_at->format('Y-m-d'),
            'endDate' => now()->toDateString()
        ]);
    }

    protected function getAccountsData($types, $accountingPeriod)
    {
        $query = SubAccount::query()
            ->whereIn('type_account', (array)$types)
            ->select([
                'sub_accounts.sub_name',
                'sub_accounts.sub_account_id',
                'sub_accounts.type_account',
                $this->getDebitSelect('ريال.يمني', 'total_debit', $accountingPeriod),
                $this->getCreditSelect('ريال.يمني', 'total_credit', $accountingPeriod),
                $this->getDebitSelect('ريال سعودي', 'total_debits', $accountingPeriod),
                $this->getCreditSelect('ريال سعودي', 'total_credits', $accountingPeriod),
                $this->getDebitSelect('دولار امريكي', 'total_debitd', $accountingPeriod),
                $this->getCreditSelect('دولار امريكي', 'total_creditd', $accountingPeriod)
            ]);
            
        return $query->orderByRaw('(total_debit - total_credit) DESC')->get();
    }

    protected function getDebitSelect($currency, $alias, $accountingPeriod)
    {
        return DB::raw("(
            SELECT IFNULL(SUM(amount_debit), 0) 
            FROM daily_entries 
            WHERE account_debit_id = sub_accounts.sub_account_id
            AND currency_name = '{$currency}'
            AND accounting_period_id = {$accountingPeriod->accounting_period_id}
        ) AS {$alias}");
    }

    protected function getCreditSelect($currency, $alias, $accountingPeriod)
    {
        return DB::raw("(
            SELECT IFNULL(SUM(amount_credit), 0) 
            FROM daily_entries 
            WHERE account_credit_id = sub_accounts.sub_account_id
            AND currency_name = '{$currency}'
            AND accounting_period_id = {$accountingPeriod->accounting_period_id}
        ) AS {$alias}");
    }
}