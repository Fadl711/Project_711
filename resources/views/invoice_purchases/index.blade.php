@extends('layout')
@section('conm')
<div class="bg-white rounded-lg shadow-lg " id="bills_purchase" style="display: ">
  
<div class="bg-white w-full  " id="bills_purchase" style="display: ">

  @include('invoice_purchases.all_bills_purchase')
</div>

  <script>

//     function all_bills_sale(){
//     var allbills_sale= document.getElementById('all_bills_sale');
//     var All_bills_purchase= document.getElementById('bills_purchase');

//     if(allbills_sale.style.display=="none"){
//       allbills_sale.style.display="block";
//         All_bills_purchase.style.display="none";

//     }
//     else if(allbills_sale.style.display=="block")
//     {
//       allbills_sale.style.display="block";

//       All_bills_purchase.style.display="none";

//     }

// }

// function all_bills_sale2(){
//   var allbills_sale= document.getElementById('all_bills_sale');
//     var billspurchase= document.getElementById('bills_purchase');
//     if(billspurchase.style.display=="none")
//     {
//       billspurchase.style.display="block";
//       allbills_sale.style.display="none";


//     }
//     else if(billspurchase.style.display=="block")
//     {
//       billspurchase.style.display="block";

//       allbills_sale.style.display="none";

//     }

// }

  </script>
@endsection
