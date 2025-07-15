@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">حركات المخزون</h5>
            <a href="{{ route('inventory-transactions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> حركة جديدة
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('inventory-transactions.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">نوع الحركة</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">الكل</option>
                            @foreach(\App\Models\InventoryTransaction::TRANSACTION_TYPES as $key => $value)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="warehouse">المخزن</label>
                        <select name="warehouse" id="warehouse" class="form-control">
                            <option value="">الكل</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->sub_account_id }}" {{ request('warehouse') == $warehouse->sub_account_id ? 'selected' : '' }}>{{ $warehouse->sub_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date">من تاريخ</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date">إلى تاريخ</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> تصفية
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>التاريخ</th>
                            <th>نوع الحركة</th>
                            <th>المادة/المنتج</th>
                            <th class="text-right">الكمية</th>
                            <th class="text-right">التكلفة</th>
                            <th>المخزن</th>
                            <th>أمر الإنتاج</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaction->transaction_date->format('Y-m-d H:i') }}</td>
                            <td>{{ \App\Models\InventoryTransaction::TRANSACTION_TYPES[$transaction->transaction_type] ?? $transaction->transaction_type }}</td>
                            <td>{{ $transaction->item->product_name }}</td>
                            <td class="text-right">{{ number_format($transaction->quantity, 3) }}</td>
                            <td class="text-right">{{ number_format($transaction->total_cost, 2) }}</td>
                            <td>{{ $transaction->warehouse->sub_name }}</td>
                            <td>{{ $transaction->productionOrder?->order_number ?? '-' }}</td>
                            <td>
                                <a href="{{ route('inventory-transactions.show', $transaction->id) }}" class="btn btn-sm btn-info" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('edit_transactions')
                                <a href="{{ route('inventory-transactions.edit', $transaction->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection