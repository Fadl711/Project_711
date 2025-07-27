    <table class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
        <thead>
            <tr class="bg-blue-100">
                <th class="text-center">رقم الصنف</th>
                <th class="text-right">اسم الصنف</th>
                <th class="text-center"> الوحدة</th>
                <th class="text-center">الكمية </th>
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
$sum_quantity= (float)($item->purchaseToQuantity+$item->saleQuantity5)-$item->warehouseFromQuantity-$item->warehouseFromQuantity3 -$item->saleQuantity4 ?? 0; 

            @endphp

    @if ($sum_quantity > 0)
               @php

    $unit_quantity = $item->unit_quantity ?? $item->categories->first()->Quantityprice ;
    $Purchase_price = $item->unit_price ?? $item->categories->first()->Purchase_price;
    $category_name = $item->category_name?? $item->categories->first()->Categorie_name;
    // تجنب القسمة على صفر
    $divisor = $unit_quantity != 0 ? $unit_quantity : 1;
    // حساب الكمية مع الكسور
    $sumquantity = $sum_quantity / $divisor;
    // حساب الباقي مع الكسور
    $remainder = fmod($sum_quantity, $divisor);
    $PurchaseTotal=$sumquantity * $Purchase_price;
                      $Purchase_priceTotal += $PurchaseTotal;
@endphp
            
                    
                    <tr class="border-b border-gray-200 hover:bg-gray-100" >
                        <td class="text-center"> 
                          
                            {{ $item->product_id }}
                            
                            -0101  
                           
                        </td>
                        <td class="text-right">{{ $item->product_name  }}</td>
<td class="text-center">
    {{ $category_name ?? '' }}
</td>                  
                   
<td class="text-center @if($sumquantity < 0) bg-green-300 @endif">
   @if($category_name && $unit_quantity>1)
    @if($remainder != 0)
    <span class=" text-red-700 mt-1">
     {{ $remainder }}. 
    </span>

    @endif
       @endif
 {{ floor($sumquantity) }}
</td>
        <td class="text-center">{{ number_format($Purchase_price?? 0) }}</td>
                    <td class="text-center">{{ number_format($PurchaseTotal) }}</td>
                </tr>

             @endif
          @endforeach
       @endisset
    @isset($excessQuantitiesCostsAllStores)
            @foreach ($excessQuantitiesCostsAllStores as $index => $item)
            @php
$sum_quantity= (float)($item->purchaseToQuantity+$item->saleQuantity5)-$item->warehouseFromQuantity-$item->warehouseFromQuantity3 -$item->saleQuantity4 ?? 0; 

            @endphp

    @if ($sum_quantity < 0)
               @php

    $unit_quantity = $item->unit_quantity ?? $item->categories->first()->Quantityprice ;
    $Purchase_price = $item->unit_price ?? $item->categories->first()->Purchase_price;
    $category_name = $item->category_name?? $item->categories->first()->Categorie_name;
    // تجنب القسمة على صفر
    $divisor = $unit_quantity != 0 ? $unit_quantity : 1;
    // حساب الكمية مع الكسور
    $sumquantity = $sum_quantity / $divisor;
    // حساب الباقي مع الكسور
    $remainder = fmod($sum_quantity, $divisor);
    $PurchaseTotal=$sumquantity * $Purchase_price;
                      $Purchase_priceTotal += $PurchaseTotal;
@endphp
            
                    
                    <tr class="border-b border-gray-200 hover:bg-gray-100" >
                        <td class="text-center"> 
                          
                            {{ $item->product_id }}
                            
                            -0101  
                           
                        </td>
                        <td class="text-right">{{ $item->product_name  }}</td>
<td class="text-center">
    {{ $category_name ?? '' }}
</td>                  
                   
<td class="text-center @if($sumquantity < 0) bg-green-300 @endif">
   @if($category_name && $unit_quantity>1)
    @if($remainder != 0)
    <span class=" text-red-700 mt-1">
     {{ $remainder }}. 
    </span>

    @endif
       @endif
 {{ floor($sumquantity) }}
</td>
        <td class="text-center">{{ number_format($Purchase_price?? 0) }}</td>
                    <td class="text-center">{{ number_format($PurchaseTotal) }}</td>
                </tr>

             @endif
          @endforeach
       @endisset
   @isset($firstQuantityCosts)
          @php
                 $sum_quantity= (float)($firstQuantityCosts->purchaseToQuantity+$firstQuantityCosts->saleQuantity5)-$firstQuantityCosts->warehouseFromQuantity-$firstQuantityCosts->warehouseFromQuantity3 -$firstQuantityCosts->saleQuantity4 ?? 0;
                   $unit_quantity = $firstQuantityCosts->category->Quantityprice ?? $firstQuantityCosts->categories->first()->Quantityprice ;
                   $Purchase_price = $firstQuantityCosts->category->Purchase_price ?? $firstQuantityCosts->categories->first()->Purchase_price ;
    $category_name = $firstQuantityCosts->category->Categorie_name?? $firstQuantityCosts->categories->first()->Categorie_name;


             @endphp
                 <tr class="border-b border-gray-200 hover:bg-gray-100" >
                     <td class="text-center">{{ $firstQuantityCosts->product_id }} 
                        -0101
                     </td>
                    <td class="text-right">{{  $firstQuantityCosts->product_name  }}</td>
                    <td class="text-center">{{ $category_name ?? '' }}</td>
                    @php
    // تجنب القسمة على صفر
    $divisor = $unit_quantity != 0 ? $unit_quantity : 1;
    // حساب الكمية مع الكسور
    $sumquantity = $sum_quantity / $divisor;
    // حساب الباقي مع الكسور
    $remainder = fmod($sum_quantity, $divisor);
    $PurchaseTotal=$sumquantity * $Purchase_price;
                          $Purchase_priceTotal += $PurchaseTotal;

@endphp
<td class="text-center @if($sumquantity < 0) bg-green-300 @endif">
    @if($category_name&& $unit_quantity>1)
    @if($remainder != 0)
    <span class=" text-red-700 mt-1">
       {{ $remainder }} . 
    </span>

    @endif
       @endif
 {{ floor($sumquantity) }} 
</td>
        <td class="text-center">{{ number_format($Purchase_price,2) ??0}}</td>
                    <td class="text-center">{{ number_format($PurchaseTotal,2) }}</td>
                </tr>
    @endisset
            <tr class="bg-blue-100">
                <th colspan="5"></th>
                <th colspan="" class="text-center">الاجمالي</th>
                <tr>
                    <td colspan="5"></td>
                    <td class="text-center text-red-700">{{number_format($Purchase_priceTotal,2  )}}</td>
                </tr>
            </tr>
        </tbody>
    </table>

