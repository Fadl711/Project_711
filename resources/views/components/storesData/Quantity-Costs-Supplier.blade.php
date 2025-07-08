@isset($QuantityCostsSupplier)
    <table class=" text-sm font-semibold  w-full overflow-y-auto max-h-[80vh] ">
        <thead>
            <tr class="bg-blue-100">
                <th class="text-center text-sm">رقم الصنف</th>
                <th class="text-center text-sm">اسم الصنف</th>
                <th class="text-center">الوحدة</th>
                <th class="text-center"> الكمية</th>
                {{-- <th class="text-right">التكلفة عند الشراء</th> --}}
                {{-- <th class="text-right">إجمالي التكلفة عند الشراء</th> --}}
                <th class="text-center">مردود المبيعات</th>
                <th class="text-center">مردود المشتريات</th>
                <th class="text-center">التكلفة الشراء</th>
                <th class="text-center">إجمالي التكلفة </th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentSupplier = null;
            @endphp
            
            @foreach ($QuantityCostsSupplier as $item)
                @if($item->sub_account_id)
                    
                    @php $currentSupplier = $item->sub_account_id; @endphp
             
                
                @php
                    // حساب الكمية الحالية
                   
                    
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
                    <td class="text-center">{{ $item->categories->Categorie_name ?? '' }}</td>
                    <td class="text-center"> 
                           {{ $item->purchaseToQuantity }}
                           
                          
                                

                    </td>
                    {{-- <td class="text-right">{{ ($purchaseUnitPrice) }}</td> --}}
                    {{-- <td class="text-right">{{ (($item->lastPurchase ?? null) )}}</td> --}}
                    <td class="text-center">{{ ($item->astsaleQuantity ?? null) }}</td>
                    <td class="text-center">{{ ($item->returnPurchaseToQuantity ?? null) }}</td>
                    <td class="text-center">{{ number_format($purchaseUnitPrice ?? 0) }}</td>
                    <td class="text-center">{{ number_format($item->lastTotal, 2) }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endisset