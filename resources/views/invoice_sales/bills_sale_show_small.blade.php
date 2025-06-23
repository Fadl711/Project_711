<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>فاتورة مبيعات</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            background-color: white;
            color: black;
            width: 76mm;
            margin: 0 auto;
        }

        .thermal-invoice {}

        .header {
            text-align: center;
            margin-bottom: 5mm;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3mm;
        }

        .company-info {
            margin-bottom: 3mm;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 2mm 0;
            margin-bottom: 3mm;
        }

        .customer-info {
            margin-bottom: 3mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 3mm 0;
        }

        th,
        td {
            padding: 1mm 2mm;
            text-align: right;
        }

        th {
            border-bottom: 1px solid #000;
        }

        .item-row {
            border-bottom: 1px dashed #ccc;
        }

        .totals {
            margin-top: 3mm;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }

        .grand-total {
            font-weight: bold;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 2mm 0;
            margin: 2mm 0;
        }

        .footer {
            text-align: center;
            margin-top: 5mm;
            font-size: 10px;
        }

        .barcode {
            text-align: center;
            margin: 5mm 0;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="thermal-invoice">
        <!-- الترويسة -->
        <div class="header">
            <div class="logo">{{ $buss->Company_Name }}</div>
            <div class="company-info">
                <div>{{ $buss->Company_Address }}</div>
                <div>{{ $buss->Services }}</div>
                <div>هاتف: {{ $buss->Phone_Number }}</div>
            </div>
            <div class="inline-flex items-center text-center w-full ">

                <span class="px-4   font-bold  bg-white  border  border-l-0.5 rounded-s-lg border-black  ">فاتورة :
                    {{ $transaction_type ?? null }}</span>
                <span
                    class="px-8 font-bold  text-blue-700  bg-white border border-black  border-l-0.5 rounded-e-lg  c">({{ $payment_type }})</span>


            </div>
        </div>

        <!-- تفاصيل الفاتورة -->
        <div class="invoice-details">
            <div>

                <div> {{ __('اسم') }} {{ $accountCla ?? __(' ') }}:
                    <strong>
                        {{ $SubAccounts->sub_name ?? __(' ') }}
                    </strong>
                </div>
                <div> العملة: <strong>{{ $currency->currency_symbol ?? __('YR') }}
                    </strong></div>
            </div>
            <div>
                <div>تاريخ: <strong>
                        {{ \Carbon\Carbon::parse($DataPurchaseInvoice->created_at)->format('Y-m-d') ?? __('غير متوفر') }}
                        /
                        {{ str_replace(['AM', 'PM'], ['ص', 'م'], \Carbon\Carbon::parse($DataPurchaseInvoice->created_at)->format('h:i A')) }}</strong>
                </div>
                <div>رقم الفاتورة: <strong>{{ $DataPurchaseInvoice->sales_invoice_id ?? __('غير متوفر') }}
                    </strong></div>
            </div>
        </div>

        <!-- معلومات العميل -->
        {{--         <div class="customer-info">
            <div>العميل: <strong>عميل نقدي</strong></div>
            <div>رقم الجوال: <strong>05xxxxxxxx</strong></div>
        </div> --}}

        <!-- جدول المنتجات -->
        <table>
            <thead>
                <tr>
                    <th>م</th>
                    <th>اسم الصنف</th>
                    <th> الوحده</th>
                    <th class="px-2 text-center">الكمية</th>
                    <th>سعر الوحده</th>
                    <th>المخزن</th>
                    <th>الإجمالي</th>
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
        <hr class="border-dashed border-gray-300 my-2">
        <!-- المجاميع -->
        <div class="totals">
            <div class="total-row">
                <span>المجموع قبل الخصم:</span>
                <span>{{ number_format($net_total_after_discount) ?? 0 }}</span>
            </div>
            <div class="grand-total total-row">
                <span>الإجمالي:</span>
                <span>{{ number_format($net_total_after_discount) ?? 0 }}
                    <p class="text-sm">{{ $priceInWords }}</p>
                </span>
            </div>
        </div>

        <!-- طريقة الدفع -->
        {{--         <div class="payment-method">
            <div class="total-row">
                <span>المبلغ المدفوع:</span>
                <span>160.00 ريال</span>
            </div>
            <div class="total-row">
                <span>المبلغ المتبقي:</span>
                <span>3.60 ريال</span>
            </div>
        </div> --}}

        <!-- الباركود -->
        <div class="barcode">
            *{{ $DataPurchaseInvoice->sales_invoice_id ?? __('غير متوفر') }}*
        </div>

        <!-- تذييل الفاتورة -->
        <div class="footer">
            <p>شكراً لزيارتكم - نتشرف بخدمتكم دائماً</p>
            <p>هذه فاتورة ضريبية مبسطة</p>
            <p>يرجى الاحتفاظ بالفاتورة لاستبدال أو إرجاع السلع خلال 14 يوم</p>
        </div>
    </div>

    <!-- أزرار الطباعة - لن تظهر في المطبوعة -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="printAndClose()"
            style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            طباعة الفاتورة
        </button>
        <button onclick="closeWindow()"
            style="padding: 8px 16px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;">
            إلغاء الطباعة
        </button>
    </div>

    <script>
        function printAndClose() {
            window.print();
            setTimeout(() => {
                window.close();
            }, 500);
        }

        function closeWindow() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.close();
            }
        }
    </script>
</body>

</html>
