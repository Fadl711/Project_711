@extends('production_system.index')
@section('productionSystem')
<div class="container px-4 py-5 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">إضافة حركة مواد خام جديدة</h1>
        <a href="{{ route('raw-material-transactions.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            رجوع
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form id="RawMaterialTransactionForm"  method="POST">
            @csrf
            {{-- action="{{ route('raw-material-transactions.store') }}" --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- أمر الإنتاج -->
                <div>
                    <label for="production_order_id" class="block text-sm font-medium text-gray-700">أمر الإنتاج</label>
                    <select id="production_order_id" name="production_order_id" required
                            class="mt-1 block select2 w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر أمر الإنتاج</option>
                        @foreach($productionOrders as $order)
                            <option value="{{ $order->id }}" {{ old('production_order_id') == $order->id ? 'selected' : '' }}>
                                {{ $order->order_number }} -{{$order->id }} - {{ $order->product->product_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('production_order_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- المادة الخام -->
                <div>
                    <label for="material_id" class="block text-sm font-medium text-gray-700">المادة الخام</label>
                    <select id="material_id" name="material_id" required
                            class="mt-1 select2 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر المادة الخام</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->product_id }}"  {{ old('material_id') == $material->product_id ? 'selected' : '' }}>
                                {{ $material->product_name }} ({{ $material->product_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('material_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- الكميات -->
                <div>
                    <label for="planned_quantity" class="block text-sm font-medium text-gray-700">الكمية المخططة</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" step="0.001" id="planned_quantity" name="planned_quantity" value="{{ old('planned_quantity') }}" required
                               class="block w-full pr-12 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm" id="planned_quantity_unit">--</span>
                        </div>
                    </div>
                    @error('planned_quantity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="actual_quantity" class="block text-sm font-medium text-gray-700">الكمية الفعلية</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" step="0.001" id="actual_quantity" name="actual_quantity" value="{{ old('actual_quantity') }}" required
                               class="block w-full pr-12 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm" id="actual_quantity_unit">--</span>
                        </div>
                    </div>
                    @error('actual_quantity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- التكلفة -->
                <div>
                    <label for="unit_cost" class="block text-sm font-medium text-gray-700">التكلفة للوحدة</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" step="0.00001" id="unit_cost" name="unit_cost" value="{{ old('unit_cost') }}" required
                               class="block w-full pr-12 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">ر.س</span>
                        </div>
                    </div>
                    @error('unit_cost')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_cost" class="block text-sm font-medium text-gray-700">التكلفة الإجمالية</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" id="total_cost" readonly
                               class="block w-full pr-12 py-2 border border-gray-300 bg-gray-100 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">ر.س</span>
                        </div>
                    </div>
                </div>

                <!-- المخزن والموقع -->
                <div>
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">المخزن</label>
                    <select id="warehouse_id" name="warehouse_id" required
                            class="mt-1 select2 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر المخزن</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->sub_account_id }}" {{ old('warehouse_id') == $warehouse->sub_account_id ? 'selected' : '' }}>
                                {{ $warehouse->sub_name }}_{{ $warehouse->name_the_known }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location_id" class="block text-sm font-medium text-gray-700">الموقع (اختياري)</label>
                    <select id="location_id" name="location_id"
                            class="mt-1 select2 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر الموقع</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->sub_account_id }}" {{ old('location_id') == $location->sub_account_id ? 'selected' : '' }}>
                                {{ $location->name_the_known }} ({{ $location->sub_account_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- مسؤول الصرف -->
                <div>
                    <label for="issued_by" class="block text-sm font-medium text-gray-700">مسؤول الصرف</label>
                    <select id="issued_by" name="issued_by" required
                            class=" select2 mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر المسؤول</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('issued_by') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('issued_by')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ملاحظات -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="button" id="RawMaterialSave" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    حفظ الحركة
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
         $('#RawMaterialSave').click(function(event) {
                event.preventDefault();
                // const entrie_id = $('#entrie_id').val();

                // إظهار مؤشر التحميل
                Swal.fire({
                    title: 'جارٍ المعالجة',
                    html: 'يرجى الانتظار أثناء حفظ البيانات...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                var formData = $('#RawMaterialTransactionForm').serialize();

                $.ajax({
                    url: '{{ route('raw-material-transactions.store') }}',
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        Swal.close();

                        if (data.success) {
                            Swal.fire({
                                title: 'نجاح!',
                                text: data.success,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });

                            // if (entrie_id) {
                            //     var invoiceField = data.entrie_id;
                            //     const url =
                            //         `{{ route('restrictions.print', ':invoiceField') }}`
                            //         .replace(':invoiceField', invoiceField);
                            //     window.open(url, '_blank', 'width=600,height=800');

                            //     setTimeout(() => {
                            //         window.location.href =
                            //             '{{ route('restrictions.create') }}';
                            //     }, 1000);
                            // }

                            // $('#Amount_debit').val("");
                            // $('#sub_account_debit_id').select2('open');
                        } else {
                            Swal.fire({
                                title: 'خطأ!',
                                text: data.errorMessage || 'حدث خطأ غير متوقع',
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessages = '';

                            for (const field in errors) {
                                errorMessages += `${errors[field][0]}<br>`;
                                const inputField = $(`#${field}`);
                                const parentDiv = inputField.closest('div');
                                parentDiv.find('.error-message').remove();
                                inputField.addClass('border-red-500');
                                parentDiv.append(
                                    `<div class="error-message text-red-500 text-sm mt-1">${errors[field][0]}</div>`
                                );
                            }

                            Swal.fire({
                                title: 'خطأ في التحقق',
                                html: errorMessages,
                                icon: 'error',
                                timer: 5000,
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                title: 'خطأ!',
                                text: 'حدث خطأ أثناء الحفظ. يرجى المحاولة لاحقًا.',
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            });
   

    // جلب مواقع المخزن عند اختيار مخزن
    // $('#warehouse_id').on('change', function() {
    //     const warehouseId = $(this).val();
    //     $('#location_id').empty().append('<option value="">جاري التحميل...</option>');
        
    //     if (warehouseId) {
    //         $.get('/api/warehouses/' + warehouseId + '/locations', function(data) {
    //             $('#location_id').empty().append('<option value="">اختر الموقع</option>');
    //             $.each(data, function(key, location) {
    //                 $('#location_id').append('<option value="' + location.id + '">' + location.name + '</option>');
    //             });
    //         });
    //     } else {
    //         $('#location_id').empty().append('<option value="">اختر الموقع</option>');
    //     }
    // });

    // تهيئة Select2
    $('.select2').select2({
        placeholder: 'اختر عنصر',
        allowClear: true
    });
});

    document.addEventListener('DOMContentLoaded', function() {
        const materialSelect = document.getElementById('material_id');
        const plannedQuantityUnit = document.getElementById('planned_quantity_unit');
        const actualQuantityUnit = document.getElementById('actual_quantity_unit');
        const unitCostInput = document.getElementById('unit_cost');
        const actualQuantityInput = document.getElementById('actual_quantity');
        const totalCostInput = document.getElementById('total_cost');

        // تحديث وحدة القياس عند تغيير المادة
        materialSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const unit = selectedOption.getAttribute('data-unit') || '--';
            plannedQuantityUnit.textContent = unit;
            actualQuantityUnit.textContent = unit;
        });

        // حساب التكلفة الإجمالية
        function calculateTotalCost() {
            const unitCost = parseFloat(unitCostInput.value) || 0;
            const actualQuantity = parseFloat(actualQuantityInput.value) || 0;
            const totalCost = unitCost * actualQuantity;
            totalCostInput.value = totalCost.toFixed(2);
        }

        unitCostInput.addEventListener('input', calculateTotalCost);
        actualQuantityInput.addEventListener('input', calculateTotalCost);

        // تشغيل مرة أولى عند التحميل إذا كانت هناك قيم مسبقة
        if (materialSelect.value) {
            const event = new Event('change');
            materialSelect.dispatchEvent(event);
        }
        calculateTotalCost();
    });
</script>
@endsection