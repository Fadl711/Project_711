@extends('layout')
@section('conm')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">تعديل مورد</h1>
    <form action="{{ route('default_customers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-gray-700">الاسم</label>
            <input type="text" name="name" id="name" value="{{ $supplier->name }}" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
        </div>
        <div class="mb-4">
            <label for="Phone" class="block text-gray-700">الهاتف</label>
            <input type="text" name="Phone" id="Phone" value="{{ $supplier->Phone }}" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
        </div>
        <div class="mb-4">
            <label for="subaccount_id" class="block text-gray-700">معرف الحساب الفرعي</label>
            <input type="number" name="subaccount_id" id="subaccount_id" value="{{ $supplier->subaccount_id }}" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
        </div>
        <!-- أضف الحقول الأخرى حسب الحاجة -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">تحديث المورد</button>
    </form>
</div>
@endsection
