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
    // استرجاع الحسابات الرئيسية مع حساباتها الفرعية وجمع الأرصدة
    $mainAccountsTotals = MainAccount::with(['subAccounts' => function ($query) use ($accountingPeriod) {
        $query->select([
            'sub_accounts.sub_account_id',
            'sub_accounts.Main_id',
            'sub_accounts.sub_name',
            DB::raw('SUM(COALESCE(debit.Amount_debit, 0)) as total_debit'),
            DB::raw('SUM(COALESCE(credit.Amount_credit, 0)) as total_credit'),
            DB::raw('(SUM(COALESCE(debit.Amount_debit, 0)) - SUM(COALESCE(credit.Amount_credit, 0))) as balance')
        ])
        ->leftJoin('daily_entries AS debit', function($join) use ($accountingPeriod) {
            $join->on('sub_accounts.sub_account_id', '=', 'debit.account_debit_id')
                 ->whereBetween('debit.accounting_period_id', [$accountingPeriod->accounting_period_id, now()]); // تأكد من أن القيود في الفترة
        })
        ->leftJoin('daily_entries AS credit', function($join) use ($accountingPeriod) {
            $join->on('sub_accounts.sub_account_id', '=', 'credit.account_Credit_id')
                 ->whereBetween('credit.accounting_period_id', [$accountingPeriod->accounting_period_id, now()]); // تأكد من أن القيود في الفترة
        })
        ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.Main_id', 'sub_accounts.sub_name');
    }])->get();
 } else {
    // إذا لم توجد فترة محاسبية مفتوحة
    $mainAccountsTotals = collect(); // أو يمكنك إرجاع رسالة مناسبة
}
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
