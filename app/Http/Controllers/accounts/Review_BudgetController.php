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
    public function review_budget($year, $month)
    {
        // جلب الفترة المحاسبية المحددة
        $accountingPeriod = AccountingPeriod::where('Year', $year)
            ->first();
    
        // التحقق من وجود الفترة المحاسبية
        if (!$accountingPeriod) {
            return response()->json(['error' => 'الفترة المحاسبية غير موجودة'], 404);
        }
    
        // التخزين المؤقت (Caching) لمدة 60 دقيقة
        $mainAccountsTotals = Cache::remember('main_accounts_totals_'.$year.'_'.$month, 60, function() {//-
            // استرجاع الحسابات الرئيسية مع حساباتها الفرعية وتجميع الأرصدة
                 return MainAccount::with(['subAccounts' => function($query) {
                    $query->select(
                        'sub_accounts.sub_account_id',
                        'sub_accounts.Main_id',
                        'sub_accounts.sub_name',
                        'sub_accounts.debtor_amount',
                        'sub_accounts.creditor_amount'
                    )
                    ->leftJoin('daily_entries AS debit', 'sub_accounts.sub_account_id', '=', 'debit.account_debit_id')
                    ->leftJoin('daily_entries AS credit', 'sub_accounts.sub_account_id', '=', 'credit.account_Credit_id')
                    ->selectRaw('
                        sub_accounts.sub_account_id,
                        sub_accounts.Main_id,
                        sub_accounts.sub_name,
                        sub_accounts.debtor_amount,
                        sub_accounts.creditor_amount,
                        SUM(IFNULL(sub_accounts.debtor_amount, 0) + IFNULL(debit.Amount_debit, 0)) as total_debit,
                        SUM(IFNULL(sub_accounts.creditor_amount, 0) + IFNULL(credit.Amount_credit, 0)) as total_credit
                    ')
                    ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.Main_id', 'sub_accounts.sub_name', 'sub_accounts.debtor_amount', 'sub_accounts.creditor_amount','sub_accounts.AccountClass', 'sub_accounts.typeAccount',);
                }])->get();
        
        });
    
        // تقسيم البيانات إلى دفعات صغيرة باستخدام chunk لتحسين الأداء
        MainAccount::chunk(100, function($mainAccounts) use (&$mainAccountsTotals) {
            foreach ($mainAccounts as $mainAccount) {
                // معالجة البيانات داخل كل جزء
                // يمكن تعديل هذه العملية لاحتياجاتك الخاصة
            }
        });
    
        // تمرير البيانات إلى العرض
        return view('accounts.review-budget', [
            'mainAccountsTotals' => $mainAccountsTotals,
            'accountingPeriod' => $accountingPeriod, 
        ]);
    }
    
    
    
    
    
    
    }
