@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <h1 class="mb-4">قائمة التكاليف الصناعية</h1>

    <div class="card shadow-sm mb-4  ">
    <div class="card shadow-sm mb-4 grid grid-cols-2 gap-4  print:hidden">
       
        <div class="card ">
        <div class="card-header">
إضافة        </div>
        <div class="card-body  grid grid-cols-2 gap-4">
             <a href="{{ route('manufacturing-costs.create') }}" class="btn btn-outline-secondary">
                <i class="fas fa-plus"></i> إضافة تكلفة جديدة
            </a>
        </div>
    </div>

        <div class="card ">
        <div class="card-header">
            تحليل سريع للتكاليف
        </div>
        <div class="card-body  grid grid-cols-2 gap-4">
            <a href="{{ route('manufacturing-costs.analysis') }}" class="btn btn-outline-secondary">
                <i class="fas fa-chart-pie"></i> عرض تحليل التكاليف
            </a>
        </div>
    </div>
    </div>
        <div class="card-body">
             <span>جميع التكاليف</span>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>أمر الإنتاج</th>
                            <th>نوع التكلفة</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                            <th>مسجل بواسطة</th>
                            <th class=" print:hidden">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($costs as $manufacturingCost)
                        <tr>
                            <td>{{ $manufacturingCost->id }}</td>
                            <td>{{ $manufacturingCost->productionOrder->order_number}}</td>
                            <td>
                              <span class="">

    {{$manufacturingCost->getCostTypes()[$manufacturingCost->cost_type] }}
</span>
                {{-- {{ $order->getStatuses()[$order->status] }} --}}

                            </td>
                            <td>{{ number_format($manufacturingCost->amount, 2) }}</td>
                            <td>{{ $manufacturingCost->cost_date->format('Y-m-d') }}</td>
                            <td>{{ $manufacturingCost->creator->name }}</td>
                            <td class=" print:hidden">
                                <a href="{{ route('manufacturing-costs.show', $manufacturingCost) }}" class="px-4" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('manufacturing-costs.edit', $manufacturingCost) }}" class="px-4" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $costs->links() }}
            </div>
        </div>
    </div>

    
</div>
@endsection