@extends('production_system.index')
@section('productionSystem')
  <style>
        .select2-container--default .select2-selection--single {
            height: 40px;
            /* ارتفاع العنصر الأساسي */
            line-height: 45px;

        }

        .select2-container--default .select2-selection__rendered {
            padding-top: 5px;
            /* تحسين النصوص */
        }
    </style>
<div class="container mx-auto px-1 py-2">
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
    <div class="mx-auto">
        <div class="bg-white rounded-lg shadow-md p-2">
            <h2 class="text-xl font-bold text-gray-800 mb-4">إنشاء أمر إنتاج جديد</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
               <div>
           
            
            <form action="{{ route('production_orders.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                    <!-- المنتج -->
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">المنتج المطلوب تصنيعه  </label>
                        <select name="product_id" id="product_id" required
                            class="w-full select2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">اختر منتج</option>
                            @foreach($products as $product)
                                <option value="{{ $product->product_id }}" @if(old('product_id') == $product->product_id) selected @endif>
                                    {{ $product->product_name }} ({{ $product->product_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- خط الإنتاج -->
                    <div>
                        <label for="line_id" class="block text-sm font-medium text-gray-700 mb-1">خط الإنتاج *</label>
                        <select name="line_id" id="line_id" required
                            class="w-full select2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                    
                    <!-- أمر البيع -->
                    <div>
                        <label for="sales_order_id" class="block text-sm font-medium text-gray-700 mb-1">أمر البيع (اختياري)</label>
                        <select name="sales_order_id" id="sales_order_id"
                            class="w-full select2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">بدون أمر بيع</option>
                            @foreach($salesOrders as $order)
                                <option value="{{ $order->sales_invoice_id }}" @if(old('sales_order_id') == $order->sales_invoice_id) selected @endif>
                                    {{ $order->sales_invoice_id }} - {{ $order->customer->sub_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sales_order_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- الأولوية -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">الأولوية *</label>
                        <select name="priority" id="priority" required
                            class="select2 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(['low' => 'منخفضة', 'medium' => 'متوسطة', 'high' => 'عالية', 'urgent' => 'عاجلة'] as $value => $label)
                                <option value="{{ $value }}" @if(old('priority', 'medium') == $value) selected @endif>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- الكمية المخططة -->
                    <div>
                        <label for="planned_quantity" class="block text-sm font-medium text-gray-700 mb-1">الكمية المخططة *</label>
                        <input type="number" step="0.001" name="planned_quantity" id="planned_quantity" required
                               value="{{ old('planned_quantity') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('planned_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- التكلفة التقديرية -->
                    <div>
                        <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-1">التكلفة التقديرية *</label>
                        <input type="number" step="0.01" name="estimated_cost" id="estimated_cost" required
                               value="{{ old('estimated_cost') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('estimated_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- تاريخ البدء -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ البدء *</label>
                        <input type="date" name="start_date" id="start_date" required
                               value="{{ old('start_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- تاريخ الانتهاء -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ الانتهاء *</label>
                        <input type="date" name="end_date" id="end_date" required
                               value="{{ old('end_date') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- الملاحظات -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- زر الحفظ -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        حفظ أمر الإنتاج
                    </button>
                </div>
            </form>
            </div>
            <div>


               <div class="table-responsive">
    <table class="table table-striped table-hover text-xs" id="boms">
        <thead class="thead-light">
            <tr>
                <th width="50px">#</th>
                <th>المادة الخام</th>
                <th>وحدة القياس</th>
                <th class="text-center">الكمية الأساسية</th>
                <th class="text-center">الكمية الإجمالية</th>
                <th class="text-center">نسبة الهدر</th>
                <th class="text-center">التكلفة للوحدة</th>
                <th class="text-center">التكلفة الإجمالية</th>
                <th class="text-center">الحالة</th>
            </tr>
        </thead>
        <tbody>
            <!-- سيتم ملؤه بواسطة JavaScript -->
        </tbody>
    </table>
</div>

<style>
    #boms tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
        transform: scale(1.005);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    
    #boms th {
        white-space: nowrap;
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
    }
    
    .text-center {
        text-align: center !important;
    }
</style>
            </div>
           
        </div>

       
    </div>
</div>
</div>
    {{-- <script src="{{ url('sales.js') }}"></script> --}}

<script>
    $(document).ready(function() {
    $('.select2').select2();
      $(document).ready(function() {
    // تعريف العناصر المهمة
    const $productSelect = $('#product_id');
    const $plannedQuantity = $('#planned_quantity');
    const $bomsTable = $('#boms tbody');
    
    // حدث تغيير المنتج
    $productSelect.on('change', function() {
        const productId = $(this).val();
        const planned = $plannedQuantity.val() || 1; // القيمة الافتراضية 1 إذا كانت فارغة
        
        if (productId) {
            getProductBoms(productId, planned);
        } else {
            $bomsTable.empty(); // إفراغ الجدول إذا لم يتم اختيار منتج
        }
    });
    
    // حدث تغيير الكمية المخططة
    $plannedQuantity.on('input', function() {
        const productId = $productSelect.val();
        const planned = $(this).val() || 1;
        
        if (productId) {
            getProductBoms(productId, planned);
        }
    });

    // دالة جلب BOMs
    function getProductBoms(productId, plannedQuantity = 1) {
        $.ajax({
            url: "{{ url('/get-product-boms') }}/" + productId,
            method: 'GET',
            data: { productid: productId },
            beforeSend: function() {
                // إظهار مؤشر تحميل
                $bomsTable.html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> جاري التحميل...</td></tr>');
            },
            success: function(response) {
                if (response.boms && response.boms.length > 0) {
                    displayBoms(response.boms, plannedQuantity);
                } else {
                    $bomsTable.html('<tr><td colspan="7" class="text-center">لا توجد بيانات متاحة</td></tr>');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                $bomsTable.html('<tr><td colspan="7" class="text-center text-danger">حدث خطأ أثناء جلب البيانات</td></tr>');
            }
        });
    }

    // دالة عرض BOMs
function displayBoms(boms, plannedQuantity) {
    let rows = '';
    const uniqueBoms = new Set();
    let counter = 1;
    let totalStandard = 0; // تعريف المتغير خارج الحلقة
    
    // دالة مساعدة لتنسيق الأرقام
    const formatNumber = (num) => {
        const numStr = num.toString();
        // إذا كان الرقم يحتوي على كسور أصفار فقط
        if (numStr.includes('.') && parseFloat(numStr.split('.')[1]) === 0) {
            return parseInt(num).toLocaleString(); // عرض كعدد صحيح
        }
        return parseFloat(num).toLocaleString(undefined, {
            minimumFractionDigits: 0,
            maximumFractionDigits: 5
        }); // عرض الرقم مع الكسور
    };

    boms.forEach(function(bom) {
        if (!uniqueBoms.has(bom.id)) {
            uniqueBoms.add(bom.id);
            
            const quantityWithWaste = bom.quantity * (1 + (bom.waste_factor / 100));
            const totalQuantity = (quantityWithWaste * plannedQuantity).toFixed(3);
            const totalCost = (parseFloat(totalQuantity) * parseFloat(bom.standard_cost)).toFixed(2);
            
            // إضافة التكلفة الإجمالية للمجموع
            totalStandard += parseFloat(totalCost) || 0;
            
            rows += `
                <tr id="row-${bom.id}">
                    <td class="text-center">${counter++}</td>
                    <td>${bom.material?.product_name || 'غير معروف'}</td>
                    <td>${bom.unit?.Categorie_name || 'غير معروف'}</td>
                    <td class="text-center">${formatNumber(parseFloat(bom.quantity))}</td>
                    <td class="text-center">${formatNumber(parseFloat(totalQuantity))}</td>
                    <td class="text-center">${bom.waste_factor}%</td>
                    <td class="text-center">${formatNumber(parseFloat(bom.standard_cost))}</td>
                    <td class="text-center">${formatNumber(parseFloat(totalCost))}</td>
                    <td class="text-center">
                        <span class="badge badge-${bom.is_active ? 'success' : 'danger'}">
                            ${bom.is_active ? 'نشط' : 'غير نشط'}
                        </span>
                    </td>
                </tr>
            `;
        }
    });
    
    // إضافة صف المجموع
    if (boms.length > 0) {
        rows += `
            <tr class="font-weight-bold">
                <td colspan="7" class="text-right">إجمالي التكاليف:</td>
                <td class="text-center">${formatNumber(totalStandard)}</td>
                <td></td>
            </tr>
        `;
    }
    
    $bomsTable.empty().append(rows || '<tr><td colspan="9" class="text-center">لا توجد بيانات متاحة</td></tr>');
}
});
    // ضبط تاريخ الانتهاء ليكون بعد تاريخ البدء
    document.getElementById('start_date').addEventListener('change', function() {
        const endDate = document.getElementById('end_date');
        endDate.min = this.value;
        if (endDate.value && endDate.value < this.value) {
            endDate.value = this.value;
        }
    });
    });
</script>
@endsection