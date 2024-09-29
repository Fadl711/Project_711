<?php

namespace App\Http\Controllers\accounts;

use App\Enum\AccountType;
use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class TreeAccountController extends Controller
{
    // //
    // public function getMainAccounts($id)
    // {
    //     // جلب الحسابات الرئيسية بناءً على الحساب الرئيسي الكبير
    //     $mainAccounts = MainAccount::where('large_main_account_id', $id)->get();

    //     // إرجاع البيانات كـ JSON
    //     return response()->json($mainAccounts);
    // }

    public function getMainAccountsByLargeAccountType($largeAccountType)
    {
        // التحقق من أن الـ largeAccountType هو قيمة صحيحة من Enum
        $largeAccountTypeEnum = AccountType::tryFrom($largeAccountType);

        if (!$largeAccountTypeEnum) {
            return response()->json([], 404); // إذا لم يكن الحساب الكبير موجود
        }

        // افترض أن لديك الحقل large_account_type في جدول MainAccount
        $mainAccounts = MainAccount::where('typeAccount', $largeAccountTypeEnum->value)->get();

        return response()->json($mainAccounts);
    }
    // لجلب الحسابات الفرعية بناءً على الحساب الرئيسي
    public function getSubAccountsByMainAccount($mainAccountId)
    {
        $subAccounts = SubAccount::where('Main_id', $mainAccountId)->get();
        return response()->json($subAccounts);
    }



    public function getMainAccounts(AccountType $type){

        $AllAssetsMainAccount=MainAccount::where('typeAccount',$type->value)->get();
        dd($AllAssetsMainAccount);
       response()->json($AllAssetsMainAccount);;
    }
    // public function getSubAccounts($id){

    //     return view('accounts.tree_accounts.index');
    // }
}
