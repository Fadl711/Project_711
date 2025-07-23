@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">تعديل تكلفة صناعية #{{ $manufacturingCost->cost_id }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('manufacturing-costs.update', $manufacturingCost->cost_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="production_order_id" class="col-md-4 col-form-label text-md-right">أمر الإنتاج</label>
                            <div class="col-md-6">
                                <select id="production_order_id" class="form-control @error('production_order_id') is-invalid @enderror" name="production_order_id" required>
                                    @foreach($productionOrders as $order)
                                        <option value="{{ $order->id }}" {{ $manufacturingCost->production_order_id == $order->id ? 'selected' : '' }}>
                                            {{ $order->order_code }} - {{ $order->product->name }}
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

                        <div class="form-group row">
                            <label for="cost_type" class="col-md-4 col-form-label text-md-right">نوع التكلفة</label>
                            <div class="col-md-6">
                                <select id="cost_type" class="form-control @error('cost_type') is-invalid @enderror" name="cost_type" required>
                                    @foreach($costTypes as $key => $type)
                                        <option value="{{ $key }}" {{ $manufacturingCost->cost_type == $key ? 'selected' : '' }}>
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

                        <div class="form-group row">
                            <label for="amount" class="col-md-4 col-form-label text-md-right">المبلغ</label>
                            <div class="col-md-6">
                                <input id="amount" type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $manufacturingCost->amount) }}" required>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gl_account_id" class="col-md-4 col-form-label text-md-right">حساب دفتر الأستاذ</label>
                            <div class="col-md-6">
                                <select id="gl_account_id" class="form-control @error('gl_account_id') is-invalid @enderror" name="gl_account_id" required>
                                    @foreach($glAccounts as $account)
                                        <option value="{{ $account->id }}" {{ $manufacturingCost->gl_account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_code }} - {{ $account->account_name }}
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

                        <div class="form-group row">
                            <label for="cost_date" class="col-md-4 col-form-label text-md-right">تاريخ التكلفة</label>
                            <div class="col-md-6">
                                <input id="cost_date" type="date" class="form-control @error('cost_date') is-invalid @enderror" name="cost_date" value="{{ old('cost_date', $manufacturingCost->cost_date->format('Y-m-d')) }}" required>
                                @error('cost_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">الوصف</label>
                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $manufacturingCost->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('manufacturing-costs.show', $manufacturingCost->cost_id) }}" class="btn btn-secondary">
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
@endsection