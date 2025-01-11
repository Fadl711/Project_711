<div class="flex justify-evenly  text-sm  items-center px-4 p-2  bg-gradient-to-t text-white from-indigo-900 to-indigo-600 rounded-md shadow-sm font-medium capitalize">
    <a href="{{route('products.index')}}" class=" {{ Request::is('products') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white "> المنتجات</a>
    <a href="{{route('products.create')}}" class="{{ Request::is('products/create') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white ">اضافة منتاج </a>

</div>
<br>
