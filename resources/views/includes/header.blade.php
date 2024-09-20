@isset($com_name)

<div class=" border-[1px] border-black rounded-b-lg my-2  ">
    <div class="bg-gray-200 p-2 rounded-b-lg  flex justify-between w-full ">
        <div class="text-right text-[10px] items-center">
            <h2 class=" font-bold ">{{$com_name}}</h2>
            <p>{{$com_for}}</p>
            <p>العنوان:{{$com_address}}</p>
            <p>التلفون:{{$com_phones}}</p>
        </div>
        <div class="flex items-center justify-center">
            <div class="w-16 h-16 bg-gray-300 flex items-center justify-center translate-x-8 ">
                <img class="" src="{{url('images/'.$com_photo.'')}}" alt="">
            </div>
        </div>
        <div class="text-left text-[10px] items-center">
            <h2 class=" font-bold ">{{$com_nameE}}</h2>
            <p>{{$com_forE}}</p>
            <p>Address: {{$com_addressE}}</p>
            <p>Phone: {{$com_phones}}</p>
        </div>
    </div>
</div>
@endisset
