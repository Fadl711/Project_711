@extends('bonds.index')
@section('tital'){{'gamal'}} @endsection
@section('bonds')
{{-- <button onclick="window.history.back()">رجوع</button> --}}

<div class=" text-sm">

    <div class=" min-w-full shadow rounded-lg   max-h-screen overflow-x-auto  ">
    <table class="min-w-full bg-white  text-sm " >
        <thead class="bg-[#f33535b7] text-white  ">
           <tr>

            <th class="bg-white ">100,500</th>
            <th colspan="2" class="py-1  border text-center">حساب الدفع</th>
            <th colspan="2" class="py-1  border text-center">حساب القبض </th>
            <th  colspan="5" class="bg-white " >
                <div colspan="4"  class="flex flex-col  justify-center items-center  w-full">

                <div class="relative  border text-black border-gray-200 rounded-lg w-[100%] ">
                    <input type="text" class="rounded-md w-full text-left" placeholder="Search ">

                    <button type="submit" class="absolute right-6 top-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                    </button>

                </div>
            </div></th>
           </tr>
            <tr>

                <th class=" border text-right "> التاريخ</th>
                <th class="py-1  border text-right">   الرئيسي</th>
                <th class="py-1  border text-right">   الفرعي </th>
                <th class="py-1  border text-right"> الرئيسي</th>
                <th class="py-1  border text-right"> الفرعي</th>
                <th class="py-1  border text-right">المبلغ</th>
                <th class="py-1  border text-right">البيان	</th>

                <th class="py-1  border text-right">العملة</th>
                <th class="py-1  border text-right "> المستخدم</th>
                <th class="py-1  border text-right"> عرض - تحرير</th>


            </tr>
        </thead>
        <tbody >
          @foreach ($ExchangeBond as $PaymentBond)
          <tr class="transition-all duration-500">

              <td class=" border text-right">{{$PaymentBond->payment_bond_id}}</td>
              <td class=" border text-right">{{$PaymentBond->created_at}}</td>
              <td class=" border text-right">{{ $MainAccounts->where('main_account_id', $PaymentBond->Main_debit_account_id)->first()->account_name }}</td>
              <td class=" border text-right">{{ $SubAccounts->where('sub_account_id', $PaymentBond->Debit_sub_account_id)->first()->sub_name }}</td>
              <td class=" border text-right">{{ $MainAccounts->where('main_account_id', $PaymentBond->Main_Credit_account_id)->first()->account_name }}</td>
              <td class=" border text-right">{{ $SubAccounts->where('sub_account_id', $PaymentBond->Credit_sub_account_id)->first()->sub_name }}</td>
              <td class=" border text-right">{{$PaymentBond->Amount_debit}}</td>
              <td class=" border text-right">{{$PaymentBond->Statement}}</td>
              <td class=" border text-right">{{ $Currencies->where('currency_id', $PaymentBond->Currency_id)->first()->currency_name }}</td>
              <th class=" border text-right ">{{ $users->where('id', $PaymentBond->User_id)->first()->name }}</th>
              <td class=" border text-right flex">
                  <a href="{{route('receip.show',$PaymentBond->payment_bond_id)}}"  class="text-sm py-2  leading-none rounded-md hover:bg-gray-100" >

                  <svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                      <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                  </a>

                    <a href="{{route('receip.edit',$PaymentBond->payment_bond_id)}}"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" >                  
                            <svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">

                      <path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>
                      <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>
                    </svg>
                  </a>

              </a>
              <button class="mt-[10px] focus:outline-none  rounded-full  group transition-all duration-500  flex item-center" data-toggle="modal" data-target="#delete-modal-{{$PaymentBond->payment_bond_id}}">
                  <svg class="" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path class="fill-red-600" d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z" fill="#F87171"></path>
                  </svg>
              </button>
          </td>
        <!-- Modal -->
        <div class="modal" tabindex="-1" role="dialog" id="delete-modal-{{$PaymentBond->payment_bond_id}}">
          <div class="modal-dialog" role="document">
            <div class="modal-content bg-white rounded shadow-md">
              <div class="modal-header">
                <h5 class="modal-title text-lg font-bold"> حذف سند فبض</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p class="text-gray-600 text-center font-bold">هل انت متاكد من حذف هذا القبض?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                <form action="{{route('receip.destroy',$PaymentBond->payment_bond_id)}}" method="POST"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" >
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">حذف</button>
                </form>
              </div>
            </div>
          </div>
        </div>
          </tr>
        @endforeach


            <!-- يمكنك إضافة المزيد من الصفوف هنا -->
        </tbody>
    </table>
</div>

@endsection