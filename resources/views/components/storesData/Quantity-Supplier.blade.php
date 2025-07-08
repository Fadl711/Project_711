@isset($QuantitySupplier)
<div class="container mx-auto print-container">
    <table class="w-full text-sm  bg-white ">
          
            <tr class="bg-blue-100">
                <th class="text-right">رقم الصنف</th>
                <th class="text-right">اسم الصنف</th>
                <th class="text-right"> الوحدة</th>
                <th class="text-center">الكمية الشراء</th>
                <th class="text-center">مردود المبيعات</th>
                <th class="text-center">مردود المشتريات</th>
            </tr>
        </thead>
        <tbody >
            @foreach ($QuantitySupplier as $item)
            @if($item->sub_name )
            <tr class="bg-[#f0cb46]">
                <td colspan="4" class="text-right">اسم المورد : {{ $item->sub_name  }}</td>
                <td class="bg-[#fffffe]"></td>
                {{-- <td class="bg-[#fffffe]"></td> --}}

                <td colspan="2" class="text-center"> حركة المنتج الخاص بالمورد</td>
            </tr>
            
    <tr class="border-b border-gray-200 hover:bg-gray-100">
        <td class="text-center">{{ $item->product_id }}</td>
        <td class="text-center">{{ $item->product_name }}</td>
        <td class="text-center">{{ $item->categories->Categorie_name??"" }}</td>
        <td class="text-center">{{ $item->purchaseToQuantity }}</td>
        <td class="text-center">{{ $item->saleQuantity5 }}</td>
        <td class="text-center">{{ $item->returnPurchaseToQuantity }}</td>
     
    </tr>
@endif
@endforeach
        </tbody>
    </table>
</div>
@endisset