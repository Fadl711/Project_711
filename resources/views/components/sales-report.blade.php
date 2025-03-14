<!-- تضمين مكتبة Chart.js في بداية الملف -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<div class="container mx-auto p-4">
    @foreach($dataProducts as $index => $product)
        <div class="mb-8 p-4 border rounded shadow">
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

            <!-- رسم بياني للمبيعات الشهرية -->
            <div class="w-full h-64 mb-4">
                <canvas id="monthlyChart{{ $index }}"></canvas>
            </div>

            <!-- رسم بياني للمبيعات السنوية -->
            <div class="w-full h-64">
                <canvas id="yearlyChart{{ $index }}"></canvas>
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
                        maintainAspectRatio: false,
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
                        maintainAspectRatio: false,
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
