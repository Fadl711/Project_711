<?php

namespace App\Http\Controllers\DailyRestrictionController;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class RestrictionController extends Controller
{
    //

    // تحقق من صحة البيانات
    public function store(Request $request)
    {

        // $mainAccount=MainAccount::all();
        // dd($mainAccount);


        // التحقق من صحة البيانات المدخلة
        $validated = $request->validate([
            'sub_account_debit_id' => 'required|integer',
            //'sub_account_debit_id' => 'required|integer',
            'Amount_debit' => 'required|numeric',
            'account_Credit_id' => 'required|integer',
            'sub_account_Credit_id' => 'required|integer',
            //'Amount_debit' => 'required|numeric', // تأكد من تطابق المبلغين
            'Statement' => 'required|string',
            'Currency_name' => 'required|string', // تأكد من استخدام الاسم الصحيح هنا
            'User_id' => 'required|integer', // تأكد من إضافة User_id إذا كان مطلوباً
        ]);
        // التأكد من عدم اختيار حسابين فرعيين متماثلين
        if ($request->sub_account_debit_id === $request->sub_account_Credit_id) {
            return response()->json(['success' => 'يجب عدم تساوي الحسابات الفرعية المدين والدائن.']);
        }

        // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();

        // إذا لم توجد صفحة، قم بإنشائها
        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([]);
        }


        // حفظ القيد اليومي
        $dailyEntrie = new DailyEntrie();
    $dailyEntrie->account_debit_id = $validated['sub_account_debit_id'];
    $dailyEntrie->Amount_debit = $validated['Amount_debit'];
    $dailyEntrie->account_Credit_id = $validated['sub_account_Credit_id'];
    $dailyEntrie->Amount_Credit = $validated['Amount_debit'];
    $dailyEntrie->Statement = $validated['Statement'];
    $dailyEntrie->Currency_name = $validated['Currency_name']; // استخدم الاسم الصحيح هنا
    $dailyEntrie->Daily_page_id = $dailyPage->page_id; // حفظ معرف الصفحة اليومية
    $dailyEntrie->User_id = $validated['User_id']; // تأكد من استخدام المتغيرات المصرح بها

    $dailyEntrie->save();

        return response()->json(['success' => 'تم حفظ القيد بنجاح'])
        ;
    }
public function stor(Request $request){
    $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
    $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة

    if ($dailyPage) {

            $generalJournal1=GeneralJournal::all();
            $mainAccount=MainAccount::all();
        $curr=Currency::all();
        return view('daily_restrictions.create',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount]);

    } else {
        $Statement=$request->Statement;
             GeneralJournal::create([
            ]);
            // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $today = Carbon::now()->toDateString();
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
            if ($dailyPage) {
                // إذا تم العثور على الصفحة، عرض رقم الصفحة
                $generalJournal1=GeneralJournal::all();
                $mainAccount=MainAccount::all();
            // dd($generalJournal1);
            $curr=Currency::all();
            return view('daily_restrictions.create',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount]);
            }
    }

}

    public function create(){
        $mainAccount=MainAccount::all();
        $curr=Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD

        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة

        //}
        return view('daily_restrictions.create',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount,$dailyPage]);

    }
    public function index(){
        return view('daily_restrictions.index');
    }
    public function   all_restrictions_show(){
        $eail=DailyEntrie::all();
        $mainc=MainAccount::all();
        $suba=SubAccount::all();
        return view('daily_restrictions.all_restrictions_show',['eail'=>$eail,'mainc'=>$mainc,'suba'=>$suba]);
    }

    public function edit($id){
        $eail=DailyEntrie::where('entrie_id',$id)->first();

        $mainAccount=MainAccount::all();
        $curr=Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD

        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة

        return view('daily_restrictions.edit',compact('curr','dailyPage','eail'),['mainAccounts'=> $mainAccount,$dailyPage,]);
    }
    public function update(Request $request,$id){
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();
        $validated = $request->validate([
            'sub_account_debit_id' => 'required|integer',
            //'sub_account_debit_id' => 'required|integer',
            'Amount_debit' => 'required|numeric',
            'account_Credit_id' => 'required|integer',
            'sub_account_Credit_id' => 'required|integer',
            //'Amount_debit' => 'required|numeric', // تأكد من تطابق المبلغين
            'Statement' => 'required|string',
            'Currency_name' => 'required|string', // تأكد من استخدام الاسم الصحيح هنا
            'User_id' => 'required|integer', // تأكد من إضافة User_id إذا كان مطلوباً
        ]);

        $dailyEntrie =DailyEntrie::where('entrie_id',$id)->update([
            'account_debit_id'=>$validated['sub_account_debit_id'],
            'Amount_debit'=>$validated['Amount_debit'],
            'account_Credit_id'=>$validated['sub_account_Credit_id'],
            'Amount_Credit'=>$validated['Amount_debit'],
            'Statement'=> $validated['Statement'],
            'Currency_name'=>$validated['Currency_name'],
            'Daily_page_id'=>$dailyPage->page_id,
            'User_id'=>$validated['User_id'],
        ]);

        return redirect()->route('all_restrictions_show');
    }
    public function  destroy($id){
        DailyEntrie::where('entrie_id',$id)->delete();
        return redirect()->back();
    }
    public function show($id){

        $mainc=MainAccount::all();
        $suba=SubAccount::all();
        $dailyEntrie =DailyEntrie::where('entrie_id',$id)->first();
        return view('daily_restrictions.show',['daily'=>$dailyEntrie,'mainc'=>$mainc,'suba'=>$suba]);
    }
    public function print($id){

        $mainc=MainAccount::all();
        $suba=SubAccount::all();
        $dailyEntrie =DailyEntrie::where('entrie_id',$id)->first();
        return view('daily_restrictions.print',['daily'=>$dailyEntrie,'mainc'=>$mainc,'suba'=>$suba]);
    }

}
