<?php

namespace App\Http\Controllers\accounts;

use App\Enum\AccountType;
use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use DB;
use Illuminate\Contracts\View\View;

class TreeAccountController extends Controller
{
    /**
     * Get main accounts by their type (e.g., Current Assets, Fixed Assets, etc.)
     */
    public function getAccountsByType($type)
    {
        // التحقق من صحة نوع الحساب
        $accountType = AccountType::tryFrom((int)$type);
        
        if (!$accountType) {
            return response()->json(['error' => 'نوع الحساب غير صالح'], 400);
        }

        // جلب الحسابات الرئيسية مع حساب الأرصدة
        $mainAccounts = MainAccount::where('typeAccount', $accountType->value)
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

        return response()->json($mainAccounts);
    }

    /**
     * Get sub-accounts for a specific main account
     */
    public function getSubAccounts($mainAccountId)
    {
        $subAccounts = SubAccount::where('main_id', $mainAccountId)
            ->select(
                'sub_account_id',
                'sub_name',
                'debtor_amount',
                'creditor_amount'
            )
            ->get();

        return response()->json($subAccounts);
    }

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
