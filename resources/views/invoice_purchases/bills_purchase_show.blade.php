<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>     {{$transaction_type}}
        {{($Invoice_type)}}
      </title>
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
        <header class="flex justify-between items-center border-b-2 border-gray-800 pb-4 mb-4">
           
            <div class="flex">
                <div class="font-extrabold">
                    <div class="">
                        اسم@isset($accountCla) {{{" "}}} {{$accountCla}}@endisset:  {{{" "}}}
                    @isset($SubAccounts)
                    {{{" "}}}   {{$SubAccounts->sub_name??null}}  {{{" "}}}
                    @endisset
                 </div>
                    <div class="">
                        {{ __('رقم الفاتورة:') }} 
                        {{$DataPurchaseInvoice->purchase_invoice_id??0}}                </div>
                </div>
                </div>
           
            <div>
                <h2 class="text-lg font-bold">فاتورة :
                   {{$transaction_type??null}}/
                   {{$Invoice_type}}



                </h2>
            </div>
            <div>
                <div class="flex">
                    <div class="font-extrabold">
                        <div class="">
                            {{ __('التاريخ') }} :
                            {{ \Carbon\Carbon::parse($DataPurchaseInvoice->created_at)->format('Y-m-d') ?? __('غير متوفر') }} / {{ str_replace(['AM', 'PM'], ['ص', 'م'], \Carbon\Carbon::parse($DataPurchaseInvoice->created_at)->format('h:i A')) }}
                        </div>
                        <div class="">
                            {{ __('رقم الإيصال:') }} 
                            {{$DataPurchaseInvoice->Receipt_number??0}}                    </div>
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
                    <th class="px-2 text-right">سعر الشراء</th>
                    <th class="px-2 text-right  ">المخزن</th>
                    <th class="px-2 text-right">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @isset($DataPurchase)



                @foreach ($DataPurchase as $Purchase)
                    <tr class="bg-white">
                        <td>{{$loop->iteration}}</td>
                        <td class="px-2 text-right">{{ $Purchase->Product_name }}</td>
                        <td class="px-2 text-right">
                            {{ $Purchase->categorie_id }}


                            </td>
                        <td class="px-2 text-center">{{ $Purchase->quantity }}</td>
                        <td class="px-2 text-right">{{number_format( $Purchase->Purchase_price) }}</td>
                        <td class="px-2 text-right">
                            @isset($warehouses)
                                @foreach ($warehouses as $warehouse)
                                    @if($warehouse->sub_account_id === ($Purchase->warehouse_to_id ?? $Purchase->warehouse_from_id))
                                        {{ $warehouse->sub_name }}
                                        @break
                                    @endif
                                @endforeach
                            @endisset
                        </td>

                        <td class="px-2 text-right">{{ number_format($Purchase->Total )}}</td>
                    </tr>
                @endforeach
                @php
                    $Discount_earnedValue=$Purchase->Discount_earned ?? 0;
                @endphp
                @endisset
            </tbody>
        </table>

        <!-- الإجماليات -->
        <div class="totals-section bg-gray-100 p-4">
            <div class="flex justify-between">

                <div>
                    <p class="font-semibold">
                        الإجمالي  : {{number_format($Purchase_priceSum) ?? 0}}</p>
                    <p> {{ $priceInWords }}</p>
                </div>
                <div>
                    <p class="font-semibold">
                        @if ($Purchase_CostSum>0)
                        الجمالي  المصاريف: {{ number_format($Purchase_CostSum ?? 0)}}</p>
                        <p>العملة:
                            @isset($currency->currency_symbol)
                            {{$currency->currency_symbol}}

                            @endisset

                        </p>

                        @endif
                </div>

            </div>
        </div>
        <div class=" bg-white p-4">

                    <p class=" text-sm" dir="ltr">  المسؤول : {{($UserName) ?? 0}}</p>


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
