@extends('layout')
@section('conm')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">إضافة عميل افتراضي</h1>

    <form action="{{ route('default_customers.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="subaccount_id" class="block text-sm font-medium text-gray-700">العميل الفرعي</label>
            <select name="subaccount_id" id="subaccount_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                <option value="">اختر عميل الفرعي</option>

                @if (isset($supplirx))
                    @foreach($supplirx as $supplir)
                        @if(isset($Default_customers))
                            <option  @selected($Default_customers->subaccount_id==$supplir['sub_account_id'])  value="{{ $supplir['sub_account_id'] }}">{{ $supplir['sub_name'] }}</option>

                        @else
                            <option  value="{{ $supplir['sub_account_id'] }}">{{ $supplir['sub_name'] }}</option>
                        @endif


                        @endforeach
                @endif


            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">إضافة</button>
    </form>
</div>
@endsection
