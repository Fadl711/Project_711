<!DOCTYPE html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head >
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://www.w3schools.com/js/myScript.js"></script>
        <script src="jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script src="jquery-3.7.1.min.js"></script>
{{-- fiex_assets --}}
        <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
        <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
{{-- fiex_assets --}}
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased a">
        <div class="flex max-h-screen    ">


            {{-- @include('includes.swip') --}}
            
<div class="">
    <div class="">
        <div class=" divNav">
            <ul class="list-none ">
                <li class="">
                    <a class="NavTagA" href="{{route('home.index')}}">
                        <svg class="w-6 h-6 stroke-current " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="textNav"> الرئسية</span>
                        </a>
                    </li>
                        <li class="">
                        <a class=" NavTagA"  href="{{route('accounts.index')}}">
                        <svg class="w-6 h-6 stroke-current " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="textNav"> الحسابات</span>
                        </a>
                    </li>
                    <li class="">
                        <a class="NavTagA" href="{{route('restrictions.index')}}">
                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 20V10m0 10-3-3m3 3 3-3m5-13v10m0-10 3 3m-3-3-3 3"/>
                              </svg>
                               <span class="textNav mr-1"> القيود</span>
                        </a></li>
                    <li class="mb-2">
                        <a class="NavTagA" href="{{route('bonds.index')}}">
                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="2" d="M16.5 15v1.5m0 0V18m0-1.5H15m1.5 0H18M3 9V6a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v3M3 9v6a1 1 0 0 0 1 1h5M3 9h16m0 0v1M6 12h3m12 4.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/>
                              </svg>
                                                  <span class="textNav mr-1"> السندات</span>
                        </a>
                    </li>
                <li class="mb-2">
    
                    <a class="NavTagA" href="{{route('sales.index')}}">
                    <svg class="w-6 h-6  text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17.345a4.76 4.76 0 0 0 2.558 1.618c2.274.589 4.512-.446 4.999-2.31.487-1.866-1.273-3.9-3.546-4.49-2.273-.59-4.034-2.623-3.547-4.488.486-1.865 2.724-2.899 4.998-2.31.982.236 1.87.793 2.538 1.592m-3.879 12.171V21m0-18v2.2"/>
                    </svg>
                    <span class="textNav">المبيعات</span>
                    </a></li>
                    <li class="mb-2"><a class="NavTagA" href="{{route('invoice_sales.index')}}">
                        <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m8-2h3m-3 3h3m-4 3v6m4-3H8M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM8 12v6h8v-6H8Z"/>
                          </svg>
                        <span class="textNav">الفواتير المبيعات</span>
                        </a></li>
                    <li class="mb-2"><a class="NavTagA" href="{{route('Purchases.index')}}">
                        <svg class="w-6 h-6  text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7h-1M8 7h-.688M13 5v4m-2-2h4"/>
                            </svg>
                        <span class="textNav">المشتريات</span>
                        </a></li>
                        <li class="mb-2"><a class="NavTagA" href="{{route('invoice_purchase.index')}}">
                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m8-2h3m-3 3h3m-4 3v6m4-3H8M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM8 12v6h8v-6H8Z"/>
                              </svg>
                            <span class="textNav">الفواتير المشتريات</span>
                            </a></li>
    
                    <li class="mb-2"><a class="NavTagA" href="{{route('products.index')}}">
                        <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="textNav">المنتجات</span>
                        </a></li>
                        <li class="mb-2">
                        <a class="NavTagA" href="{{route('payments.index')}}">
                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 20V10m0 10-3-3m3 3 3-3m5-13v10m0-10 3 3m-3-3-3 3"/>
                              </svg>
                               <span class="textNav mr-1"> المدفوعات</span>
                        </a></li>
    
                        <li class="mb-2">
                        <a class="NavTagA" href="{{route('refunds.index')}}">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"></path><path d="M5.671 4.257c3.928-3.219 9.733-2.995 13.4.672 3.905 3.905 3.905 10.237 0 14.142-3.905 3.905-10.237 3.905-14.142 0A9.993 9.993 0 0 1 2.25 9.767l.077-.313 1.934.51a8 8 0 1 0 3.053-4.45l-.221.166L8.11 6.697l-4.596 1.06 1.06-4.596L5.67 4.257zM13 6v2h2.5v2H10a.5.5 0 0 0-.09.992L10 11h4a2.5 2.5 0 1 1 0 5h-1v2h-2v-2H8.5v-2H14a.5.5 0 0 0 .09-.992L14 13h-4a2.5 2.5 0 1 1 0-5h1V6h2z" fill="#ffffff" class="fill-000000"></path></svg>
                                <span class="textNav"> المردودات</span>
                        </a></li>
                        <li class="">
                            {{--  --}}
    
    
                            <div class="dropdown inline-block relative z-10 ">
                                <button class=" buttNav ">
                                    <span class="textNav ml text-white">قائمة التحليلات </span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    
                                </button>
                                <ul class="ulNav">
                                    <li><a class="aNav " href="{{route('chart.index')}}"><svg class="w-6 h-6" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M28 25V10a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v15h-2V6a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v19h-2V15a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v10H4V5H2v21a1 1 0 0 0 1 1h27v-2Zm-4-14h2v14h-2Zm-8-4h2v18h-2Zm-8 9h2v9H8Z" data-name="2" fill="#ffffff" class="fill-000000"></path></svg>
                                        <span class="textNav  text-white ">تحليل البيانات</span></a></li>
                                    <li><a class=" aNav " href="{{route('inventory.index')}}">
                                                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                    </svg>
                                        <span class="textNav mr-1 text-white ">قائمة الجرد </span>
                                    </a></li>
                                        <li>
                                            <a class="aNav" href="{{route('fixed.index')}}">                            <svg class="w-6 h-6" viewBox="0 0 48 48" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M46 44.438H2a1 1 0 1 1 0-2h44a1 1 0 1 1 0 2zm-30-10a1 1 0 1 1 0 2H8a1 1 0 1 1 0-2h1v-13H8a1 1 0 1 1 0-2h8a1 1 0 1 1 0 2h-1v13h1zm-3-13h-2v13h2v-13zm15 13a1 1 0 1 1 0 2h-8a1 1 0 1 1 0-2h1v-13h-1a1 1 0 1 1 0-2h8a1 1 0 1 1 0 2h-1v13h1zm-3-13h-2v13h2v-13zm19 18a1 1 0 0 1-1 1H5a1 1 0 1 1 0-2h38a1 1 0 0 1 1 1zm-4-5a1 1 0 1 1 0 2h-8a1 1 0 1 1 0-2h1v-13h-1a1 1 0 1 1 0-2h8a1 1 0 1 1 0 2h-1v13h1zm-3-13h-2v13h2v-13zm-34-6L24 4l21 11.438v2H3v-2zm37.541 0L24 6.886 7.396 15.438h33.145z" fill-rule="evenodd" fill="#ffffff" class="fill-000000"></path></svg>
                                            <span class="textNav mr-1 text-white ">الأصول الثابتة  </span>
                                        </a>
                                    </li>
                                        </ul>
                                    </div>
    
    
    
                                    {{--  --}}
                        </li>
                        <li class="  ">
                            {{--  --}}
    
    
                        <div class="dropdown inline-block relative z-0">
                        <button class=" text-white font-semibold py-2 px-2 rounded inline-flex items-center hover:bg-gray-400">
                        <span class="textNav tracking-wider  text-white">قائمة العملاء</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    
                        </button>
                        <ul class="dropdown-content absolute hidden text-gray-700  bg-gradient-to-r from-indigo-700 to-indigo-500 -right-3">
                        <li>
                            <a class=" rounded-t  border border-white hover:bg-indigo-400 py-2 px-4 flex whitespace-no-wrap none " href="{{route('users.index')}}">
                            <svg class="w-6 h-6  text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                        </svg>
                        <span class="textNav mr-1 text-white "> المستخدمين</span></a></li>
                        <li><a class="aNav " href="{{route('customers.index')}}">
                            <svg class="w-6 h-6  text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                        </svg>
                        <span class="textNav mr-1 text-white ">العملاء</span></a></li>
                        <li><a class=" aNav" href="{{route('suppliers.index')}}">
                            <svg class="w-6 h-6  text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                        </svg>
                        <span class="textNav mr-1 text-white ">الموردين</span></a></li>
                        </ul>
                        </div>
    
    
    
                        {{--  --}}
                    </li>
                    <li class="mb-2">
                        <a class="NavTagA" href="">
                        <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        <span class="textNav">الرسائل</span>
                        <span class="absolute top-0 left-0 w-2 h-2 mt-2 ml-2 bg-indigo-500 rounded-full"></span>
                    </a>
                    </li>
                    <li class="mb-2">
                        <a class="NavTagA" href="{{route('report.index')}}">
                                <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                            </svg>
                        <span class="textNav">التقارير</span>
                        </a></li>
                    <li class="mb-2">
                        <a class="NavTagA" href="{{route('settings.index')}}">
                        <svg class="w-6 h-6  text-white stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        <span class="textNav">الإعدادات</span>
                        </a></li>
            </ul>
        </div>
    </div>
    </div>
    
    


            <div class="container relative ">

                @yield('conm')
            </div>
        </div>




    
    </body>
</html>
