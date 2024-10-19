<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;//+
use Illuminate\Support\Facades\DB;//+

class Review_BudgetController extends Controller
{

    public function review_budget($year)
    {
        // جلب الفترة المحاسبية المحددة
        $accountingPeriod = AccountingPeriod::where('Year', $year)->firstOrFail();
    
        // استرجاع الحسابات الرئيسية مع حساباتها الفرعية وجمع الأرصدة
        $mainAccountsTotals = MainAccount::with(['subAccounts' => function ($query) {
            $query->select([
                'sub_accounts.sub_account_id',
                'sub_accounts.Main_id',
                'sub_accounts.sub_name',
                DB::raw('SUM(DISTINCT COALESCE(sub_accounts.debtor_amount, 0) + COALESCE(debit.Amount_debit, 0)) as total_debit'),
                DB::raw('SUM(DISTINCT COALESCE(sub_accounts.creditor_amount, 0) + COALESCE(credit.Amount_credit, 0)) as total_credit'),
                DB::raw('(SUM(DISTINCT COALESCE(sub_accounts.debtor_amount, 0) + COALESCE(debit.Amount_debit, 0)) - SUM(DISTINCT COALESCE(sub_accounts.creditor_amount, 0) + COALESCE(credit.Amount_credit, 0))) as balance')
            ])
            ->leftJoin('daily_entries AS debit', 'sub_accounts.sub_account_id', '=', 'debit.account_debit_id')
            ->leftJoin('daily_entries AS credit', 'sub_accounts.sub_account_id', '=', 'credit.account_Credit_id')
            ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.Main_id', 'sub_accounts.sub_name');
        }])->get();
    
        // استخدام المجموعات لتحسين العمليات الحسابية
        $totals = $mainAccountsTotals->reduce(function ($carry, $mainAccount) {
            $debitSum = 0;
            $creditSum = 0;
    
            foreach ($mainAccount->subAccounts as $subAccount) {
                $balance = $subAccount->balance;
    
                if ($balance > 0) {
                    $debitSum += $balance; // مجموع الأرصدة المدينة
                } elseif ($balance < 0) {
                    $creditSum += $balance; // مجموع الأرصدة الدائنة
                }
            }
    
            if (in_array($mainAccount->typeAccount, [1, 2])) {
                $carry['totalDebit'] += $debitSum+$creditSum;
                $carry['totalCredit'] += $creditSum;
            } elseif ($mainAccount->typeAccount == 3) {
                $carry['totalDebit2'] += $debitSum;
                $carry['totalCredit2'] += $creditSum;
            }
    
            return $carry;
        }, ['totalDebit' => 0, 'totalCredit' => 0, 'totalDebit2' => 0, 'totalCredit2' => 0]);
    
        // تمرير البيانات إلى العرض
        return view('accounts.review-budget', [
            'mainAccountsTotals' => $mainAccountsTotals,
            'accountingPeriod' => $accountingPeriod,
            'totalDebit' => $totals['totalDebit'],
            'totalCredit' => $totals['totalCredit'],
            'totalDebit2' => $totals['totalDebit2'],
            'totalCredit2' => $totals['totalCredit2'],
        ]);
    }
    
    
    }
