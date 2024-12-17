
@isset($QuantityIncomplete)
<div class="container mx-auto print-container">
    <table class="w-full text-sm bg-white" aria-describedby="inventory-table-description">
        <thead>
            <tr class="bg-blue-100">
                <th scope="col" class="text-right">رقم الصنف</th>
                <th scope="col" class="text-right">اسم الصنف</th>
                <th scope="col" class="text-right">الوحدة</th>
                <th scope="col" class="text-center">الكمية المتوفره</th>
                <th scope="col" class="text-center">كمية الجرد</th>
                <th scope="col" class="text-right">فارق الكمية</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($QuantityIncomplete as $item)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="text-right">{{ $item['product_id'] }}</td>
                    <td class="text-right">{{ $item['product_name'] }}</td>
                    <td class="text-right">{{ $item['categories']->Categorie_name ?? '-' }}</td>
                    <td class="text-right">{{ $item['AvailableQuantity'] ?? '-' }}</td>
                    <td class="text-center">{{ $item['InventoryQuantity'] ?? 0 }}</td>
                    <td class="text-center">{{ $item['QuantityDifference'] ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endisset
@php
   if (isset($CostIncomplete)) {
    $items=$CostIncomplete;
    # code...
   }
@endphp
@isset($items )
<div class="container mx-auto print-container">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-blue-100">
                <th class="text-right">رقم الصنف</th>
                <th class="text-right">اسم الصنف</th>
                <th class="text-right"> الوحدة </th>
                <th class="text-right">  التكلفة الوحدة</th>
                <th class="text-right">الكمية المتوفره</th>
                <th class="text-right">الكمية الجرد</th>
                <th class="text-right">فارق الجرد </th>
                <th class="text-right">  التكلفة فارق الجرد </th>
                <th class="text-right">  التكلفة  الكمية المتوفره </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="text-right">{{ $item['product_id'] }}</td>
                <td class="text-right">{{ $item['product_name'] }}</td>
                <td class="text-right">{{ $item['categories']->Categorie_name ?? '' }}</td>
                <td class="text-right">{{ number_format($item['CostPrice'] ?? 0 ) }}</td>
                <td class="text-center">{{ $item['AvailableQuantity'] ?? '' }}</td>
                <td class="text-center">{{ $item['InventoryQuantity'] ?? 0 }}</td>
                <td class="text-center">{{ $item['QuantityDifference'] ?? 0 }}</td>
                <td class="text-right">{{ number_format(($item['TotalInventoryCost'] ?? 0),2) }}</td>      
                <td class="text-right">{{ number_format(($item['TotalCostQuantityAvailable'] ?? 0), 2) }}</td>      
            </tr>
            @endforeach
            <tr>
                <th colspan="7" class="text-right"></th>
                <th colspan="" class="text-right">    الإجمالي</th> {{-- TotalCostQuantityAvailable --}}
                <th colspan="" class="text-right">الإجمالي    </th> {{-- TotalCostQuantityAvailable --}}
            </tr>
            <tr>
                <td  colspan="7" ></td>
                <td class="text-right">{{ number_format($TotalInventoryCostVariance, 2) }}</td>    
                <td class="text-right">{{ number_format($TotalCostQuantityAvailable, 2) }}</td>      
            </tr>
        </tbody>
    </table>
</div>
@endisset