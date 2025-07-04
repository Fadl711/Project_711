@extends('layout')
@section('conm')
    <div class="container mx-auto p-4" x-data="operations1">
        <div class="bg-blue-100 opacity-75 shadow rounded-lg p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">سجل العمليات</h1>

            <!-- فلتر العمليات -->
            <div class="flex gap-4 mb-6">
                <button @click="filter = 'all'" :class="{ 'bg-blue-500 text-white': filter === 'all' }"
                    class="px-4 py-2 rounded-lg border border-gray-300">
                    الكل
                </button>
                <button @click="filter = 'unseen'" :class="{ 'bg-blue-500 text-white': filter === 'unseen' }"
                    class="px-4 py-2 rounded-lg border border-gray-300">
                    غير المقروءة
                </button>
                <button @click="filter = 'seen'" :class="{ 'bg-blue-500 text-white': filter === 'seen' }"
                    class="px-4 py-2 rounded-lg border border-gray-300">
                    المقروءة
                </button>
            </div>

            <!-- إحصاءات -->
            <div class="flex gap-4 mb-6">
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                    غير مقروءة: <span x-text="unseenCount"></span>
                </span>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                    مقروءة: <span x-text="seenCount"></span>
                </span>
            </div>
        </div>

        <!-- قائمة العمليات -->
        <div class="bg-white shadow rounded-lg mb-10">
            <ul class="divide-y divide-gray-200">
                <template x-for="operation in filteredOperations" :key="operation.id">
                    <li class="p-4 hover:bg-gray-50 transition duration-150 ease-in-out flex justify-between items-center"
                        :class="{ 'bg-gray-50': operation.is_seen }">
                        <div>
                            <p x-text="operation.message" class="text-sm font-medium text-gray-900"></p>
                            <div class="flex gap-2 mt-1">
                                <span x-text="operation.type" class="text-xs px-2 py-1 rounded"
                                    :class="getTypeClass(operation.type)"></span>
                                <span x-text="new Date(operation.created_at).toLocaleString()"
                                    class="text-xs text-gray-500"></span>
                                <p x-text="'بواسطة: ' + getUserName(operation)"></p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button @click="markSeen(operation.id)" x-show="!operation.is_seen"
                                class="text-gray-400 hover:text-blue-500" title="تعليم كمقروء">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button @click="deleteOperation(operation.id)" class="text-gray-400 hover:text-red-500"
                                title="حذف">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </li>
                </template>
                <template x-if="filteredOperations.length === 0">
                    <li class="p-4 text-center text-gray-500">لا توجد عمليات</li>
                </template>
            </ul>
        </div>
    </div>

    <script>
        window.Laravel = {!! json_encode([
            'baseUrl' => url('/'), // إضافة الـ URL الأساسي
        ]) !!};
        document.addEventListener('alpine:init', () => {
            Alpine.data('operations1', () => {
                return {
                    operations1: @json($operationsAll),

                    getUserName(operation) {
                        return operation.user?.name || 'مجهول';
                    },
                    filter: 'all',

                    get filteredOperations() {
                        if (this.filter === 'unseen') {
                            return this.operations1.filter(op => !op.is_seen);
                        } else if (this.filter === 'seen') {
                            return this.operations1.filter(op => op.is_seen);
                        }
                        return this.operations1;
                    },

                    get unseenCount() {
                        return this.operations1.filter(op => !op.is_seen).length;
                    },

                    get seenCount() {
                        return this.operations1.filter(op => op.is_seen).length;
                    },

                    getTypeClass(type) {
                        const classes = {
                            'حذف': 'bg-red-100 text-red-800',
                            'تعديل': 'bg-yellow-100 text-yellow-800',
                            'إضافة': 'bg-green-100 text-green-800',
                            'تنبيه': 'bg-blue-100 text-blue-800'
                        };
                        return classes[type] || 'bg-gray-100 text-gray-800';
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
                                const index = this.operations1.findIndex(op => op.id === operationId);
                                this.operations1[index].is_seen = 1;
                            }
                        } catch (error) {
                            console.error('Error:', error);
                        }
                    },

                    async deleteOperation(operationId) {
                        const result = await Swal.fire({
                            title: 'هل أنت متأكد من الحذف؟',
                            text: "لن تتمكن من التراجع!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'نعم، احذف',
                            cancelButtonText: 'إلغاء'
                        });

                        if (result.isConfirmed) {
                            try {
                                const res = await fetch(
                                    `${window.Laravel.baseUrl}/operations/${operationId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json',
                                        },
                                    });
                                const data = await res.json();
                                if (data.success) {
                                    Swal.fire({
                                        title: 'تم الحذف!',
                                        text: data.message ?? 'تمت عملية الحذف بنجاح',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    this.operations1 = this.operations1.filter(op => op.id !==
                                        operationId);
                                } else {
                                    Swal.fire('خطأ', data.message, 'error');
                                }
                            } catch {
                                Swal.fire('خطأ', 'حدث خطأ أثناء الحذف', 'error');
                            }
                        }
                    }
                }
            });

        });
    </script>
@endsection
