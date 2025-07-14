@extends('production_system.index')
@section('productionSystem')
@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">جدولة صيانة جديدة</h2>
            
            <form action="{{ route('equipment-maintenance.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- خط الإنتاج -->
                    <div class="md:col-span-2">
                        <label for="line_id" class="block text-sm font-medium text-gray-700 mb-1">خط الإنتاج *</label>
                        <select name="line_id" id="line_id" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">اختر خط الإنتاج</option>
                            @foreach($lines as $line)
                                <option value="{{ $line->id }}" @if(old('line_id') == $line->id) selected @endif>
                                    {{ $line->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('line_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- المعدة -->
                    <div class="md:col-span-2">
                        <label for="equipment_code" class="block text-sm font-medium text-gray-700 mb-1">كود المعدة *</label>
                        <input type="text" name="equipment_code" id="equipment_code" required 
                               value="{{ old('equipment_code') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('equipment_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- نوع الصيانة -->
                    <div>
                        <label for="maintenance_type" class="block text-sm font-medium text-gray-700 mb-1">نوع الصيانة *</label>
                        <select name="maintenance_type" id="maintenance_type" required class="w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($types as $key => $type)
                                <option value="{{ $key }}" @if(old('maintenance_type') == $key) selected @endif>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('maintenance_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- كود الصيانة -->
                    <div>
                        <label for="maintenance_code" class="block text-sm font-medium text-gray-700 mb-1">كود الصيانة</label>
                        <input type="text" name="maintenance_code" id="maintenance_code" 
                               value="{{ old('maintenance_code') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('maintenance_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- التاريخ المخطط -->
                    <div>
                        <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-1">التاريخ المخطط *</label>
                        <input type="datetime-local" name="scheduled_date" id="scheduled_date" required 
                               value="{{ old('scheduled_date') }}" min="{{ date('Y-m-d\TH:i') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('scheduled_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- التكلفة التقديرية -->
                    <div>
                        <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-1">التكلفة التقديرية *</label>
                        <input type="number" step="0.01" min="0" name="estimated_cost" id="estimated_cost" required 
                               value="{{ old('estimated_cost') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('estimated_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- الفني المسؤول -->
                    <div>
                        <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-1">الفني المسؤول *</label>
                        <select name="technician_id" id="technician_id" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value=""> اختر الفني </option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->sub_account_id }}" @if(old('technician_id') == $tech->sub_account_id) selected @endif>
                                    {{ $tech->sub_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('technician_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- وصف العملية -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">وصف العملية *</label>
                    <textarea name="description" id="description" rows="3" required 
                              class="w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- الأجزاء المستبدلة -->
                <div class="mb-6">
                    <label for="parts_replaced" class="block text-sm font-medium text-gray-700 mb-1">الأجزاء المستبدلة (مفصولة بفواصل)</label>
                    <input type="text" name="parts_replaced" id="parts_replaced" 
                           value="{{ old('parts_replaced') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('parts_replaced')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- ملاحظات -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="2" 
                              class="w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- زر الحفظ -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        حفظ الصيانة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection