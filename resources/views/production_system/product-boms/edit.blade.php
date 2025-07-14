@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تعديل BOM</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('product-boms.update', $bom->bom_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_id">المنتج النهائي</label>
                            <select name="product_id" id="product_id" class="form-control select2" required disabled>
                                <option value="{{ $bom->product_id }}" selected>{{ $bom->product->product_name }}</option>
                            </select>
                            <input type="hidden" name="product_id" value="{{ $bom->product_id }}">
                            @error('product_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="material_id">المادة الخام</label>
                            <select name="material_id" id="material_id" class="form-control select2" required >
                                <option value="{{ $bom->material_id }}" selected>{{ $bom->material->product_name }}</option>
                            </select>
                            <input type="hidden" name="material_id" value="{{ $bom->material_id }}">
                            @error('material_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quantity">الكمية المطلوبة</label>
                            <input type="number" step="0.001" name="quantity" id="quantity" class="form-control" 
                                   value="{{ old('quantity', $bom->quantity) }}" required>
                            @error('quantity')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="unit_id">وحدة القياس</label>
                            <select name="unit_id" id="unit_id" class="form-control" required>
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $bom->unit_id) == $unit->categorie_id ? 'selected' : '' }}>
                                    {{ $unit->Categorie_name }}
                                </option>
                                @endforeach
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
                                   class="form-control" value="{{ old('waste_factor', $bom->waste_factor) }}">
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
                            <select name="default_warehouse_id" id="default_warehouse_id" class="form-control" required>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->sub_account_id }}" {{ old('default_warehouse_id', $bom->default_warehouse_id) == $warehouse->sub_account_id ? 'selected' : '' }}>
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
                            <label for="standard_cost">التكلفة المعيارية</label>
                            <input type="number" step="0.00001" name="standard_cost" id="standard_cost" 
                                   class="form-control" value="{{ old('standard_cost', $bom->standard_cost) }}" required>
                            @error('standard_cost')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group form-check mt-3">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                           {{ old('is_active', $bom->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">مفعل</label>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    <a href="{{ route('product-boms.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'اختر عنصر',
            allowClear: true,
            disabled: true
        });
    });
</script>
@endpush