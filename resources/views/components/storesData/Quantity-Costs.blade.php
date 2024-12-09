@isset($productDataCosts)
    
<div class="overflow-x-auto">
    <table class="w-full text-sm  bg-white ">

        <tr class="bg-blue-100">
            <th class="text-right">رقم الصنف</th>
            <th class="text-right">اسم الصنف</th>
            <th class="text-right"> الوحدة</th>
            <th class="text-right">المخزن</th>
            <th class="text-right">الكمية المتوفره</th>
            <th class="text-right"> التكلفة</th>
            <th class="text-right"> الجالي التكلفة</th>
        </tr>
    </thead>
    <tbody >
            <tr class="border-b border-gray-200 hover:bg-gray-100">
             
                <td class="">{{ $productDataCosts->product_id }}</td>
                <td class="">{{ $productDataCosts->product_name }}</td>
                <td class="">@isset($categories){{$categories->Categorie_name ??''}}@endisset</td>
                    <td class="">@isset($warehouseName){{$warehouseName ??''}}@endisset</td>
               
                <td class="">@isset($productPurchase){{$productPurchase ??0}}@endisset</td>
                <td class="">{{ number_format($productDataCosts->Purchase_price) }}</td>
                <td class="">{{ number_format($productDataCosts->Purchase_price *$productPurchase ) }}</td>

            </tr>
        </tbody>
    </table>
</div>
@endisset
