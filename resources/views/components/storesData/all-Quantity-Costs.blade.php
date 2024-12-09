@isset($allQuantityCosts)
<div class="container mx-auto print-container">
    <table class="w-full text-sm  bg-white ">
            
            <tr class="bg-blue-100">
                <th class="text-right">رقم الصنف</th>
                <th class="text-right">اسم الصنف</th>
                <th class="text-right"> الوحدة</th>
                <th class="text-right">المخزن</th>
                <th class="text-center">الكمية المتوفره</th>
                <th class="text-right"> التكلفة</th>
                <th class="text-right"> الجالي التكلفة</th>
            </tr>
        </thead>
        <tbody >
            @foreach ($allQuantityCosts as $item)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="text-right">{{ $item['product_id'] }}</td>
                <td class="text-right">{{ $item['product_name'] }}</td>
                <td class="text-right">{{ $item['categories']->Categorie_name ?? '' }}</td>
                <td class="text-right">{{ $item['warehouse_name'] ?? '' }}</td>
                <td class="text-center">{{ $item['SumQuantity'] ?? 0 }}</td>
                <td class="text-right">{{ number_format($item['Purchase_price'] ?? 0 ) }}</td>
                <td class="text-right">{{ number_format(($item['Purchase_price'] ?? 0) * ($item['SumQuantity'] ?? 0), 2) }}</td>      
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endisset