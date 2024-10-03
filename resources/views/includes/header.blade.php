@isset($buss)

<div class=" border-[1px] border-black rounded-b-lg my-2  ">
    <div class="bg-gray-200 p-2 rounded-b-lg  flex justify-between w-full ">
        <div class="text-right text-[10px] items-center">
            <h2 class=" font-bold ">{{$buss->Company_Name}}</h2>
            <p>{{$buss->Services}}</p>
            <p>العنوان:{{$buss->Company_Address}}</p>
            <p>التلفون:{{$buss->Phone_Number}}</p>
        </div>
        <div class="flex items-center justify-center">
            <div class="w-16 h-16 bg-gray-300 flex items-center justify-center translate-x-8 ">
                <img class="" src="{{url($buss->Company_Logo ? 'images/'.$buss->Company_Logo.'': "")}}" alt="">
            </div>
        </div>
        <div class="text-left text-[10px] items-center">
            <h2 class=" font-bold ">{{$buss->Company_NameE }}</h2>
            <p>{{$buss->ServicesE}}</p>
            <p>Address: {{$buss->Company_AddressE}}</p>
            <p>Phone: {{$buss->Phone_Number}}</p>
        </div>
    </div>
</div>
@endisset
