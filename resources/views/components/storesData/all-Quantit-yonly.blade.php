    <table class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
        <thead>
            <tr class="bg-blue-100">

                <th class="text-center">رقم الصنف</th>
                <th class="text-right">اسم الصنف</th>
                @isset ($allQuantityonly  )
                    
                <th class="text-center"> الوحدة</th>
                @endisset
                @isset ( $firstQuantityonly )
                    
                <th class="text-center"> الوحدة</th>
                @endisset
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
       
                  @php
          $sum_quantity= (float)($item->purchaseToQuantity+$item->saleQuantity5)-$item->warehouseFromQuantity-$item->warehouseFromQuantity3 -$item->saleQuantity4 ?? 0; 
             @endphp
                @if ($sum_quantity)
                    @php

        $unit_quantity = $item->unit_quantity ?? $item->categories->first()->Quantityprice ;
    $category_name = $item->category_name?? $item->categories->first()->Categorie_name;
    // $unit_quantity = $item->unit_quantity ?? 1;
    // تجنب القسمة على صفر
    $divisor = $unit_quantity != 0 ? $unit_quantity : 1;
    // حساب الكمية مع الكسور
    $sumquantity = $sum_quantity / $divisor;
    
    // حساب الباقي مع الكسور
    $remainder = fmod($sum_quantity, $divisor);
    

@endphp
                <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="text-center"> {{ $item->product_id }}
                                                -0101  
                </td>
                    <td class="text-right">{{ $item->product_name  }}</td>
<td class="text-center">
    {{  $category_name  ?? '' }}
</td>                  


<td class="text-center @if($sumquantity < 0) bg-green-300 @endif">
    @if($remainder != 0)
    <span class=" text-red-700 mt-1">
      {{ $remainder }}. 
    </span>

      @endif
        {{ floor($sumquantity) }}

</td>
                </tr>
            @endif
             @endforeach
                          @endisset
       
@isset($QuantityNotAvailable)
        @foreach ($QuantityNotAvailable as $index => $item)
       
                  @php
          $sum_quantity= (float)($item->purchaseToQuantity+$item->saleQuantity5)-$item->warehouseFromQuantity-$item->warehouseFromQuantity3 -$item->saleQuantity4 ?? 0; 
             @endphp
                @if ($sum_quantity==0)
                  
    


                <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="text-center"> {{ $item->product_id }}
                                                -0101  
                </td>
                    <td class="text-right">{{ $item->product_name  }}</td>
                 


<td class="">
  
    <span class=" text-red-700 mt-1">
      {{ $sum_quantity??0 }}
    </span>


</td>
                </tr>
                @endif
             @endforeach
                          @endisset
             @isset($firstQuantityonly)
              @php

              
                 $sum_quantity= (float)($firstQuantityonly->purchaseToQuantity+$firstQuantityonly->saleQuantity5)-$firstQuantityonly->warehouseFromQuantity-$firstQuantityonly->warehouseFromQuantity3 -$firstQuantityonly->saleQuantity4 ?? 0; 

                       $unit_quantity = $firstQuantityonly->category->Quantityprice ?? $firstQuantityonly->categories->first()->Quantityprice ;
    $category_name = $firstQuantityonly->category->Categorie_name?? $firstQuantityonly->categories->first()->Categorie_name;

          
             @endphp
                 <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="text-center">{{ $firstQuantityonly->product_id }}</td>
                    <td class="text-right">{{  $firstQuantityonly->product_name  }}</td>
                    <td class="text-center">   {{ $category_name?? '' }}</td>
@php
    $unit_quantity =  $unit_quantity?? 1;
    // تجنب القسمة على صفر
    $divisor = $unit_quantity != 0 ? $unit_quantity : 1;
    // حساب الكمية مع الكسور
    $sumquantity = $sum_quantity / $divisor;
    // حساب الباقي مع الكسور
    $remainder = fmod($sum_quantity, $divisor);
@endphp
<td class="text-center @if($sumquantity < 0) bg-green-300 @endif">
      @if($category_name && $unit_quantity>1)
    @if($remainder < 0 || $remainder > 0)
    <span class=" text-red-700 mt-1">
{{$remainder}}.</span>
    @endif
       @endif
  {{floor($sumquantity)}}
</td> 
                </tr>
                
             @endisset
        </tbody>
    </table>


