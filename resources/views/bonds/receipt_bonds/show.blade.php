@extends('bonds.index')
@section('bonds')

<div class="container   " id="js-print-template" x-ref="printTemplate">
    {{-- <button onclick="window.history.back()">رجوع</button> --}}

    @include('includes.header')

    <div class="container p-3 flex justify-between bg-gray-100 border-black border-2 rounded-lg h-40 my-2 text-center font-bold ">
        <div class="p-3  bg-gray-100 border-black  h-16 my-1 text-right font-bold space-y-2 ">

            <p >رقم السند : {{$PaymentBond->payment_bond_id}}</p>
            <p>تاريخ السند : {{ \Carbon\Carbon::parse($PaymentBond->created_at)->format('Y/m/d') }}</p>
            <p >رقم المرجع : 21</p>
        </div>
        <div class="p-3 w-52 bg-gray-100 border-black   h-16 my-1 text-center text-2xl font-bold underline underline-offset-8">

            <p > سند قبض /نقد</p>
        </div>
        <div class=" w-52 bg-gray-100 h-16 my-1 text-center font-bold space-y-2">
            <div>

                <p class="border-b-2 border-black font-bold text-base">المبلغ </p>
                <p id="maont2" class="bg-white h-10 font-bold text-lg pt-2">{{number_format($PaymentBond->Amount_debit)}} <span class="pb-1  font-normal">{{ $Currencies->where('currency_id', $PaymentBond->Currency_id)->first()->currency_name }} </span></p>
            </div>
            <p class="text-right text-base">إيداع في حساب :{{ $MainAccounts->where('main_account_id', $PaymentBond->Main_debit_account_id)->first()->account_name }} </p>
        </div>
    </div>
    {{-- end header --}}
{{--
@php
    function convertNumberToWords($number)
{
    $formatter = new \NumberFormatter('ar', \NumberFormatter::SPELLOUT);
    return $formatter->format($number);
}

$number = 505550 ;
$oo = convertNumberToWords($number) . ' ريال  يمني';
@endphp --}}


    {{-- body srart --}}
    <div class="container p-3 relative space-y-2  text-base bg-gray-200 border-black  rounded-lg  my-2 text-right font-bold">
        <div class="flex justify-between">
            {{-- {{"(".$oo.")"}}  --}}
            <p class="">أستلمنا من الأخ / {{ $SubAccounts->where('sub_account_id', $PaymentBond->Credit_sub_account_id)->first()->sub_name }}</p>
            <p class="">مبلغ وقدره <span class="text-lg">:</span> <span class="font-bold text-base">{{number_format($PaymentBond->Amount_debit)}}</span> ر.ي  </p>
        </div>
        <br>
        <p>تقيد المبلغ لحساب /الدائن</p>
        <div class="container">
            <table class=" bg-gray-100 container text-center  border-black border-2 ">
                <thead>
                    <tr class="bg-gray-300 ">
                        <th class="px-2 py-2  border-black border-2 " >م</th>
                        <th class="  max-w-72 border-black border-2">اسم الحساب </th>
                        <th class="px-2 py-2 min-w-40 max-w-40  border-black border-2">البيان</th>
                        <th class="px-2 py-2 min-w-16 max-w-24  border-black border-2">المبلغ </th>
                        <th class="px-2 py-2 min-w-10 max-w-14  border-black border-2">العملة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-right">
                        <td class="py-2 px-2 border-black border-2">1</td>
                        <td class="py-2 px-2 border-black border-2">{{ $SubAccounts->where('sub_account_id', $PaymentBond->Credit_sub_account_id)->first()->sub_name }}</td>
                        <td class="py-2 px-2 border-black border-2">{{$PaymentBond->Statement}}</td>
                        <td class="py-2 px-2 border-black border-2">{{number_format($PaymentBond->Amount_debit)}}</td>
                        <td class="py-2 px-2 border-black border-2">{{ $Currencies->where('currency_id', $PaymentBond->Currency_id)->first()->currency_name }}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <hr class="border-t-2 bg-black">
            <br>
            <br>
            <div class="flex justify-between mx-10">


                <p >المحاسب............................ </p>
            </div>
        </div>
    </div>
            {{-- body --}}

</div>

    <!-- Modal body -->


    <div class="inline-flex rounded-md shadow-sm px-2 py-2" role="group">
        <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" d="M7.556 8.5h8m-8 3.5H12m7.111-7H4.89a.896.896 0 0 0-.629.256.868.868 0 0 0-.26.619v9.25c0 .232.094.455.26.619A.896.896 0 0 0 4.89 16H9l3 4 3-4h4.111a.896.896 0 0 0 .629-.256.868.868 0 0 0 .26-.619v-9.25a.868.868 0 0 0-.26-.619.896.896 0 0 0-.63-.256Z"/>
              </svg>

          رسالة نصية
        </button>
        <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z" clip-rule="evenodd"/>
                <path fill="currentColor" d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z"/>
              </svg>


          الرسال
          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" d="M5 17v-5h1.5a1.5 1.5 0 1 1 0 3H5m12 2v-5h2m-2 3h2M5 10V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1v6M5 19v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1M10 3v4a1 1 0 0 1-1 1H5m6 4v5h1.375A1.627 1.627 0 0 0 14 15.375v-1.75A1.627 1.627 0 0 0 12.375 12H11Z"/>
          </svg>

        </button>
        <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="1.4" d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
              </svg>

              الرسال
        </button>
        <a href="{{route('receip.print',$PaymentBond->payment_bond_id)}}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="1.4" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z"/>
              </svg>


              طباعة
            </a>
      </div>


<script>
function datainvoices(){
var maont = document.getElementById('crud-modal').value;
}

function invoices() {




 return {
     printInvoice() {
        // var modal = document.getElementById('crud-modal');
        //  modal.classList.remove('hidden');
         var printContents = document.getElementById('js-print-template').innerHTML;
         var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
         window.onafterprint = function() {
             document.body.innerHTML = originalContents;
             window.focus();
             window.location.reload(); // reload the page after printing

             // Add this line to close the modal window
             document.getElementById('crud-modal').classList.add('hidden');
         };
         window.print();
     }
 }
}
</script>
@endsection