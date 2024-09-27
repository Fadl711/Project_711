@extends('accounts.index')
@section('accounts')
<h1 class="font-bold">شجرة الحسابات</h1> 
<br>


<h1>أنواع الحسابات والحسابات الرئيسية لكل نوع</h1>

@foreach($accountTypes as $accountType)
    <div class="account-type">
      @if($accountsByType[$accountType->value]->isEmpty())
                          
        {{-- <h2>نوع الحساب: {{ $accountType->value }}</h2> --}}
   @endif
    <h2>نوع الحساب: {{ $accountType->value }}</h2>
        @if($accountsByType[$accountType->value]->isEmpty())
            {{-- <p>لا توجد حسابات رئيسية لهذا النوع.</p> --}}
        @else
            <ul>
                @foreach($accountsByType[$accountType->value] as $mainAccount)
                    <li>
                    
                  
                        <h3>الحساب الرئيسي: {{ $mainAccount->account_name }}</h3>
                        {{-- <p>طبيعة الحساب: {{ $mainAccount->Nature_account }}</p> --}}
                     
                        {{-- <h4>الحسابات الفرعية:</h4> --}}
                        <ul>
                            {{--
                             @foreach($mainAccount->subAccounts as $subAccount)
                                <li>
                                    {{ $subAccount->sub_name }} - مدين: {{ $subAccount->debtor }} - دائن: {{ $subAccount->creditor }}
                                </li>
                            @endforeach 
                            --}}
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endforeach
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

<ol id="myUL">
  @foreach ($TypesAccount as $TypesAccounts)
      
       <li>
          <span class="caret">{{$TypesAccounts['TypesAccount']}}</span> 
        <ul class="nested">
            <li>الصندوق</li>
            <li>البنك</li>
            <li>المخزون</li>
            <li>
              <span class="caret">العملاء</span> 
  
              <ul class="nested">
                <li>المبيعات</li>
                <li>المشتريات</li>
              </ul>
            </li>
            </li>
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
      @endforeach 
    
   
      
        </ol>

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