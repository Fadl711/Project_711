<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>تقرير المبيعات</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        
        .english {
            font-family: 'Times New Roman', serif;
        }

        table {
            width: 100%;
        }

        th, td {
            border: 1px solid #000;
        }

        .header-section, .totals-section {
            margin-top: 10px;
            border: 2px solid #000;
            border-radius: 8px;
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }
            
            html, body {
                width: 100%;
                height: 100%;
                margin: 0 !important;
                padding: 0 !important;
                background-color: white;
            }

            .print-container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 1cm !important;
                margin: 0 !important;
                background-color: white;
            }

            .no-print {
                display: none !important;
            }

            canvas {
                max-width: 100% !important;
                height: auto !important;
            }

            .chart-container {
                width: 100% !important;
                height: 250px !important;
                page-break-inside: avoid;
                margin-bottom: 1cm;
                display: block;
                background-color: white;
            }

            .product-section {
                page-break-inside: avoid;
                margin-bottom: 1cm;
                break-inside: avoid;
                background-color: white;
            }

            .header-section, .totals-section {
                break-inside: avoid;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <main class="print-container">
        @isset($buss)
            <div class="header rounded-lg">
                @include('includes.header2')
            </div>
            <div class="text-center space-y-4">
                <p class="font-extrabold text-lg">تقرير المبيعات</p>
            </div>
        @endisset

        @isset($dataProducts)
            @include('components.sales-report')
        @endisset
    </main>

    <div class="mt-4 no-print">
        <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">طباعة</button>
        <button onclick="window.close()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">إغلاق</button>
    </div>
</body>
</html>
