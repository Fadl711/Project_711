    <table class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
        <thead>
            <tr class="bg-blue-100">

                <th class="text-right">#</th>
                <th class="text-right">اسم الصنف</th>
                <th class="text-center">رقم الصنف</th>
                <th class="text-center"> الوحدة</th>
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
                    
                <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="px-4 text-center">{{ $index + 1 }}</td>
                    <td class="text-right">{{ $item->product_name  }}</td>
                    <td class="text-center">{{ $item->product_id }}</td>
<td class="text-center">
    {{ $item->category_name ?? '' }}
</td>                  
@php
    $unit_quantity = $item->unit_quantity ?? 1;
    // تجنب القسمة على صفر
    $divisor = $unit_quantity != 0 ? $unit_quantity : 1;
    // حساب الكمية مع الكسور
    $sumquantity = $sum_quantity / $divisor;
    
    // حساب الباقي مع الكسور
    $remainder = fmod($sum_quantity, $divisor);
    

@endphp

<td class="text-center @if($sumquantity < 0) bg-green-300 @endif">
    {{ $sumquantity }}

    @isset($item->category_name) 
    @if($remainder != 0)
    <p class="text-xs text-gray-500 mt-1">
        التفاصيل: 
        {{ floor($sumquantity) }} × {{ $unit_quantity }} = {{ floor($sumquantity) * $unit_quantity }} 
        + باقي {{ $remainder }}
    </p>

    @endif
      @endisset
</td>
                </tr>
            @endif
             @endforeach
                          @endisset
             @isset($firstQuantityonly)
              @php
                 $sum_quantity= (float)($firstQuantityonly->purchaseToQuantity+$firstQuantityonly->saleQuantity5)-$firstQuantityonly->warehouseFromQuantity-$firstQuantityonly->warehouseFromQuantity3 -$firstQuantityonly->saleQuantity4 ?? 0; 
             @endphp
                 <tr class="border-b border-gray-200 hover:bg-gray-100" >
                    <td class="px-4 text-center">1 </td>
                    <td class="text-right">{{  $firstQuantityonly->product_name  }}</td>
                    <td class="text-center">{{ $firstQuantityonly->product_id }}</td>
                    <td class="text-center">{{ $firstQuantityonly->category->Categorie_name ?? '' }}</td>
                    @php
    $unit_quantity = $firstQuantityonly->category->Quantityprice ?? 1;
    // تجنب القسمة على صفر
    $divisor = $unit_quantity != 0 ? $unit_quantity : 1;
    // حساب الكمية مع الكسور
    $sumquantity = $sum_quantity / $divisor;
    // حساب الباقي مع الكسور
    $remainder = fmod($sum_quantity, $divisor);
@endphp
<td class="text-center @if($sumquantity < 0) bg-green-300 @endif">
    {{ $sumquantity }}
    @if($firstQuantityonly->category->Categorie_name && $unit_quantity>1)
    @if($remainder != 0)
    <p class="text-xs text-gray-500 mt-1">
        التفاصيل: 
        {{ floor($sumquantity) }} × {{ $unit_quantity }} = {{ floor($sumquantity) * $unit_quantity }} 
        + باقي {{ $remainder }}
    </p>

    @endif
       @endif

</td>
                </tr>
             @endisset
        </tbody>
    </table>


