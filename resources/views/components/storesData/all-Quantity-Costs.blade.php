    <table class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
        <thead>
            <tr class="bg-blue-100">
                <th class="text-right">#</th>
                <th class="text-right">اسم الصنف</th>
                <th class="text-center">رقم الصنف</th>
                <th class="text-center"> الوحدة</th>
                <th class="text-center">الكمية المتوفره</th>
                <th class="text-center"> التكلفة</th>
                <th class="text-center"> اجمالي التكلفة</th>
            </tr>
        </thead>
        <tbody >
            @php
            $Purchase_priceTotal = 0;
            $displayedProducts = [];
           @endphp
    @isset($allQuantityCosts)
            @foreach ($allQuantityCosts as $index => $item)

               @php
                  $sum_quantity= ($item->purchaseToQuantity+$item->saleQuantity5 )-$item->warehouseFromQuantity-$item->warehouseFromQuantity3 -$item->saleQuantity4 ?? 0; 
                  $unit_quantity = $item->unit_quantity ?? 1; 
                  $Purchase_price=  $item->unit_price?? $item->Purchase_price;
                  $sumquantity=  $sum_quantity / $unit_quantity ??1; 
                  $PurchaseTotal = ($Purchase_price) * ($sumquantity ?? 0);
                  $Purchase_priceTotal += $PurchaseTotal;
                @endphp
                @if ($sum_quantity)
                    
                    <tr class="border-b border-gray-200 hover:bg-gray-100" >
                        <td class="px-4 text-center">{{ $index + 1 }}</td>
                        <td class="text-right">{{ $item->product_name  }}</td>
                        <td class="text-center">{{ $item->product_id }}</td>
                        <td class="text-center">{{ $item->category_name ?? '' }}</td>                
                        <td class="text-center">{{ number_format($sumquantity)??0}}</td>
                        <td class="text-center">{{ number_format($Purchase_price?? 0) }}</td>
                        <td class="text-center">{{ number_format($PurchaseTotal, 2) }}</td>
                     </tr>

             @endif
          @endforeach
       @endisset
   @isset($firstQuantityCosts)
             @php
                 $sum_quantity= ($firstQuantityCosts->purchaseToQuantity+$firstQuantityCosts->saleQuantity5 )-$firstQuantityCosts->warehouseFromQuantity-$firstQuantityCosts->warehouseFromQuantity3 -$firstQuantityCosts->saleQuantity4 ?? 0; 
                 $unitPrice = $firstQuantityCosts->category->Quantityprice ?? 1;
                 $Purchase_price=  $firstQuantityCosts->category->Purchase_price??$firstQuantityCosts->Purchase_price;
                 $suMquantity=  $sum_quantity / $unitPrice ??1; 
                 $PurchaseTotal = ($Purchase_price) * ($suMquantity ?? 0);
                 $Purchase_priceTotal = $PurchaseTotal;
           @endphp
                 <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="px-4 text-center">1 </td>
                    <td class="text-right">{{  $firstQuantityCosts->product_name  }}</td>
                    <td class="text-center">{{ $firstQuantityCosts->product_id }}</td>
                    <td class="text-center">{{ $firstQuantityCosts->category->Categorie_name ?? '' }}</td>
                    <td class="text-center">{{ number_format($suMquantity)??0}}</td>
                    <td class="text-center">{{ number_format($Purchase_price?? 0) }}</td>
                    <td class="text-center">{{ number_format($PurchaseTotal) }}</td>
                </tr>
    @endisset
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

