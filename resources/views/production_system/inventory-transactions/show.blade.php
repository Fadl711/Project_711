@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل حركة المخزون #{{ $transaction->id }}</h5>
            <div>
                <a href="{{ route('inventory-transactions.edit', $transaction->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> تعديل
                </a>
                <a href="{{ route('inventory-transactions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">نوع الحركة:</th>
                            <td>{{ \App\Models\InventoryTransaction::TRANSACTION_TYPES[$transaction->transaction_type] ?? $transaction->transaction_type }}</td>
                        </tr>
                        <tr>
                            <th>التاريخ والوقت:</th>
                            <td>{{ $transaction->transaction_date->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>المادة/المنتج:</th>
                            <td>{{ $transaction->item->product_name }}</td>
                        </tr>
                        <tr>
                            <th>الكمية:</th>
                            <td>{{ number_format($transaction->quantity, 3) }}</td>
                        </tr>
                        <tr>
                            <th>التكلفة للوحدة:</th>
                            <td>{{ number_format($transaction->unit_cost, 5) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">التكلفة الإجمالية:</th>
                            <td>{{ number_format($transaction->total_cost, 2) }}</td>
                        </tr>
                        <tr>
                            <th>المخزن:</th>
                            <td>{{ $transaction->warehouse->sub_name }}</td>
                        </tr>
                        <tr>
                            <th>الموقع:</th>
                            <td>{{ $transaction->location?->name_the_known ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>أمر الإنتاج:</th>
                            <td>{{ $transaction->productionOrder?->order_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>مسجل بواسطة:</th>
                            <td>{{ $transaction->creator->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <h5>ملاحظات</h5>
                <div class="p-3 bg-light rounded">
                    {{ $transaction->notes ?? 'لا توجد ملاحظات' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection