
   @extends('production_system.index')
@section('productionSystem')
   <style>
        body {
      font-family: Arial, sans-serif; /* الخط الافتراضي */
  }
  .english {
      font-family: 'Times New Roman', serif; /* الخط الإنجليزي */
  }
      /* تخصيص للطباعة */
      @media print {
          body {
              width: 100%;
              margin: 0;
              padding: 0;
          }
          .print-container {
              @apply w-full max-w-full mx-auto p-2;
          }

          .no-print {
              display: none;
          }
      }

  table {
      table-layout: ; /* استخدم تخطيط ثابت */
      width: 100%;
  }

  th, td {
      border: 1px solid #000;
      /* padding: 8px; */
  }

 

  /* تحسين مظهر الجدول */
  .header-section, .totals-section {
      margin-top: 10px;
      border: 2px solid #000;
      border-radius: 8px;
  }
      
  </style>

    <div class=" print-container px-1 bg-white ">
        <!-- العنوان -->
        @isset($buss)
        <div class="header bg-[#1749fd15]  rounded-lg">
               @include('includes.header2')

       @endisset
  </div>

<div class="  bg-white rounded-lg shadow-lg">
    <div class=" w-full  ">
        <!-- رأس البطاقة -->
        <div class="bg-gray-50 px-1 py-4 border-b flex justify-between w-full  ">
            <h2 class="text-xl font-semibold text-gray-800">
                أمر الإنتاج #{{ $order->order_number }}
            </h2>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->getStatusClass() }}">
                {{ $order->getStatuses()[$order->status] }}
            </span>
        </div>

        <!-- محتوى البطاقة -->
        <div class="p-1">
            <div class="grid grid-cols-1">
                     <div class=" py-2 mb-4 w-full   grid grid-cols-2 md:grid-cols-2 gap-1 px-1">

                <!-- المعلومات الأساسية -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">المعلومات الأساسية</h3>
                    <div class="">
                        <div>
                            <p class="text-sm text-gray-500">المنتج</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->product->product_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">خط الإنتاج</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->line->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">الأولوية</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->getPriorities()[$order->priority] }}</p>
                        </div>
                    </div>
                </div>
                <!-- الكميات والتكاليف -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">الكميات والتكاليف</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">الكمية المخططة</p>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($order->planned_quantity) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">الكمية المنتجة</p>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($order->produced_quantity) }}</p>
                        </div>
                       <div>
                            <p class="text-sm text-gray-500">الكمية المعتمدة</p>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($order->approved_quantity) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">التكلفة التقديرية</p>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($order->estimated_cost) }}</p>
                        </div>

                    </div>
                </div>
                <!-- التواريخ -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">التواريخ</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">تاريخ البدء المخطط</p>
                            <p class="mt-1 text-sm text-gray-900">{{\Carbon\Carbon::parse($order->start_date)->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">تاريخ الانتهاء المخطط</p>
                            <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->end_date)->format('Y-m-d') }}</p>
                        </div>
                        @if($order->actual_start)
                        <div>
                            <p class="text-sm text-gray-500">تاريخ البدء الفعلي</p>
                            <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->actual_start)->format('Y-m-d H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- معلومات إضافية -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">معلومات إضافية</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">منشئ الأمر</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->creator->name }}</p>
                        </div>
                        @if($order->approved_by)
                        <div>
                            <p class="text-sm text-gray-500">المعتمد</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->approver->name }}</p>
                        </div>
                        @endif
                        @if($order->notes)
                        <div>
                            <p class="text-sm text-gray-500">ملاحظات</p>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                </div>
                <hr>

     <div class=" py-2 mb-4 w-full  ">
        <div class=" w-full">
            <div class="  w-full">
                <div class="card-header d-flex ">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">معلومات حركة المواد الخام</h3>
                   
                </div>
                </div>
                    
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="bg-gray-100 px-1 border text-xs font-bold ">المادة</th>
                        <th class="bg-gray-100 px-1 border text-xs font-bold ">الكمية المخططة</th>
                        <th class="bg-gray-100 px-1 border text-xs font-bold ">الكمية الفعلية</th>
                        <th class="bg-gray-100 px-1 border text-xs font-bold ">الكمية المرتجعة</th>
                        <th class="bg-gray-100 px-1 border text-xs font-bold ">التكلفة</th>
                        <th class="bg-gray-100 px-1 border text-xs font-bold ">التكلفة الإجمالية</th>
                        <th class="bg-gray-100 px-1 border text-xs font-bold ">المخزن</th>
                        <th class="bg-gray-100 px-1 border text-xs font-bold ">مسؤول الصرف</th>
                        <th class="bg-gray-100 px-1 border text-xs font-bold ">تاريخ الصرف</th>
                    </tr>
                </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @php
                                                    $sum_total_cost=0;
                                                @endphp
                    @foreach($rawMaterialTransaction as $transaction)
                    @php
                       $sum_cost =($transaction->actual_quantity)*($transaction->unit_cost);
                       $sum_total_cost+= $sum_cost ;
                    @endphp
                    <tr class=" ">

                        {{-- <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->transaction_id }}</td> --}}
                        <td class="border">{{ $transaction->material->product_name }}</td>
                        <td class="border">{{ number_format($transaction->planned_quantity) }}</td>
                        <td class="border">{{ number_format( $transaction->actual_quantity) }}</td>
                        <td class="border">{{ number_format($transaction->returned_quantity) }}</td>
                        <td class="border">{{ number_format($transaction->unit_cost) }}</td>
                        <td class="border">{{ number_format($transaction->total_cost) }}</td> 
                        <td class="border">{{ $transaction->warehouse->sub_name }}</td>
                        <td class="border">{{ $transaction->issuedByUser->sub_name }}</td>
                        <td class="border">{{ $transaction->issue_date->format('Y-m-d') }}</td>
                      
                          
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="5" class="text-left bg-gray-100 px-1 border text-xs font-bold "> الإجمالي</th>
                        <th colspan="4" class="bg-gray-100 px-1 text-red-700 border text-xs font-bold "> {{number_format($sum_total_cost)}}</th>
                    </tr>

                </tbody>
            </table>
       
    </div>
    </div>
    
    <hr>
    <div class=" mb-4 w-full py-2 ">
        <div class=" w-full">
            <div class="w-full ">
                <div class="card-header d-flex ">
                         <h3 class="text-lg font-medium text-gray-900 mb-4">تفاصيل التكلفة الصناعية # {{ $order->order_number }}</h3>
                </div>
                </div>
                  <table class=" w-full">
                     <thead class="bg-gray-50 ">
                                <tr class="bg-gray-50">
                                    <th class="bg-gray-100 px-1 border text-xs font-bold">نوع التكلفة</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold">المبلغ</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold">التاريخ</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold">مسجل بواسطة</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold" >حساب دفتر الأستاذ</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold"> البيان</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold">تاريخ التسجيل</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold">آخر تحديث</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold"></th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold"></th>
                                </tr>
                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @php
                                                    $sum_total_amount=0;
                                                @endphp
                                                                    @foreach($order->manufacturingCosts as $manufacturingCost)
                                                                     <tr>
                                                                         @php
                       $sum_total_amount+= $manufacturingCost->amount ;
                    @endphp
                                    <td class="border">{{ $manufacturingCost->getCostTypes()[$manufacturingCost->cost_type] }}</td>
                                    <td class="border">{{ number_format($manufacturingCost->amount) }}</td>
                                    <td class="border">{{ $manufacturingCost->cost_date->format('Y-m-d') }}</td>
                                    <td class="border">{{ $manufacturingCost->creator->name }}</td>
                                    <td class="border">{{ $manufacturingCost->glAccount->sub_name }}</td>
                                    <td class="border">
                                        {{ $manufacturingCost->description }}
                                      
                                        
                                    </td>
                                    <td class="border">{{ $manufacturingCost->created_at->format('Y-m-d H:i') }}</td>
                                   <td class="border">{{ $manufacturingCost->updated_at->format('Y-m-d H:i') }}</td>

                                    <td class="border"><div class="card-body"><pre>{{ json_encode($manufacturingCost->details, JSON_PRETTY_PRINT) }}</pre></div></td>
                               <td class="border"><pre>{{ json_encode($manufacturingCost->details, JSON_PRETTY_PRINT) }}</pre></td>
                                     </tr>
                             @endforeach
                     <tr>
                        <th colspan="5" class="text-left bg-gray-100 px-1 border text-xs font-bold "> الإجمالي</th>
                        <th colspan="5" class="bg-gray-100 px-1 text-red-700 border text-xs font-bold "> {{number_format($sum_total_amount)}}</th>
                      </tr>
                    </tbody>  
                 </table>
                                              
    </div>
    </div>

           <br> 
           <br> 
    <div class=" mb-4 w-full py-2 ">
        <div class=" w-full">
            <div class="w-full ">
                <div class="card-header d-flex ">
         <h3 class="text-lg font-medium text-gray-900 mb-4"> التكلفة المنتج الذي تم تصنيعه</h3>
                </div>
                </div>
            <table class="w-full">
                     <thead class="bg-gray-50 ">
                                <tr class="bg-gray-50">
                                    <th class="bg-gray-100 px-1 border text-xs font-bold"> المنتج النهائي</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold">الكمية المنتجة</th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold"> التكاليف المباشرة </th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold"> التكاليف  الغير مباشرة </th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold">الاجمالي </th>
                                    <th class="bg-gray-100 px-1 border text-xs font-bold">تكلفة المنتج </th>
                                </tr>
                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                               
                                                                     <tr>
                                                                        @php
                                                                         $sum_total= ($sum_total_amount+$sum_total_cost) ??0 ;
                                                                         $approved_quantity= $order->approved_quantity??0 ;
                                                                        @endphp
                                    <td class="border">{{$order->product->product_name }}</td>
                                    <td class="border  font-bold">{{ number_format($approved_quantity) }}</td>
                                    <td class="border  font-bold">{{ number_format($sum_total_cost) }}</td>
                                    <td class="border  font-bold">{{ number_format($sum_total_amount) }}</td>
                                    <td class="border  font-bold text-red-600 ">{{ number_format($sum_total) }}</td>
                                    <td class="border  font-bold">{{ number_format($sum_total/$approved_quantity) }}</td>

                                     </tr>
                           
                     
                    </tbody>  
                 </table>
                                              
    </div>
    </div>


            <!-- أزرار التحكم -->
            <div class="mt-8 print:hidden flex justify-end space-x-3">
                <a href="{{ route('production_orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    رجوع
                </a>
                <a href="{{ route('production-orders.edit', $order->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    تعديل
                </a>
            </div>
             </div>
        </div>
             </div>
</div>

    


</div>
<div class="mt-4 no-print">
    <button onclick="printAndClose()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">طباعة</button>

    <script>
        function printAndClose() {
            window.print(); // أمر الطباعة
            setTimeout(() => {
                window.close(); // الإغلاق بعد بدء الطباعة
            }, 500); // فترة الانتظار نصف ثانية فقط
        }
    </script>

    <button onclick="closeWindow()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">إلغاء الطباعة</button>

    <script>
        function closeWindow() {
            if (window.history.length > 1) {
                window.history.back(); // العودة للصفحة السابقة
            } else {
                window.close(); // الإغلاق إذا كانت الصفحة مفتوحة في نافذة جديدة
            }
        }
    </script>
</div>
@endsection

