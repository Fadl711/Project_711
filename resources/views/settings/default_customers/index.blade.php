@extends('layout')
@section('conm')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">العملاء الافتراضيين</h1>
    <form action="{{ route('default_customers.store') }}" method="POST" class="w-1/2">
        @csrf
        <div class="mb-4">
            <label for="subaccount_id" class="block text-sm font-medium text-gray-700">العميل الفرعي</label>
            <select name="subaccount_id" id="subaccount_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                <option selected value=""></option>
                @foreach ($supplirx as $SubAccount)
                <option @isset($defaultSuppliers)
                @selected($defaultSuppliers->id==$SubAccount->sub_account_id)
                @endisset
                value="{{ $SubAccount->sub_account_id }}">{{ $SubAccount->sub_name }}</option>
                 @endforeach

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

                @if (isset($SubAccounts) && isset($Default_customers))

                @foreach ($SubAccounts as $SubAccount)


                @if ($SubAccount->sub_account_id==$Default_customers->subaccount_id)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $SubAccount->sub_name }}</td>
                    <td class="py-2 px-4 border-b">{{ $SubAccount->Phone }}</td>
                    <td class="py-2 px-4 border-b">
                        <form action="{{ route('default_customers.destroy', $Default_customers->id) }}" method="POST" class="inline">
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
    </script>
@endsection
