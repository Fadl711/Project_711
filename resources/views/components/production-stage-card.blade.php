<div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 @if($stage->is_active) border-blue-500 @else border-gray-400 @endif">
    <div class="p-4">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-lg font-medium text-gray-900">{{ $stage->name }}</h3>
                <p class="text-sm text-gray-500">الترتيب: {{ $stage->sequence }}</p>
            </div>
            <span class="px-2 py-1 text-xs rounded-full 
                @if($stage->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                @if($stage->is_active) نشطة @else غير نشطة @endif
            </span>
        </div>
        
        @if($stage->purpose)
        <div class="mt-2">
            <p class="text-sm text-gray-700">{{ $stage->purpose }}</p>
        </div>
        @endif
        
        <div class="mt-4 grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500">المدة المعيارية</p>
                <p class="text-sm font-medium">{{ number_format($stage->standard_duration, 2) }} ثانية/وحدة</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">الجودة المستهدفة</p>
                <p class="text-sm font-medium">{{ number_format($stage->target_yield, 2) }}%</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">أقصى عيوب</p>
                <p class="text-sm font-medium">{{ number_format($stage->max_defect_rate, 2) }}%</p>
            </div>
        </div>
        
        @if($showActions ?? false)
        <div class="mt-4 flex justify-end space-x-2">
            <a href="{{ route('production-stages.show', $stage) }}" class="text-blue-600 hover:text-blue-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="{{ route('production-stages.edit', $stage) }}" class="text-indigo-600 hover:text-indigo-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            </a>
        </div>
        @endif
    </div>
</div>