@isset($buss)
<div class="header-section border border-gray-300 rounded-lg shadow-md p-4 mb-4">
    <div class="grid grid-cols-3 gap-4 items-center">
        <div class="text-right">
            <h2 class="text-1xl font-bold text-blue-700 mb-2">{{ $buss->Company_Name }}</h2>
            <p class="text-gray-600">{{ $buss->Services }}</p>
            <p class="text-gray-600">العنوان: {{ $buss->Company_Address }}</p>
            <p class="text-gray-600">التلفون: {{ $buss->Phone_Number }}</p>
        </div>
        <div class="flex justify-center">
            <img class="w-32 h-32 rounded-lg shadow" src="{{ url($buss->Company_Logo ? 'images/' . $buss->Company_Logo : '') }}" alt="Logo">
        </div>
        <div class="text-left">
            <h2 class="text-1xl font-bold text-blue-700 mb-2">{{ $buss->Company_NameE }}</h2>
            <p class="text-gray-600">{{ $buss->ServicesE }}</p>
            <p class="text-gray-600">Address: {{ $buss->Company_AddressE }}</p>
            <p class="text-gray-600">Phone: {{ $buss->Phone_Number }}</p>
        </div>
    </div>
</div>
@endisset