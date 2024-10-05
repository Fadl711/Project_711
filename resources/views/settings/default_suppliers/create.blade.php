@extends('layout')
@section('conm')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">إضافة مورد افتراضي</h1>

    <form action="{{ route('default_suppliers.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">اسم المورد</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        
        <div class="mb-4">
            <label for="subaccount_id" class="block text-sm font-medium text-gray-700">المورد الفرعي</label>
            <select name="subaccount_id" id="subaccount_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                <option value="">اختر المورد الفرعي</option>
                @auth
                @if (isset($supplirx))
                @foreach($supplirx as $supplir)
                    {{-- <option value="{{ $supplir['sub_account_id'] }}">{{ $supplir['sub_name'] }}</option> --}}
                @endforeach
                @endif
                @endauth

            </select>
        </div>

        <div class="mb-4">
            <label for="debtor_amount" class="block text-sm font-medium text-gray-700">المبلغ المدين</label>
            <input type="number" name="debtor_amount" id="debtor_amount" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" step="0.01">
        </div>

        <div class="mb-4">
            <label for="creditor_amount" class="block text-sm font-medium text-gray-700">المبلغ الدائن</label>
            <input type="number" name="creditor_amount" id="creditor_amount" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" step="0.01">
        </div>

        <div class="mb-4">
            <label for="name_The_known" class="block text-sm font-medium text-gray-700">اسم المعرف</label>
            <input type="text" name="name_The_known" id="name_The_known" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="Known_phone" class="block text-sm font-medium text-gray-700">رقم الهاتف المعرف</label>
            <input type="text" name="Known_phone" id="Known_phone" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="User_id" class="block text-sm font-medium text-gray-700">رقم المستخدم</label>
            <input type="number" name="User_id" id="User_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="Phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
            <input type="text" name="Phone" id="Phone" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">إضافة</button>
    </form>
</div>
@endsection