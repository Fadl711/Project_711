<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> كشف حساب {{$Myanalysis}}</title>

    
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery/dist/jquery.min.js') }}"></script>

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
    <div class=" print-container px-1 ">
        <!-- العنوان -->
        @isset($buss)
        <div id="header1" class="header bg-[#1749fd15]  rounded-lg ">
            <div id="header1" class="header-section border border-gray-300 rounded-lg shadow-md p-1">
                <div class=" mx-2 flex justify-between ">
                    <div class="text-right my-4" >
                        <h2 class="font-extrabold    ">{{ $buss->Company_Name }}</h2>
                        <p class="text-sm">{{ $buss->Services }}</p>
                        <p class="text-sm">العنوان: {{ $buss->Company_Address }}</p>
                        <p class="text-sm">التلفون: {{ $buss->Phone_Number }}</p>
                    </div>
                    <div class="flex justify-center ">
                        <img class="w-32 h-32 rounded-lg " src="{{ url($buss->Company_Logo ? 'images/' . $buss->Company_Logo : '') }}" alt="Logo">
                    </div>
                    <div class="text-left  english my-4" >
                        <h2 class="font-extrabold text-sm  ">{{ $buss->Company_NameE }}</h2>
                        <div class=" text-sm ">{{ $buss->ServicesE }}</div>
                        <div class="text-sm">Address: {{ $buss->Company_AddressE }}</div>
                        <div class="text-sm">Phone: {{ $buss->Phone_Number }}</div>
                    </div>
                </div>
            </div>


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
                    <div>
                        @if($SumDebtor_amount!=0||$SumCredit_amount!=0)
                        {{ $currencysettings ?? __('') }}
                        @endif
                        @if($SumDebtor_amountASR!=0||$SumCredit_amountASR!=0)
                        {{" - ". $currencysettingsASR ?? __('') }}
                        @endif
                        @if($SumDebtor_amountUSD!=0||$SumCredit_amountUSD!=0)
                        {{" - ". $currencysettingsUSD ?? __('') }}
                        @endif
                    </div>
                </div>
            </div>

        </header>
        <!-- جدول المنتجات -->
        <table id="header1" class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
            <thead id="header1"  class="">
                @isset($entries)

                <tr id="header1" class="bg-[#1749fd15]">
                    <th class=" text-center">التاريخ </th>
                    <th class=" text-center">نوع المستند</th>
                    <th class=" text-center">رقم المستند</th>
                    <th class=" text-center">البيان</th>
                    <th class=" text-center">رقم المرجع</th>
                    <th class=" text-right"> المدين</th>
                    <th class=" text-right"> الدائن</th>
                    <th class=" text-center">العملة</th>


                </tr>
                @endisset
                @isset($entriesTotally)
                <tr class="">
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
                        <tr class="">
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
                            <td class=" text-right  "style="width: 120px; " >
                                {{ $entrie->created_at ? $entrie->created_at->format('Y-m-d') : __('غير متوفر') }}
                            </td>
                            <td class=" text-right " style="width: 130px; {{ $cellColor }} " >{{ $entrie->daily_entries_type }} {{ $Invoice_type  ?? ""}}</td>
                            <td class=" text-center " style="width: 100px; ">{{ $entrie->Invoice_id ?? ''}}</td>
                            <td class=" text-right "    style="width: 300px; {{ $cellColor }}">{{ $entrie->Statement }}</td>
                            <td class=" text-center " style="width: 90px; ">{{ $entrie->entrie_id }}</td>
                            <td class="text-right px-1" style="width: 120px; {{ $cellColor }}">
                                {{ number_format($entrie->total_debit ?? 0) }}
                            </td>
                                               <td class="text-right px-1"  style="width: 120px; " >{{ number_format( $entrie->total_credit ?? 0) }}</td>

                                                <td class=" text-center " style="width: 100px; ">{{ $entrie->Currency_name ?? ''}}</td>
                        </tr>
                    @endforeach
                @endisset
                <tr id="header1" class="bg-blue-100">
                <td colspan="5" class=" text-center rounded-s-lg border-l-0 border-r-0 border-b-1">بيان الأرصدة </th>
                    <td class=" text-right ">
                        <p class="text-sm ">عليكم/الإجمالي  </p>
                    </td>
                    <td class=" text-right ">
                        <p class="">لكم/الإجمالي  </p>
                   </td>
                    <td class=" text-right rounded-e-lg border-0">
                        <p class="">نوع العملة  </p>
                   </td>
                </tr>
                @if($amount_YER)
                 <tr id="header1" class="bg-blue-100">
                    <th colspan="5" class=" text-center rounded-s-lg border-l-0 border-r-0 border-b-1 ">اجمالي الرصيد العملة اليمنية </th>
                    <td class=" text-right ">
                        {{ number_format($SumDebtor_amount) ?? 0 }}
                    </td>
                    <td class=" text-right ">
                        {{ number_format($SumCredit_amount) ?? 0 }}
                    </td>
                    <td class=" text-right rounded-e-lg border-0">
                        {{ $currencysettings }}
                    </td>
                </tr>
                @endif
                @if($amountASR)
                <tr id="header1" class="bg-blue-100">
                <th colspan="5" class=" text-center rounded-s-lg border-l-0 border-r-0 border-b-1  ">اجمالي الرصيد العملة السعودية</th>
                    <td class=" text-right  ">
                        {{ number_format($SumDebtor_amountASR) ?? 0 }}
                    </td>
                    <td class=" text-right ">
                        {{ number_format($SumCredit_amountASR) ?? 0 }}

                    </td>
                    <td class=" text-right rounded-e-lg border-0">
                        {{ $currencysettingsASR }}

                    </td>
                </tr>
                @endif
                @if($amountUSD)
                <tr id="header1" class="bg-blue-100 " >
                <th colspan="5" class=" text-center rounded-s-lg border-l-0 border-r-0 border-b-1 ">اجمالي الرصيد العملة الدولار  </th>
                    <td class=" text-right ">
                        {{ number_format($SumDebtor_amountUSD) ?? 0 }}
                    </td>
                    <td class=" text-right ">
                        {{ number_format($SumCredit_amountUSD) ?? 0 }}

                    </td>
                    <td class=" text-right border-r-0   rounded-e-lg border-0">
                        {{ $currencysettingsUSD }}

                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        <table id="header1" class="w-[60%] text-sm ">
            <thead class=" ">
            @php
                $sum=$SumDebtor_amount-$SumCredit_amount;
                @endphp
                @if($sum!=0)

                <tr id="header1" class="bg-blue-100 ">
                    <th class="px-2 text-right rounded-s-lg border-0 ">
                        @php
                        if ($sum>=0) {
                            $commintString  = "عليكم/ رصيد"   ;

                                         }
                                         if ($sum<0) {
                                            $commintString  = "لكم/ رصيد"   ;
                                         }
                        @endphp
                        <p class="">{{ $commintString }}</p>
                    </th>
                    <th class="px-2 text-right border-0 rounded-e-lg  ">
                        {{ number_format(abs($Sale_priceSum)) ?? 0 }}

                        <p class="text-sm">{{ $priceInWords }}</p>
                    </th>
                </tr>
                @endIf
            @php
                $sumASR=$SumDebtor_amountASR-$SumCredit_amountASR;
                @endphp
                @if($sumASR!=0)

                <tr id="header1" class="bg-blue-100">
                    <th class="px-2 text-right rounded-s-lg border-0 ">
                        @php
                        if ($sumASR>=0) {
                            $commintStringASR  = "عليكم/ رصيد"   ;

                                         }
                                         if ($sumASR<0) {
                                            $commintStringASR  = "لكم/ رصيد"   ;
                                         }
                        @endphp
                        <p class="">{{ $commintStringASR }}</p>
                    </th>
                    <th class="px-2 text-right border-0 rounded-e-lg  ">
                        {{ number_format(abs($amountASR)) ?? 0 }}
                        <p class="text-sm">{{ $priceInWordsASR }}</p>
                    </th>
                </tr>
                @endIf
            @php
                $sumUSD=$SumDebtor_amountUSD-$SumCredit_amountUSD   ;
                @endphp
                @if($sumUSD!=0)

                <tr id="header1" class="bg-blue-100 rounded-lg ">
                    <th class="px-2 text-right rounded-s-lg border-0 ">
                        @php
                        if ($sumUSD>=0) {
                            $commintStringUSD  = "عليكم/ رصيد"   ;
                                         }
                                         if ($sumUSD<0) {
                                            $commintStringUSD  = "لكم/ رصيد"   ;
                                         }
                        @endphp
                        <p class="">{{ $commintStringUSD }}</p>
                    </th>
                    <th class="px-2 text-right border-0 rounded-e-lg  ">
                        {{ number_format(abs($amountUSD)) ?? 0 }}
                        <p class="text-sm">{{ $priceInWordsUSD }}</p>
                    </th>
                </tr>
                @endIf
            </thead>
