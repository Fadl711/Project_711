    <table class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
        <thead>
            <tr class="bg-blue-100">

                <th class="text-right">#</th>
                <th class="text-right">اسم الصنف</th>
                <th class="text-center">رقم الصنف</th>
                <th class="text-center"> الوحدة</th>
                <th class="text-center">المخزن</th>
                <th class="text-center">الكمية المتوفره</th>
         
            </tr>
        </thead>

        </thead>
        <tbody >
            @php
            $Purchase_priceTotal = 0;
            $displayedProducts = [];
        @endphp
        
       
@isset($allQuantityonly)
        @foreach ($allQuantityonly as $index => $item)
        {{-- @dd($item->product_name) --}}
            {{-- @if (!in_array($item->product_id, $displayedProducts)) --}}
                {{-- @php
                    $displayedProducts[] = $item->product_id;
                @endphp --}}
                  @php
                 $sum_quantity= ($item->purchaseToQuantity+$item->saleQuantity5)-$item->warehouseFromQuantity-$item->warehouseFromQuantity3 -$item->saleQuantity4 ?? 0; 
             @endphp
                @if ($sum_quantity)
                    
                <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="px-4 text-center">{{ $index + 1 }}</td>
                    <td class="text-right">{{ $item->product_name  }}</td>
                    <td class="text-center">{{ $item->product_id }}</td>
                    <td class="text-center">{{ $item->categories->Categorie_name ?? '' }}</td>
                    <td class="text-center">{{ $item->warehouse_name ?? '' }}</td>

                    <td class="text-center *@if ($sum_quantity<0)
                    bg-green-500
                        
                    @endif">
                        {{ $sum_quantity?? 0 }}
                    </td>
                  
                </tr>
                {{-- @endif --}}

            @endif
             @endforeach
                          @endisset

             @isset($firstQuantityonly)
             {{-- @dd($firstQuantityCosts) --}}
             @php
                 $sum_quantity= ($firstQuantityonly->purchaseToQuantity+$firstQuantityonly->saleQuantity5 )-$firstQuantityonly->warehouseFromQuantity-$firstQuantityonly->warehouseFromQuantity3 -$firstQuantityonly->saleQuantity4 ?? 0; 
             @endphp
                 <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="px-4 text-center">1 </td>

                    <td class="text-right">{{  $firstQuantityonly->product_name  }}</td>
                    <td class="text-center">{{ $firstQuantityonly->product_id }}</td>
                    <td class="text-center">{{ $firstQuantityonly->categories->Categorie_name ?? '' }}</td>
                    <td class="text-center">{{ $firstQuantityonly->warehouse_name ?? '' }}</td>
                    <td class="text-center">{{ $sum_quantity ?? 0 }}</td>
                </tr>
             @endisset
        </tbody>
    </table>


