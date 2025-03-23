<style>
     .english {
        font-family: 'Times New Roman', serif; /* الخط الإنجليزي */
    }
</style>
@isset($buss)
<div class="header-section border border-gray-300 rounded-lg shadow-md  ">
    <div class=" mx-2 flex justify-between">
        <div class="text-right pb-0">
            <h2 class="font-extrabold    ">{{ $buss->Company_Name }}</h2>
            <p class="text-sm text-gray-700">{{ $buss->Services }}</p>
            <p class="text-sm text-gray-700">العنوان: {{ $buss->Company_Address }}</p>
            <p class="text-sm text-gray-700">التلفون: {{ $buss->Phone_Number }}</p>
        </div>
        <div class="flex justify-center ">
            <img class="w-32 h-32 rounded-lg " src="{{ url($buss->Company_Logo ? 'images/' . $buss->Company_Logo : '') }}" alt="Logo">
        </div>
        <div class="text-left  english " >
            <h2 class="font-extrabold text-sm  ">{{ $buss->Company_NameE }}</h2>
            <div class=" text-sm  text-gray-700">{{ $buss->ServicesE }}</div>
            <div class="text-sm text-gray-700">Address: {{ $buss->Company_AddressE }}</div>
            <div class="text-sm text-gray-700">Phone: {{ $buss->Phone_Number }}</div>
        </div>
    </div>
</div>
@endisset
