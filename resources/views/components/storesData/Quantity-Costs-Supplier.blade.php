<table class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
       
        <thead>
            <tr class="bg-blue-100">
                <th class="text-center text-sm">رقم الصنف</th>
                <th class="text-center text-sm">اسم الصنف</th>
                <th class="text-center">الوحدة</th>
                <th class="text-center"> الكمية</th>
                <th class="text-center">مردود المبيعات</th>
                <th class="text-center">مردود المشتريات</th>
                <th class="text-center">التكلفة الشراء</th>
                <th class="text-center">إجمالي التكلفة </th>
            </tr>
        </thead>
        <tbody>
             @isset($QuantityCostsSupplier)
            @php
                $currentSupplier = null;
            @endphp
            
            @foreach ($QuantityCostsSupplier as $item)
                @if($item->sub_name)
                    
             
               @php
                                $purchaseToQuantity= (float)($item->purchaseToQuantity)?? 0; 
                                $saleQuantity5= (float)($item->saleQuantity5)?? 0; 
                                $returnPurchaseToQuantity= (float)($item->returnPurchaseToQuantity)?? 0; 


        $unit_quantity = $item->unit_quantity ?? $item->categories->first()->Quantityprice ;
    $Purchase_price = $item->unit_price ?? $item->categories->first()->Purchase_price;
    $category_name = $item->category_name?? $item->categories->first()->Categorie_name;
    // تجنب القسمة على صفر
    $divisor = $unit_quantity != 0 ? $unit_quantity : 1;
    // حساب الكمية مع الكسور
    $sumquantity = $purchaseToQuantity / $divisor;
    $sumAstsaleQuantity = $saleQuantity5 / $divisor;
    $sumReturnPurchaseToQuantity = $returnPurchaseToQuantity / $divisor;
    // حساب الباقي مع الكسور
    $remainder = fmod($purchaseToQuantity, $divisor);
    $remainderAstsaleQuantity = fmod($saleQuantity5, $divisor);
    $remainderReturnPurchaseToQuantity = fmod($returnPurchaseToQuantity, $divisor);
    // $PurchaseTotal=$sumquantity * $Purchase_price;
                    //   $Purchase_priceTotal += $PurchaseTotal;

@endphp
                
                @php
                    // حساب التكلفة عند الشراء مع تجنب القسمة على صفر
                    $purchaseUnitPrice = ($item->lastTotal > 0 && $item->purchaseToQuantity > 0) 
                                      ? $item->lastTotal / $item->purchaseToQuantity 
                                      : 0;
                @endphp
                
                 <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="text-center">{{ $item->product_id }}</td>
                    <td class="text-center ">
                        
                        <div>   {{ $item->product_name }}</div>
                                <div class="mt-1">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">
                        اسم المورد: {{ $item->sub_name }}
                                    </span>
                                </div>




                    </td>
                    <td class="text-center">{{ $category_name ?? '' }}</td>
                   
                    <td class="text-center @if($sumquantity < 0) bg-green-300 @endif">
    @if($item->category_name && $unit_quantity>1)
    @if($remainder < 0 || $remainder > 0)
    <span class=" text-red-700 mt-1">
         
         {{ $remainder }}.
    </span>

    @endif
       @endif
               {{ floor($sumquantity) }} 


</td>
                   
                    <td class="text-center @if($sumAstsaleQuantity < 0) bg-green-300 @endif">
                        @if($item->category_name && $unit_quantity>1)
                        @if($remainderAstsaleQuantity != 0)
    <span class=" text-red-700 mt-1">
                          {{ $remainderAstsaleQuantity }}.
                        </span>
                    
                        @endif
                           @endif
        {{ floor($sumAstsaleQuantity) }} 

</td>
                    <td class="text-center @if($sumReturnPurchaseToQuantity < 0) bg-green-300 @endif">
                        @if($item->category_name && $unit_quantity>1)
                        @if($remainderReturnPurchaseToQuantity != 0)
    <span class=" text-red-700 mt-1">
                               {{ $remainderReturnPurchaseToQuantity }}.
                            
                        </span>
                        
                        @endif
                        @endif
                        {{ floor($sumReturnPurchaseToQuantity) }} 

