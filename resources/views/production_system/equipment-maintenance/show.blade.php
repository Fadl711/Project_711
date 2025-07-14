@extends('production_system.index')
@section('productionSystem')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- رأس الصفحة -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">تفاصيل الصيانة: {{ $equipmentMaintenance->maintenance_code }}</h2>
                <span class="px-3 py-1 text-sm rounded-full 
                    @if($equipmentMaintenance->status == 'completed') bg-green-100 text-green-800
                    @elseif($equipmentMaintenance->status == 'canceled') bg-red-100 text-red-800
                    @elseif($equipmentMaintenance->status == 'in_progress') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ $statuses[$equipmentMaintenance->status] }}
                    @if($equipmentMaintenance->isOverdue())
                        <span class="text-xs text-red-500">(متأخرة)</span>
                    @endif
                </span>
            </div>
            
            <!-- محتوى الصفحة -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- المعلومات الأساسية -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">المعلومات الأساسية</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">خط الإنتاج</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipmentMaintenance->productionLine->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">المعدة</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipmentMaintenance->equipment_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">نوع الصيانة</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $types[$equipmentMaintenance->maintenance_type] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">الفني المسؤول</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipmentMaintenance->technician->name }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- التواريخ والتكاليف -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">الجدول الزمني والتكاليف</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">التاريخ المخطط</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipmentMaintenance->scheduled_date->format('Y-m-d H:i') }}</p>
                            </div>
                            @if($equipmentMaintenance->start_time)
                            <div>
                                <p class="text-sm text-gray-500">تاريخ البدء</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipmentMaintenance->start_time->format('Y-m-d H:i') }}</p>
                            </div>
                            @endif
                            @if($equipmentMaintenance->end_time)
                            <div>
                                <p class="text-sm text-gray-500">تاريخ الانتهاء</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipmentMaintenance->end_time->format('Y-m-d H:i') }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500">التكلفة التقديرية</p>
                                <p class="mt-1 text-sm text-gray-900">{{ number_format($equipmentMaintenance->estimated_cost, 2) }} ر.س</p>
                            </div>
                            @if($equipmentMaintenance->actual_cost)
                            <div>
                                <p class="text-sm text-gray-500">التكلفة الفعلية</p>
                                <p class="mt-1 text-sm text-gray-900">{{ number_format($equipmentMaintenance->actual_cost, 2) }} ر.س</p>
                            </div>
                            @endif
                            @if($equipmentMaintenance->downtime_hours)
                            <div>
                                <p class="text-sm text-gray-500">وقت التوقف</p>
                                <p class="mt-1 text-sm text-gray-900">{{ number_format($equipmentMaintenance->downtime_hours, 2) }} ساعة</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- وصف العملية -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">وصف العملية</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $equipmentMaintenance->description }}</p>
                    </div>
                </div>
                
                <!-- الأجزاء المستبدلة -->
                @if($equipmentMaintenance->parts_replaced)
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">الأجزاء المستبدلة</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="list-disc list-inside text-sm text-gray-800">
                            @foreach($equipmentMaintenance->parts_replaced as $part)
                                <li>{{ $part }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                
                <!-- ملاحظات -->
                @if($equipmentMaintenance->notes)
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">ملاحظات</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $equipmentMaintenance->notes }}</p>
                    </div>
                </div>
                @endif
                
                <!-- التوقيعات -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if($equipmentMaintenance->approved_by)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">المعتمد</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-800">{{ $equipmentMaintenance->approver->name }}</p>
                            <p class="text-xs text-gray-500 mt-2">بتاريخ: {{ $equipmentMaintenance->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($equipmentMaintenance->verified_by)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">المدقق</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-800">{{ $equipmentMaintenance->verifier->name }}</p>
                            <p class="text-xs text-gray-500 mt-2">بتاريخ: {{ $equipmentMaintenance->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- تذييل الصفحة -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('equipment-maintenance.index') }}" class="text-gray-600 hover:text-gray-900">
                    رجوع إلى القائمة
                </a>
                
                <div>
                    @if($equipmentMaintenance->status == 'scheduled')
                        <a href="{{ route('equipment-maintenance.edit', $equipmentMaintenance) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                            تعديل
                        </a>
                        <form action="{{ route('equipment-maintenance.start', $equipmentMaintenance) }}" method="POST" class="inline mr-4">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-900">
                                بدء الصيانة
                            </button>
                        </form>
                        <form action="{{ route('equipment-maintenance.cancel', $equipmentMaintenance) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من إلغاء هذه الصيانة؟')">
                                إلغاء
                            </button>
                        </form>
                    @elseif($equipmentMaintenance->status == 'in_progress')
                        <button onclick="openCompleteModal()" class="text-green-600 hover:text-green-900 mr-4">
                            إكمال الصيانة
                        </button>
                        <form action="{{ route('equipment-maintenance.cancel', $equipmentMaintenance) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من إلغاء هذه الصيانة؟')">
                                إلغاء
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال إكمال الصيانة -->
@if($equipmentMaintenance->status == 'in_progress')
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">إكمال عملية الصيانة</h3>
            <form action="{{ route('equipment-maintenance.complete', $equipmentMaintenance) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="actual_cost" class="block text-sm font-medium text-gray-700">التكلفة الفعلية *</label>
                    <input type="number" step="0.01" min="0" name="actual_cost" id="actual_cost" required 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="parts_replaced" class="block text-sm font-medium text-gray-700">الأجزاء المستبدلة (مفصولة بفواصل)</label>
                    <input type="text" name="parts_replaced" id="parts_replaced" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
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
    function openCompleteModal() {
        document.getElementById('completeModal').classList.remove('hidden');
    }

    function closeCompleteModal() {
        document.getElementById('completeModal').classList.add('hidden');
    }
</script>
@endif
@endsection