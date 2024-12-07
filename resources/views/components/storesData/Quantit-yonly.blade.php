@isset($productData)
    
<div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr class="bg-blue-100">
                <th class=" px-2 py-1  tagTd">رقم الصنف</th>
                <th class=" px-2 py-1  tagTd">اسم الصنف</th>
                <th class=" px-2 py-1  tagTd"> الوحدة</th>
                <th class="py-1 px-2 tagTd">  وصف الصنف</th>
                <th class="py-1 px-2 tagTd">المخزن</th>
                <th class="py-1 px-2 tagTd">الكمية المتوفره</th>
            </tr>
        </thead>
        <tbody class=" text-sm font-light">
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-1 px-2">{{ $productData->product_id }}</td>
                <td class="py-1 px-2">{{ $productData->product_name }}</td>
                <td class="py-1 px-2">@isset($categories)
                    {{$categories->Categorie_name ??''}}
                    
                    @endisset</td>
                    <td class="py-1 px-2">{{ $productData->note??'' }}</td>
                    <td class="py-1 px-2">@isset($warehouseName)
                        {{$warehouseName ??''}}
                        
                        @endisset</td>
               
                <td class="py-1 px-2">@isset($productPurchase)
                    {{$productPurchase ??0}}
                    
                @endisset</td>
            </tr>
        </tbody>
    </table>
</div>
@endisset