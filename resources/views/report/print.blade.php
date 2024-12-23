<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>              التقرير المخزن -  {{ $Myanalysis }}
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
    <div class="container mx-auto print-container">
  <!-- العنوان -->
  @isset($buss)
  <div class="header-section border-2 border-black bg-[#1749fd15]  rounded-lg my-4">
      <div class="rounded-lg grid grid-cols-3 gap-6 p-2 w-full">
          <!-- القسم الأيمن - Arabic content -->
          <div class="text-right space-y-2">
              <h2 class="font-extrabold  ">{{ $buss->Company_Name }}</h2>
              <p class="text-sm text-gray-700">{{ $buss->Services }}</p>
              <p class="text-sm text-gray-700">العنوان: {{ $buss->Company_Address }}</p>
              <p class="text-sm text-gray-700">التلفون: {{ $buss->Phone_Number }}</p>
          </div>
          <!-- القسم الأوسط - تحليل الحسابات -->
          <div class="flex items-center justify-center px-2">
              <div class="w-24 h-20   flex items-center justify-center translate-x-10">
                  <img class=" bg-[#1749fd15] rounded-3xl" src="{{ url($buss->Company_Logo ? 'images/' . $buss->Company_Logo : '') }}" alt="">
              </div>
          </div>
          <!-- القسم الأيسر - English content -->
          <div class="text-left space-y-2">
              <h2 class="font-extrabold  ">{{ $buss->Company_NameE }}</h2>
              <p class="text-sm text-gray-700">{{ $buss->ServicesE }}</p>
              <p class="text-sm text-gray-700">Address: {{ $buss->Company_AddressE }}</p>
              <p class="text-sm text-gray-700">Phone: {{ $buss->Phone_Number }}</p>
          </div>
      </div>
      <div class="text-center space-y-4">
          <p class="font-extrabold text-lg">
            التقرير المخزن -  {{ $Myanalysis }}
             :
                {{ $accountingPeriod ?? ''}}
          </p>
      </div>
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
@isset($allQuantityCosts)
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
