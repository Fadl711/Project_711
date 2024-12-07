<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> تحليل المخزن</title>
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
        }
       
        .header-section, .totals-section {
            margin-top: 16px;
            border: 2px solid #000;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-white">
<div class="container mx-10">
@include('includes.header2')
@isset($productData)
@include('components.storesData.Quantit-yonly')
@endisset
@isset($productDataCosts)
@include('components.storesData.Quantity-Costs')
@endisset
@isset($Getproduct)
    
<div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr class="bg-blue-100">
                <th class=" px-2 py-1  tagTd">رقم الصنف</th>
                <th class=" px-2 py-1  tagTd">اسم الصنف</th>
                <th class=" px-2 py-1  tagTd"> الوحدة</th>
                <th class="py-1 px-2 tagTd">  وصف الصنف</th>
                <th class="py-1 px-2 tagTd">المخزن</th>
                <th class="py-1 px-2 tagTd">الكمية المتوفره</th>
                <th class="py-1 px-2 tagTd"> التكلفة</th>
                <th class="py-1 px-2 tagTd"> الجالي التكلفة</th>
            </tr>
        </thead>
        <tbody class="  font-medium ">
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                @foreach ($Getproduct as $item)
                <td class="py-1 px-2">{{ $item['product_id'] }}</td>
                <td class="py-1 px-2">{{ $item['product_name'] }}</td>
                <td class="py-1 px-2">
                    {{ $item['categories']->Categorie_name ?? 'لا توجد فئة' }}
                </td>
                {{-- <td class="py-1 px-2">{{ $item['note'] ?? '' }}</td> --}}
                <td class="py-1 px-2">
                    {{ $item['warehouse_name'] ?? '' }}
                </td>
                <td class="py-1 px-2">
                    {{ $item['product_purchase'] ?? 0 }}
                </td>
            @endforeach

            </tr>
        </tbody>
    </table>
</div>
@endisset



</div>
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
<Script>
</Script>

</body>

</html>
