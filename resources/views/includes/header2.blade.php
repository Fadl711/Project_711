@isset($buss)

<div class=" border-2 border-black rounded-b-lg my-2 ">
    <div class="bg-gray-200 p-8 rounded-lg  flex justify-between w-full ">
        <div class="text-right">
            <h2 class=" text-xl font-bold mb-2 ">{{$buss->Company_Name}}</h2>
            <p>{{$buss->Services}}</p>
            <p>العنوان:{{$buss->Company_Address}}</p>
            <p>التلفون:{{$buss->Phone_Number}}</p>
        </div>
        <div class="flex items-center justify-center">
            <div class="w-24 h-20 bg-gray-300 flex items-center justify-center translate-x-10 ">
                <img class="" src="{{url($buss->Company_Logo ? 'images/'.$buss->Company_Logo.'': "")}}" alt="">
            </div>
        </div>
        <div class="text-left ">
            <h2 class=" text-xl font-bold mb-2 ">{{$buss->Company_NameE }}</h2>
            <p>{{$buss->ServicesE}}</p>
            <p>Address: {{$buss->Company_AddressE}}</p>
            <p>Phone: {{$buss->Phone_Number}}</p>
        </div>
    </div>
</div>
@endisset
