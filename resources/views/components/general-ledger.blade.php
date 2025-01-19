<div class="container mx-auto px-4 py-8">
    {{-- resources/views/components/general-ledger-table.blade.php --}}
    <input type="text" wire:model.debounce.500ms="search" placeholder="Search...">

    <h1 class="text-2xl font-bold mb-4">General Ledger</h1>
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">عرض</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Main Account</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Account</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accounting ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated At</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($ledgers as $ledger)
                    <tr>
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><a href="{{ route('general.ledger', ['id' => $ledger->general_ledge_id, 'accounting_id' => $ledger->accounting_id]) }}" class="text-blue-500 hover:underline">عرض</a></td> --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ledger->general_ledge_id }}</td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ledger->user->name }}</td> --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ledger->mainAccount->accoun_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ledger->subAccount->sub_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ledger->accounting_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ledger->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ledger->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $ledgers->links() }}
    </div>
</div>