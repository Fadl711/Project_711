@extends('layout')
@section('conm')
<x-nav-transfer-restriction/>   
<div class="w-full overflow-y-auto max-h-[80vh] container mx-auto print-container  bg-white">
    <table class="w-full text-sm overflow-y-auto max-h-[80vh]">    
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3"> عرض </th>
                <th scope="col" class="px-6 py-3">
                    رقم السجل
                </th>
                <th scope="col" class="px-6 py-3">
                    الفترة المحاسبية
                </th>
                <th scope="col" class="px-6 py-3">
                    تاريخ البد
                </th>
              
                <th scope="col" class="px-6 py-3">
                    تاريخ النتهاء
                </th>
                    
                <th scope="col" class="px-6 py-3">
                    #
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pageNums as $pageNum)
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{-- <a href="{{ route('general', ['accounting_id' => $pageNum->accounting_period_id]) }}" class="text-blue-500 hover:underline">عرض</a> --}}
            </td>
            <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <a href="{{route('all_restrictions_show',$pageNum->accounting_period_id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$pageNum->accounting_period_id}}</a>

                </td>
                <td scope="col" class="px-6 py-3">
                 
                  @if ($pageNum->is_closed )
                  <span class="text-danger">مغلقة</span>
              @else
                  <span class="text-success"> غير مغلقة'</span>
          @endif
                </td>
                <td scope="col" class="px-6 py-3">
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
                
                <td scope="col" class="px-6 py-3">
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
                                    @if ($pageNum->end_date)
                                    <span class="text-danger">  {{$pageNum->end_date.":".$arabicDays[$arabicDay]}}</span>
                                    @else
                                    <span class="text-success">    {{$pageNum->end_date}}</span>

                            @endif
                                
                                </td>
               
                <td class="px-6 py-4 ">
                    <a href="{{route('all_restrictions_show',$pageNum->accounting_period_id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">اظهار</a>
                </td>
            </tr>
            @endforeach


        </tbody>
    </table>
</div>

@endsection