@extends('layout')
@section('conm')

    <x-navbar_accounts />
    <br>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        .header-section {
            background-color: #f3f4f6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        table th,
        table td {
            text-align: center;
        }

        .no-print button {
            transition: background-color 0.3s ease;
        }

        .no-print button:hover {
            transform: scale(1.05);
        }

        @media print {
            .no-print {
                display: none;
            }

        }

        body {

            font-family: Arial, sans-serif;
            /* الخط الافتراضي */
        }

        /* تثبيت الأرقام بالإنجليزية */
        .english-numbers {
            font-feature-settings: 'tnum';
            direction: ltr;
            unicode-bidi: plaintext;
        }
    </style>
    <div id="successMessage" class="alert-success" style="display: none;"></div>


    <div class="grid mb-4 min-w-[100%]   " id="mainaccount">

        <div class="w-[80%]">
            <form id="ajaxForm">
                @csrf
                <div class=" flex border-b-2 border-blue-700  pb-2">

                    <div class="flex   ">
                        <label class="font-bold" for="">طبيعة الحساب :</label>

                        <div class="flex px-4">
                            <label class="labelSale">مدين</label>
                            <input type="radio" required value="مدين" name="Nature_account"
                                class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>

                        <div class="flex ">
                            <label class="labelSale">دائن</label>
                            <input type="radio" required value="دائن" name="Nature_account"
                                class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </div>

                    <div class="flex px-4">
                        <label class="font-bold" for=""> نوع الحساب :</label>
                        @isset($accountClasses)
                            @foreach ($accountClasses as $accountClass)
                                <div class="flex px-4">
                                    <label class="labelSale">{{ $accountClass->label() }} </label>
                                    <input type="radio" required value="{{ $accountClass->value }}" id="AccountClass"
                                        name="AccountClass"
                                        class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                </div>
                            @endforeach
                        @endisset
                    </div>
                </div>
                <br>
                <div class="grid gap-4 mb-4  lg:grid-cols-6  max-sm:grid-cols-2">
                    <div class="mb-2">
                        <label class="labelSale" for="account_name">اسم الحساب</label>
                        <input name="account_name" class="inputSale  h-7 input-field" id="account_name" type="text"
                            required placeholder="اسم الحساب الجديد" />
                    </div>
                    <div class="mb-2">
                        <label class="labelSale  " for="typeAccount"> تصنيف الحساب</label>
                        <select class=" input-field inputSale  text-left select2 " required name="typeAccount"
                            id="typeAccount">
                            <option selected></option>
                            @foreach ($AccountTypes as $AccountType)
                                <option value="{{ $AccountType->value }}">{{ $AccountType->label() }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="labelSale  " required for="Type_migration"> يرحل الى </label>
                        <select id="Type_migration" class=" text-left select2 input-field inputSale" name="Type_migration">
                            <option selected></option>
                            @foreach ($financal as $financa)
                                <option value="{{ $financa->value }}">{{ $financa->label() }} </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-2">
                        <button type="button" id="saveButton"
                            class="  inline-flex items-center bg-gradient-to-t text-white from-indigo-900 to-indigo-600   font-medium rounded-lg text-sm px-5 text-nowrap py-2.5 m-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

                            حفظ البيانات
                        </button>
                    </div>
                </div>
        </div>
        @auth
            <input type="hidden" name="User_id" required id="User_id" value="{{ Auth::user()->id }}">
        @endauth


        </form>
    </div>

    <div class=" overflow-x-auto bg-white shadow-md sm:rounded-lg    w-full px-4 py-2   max-h-[80vh]">
        <table class="text-sm  bg-indigo-900 font-semibold w-full overflow-y-auto max-h-[80vh] border-collapse">
            <thead class="bg-indigo-900 text-white sticky top-0  uppercase dark:bg-gray-700 dark:text-gray-400 ">
                <tr class="text-white bg-[#2430d3]">
                    <th class="text-right ">رقم الحساب</th>
                    <th class="text-right ">اسم الحساب</th>
                    <th class="text-right "> طبيعة الحساب</th>
                    <th class="text-right ">تصنيف الحساب</th>
                    <th class="text-right "> يرحل الى</th>
                    <th class="text-right "> المستخدم</th>
                    <th class="text-right "> تاريخ الاضافة</th>
                    <th class="text-right "> تاريخ التحديث</th>



                    <th class="text-right "> خيارات</th>

                </tr>
            </thead>
            <tbody>
                @auth
                    @foreach ($main_accounts as $main_account)
                        {{-- @foreach ($subAccounts as $subAccount) --}}

                        <tr>


                            {{-- @if ($subAccount->Main_id == $mainAccount->sub_account_id)  --}}

                            <td class="text-right tagTd">{{ $main_account->main_account_id }}</td>
                            <td class="text-right tagTd">{{ $main_account->account_name }}</td>
                            <td class="text-right tagTd">{{ $main_account->Nature_account }}</td>
                            @foreach ($TypesAccounts as $TypesAccount)
                                @if ($main_account->typeAccount == $TypesAccount['id']->value)
                                    <td class="text-right tagTd">{{ $TypesAccount['TypesAccountName'] }}</td>
                                @endif
                            @endforeach

                            @foreach ($financal as $Deportatton)
                                @if ($main_account->Type_migration == $Deportatton->value)
                                    <td class="text-right tagTd">{{ $Deportatton->label() }}</td>
                                @endif
                            @endforeach
                            <td class="text-right tagTd">{{ $main_account->user->name }}</td>

                            <td class="text-right tagTd"> {{ $main_account->created_at }}</td>
                            <td class="text-right tagTd">{{ $main_account->updated_at }}</td>




                            <td class="text-right tagTd">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('accounts.Main_Account.edit', $main_account->main_account_id) }}"
                                        class="p-1  rounded-full  group transition-all duration-500  flex item-center">
                                        <svg class="cursor-pointer" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="fill-indigo-500 "
                                                d="M9.53414 8.15675L8.96459 7.59496L8.96459 7.59496L9.53414 8.15675ZM13.8911 3.73968L13.3215 3.17789V3.17789L13.8911 3.73968ZM16.3154 3.75892L15.7367 4.31126L15.7367 4.31127L16.3154 3.75892ZM16.38 3.82658L16.9587 3.27423L16.9587 3.27423L16.38 3.82658ZM16.3401 6.13595L15.7803 5.56438L16.3401 6.13595ZM11.9186 10.4658L12.4784 11.0374L11.9186 10.4658ZM11.1223 10.9017L10.9404 10.1226V10.1226L11.1223 10.9017ZM9.07259 10.9951L8.52556 11.5788L8.52556 11.5788L9.07259 10.9951ZM9.09713 8.9664L9.87963 9.1328V9.1328L9.09713 8.9664ZM9.05721 10.9803L8.49542 11.5498H8.49542L9.05721 10.9803ZM17.1679 4.99458L16.368 4.98075V4.98075L17.1679 4.99458ZM15.1107 2.8693L15.1171 2.06932L15.1107 2.8693ZM9.22851 8.51246L8.52589 8.12992L8.52452 8.13247L9.22851 8.51246ZM9.22567 8.51772L8.52168 8.13773L8.5203 8.1403L9.22567 8.51772ZM11.5684 10.7654L11.9531 11.4668L11.9536 11.4666L11.5684 10.7654ZM11.5669 10.7662L11.9507 11.4681L11.9516 11.4676L11.5669 10.7662ZM11.3235 3.30005C11.7654 3.30005 12.1235 2.94188 12.1235 2.50005C12.1235 2.05822 11.7654 1.70005 11.3235 1.70005V3.30005ZM18.3 9.55887C18.3 9.11705 17.9418 8.75887 17.5 8.75887C17.0582 8.75887 16.7 9.11705 16.7 9.55887H18.3ZM3.47631 16.5237L4.042 15.9581H4.042L3.47631 16.5237ZM16.5237 16.5237L15.958 15.9581L15.958 15.9581L16.5237 16.5237ZM10.1037 8.71855L14.4606 4.30148L13.3215 3.17789L8.96459 7.59496L10.1037 8.71855ZM15.7367 4.31127L15.8013 4.37893L16.9587 3.27423L16.8941 3.20657L15.7367 4.31127ZM15.7803 5.56438L11.3589 9.89426L12.4784 11.0374L16.8998 6.70753L15.7803 5.56438ZM10.9404 10.1226C10.3417 10.2624 9.97854 10.3452 9.72166 10.3675C9.47476 10.3888 9.53559 10.3326 9.61962 10.4113L8.52556 11.5788C8.9387 11.966 9.45086 11.9969 9.85978 11.9615C10.2587 11.9269 10.7558 11.8088 11.3042 11.6807L10.9404 10.1226ZM8.31462 8.8C8.19986 9.33969 8.09269 9.83345 8.0681 10.2293C8.04264 10.6393 8.08994 11.1499 8.49542 11.5498L9.619 10.4107C9.70348 10.494 9.65043 10.5635 9.66503 10.3285C9.6805 10.0795 9.75378 9.72461 9.87963 9.1328L8.31462 8.8ZM9.61962 10.4113C9.61941 10.4111 9.6192 10.4109 9.619 10.4107L8.49542 11.5498C8.50534 11.5596 8.51539 11.5693 8.52556 11.5788L9.61962 10.4113ZM15.8013 4.37892C16.0813 4.67236 16.2351 4.83583 16.3279 4.96331C16.4073 5.07234 16.3667 5.05597 16.368 4.98075L17.9678 5.00841C17.9749 4.59682 17.805 4.27366 17.6213 4.02139C17.451 3.78756 17.2078 3.53522 16.9587 3.27423L15.8013 4.37892ZM16.8998 6.70753C17.1578 6.45486 17.4095 6.21077 17.5876 5.98281C17.7798 5.73698 17.9607 5.41987 17.9678 5.00841L16.368 4.98075C16.3693 4.90565 16.4103 4.8909 16.327 4.99749C16.2297 5.12196 16.0703 5.28038 15.7803 5.56438L16.8998 6.70753ZM14.4606 4.30148C14.7639 3.99402 14.9352 3.82285 15.0703 3.71873C15.1866 3.62905 15.1757 3.66984 15.1044 3.66927L15.1171 2.06932C14.6874 2.06591 14.3538 2.25081 14.0935 2.45151C13.8518 2.63775 13.5925 2.9032 13.3215 3.17789L14.4606 4.30148ZM16.8941 3.20657C16.6279 2.92765 16.373 2.65804 16.1345 2.46792C15.8774 2.26298 15.5468 2.07273 15.1171 2.06932L15.1044 3.66927C15.033 3.66871 15.0226 3.62768 15.1372 3.71904C15.2704 3.82522 15.4387 3.999 15.7367 4.31126L16.8941 3.20657ZM8.96459 7.59496C8.82923 7.73218 8.64795 7.90575 8.5259 8.12993L9.93113 8.895C9.92075 8.91406 9.91465 8.91711 9.93926 8.88927C9.97002 8.85445 10.0145 8.80893 10.1037 8.71854L8.96459 7.59496ZM9.87963 9.1328C9.9059 9.00925 9.91925 8.94785 9.93124 8.90366C9.94073 8.86868 9.94137 8.87585 9.93104 8.89515L8.5203 8.1403C8.39951 8.36605 8.35444 8.61274 8.31462 8.8L9.87963 9.1328ZM8.52452 8.13247L8.52168 8.13773L9.92967 8.89772L9.9325 8.89246L8.52452 8.13247ZM11.3589 9.89426C11.27 9.98132 11.2252 10.0248 11.1909 10.055C11.1635 10.0791 11.1658 10.0738 11.1832 10.0642L11.9536 11.4666C12.1727 11.3462 12.3427 11.1703 12.4784 11.0374L11.3589 9.89426ZM11.3042 11.6807C11.4912 11.6371 11.7319 11.5878 11.9507 11.4681L11.1831 10.0643C11.2007 10.0547 11.206 10.0557 11.1697 10.0663C11.1248 10.0793 11.0628 10.0941 10.9404 10.1226L11.3042 11.6807ZM11.1837 10.064L11.1822 10.0648L11.9516 11.4676L11.9531 11.4668L11.1837 10.064ZM16.399 6.10097L13.8984 3.60094L12.7672 4.73243L15.2677 7.23246L16.399 6.10097ZM10.8333 16.7001H9.16667V18.3001H10.8333V16.7001ZM3.3 10.8334V9.16672H1.7V10.8334H3.3ZM9.16667 3.30005H11.3235V1.70005H9.16667V3.30005ZM16.7 9.55887V10.8334H18.3V9.55887H16.7ZM9.16667 16.7001C7.5727 16.7001 6.45771 16.6984 5.61569 16.5851C4.79669 16.475 4.35674 16.2728 4.042 15.9581L2.91063 17.0894C3.5722 17.751 4.40607 18.0369 5.4025 18.1709C6.37591 18.3018 7.61793 18.3001 9.16667 18.3001V16.7001ZM1.7 10.8334C1.7 12.3821 1.6983 13.6241 1.82917 14.5976C1.96314 15.594 2.24905 16.4279 2.91063 17.0894L4.042 15.9581C3.72726 15.6433 3.52502 15.2034 3.41491 14.3844C3.3017 13.5423 3.3 12.4273 3.3 10.8334H1.7ZM10.8333 18.3001C12.3821 18.3001 13.6241 18.3018 14.5975 18.1709C15.5939 18.0369 16.4278 17.751 17.0894 17.0894L15.958 15.9581C15.6433 16.2728 15.2033 16.475 14.3843 16.5851C13.5423 16.6984 12.4273 16.7001 10.8333 16.7001V18.3001ZM16.7 10.8334C16.7 12.4274 16.6983 13.5423 16.5851 14.3844C16.475 15.2034 16.2727 15.6433 15.958 15.9581L17.0894 17.0894C17.7509 16.4279 18.0369 15.594 18.1708 14.5976C18.3017 13.6241 18.3 12.3821 18.3 10.8334H16.7ZM3.3 9.16672C3.3 7.57275 3.3017 6.45776 3.41491 5.61574C3.52502 4.79674 3.72726 4.35679 4.042 4.04205L2.91063 2.91068C2.24905 3.57225 1.96314 4.40612 1.82917 5.40255C1.6983 6.37596 1.7 7.61798 1.7 9.16672H3.3ZM9.16667 1.70005C7.61793 1.70005 6.37591 1.69835 5.4025 1.82922C4.40607 1.96319 3.5722 2.24911 2.91063 2.91068L4.042 4.04205C4.35674 3.72731 4.79669 3.52507 5.61569 3.41496C6.45771 3.30175 7.5727 3.30005 9.16667 3.30005V1.70005Z"
                                                fill="#818CF8"></path>
                                        </svg>
                                    </a>
                                    <button
                                        class="p-1 focus:outline-none  rounded-full  group transition-all duration-500  flex item-center"
                                        data-toggle="modal" data-target="#delete-modal-{{ $main_account->main_account_id }}">
                                        <svg class="" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="fill-red-600"
                                                d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z"
                                                fill="#F87171"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                            <!-- Modal -->
                            <div class="modal" tabindex="-1" role="dialog"
                                id="delete-modal-{{ $main_account->main_account_id }}">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content bg-white rounded shadow-md">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-lg font-bold "> حذف الحساب</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-gray-600">هل انت متاكد من حذف هذا الحساب؟</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn bg-blue-500 text-white"
                                                data-dismiss="modal">الغاء</button>
                                            <form
                                                action="{{ route('accounts.Main_Account.destroy', $main_account->main_account_id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger text-white">حذف</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- @endif --}}


                        </tr>
                    @endforeach
                    {{-- @endforeach --}}
                @endauth
            </tbody>
        </table>
    </div>
    </div>
    </div>

    <div id="successMessage" style="display: none;">
        <p>تم الحفظ بنجاح!</p>
    </div>
    <div id="errorMessage" style="display: none; color: red;">
        <p>حدث خطأ أثناء الحفظ.</p>
    </div>
    </div>
    <!-- منطقة طباعة البيانات المحفوظة -->
    <div id="results" class="results"></div>
    <script src="{{ url('payments.js') }}"></script>

    <style>
        .alert-success {
            color: green;
            font-weight: bold;
        }
    </style>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();

            const successMessage = $('#successMessage');
            const errorMessage = $('#errorMessage');
            const inputs = $('.input-field'); // تحديد جميع الحقول
            const account_name = $('#account_name');

            // تركيز على حقل الاسم عند بدء التشغيل
            account_name.focus();

            // منع السلوك الافتراضي لزر Enter
            const form = $('#ajaxForm');
            form.on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
                }
            });

            // التنقل بين الحقول باستخدام السهم الأيمن أو الأيسر
            $(document).on('keydown', function(event) {
                if (event.key === "ArrowRight" || event.key === "ArrowLeft") {
                    let currentIndex = inputs.index(document.activeElement);

                    if (currentIndex !== -1) {
                        if (event.key === "ArrowRight") {
                            if (currentIndex < inputs.length - 1) {
                                $(inputs[currentIndex + 1]).focus(); // نقل التركيز إلى الحقل التالي
                            }
                        } else if (event.key === "ArrowLeft") {
                            if (currentIndex > 0) {
                                $(inputs[currentIndex - 1]).focus(); // نقل التركيز إلى الحقل السابق
                            }
                        }
                    }
                }
            });

            $(document).keydown(function(event) {
                if (event.ctrlKey && event.shiftKey) {
                    event.preventDefault(); // منع السلوك الافتراضي (حفظ الصفحة)
                    saveData(); // استدعاء دالة الحفظ
                }
            });
            $('#saveButton').click(function() {
                saveData(); // استدعاء دالة الحفظ
            });
            // التعامل مع إرسال النموذج وحفظ البيانات باستخدام AJAX
            function saveData() {
                event.preventDefault(); // منع تحديث الصفحة
                // تجميع بيانات النموذج
                const formData = new FormData($('#ajaxForm')[0]);

                $.ajax({
                    url: '{{ route('Main_Account.store') }}', // استبدل هذا بالمسار الخاص بك
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // إرسال التوكن الخاص بـ Laravel
                    },
                    data: formData,
                    processData: false, // ضروري مع FormData
                    contentType: false, // ضروري مع FormData
                    success: function(data) {
                        if (data.success) {
                            // إظهار رسالة النجاح
                            Swal.fire({
                                title: 'نجاح!',
                                text: "تم اضافة الحساب بنجاح",
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            account_name.focus(); // إعادة التركيز على حقل الاسم بعد الحفظ

                            // إخفاء الرسالة بعد 3 ثوانٍ
                            setTimeout(() => {
                                location.reload();
                            }, 1000);

                            // إضافة البيانات المحفوظة إلى الجدول
                            addToTable(data.DataSubAccount);

                            // تفريغ النموذج بعد الحفظ
                            form[0].reset();
                        } else {
                            // إظهار رسالة عند وجود نفس الاسم
                            errorMessage.show().text('يوجد نفس هذا الاسم من قبل');
                            account_name.focus();

                            setTimeout(() => {
                                errorMessage.hide();
                            }, 1000);
                        }
                    },
                    error: function() {
                        errorMessage.show().text('حدث خطأ أثناء الحفظ.');
                    }
                });
            };

            // وظيفة لإضافة البيانات إلى الجدول
            function addToTable(account) {
                const tableBody = $('#mainAccountsTable tbody');
                const newRow = `
          <tr>
              <td class="text-right tagTd">${account.main_account_id}</td>
              <td class="text-right tagTd">${account.account_name}</td>
              <td class="text-right tagTd">${account.Nature_account || 0}</td>
              <td class="text-right tagTd">${account.typeAccount || 0}</td>
              <td class="text-right tagTd">${account.User_id}</td>
              <td class="text-right tagTd">${account.Type_migration}</td>
          </tr>
      `;
                tableBody.append(newRow); // إضافة الصف الجديد إلى الجدول
            }
        });
    </script>

@endsection
