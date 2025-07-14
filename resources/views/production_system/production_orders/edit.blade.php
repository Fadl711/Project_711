@extends('production_system.index')
@section('productionSystem')
<div class="container mx-auto px-4 py-1">
    <div class="container mx-auto px-4 py-1">
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
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">تعديل أمر إنتاج #{{ $order->order_number }}</h2>
        
        <form action="{{ route('production-orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- معلومات أساسية -->
            
                <!-- التواريخ والحالة -->
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">التاريخ  المخطط للبدء<span class="text-red-500">*</span></label>
                            <input type="date" id="start_date" name="start_date" 
 value="{{ old('start_date', $order->start_date ? \Carbon\Carbon::parse($order->start_date)->format('Y-m-d') : '') }}"    
                                   class="inputSale"
                                   required>
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">التاريخ المخطط للانتهاء <span class="text-red-500">*</span></label>
                            <input type="date" id="end_date" name="end_date" 
 value="{{ old('end_date', $order->end_date ? \Carbon\Carbon::parse($order->end_date)->format('Y-m-d') : '') }}"         
                                   class="inputSale"
                                   required>
                        </div>
                        <div>
                            <label for="actual_start" class="block text-sm font-medium text-gray-700">تاريخ  البدء الفعلي <span class="text-red-500">*</span></label>
                            <input type="date" id="actual_start" name="actual_start" 
 value="{{ old('actual_start', $order->actual_start ? \Carbon\Carbon::parse($order->actual_start)->format('Y-m-d') : '') }}"                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                        </div>
                        <div>
                            <label for="actual_end" class="block text-sm font-medium text-gray-700">تاريخ  الانتهاء الفعلي <span class="text-red-500">*</span></label>
                            <input type="date" id="actual_end" name="actual_end" 
 value="{{ old('actual_end', $order->actual_end ? \Carbon\Carbon::parse($order->actual_end)->format('Y-m-d') : '') }}"                             
                                   class="inputSale" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">حالة أمر الإنتاج<span class="text-red-500">*</span></label>
                            <select id="status" name="status"
                                   class="inputSale select2">
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">أولوية التنفيذ <span class="text-red-500">*</span></label>
                            <select id="priority" name="priority"
                                   class="inputSale select2">
                                @foreach($priorities as $value => $label)
                                    <option value="{{ $value }}" {{ $order->priority == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- الكميات -->
                <div class="space-y-4">
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700">المنتج المطلوب تصنيعه <span class="text-red-500">*</span></label>
                        <select id="product_id" name="product_id" required
                                   class="inputSale select2">
                            @foreach($products as $product)
                                <option value="{{ $product->product_id }}" {{ $order->product_id == $product->product_id ? 'selected' : '' }}>
                                    {{ $product->product_name }} ({{ $product->product_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="line_id" class="block text-sm font-medium text-gray-700">خط الإنتاج <span class="text-red-500">*</span></label>
                        <select id="line_id" name="line_id" required
                                   class="inputSale select2">
                            @foreach($lines as $line)
                                <option value="{{ $line->id }}" {{ $order->line_id == $line->id ? 'selected' : '' }}>
                                    {{ $line->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                             <div>
                        <label for="planned_quantity" class="block text-sm font-medium text-gray-700">الكمية المخططة <span class="text-red-500">*</span></label>
                        <input type="number" id="planned_quantity" name="planned_quantity" step="0.001" min="0.001"
                               value="{{ old('planned_quantity', $order->planned_quantity) }}"
                                   class="inputSale" required>
                    </div>

                    <div>
                        <label for="produced_quantity" class="block text-sm font-medium text-gray-700">الكمية المنتجة</label>
                        <input type="number" id="produced_quantity" name="produced_quantity" step="0.001" min="0"
                               value="{{ old('produced_quantity', $order->produced_quantity) }}"
                                   class="inputSale">
                    </div>
                    <div>
                        <label for="approved_quantity" class="block text-sm font-medium text-gray-700"> الكمية المعتمدة</label>
                        <input type="number" id="approved_quantity" name="approved_quantity" step="0.001" min="0"
                               value="{{ old('approved_quantity', $order->approved_quantity) }}"
                                   class="inputSale">
                    </div>
                    </div>
                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div>
                            <label for="estimated_cost" class="block text-sm font-medium text-gray-700">التكلفة التقديرية <span class="text-red-500">*</span></label>
                            <input type="number" id="estimated_cost" name="estimated_cost" step="0.01" min="0"
                                   value="{{ old('estimated_cost', $order->estimated_cost) }}"
                                   class="inputSale"
                                   required>
                        </div>
                        
                        <div>
                            <label for="actual_cost" class="block text-sm font-medium text-gray-700">التكلفة الفعلية</label>
                            <input type="number" id="actual_cost" name="actual_cost" step="0.01" min="0"
                                   value="{{ old('actual_cost', $order->actual_cost) }}"
                                   class="inputSale">
                        </div>
                         <div>
                            <label for="sales_order_id" class="block text-sm font-medium text-gray-700"> أمر البيع المرتبط</label>
                            <input type="number" id="sales_order_id" name="sales_order_id" step="0.01" min="0"
                                   value="{{ old('sales_order_id', $order->sales_order_id) }}"
                                   class="inputSale">
                        </div>
                       
                    </div>

                </div>
                
                <!--  والملاحظات -->
                <div class="space-y-4">
                        
                    
                    <div id="cancellation_reason_div" style="{{ $order->status !== 'canceled' ? 'display: none;' : '' }}">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">سبب الإلغاء</label>
                        <textarea id="cancellation_reason" name="cancellation_reason" rows="2"
                             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('cancellation_reason', $order->cancellation_reason) }}</textarea>
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $order->notes) }}</textarea>
                    </div>
            </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('production-orders.show', $order->id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    إلغاء
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
    $('.select2').select2();
});
    // إظهار/إخفاء حقل سبب الإلغاء عند اختيار الحالة "ملغى"
    document.getElementById('status').addEventListener('change', function() {
        const reasonDiv = document.getElementById('cancellation_reason_div');
        if (this.value === 'canceled') {
            reasonDiv.style.display = 'block';
        } else {
            reasonDiv.style.display = 'none';
        }
    });

    // التحقق من أن تاريخ الانتهاء بعد تاريخ البدء
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDateInput = document.getElementById('end_date');
        
        if (startDate && endDateInput.value && new Date(endDateInput.value) < startDate) {
            endDateInput.valueAsDate = startDate;
        }
    });
</script>
@endsection