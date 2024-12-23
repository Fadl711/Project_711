<?php

namespace App\Http\Controllers;

use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\GeneralEntrie;
use App\Models\GeneralLedge;
use App\Models\GeneralLedgeMain;
use App\Models\GeneralLedgePage;
use App\Models\MainAccount;
use App\Models\SubAccount;
use App\View\Components\GuestLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountCoctroller extends Controller
{
    public function create(){
        
        $MainAccounts=MainAccount::all();
     

 return view(['MainAccounts'=> $MainAccounts]);
         }

    public function index(){
        $data=[
            ['idsec'=>'10','id'=>'1','sec'=>'العملاء','name'=>'جمال','pric'=>'$102'],
            ['idsec'=>'1','id'=>'8','sec'=>'البنك','name'=>'','pric'=>'$102'],
            ['idsec'=>'2','id'=>'2','sec'=>'الصندوق','name'=>'','pric'=>'$10225'],
            ['idsec'=>'4','id'=>'3','sec'=>'المبيعات','name'=>'','pric'=>'$102248'],
            ['idsec'=>'10','id'=>'4','sec'=>'العملاء','name'=>'سعيد','pric'=>'$10255'],
            ['idsec'=>'2','id'=>'5','sec'=>'الصندوق','name'=>'','pric'=>'$10255'],
            ['idsec'=>'1','id'=>'6','sec'=>'البنك','name'=>'','pric'=>'$102'],
            ['idsec'=>'10','id'=>'7','sec'=>'العملاء','name'=>'علي','pric'=>'$1155'],
            ['idsec'=>'10','id'=>'7','sec'=>'العملاء','name'=>'علي','pric'=>'$1155'],



            ];
            $post=MainAccount::all();


      return  view('accounts.index',['posts'=>$data,'post'=> $post]);
    }
    public function getOptions( ){
        $options=[
            ['idsec'=>'10','id'=>'1','sec'=>'العملاء','name'=>'جمال','pric'=>'$102'],
            ['idsec'=>'1','id'=>'8','sec'=>'البنك','name'=>'','pric'=>'$102'],
            ['idsec'=>'2','id'=>'2','sec'=>'الصندوق','name'=>'','pric'=>'$10225'],
            ['idsec'=>'4','id'=>'3','sec'=>'المبيعات','name'=>'','pric'=>'$102248'],
            ['idsec'=>'10','id'=>'4','sec'=>'العملاء','name'=>'سعيد','pric'=>'$10255'],
            ['idsec'=>'2','id'=>'5','sec'=>'الصندوق','name'=>'','pric'=>'$10255'],
            ['idsec'=>'1','id'=>'6','sec'=>'البنك','name'=>'','pric'=>'$102'],
            ['idsec'=>'10','id'=>'7','sec'=>'العملاء','name'=>'علي','pric'=>'$1155'],
            ['idsec'=>'10','id'=>'7','sec'=>'العملاء','name'=>'علي','pric'=>'$1155'],

            ];
            $data=MainAccount::all();
return response()->json( $data);
    //   return view('accounts.account_balancing',['posts'=>$data]);
    }
    public function index_account_tree()
    {
        $MainAccounts=MainAccount::all();
        $SubAccount=SubAccount::all();
        
        $ASSETS=AccountType::FIXED_ASSETS;

        $LIABILITIES_OPPONENTS=AccountType::LIABILITIES_OPPONENTS;
        $EXPENSES=AccountType::EXPENSES;
        $REVENUE=AccountType::REVENUE;
        $Assets=MainAccount::where('typeAccount',$ASSETS)->get();
        $LIABILITIES_OPPONENTS=MainAccount::where('typeAccount',$LIABILITIES_OPPONENTS)->get();

        $TypesAccountName = [

            ['TypesAccountName' => Deportatton::FIXED_ASSETS, 'id' => AccountType::FIXED_ASSETS],
            ['TypesAccountName' => Deportatton::CURRENT_ASSETS, 'id' => AccountType::CURRENT_ASSETS],
            ['TypesAccountName' => Deportatton::LIABILITIES_OPPONENTS, 'id' => AccountType::LIABILITIES_OPPONENTS],
            ['TypesAccountName' => Deportatton::EXPENSES, 'id' => AccountType::EXPENSES],
            ['TypesAccountName' => Deportatton::REVENUE, 'id' => AccountType::REVENUE],
         ];

       
        return view('accounts.account_tree', ['Assets'=>$Assets,'TypesAccounts'=> $TypesAccountName,]);
    
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'entrie_id' => 'nullable|integer',
            'account_debit_id' => 'nullable|integer',
            'account_Credit_id' => 'nullable|integer',
            'allDailyEntrie' => 'nullable|integer',
         

        ]);
        
        
        try {
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();
            
            // تحقق من وجود entrie_id
            if ($request->entrie_id) {
                $entry = DailyEntrie::where('entrie_id', $request->entrie_id)
                    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->first();
    
                // تحقق مما إذا كان القيد موجودًا
                if (!$entry) {
                    return response()->json(['error' => 'لم يتم العثور على القيد.'], 404);
                }
            }
    
            // إذا تم اختيار "ترحيل جميع القيود"
            if ($request->the_way_of_deportation=="all") 
            {
                return $this->storeAllEntries();
            } elseif ($request->the_way_of_deportation == "optional") {
                return $this->storeOptionalEntries($request->main_account_id, $request->sub_account_id);
            } 
            // معالجة القيد المدين
            if ($request->account_debit_id) {
                if (GeneralEntrie::where([
                    'Daily_entry_id' => $request->entrie_id,
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'sub_id' => $request->account_debit_id,
                ])->exists()) {
                    throw new \Exception("تم ترحيل هذا القيد مسبقاً كـ .");
                }   
                $this->processEntry($request->account_debit_id, null, $entry->entrie_id);  
               }
            // معالجة القيد الدائن
            if ($request->account_Credit_id) {
                if (GeneralEntrie::where([
                    'Daily_entry_id' => $request->entrie_id,
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'sub_id' => $request->account_Credit_id,
                ])->exists()) {
                    throw new \Exception("تم ترحيل هذا القيد مسبقاً كـ .");
                }   
                $this->processEntry(null, $request->account_Credit_id, $entry->entrie_id);
            }
    
            return response()->json(['success' => 'تم ترحيل القيد بنجاح!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'لم يتم العثور على الفترة المحاسبية المفتوحة.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()], 500);
        }
    }
    
    private function processEntry($accountDebitId, $accountCreditId, $entryId)
    {
        $entry = DailyEntrie::findOrFail($entryId);
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();
        $descriptionText = '';
        $descriptionCommint = '';
        $accountCreditId ?? null;
        $$accountDebitId ?? null;
        // تحديد نوع القيد (مدين أو دائن) والتعليق
        if ($accountCreditId) {
            $accountId = $accountCreditId;

            $entries = DailyEntrie::where('accounting_period_id',$accountingPeriod->accounting_period_id)
            ->where('entrie_id', $entryId)
            ->where('account_Credit_id', $accountId)
            ->first();
            $amount= $entries->Amount_Credit ??0;
            $descriptionCommint = "الى ح/";
            $entryType = "credit";
        }  
    
        
        if ($accountDebitId) 
        {
            $accountId = $accountDebitId;
            $entries = DailyEntrie::where('accounting_period_id',$accountingPeriod->accounting_period_id)
            ->where('entrie_id', $entryId)
            ->where('account_debit_id', $accountId)
            ->first();
            $amount= $entries->Amount_debit??0;

            $descriptionCommint = "من ح/";
            $entryType = "debit";
        }
        // جلب الحسابات الفرعية والرئيسية
        $subAccount = SubAccount::where('sub_account_id',$accountId)->firstOrFail();
        $Main_id=$subAccount->Main_id;
        $mainAccount = MainAccount::where('main_account_id',$Main_id)->firstOrFail();
            // تحديد الوصف بناءً على تصنيف الحساب
        if ($subAccount->AccountClass == 1) {
            $descriptionText = "العميل";
        } elseif ($subAccount->AccountClass == 2) {
            $descriptionText = "المورد";
        }
        // إنشاء أو استرجاع السجل من GeneralLedgeMain ووضعه في General_ledger_page_number_id
        $generalLedgeMain = GeneralLedgeMain::firstOrCreate(
            [
                'Main_id' => $mainAccount->main_account_id,
                'accounting_id' => $accountingPeriod->accounting_period_id,
            ],
            [
                'User_id' => auth()->id(),
            ]
        );
        // إنشاء أو استرجاع السجل من GeneralLedge
        $generalLedge = GeneralLedge::where([
            'Account_id' => $accountId,
            'accounting_id' => $accountingPeriod->accounting_period_id,
            'Main_id' => $mainAccount->main_account_id,
        ])->first();
        $id = $generalLedge ? $generalLedge->general_ledge_id : GeneralLedge::firstOrCreate(
            [
                'Account_id' => $accountId,
                'accounting_id' => $accountingPeriod->accounting_period_id,
            ],
            [
                'Main_id' => $mainAccount->main_account_id,
                'User_id' => auth()->id(),
            ]
        )->id;
      
        if($amount!=0)
        {

        // إنشاء السجل في GeneralEntrie باستخدام المعرف الصحيح
        $generalEntry = GeneralEntrie::firstOrCreate([
            'Daily_entry_id' => $entry->entrie_id,
            'Daily_Page_id' => $entry->Daily_page_id,
            'accounting_period_id' => $accountingPeriod->accounting_period_id,
            'sub_id' => $subAccount->sub_account_id,
            'Main_id' => $mainAccount->main_account_id,
            'typeAccount' => $subAccount->typeAccount,
            'entry_type' => $entryType,
        ], [
            'amount' => $amount,
            'description' => $descriptionCommint . $descriptionText . " " . $subAccount->sub_name,
            'entry_date' => $entry->created_at,
            'status' =>'غير مرحل',
            'Invoice_type' => $entry->Invoice_type,
            'Invoice_id' => $entry->Invoice_id,
            'Currency_name' => $entry->Currency_name,
            'General_ledger_page_number_id' => $id,
            'User_id' => auth()->id(),
        ]);

        if (!$generalEntry) {
            throw new \Exception("حدث خطأ أثناء إنشاء السجل في GeneralEntrie.");
        }
        // تحديث حالة القيد اليومي بعد الترحيل
        if ($accountDebitId) {
            $entry->update(['status_debit' => 'مرحل']);
        }
        if ($accountCreditId) {
            $entry->update(['status' => 'مرحل']);
        }
    }
    }
    
    public function storeAllEntries()
    {
        try {
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();
            
                  $entries = DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->get();
            if ($entries->isEmpty()) {
                return response()->json(['error' => 'لا توجد قيود غير مرحل لترحيلها.'], 404);
            }
            foreach ($entries as $entry)
                 {
                   
                    if($entry->account_debit_id){

                        $this->processEntry($entry->account_debit_id, null, $entry->entrie_id);
                    }
                    if($entry->account_Credit_id){

                        $this->processEntry(null, $entry->account_Credit_id, $entry->entrie_id);
                    }

                 

                    

                  }
            return response()->json(['success' => 'تم ترحيل جميع القيود بنجاح!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء ترحيل القيود: ' . $e->getMessage()], 500);
        }
    }
    
    public function storeOptionalEntries($main_account, $subAccount)
{
    try {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();

        if ($subAccount == "all") {
            $mainAccount = MainAccount::with('subAccounts')->findOrFail($main_account);
            // الحصول على معرفات الحسابات الفرعية المرتبطة بالحساب الرئيسي فقط
            $subAccountIds = $mainAccount->subAccounts->pluck('sub_account_id');

            // جلب جميع القيود اليومية المرتبطة بالحسابات الفرعية في الفترة المحاسبية المفتوحة
            $entries = DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where(function ($query) use ($subAccountIds) {
                    $query->whereIn('account_debit_id', $subAccountIds)
                          ->orWhereIn('account_Credit_id', $subAccountIds);
                })
                ->get();
        } elseif ($subAccount >= 1) {
            $entries = DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where(function ($query) use ($subAccount) {
                    $query->where('account_debit_id', $subAccount)
                          ->orWhere('account_Credit_id', $subAccount);
                })
                ->get();

            // تعريف متغير فارغ لتجنب الخطأ عند عدم توفر $subAccountIds
            $subAccountIds = collect([$subAccount]);
        }

        if ($entries->isEmpty()) {
            return response()->json(['error' => 'لا توجد قيود غير مرحل لترحيلها.'], 404);
        }

        foreach ($entries as $entry) {
            // التحقق من توفر $subAccountIds لتجنب الخطأ
            $hasDebit = isset($subAccountIds) && $subAccountIds->contains($entry->account_debit_id);
            $hasCredit = isset($subAccountIds) && $subAccountIds->contains($entry->account_Credit_id);

            if ($hasDebit || $hasCredit) {
                // يتم ترحيل القيد لمرة واحدة بغض النظر عن موقع الحساب الفرعي
                $this->processEntry($hasDebit ? $entry->account_debit_id : null, $hasCredit ? $entry->account_Credit_id : null, $entry->entrie_id);
            }
        }
        return response()->json(['success' => 'تم ترحيل جميع القيود بنجاح!']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'لم يتم العثور على الفترة المحاسبية المفتوحة.'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'حدث خطأ أثناء ترحيل القيود: ' . $e->getMessage()], 500);
    }
}

    
    // private function updateOpeningBalances($subAccounts)
    // {
    //     $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();
    //     foreach ($subAccounts as $subAccount) {
    //         $entryTypeDebit = "debit";
    //         $entryTypeCredit = "credit";
    
    //         // جلب المبالغ المدين والدائن من GeneralEntrie للفترة المحاسبية الحالية
    //         $amountDebit = GeneralEntrie::where('sub_id', $subAccount->sub_account_id)
    //             ->where('entry_type', $entryTypeDebit)
    //             ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    //             ->sum('amount');
    
    //         $amountCredit = GeneralEntrie::where('sub_id', $subAccount->sub_account_id)
    //             ->where('entry_type', $entryTypeCredit)
    //             ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    //             ->sum('amount');
    
    //         // حساب الفرق وتحديث الرصيد الافتتاحي بناءً عليه
    //         $sub = $amountDebit - $amountCredit;
    
    //         if ($sub == 0) {
    //             $subAccount->update([
    //                 'creditor_amount' => 0,
    //                 'debtor_amount' => 0,
    //             ]);
    //         } elseif ($sub > 0) {
    //             $subAccount->update([
    //                 'debtor_amount' => $sub,
    //                 'creditor_amount' => 0,
    //             ]);
    //         } else {
    //             $subAccount->update([
    //                 'creditor_amount' => abs($sub),
    //                 'debtor_amount' => 0,
    //             ]);
    //         }
    //     }
    // }
    
    }
