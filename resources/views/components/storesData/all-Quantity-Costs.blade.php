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
        
       
@isset($allQuantityCosts)
        @foreach ($allQuantityCosts as $index => $item)
        {{-- @dd($item->product_name) --}}
            {{-- @if (!in_array($item->product_id, $displayedProducts)) --}}
                {{-- @php
                    $displayedProducts[] = $item->product_id;
                @endphp --}}
                  @php
                 $sum_quantity= ($item->purchaseToQuantity+$item->saleQuantity5 )-$item->warehouseFromQuantity-$item->warehouseFromQuantity3 -$item->saleQuantity4 ?? 0; 
             @endphp
                @if ($sum_quantity)
                    
                <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="px-4 text-center">{{ $index + 1 }}</td>

                    <td class="text-right">{{ $item->product_name  }}</td>
                    <td class="text-center">{{ $item->product_id }}</td>
                    <td class="text-center">{{ $item->categories->Categorie_name ?? '' }}</td>
                    <td class="text-center">{{ $item->warehouse_name ?? '' }}</td>
                    <td class="text-center">{{ $sum_quantity?? 0 }}</td>
                    <td class="text-center">{{ number_format($item->Purchase_price?? 0) }}</td>
                    @php
                        $PurchaseTotal = ($item->Purchase_price ?? 0) * ($sum_quantity ?? 0);
                        $Purchase_priceTotal += $PurchaseTotal;
                    @endphp
                    <td class="text-center">{{ number_format($PurchaseTotal, 2) }}</td>
                </tr>
                {{-- @endif --}}

            @endif
             @endforeach
                          @endisset

             @isset($firstQuantityCosts)
             {{-- @dd($firstQuantityCosts) --}}
             @php
                 $sum_quantity= ($firstQuantityCosts->purchaseToQuantity+$firstQuantityCosts->saleQuantity5 )-$firstQuantityCosts->warehouseFromQuantity-$firstQuantityCosts->warehouseFromQuantity3 -$firstQuantityCosts->saleQuantity4 ?? 0; 
             @endphp
                 <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="px-4 text-center">1 </td>

                    <td class="text-right">{{  $firstQuantityCosts->product_name  }}</td>
                    <td class="text-center">{{ $firstQuantityCosts->product_id }}</td>
                    <td class="text-center">{{ $firstQuantityCosts->categories->Categorie_name ?? '' }}</td>
                    <td class="text-center">{{ $firstQuantityCosts->warehouse_name ?? '' }}</td>
                    <td class="text-center">{{ $sum_quantity ?? 0 }}</td>
                    <td class="text-center">{{ number_format($firstQuantityCosts->Purchase_price?? 0) }}</td>
                    @php
                        $PurchaseTotal = ($firstQuantityCosts->Purchase_price ?? 0) * ($sum_quantity ?? 0);
                        $Purchase_priceTotal = $PurchaseTotal;
                    @endphp
                    <td class="text-center">{{ number_format($PurchaseTotal, 2) }}</td>
                </tr>
             @endisset
            <tr class="bg-blue-100">
                <th colspan="7"></th>
                <th colspan="" class="text-center">الاجمالي</th>
                <tr>
                    <td colspan="7"></td>
                    <td class="text-center text-red-700">{{number_format($Purchase_priceTotal , 2 )}}</td>
                </tr>
            </tr>
        </tbody>
    </table>

