@extends('layout')
@section('conm')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">الموردين الافتراضيين</h1>
    @if ($defaultSuppliers!=null)

    @if (isset($defaultSuppliers))
                    
    <a href="{{ route('default_suppliers.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">إضافة مورد جديد</a>
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
            @auth
                @if (isset($defaultSuppliers))
                    
              
            @foreach ($defaultSuppliers as $supplier)
            <tr>
                <td class="py-2 px-4 border-b">{{ $supplier->name }}</td>
                <td class="py-2 px-4 border-b">{{ $supplier->Phone }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('default_suppliers.edit', $supplier->id) }}" class="text-blue-500">تعديل</a>
                    <form action="{{ route('default_suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @endif
            @endauth

        </tbody>
    </table>
</div>
@endsection
