@extends('accounts.index')
@section('accounts')
<h1 class="font-bold">شجرة الحسابات</h1> 
<br>
 <ul id="myUL">
      <li>
        <span class="caret">الاصول#</span> 
      <ul class="nested">
          <li>الصندوق</li>
          <li>البنك</li>
          <li>المخزون</li>
          <li>العملاء</li>
          <li >
            <span class="caret">الاصول الثابتة#</span> 
            <ul class="nested">
              <li>المباني</li>
                <li>الاراضي</li>
                <li>الاثاث</li>
             </ul>
          </li>
       </ul>
    </li>
    <li>
      <span class="caret">الايرادات#</span> 
      <ul class="nested">
        <li>ايرادات المبيعات</li>
          <li>ايرادات الخدمات</li>
          <li>خصم مكتسب</li>
          <li>مردودات المشتريات</li>
       </ul>
    </li>
    <li>
      <span class="caret">حقوق الملكية#</span> 
      <ul class="nested">
        <li>راس المال المدفوع</li>
           </ul>
    </li>
    <li>
      <span class="caret">الإلتزامات#</span> 
      <ul class="nested">
        <li>الموردين</li>
          <li>القروض</li>

           </ul>
    </li>
    <li>
      <span class="caret">  المصروفات#</span> 
      <ul class="nested">
        <li> مسموحات المبيعات</li>
              <li>المشتريات</li>
              <li>ايجار</li>
              <li>الاكهرباء</li>

           </ul>
        </li>
      </ul>
 
<style>
   ul, #myUL {
     list-style-type: none;
   }
   
   #myUL {
     margin: 0;
     padding: 0;
   }
   
   .caret {
     cursor: pointer;
     -webkit-user-select: none; /* Safari 3.1+ */
     -moz-user-select: none; /* Firefox 2+ */
     -ms-user-select: none; /* IE 10+ */
     user-select: none;
   }
   
   .caret::before {
     content: "\25B6";
     color: black;
     display: inline-block;
     margin-right: 6px;
   }
   
   .caret-down::before {
     -ms-transform: rotate(90deg); /* IE 9 */
     -webkit-transform: rotate(90deg); /* Safari */'
     transform: rotate(90deg);  
   }
   
   .nested {
     display: none;
   }
   
   .active {
     display: block;
   }
   </style>

<script>
var toggler = document.getElementsByClassName("caret");
var i;

for (i = 0; i < toggler.length; i++) {
 toggler[i].addEventListener("click", function() {
   this.parentElement.querySelector(".nested").classList.toggle("active");
   this.classList.toggle("caret-down");
 });
}
</script>
@endsection