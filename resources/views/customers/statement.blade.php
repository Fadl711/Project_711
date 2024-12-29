<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> كشف حساب {{$Myanalysis}}</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    <style>
          body {
        font-family: Arial, sans-serif; /* الخط الافتراضي */
    }
    .english {
        font-family: 'Times New Roman', serif; /* الخط الإنجليزي */
    }
        /* تخصيص للطباعة */
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .print-container {
                @apply w-full max-w-full mx-auto p-2;
            }

            .no-print {
                display: none;
            }
        }

    table {
        table-layout: ; /* استخدم تخطيط ثابت */
        width: 100%;
    }

    th, td {
        border: 1px solid #000;
        /* padding: 8px; */
    }

   

    /* تحسين مظهر الجدول */
    .header-section, .totals-section {
        margin-top: 10px;
        border: 2px solid #000;
        border-radius: 8px;
    }
        
    </style>
</head>
<body class="bg-white">
    <div class=" print-container  ">
        <!-- العنوان -->
        @isset($buss)
        <div class="header bg-[#1749fd15]  rounded-lg">
            @include('includes.header2')

            

        </div>
    @endisset
    <div class="grid grid-cols-3 w-full text-center gap-2 text-sm font-semibold">
        <div></div>
        <div>
            <p class="font-semibold">
                كشف حساب {{ $Myanalysis }} - رصيد نهاية التقرير
            </p>

        </div>

        <div class="flex  w-full  text-sm font-bold text-gray-700">
            <div> من  :
                {{ $startDate }}
            </div>
            <div>{{ __('الى   ') }}:
                {{ $endDate }}
            </div>
        </div>
    </div>
        <header class="flex justify-between items-center border-b-2 border-gray-800 pb-1 mb-1">
            <div>
                <div class="flex">
                    <div class="flex mt-2 gap-5">
                        <div class="font-extrabold">{{ __('رقم ') }}  {{ $AccountClassName  ?? __('غير متوفر') }}:</div>
                        <div>{{ $customer->sub_account_id ?? $customer->main_account_id ?? __('غير متوفر') }}</div>
                        <div>{{ $customer->sub_name ??$customer->account_name?? __('غير متوفر') }}/{{ $customer->name_The_known ?? __(' ') }}</div>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex mt-2 ">
                    <div class="font-extrabold">{{ __('العملة') }} :</div>
                    <div>{{ $currencysettings ?? __('YR') }}</div>
                </div>
            </div>

        </header>
        <!-- جدول المنتجات -->
        <table class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
            <thead>
                @isset($entries)

                <tr class="bg-[#1749fd15]">
                    <th class=" text-center">التاريخ </th>
                    <th class=" text-center">نوع المستند</th>
                    <th class=" text-center">رقم المستند</th>
                    <th class=" text-center">البيان</th>
                    <th class=" text-center">رقم المرجع</th>
                    <th class=" text-right"> المدين</th>
                    <th class=" text-right"> الدائن</th>
                </tr>
                @endisset
                @isset($entriesTotally)
                <tr class="bg-[#1749fd15]">
                    <th class=" text-center">البيان</th>
                </tr>
                @endisset

            </thead>
            <tbody>
                @isset($entriesTotally)
                <td class=" text-center"><div class="flex mt-2 gap-5">
                    <div class="font-extrabold">{{ __('كشف حساب كلي للمبالغ المدينة والمبالغ الدائنة ') }}  {{  __('لحساب ').__(' '). $AccountClassName ??''}}:
                        {{ $customer->sub_name ??$customer->account_name?? __('غير متوفر') }}/{{ $customer->name_The_known ?? __(' ') }}
                    </div>
                </div>
            </td>

                @endisset

                @isset($entries)
                    @foreach ($entries as $entrie)
                        <tr class="bg-white">
                            @php
                        if ($entrie->Invoice_type==2) {
                            $Invoice_type  = "آجل"   ;

                                         }
                                         if ($entrie->Invoice_type==1) {
                            $Invoice_type  = "نقدآ"   ;

                                         }
                                         if ($entrie->Invoice_type==3) {
                            $Invoice_type  = "تحويل بنكي"   ;

                                         }
                                         if ($entrie->Invoice_type==4) {
                            $Invoice_type  = "شيك"   ;

                                         }
                                         if ($entrie->daily_entries_type == "رصيد افتتاحي") {
        $Invoice_type = "";
        $cellColor = ($entrie->total_debit > 0) ? 'color: red;' : ''; // إذا كانت القيمة أكبر من 0 اجعل اللون أحمر
    } else {
        $cellColor = ''; // استخدم اللون الافتراضي إذا لم يكن "رصيد افتتاحي"
    }

                        @endphp
                            <td class=" text-right font-sans "style="width: 120px; " >
                                {{ $entrie->created_at ? $entrie->created_at->format('Y-m-d') : __('غير متوفر') }}
                            </td>
                            <td class=" text-right " style="width: 130px; {{ $cellColor }} " >{{ $entrie->daily_entries_type }} {{ $Invoice_type  ?? ""}}</td>
                            <td class=" text-center " style="width: 100px; ">{{ $entrie->Invoice_id ?? ''}}</td>
                            <td class=" text-right "    style="width: 300px; {{ $cellColor }}">{{ $entrie->Statement }}</td>
                            <td class=" text-center " style="width: 90px; ">{{ $entrie->entrie_id }}</td>
                            <td class="text-right px-1" style="width: 120px; {{ $cellColor }}">
                                {{ number_format($entrie->total_debit ?? 0) }}
                            </td>                            <td class="text-right px-1"  style="width: 120px; " >{{ number_format( $entrie->total_credit ?? 0) }}</td>
                        </tr>
                    @endforeach
                @endisset
                <tr class="bg-blue-100">
                        <td colspan="5" class=" text-right">اجمالي الرصيد</th>

                    <td class=" text-right ">
                        <p class="text-sm ">عليكم/الإجمالي  </p>
                    </td>
                    <td class=" text-right ">
                        <p class="">لكم/الإجمالي  </p>
                   </td>
                </tr>
                 <tr class="bg-blue-100">
                    <td colspan="5" class=" text-right"> </th>
                    <td class=" text-right ">
                        {{ number_format($SumDebtor_amount) ?? 0 }}
                    </td>
                    <td class=" text-right ">
                        {{ number_format($SumCredit_amount) ?? 0 }}

                    </td>
                </tr>
            </tbody>
        </table>
        <table class="w-[60%] text-sm ">
            <thead>
                <tr class="bg-blue-100">
                    <th class="px-2 text-right ">
                        @php
                        $sum=$SumDebtor_amount-$SumCredit_amount;
                        if ($sum>=0) {
                            $commintString  = "عليكم/ رصيد"   ;

                                         }
                                         if ($sum<0) {
                                            $commintString  = "لكم/ رصيد"   ;
                                         }
                        @endphp
                        <p class="">{{ $commintString }}</p>
                    </th>
                    <th class="px-2 text-right">
                        {{ number_format(abs($Sale_priceSum)) ?? 0 }}

                        <p class="text-sm">{{ $priceInWords }}</p>
                    </th>
                </tr>

            </thead>


