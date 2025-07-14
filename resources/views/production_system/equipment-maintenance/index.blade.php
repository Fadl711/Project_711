@extends('production_system.index')
@section('productionSystem')

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">إدارة صيانة المعدات</h1>
        <a href="{{ route('equipment-maintenance.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            جدولة صيانة جديدة
        </a>
    </div>

    <!-- فلترة البحث -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('equipment-maintenance.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="line_id" class="block text-sm font-medium text-gray-700 mb-1">خط الإنتاج</label>
                <select name="line_id" id="line_id" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">الكل</option>
                    @foreach($lines as $line)
                        <option value="{{ $line->line_id }}" @if(request('line_id') == $line->line_id) selected @endif>
                            {{ $line->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">الكل</option>
                    @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" @if(request('status') == $key) selected @endif>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="maintenance_type" class="block text-sm font-medium text-gray-700 mb-1">نوع الصيانة</label>
                <select name="maintenance_type" id="maintenance_type" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">الكل</option>
                    @foreach($types as $key => $type)
                        <option value="{{ $key }}" @if(request('maintenance_type') == $key) selected @endif>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md w-full">
                    تصفية
                </button>
            </div>
        </form>
    </div>

    <!-- جدول عمليات الصيانة -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">كود الصيانة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المعدة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">خط الإنتاج</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ المخطط</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العمليات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($maintenances as $maintenance)
                <tr class="@if($maintenance->isOverdue()) bg-red-50 @endif">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $maintenance->maintenance_code }}
                        @if($maintenance->isOverdue())
                            <span class="text-xs text-red-500">(متأخرة)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $maintenance->equipment_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $maintenance->productionLine->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $types[$maintenance->maintenance_type] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $maintenance->scheduled_date->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($maintenance->status == 'completed') bg-green-100 text-green-800
                            @elseif($maintenance->status == 'canceled') bg-red-100 text-red-800
                            @elseif($maintenance->status == 'in_progress') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $statuses[$maintenance->status] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('equipment-maintenance.show', $maintenance) }}" class="text-blue-600 hover:text-blue-900 mx-1">عرض</a>
                        @if($maintenance->status == 'scheduled')
                            <a href="{{ route('equipment-maintenance.edit', $maintenance) }}" class="text-indigo-600 hover:text-indigo-900 mx-1">تعديل</a>
                            <form action="{{ route('equipment-maintenance.start', $maintenance) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 mx-1">بدء</button>
                            </form>
                        @elseif($maintenance->status == 'in_progress')
                            <button onclick="openCompleteModal({{ $maintenance->maintenance_id }})" class="text-green-600 hover:text-green-900 mx-1">إكمال</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- الترقيم -->
    <div class="mt-4">
        {{ $maintenances->links() }}
    </div>
</div>

<!-- مودال إكمال الصيانة -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">إكمال عملية الصيانة</h3>
            <form id="completeForm" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="actual_cost" class="block text-sm font-medium text-gray-700">التكلفة الفعلية</label>
                    <input type="number" step="0.01" min="0" name="actual_cost" id="actual_cost" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="parts_replaced" class="block text-sm font-medium text-gray-700">الأجزاء المستبدلة (مفصولة بفواصل)</label>
                    <input type="text" name="parts_replaced" id="parts_replaced" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeCompleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        إلغاء
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        تأكيد الإكمال
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCompleteModal(maintenanceId) {
        const form = document.getElementById('completeForm');
        form.action = `/equipment-maintenance/${maintenanceId}/complete`;
        document.getElementById('completeModal').classList.remove('hidden');
    }

    function closeCompleteModal() {
        document.getElementById('completeModal').classList.add('hidden');
    }
</script>
@endsection