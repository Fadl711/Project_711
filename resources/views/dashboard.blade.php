<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container mx-auto p-4">
                        <h2 class="text-2xl font-bold mb-4">Payment Chart</h2>
                        <div class="bg-white shadow-md rounded-lg p-4">
                            <canvas id="paymentChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    @php
$totalPayments = [
    'customers' => 100,
    'DefaultCustomer' => 4545,
    'DefaultComer' => -4545,
    'Currency_name' => 545,
    'MainAccounts' => 454,
    'financial_account' => 4545,
    'financialts' => 7788,
    'financia' => 0,
];
@endphp



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('paymentChart').getContext('2d');

    // تحويل البيانات من PHP إلى JavaScript
    const totalPayments = {!! json_encode($totalPayments) !!}; // استخدام json_encode لتحويل المصفوفة إلى JSON

    const paymentChart = new Chart(ctx, {
        type: 'line', // أو 'bar' حسب نوع المخطط الذي تريده
        data: {
            labels: Object.keys(totalPayments), // استخدام مفاتيح المصفوفة كملصقات
            datasets: [{
                label: 'Payments',
                data: Object.values(totalPayments), // استخدام القيم في المصفوفة
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true, // لملء المنطقة تحت الخط
                datalabels: {
                    display: true,
                    align: 'end',
                    anchor: 'end',
                    formatter: (value) => {
                        return value; // عرض القيمة
                    }
                },
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    color: '#36A2EB', // لون النص
                    font: {
                        weight: 'bold',
                        size: 12,
                    },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Categories'
                    }
                }
            }
        },
        plugins: [ChartDataLabels] // إضافة البيانات كـ plugin
    });
</script>
</x-app-layout>
