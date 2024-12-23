@extends('daily_restrictions.index')
@section('restrictions')



<div class="w-full overflow-y-auto max-h-[80vh]  bg-white">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    رقم الصفحة
                </th>
                <th scope="col" class="px-6 py-3">
                    تاريخ انشاء الصفحة
                </th>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
            </tr>
        </thead>
        <tbody>

@isset($pagesNum)
    
            @foreach ($pagesNum as $pageNum)

            <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <a href="{{route('all_restrictions_show',$pageNum->page_id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$pageNum->page_id}}</a>

                </th>
                <td class="px-6 py-4">
                    @php



    $date = $pageNum->created_at;
    $carbonDate = \Carbon\Carbon::parse($date);

    $arabicDay = $carbonDate->format('l'); // Get the day of the week in English
    $arabicDays = [
        'Sunday' => 'الأحد',
        'Monday' => 'الإثنين',
        'Tuesday' => 'الثلاثاء',
        'Wednesday' => 'الأربعاء',
        'Thursday' => 'الخميس',
        'Friday' => 'الجمعة',
        'Saturday' => 'السبت',

    ];
                @endphp
                    {{$pageNum->created_at.":".$arabicDays[$arabicDay]}}
                </td>
                <td class="px-6 py-4 ">
                    <a href="{{route('all_restrictions_show',$pageNum->page_id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">اظهار</a>
                </td>
            </tr>
            @endforeach
            @endisset


        </tbody>
    </table>
</div>

@endsection