</td>
     
              
                    <td class="text-center">{{ number_format($purchaseUnitPrice ) }}</td>
                    <td class="text-center">{{ number_format($item->lastTotal) }}</td>
                </tr>
                @endif
            @endforeach
            @endisset



             @isset($SelecetQuantityCostsSupplier)

               @php
                $currentSupplier = null;
                $Purchase_priceTotal = 0;
                $Quantity = 0;

            @endphp
            
            @foreach ($SelecetQuantityCostsSupplier as $item)
                @if($item->sub_name)
                    
             
               @php
                                $purchaseToQuantity= (float)($item->purchaseToQuantity)?? 0; 
                                $saleQuantity5= (float)($item->saleQuantity5)?? 0; 
                                $returnPurchaseToQuantity= (float)($item->returnPurchaseToQuantity)?? 0; 


        $unit_quantity = $item->unit_quantity ?? 1 ;
    $category_name = $item->category_name??'';
    // تجنب القسمة على صفر
    $divisor = $unit_quantity != 0 ? $unit_quantity : 1;
    // حساب الكمية مع الكسور
    $sumquantity = $purchaseToQuantity / $divisor;
    $sumAstsaleQuantity = $saleQuantity5 / $divisor;
    $sumReturnPurchaseToQuantity = $returnPurchaseToQuantity / $divisor ;
    // حساب الباقي مع الكسور
    $remainder = fmod($purchaseToQuantity, $divisor);
    $remainderAstsaleQuantity = fmod($saleQuantity5, $divisor);
    $remainderReturnPurchaseToQuantity = fmod($returnPurchaseToQuantity, $divisor);
                    //   $Purchase_priceTotal += $PurchaseTotal;
                    

@endphp
                
                 @php
                    // حساب التكلفة عند الشراء مع تجنب القسمة على صفر
                    $purchaseUnitPrice = ($item->lastTotal > 0 && $item->purchaseToQuantity > 0) 
                                      ? $item->lastTotal / $sumquantity 
                                      : 0;
                                                          
                                      $Quantity += $sumquantity ;
                                      $Purchase_priceTotal += $item->lastTotal ;
                                                            
                                                           




                @endphp
                
                 <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="text-center">{{ $item->product_id }}</td>
                    <td class="text-center ">
                        
                        <div>   {{ $item->product_name }}</div>
                                <div class="mt-1">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">
                        اسم المورد: {{ $item->sub_name }}
                                    </span>
                                </div>




                    </td>
                    <td class="text-center">{{ $category_name ?? '' }}</td>
                   
                    <td class="text-center @if($sumquantity < 0) bg-green-300 @endif">
    @if($item->category_name && $unit_quantity>1)
    @if($remainder < 0 || $remainder > 0)
    <span class=" text-red-700 mt-1">
         
         {{ $remainder }}.
    </span>

    @endif
       @endif
               {{ floor($sumquantity) }} 


</td>
                   
                    <td class="text-center @if($sumAstsaleQuantity < 0) bg-green-300 @endif">
                        @if($item->category_name && $unit_quantity>1)
                        @if($remainderAstsaleQuantity != 0)
    <span class=" text-red-700 mt-1">
                          {{ $remainderAstsaleQuantity }}.
                        </span>
                    
                        @endif
                           @endif
        {{ floor($sumAstsaleQuantity) }} 

</td>
                    <td class="text-center @if($sumReturnPurchaseToQuantity < 0) bg-green-300 @endif">
                        @if($item->category_name && $unit_quantity>1)
                        @if($remainderReturnPurchaseToQuantity != 0)
    <span class=" text-red-700 mt-1">
                               {{ $remainderReturnPurchaseToQuantity }}.

                        </span>
                        
                        @endif
                        @endif
                        {{ floor($sumReturnPurchaseToQuantity) }} 

</td>
   <td class="text-center">{{ number_format($purchaseUnitPrice ) }}</td>
                    <td class="text-center">{{ number_format($item->lastTotal) }}</td>
    
                </tr>

                @endif
            @endforeach
            @php
                  $purchasePrice = ($Purchase_priceTotal > 0 && $Quantity > 0) 
                                      ?$Purchase_priceTotal / $Quantity 
                                      : 0;
            @endphp
            <tr  class="bg-blue-100" >
                <th  colspan="2" class="" >
                </th>
                <th  colspan="2" class="" >
                                        اجمالي الكمية عند الشراء


                </th>
               
                <th  colspan="2" class="" >
                </th>
                <th  colspan="1" class="" >
                  متوسط   التكلفة  
                </th>
                <th  colspan="1" class="" >
                    اجمالي التكلفة عند الشراء
                </th>
              
            </tr>
            <tr>
                 <td  colspan="3" class="text-red-700 " >
               
                </td>
                <td  colspan="1" class="text-red-700 text-center " >
                {{
                   $Quantity  
                }}
                </td>
                <td  colspan="2" class="text-red-700 " >
               
                </td>
               
                <td  colspan="1" class="text-red-700 " >
                  {{number_format( $purchasePrice,2)}}
                </td>
                <td  colspan="1" class="text-red-700 " >
                    
                    {{number_format($Purchase_priceTotal,2)}}
                </td>
              
            </tr>
        
            @endisset
        </tbody>
    </table>
