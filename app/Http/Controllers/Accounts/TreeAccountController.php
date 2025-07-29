<?php

namespace App\Http\Controllers\accounts;

use App\Enum\AccountType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Contracts\View\View;

class TreeAccountController extends Controller
{
    /**
     * Get main accounts by their type (e.g., Current Assets, Fixed Assets, etc.)
     */
    public function getAccountsByType($type)
    {
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        // التحقق من صحة نوع الحساب
        $accountType = AccountType::tryFrom((int)$type);
      $accountType=  $accountType->value;
        
        if (!$accountType) {
            return response()->json(['error' => 'نوع الحساب غير صالح'], 400);
        }

        // جلب الحسابات الرئيسية مع حساب الأرصدة

        $mainAccounts = MainAccount::where('typeAccount', $accountType)
            ->select('main_accounts.*')
            ->selectRaw('COALESCE((
                SELECT SUM(debtor_amount) 
                FROM sub_accounts 
                WHERE sub_accounts.main_id = main_accounts.main_account_id
            ), 0) as debit_balance')
            ->selectRaw('COALESCE((
                SELECT SUM(creditor_amount) 
                FROM sub_accounts 
                WHERE sub_accounts.main_id = main_accounts.main_account_id
            ), 0) as credit_balance')
            ->get();

//              $mainAccounts = DB::table('main_accounts')
//         ->leftJoin('sub_accounts', function($join) use ($accountType) {
//             $join->on( 'sub_accounts.main_id' , '=', 'main_accounts.main_account_id')
//                 ->where('sub_accounts.type_account', $accountType)
//                     ;

//         })
//       ->leftJoin('daily_entries', function($join) use ($accountingPeriod) {
//     $join->on('daily_entries.account_debit_id', '=','sub_accounts.sub_account_id')
//         ->orOn('daily_entries.account_credit_id','=','sub_accounts.sub_account_id' )
//         ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
//     ; // إضافة شرط نوع الحركة هنا
// })

     
//         ->select([
//             'sub_accounts.sub_name',
//             'sub_accounts.sub_account_id',
//             'main_accounts.account_name',
//             'main_accounts.main_account_id',
//             DB::raw('COALESCE(SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.accounting_period_id = ?  THEN daily_entries.amount_debit  ELSE 0 END), 0) as debit_balance'),
//              function ($join) use ( $accountingPeriod) {
//             $join->on('sub_accounts.sub_account_id', '=', 'daily_entries.account_debit_id')
//                 ->addBinding([ $accountingPeriod->accounting_period_id]);
//         },
//             DB::raw('COALESCE(SUM(CASE WHEN daily_entries.account_credit_id = sub_accounts.sub_account_id  AND daily_entries.accounting_period_id = ? THEN daily_entries.amount_credit ELSE 0 END), 0) as credit_balance'),
//                function ($join) use ( $accountingPeriod) {
//             $join->on('sub_accounts.sub_account_id', '=', 'daily_entries.account_credit_id')
//                 ->addBinding([ $accountingPeriod->accounting_period_id]);
//         },
//                 ])

//         ->groupBy(
//                   'sub_accounts.sub_name',
//             'sub_accounts.sub_account_id',
//             'main_accounts.account_name',
//             'main_accounts.main_account_id',
//         )
//         ->orderBy('main_accounts.main_account_id')
//         ->get();
//         dd( $mainAccounts);

        return response()->json($mainAccounts);
    }

    /**
     * Get sub-accounts for a specific main account
     */
    public function getSubAccounts($mainAccountId)
    {
                   $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
$query = SubAccount::query()
    ->where('main_id', $mainAccountId)
    ->select([
        'sub_accounts.sub_name',
        'sub_accounts.sub_account_id',
        // الريال اليمني
        DB::raw("(SELECT IFNULL(SUM(amount_debit), 0) 
                FROM daily_entries 
                WHERE account_debit_id = sub_accounts.sub_account_id
                AND currency_name = 'ريال.يمني'
                AND accounting_period_id = {$accountingPeriod->accounting_period_id}) AS debtoramount"),
        DB::raw("(SELECT IFNULL(SUM(amount_credit), 0) 
                FROM daily_entries 
                WHERE account_credit_id = sub_accounts.sub_account_id
                AND currency_name = 'ريال.يمني'
                AND accounting_period_id = {$accountingPeriod->accounting_period_id}) AS creditoramount"),
        
        // الريال السعودي
        DB::raw("(SELECT IFNULL(SUM(amount_debit), 0) 
                FROM daily_entries 
                WHERE account_debit_id = sub_accounts.sub_account_id
                AND currency_name = 'ريال سعودي'
                AND accounting_period_id = {$accountingPeriod->accounting_period_id}) AS total_debits"),
        DB::raw("(SELECT IFNULL(SUM(amount_credit), 0) 
                FROM daily_entries 
                WHERE account_credit_id = sub_accounts.sub_account_id
                AND currency_name = 'ريال سعودي'
                AND accounting_period_id = {$accountingPeriod->accounting_period_id}) AS total_credits"),
        
        // الدولار الأمريكي
        DB::raw("(SELECT IFNULL(SUM(amount_debit), 0) 
                FROM daily_entries 
                WHERE account_debit_id = sub_accounts.sub_account_id
                AND currency_name = 'دولار امريكي'
                AND accounting_period_id = {$accountingPeriod->accounting_period_id}) AS total_debitd"),
        DB::raw("(SELECT IFNULL(SUM(amount_credit), 0) 
                FROM daily_entries 
                WHERE account_credit_id = sub_accounts.sub_account_id
                AND currency_name = 'دولار امريكي'
                AND accounting_period_id = {$accountingPeriod->accounting_period_id}) AS total_creditd")
    ])
    ->orderByRaw('(debtoramount - creditoramount) DESC');

$subAccounts = $query->get();

return response()->json($subAccounts);
}
// dd($subAccounts);
// dd($query->toSql(), $query->getBindings());
// $subAccounts = SubAccount::where('main_id', $mainAccountId)
//     ->select(
//         'sub_account_id',
//         'sub_name',
//         'debtoramount',
//         'creditoramount'
//     )
//     ->get();

    public function searchSubAccounts(Request $request)
    {
        $query = $request->input('query');
        
        $subAccounts = SubAccount::where('sub_account_id', '!=', null)
            ->where(function($q) use ($query) {
                $q->where('sub_name', 'LIKE', "%{$query}%")
                  ->orWhere('sub_account_id', 'LIKE', "%{$query}%");
            })
            ->get();

        return response()->json($subAccounts);
    }
}
