@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل BOM</h5>
            <div>
                <a href="{{ route('product-boms.edit', $bom->bom_id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> تعديل
                </a>
                <a href="{{ route('product-boms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">المنتج النهائي:</th>
                            <td>{{ $bom->product->name }}</td>
                        </tr>
                        <tr>
                            <th>المادة الخام:</th>
                            <td>{{ $bom->material->name }}</td>
                        </tr>
                        <tr>
                            <th>الكمية المطلوبة:</th>
                            <td>{{ number_format($bom->quantity, 3) }}</td>
                        </tr>
                        <tr>
                            <th>وحدة القياس:</th>
                            <td>{{ $bom->unit->name }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">نسبة الهدر:</th>
                            <td>{{ $bom->waste_factor }}%</td>
                        </tr>
                        <tr>
                            <th>المخزن الافتراضي:</th>
                            <td>{{ $bom->warehouse->name }}</td>
                        </tr>
                        <tr>
                            <th>التكلفة المعيارية:</th>
                            <td>{{ number_format($bom->standard_cost, 5) }}</td>
                        </tr>
                        <tr>
                            <th>الحالة:</th>
                            <td>
                                <span class="badge badge-{{ $bom->is_active ? 'success' : 'danger' }}">
                                    {{ $bom->is_active ? 'مفعل' : 'غير مفعل' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <h5>تاريخ الإنشاء والتحديث</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">تاريخ الإنشاء:</th>
                        <td>{{ $bom->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>آخر تحديث:</th>
                        <td>{{ $bom->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection