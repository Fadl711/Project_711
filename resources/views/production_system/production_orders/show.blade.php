@extends('production_system.index')
@section('productionSystem')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- رأس البطاقة -->
        <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                أمر إنتاج #{{ $order->order_number }}
            </h2>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->getStatusClass() }}">
                {{ $order->getStatuses()[$order->status] }}
            </span>
        </div>

        <!-- محتوى البطاقة -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- المعلومات الأساسية -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">المعلومات الأساسية</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">المنتج</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->product->product_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">خط الإنتاج</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->line->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">الأولوية</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->getPriorities()[$order->priority] }}</p>
                        </div>
                    </div>
                </div>

                <!-- الكميات والتكاليف -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">الكميات والتكاليف</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">الكمية المخططة</p>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($order->planned_quantity, 3) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">الكمية المنتجة</p>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($order->produced_quantity, 3) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">التكلفة التقديرية</p>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($order->estimated_cost, 2) }} ر.س</p>
                        </div>
                    </div>
                </div>

                <!-- التواريخ -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">التواريخ</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">تاريخ البدء المخطط</p>
                            <p class="mt-1 text-sm text-gray-900">{{\Carbon\Carbon::parse($order->start_date)->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">تاريخ الانتهاء المخطط</p>
                            <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->end_date)->format('Y-m-d') }}</p>
                        </div>
                        @if($order->actual_start)
                        <div>
                            <p class="text-sm text-gray-500">تاريخ البدء الفعلي</p>
                            <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->actual_start)->format('Y-m-d H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- معلومات إضافية -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">معلومات إضافية</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">منشئ الأمر</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->creator->name }}</p>
                        </div>
                        @if($order->approved_by)
                        <div>
                            <p class="text-sm text-gray-500">المعتمد</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->approver->name }}</p>
                        </div>
                        @endif
                        @if($order->notes)
                        <div>
                            <p class="text-sm text-gray-500">ملاحظات</p>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- أزرار التحكم -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('production_orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    رجوع
                </a>
                <a href="{{ route('production-orders.edit', $order->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    تعديل
                </a>
            </div>
        </div>
    </div>
</div>
@endsection