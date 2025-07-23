@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">إضافة تكلفة صناعية جديدة</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('manufacturing-costs.store') }}">
                        @csrf

                        <div class="form-group row py-2">
                            <label for="production_order_id" class="col-md-4 col-form-label text-md-right">أمر الإنتاج</label>
                            <div class="col-md-6 ">
                                <select id="production_order_id" class="form-control  select2 @error('production_order_id') is-invalid @enderror" name="production_order_id" required>
                                    <option value="">اختر أمر الإنتاج</option>
                                    @foreach($productionOrders as $order)
                                        <option value="{{ $order->id }}" {{ old('production_order_id') == $order->id ? 'selected' : '' }}>
                                            {{ $order->order_number }} - {{ $order->product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('production_order_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row py-2">
                            <label for="cost_type" class="col-md-4 col-form-label text-md-right">نوع التكلفة</label>
                            <div class="col-md-6">
                                <select id="cost_type" class="form-control select2 @error('cost_type') is-invalid @enderror" name="cost_type" required>
                                    <option value="">اختر نوع التكلفة</option>
                                    @foreach($costTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('cost_type') == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cost_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row py-2">
                            <label for="amount" class="col-md-4 col-form-label text-md-right">المبلغ</label>
                            <div class="col-md-6">
                                <input id="amount" type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row py-2">
                            <label for="gl_account_id" class="col-md-4 col-form-label text-md-right">حساب دفتر الأستاذ</label>
                            <div class="col-md-6">
                                <select id="gl_account_id" class="form-control select2 @error('gl_account_id') is-invalid @enderror" name="gl_account_id" required>
                                    <option value="">اختر الحساب</option>
                                    @foreach($glAccounts as $account)
                                        <option value="{{ $account->sub_account_id }}" {{ old('gl_account_id') == $account->sub_account_id  ? 'selected' : '' }}>
                                            {{ $account->sub_account_id }} - {{ $account->sub_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gl_account_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row py-2">
                            <label for="cost_date" class="col-md-4 col-form-label text-md-right">تاريخ التكلفة</label>
                            <div class="col-md-6">
                                <input id="cost_date" type="date" class="form-control @error('cost_date') is-invalid @enderror" name="cost_date" value="{{ old('cost_date', date('Y-m-d')) }}" required>
                                @error('cost_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row py-2">
                            <label for="description" class="col-md-4 col-form-label text-md-right">الوصف</label>
                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0 py-2">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ التكلفة
                                </button>
                                <a href="{{ route('manufacturing-costs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
     $(document).ready(function() {
         $('.select2').select2({
        placeholder: 'اختر عنصر',
        allowClear: true
    });
});
</script>
@endsection