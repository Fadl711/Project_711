@extends('layout')
@section('conm')
<style>
    body {
        font-family: 'Tajawal', sans-serif;
    }
    .header-section {
        background-color: #f3f4f6;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }
    table th, table td {
        text-align: center;
    }
    .no-print button {
        transition: background-color 0.3s ease;
    }
    .no-print button:hover {
        transform: scale(1.05);
    }
    @media print {
    .no-print {
        display: none;
    }
    
}
body {
    
    font-family: Arial, sans-serif; /* الخط الافتراضي */
}

</style>
<x-nav-transfer-restriction/>

    @if(session('success'))
        <div id="success-message" class=" fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
<div id="successAlert" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">

  </div>
  <div id="successAlert1"  class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
  </div>


    <div class=" overflow-x-auto bg-white shadow-md sm:rounded-lg    w-full px-4 py-2   max-h-[80vh]">
        <table class="text-sm   font-semibold w-full overflow-y-auto max-h-[80vh] border-collapse">
                <thead class="bg-[#2430d3] text-white sticky top-0  uppercase dark:bg-gray-700 dark:text-gray-400">
                <tr class="">
                    <th  colspan="2" class=" text-right bg-white text-white  ">
                       </th>
                    <th colspan="2" class="rounded-s-lg border border-white text-right">بيانات حساب المدين (الأخذ)</th>
                    <th colspan="2" class=" border-whi border  text-right">بيانات حساب الدائن (المعطي)</th>
                    <th colspan="5" class=" text-right bg-white border-whi">
                        <div class="relative  border text-black border-whi rounded-lg   ">
                        <input id="search" name="search" type="text" class="rounded-md w-full text-right placeholder:text-right" placeholder="ابحث با اسم الحساب او رقم القيد  ">
                    </div></th>
                    <th class="border text-right text-white border-C rounded-e-lg ">
                        رقم الصفحة
{{$id}}
                       </th>

                </tr>
                <tr>
                    <th class="rounded-s-lg">رقم القيد</th>
                    <th class="">نوع القيد</th>
                    <th class=" ">من حساب/المدين</th>
                    <th class="">مدين</th>
                    <th class="">الى حساب/الدائن</th>
                    <th class="">دائن</th>
                    <th class=""> البيان</th>
                    <th class=""> العملة</th>
                    <th class="">تاريخ القيد</th>
                    <th class="">تاريخ التحديث</th>
                    <th class="">الحالة</th>
                    <th class="">المستخدم</th>
                    <th class=" rounded-e-lg ">عرض - تحرير</th>
                </tr>
            </thead>
            <tbody id="products-table">
                @forelse ($eail as $eai)
                @php

                $resultDebit1 = $suba->where('sub_account_id', $eai->account_debit_id)->first();
                $resultDebit = $resultDebit1
                    ? $mainc->where('main_account_id', $resultDebit1->main_id)->first()
                    : 'تم الحذف';

                $resultCredit1 = $suba->where('sub_account_id', $eai->account_credit_id)->first();
                $resultCredit = $resultCredit1
                    ? $mainc->where('main_account_id', $resultCredit1->main_id)->first()
                    : 'تم الحذف';
            @endphp
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200"
         id="row-{{$eai->entrie_id }}">
                <td class="border text-right">{{ $eai->entrie_id }}</td>
                        <td class="border text-right">{{ $eai->daily_entries_type }}</td>
                        <td class=" text-right bg-gray-300 border">
                            {{ $resultDebit1->sub_name ?? ''}}
                        </td>                    
                              <td class="border text-right bg-gray-300 ">{{ $eai->amount_debit }} </td>
                        <td class=" text-right  bg-red-400 border ">
                            {{ $resultCredit1->sub_name ?? '' }}
                        </td>                        <td class="border text-right bg-red-400">{{ $eai->amount_credit }} </td>
                        <td class="border text-right ">{{ $eai->statement }}/رقم المستند<span class=" bg-red-400 px-1 rounded-md">{{ $eai->invoice_id }}</span></td>
                    </td>                        <td class="border text-right bg-red-400">{{ $eai->currency_name }} </td>
                        <td class="border text-right">{{ $eai->created_at->format('Y-m-d') }}</td>
                        <td class="border text-right">{{ $eai->updated_at }}</td>
                        <td class="border text-right">
                            @if ($eai->status == 'مرحل')
                                   <span class="text-success">مرحل</span>
                               @else
                                   <span class="text-danger">غير مرحل</span>
                           @endif
                        </td>

                        <td class="border text-right">{{ $eai->user->name }}</td>
                        <td class="border text-right flex">
                            <a href="{{ route('restrictions.show', $eai->entrie_id) }}" class="text-sm py-2 leading-none rounded-md hover:bg-gray-100">
                                <svg class="w-6 h-6 text-[#2430d3]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                    <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </a>
                            <a href="{{route('restrictions.edit',$eai->entrie_id)}}" class="text-sm py-2 px-2 leading-none rounded-md hover:bg-gray-100">
                                <svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="#" class="text-sm py-2 px-2 leading-none rounded-md hover:bg-gray-100 mt-[10px]    group transition-all duration-500  flex item-center delete-payment" data-id="{{$eai->entrie_id}}" >
                                <svg class="" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path class="fill-red-600" d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z" fill="#F87171"></path>
                                     </svg>
                                </a>
                    </td>
            </tr>
            @empty

            @endforelse
        </tbody>
    </table>
    {{$eail->links() }}
</div>

<script >
$(document).ready(function() {
    $(document).on('click', '.delete-payment', function (e) {
        e.preventDefault();

        var successMessage = $('#successAlert'); // الرسالة الناجحة
        var errorMessage = $('#successAlert1'); // الرسالة الخطأ
        // const url = "{{ url('daily_restrictions')}}"; // استخدم مُتغير
        let paymentId = $(this).data('id');
        console.log('Payment ID:', paymentId); // تحقق من قيمة paymentId

        // const url = act.replace(':id', paymentId);

        if (confirm('هل أنت متأكد أنك تريد حذف هذا القيد')) {
            $.ajax({
                url: "{{ url('daily_restrictions/')}}/" + paymentId,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
    console.log(response); // تحقق من استجابة الخادم
    if (response.success) {
        var rowSelector = '#row' + paymentId;
        $(rowSelector).hide();
        $(rowSelector).css('display', 'none');
        $(rowSelector).slideUp();
        $('#row-' + paymentId).fadeOut(); // إخفاء الصف

        $(rowSelector).css('display', 'none');
        $(rowSelector).fadeOut(function() {
            console.log('Row faded out successfully'); // تحقق من نجاح الإخفاء
        });

        successMessage.text(response.message || 'تم الحذف بنجاح.').show();
        setTimeout(() => successMessage.hide(), 3000);
    } else {
        errorMessage.text(response.message || 'حدث خطأ أثناء الحذف.').show();
        setTimeout(() => errorMessage.hide(), 3000);
    }
},
                error: function (xhr) {
                    errorMessage.show().text(xhr.responseJSON.message || 'حدث خطأ أثناء الاتصال بالخادم.');
                    setTimeout(() => {
                        errorMessage.hide();
                    }, 5000);
                }
            });
        }
    });
});
    $(document).ready(function() {

        $('#search').on('keyup', function() {
            var searchValue = $(this).val();
            var inputValue = $('#Daily_page_id').val();
            if (searchValue !== '') {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('search.daily_restrictions') }}',
                    data: {search: searchValue,Value: inputValue},
                    success: function(data) {
                        $('#products-table').html(data);
                    }
                });
            }else {
                // إذا كان الحقل فارغًا، أرجع جميع المنتجات
                $.ajax({
                    type: 'GET',
                    url: '{{ route('search.daily_restrictions') }}',
                    data: {search: '',Value: inputValue},
                    success: function(data) {
                        $('#products-table').html(data);
                    }
                });
            }
        });
    });

//رسالة اختفاء تعديل قيد افتتاحي
setTimeout(function() {
    document.getElementById('success-message').style.display = 'none';
}, 5000);  // الرسالة ستختفي بعد 5 ثواني (5000 ميلي ثانية)


</script>
@endsection
