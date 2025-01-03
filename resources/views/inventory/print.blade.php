<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>  تقرير  المخزني {{$Myanalysis}}</title>
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
    <div class=" print-container px-1 ">
        <!-- العنوان -->
  @isset($buss)
    @isset($buss)
    <div class="header bg-[#1749fd15]  rounded-lg">
        @include('includes.header2')


        

    </div>
@endisset





  <div class="grid grid-cols-2 w-full gap-2  text-gray-700">
          {{$productname ??'' }}
</div>


@endisset
@isset($inventoryList)

<div class="rounded-lg grid grid-cols-3 gap-6 p-2 w-full">
  <!-- القسم الأيمن - Arabic content -->
  <div class="text-right space-y-2">
      <p class="text-sm text-gray-700"><span class=" font-medium">مسؤول الجرد:</span> </p>
      <p class="text-sm text-gray-700"> <span class=" font-medium">تاريخ الجرد:</span>{{ $toDate ??'' }}</p>
  </div>
  <!-- القسم الأوسط - تحليل الحسابات -->
  <div class="flex items-center justify-center px-2">

  </div>
  <!-- القسم الأيسر - English content -->
  <div class="text-left space-y-2">
      <p class="text-sm text-gray-700"><span class=" font-medium">المخزن: </span>{{ $warehouseName }}</p>
  </div>
</div>
@endisset
@isset($QuantityIncomplete)
@include('components.InventoryReports.Missing-quantities-report')
@endisset
@isset($CostIncomplete)
@include('components.InventoryReports.Missing-quantities-report')
@endisset
@isset($QuantityAppendix)
@include('components.InventoryReports.excess-quantity-report')
@endisset
@isset($inventoryList)
@include('components.storesData.inventory-list')
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


</body>

</html>
