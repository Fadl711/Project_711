@extends('layout')
@section('conm')
{{-- <form action="{{route(locks_financial_period.index)}}" method="GET" ></form>
<div id="reportContainer" class="mt-6 ">
    <div class="mb-6">
        <h2 class="text-lg font-semibold">الإيرادات والمصروفات</h2>
        <table class="min-w-full border bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">البند</th>
                    <th class="py-3 px-6 text-right">المبلغ</th>
                </tr>
            </thead>
            <tbody id="revenueExpenses">
                <!-- البيانات سيتم تحميلها عبر AJAX -->
            </tbody>
        </table>
    </div> --}}
    <div id="successAlert" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
        {{-- <p class="font-bold">تم بنجاح!</p>
        <p>تمت إضافة المنتج بنجاح.</p> --}}
      </div>
      <div id="successAlert1"  class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
      </div>
    <div class="gap-2 grid grid-cols-4 bg-white p-1 rounded-lg shadow-md mb-2">
        <form id="LocksFinancialPeriod" >
            @csrf
            <div>
                <label for="accountingPeriod" class="labelSale"> السنة</label>
                <select name="accountingPeriod" id="accountingPeriod" class="input-field select2 inputSale" required>
                    @isset($accountingPeriodOpen)
                    <option  @isset($accountingPeriodOpen)
                    value="{{$accountingPeriodOpen['accounting_period_id']}}" 
                    @endisset > {{$accountingPeriodOpen['created_at']->format('Y-m-d') }}</option>
                    @endisset 
                </select>
            </div>
        </form>
    </div>
<div class="container mx-auto my-8">
    <h1 class="text-2xl font-bold mb-4">تقرير الأرباح والخسائر</h1>
    <button id="loadReport" type="button" name="loadReport" class="bg-blue-500 text-white py-2 px-4 rounded"> إقفال السنة</button>
    <div id="reportContainer" class="mt-6 ">
        <div class="mb-6">
            <h2 class="text-lg font-semibold">الإيرادات والمصروفات</h2>
            <table class="min-w-full border bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">البند</th>
                        <th class="py-3 px-6 text-right">المبلغ</th>
                    </tr>
                </thead>
                <tbody id="revenueExpenses">
                    <tr class="border-b">
                        <td class="py-3 px-6">إجمالي الإيرادات</td>
                        <td class="py-3 px-6 text-right">{{$totalRevenue}} ريال</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-3 px-6">إجمالي المصروفات</td>
                        <td class="py-3 px-6 text-right">{{$totalExpenses}} ريال</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="py-3 px-6">صافي الربح/الخسارة</td>
                        <td class="py-3 px-6 text-right">{{$profit}} ريال</td>
                    </tr>                </tbody>
            </table>
        </div>

      
        
       
    </div>
    <div id="successMessage" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
        <p class="font-bold">تم بنجاح!</p>
      </div>
    <div id="errorMessage" style="display: none;" class="alert alert-danger"></div>
    <div id="successMessage" style="display: none;" class="alert alert-success"></div>
</div>
<script>
    $(document).ready(function() {
        const form = $('#LocksFinancialPeriod');
    
        // منع الحفظ عند الضغط على زر Enter
        form.on('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    
        $('#loadReport').on('click', function() {
            const submitButton = $(this);
            // $('#loadReport').val('جاري الحفظ').prop('disabled', true);
            submitButton.prop('disabled', true).text('جاري الإرسال...');

    
            let mainAccountId = $('#accountingPeriod').val();
    
            // تحقق من وجود معرف الحساب
            if (!mainAccountId) {
                alert('تم إلغاء العملية. لم يتم حفظ التعديلات.');
                submitButton.prop('disabled', false);
                return; // إنهاء العملية إذا لم يكن هناك معرف
            }
    
            $.ajax({
                url: `{{ url('Locks_financial_period/') }}/${mainAccountId}/getProfitAndLossData`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // التعامل مع الرسائل الناجحة أو الفاشلة
                
                   
    
                    // إعادة تفعيل الزر بعد الانتهاء من الطلب
                    submitButton.prop('disabled', false).text('إقفال السنة');
    
                        
                        $('#successAlert').text(data.message).removeClass('hidden');
                    
                    // إخفاء التنبيه بعد 8 ثوانٍ
                    setTimeout(function() {
                        $('#successAlert').addClass('hidden');
                    }, 8000);
                   
                    
                },
                error: function(xhr, status, error) {
                    
                    $('#successAlert1').text(data.message).removeClass('hidden');
                        $('#successAlert1').addClass('hidden');
                        setTimeout(function() {
                        $('#successAlert').addClass('hidden');
                    }, 8000);
                    console.error('Error fetching data:', error);
                    submitButton.prop('disabled', false).text('إقفال السنة'); // إعادة النص إلى "إقفال السنة" عند الخطأ
                }
            });
        });
    });
    </script>
@endsection
