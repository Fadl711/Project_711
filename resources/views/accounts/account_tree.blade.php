@extends('layout')
@section('conm')
<x-navbar_accounts/>

<div class="container mx-auto p-6" dir="rtl">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">شجرة الحسابات</h1>

    <!-- Account Types Table with Fixed Height and Scroll -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
            <table class="min-w-full">
                <tbody class="bg-white">
                    @foreach (\App\Enum\AccountType::cases() as $type)
                        <!-- Account Type Row -->
                        <tr class="account-type-row hover:bg-gray-50 cursor-pointer transition-colors border-b sticky top-0 bg-white z-10" data-type="{{ $type->value }}">
                            <td class="px-6 py-4" colspan="4">
                                <div class="flex items-center">
                                    <span class="w-4 h-4 rounded-sm mr-2" style="background-color: {{ match($type->value) {
                                        1 => '#4ade80',  // أصول متداولة
                                        2 => '#60a5fa',  // اصول ثابتة
                                        3 => '#f472b6',  // الإتزامات/الخصوم
                                        4 => '#fb923c',  // المصروفات
                                        5 => '#a78bfa',  // الإيرادات
                                    } }}"></span>
                                    <span class="font-bold text-lg">{{ $type->label() }}</span>
                                    <span class="text-sm text-gray-500 mr-4" id="count-type-{{ $type->value }}">(0)</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Main Accounts Container -->
                        <tr class="main-accounts-container hidden" id="main-accounts-{{ $type->value }}">
                            <td colspan="4" class="p-0">
                                <div class="border-r-4" style="border-color: {{ match($type->value) {
                                    1 => '#4ade80',
                                    2 => '#60a5fa',
                                    3 => '#f472b6',
                                    4 => '#fb923c',
                                    5 => '#a78bfa',
                                } }}">
                                    <table class="min-w-full">
                                        <tbody class="main-accounts-body">
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accountTypeRows = document.querySelectorAll('.account-type-row');
    let activeTypes = new Set();
    let activeMainAccounts = new Set();

    accountTypeRows.forEach(row => {
        row.addEventListener('click', async function() {
            const type = this.dataset.type;
            const mainAccountsContainer = document.getElementById(`main-accounts-${type}`);

            if (activeTypes.has(type)) {
                mainAccountsContainer.classList.add('hidden');
                activeTypes.delete(type);
            } else {
                mainAccountsContainer.classList.remove('hidden');
                activeTypes.add(type);

                try {
                        const apiUrl = "{{ url('/api/accounts/by-type') }}";
                     const response = await fetch(`${apiUrl}/${type}`);
                    const mainAccounts = await response.json();

                    document.getElementById(`count-type-${type}`).textContent = `(${mainAccounts.length})`;

                    const tbody = mainAccountsContainer.querySelector('.main-accounts-body');
                    tbody.innerHTML = '';

                    mainAccounts.forEach(account => {
                        // Main Account Row
                        const tr = document.createElement('tr');
                        tr.className = 'main-account-row hover:bg-gray-50 cursor-pointer transition-colors border-b';
                        tr.dataset.id = account.main_account_id;

                        tr.innerHTML = `
                            <td class="pr-16 py-3">
                                <div class="flex items-center">
                                    <span class="text-gray-800">${account.main_account_id} - ${account.account_name}</span>
                                    <div class="mr-auto flex gap-4">
                                        <span class="text-gray-600">مدين: ${account.debit_balance || 0}</span>
                                        <span class="text-gray-600">دائن: ${account.credit_balance || 0}</span>
                                    </div>
                                </div>
                            </td>
                        `;

                        tbody.appendChild(tr);

                        // Sub Accounts Container
                        const subAccountsContainer = document.createElement('tr');
                        subAccountsContainer.className = 'sub-accounts-container hidden';
                        subAccountsContainer.id = `sub-accounts-${account.main_account_id}`;

                        subAccountsContainer.innerHTML = `
                            <td class="p-0">
                                <div class="border-r-2 border-gray-300 mr-16">
                                    <table class="min-w-full">
                                        <tbody class="sub-accounts-body">
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        `;

                        tbody.appendChild(subAccountsContainer);

                        // Add click handler for main account
                        tr.addEventListener('click', async function() {
                            const mainId = this.dataset.id;
                            const subContainer = document.getElementById(`sub-accounts-${mainId}`);

                            if (activeMainAccounts.has(mainId)) {
                                subContainer.classList.add('hidden');
                                activeMainAccounts.delete(mainId);
                            } else {
                                subContainer.classList.remove('hidden');
                                activeMainAccounts.add(mainId);

                                try {
                                    const apiUrl = "{{ url('/api/accounts') }}";

                                    const response = await fetch(`${apiUrl}/${mainId}/sub-accounts`);
                                    const subAccounts = await response.json();

                                    const subBody = subContainer.querySelector('.sub-accounts-body');
                                    subBody.innerHTML = '';

                                    subAccounts.forEach(sub => {
                                        const subTr = document.createElement('tr');
                                        subTr.className = 'hover:bg-gray-50 transition-colors border-b';
                                        subTr.innerHTML = `
                                            <td class="pr-24 py-3">
                                                <div class="flex items-center">
                                                    <span class="text-gray-800">${sub.sub_account_id} - ${sub.sub_name}</span>
                                                    <div class="mr-auto flex gap-4">
                                                        <span class="text-gray-600">مدين: ${sub.debtor_amount || 0}</span>
                                                        <span class="text-gray-600">دائن: ${sub.creditor_amount || 0}</span>
                                                    </div>
                                                </div>
                                            </td>
                                        `;
                                        subBody.appendChild(subTr);
                                    });
                                } catch (error) {
                                    console.error('Error fetching sub accounts:', error);
                                }
                            }
                        });
                    });
                } catch (error) {
                    console.error('Error fetching main accounts:', error);
                }
            }
        });
    });
});
</script>

<style>
.account-type-row:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Custom Scrollbar Styles */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection
