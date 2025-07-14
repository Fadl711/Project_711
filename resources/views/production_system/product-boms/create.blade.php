@extends('production_system.index')
@section('productionSystem')
<div class="container">
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
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إضافة BOM جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('product-boms.store') }}" method="POST" id="bomForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_id">المنتج النهائي</label>
                                <select name="product_id" id="product_id" class="form-control select2" required>
                                    <option value="">اختر المنتج النهائي</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->product_id }}" {{ old('product_id') == $product->product_id ? 'selected' : '' }}>
                                        {{ $product->product_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_id">المادة الخام</label>
                                <select name="material_id" id="material_id" class="form-control select2" required>
                                    <option value="">اختر المادة الخام</option>
                                    @foreach($materials as $material)
                                    <option value="{{ $material->product_id }}" {{ old('material_id') == $material->product_id ? 'selected' : '' }}>
                                        {{ $material->product_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('material_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity">الكمية المطلوبة لكل وحدة</label>
                                <input type="number" step="0.001" name="quantity" id="quantity" class="form-control" 
                                       value="{{ old('quantity') }}" required>
                                @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit_id">وحدة القياس</label>
                                <select name="unit_id" id="unit_id" class="form-control select2" required>
                                    <option value="">اختر وحدة القياس</option>
                                </select>
                                @error('unit_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="waste_factor">نسبة الهدر (%)</label>
                                <input type="number" step="0.01" name="waste_factor" id="waste_factor" 
                                       class="form-control" value="{{ old('waste_factor', 0) }}">
                                @error('waste_factor')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="default_warehouse_id">المخزن الافتراضي</label>
                                <select name="default_warehouse_id" id="default_warehouse_id" class="form-control select2" required>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->sub_account_id }}" {{ old('default_warehouse_id') == $warehouse->sub_account_id ? 'selected' : '' }}>
                                        {{ $warehouse->sub_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('default_warehouse_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="standard_cost"> التكلفة المعيارية للوحدة</label>
                                <input type="number" step="0.00001" name="standard_cost" id="standard_cost" 
                                       class="form-control" value="{{ old('standard_cost') }}" required>
                                @error('standard_cost')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-check mt-3">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">مفعل</label>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">حفظ</button>
                        <a href="{{ route('product-boms.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'اختر عنصر',
        allowClear: true
    });

    // Load product units when product is selected
    $('#material_id').on('change', function() {
        const productId = $(this).val();
        const warehouseId = $('#default_warehouse_id').val();
        
        if (productId) {
            fetchProductDetails(productId, warehouseId);
        } else {
            resetProductDetails();
        }
    });

    // Update standard cost when unit is changed
    $('#unit_id').on('change', function() {
        const unitId = $(this).val();
        const productId = $('#material_id').val();
        
        if (unitId && productId) {
            getUnitPrice(productId, unitId);
        }
    });

    // Fetch product details including units
    function fetchProductDetails(productId, warehouseId) {
        $.ajax({
            url: "{{ url('/api/products/search/') }}",
            method: 'GET',
            data: {
                id: productId,
                account_debitid: warehouseId
            },
            success: function(product) {
                if (product) {
                    updateProductDetails(product);
                    $('#unit_id').select2('open');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                alert('حدث خطأ أثناء جلب بيانات المنتج');
            }
        });
    }

    // Update product details in the form
    function updateProductDetails(product) {
        // Update standard cost
        if (product.Purchase_price) {
            $('#standard_cost').val(product.Purchase_price).trigger('change');
        }
        
        // Update units dropdown
        const unitSelect = $('#unit_id');
        unitSelect.empty();
        
        if (product.Categorie_names && product.Categorie_names.length > 0) {
            $.each(product.Categorie_names, function(index, category) {
                unitSelect.append(new Option(category.Categorie_name, category.categorie_id));
            });
        }
        
        unitSelect.append('<option value="" selected>اختر وحدة القياس</option>');
        unitSelect.trigger('change');
    }

    // Get price for selected unit
    function getUnitPrice(productId, unitId) {
        $.ajax({
            url: "{{ url('/GetProduct') }}/" + productId + "/price",
            method: 'GET',
            data: {
                mainAccountId: productId,
                Categoriename: unitId
            },
            success: function(response) {
                if (response.product) {
                    $('#standard_cost').val(response.product.Purchase_price).trigger('change');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
            }
        });
    }

    // Reset product details
    function resetProductDetails() {
        $('#unit_id').empty().append('<option value="">اختر وحدة القياس</option>').trigger('change');
        $('#standard_cost').val('').trigger('change');
        $('#quantity').val('').trigger('change');
    }

    // Form validation
    // $('#bomForm').validate({
    //     rules: {
    //         product_id: "required",
    //         material_id: "required",
    //         quantity: {
    //             required: true,
    //             min: 0.001
    //         },
    //         unit_id: "required",
    //         waste_factor: {
    //             min: 0,
    //             max: 100
    //         },
    //         default_warehouse_id: "required",
    //         standard_cost: {
    //             required: true,
    //             min: 0
    //         }
    //     },
    //     messages: {
    //         product_id: "يجب اختيار المنتج النهائي",
    //         material_id: "يجب اختيار المادة الخام",
    //         quantity: {
    //             required: "يجب إدخال الكمية",
    //             min: "يجب أن تكون الكمية أكبر من الصفر"
    //         },
    //         unit_id: "يجب اختيار وحدة القياس",
    //         waste_factor: {
    //             min: "يجب أن تكون النسبة موجبة",
    //             max: "يجب أن تكون النسبة أقل من أو تساوي 100%"
    //         },
    //         default_warehouse_id: "يجب اختيار المخزن الافتراضي",
    //         standard_cost: {
    //             required: "يجب إدخال التكلفة المعيارية",
    //             min: "يجب أن تكون التكلفة موجبة"
    //         }
    //     },
    //     errorElement: 'span',
    //     errorClass: 'text-danger',
    //     highlight: function(element, errorClass) {
    //         $(element).addClass('is-invalid');
    //     },
    //     unhighlight: function(element, errorClass) {
    //         $(element).removeClass('is-invalid');
    //     }
    // });
});
</script>
@endsection