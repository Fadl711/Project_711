@isset($allQuantityonly)
    <div class="w-full overflow-y-auto max-h-[80vh]  bg-white">
    <table class="w-full mb-4 text-sm bg-white border border-gray-200 rounded-lg shadow-lg">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr class="bg-blue-100">
                <th class="px-2 py-1 tagTd">رقم الصنف</th>
                <th class="px-2 py-1 tagTd">اسم الصنف</th>
                <th class="px-2 py-1 tagTd"> الوحدة</th>
                <th class="py-1 px-2 tagTd">المخزن</th>
                <th class="py-1 px-2 tagTd">الكمية المتوفره</th>
            
            </tr>
        </thead>
        <tbody class="  font-medium ">
            @foreach ($allQuantityonly as $item)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-1 px-2">{{ $item['product_id'] }}</td>
                <td class="py-1 px-2">{{ $item['product_name'] }}</td>
                <td class="py-1 px-2">{{ $item['categories']->Categorie_name ?? ' ' }}</td>
                <td class="py-1 px-2">{{ $item['warehouse_name'] ?? '' }}</td>
                <td class="py-1 px-2">{{ $item['SumQuantity'] ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endisset