</table>
        <!-- الإجماليات -->
        <div id="header1" class="totals-section bg-blue-100 p-4 rounded-lg ">
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
            {{ \Carbon\Carbon::now('Asia/Riyadh')->format('Y-m-d H:i:s') }}

        </div>


        <!-- زر الطباعة -->
        <div class="mt-4 no-print header1">
            <button id="myButton" class="px-4 py-2 bgcolor text-white rounded-lg shadow-md hover:bg-blue-600">
        تغيير الألوان
            </button>

            <button id="myButton2" class="px-4 py-2 bgcolor2 text-black rounded-lg shadow-md hover:bg-blue-600">
        تغيير الألوان
            </button>
            <button id="myButton3" class="px-4 py-2 bgcolor3  rounded-lg shadow-md hover:bg-blue-600">
        تغيير الألوان
            </button>
            <script>
                $(document).ready(function(){
                    $("#myButton").click(function(){
                        $("div[id='header1']").toggleClass("text-white bgcolor shadow-sm   ");
                        $("tr[id='header1']").toggleClass("text-white bgcolor shadow-sm  ");

                    });
                    $("#myButton2").click(function(){
                        $("div[id='header1']").toggleClass("bgcolor2 shadow-sm   ");
                        $("tr[id='header1']").toggleClass(" bgcolor2 shadow-sm  ");

                    });
                    $("#myButton3").click(function(){
                        $("div[id='header1']").toggleClass("bgcolor3 shadow-sm   ");
                        $("tr[id='header1']").toggleClass(" bgcolor3 shadow-sm  ");

                    });
                });
            </script>
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
