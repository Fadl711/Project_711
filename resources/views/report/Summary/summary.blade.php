@extends('report.layout')
@section('report')

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-200 text-center">
                        <tr>
                            @can('write')
    <button>إضافة</button>
@endcan
                            <th class="py-2 px-4 border-b">رقم الحساب</th>
                            <th class="py-2 px-4 border-b">اسم الحساب</th>
                            <th class="py-2 px-4 border-b">مدين/عليه</th>
                            <th class="py-2 px-4 border-b">دائن/له</th>

                        </tr>
                    </thead>
                    <tbody >
                        @isset($SubAccounts)

                        @foreach ($MainAccounts as $MainAccount)

                        <tr>
                            <td class="py-2 px-4 border-b text-center">{{$MainAccount->main_account_id}}</td>
                            <td class="py-2 px-4 border-b text-center"> {{$MainAccount->account_name}} </td>
                            @foreach ($SubAccounts as $SubAccount)
                            @if ($SubAccount->where('Main_id',$MainAccount->main_account_id)->get())
                            <td class="py-2 px-4 border-b text-center">{{$SubAccount->where('Main_id',$MainAccount->main_account_id)->sum('debtor_amount')}}</td>
                            <td class="py-2 px-4 border-b text-center">{{$SubAccount->where('Main_id',$MainAccount->main_account_id)->sum('creditor_amount')}}</</td>
                            @break
                            @endif
                            @endforeach

                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>

            <script>
                $(document).ready(function () {
    // استهداف كل الخيارات
    $('input[name="search"]').on('change', function () {
        const selectedValue = $(this).val(); // الحصول على القيمة المحددة

        // إرسال الطلب باستخدام AJAX
        $.ajax({
            url: "{{route('report.summary')}}", // المسار إلى الـ Controller
            method: 'GET',
            data: {
                type: selectedValue,
                _token: $('meta[name="csrf-token"]').attr('content') // لإضافة CSRF Token
            },
            success: function (response) {
                // مسح المحتوى السابق
                $('#selected-result').empty();

                if (response.length > 0) {
                    response.forEach(balance => {
                        $('#selected-result').append(`
                            <p>الحساب: ${balance.name} - الرصيد: ${balance.balance}</p>
                        `);
                    });
                } else {
                    $('#selected-result').text('لا توجد أرصدة للعرض.');
                }
            },
            error: function (xhr) {
                console.error('حدث خطأ أثناء إرسال الطلب:', xhr.responseText);
                $('#selected-result').text('حدث خطأ، يرجى المحاولة لاحقًا.');
            }
        });
    });
});
            </script>
@endsection


