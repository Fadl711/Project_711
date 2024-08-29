@extends('layout') 
@section('conm')

<h1 class="text-center  font-bold py-2">ترصيد الحسابات</h1>

<div class="-mx-4 sm:-mx-8 px-4 sm:px-8 overflow-x-auto   p-1">
    <h1>  حساب: الصندوق  </h1>
    <div class="inline-block min-w-full shadow rounded-lg  max-h-[500px] ">
        <table id="myTable" class="min-w-full leading-normal ">
            <thead class="tracking-tight ">
                <tr class="bgcolor">
                    <th scope="col" class="leading-2 tagHt"> القم القيد</th>
                    <th scope="col" class="leading-2 tagHt ">اسم الحساب</th>
                    <th scope="col" class="leading-2 tagHt ">رقم الحساب</th>
                    <th scope="col" class="leading-2 tagHt ">  المبالغ المقبوض</th>
                    <th scope="col" class="leading-2 tagHt ">  اسم العميل</th>
                    <th scope="col" class="leading-2 tagHt"> القم القيد</th>
                    <th scope="col" class="leading-2 tagHt ">اسم الحساب</th>
                    <th scope="col" class="leading-2 tagHt ">رقم الحساب</th>
                    <th scope="col" class="leading-2 tagHt ">  المبالغ المدفوع</th>
                    <th scope="col" class="leading-2 tagHt ">  اسم العميل</th>
                    <th scope="col" class="leading-2 tagHt "> الرصيد الحالي </th>
                    <th scope="col" class="leading-2 tagHt "> المزيد التفاصيل</th>
                </tr>
            </thead>
            <tbody class="">
                @foreach($posts as $mnn)
               <tr class="bg-white transition-all duration-500 hover:bg-gray-50"> 
                    <td class="tagTd  border-r border-r-orange-950">{{$mnn['id']}}</td>
                    <td class="tagTd  border-r border-r-orange-950">{{$mnn['sec']}}</td>
                    <td class="tagTd  border-r border-r-orange-950">{{$mnn['idsec']}}</td>
                    <td class="tagTd   items-center font-bold ">{{$mnn['pric']}}</td>
                    <td class="tagTd   items-center font-bold ">{{$mnn['name']}}</td> 
                    <td class="tagTd  border-r border-r-orange-950">1</td>
                    <td  id="financial_accoun" class="tagTd  border-r border-r-orange-950">الصندوق</td>
                    <td class="tagTd   items-center font-bold ">$41225</td>
                    <td class="tagTd   items-center font-bold ">جمال</td>
                    <td class="tagTd ">$1500</td>
                            <td class="tagTd  "><a href="#"
                            class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                            <span aria-hidden
                            class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                            <span class="relative">المزيد</span>
                        </a>
                    </td>
                </tr>
             @endforeach 
            </tbody>
        </table>
    </div>
</div>

{{-- onclick="AccountBalancing()" --}}
{{-- <div id="financial_accoun"></div> --}}
<script type="text/javascript">
// document.getElementById('#Accountbalancing').addEventListener('click',

// $(document).ready(function(){
//   $("#Accountbalancing").click(function(){  
//      $.ajax({
    
//     url:'{{route('accounts.balancing')}}',
//     type:'GET',
//     success:function(data){
//         let html='';
//         data.forEach($item=>{
//             html+='<br>'+'<p>'+$item.name+'</P>';
                
//                    });
        
//         $('#financial_accoun').html(html)


//     },
// });


//   });
// });
 function AccountBalancing(){
//     $.ajax({
    
//     URL:'/accounts.balancing',
//     type:'GET',
//     success:function(data){
//         let html='';
//         (item=>{
//             html+='<p>${item.name}</p>';
//         });
        
//         $('#financial_accoun').html(html)


//     },
// });


//  fetch('/C')
//  .then(response=>response.json())
//  .then(data=>{
//         document.getElementById('#financial_accoun').innerHTML='Name:'+data.sec;
//     })
};
// });

// document.getElementById('Accountbalancing').addEventListener('click',
// function(){

//     fetch('/financial_accounts').then(response=>response.json()).then(data=>{
//         document.getElementById('financial_account').innerHTML='Name:'+data.idsec;
//     })
// });
</script>
<script type="text/javascript">
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>
@endsection