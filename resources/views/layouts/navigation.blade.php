<nav x-data="{ open: false, notificationOpen: false }" class="bg-white print:hidden  dark:bg-gray-800 border-b border-gray-300 dark:border-gray-700 h-[55px]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home.index') }}">
                        @if (isset($buss->Company_Logo))
                            <x-application-logo class="block  w-auto fill-current text-gray-800 dark:text-gray-200" />
                        @else
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-nav-link :href="route('home.index')" :active="request()->routeIs('home.index')">
                                    {{ __('الرئيسي') }}
                                </x-nav-link>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('لوحة التحكم') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center ms-4">
                    @if (auth()->user()->hasPermission('الاشعارات'))

                        <button @click="notificationOpen = true" class="relative focus:outline-none">
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if ($operations->count() > 0)
                                <span id="not">
                                    <span
                                        class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600   animate-ping rounded-full"></span>
                                    <span
                                        class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600  rounded-full"></span>
                                </span>
                            @endif

                        </button>
                    @endif
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('الملف الشخصي') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('تسجيل الخروج') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center text-gray-800 dark:text-gray-200">
                <span>{{ Auth::user()->name }}</span>
                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md shadow-lg py-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('الملف الشخصي') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification Button -->


    <!-- Notification Modal -->
    @if (auth()->user()->hasPermission('الاشعارات'))
        <div x-show="notificationOpen" style="display: none;"
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/30 backdrop-blur-sm">
            <div @click.away="notificationOpen = false"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">الإشعارات</h2>
                    <button @click="notificationOpen = false"
                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div x-data="{ activeTab: 'operations' }" class="space-y-4">
                    <!-- أزرار التبديل -->
                    <div class="flex gap-4">
                        <button @click="activeTab = 'operations'"
                            :class="{ 'bg-blue-500 text-white': activeTab === 'operations', 'bg-gray-200': activeTab !== 'operations' }"
                            class="px-4 py-2 rounded-lg transition">
                            العمليات
                        </button>

                        <button @click="activeTab = 'expiry'"
                            :class="{ 'bg-blue-500 text-white': activeTab === 'expiry', 'bg-gray-200': activeTab !== 'expiry' }"
                            class="px-4 py-2 rounded-lg transition">
                            منتجات مقاربة الانتهاء
                        </button>
                    </div>

                    <!-- قسم العمليات -->
                    <div x-show="activeTab === 'operations'" style="display: none;">
                        <div class="container mx-auto p-4" x-data="operations">
                            <h1 class="text-2xl font-bold text-gray-800 mb-6">سجل العمليات</h1>

                            @if ($operations->count() > 0)
                                <button @click="markAllSeen()" id="mark-all-seen"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 -mx-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    قراءة الكل
                                </button>
                            @endif

                            <div class="bg-white shadow rounded-lg overflow-auto max-h-96">
                                <ul class="divide-y divide-gray-200">
                                    <template x-for="operation in operations" :key="operation.id">
                                        <li
                                            class="p-4 hover:bg-gray-50 transition duration-150 ease-in-out flex justify-between items-center">
                                            <div>
                                                <p x-text="operation.message"
                                                    class="text-sm font-medium text-gray-900"></p>
                                                <p x-text="'بواسطة: ' + getUserName(operation)"></p>
                                                <p x-text="new Date(operation.created_at).toLocaleString()"
                                                    class="text-sm text-gray-500"></p>
                                            </div>
                                            <button @click="markSeen(operation.id)"
                                                class="text-gray-400 hover:text-red-500" title="تعليم كمقروء">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </li>
                                    </template>
                                    <template x-if="operations.length === 0">
                                        <li class="p-4 text-center text-gray-500">لا توجد عمليات جديدة.</li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- قسم المنتجات المنتهية الصلاحية -->
                    <div x-show="activeTab === 'expiry'" style="display: none;">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-bold text-red-600 mb-4 border-b pb-2">المنتجات المنتهية أو المقاربة
                                للانتهاء</h2>

                            <div class="overflow-y-scroll max-h-96">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                اسم المنتج</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                حالة الصلاحية</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                الأيام المتبقية</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($expiringProducts as $product)
                                            <tr
                                                class="{{ floor($product->days_until_expiry) <= 0 ? 'bg-red-50' : (floor($product->days_until_expiry) <= 30 ? 'bg-yellow-50' : '') }}">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $product->product_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if (floor($product->days_until_expiry) <= 0)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            منتهي الصلاحية
                                                        </span>
                                                    @elseif(floor($product->days_until_expiry) <= 30)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            مقارب للانتهاء
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            ساري
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if (floor($product->days_until_expiry) <= 0)
                                                        <span class="text-red-600">انتهت منذ
                                                            {{ abs(floor($product->days_until_expiry)) }} يوم</span>
                                                    @else
                                                        {{ floor($product->days_until_expiry) }} يوم
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($expiringProducts->isEmpty())
                                <div class="text-center py-8 text-gray-500">
                                    لا توجد منتجات منتهية أو مقاربة للانتهاء
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
    @endif
</nav>

<script>
    window.Laravel = {!! json_encode([
        'baseUrl' => url('/'), // إضافة الـ URL الأساسي
    ]) !!};
    document.addEventListener('alpine:init', () => {
        Alpine.data('operations', () => ({
            operations: @json($operations), // تمرير البيانات من PHP إلى JS
            getUserName(operation) {
                return operation.user?.name || 'مجهول';
            },
            async markSeen(operationId) {
                try {
                    const response = await fetch(
                        `${window.Laravel.baseUrl}/operations/${operationId}/mark-seen`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                        });
                    const data = await response.json();

                    if (data.success) {
                        // إزالة العملية من القائمة
                        this.operations = this.operations.filter(op => op.id !== operationId);
                        // إذا كانت القائمة فارغة، إخفاء الإشعار
                        if (this.operations.length === 0) {
                            document.getElementById('not').style.display = 'none';
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            },

            async markAllSeen() {
                try {
                    const response = await fetch(
                        `${window.Laravel.baseUrl}/operations/mark-all-seen`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        });
                    const data = await response.json();

                    if (data.success) {
                        // إفراغ القائمة
                        this.operations = [];
                        // إخفاء الإشعار
                        document.getElementById('not').style.display = 'none';
                        document.getElementById('mark-all-seen').style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            },
        }));
    });
</script>
