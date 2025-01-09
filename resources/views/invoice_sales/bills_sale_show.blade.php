<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>فاتورة مبيعات</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    <style>
        /* تخصيص للطباعة */
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .print-container {
                @apply w-full max-w-full mx-auto p-4;
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
        th, td {
            border: 1px solid #000;
        }

        .header-section, .totals-section {
            margin-top: 16px;
            border: 2px solid #000;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-white">
    <div class=" mx-auto print-container">
        <!-- العنوان -->
        @include('includes.header2')


        <header class="flex justify-between items-center border-b-2 border-gray-800 ">
            <div>
                <div>
                    <div class="flex">
                        <div class="font-extrabold">
                            {{ __('اسم') }} {{ $accountCla ?? __('غير متوفر') }}:
                        </div>
                        <div class="">
                            {{ $SubAccounts->sub_name ?? __('غير متوفر') }}
                        </div>
                    </div>
                </div>


                <div>
                    <div class="flex">
                        <div class="font-extrabold">
                            {{ __('العملة') }} :
                        </div>
                        <div class="">
                            {{ $currency->currency_symbol ?? __('YR') }}
                        </div>
                    </div>
                </div>

            </div>
            <div>
                <h2 class="text-lg font-bold">فاتورة :
                   {{$transaction_type??null}}/
                   {{$payment_type}}



                </h2>
            </div>
            <div>
                <div>
                    <div class="flex">
                        <div class="font-extrabold">
                            {{ __('التاريخ') }} :
                        </div>
                        <div class="">
                            {{ $DataPurchaseInvoice->created_at ?? __('غير متوفر') }}
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex">
                        <div class="font-extrabold">
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
        <table class="w-full  text-sm">
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



                @foreach ($DataSale as $Sale)
                    <tr class="bg-white">
                        <td class="px-2 ">{{$loop->iteration}}</td>
                        <td class="px-2 text-right">{{ $Sale->Product_name }}</td>
                        <td class="px-2 text-right">

                            {{ $Sale->Category_name }}
                            </td>
                        <td class="px-2 text-right">{{ $Sale->Quantityprice }}</td>
                        <td class="px-2 text-right">{{number_format( $Sale->Selling_price) }}</td>
                        <td class="px-2 text-right">
                            @isset($warehouses)
                            @foreach ($warehouses as $warehouse)
                            @if($warehouse->sub_account_id === $Sale->warehouse_to_id)
                            {{ $warehouse->sub_name }}
                            @endif
                            @endforeach
                            @endisset

                        </td>
                        <td class=" text-right">{{ number_format($Sale->total_amount )}}</td>
                    </tr>
                @endforeach

                @endisset
            </tbody>

        </table>
        <table class="w-[60%] text-sm ">


                    <thead>
                        <tr class="bg-blue-100">
                            <th class="px-2 text-right w-[30%]">
                                <p class="font-">المبلغ المستحق</p>
                            </th>
                            <th class="px-2 text-right">
                                {{ number_format($Sale_CostSum) ?? 0 }}
                                <p class="text-sm">{{ $priceInWords }}</p>
                            </th>

                        </tr>
                        @php
                        $sum1 = $Sum_amount-$Sale_CostSum  ;
                    @endphp
                        <tr class="bg-blue-100">
                            @if($sum1>0)
                            <th class="px-2 text-right">رصيد سابق</th>
                            <th class="px-2 text-right">
                                @if(isset( $sum1) &&   $sum1> 0)
                                @if($payment_type=="أجل")

                                        {{ number_format($sum1) ?? 0 }}
                                        @endif
                                @if($payment_type!=="أجل" && $Sum_amount>0)

                                        {{ number_format($Sum_amount) ?? 0 }}
                                        @endif




                                @endif
                            </th>
                            @endif

                        </tr>
                        @if($payment_type=="أجل")
                        <tr class="bg-blue-100">
                            @php
                          $Sum_amount ;
                        @endphp
                            <th class="px-2 text-right">الجمالي رصيد</th>

                            <th class="px-2 text-right">
                                @if(isset($Sum_amount) && $Sum_amount > 0)
                                    {{ number_format($Sum_amount) ?? 0 }}
                                @endif                        </tr>
                        @endif

                    </thead>


        </table>

        <!-- الإجماليات -->
        <div class="totals- bg-gray-100 p-4">
            <div class="flex justify-between ">
                <div>
                    <p class=" text-sm" dir="ltr">................ توقيع المستلم</p>
                </div>
                <span class="-translate-y-40">{{$thanks ?? ''}}</span>
                <div>
                    <p class=" text-sm" dir="ltr">  المستخدم: {{($UserName) }}</p>
                </div>
            </div>
            <div>

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
