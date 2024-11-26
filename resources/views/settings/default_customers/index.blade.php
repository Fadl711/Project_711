@extends('layout')
@section('conm')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">العملاء الافتراضيين</h1>
    <form action="{{ route('default_customers.store') }}" method="POST" class="w-1/2">
        @csrf
        <div class="mb-4">
            <label for="subaccount_id" class="block text-sm font-medium text-gray-700">العميل الفرعي</label>
            <select style="background-image: none ;" name="subaccount_id" id="subaccount_id" class="appearance-auto  mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                <option selected value=""></option>
            @isset($supplirx)

                 @foreach ($supplirx as $SubAccount)
                <option @isset($defaultSuppliers->subaccount_id)
                @selected($defaultSuppliers->subaccount_id==$SubAccount->sub_account_id)
                @endisset
                value="{{ $SubAccount->sub_account_id }}">{{ $SubAccount->sub_name }}</option>
                @endforeach
            @endisset

            </select>
        </div>

    </form>


    <table class="min-w-full bg-white border border-gray-300 mt-4">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">الاسم</th>
                <th class="py-2 px-4 border-b">الهاتف</th>
                <th class="py-2 px-4 border-b">الإجراءات</th>
            </tr>
        </thead>
        <tbody>

                @if (isset($SubAccounts) && isset($Default_customers->subaccount_id))

                @foreach ($SubAccounts as $SubAccount)


                @if ($SubAccount->sub_account_id==$Default_customers->subaccount_id)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $SubAccount->sub_name }}</td>
                    <td class="py-2 px-4 border-b">{{ $SubAccount->Phone }}</td>
                    <td class="py-2 px-4 border-b">
                        <form action="{{ route('default_customers.destroy', $Default_customers->subaccount_id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">حذف</button>
                        </form>
                    </td>
                </tr>


                @endif
            @endforeach
            @endif


        </tbody>
    </table>
</div>
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">المخزن الافتراضي</h1>
    <form action="{{ route('default_warehouse.store') }}" method="POST" class="w-1/2">
        @csrf
        <div class="mb-4">
            <label for="warehouse_id" class="block text-sm font-medium text-gray-700">المخزن الفرعي</label>
            <select style="background-image: none ;" name="warehouse_id" id="warehouse_id" class="appearance-auto  mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                <option selected value=""></option>
            @isset($warehouse1)

                @foreach ($warehouse1 as $SubAccount)
                <option @isset($defaultSuppliers->warehouse_id)
                    @selected($defaultSuppliers->warehouse_id==$SubAccount->sub_account_id)
                    @endisset
                    value="{{ $SubAccount->sub_account_id }}">{{ $SubAccount->sub_name }}</option>
                    @endforeach
            @endisset

            </select>
        </div>

    </form>


    <table class="min-w-full bg-white border border-gray-300 mt-4">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">الاسم</th>
                <th class="py-2 px-4 border-b">الهاتف</th>
                <th class="py-2 px-4 border-b">الإجراءات</th>
            </tr>
        </thead>
        <tbody>

                @if (isset($SubAccounts) && isset($Default_customers->warehouse_id))

                @foreach ($SubAccounts as $SubAccount)


                @if ($SubAccount->sub_account_id==$Default_customers->warehouse_id)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $SubAccount->sub_name }}</td>
                    <td class="py-2 px-4 border-b">{{ $SubAccount->Phone }}</td>
                    <td class="py-2 px-4 border-b">
                        <form action="{{ route('default_warehouse.destroy', $Default_customers->warehouse_id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">حذف</button>
                        </form>
                    </td>
                </tr>


                @endif
            @endforeach
            @endif


        </tbody>
    </table>
</div>
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">الصندوق الافتراضي</h1>
    <form action="{{ route('default_financial.store') }}" method="POST" class="w-1/2">
        @csrf
        <div class="mb-4">
            <label for="financial_account_id" class="block text-sm font-medium text-gray-700">الصندوق الفرعي</label>
            <select style="background-image: none ;" name="financial_account_id" id="financial_account_id" class=" appearance-auto mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                <option selected value=""></option>
                @isset($box1)

                @foreach ($box1 as $SubAccount)
                <option @isset($defaultSuppliers->financial_account_id)
                    @selected($defaultSuppliers->financial_account_id==$SubAccount->sub_account_id)
                    @endisset
                    value="{{ $SubAccount->sub_account_id }}">{{ $SubAccount->sub_name }}</option>
                    @endforeach
                @endisset

            </select>
        </div>

    </form>


    <table class="min-w-full bg-white border border-gray-300 mt-4">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">الاسم</th>
                <th class="py-2 px-4 border-b">الهاتف</th>
                <th class="py-2 px-4 border-b">الإجراءات</th>
            </tr>
        </thead>
        <tbody>

                @if (isset($SubAccounts) && isset($Default_customers->financial_account_id))

                @foreach ($SubAccounts as $SubAccount)


                @if ($SubAccount->sub_account_id==$Default_customers->financial_account_id)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $SubAccount->sub_name }}</td>
                    <td class="py-2 px-4 border-b">{{ $SubAccount->Phone }}</td>
                    <td class="py-2 px-4 border-b">
                        <form action="{{ route('default_financial.destroy', $Default_customers->financial_account_id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">حذف</button>
                        </form>
                    </td>
                </tr>


                @endif
            @endforeach
            @endif


        </tbody>
    </table>
</div>

<script>
    $('#subaccount_id').on('change', function() {
        var subaccount_id = $(this).val();
        var token = '{{ csrf_token() }}'; // Add this line
        $.ajax({
            type: 'POST',
            url: '{{ route('default_customers.store') }}',
            data: {
                _token: token, // Add this line
                subaccount_id: subaccount_id
            },
            success: function(data) {
                location.reload();            }

        });
    });
    $('#warehouse_id').on('change', function() {
        var warehouse_id = $(this).val();
        var token = '{{ csrf_token() }}'; // Add this line
        $.ajax({
            type: 'POST',
            url: '{{ route('default_warehouse.store') }}',
            data: {
                _token: token, // Add this line
                warehouse_id: warehouse_id
            },
            success: function(data) {
                location.reload();            }

        });
    });
    $('#financial_account_id').on('change', function() {
        var financial_account_id = $(this).val();
        var token = '{{ csrf_token() }}'; // Add this line
        $.ajax({
            type: 'POST',
            url: '{{ route('default_financial.store') }}',
            data: {
                _token: token, // Add this line
                financial_account_id: financial_account_id
            },
            success: function(data) {
                location.reload();            }

        });
    });
    </script>
@endsection
