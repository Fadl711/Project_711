<h1 class="font-bold">شجرة الحسابات</h1> 
<br>

<ol class=" text-right space-y-4 text-black list-decimal list-inside dark:text-gray-400">
    <li>
      <lable class="font-bold">الاصول#</lable> 
       <ul class=" ps-5 mt-2 space-y-1 list-disc list-inside">
          <li>الصندوق</li>
          <li>البنك</li>
          <li>المخزون</li>
          <li>العملاء</li>
          <li >
            <lable class="font-bold">الاصول الثابتة#</lable> 
             <ul class=" ps-5 mt-2 space-y-1 list-disc list-inside">
                <li>المباني</li>
                <li>الاراضي</li>
                <li>الاثاث</li>
             </ul>
          </li>
       </ul>
    </li>
    <li>
        <lable class="font-bold">الايرادات#</lable> 
       <ul class="ps-5 mt-2 space-y-1 list-disc list-inside">
          <li>ايرادات المبيعات</li>
          <li>ايرادات الخدمات</li>
          <li>خصم مكتسب</li>
          <li>مردودات المشتريات</li>
       </ul>
    </li>
    <li>
        <lable class="font-bold">حقوق الملكية#</lable> 
       <ul class="ps-5 mt-2 space-y-1 list-disc list-inside">
          <li>راس المال المدفوع</li>
           </ul>
    </li>
    <li>
        <lable class="font-bold">الإلتزامات#</lable> 
       <ul class="ps-5 mt-2 space-y-1 list-disc list-inside">
          <li>الموردين</li>
          <li>القروض</li>

           </ul>
    </li>
    <li>
        <lable class="font-bold">  المصروفات#</lable> 
           <ul class="ps-5 mt-2 space-y-1 list-disc list-inside">
              <li> مسموحات المبيعات</li>
              <li>المشتريات</li>
              <li>ايجار</li>
              <li>الاكهرباء</li>

           </ul>
        </li>
 </ol>
 
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

<h2>Tree View</h2>
<p>A tree view represents a hierarchical view of information, where each item can have a number of subitems.</p>
<p>Click on the arrow(s) to open or close the tree branches.</p>

<ul id="myUL">
 <li><span class="caret">Beverages</span>
   <ul class="nested">
     <li>Water</li>
     <li>Coffee</li>
     <li><span class="caret">Tea</span>
       <ul class="nested">
         <li>Black Tea</li>
         <li>White Tea</li>
         <li><span class="caret">Green Tea</span>
           <ul class="nested">
             <li>Sencha</li>
             <li>Gyokuro</li>
             <li>Matcha</li>
             <li>Pi Lo Chun</li>
           </ul>
         </li>
       </ul>
     </li>  
   </ul>
 </li>
</ul>

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