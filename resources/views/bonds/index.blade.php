@extends('layout')
@section('conm')


        <ul class="grid grid-cols-4 text-right container relative shadow-md px-2 py-2  bg-white">
            <li class="  rounded ">
                <button onclick="NewReceiptBonds()" type="button" class="text-sm  py-2     leading-none rounded-md hover:bg-gray-100" > سند قبض جديد</button>
            </li>
            <li class="   rounded ">
                <button onclick="AllReceiptBonds()" type="button" class="text-sm py-2  leading-none rounded-md hover:bg-gray-100" > المقبوضات</button>

            </li>
            <li class="  rounded "> 
                <button onclick="NewExchangeBonds()" type="button" class="text-sm py-2  leading-none rounded-md hover:bg-gray-100" > سند صرف جديد </button>

            </li>
            <li class="    rounded ">  
                <button onclick="AccountBalancing()" id="Accountbalancing" type="submit"  class="text-sm py-2  rounded-md hover:bg-gray-100" >  المدفوعات</button>
            </li>

        </ul>
       

        <div class=" bg-slate-50  ">


    <div class="  p-2 mt-2 " id="newExchangeBonds " style="display:">
        @include('bonds.exchange_bonds.index')
    </div>
    <div class="   p-2 mt-2" id="newReceiptBonds " style="display:none">
        @include('bonds.receipt_bonds.index')
    </div>

    <div class="   p-2 " id="AllReceipt_bonds" style="display:none">

       @include('bonds.receipt_bonds.all_receipt_bonds')
    </div>
</div>

<script>
    
// function NewExchangeBonds(){   
 
//     var allreceiptbonds= document.getElementById('AllReceipt_bonds');
//         var ExchangeBonds= document.getElementById('newExchangeBonds');
//         var newreceipt_bonds= document.getElementById('newReceiptBonds'); 

//     if(ExchangeBonds.style.display=="none"){
//         ExchangeBonds.style.display="block";
//         allreceipt_bonds.style.display="none";
//         exchange_bonds.style.display="none";

//     }else  if(ExchangeBonds.style.display=="none")
//     {
//         ExchangeBonds.style.display="block";
//         newreceipt_bonds.style.display="none";
//         allreceipt_bonds.style.display="none";

//     }

//     }
    
// function NewReceiptBonds(){   
   
//     var allreceiptbonds= document.getElementById('AllReceipt_bonds');
//         var exchange_bonds= document.getElementById('exchange_bonds');
//         var newreceipt_bonds= document.getElementById('newreceipt_bonds'); 

//     if(allreceiptbonds.style.display=="none"){
//         allreceiptbonds.style.display="block";
//         newreceipt_bonds.style.display="none";
//         exchange_bonds.style.display="none";

//     }

//     }

//     function AllReceiptBonds(){
    
//         var allreceiptbonds= document.getElementById('AllReceipt_bonds');
//         var exchange_bonds= document.getElementById('exchange_bonds');
//         var newreceipt_bonds= document.getElementById('newreceipt_bonds'); 

//     if(allreceiptbonds.style.display=="none"){
//         allreceiptbonds.style.display="block";
//         newreceipt_bonds.style.display="none";
//         exchange_bonds.style.display="none";

//     }
    
//     }
</script>
@endsection
