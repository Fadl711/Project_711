@extends('layout')
@section('conm')
    <!-- FOR DEMO PURPOSE -->
    <!-- component -->
    <aside class=" bg-white h-screen shadow-md flex flex-col justify-between">
        <div class="p-6">

            <nav class="space-y-2">
                <a href="{{ route('company_data.settings.create') }}"
                    class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                    <span class="mr-3">
                        <!-- Home Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9.5L12 3l9 6.5v9.5a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H3a2 2 0 01-2-2V9.5z" />
                        </svg>
                    </span>
                    <span> بيانات العمل التجاري </span>
                </a>
                <a href="{{ route('settings.currencies.index') }}"
                    class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                    <span class="mr-3">
                        <!-- View Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12l-3-3m0 0l-3 3m3-3v12M5 12a7 7 0 0114 0v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4z" />
                        </svg>
                    </span>
                    <span>العملات</span>
                </a>


                {{-- <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                <span class="mr-3">
                    <!-- Analytics Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3a8 8 0 0116 0v7a8 8 0 01-16 0V3zm5 15h-4v7h4v-7zm-9 3h4v-3H7v3zm-4 3h4v-5H3v5z" />
                    </svg>
                </span>
                <span>اعدادات الجرد</span>
            </a> --}}
                <a href="{{ route('Locks_financial_period.index') }}"
                    class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                    <span class="mr-3">
                        <!-- Messages Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a3 3 0 013-3h12a3 3 0 013 3v10a3 3 0 01-3 3H9l-6 3V5z" />
                        </svg>
                    </span>
                    <span>الإقفال السنوي</span>
                </a>

                <hr class="my-2 border-gray-300">
                <a href="{{ route('default_customers.index') }}"
                    class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                    <span class="mr-3">
                        <!-- Training Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 20h10M12 4v16m0 0H8m4 0h4" />
                        </svg>
                    </span>
                    <span>الإعدادات الإفتراضية</span>
                </a>
                {{--             <a href="{{route('warehouse.index')}}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                <span class="mr-3">
                    <!-- Visual Look Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12h4m-4-2h6m-6 4h6m-6 4h4M6 12H2m0-2h6m-6 4h6M8 6h8M8 18h8m-5 0v4m-2-4v4M8 3v4m0 12v4" />
                    </svg>
                </span>
                <span>المخازن</span>
            </a> --}}
                <a href="{{ route('transfer_restrictions.create') }}"
                    class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                    <span class="mr-3">
                        <!-- Embed Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M4 7h.01M12 3v4M3 12h18M16 7h.01M12 20v-4m-8 5h16" />
                        </svg>
                    </span>
                    <span>الترحيل القيود </span>
                </a>
                <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                    <span class="mr-3">
                        <!-- Embed Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9.5L12 3l9 6.5v9.5a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H3a2 2 0 01-2-2V9.5z" />
                        </svg>
                    </span>
                    <span> لوحة التحكم </span>
                </a>
                @if (auth()->user()->hasPermission('الاشعارات'))
                    <a href="{{ route('operations.index') }}"
                        class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                        <span class="mr-3">
                            <!-- Embed Icon -->
                            <svg fill="#000000" class="h-5 w-5 opacity-75" viewBox="-3.2 -3.2 38.40 38.40" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00032"
                                transform="matrix(1, 0, 0, 1, 0, 0)rotate(0)">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"
                                    stroke="#CCCCCC" stroke-width="0.576"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <title>logs</title>
                                    <path
                                        d="M0 24q0 0.832 0.576 1.44t1.44 0.576h1.984q0 2.496 1.76 4.224t4.256 1.76h6.688q-2.144-1.504-3.456-4h-3.232q-0.832 0-1.44-0.576t-0.576-1.408v-20q0-0.832 0.576-1.408t1.44-0.608h16q0.8 0 1.408 0.608t0.576 1.408v7.232q2.496 1.312 4 3.456v-10.688q0-2.496-1.76-4.256t-4.224-1.76h-16q-2.496 0-4.256 1.76t-1.76 4.256h-1.984q-0.832 0-1.44 0.576t-0.576 1.408 0.576 1.44 1.44 0.576h1.984v4h-1.984q-0.832 0-1.44 0.576t-0.576 1.408 0.576 1.44 1.44 0.576h1.984v4h-1.984q-0.832 0-1.44 0.576t-0.576 1.408zM10.016 24h2.080q0-0.064-0.032-0.416t-0.064-0.576 0.064-0.544 0.032-0.448h-2.080v1.984zM10.016 20h2.464q0.288-1.088 0.768-1.984h-3.232v1.984zM10.016 16h4.576q0.992-1.216 2.112-1.984h-6.688v1.984zM10.016 12h16v-1.984h-16v1.984zM10.016 8h16v-1.984h-16v1.984zM14.016 23.008q0 1.824 0.704 3.488t1.92 2.88 2.88 1.92 3.488 0.704 3.488-0.704 2.88-1.92 1.92-2.88 0.704-3.488-0.704-3.488-1.92-2.88-2.88-1.92-3.488-0.704-3.488 0.704-2.88 1.92-1.92 2.88-0.704 3.488zM18.016 23.008q0-2.080 1.44-3.52t3.552-1.472 3.52 1.472 1.472 3.52q0 2.080-1.472 3.52t-3.52 1.472-3.552-1.472-1.44-3.52zM22.016 23.008q0 0.416 0.288 0.704t0.704 0.288h1.984q0.416 0 0.704-0.288t0.32-0.704-0.32-0.704-0.704-0.288h-0.992v-0.992q0-0.416-0.288-0.704t-0.704-0.32-0.704 0.32-0.288 0.704v1.984z">
                                    </path>
                                </g>
                            </svg>
                        </span>
                        <span> قائمة العمليات </span>
                    </a>
                @endif
                <a href="{{ route('permission_user') }}"
                    class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                    <span class="mr-3">
                        <!-- Embed Icon -->
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M4 21C4 17.134 7.13401 14 11 14C11.3395 14 11.6734 14.0242 12 14.0709M15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7ZM12.5898 21L14.6148 20.595C14.7914 20.5597 14.8797 20.542 14.962 20.5097C15.0351 20.4811 15.1045 20.4439 15.1689 20.399C15.2414 20.3484 15.3051 20.2848 15.4324 20.1574L19.5898 16C20.1421 15.4477 20.1421 14.5523 19.5898 14C19.0376 13.4477 18.1421 13.4477 17.5898 14L13.4324 18.1574C13.3051 18.2848 13.2414 18.3484 13.1908 18.421C13.1459 18.4853 13.1088 18.5548 13.0801 18.6279C13.0478 18.7102 13.0302 18.7985 12.9948 18.975L12.5898 21Z"
                                    stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </g>
                        </svg>
                    </span>
                    <span> التحكم بالصلاحيات </span>
                </a>

                <a href="{{ route('backup.form') }}"
                    class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                    <span class="mr-3">
                        <!-- Embed Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 1v3M8.5 2.5l2 2M1 12h3m16 0h3m-9 7l2 2M6.5 21.5l2-2M21 12l2-2M6 9h12v6H6V9z" />
                        </svg>
                    </span>
                    <span>نسخ احتياطي للبيانات</span>
                </a>
                <a href="{{ route('default_suppliers.index') }}"
                    class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                    <span class="mr-3">
                        <!-- Leads Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354c-4.418 0-8 1.79-8 4v7.99a3 3 0 003 3h2m3 0h6a3 3 0 003-3V8.354c0-2.21-3.582-4-8-4zM12 1v3M9.879 5.879l-2.121 2.122M4 9h3M20 9h-3M14.121 5.879l2.122 2.122" />
                        </svg>
                    </span>
                    <span> اعدادات النظام</span>
                </a>
                {{--             <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                <span class="mr-3">
                    <!-- Add-ons Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-6v7h6V5zm0 7h6V5h-6v7zm-1 6h8M8 17h-3m8-6h1v1m-3 0h1v1m-2-2h1v1m-3 0h1v1m-2-2h1v1m-1 5h5" />
                    </svg>
                </span>
                <span>Add-ons</span>
            </a> --}}
                <hr class="my-2 border-gray-300">
                {{--             <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                <span class="mr-3">
                    <!-- Settings Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v3M8.5 2.5l2 2M1 12h3m16 0h3m-9 7l2 2M6.5 21.5l2-2M21 12l2-2M6 9h12v6H6V9z" />
                    </svg>
                </span>
                <span>Settings</span>
            </a> --}}



            </nav>
        </div>

        <!-- Footer Note -->

    </aside>
    <script>
        $('#executeBtn').click(function() {
            $.ajax({
                url: "{{ route('executeC') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function() {
                    alert('حدث خطأ أثناء تنفيذ الأمر!');
                }
            });
        });
    </script>
@endsection
