<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>               {{ $Myanalysis }}
    </title>
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
    <div class=" print-container px-1 ">
        <!-- العنوان -->
        @isset($buss)
        <div class="header bg-[#1749fd15]  rounded-lg">
               @include('includes.header2')

      
  </div>
  <div class="text-center space-y-4">
    <p class="font-extrabold text-lg">
        {{ $Myanalysis }}
      
    </p>
</div>
  {{-- <div class="grid grid-cols-2 w-full gap-2  text-gray-700">
          {{$productname }}
</div> --}}
{{-- @if(!$inventoryList)
  <div class="grid grid-cols-2 w-full gap-2  text-gray-700">
          {{$productname }}
</div>
@endif --}}

@endisset
@isset($productData)
@include('components.storesData.Quantit-yonly')
@endisset
@isset($productDataCosts)
@include('components.storesData.Quantity-Costs')
@endisset
@isset($allQuantityonly)
@include('components.storesData.all-Quantit-yonly')
@endisset
@isset($firstQuantityonly)
@include('components.storesData.all-Quantit-yonly')
@endisset
@isset($allQuantityCosts)
@include('components.storesData.all-Quantity-Costs')
@endisset
@isset($firstQuantityCosts)
@include('components.storesData.all-Quantity-Costs')
@endisset
@isset($QuantityCostsSupplier)
@include('components.storesData.Quantity-Costs-Supplier')
@endisset
@isset($QuantitySupplier)
@include('components.storesData.Quantity-Supplier')
@endisset
@isset($inventoryList)
@include('components.storesData.inventory-list')
@endisset
{{-- @isset($inventoryList)
  <div class="grid grid-cols-2 w-full gap-2  text-gray-700">
          {{$productname }}
</div>
@endisset --}}
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
