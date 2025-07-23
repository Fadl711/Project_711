@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <h1 class="mb-4">تحليل التكاليف الصناعية</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            توزيع التكاليف حسب النوع
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <canvas id="costTypeChart" height="300"></canvas>
                </div>
                <div class="col-md-6">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>نوع التكلفة</th>
                                <th>عدد السجلات</th>
                                <th>إجمالي المبلغ</th>
                                <th>النسبة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = $analysis->sum('total_amount') @endphp
                            @foreach($analysis as $item)
                            <tr>
                                <td>{{ $costTypes[$item->cost_type] }}</td>
                                <td>{{ $item->count }}</td>
                                <td>{{ number_format($item->total_amount, 2) }}</td>
                                <td>{{ $total > 0 ? number_format(($item->total_amount / $total) * 100, 2) : 0 }}%</td>
                            </tr>
                            @endforeach
                            <tr class="table-primary font-weight-bold">
                                <td>الإجمالي</td>
                                <td>{{ $analysis->sum('count') }}</td>
                                <td>{{ number_format($total, 2) }}</td>
                                <td>100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm  print:hidden">
        <div class="card-header">
            خيارات إضافية
        </div>
        <div class="card-body">
            <a href="{{ route('manufacturing-costs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> العودة إلى القائمة
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('costTypeChart').getContext('2d');
        const costData = @json($analysis);
        const costTypes = @json($costTypes);

        const labels = costData.map(item => costTypes[item.cost_type]);
        const data = costData.map(item => item.total_amount);
        const backgroundColors = [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
        ];

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    hoverBackgroundColor: backgroundColors,
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection