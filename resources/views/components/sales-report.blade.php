<!-- تضمين مكتبة Chart.js في بداية الملف -->
<script src="{{ asset('assets/chart.js/dist/chart.umd.js') }}"></script>

@php
    $months = [
        '01' => 'يناير',
        '02' => 'فبراير',
        '03' => 'مارس',
        '04' => 'أبريل',
        '05' => 'مايو',
        '06' => 'يونيو',
        '07' => 'يوليو',
        '08' => 'أغسطس',
        '09' => 'سبتمبر',
        '10' => 'أكتوبر',
        '11' => 'نوفمبر',
        '12' => 'ديسمبر'
    ];
@endphp

<style>
    @media print {
        .chart-container {
            width: 100% !important;
            height: 250px !important;
            page-break-inside: avoid;
            margin-bottom: 20px;
        }
        canvas {
            max-width: 100%;
            height: auto !important;
        }
        .product-section {
            page-break-inside: avoid;
            margin-bottom: 30px;
        }
    }
</style>

<div class="container mx-auto p-0">
    @if($CostmerName != "")
    <h2 class="text-xl font-bold mb-4 text-center">{{ $CostmerName }}</h2>
    @endif



    @foreach($dataProducts as $index => $product)
        <div class="product-section mb-6 p-4 rounded">
            <h2 class="text-xl font-bold mb-4 text-center">{{ $product['productName'] }}</h2>

            <!-- بيانات المبيعات -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="p-3 bg-blue-100 rounded">
                    <p>إجمالي الأرباح (مبيعات): {{ number_format($product['salesProfit4'] - $product['salesDiscount4'], 2) }}</p>
                    <p>إجمالي الخصومات (مبيعات): {{ number_format($product['salesDiscount4'], 2) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded">
                    <p>إجمالي الأرباح (مرتجع): {{ number_format($product['salesProfit5'] - $product['salesDiscount5'], 2) }}</p>
                    <p>إجمالي الخصومات (مرتجع): {{ number_format($product['salesDiscount5'], 2) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">


            <!-- رسم بياني للمبيعات الشهرية -->
            <div class="chart-container w-full mb-4" style="height: 200px;">
                <canvas id="monthlyChart{{ $index }}"></canvas>
            </div>

            <!-- رسم بياني للمبيعات السنوية -->
            <div class="chart-container w-full" style="height: 200px;">
                <canvas id="yearlyChart{{ $index }}"></canvas>
            </div>
        </div>
        </div>
    @endforeach
</div>

<script>
    // تعريف أسماء الأشهر
    const months = {!! json_encode($months) !!};

    // تحويل البيانات من PHP إلى JavaScript
    const productsData = {!! json_encode($dataProducts) !!};

    // انتظر حتى يتم تحميل الصفحة بالكامل
    window.addEventListener('load', function() {
        productsData.forEach((product, index) => {
            // إعداد المخطط الشهري
            const monthlyCtx = document.getElementById('monthlyChart' + index);
            if (monthlyCtx) {
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: product.dailyTotals.map(item => months[item.month] || item.month),
                        datasets: [{
                            label: 'المبيعات الشهرية',
                            data: product.dailyTotals.map(item => item.total),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // إعداد المخطط السنوي
            const yearlyCtx = document.getElementById('yearlyChart' + index);
            if (yearlyCtx) {
                new Chart(yearlyCtx, {
                    type: 'line',
                    data: {
                        labels: product.dailyTotal.map(item => '20' + item.months),
                        datasets: [{
                            label: 'المبيعات السنوية',
                            data: product.dailyTotal.map(item => item.totals),
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    });
</script>