</table>
        <!-- الإجماليات -->
        <div class="totals-section bg-blue-100 p-4">
            <div class="flex justify-between">
                <div>

                        <div class="text-sm">{{ __('  مصادقة الحساب  من  ') }}  {{ $AccountClassName ?? __('غير متوفر') }}: {{  $customer->sub_name ??$customer->account_name?? __('غير متوفر') }}</div>

                        <div>
                            <p class="text-sm" dir="ltr">................ التوقيع </p>

                        </div>
                    </div>

                    <div>
                        <p class="text-sm" dir="ltr">المسؤول : {{ $UserName ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- زر الطباعة -->
        <div class="mt-4 no-print">
            <button onclick="printAndClose()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">طباعة</button>

            <script>
                function printAndClose() {
                    window.print(); // أمر الطباعة
                    setTimeout(() => {
                        window.close(); // الإغلاق بعد بدء الطباعة
                    }, 500); // فترة الانتظار نصف ثانية فقط
                }
            </script>

            <button onclick="closeWindow()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">إلغاء الطباعة</button>

            <script>
                function closeWindow() {
                    if (window.history.length > 1) {
                        window.history.back(); // العودة للصفحة السابقة
                    } else {
                        window.close(); // الإغلاق إذا كانت الصفحة مفتوحة في نافذة جديدة
                    }
                }
            </script>
        </div>
    </div>
</body>
</html>
