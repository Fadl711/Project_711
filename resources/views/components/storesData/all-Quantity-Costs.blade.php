@isset($allQuantityCosts)
    <table class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
        <thead>
            <tr class="bg-blue-100">

                <th class="text-right">#</th>
                <th class="text-right">اسم الصنف</th>
                <th class="text-center">رقم الصنف</th>
                <th class="text-center"> الوحدة</th>
                <th class="text-center">المخزن</th>
                <th class="text-center">الكمية المتوفره</th>
                <th class="text-center"> التكلفة</th>
                <th class="text-center"> اجمالي التكلفة</th>
            </tr>
        </thead>

        </thead>
        <tbody >
            @php
            $Purchase_priceTotal = 0;
            $displayedProducts = [];
        @endphp
        
        @foreach ($allQuantityCosts as $index => $item)
            @if (!in_array($item['product_id'], $displayedProducts))
                @php
                    $displayedProducts[] = $item['product_id'];
                @endphp
                @if ($item['SumQuantity'])
                    
                <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="px-4 text-center">{{ $index + 1 }}</td>

                    <td class="text-right">{{ $item['product_name'] }}</td>
                    <td class="text-center">{{ $item['product_id'] }}</td>
                    <td class="text-center">{{ $item['categories']->Categorie_name ?? '' }}</td>
                    <td class="text-center">{{ $item['warehouse_name'] ?? '' }}</td>
                    <td class="text-center">{{ $item['SumQuantity'] ?? 0 }}</td>
                    <td class="text-center">{{ number_format($item['Purchase_price'] ?? 0) }}</td>
                    @php
                        $PurchaseTotal = ($item['Purchase_price'] ?? 0) * ($item['SumQuantity'] ?? 0);
                        $Purchase_priceTotal += $PurchaseTotal;
                    @endphp
                    <td class="text-center">{{ number_format($PurchaseTotal, 2) }}</td>
                </tr>
                @endif

            @endif
             @endforeach
            <tr class="bg-blue-100">
                <th colspan="6"></th>
                <th colspan="" class="text-center">الاجمالي</th>
                <tr>
                    <td colspan="6"></td>
                    <td class="text-center text-red-700">{{number_format($Purchase_priceTotal , 2 )}}</td>
                </tr>
            </tr>
        </tbody>
    </table>

@endisset