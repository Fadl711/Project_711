@extends('production_system.index')
@section('productionSystem')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">أوامر الإنتاج</h1>
            <a href="{{ route('production-orders.create') }}"
                class="ajax-link bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                إنشاء أمر جديد
            </a>
        </div>

        <!-- فلترة البحث -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form action="{{ route('production_orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">الكل</option>
                        @foreach (['draft', 'planned', 'in_progress', 'paused', 'completed', 'canceled'] as $status)
                            <option value="{{ $status }}" @if (request('status') == $status) selected @endif>
                                {{ __("production.status.$status") }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">الأولوية</label>
                    <select name="priority" id="priority" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">الكل</option>
                        @foreach (['low', 'medium', 'high', 'urgent'] as $priority)
                            <option value="{{ $priority }}" @if (request('priority') == $priority) selected @endif>
                                {{ __("production.priority.$priority") }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="رقم الأمر..." class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md w-full">
                        تصفية
                    </button>
                </div>
            </form>
        </div>

        <!-- جدول أوامر الإنتاج -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم
                                الأمر</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المنتج</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الكمية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">خط
                                الإنتاج</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ البدء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                العمليات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($productionOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $order->order_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->product->product_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->produced_quantity }} / {{ $order->planned_quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->line->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full
                                @if ($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'canceled') bg-red-100 text-red-800
                                @elseif($order->status == 'in_progress') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">

                                        {{ \App\Models\ProductionOrder::getStatuses()[$order->status] }} </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->start_date)->format('Y-m-d') }}</td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('production-orders.show', $order) }}"
                                        class="text-blue-600 hover:text-blue-900 mx-1">عرض</a>
                                    <a href="{{ route('production-orders.edit', $order) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mx-1">تعديل</a>
                                    <form action="{{ route('production-orders.destroy', $order) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 mx-1"
                                            onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">لا توجد أوامر إنتاج
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                {{ $productionOrders->links() }}
            </div>
        </div>
    </div>
@endsection
