<?php

namespace App\Http\Controllers\DailyRestrictionController;

use App\Http\Controllers\Controller;
use App\Models\DailyEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictionController extends Controller
{
    //
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'account_debit_id' => 'required|exists:accounts,id',
            'sub_account_debit_id' => 'nullable|exists:accounts,id',
            'Amount_debit' => 'required|numeric|min:0',
            'account_Credit_id' => 'required|exists:accounts,id',
            'sub_account_Credit_id' => 'nullable|exists:accounts,id',
            'Amount_Credit' => 'required|numeric|min:0',
            'Statement' => 'required|string',
            'Currency_id' => 'required|exists:currencies,id',
            'User_id' => 'required|exists:users,id',
        ]);

        // التأكد من تساوي المبلغ المدين والمبلغ الدائن
        if ($validatedData['Amount_debit'] != $validatedData['Amount_Credit']) {
            return response()->json(['error' => 'المبلغ المدين يجب أن يساوي المبلغ الدائن'], 400);
        }

        // التأكد من وجود صفحة بتاريخ اليوم
        $today = date('Y-m-d'); // الحصول على تاريخ اليوم
        $dailyPage = GeneralJournal::whereDate('created_at', $today)
            ->where('User_id', Auth::user()->id) // يمكنك تعديل هذا الشرط حسب الحاجة
            ->first();

        if (!$dailyPage) {
            // إنشاء صفحة جديدة إذا لم توجد صفحة بتاريخ اليوم
            $dailyPage = new GeneralJournal();
            $dailyPage->User_id = Auth::user()->id; // حفظ معرف المستخدم الحالي
            $dailyPage->created_at = now(); // تعيين تاريخ الإنشاء
            $dailyPage->save(); // حفظ الصفحة الجديدة
        }

        // حفظ القيد اليومي
        $restriction = new DailyEntrie();
        $restriction->account_debit_id = $validatedData['account_debit_id'];
        $restriction->sub_account_debit_id = $validatedData['sub_account_debit_id'];
        $restriction->Amount_debit = $validatedData['Amount_debit'];
        $restriction->account_Credit_id = $validatedData['account_Credit_id'];
        $restriction->sub_account_Credit_id = $validatedData['sub_account_Credit_id'];
        $restriction->Amount_Credit = $validatedData['Amount_Credit'];
        $restriction->Statement = $validatedData['Statement'];
        $restriction->Currency_id = $validatedData['Currency_id'];
        $restriction->Daily_page_id = $dailyPage->id; // حفظ معرف الصفحة اليومية
        $restriction->User_id = Auth::user()->id; // حفظ معرف المستخدم الحالي
        $restriction->save();

        // الرد بنجاح العملية
        return response()->json(['success' => 'تم حفظ القيد بنجاح']);
    }
    public function create(){
        $mainAccount=MainAccount::all();
        return view('daily_restrictions.create',['mainAccounts'=> $mainAccount]);

    }
    public function index(){
        return view('daily_restrictions.index');
    }
    public function   all_restrictions_show(){
        return view('daily_restrictions.all_restrictions_show');
    }

    public function   edit(){
        return view('daily_restrictions.edit');
    }
    public function   show(){
        return view('daily_restrictions.show');
    }
    
}
