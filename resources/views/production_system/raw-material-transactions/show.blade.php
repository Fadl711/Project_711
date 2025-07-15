@extends('production_system.index')
@section('productionSystem')
<div class="container px-4 py-5 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">تفاصيل حركة المواد الخام #{{ $rawMaterialTransaction->transaction_id }}</h1>
        <a href="{{ route('raw-material-transactions.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            رجوع
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                معلومات أساسية
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">أمر الإنتاج</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->productionOrder->order_number }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">المادة الخام</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->material->product_name }} ({{ $rawMaterialTransaction->material->product_id }})
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">الكمية المخططة</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->planned_quantity }} {{ $rawMaterialTransaction->material->unit }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">الكمية الفعلية</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->actual_quantity }} {{ $rawMaterialTransaction->material->unit }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">الكمية المرتجعة</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->returned_quantity ?? '0' }} {{ $rawMaterialTransaction->material->unit }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">التكلفة للوحدة</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ number_format($rawMaterialTransaction->unit_cost, 5) }} ر.س
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">التكلفة الإجمالية</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ number_format($rawMaterialTransaction->total_cost, 2) }} ر.س
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">المخزن</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->warehouse->sub_name }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">الموقع</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->location->sub_name ?? 'غير محدد' }} ({{ $rawMaterialTransaction->location->code ?? '--' }})
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">مسؤول الصرف</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->issuedByUser->sub_name }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">تاريخ الصرف</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->issue_date->format('Y-m-d H:i') }}
                    </p>
                </div>
                @if($rawMaterialTransaction->received_by)
                <div>
                    <p class="text-sm font-medium text-gray-500">مسؤول الاستلام</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->receivedByUser->sub_name }}
                    </p>
                </div>
                @endif
                @if($rawMaterialTransaction->return_date)
                <div>
                    <p class="text-sm font-medium text-gray-500">تاريخ الإرجاع</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $rawMaterialTransaction->return_date->format('Y-m-d H:i') }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        @if($rawMaterialTransaction->notes)
        <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                ملاحظات
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $rawMaterialTransaction->notes }}</p>
        </div>
        @endif

        <div class="px-4 py-4 bg-gray-50 flex justify-end">
            <a href="{{ route('raw-material-transactions.edit', $rawMaterialTransaction->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 mr-2">
                تعديل
            </a>
            <form action="{{ route('raw-material-transactions.destroy', $rawMaterialTransaction->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذه الحركة؟');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    حذف
                </button>
            </form>
        </div>
    </div>
</div>
@endsection