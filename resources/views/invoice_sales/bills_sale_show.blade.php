<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>فاتورة مبيعات</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/jquery/dist/jquery.min.js') }}"></script>
    <style>
        /* تخصيص للطباعة */
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .print-container {
                @apply w-full max-w-full mx-auto;
            }

            .no-print {
                display: none;
            }
        }

        /* تحسين مظهر الجدول */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
        }

        .mr2 {
            margin-top: -20px !important;
        }

        .mr3 {
            margin-bottom: -20px !important;
        }

        .header-section,
        .totals-section {
            margin-top: 16px;
            border: 2px solid #000;
            border-radius: 8px;
        }
    </style>
</head>

<body class="bg-white">
    <div class=" mx-5 print-container ">
        <!-- العنوان -->
        @include('includes.header2')


        <header class="flex justify-between items-center  text-sm mr3 ">
            <div>
                <div>
                    <div class="flex">
                        <div class="font-extrabold text-sm">
                            {{ __('اسم') }} {{ $accountCla ?? __(' ') }}:
                        </div>
                        <div class="">
                            {{ $SubAccounts->sub_name ?? __(' ') }}
                        </div>
                    </div>
                </div>


                <div>
                    <div class="flex">
                        <div class="font-extrabold text-sm ">
                            {{ __('العملة') }} :
                        </div>
                        <div class="">
                            {{ $currency->currency_symbol ?? __('YR') }}
                        </div>
                    </div>
                </div>

            </div>
            <div>
                <div class="inline-flex items-center text-center w-full ">

                    <span class="px-4   font-bold  bg-white  border  border-l-0.5 rounded-s-lg border-black  ">فاتورة :
                        {{ $transaction_type ?? null }}</span>
                    <span
                        class="px-8 font-bold  text-blue-700  bg-white border border-black  border-l-0.5 rounded-e-lg  c">({{ $payment_type }})</span>


                </div>
            </div>
            <div>
                <div>
                    <div class="flex">
                        <div class="font-extrabold text-sm">
                            {{ __('التاريخ') }} :
                        </div>
                        <div class=" ">
                            {{ \Carbon\Carbon::parse($DataPurchaseInvoice->created_at)->format('Y-m-d') ?? __('غير متوفر') }}
                            /
                            {{ str_replace(['AM', 'PM'], ['ص', 'م'], \Carbon\Carbon::parse($DataPurchaseInvoice->created_at)->format('h:i A')) }}
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex">
                        <div class="font-extrabold text-sm">
                            {{ __('رقم الفاتورة') }} :
                        </div>
                        <div class="">
                            {{ $DataPurchaseInvoice->sales_invoice_id ?? __('غير متوفر') }}
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- جدول المنتجات -->
        <table class="w-full  text-sm ">
            <thead>
                <tr class="bg-[#1749fd15] ">
                    <th class="px-2 text-right">م</th>
                    <th class="px-2 text-right">اسم الصنف</th>
                    <th class="px-2 text-right"> الوحده</th>

                    <th class="px-2 text-center">الكمية</th>
                    <th class="px-2 text-right">سعر الوحده</th>
                    <th class="px-2 text-right">المخزن</th>
                    <th class="px-2 text-right">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @isset($DataSale)
                    @php
                        $sumAmount = 0;
                    @endphp
                    @foreach ($DataSale as $Sale)
                        <tr class="bg-white">
                            <td class="px-2 ">{{ $loop->iteration }}</td>
                            <td class="px-2 text-right">{{ $Sale->Product_name }}</td>
                            <td class="px-2 text-right">
                                {{ $Sale->Category_name ?? '' }}
                            </td>
                            <td class="px-2 text-center">
                                @if (floor($Sale->Quantityprice) == $Sale->Quantityprice)
                                    {{ number_format($Sale->Quantityprice, 0, '.', '') }}
                                @else
                                    {{ number_format($Sale->Quantityprice, 2, '.', '') }}
                                @endif
                            </td>
                            <td class="px-2 text-right">{{ number_format($Sale->Selling_price) }}</td>
                            <td class="px-2 text-right">
                                @isset($warehouses)
                                    @foreach ($warehouses as $warehouse)
                                        @if ($warehouse->sub_account_id === $Sale->warehouse_to_id)
                                            {{ $warehouse->sub_name }}
                                        @endif
                                    @endforeach
                                @endisset

                            </td>

                            <td class=" text-right">{{ number_format($Sale->total_amount) }}</td>

                        </tr>
                    @endforeach
                    @php
                        if ($discount <= 0) {
                            $x = 6;
                        } else {
                            $x = 5;
                        }
                    @endphp
                    @if ($discount > 0)
                        <tr class="bg-[#1749fd15]  ">
                            <th colspan="{{ $x }}" class="font-bold text-right px-2 "> </th>
                            @if ($discount > 0)
                                <th class="font-bold  px-2 ">الخصم</th>
                            @endif
                            <th class="font-bold text- px-2 ">الإجمالي</th>
                        </tr>
                        <tr>
                            <td colspan="{{ $x }}" class="font-bold text-right px-2 "></td>
                            @if ($discount > 0)
                                <th class="font-bold text-red-500 px-2 ">{{ number_format($discount) }}</th>
                            @endif
                            <th class=" text-red-500 px-2">{{ number_format($Sale_CostSum) }}</th>
                        </tr>
                    @endif

                @endisset
            </tbody>

        </table>
        <div class="flex justify-start mr2 py-1">
            <table class=" text-sm mx-auto">
                <thead class=" ">
                    <tr class="bg-blue-100 ">


                        <th class="px-2 text-left   ">
                            <p class="font-">المبلغ المستحق</p>
                            <p class="text-sm">{{ $priceInWords }}</p>
                        </th>
                        <th class="px-2 text-right w-[70%] ">
                            {{ number_format($net_total_after_discount) ?? 0 }}
                        </th>

                    </tr>
                    @php
                        $sum1 = $Sum_amount - $Sale_CostSum;
                    @endphp
                    <tr id="Sumamount" class="bg-blue-100 ">

                        @if ($sum1 > 0)
                            <th class="px-2 text-left w-[50%] ">
                                رصيد سابق
                            </th>
                            <th class="px-2 text-right 30">

                                @if (isset($sum1) && $sum1 > 0)
                                    @if ($payment_type == 'أجل')
                                        {{ number_format($sum1) ?? 0 }}
                                    @endif
                                    @if ($payment_type !== 'أجل' && $Sum_amount > 0)
                                        {{ number_format($Sum_amount) ?? 0 }}
                                    @endif




                                @endif
                            </th>
                        @endif

                    </tr>
                    @if ($payment_type == 'أجل' && $sum1 > 0)
                        <tr id="Sumamount" class="bg-blue-100 ">
                            @php
                                $Sum_amount;
                            @endphp

                            <th id="Sumamount" class="px-2 text-left w-[20%]">
                                اجمالي الرصيد </th>

                            <th id="sum_amount" class="px-2 text-right">
                                @if (isset($Sum_amount) && $Sum_amount > 0)
                                    {{ number_format($Sum_amount - $discount ?? 0) ?? 0 }}
                                @endif
                        </tr>
                    @endif

                </thead>


            </table>
        </div>
        @if ($note)
            <br>
            <div class="rounded-md border p-4 py-2 align-text-bottom-2 border-gray-800">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm" dir="ltr">
                            <span class="text-body font-bold">ملاحظة:</span>
                            {{ $note }}
                        </p>
                    </div>
                </div>
            </div>
            <br>
        @endif
        <!-- الإجماليات -->
        <div class="totals- bg-gray-100 px-4 py-2">
            <div class="flex justify-between ">
                <div>
                    <p class=" text-sm" dir="ltr">................ توقيع المستلم</p>
                </div>
                <span class="">{{ $thanks ?? '' }}</span>
                <div>
                    <p class=" text-sm" dir="ltr"> المستخدم: {{ $UserName }}</p>
                </div>
            </div>
            <div>
            </div>
        </div>
        <!-- زر الطباعة -->
        <div class="mt-4 no-print">
            <button id="sumamount" type="button" class="px-4 py-2    rounded-lg shadow-md hover:bg-blue-600">
                الرصيد السابق </button>
            <button onclick="printAndClose()"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">طباعة</button>

            <script>
                $(document).ready(function() {
                    $("#sumamount").click(function() {

                        $("tr[id='Sumamount']").toggleClass("hidden");

                    });

                });

                function printAndClose() {
                    window.print(); // أمر الطباعة
                    setTimeout(() => {
                        window.close(); // الإغلاق بعد بدء الطباعة
                    }, 500); // فترة الانتظار نصف ثانية فقط
                };
            </script>
            <button onclick="closeWindow()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">إلغاء
                الطباعة</button>
            <script>
                function closeWindow() {
                    if (window.history.length > 1) {
                        // إذا كانت الصفحة جزءًا من التنقل
                        window.history.back();
                    } else {
                        // إذا كانت الصفحة مفتوحة في نافذة جديدة
                        window.close();
                    }
                }
            </script>
        </div>
    </div>
</body>

</html>
