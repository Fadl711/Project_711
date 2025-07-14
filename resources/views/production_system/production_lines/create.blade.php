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
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">إضافة خط إنتاج جديد</h1>
        {{-- <a href="{{ route('production_lines.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">رجوع</a> --}}
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('production_lines.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- الاسم -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">اسم الخط *</label>
                    <input type="text" name="name" id="name" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- الكود -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">كود الخط *</label>
                    <input type="text" name="code" id="code" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('code') }}">
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- الوصف -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- القسم -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700">القسم *</label>
                    <select name="department_id" id="department_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">اختر القسم</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- المصنع -->
                <div>
                    <label for="plant_id" class="block text-sm font-medium text-gray-700">المصنع *</label>
                    <select name="plant_id" id="plant_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">اختر المصنع</option>
                        @foreach($plants as $plant)
                            <option value="{{ $plant->id }}" {{ old('plant_id') == $plant->id ? 'selected' : '' }}>{{ $plant->name }}</option>
                        @endforeach
                    </select>
                    @error('plant_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- مستوى الأتمتة -->
                <div>
                    <label for="automation_level" class="block text-sm font-medium text-gray-700">مستوى الأتمتة *</label>
                    <select name="automation_level" id="automation_level" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="manual" {{ old('automation_level') == 'manual' ? 'selected' : '' }}>يدوي</option>
                        <option value="semi-auto" {{ old('automation_level') == 'semi-auto' ? 'selected' : '' }}>شبه آلي</option>
                        <option value="full-auto" {{ old('automation_level') == 'full-auto' ? 'selected' : '' }}>كامل الآلي</option>
                    </select>
                    @error('automation_level')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حالة التشغيل -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">حالة التشغيل *</label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>صيانة</option>
                        <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>معطل نهائي</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- السعة التصميمية -->
                <div>
                    <label for="design_capacity" class="block text-sm font-medium text-gray-700">السعة التصميمية (وحدة/ساعة) *</label>
                    <input type="number" step="0.01" name="design_capacity" id="design_capacity" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('design_capacity') }}">
                    @error('design_capacity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- السعة الفعلية -->
                <div>
                    <label for="current_capacity" class="block text-sm font-medium text-gray-700">السعة الفعلية (وحدة/ساعة) *</label>
                    <input type="number" step="0.01" name="current_capacity" id="current_capacity" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('current_capacity') }}">
                    @error('current_capacity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- تاريخ التشغيل -->
                <div>
                    <label for="commissioning_date" class="block text-sm font-medium text-gray-700">تاريخ التشغيل الأول *</label>
                    <input type="date" name="commissioning_date" id="commissioning_date" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('commissioning_date') }}">
                    @error('commissioning_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- التكلفة التشغيلية -->
                <div>
                    <label for="hourly_operating_cost" class="block text-sm font-medium text-gray-700">التكلفة التشغيلية للساعة *</label>
                    <input type="number" step="0.01" name="hourly_operating_cost" id="hourly_operating_cost" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('hourly_operating_cost') }}">
                    @error('hourly_operating_cost')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- تاريخ آخر معايرة -->
<div>
    <label for="last_calibration_date" class="block text-sm font-medium text-gray-700">تاريخ آخر معايرة</label>
    <input type="date" name="last_calibration_date" id="last_calibration_date"
           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
           value="{{ old('last_calibration_date') }}">
    @error('last_calibration_date')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- استهلاك الطاقة -->
<div>
    <label for="energy_consumption" class="block text-sm font-medium text-gray-700">استهلاك الطاقة (ك.و.س/ساعة)</label>
    <input type="number" step="0.01" name="energy_consumption" id="energy_consumption"
           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
           value="{{ old('energy_consumption') }}">
    @error('energy_consumption')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- المواصفات الفنية -->
<div class="md:col-span-2">
    <label for="specifications" class="block text-sm font-medium text-gray-700">المواصفات الفنية (JSON)</label>
    <textarea name="specifications" id="specifications" rows="3"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder='{"مثال": "قيمة"}'>{{ old('specifications') }}</textarea>
    @error('specifications')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- متطلبات السلامة -->
<div class="md:col-span-2">
    <label for="safety_requirements" class="block text-sm font-medium text-gray-700">متطلبات السلامة (JSON)</label>
    <textarea name="safety_requirements" id="safety_requirements" rows="3"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder='{"مثال": "قيمة"}'>{{ old('safety_requirements') }}</textarea>
    @error('safety_requirements')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
            </div>

            <div class="mt-8">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection