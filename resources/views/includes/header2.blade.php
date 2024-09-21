@isset($com_name)

<div class=" border-2 border-black rounded-b-lg my-2 ">
    <div class="bg-gray-200 p-8 rounded-lg  flex justify-between w-full ">
        <div class="text-right">
            <h2 class=" text-xl font-bold mb-2 ">{{$com_name}}</h2>
            <p>{{$com_for}}</p>
            <p>العنوان:{{$com_address}}</p>
            <p>التلفون:{{$com_phones}}</p>
        </div>
        <div class="flex items-center justify-center">
            <div class="w-24 h-20 bg-gray-300 flex items-center justify-center translate-x-10 ">
                <img class="" src="{{url('images/'.$com_photo.'')}}" alt="">
            </div>
        </div>
        <div class="text-left ">
            <h2 class=" text-xl font-bold mb-2 ">{{$com_nameE}}</h2>
            <p>{{$com_forE}}</p>
            <p>Address: {{$com_addressE}}</p>
            <p>Phone: {{$com_phones}}</p>
        </div>
    </div>
</div>
@endisset
