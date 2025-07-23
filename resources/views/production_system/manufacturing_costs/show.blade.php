@extends('production_system.index')
@section('productionSystem')
<div class=" bg-white">
    <div class=" ">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between ">
                    <span>تفاصيل التكلفة الصناعية #{{ $manufacturingCost->cost_id }}</span>
                    <div>
                        <a href="{{ route('manufacturing-costs.edit', $manufacturingCost) }}" class="  print:hidden  btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('manufacturing-costs.index') }}" class="  print:hidden  btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>معلومات أساسية</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">أمر الإنتاج</th>
                                    <td>{{ $manufacturingCost->productionOrder->order_number }}</td>
                                </tr>
                                <tr>
                                    <th>نوع التكلفة</th>
                                    <td>
                                        <span class="">
                                            {{ $manufacturingCost->getCostTypes()[$manufacturingCost->cost_type] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>المبلغ</th>
                                    <td>{{ number_format($manufacturingCost->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>التاريخ</th>
                                    <td>{{ $manufacturingCost->cost_date->format('Y-m-d') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>المعلومات المحاسبية</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">حساب دفتر الأستاذ</th>
                                    <td>{{ $manufacturingCost->glAccount->sub_name }} - {{ $manufacturingCost->glAccount->sub_name }}</td>
                                </tr>
                                <tr>
                                    <th>مسجل بواسطة</th>
                                    <td>{{ $manufacturingCost->creator->name }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ التسجيل</th>
                                    <td>{{ $manufacturingCost->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث</th>
                                    <td>{{ $manufacturingCost->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5>تفاصيل إضافية</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    @if($manufacturingCost->description)
                                        <p>{{ $manufacturingCost->description }}</p>
                                    @else
                                        <p class="text-muted">لا يوجد وصف</p>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    @if($manufacturingCost->details)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>بيانات إضافية</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <pre>{{ json_encode($manufacturingCost->details, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection