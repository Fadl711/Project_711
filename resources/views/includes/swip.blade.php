
<div class="max-w-40 print:hidden ">
<div class="">
    <div class=" divNav">
        <ul class="list-none ">
            <li class="hover:text-black">
                <a class="NavTagA" href="{{route('home.index')}}">
                    <svg class="w-6 h-6 stroke-current " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="textNav"> الرئسية</span>
                    </a>

                </li>
                @if(auth()->user()->hasPermission('الحسابات'))
                    <li class="hover:text-black">
                    <a class=" NavTagA"  href="{{route('accounts.index')}}">
                        <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                          </svg>

                    <span class="textNav"> الحسابات</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('القيود'))
                <li class="hover:text-black">
                        <a class="NavTagA" href="{{ route('restrictions.index') }}">
                            <svg class="w-6 h-6  dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 20V10m0 10-3-3m3 3 3-3m5-13v10m0-10 3 3m-3-3-3 3"/>
                            </svg>
                            <span class="textNav mr-1"> القيود</span>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasPermission('السندات'))

                <li class="hover:text-black">
                    <a class="NavTagA " href="{{route('bonds.index')}}">
                        <svg class="w-6 h-6  dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="2" d="M16.5 15v1.5m0 0V18m0-1.5H15m1.5 0H18M3 9V6a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v3M3 9v6a1 1 0 0 0 1 1h5M3 9h16m0 0v1M6 12h3m12 4.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/>
                          </svg>
                                              <span class="textNav mr-1"> السندات</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('المبيعات'))

            <li class="hover:text-black">
                <a class="NavTagA" href="{{route('sales.create')}}">
                <svg class="w-6 h-6   dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17.345a4.76 4.76 0 0 0 2.558 1.618c2.274.589 4.512-.446 4.999-2.31.487-1.866-1.273-3.9-3.546-4.49-2.273-.59-4.034-2.623-3.547-4.488.486-1.865 2.724-2.899 4.998-2.31.982.236 1.87.793 2.538 1.592m-3.879 12.171V21m0-18v2.2"/>
                </svg>
                <span class="textNav">المبيعات</span>
                </a>
            </li>
            @endif



            @if(auth()->user()->hasPermission('الفواتير المبيعات'))

                <li class="hover:text-black"><a class="NavTagA" href="{{route('invoice_sales.all_invoices_sale')}}">
                    <svg class="w-6 h-6  dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m8-2h3m-3 3h3m-4 3v6m4-3H8M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM8 12v6h8v-6H8Z"/>
                      </svg>
                    <span class="textNav">فواتير المبيعات</span>
                    </a>
                </li>
                @endif

                                @if(auth()->user()->hasPermission('المشتريات'))
                <li class="hover:text-black">
                    <a class="NavTagA" href="{{route('Purchases.create')}}">
                    <svg class="w-6 h-6   dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7h-1M8 7h-.688M13 5v4m-2-2h4"/>
                        </svg>
                    <span class="textNav">المشتريات</span>
                    </a>
                </li>
                    @endif
                    @if(auth()->user()->hasPermission('الفواتير المشتريات'))

                    <li class="hover:text-black"><a class="NavTagA" href="{{route('invoice_purchase.index')}}">
                        <svg class="w-6 h-6  dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m8-2h3m-3 3h3m-4 3v6m4-3H8M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM8 12v6h8v-6H8Z"/>
                          </svg>
                        <span class="textNav">فواتير المشتريات</span>
                        </a></li>
                        @endif
                        @if(auth()->user()->hasPermission('المنتجات'))
                <li class="hover:text-black"><a class="NavTagA" href="{{route('products.index')}}">
                    <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="textNav">المنتجات</span>
                    </a></li>
                    @endif
                    <li class="hover:text-black"><a class="NavTagA" href="{{route('production_system.dashboard')}}">
                        <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="textNav">نظام الانتاج</span>
                        </a></li>
                    @if(auth()->user()->hasPermission('سجلات الترحيل'))
                    <li class="hover:text-black">
                    <a class="NavTagA" href="{{route('transfer_restrictions.index')}}">
                        <svg class="w-6 h-6  dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 20V10m0 10-3-3m3 3 3-3m5-13v10m0-10 3 3m-3-3-3 3"/>
                          </svg>
                           <span class="textNav mr-1"> سجلات الترحيل</span>
                    </a></li>
                    @endif
                    @if(auth()->user()->hasPermission('قائمة الجرد'))
                    <li class="hover:text-black">
                        {{--  --}}


                        {{-- @if($resultDebit1->Authority_Name=="قائمة التحليلات ") --}}
              {{--           <div class="dropdown inline-block relative z-10 ">
                            <button class=" text-white font-semibold py-2 px-2 rounded inline-flex items-center hover:bg-gray-400 ">
                                <span class="textNav ml text-white">قائمة التحليلات </span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>

                            </button>
                            <ul class=" dropdown-content absolute hidden text-gray-700  bg-gradient-to-r from-indigo-700 to-indigo-500 -right-3  ">
                                <li><a class=" rounded-t  border border-white hover:bg-indigo-400  py-2 px-4 flex whitespace-no-wrap none " href="{{route('chart.index')}}"><svg class="w-6 h-6" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M28 25V10a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v15h-2V6a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v19h-2V15a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v10H4V5H2v21a1 1 0 0 0 1 1h27v-2Zm-4-14h2v14h-2Zm-8-4h2v18h-2Zm-8 9h2v9H8Z" data-name="2" fill="#ffffff" class="fill-000000"></path></svg>
                                    <span class="textNav mr-1 text-white ">تحليل البيانات</span></a></li>
                                <li><a class=" rounded-t  border border-white hover:bg-indigo-400  py-2 px-4 flex whitespace-no-wrap none " href="{{route('inventory.index')}}"><svg class="w-6 h-6 text-white" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">                             <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                </svg>
                                    <span class="textNav mr-1 text-white ">قائمة الجرد </span></a></li>
                                    <li><a class=" rounded-t  border border-white hover:bg-indigo-400  py-2 px-4 flex whitespace-no-wrap none " href="{{route('fixed.index')}}">                            <svg class="w-6 h-6" viewBox="0 0 48 48" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M46 44.438H2a1 1 0 1 1 0-2h44a1 1 0 1 1 0 2zm-30-10a1 1 0 1 1 0 2H8a1 1 0 1 1 0-2h1v-13H8a1 1 0 1 1 0-2h8a1 1 0 1 1 0 2h-1v13h1zm-3-13h-2v13h2v-13zm15 13a1 1 0 1 1 0 2h-8a1 1 0 1 1 0-2h1v-13h-1a1 1 0 1 1 0-2h8a1 1 0 1 1 0 2h-1v13h1zm-3-13h-2v13h2v-13zm19 18a1 1 0 0 1-1 1H5a1 1 0 1 1 0-2h38a1 1 0 0 1 1 1zm-4-5a1 1 0 1 1 0 2h-8a1 1 0 1 1 0-2h1v-13h-1a1 1 0 1 1 0-2h8a1 1 0 1 1 0 2h-1v13h1zm-3-13h-2v13h2v-13zm-34-6L24 4l21 11.438v2H3v-2zm37.541 0L24 6.886 7.396 15.438h33.145z" fill-rule="evenodd" fill="#ffffff" class="fill-000000"></path></svg>
                                        <span class="textNav mr-1 text-white ">الأصول الثابتة  </span></a></li>
                                    </ul>
                                </div> --}}

                                <a class="NavTagA" href="{{route('inventory.index')}}"><svg class="w-6 h-6 " fill="#ffffff" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M3,21V11a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1V21a1,1,0,0,1-1,1H4A1,1,0,0,1,3,21ZM20,2H10A1,1,0,0,0,9,3V4h9.5a.5.5,0,0,1,.5.5V15h1a1,1,0,0,0,1-1V3A1,1,0,0,0,20,2ZM16,19a1,1,0,0,0,1-1V7a1,1,0,0,0-1-1H6A1,1,0,0,0,5,7V8h9.5a.5.5,0,0,1,.5.5V19Z"></path></g></svg>
                                    <span class="textNav mr-1  ">قائمة الجرد </span></a>

                                {{--  --}}
                    </li>
                    @endif
                    {{-- @if($resultDebit1->Authority_Name=="قائمة العملاء") --}}

                    {{-- <li class="  ">



                    <div class="dropdown inline-block relative z-0">
                    <button class=" text-white font-semibold py-2 px-2 rounded inline-flex items-center hover:bg-gray-400">
                    <span class="textNav tracking-wider  text-white">قائمة العملاء</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>

                    </button>
                    <ul class="dropdown-content absolute hidden text-gray-700  bg-gradient-to-r from-indigo-700 to-indigo-500 -right-3">
                    <li><a class=" rounded-t  border border-white hover:bg-indigo-400 py-2 px-4 flex whitespace-no-wrap none " href="{{route('users.index')}}"><svg class="w-6 h-6  text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                    </svg>
                    <span class="textNav mr-1 text-white "> المستخدمين</span></a></li>
                    <li><a class=" rounded-t  border border-white hover:bg-indigo-400 py-2 px-4 flex whitespace-no-wrap none " href="{{route('customers.index')}}">                    <svg class="w-6 h-6  text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                    </svg>
                    <span class="textNav mr-1 text-white ">العملاء</span></a></li>
                    <li><a class=" rounded-t  border border-white hover:bg-indigo-400 py-2 px-4 flex whitespace-no-wrap none " href="{{route('suppliers.index')}}"><svg class="w-6 h-6  text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                    </svg>
                    <span class="textNav mr-1 text-white ">الموردين</span></a></li>
                    </ul>
                    </div>




                </li> --}}
                {{-- @endif --}}
                {{-- @if($resultDebit1->Authority_Name=="الرسائل") --}}

{{--                 <li class="">
                    <a class="NavTagA" href="">
                    <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <span class="textNav">الرسائل</span>
                    <span class="absolute top-0 left-0 w-2 h-2 mt-2 ml-2 bg-indigo-500 rounded-full"></span>
                </a>
                </li> --}}
                {{-- @endif --}}
                {{-- @if($resultDebit1->Authority_Name=="التقارير") --}}

                @if(auth()->user()->hasPermission('التقارير'))
                <li class="hover:text-black">
                    <a class="NavTagA" href="{{route('report.summary')}}">
                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                    <span class="textNav">ادارة مخازن</span>
                    </a>
                </li>
                    @endif
                    @if(auth()->user()->hasPermission('الإعدادات'))
                <li class="hover:text-black">
                    <a class="NavTagA" href="{{route('settings.index')}}">
                    <svg class="w-6 h-6   stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    <span class="textNav">الإعدادات</span>
                    </a></li>
                    @endif
                    @if(auth()->user()->hasPermission('تفعيل'))
                <li class="hover:text-black">
                    <form action="{{route('Saleupdate')}} "  method="POST" >
                    <button type="submit" >

                    <span class="textNav">تفعيل الخصومات</span>
                </button>
            </form>
                    {{-- </a> --}}
                </li>
                    @endif

        </ul>
    </div>
</div>


</div>

