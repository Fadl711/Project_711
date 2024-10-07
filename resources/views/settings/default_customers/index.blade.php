@extends('layout')
@section('conm')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">العملاء الافتراضيين</h1>
    @if ($defaultSuppliers!=null)

    @if (isset($defaultSuppliers))

    <a href="{{ route('default_customers.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">إضافة عميل جديد</a>
    @endif
    @endif
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
                        <a href="{{ route('default_customers.edit', $Default_customers->id) }}" class="text-blue-500">تعديل</a>
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
@endsection
