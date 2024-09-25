@extends('products.layout')
@section('prod')


<div class="-translate-x-[40%] w-1/2">

    <form action="{{route('Category.update',$prod->categorie_id)}}" method="POST">
        @csrf
        @method('PUT')
            <div class="border-b flex justify-between text-sm">
                <div class="w-full border-x border-y border-orange-950 rounded-xl">
                    <div class="px-1">
                    <label for="barcod" class="btn">اسم الوحدة</label>
                    <input name="cate" type="text" value="{{$prod->Categorie_name}}" class="inputSale" />
                    </div>
                <div id="newProduc" class="py-2 mr-1 flex justify-between ml-1">
                    <button class="flex bg-green-500 hover:bg-green-700 text-white font-bold  py-2 px-4 rounded">
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g id="Edit / Add_Plus_Circle">
                                    <path id="Vector" d="M8 12H12M12 12H16M12 12V16M12 12V8M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </g>
                        </svg>
                         تعديل
                    </button>
                </div>
                </div>
            </div>

    </form>


</div>


@endsection
