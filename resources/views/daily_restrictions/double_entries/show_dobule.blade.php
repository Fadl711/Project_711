<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>قيد مزدوج</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* تخصيص للطباعة */
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
                font-size: 14px;
            }

            .print-container {
                width: 100%;
                max-width: 100%;
                margin: 0 auto;
            }

            .no-print {
                display: none;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .header-section,
            .totals-section {
                border: 1px solid #000 !important;
            }
        }

        /* تحسين مظهر الجدول */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            font-family: Arial, sans-serif;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: right;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .header-info {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }

        .header-info p {
            margin: 5px 0;
            line-height: 1.4;
        }

        .signature-section {
            margin-top: 30px;
            text-align: left;
        }

        .container {
            padding: 10px;
        }

        .table-container {
            overflow-x: auto;
        }

        /* تحسينات للعرض على الشاشة */
        @media screen {
            body {
                padding: 20px;
                background-color: #fff;
            }

            .print-container {
                max-width: 1000px;
                margin: 0 auto;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }
        }

        /* تثبيت عرض الأعمدة */
        .col-no {
            width: 40px;
        }

        .col-account {
            width: 250px;
        }

        .col-amount {
            width: 120px;
        }

        .col-currency {
            width: 80px;
        }

        .col-statement {
            width: 300px;
        }
    </style>
</head>

<body class="bg-white">
    <div class="print-container mx-auto" id="js-print-template" x-ref="printTemplate">
        @include('includes.header2')

        <div class="header-info  border-2 border-gray border-t-0 border-solid flex justify-between">
            <div>
                <p>تاريخ القيد: {{ \Carbon\Carbon::parse($doubleEntry->created_at)->format('Y/m/d') }}</p>
                <p>رقم القيد: {{ $doubleEntry->id }}</p>
                <p>رقم المرجع:
                    {{ $doubleEntry->double_entries->count() >= 1 ? $doubleEntry->double_entries->first()->daily_page_id : ' ' }}
                </p>
            </div>
            <div>
                <p>
                    @if ($doubleEntry->account_type == 'دائن')
                        تقيد في حساب: {{ $doubleEntry->creditAccount->sub_name }}
                    @else
                        إيداع في حساب: {{ $doubleEntry->debitAccount->sub_name }}
                    @endif
                </p>
                <p>المبلغ:
                    <span id="maont2">
                        {{ $doubleEntry->double_entries->count() >= 1 ? number_format($doubleEntry->double_entries->sum('amount_credit')) : 0 }}
                        <span
                            class="font-normal">{{ $doubleEntry->double_entries->count() >= 1 ? $doubleEntry->double_entries->first()->currency_name : ' ' }}</span>
                    </span>
                </p>
                <p>البيان: {{ $doubleEntry->Statement }}</p>
            </div>
        </div>

        <div class="table-container">
            <table class="text-center">
                <thead>
                    <tr>
                        <th class="col-no">م</th>
                        <th class="col-account">اسم الحساب
                            {{ $doubleEntry->account_type == 'دائن' ? 'المدين' : 'الدائن' }}</th>
                        <th class="col-amount">{{ $doubleEntry->account_type == 'دائن' ? 'مدين' : 'دائن' }}</th>
                        <th class="col-currency">العملة</th>
                        <th class="col-statement">البيان</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($doubleEntry->double_entries->count() >= 1)

                        @foreach ($doubleEntry->double_entries as $dailyEntry)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $doubleEntry->account_type == 'دائن' ? $dailyEntry->debitAccount->sub_name : $dailyEntry->creditAccount->sub_name }}
                                </td>
                                <td>{{ number_format($dailyEntry->amount_debit) }}</td>
                                <td>{{ $dailyEntry->currency_name }}</td>
                                <td>{{ $dailyEntry->statement }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="signature-section flex justify-between">
            <p>المحاسب: {{ $doubleEntry->user->name ?? '--' }} </p>
            <p>التوقيع: ............................ </p>
        </div>
    </div>

    <!-- زر الطباعة -->
    <div class="no-print text-center mt-4">
        <button onclick="printAndClose()"
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 mr-2">طباعة</button>
        <button onclick="closeWindow()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">إلغاء
            الطباعة</button>
    </div>

    <script>
        function printAndClose() {
            window.print();
            setTimeout(() => {
                window.close();
            }, 500);
        };

        function closeWindow() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.close();
            }
        }

        $(document).ready(function() {
            $("#sumamount").click(function() {
                $("tr[id='Sumamount']").toggleClass("hidden");
            });
        });
    </script>
</body>

</html>
