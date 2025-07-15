@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تسجيل حركة مخزون جديدة</h5>
        </div>
        <div class="card-body">
            <form  id="inventoryTransactionsForm" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transaction_type">نوع الحركة</label>
                            <select name="transaction_type" id="transaction_type" class="form-control" required>
                                <option value="">اختر نوع الحركة</option>
                                @foreach(\App\Models\InventoryTransaction::TRANSACTION_TYPES as $key => $value)
                                <option value="{{ $key }}" {{ old('transaction_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="item_id">المادة/المنتج</label>
                            <select name="item_id" id="item_id" 
                            class="form-control select2" required>
                                <option value="">اختر المادة/المنتج</option>
                                @foreach($items as $item)
                                <option value="{{ $item->product_id }}" {{ old('item_id') == $item->product_id ? 'selected' : '' }}>{{ $item->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quantity">الكمية</label>
                            <input type="number" step="0.001" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="unit_cost">التكلفة للوحدة</label>
                            <input type="number" step="0.00001" name="unit_cost" id="unit_cost" class="form-control" value="{{ old('unit_cost') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_cost">التكلفة الإجمالية</label>
                            <input type="number" step="0.01" name="total_cost" id="total_cost" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="warehouse_id">المخزن</label>
                            <select name="warehouse_id" id="warehouse_id" class="form-control select2" required>
                                <option value="">اختر المخزن</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->sub_account_id }}" {{ old('warehouse_id') == $warehouse->sub_account_id ? 'selected' : '' }}>{{ $warehouse->sub_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="location_id">الموقع (اختياري)</label>
                            <select name="location_id" id="location_id" class="form-control select2">
                                <option value="">اختر الموقع</option>
                                <!-- سيتم ملؤه بواسطة JavaScript -->
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="production_order_id">أمر الإنتاج (اختياري)</label>
                            <select name="production_order_id" id="production_order_id" class="form-control select2">
                                <option value="">اختر أمر الإنتاج</option>
                                @foreach($productionOrders as $order)
                                <option value="{{ $order->id }}" {{ old('production_order_id') == $order->id ? 'selected' : '' }}>{{ $order->order_number }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transaction_date">تاريخ الحركة</label>
                            <input type="datetime-local" name="transaction_date" id="transaction_date" class="form-control" value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="notes">ملاحظات</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="button" id="TransactionsSave"  class="btn btn-primary">حفظ</button>
                    <a href="{{ route('inventory-transactions.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
         $('#TransactionsSave').click(function(event) {
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

                var formData = $('#inventoryTransactionsForm').serialize();

                $.ajax({
                    url: '{{ route('inventory-transactions.store') }}',
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
    // حساب التكلفة الإجمالية تلقائياً
    $('#quantity, #unit_cost').on('input', function() {
        const quantity = parseFloat($('#quantity').val()) || 0;
        const unitCost = parseFloat($('#unit_cost').val()) || 0;
        $('#total_cost').val((quantity * unitCost).toFixed(2));
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
</script>
@endsection



