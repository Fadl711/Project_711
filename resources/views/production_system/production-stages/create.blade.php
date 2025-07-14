@extends('production_system.index')
@section('productionSystem')
<div class="container mx-auto px-4 py-8">
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
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">إضافة مرحلة إنتاج جديدة</h2>
                <a href="{{ route('production-stages.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    <span class="mr-1">رجوع</span>
                </a>
            </div>
            
            <form action="{{ route('production-stages.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 gap-6 mb-6">
                    <!-- خط الإنتاج -->
                    <div>
                        <label for="line_id" class="block text-sm font-medium text-gray-700 mb-1">خط الإنتاج *</label>
                        <select name="line_id" id="line_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('line_id') border-red-500 @enderror">
                            <option value="">اختر خط إنتاج</option>
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
                    
                    <!-- اسم المرحلة -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">اسم المرحلة *</label>
                        <input type="text" name="name" id="name" required
                               value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- ترتيب المرحلة -->
                    <div>
                        <label for="sequence" class="block text-sm font-medium text-gray-700 mb-1">ترتيب المرحلة *</label>
                        <input type="number" name="sequence" id="sequence" min="1" required
                               value="{{ old('sequence') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('sequence') border-red-500 @enderror">
                        @error('sequence')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- الهدف من المرحلة -->
                    <div>
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">الهدف من المرحلة</label>
                        <textarea name="purpose" id="purpose" rows="2"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('purpose') border-red-500 @enderror">{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- المدة المعيارية -->
                    <div>
                        <label for="standard_duration" class="block text-sm font-medium text-gray-700 mb-1">المدة المعيارية (ثانية/وحدة) *</label>
                        <input type="number" step="0.01" min="0" name="standard_duration" id="standard_duration" required
                               value="{{ old('standard_duration') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('standard_duration') border-red-500 @enderror">
                        @error('standard_duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- النسبة المستهدفة للجودة -->
                    <div>
                        <label for="target_yield" class="block text-sm font-medium text-gray-700 mb-1">النسبة المستهدفة للجودة (%) *</label>
                        <input type="number" step="0.01" min="0" max="100" name="target_yield" id="target_yield" required
                               value="{{ old('target_yield', 100) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('target_yield') border-red-500 @enderror">
                        @error('target_yield')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- أقصى نسبة عيوب مسموح بها -->
                    <div>
                        <label for="max_defect_rate" class="block text-sm font-medium text-gray-700 mb-1">أقصى نسبة عيوب مسموح بها (%) *</label>
                        <input type="number" step="0.01" min="0" max="100" name="max_defect_rate" id="max_defect_rate" required
                               value="{{ old('max_defect_rate') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('max_defect_rate') border-red-500 @enderror">
                        @error('max_defect_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- المعدات المطلوبة -->
                    <div>
                        <label for="required_equipment" class="block text-sm font-medium text-gray-700 mb-1">أكواد المعدات المطلوبة</label>
                        <input type="text" name="required_equipment" id="required_equipment"
                               value="{{ old('required_equipment') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('required_equipment') border-red-500 @enderror"
                               placeholder="مثال: EQ-001, EQ-002">
                        @error('required_equipment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- إعدادات المعدات -->
                    <div>
                        <label for="equipment_settings" class="block text-sm font-medium text-gray-700 mb-1">إعدادات المعدات (JSON)</label>
                        <textarea name="equipment_settings" id="equipment_settings" rows="3"
                                  class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('equipment_settings') border-red-500 @enderror"
                                  placeholder='{"speed": 100, "temperature": 25}'>{!! old('equipment_settings') !!}</textarea>
                        @error('equipment_settings')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- معايير الجودة -->
                    <div>
                        <label for="quality_parameters" class="block text-sm font-medium text-gray-700 mb-1">معايير الجودة (JSON)</label>
                        <textarea name="quality_parameters" id="quality_parameters" rows="3"
                                  class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('quality_parameters') border-red-500 @enderror"
                                  placeholder='{"tolerance": 0.05, "pressure": 2.5}'>{!! old('quality_parameters') !!}</textarea>
                        @error('quality_parameters')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- تعليمات الفحص -->
                    <div>
                        <label for="inspection_instructions" class="block text-sm font-medium text-gray-700 mb-1">تعليمات الفحص</label>
                        <textarea name="inspection_instructions" id="inspection_instructions" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('inspection_instructions') border-red-500 @enderror">{{ old('inspection_instructions') }}</textarea>
                        @error('inspection_instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- حالة المرحلة -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('is_active') border-red-500 @enderror">
                        <label for="is_active" class="mr-2 block text-sm text-gray-700">مرحلة نشطة</label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- زر الحفظ -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>حفظ المرحلة</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection