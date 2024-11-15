<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>فاتورة الشراء</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            padding: 8px;
        }
        th {
            background-color: #e0f7fa;
            color: #00796b;
        }
        .header-section, .totals-section {
            margin-top: 16px;
            padding: 12px;
            border: 2px solid #000;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-white">
    <div class="container mx-auto print-container">
        <!-- العنوان -->
        @isset($buss)
        <div class="header-section border-2 border-black rounded-b-lg my-2">
            <div class="bg-gray-200 p-8 rounded-lg flex justify-between w-full">
                <div class="text-right">
                    <h2 class="text-xl font-bold mb-2">{{ $buss->Company_Name }}</h2>
                    <p>{{ $buss->Services }}</p>
                    <p>العنوان: {{ $buss->Company_Address }}</p>
                    <p>التلفون: {{ $buss->Phone_Number }}</p>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-24 h-20 bg-gray-300 flex items-center justify-center translate-x-10">
                        <img src="{{ url($buss->Company_Logo ? 'images/' . $buss->Company_Logo : '') }}" alt="">
                    </div>
                </div>
                <div class="text-left">
                    <h2 class="text-xl font-bold mb-2">{{ $buss->Company_NameE }}</h2>
                    <p>{{ $buss->ServicesE }}</p>
                    <p>Address: {{ $buss->Company_AddressE }}</p>
                    <p>Phone: {{ $buss->Phone_Number }}</p>
                </div>
            </div>
        </div>
        @endisset

        <header class="flex justify-between items-center border-b-2 border-gray-800 pb-4 mb-4">
            <div>
                <p>اسم المورد:  
                    @isset($SubAccounts)
                    
             

                    @foreach ($SubAccounts as $SubAccount)
                    @if($SubAccount->sub_account_id === $DataPurchaseInvoice->Supplier_id)

                     {{$SubAccount->sub_name??null}}</p>
                     @endif
                    @endforeach

                    @endisset

                </p>
                <p>التلفون: 776327938</p>
                <p>العملة: 776327938</p>
            </div>
            <div>
                <h2 class="text-lg font-bold">فاتورة :
                    @isset($accountType)
                    @foreach ($accountType as $accountTypes)
                    @if($accountTypes->value === $DataPurchaseInvoice->transaction_type)

                    {{ $accountTypes->label()}} 
                     @endif
                    @endforeach
                    @endisset


                </h2>
                {{-- <p>اسم المورد: جمال علي احمد</p>
                <p>التلفون: 776327938</p> --}}
            </div>
            <div>
                <p>التاريخ: {{$DataPurchaseInvoice->created_at}}</p>
                <p>رقم الإيصال:  {{$DataPurchaseInvoice->Receipt_number??0}}</p>
                <p>الدفع :{{$DataPurchaseInvoice->Invoice_type??null}}</p>
            </div>
        </header>

        <!-- جدول المنتجات -->
        <table class="w-full mb-4 text-sm">
            <thead>
                <tr class="bg-blue-100">
                    <th class="p-2 text-right">م</th>
                    <th class="p-2 text-right">اسم الصنف</th>
                    <th class="p-2 text-right"> الوحده</th>

                    <th class="p-2 text-center">الكمية</th>
                    <th class="p-2 text-right">سعر الشراء</th>
                    <th class="border border-black px-2 py-1">المخزن</th>
                    <th class="p-2 text-right">الإجمالي</th>
                    {{-- <th class="p-2 text-right">التكلفة</th> --}}
                </tr>
            </thead>
            <tbody>
                @isset($DataPurchase)
                    
             

                @foreach ($DataPurchase as $Purchase)
                    <tr class="bg-white">
                        <td>{{$loop->iteration}}</td>
                        <td class="p-2 text-right">{{ $Purchase->Product_name }}</td>
                        <td class="p-2 text-right">
                            @isset($Categorys)
                            @foreach ($Categorys as $Category)
                            @if($Category->categorie_id === $Purchase->categorie_id)
                            {{ $Category->Categorie_name }}
                            @endif
                            @endforeach
                         
                            @endisset

                            </td>
                        <td class="p-2 text-center">{{ $Purchase->quantity }}</td>
                        <td class="p-2 text-right">{{number_format( $Purchase->Purchase_price) }}</td>
                        <td class="p-2 text-right">{{ $Purchase->warehouse_to_id }}</td>
                        <td class="p-2 text-right">{{ number_format($Purchase->Total )}}</td>
                        {{-- <td class="p-2 text-right">{{ $Purchase->Cost }}</td> --}}
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
                {{-- <div>
                    <p class="font-semibold">الخصم: {{$Discount_earnedValue}}</p>
                </div> --}}
                <div>
                    <p class="font-semibold">الجمالي تكلفة الشراء: {{number_format($Purchase_priceSum) ?? 0}}</p>
                </div>
                <div>
                    <p class="font-semibold">الجمالي  المصاريف: {{ number_format($Purchase_CostSum ?? 0)}}</p>
                </div>
                
                {{-- <div>
                    <p class="font-semibold">صافي الفاتورة: 99,000 ريال يمني</p>
                    <p class="text-xs text-gray-600">الفين ومائتين وخمسين ريال يمني</p>
                </div> --}}
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
