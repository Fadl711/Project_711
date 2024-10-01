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
   



    public function getMainAccounts(AccountType $type){

        $AllAssetsMainAccount=MainAccount::where('typeAccount',$type->value)->get();
        dd($AllAssetsMainAccount);
       response()->json($AllAssetsMainAccount);;
    }
   
    public function searchSubAccounts(Request $request)
    {
        // ->withSum(['daily_entries as total_debit' => function ($query) {
        //     $query->whereYear('created_at', 2024);
        // }], 'Amount_debit') // جمع المبالغ المدينة لعام 2024
        // ->withSum(['daily_entries as total_credit' => function ($query) {
        //     $query->whereYear('created_at', 2024);
        // }], 'Amount_Credit') // جمع المبالغ الدائنة لعام 2024
       
        $query = $request->input('query');

        // استعلام لجلب الحسابات الفرعية مع جمع المبالغ المدينة والدائنة من جدول القيود اليومية لعام 2024
        $subAccounts = SubAccount::where('sub_account_id', '!=', null)
                                 ->where('sub_name', 'LIKE', "%{$query}%")
                               ->get();

        // إرجاع النتيجة كاستجابة JSON
                    //  if ($subAccounts->isEmpty()) {
                    //         return response()->json(['message' => 'No sub-accounts found'], 404);
                    //     }
   // إرجاع النتيجة كاستجابة JSON
   return response()->json($subAccounts);
                                                 
                                                  
}
}
