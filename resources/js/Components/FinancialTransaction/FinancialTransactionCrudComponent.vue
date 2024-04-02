<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <!-- <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">

                <i class="fal fa-search"></i>
            </label> -->
            <input type="date" class="h-8 mr-2 rounded-md" v-model="fromDate">
            <input type="date" class="h-8 mx-2 rounded-md" v-model="toDate">

            <div class="bg-white mb-0 w-[40%] text-sm inline-block" data-te-select-wrapper-ref>
                <select data-te-select-init data-te-select-placeholder="Filter"
                data-te-select-filter="true" v-model="searchCategory">
                    <option value="-1"> Show all </option>
                    <option value="0"> Show unconfirmed </option>
                    <option value="1"> Show confirmed </option>
                </select>
            </div>

            <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
            <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button>
        </div>
        <div class="flex justify-end flex-col">

            <button type="button" class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                data-te-toggle="modal" data-te-target="#create_modal">
                Add New
            </button>
        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
            <button type="button" class="mt-4 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
            :disabled="toBeConfirmTransactionList.length < 1"
            data-te-toggle="modal" data-te-target="#confirm_modal">
                Confirm Selected
            </button>
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                No
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Remark
                            </th>
                            <th scope="col" class=" px-6 py-4 text-left">
                                Account Name
                            </th>
                            <th scope="col" class=" px-6 py-4 text-left">
                                Debit
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Credit
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- looping start -->
                        <div class="contents" v-for="(transaction, transactionIndex) in transactionList" :key="transactionIndex">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ per_page * (currentPage - 1) + (++transactionIndex) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ transaction.description }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    <div class="text-left">
                                        <div v-for="(ledger) in transaction.ledgers">
                                            <p class="mb-2" v-if="ledger.action == 'debit'">
                                                {{ ledger.account_name }}
                                            </p>
                                        </div>
                                        <div v-for="(ledger) in transaction.ledgers">
                                            <p class="mb-2" v-if="ledger.action == 'credit'">
                                                {{ ledger.account_name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    <div class="text-left">
                                        <div v-for="(ledger) in transaction.ledgers">
                                            <p class="mb-2" v-if="ledger.action == 'debit'">
                                                {{ (ledger.value).toLocaleString() }}
                                            </p>
                                        </div>
                                        <div v-for="(ledger) in transaction.ledgers">
                                            <p class="mb-2" v-if="ledger.action == 'credit'">
                                                0
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    <div class="text-left">
                                        <div v-for="(ledger) in transaction.ledgers">
                                            <p class="mb-2" v-if="ledger.action == 'debit'">
                                                0
                                            </p>
                                        </div>
                                        <div v-for="(ledger) in transaction.ledgers">
                                            <p class="mb-2" v-if="ledger.action == 'credit'">
                                                {{ (ledger.value).toLocaleString() }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ transaction.date }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="mb-[0.125rem] block min-h-[1.5rem] ps-[1.5rem]">
                                        <input
                                            class="relative float-left -ms-[1.5rem] me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-secondary-500 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-checkbox before:shadow-transparent before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ms-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-black/60 focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-checkbox checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ms-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent rtl:float-right dark:border-neutral-400 dark:checked:border-primary dark:checked:bg-primary"
                                            type="checkbox"
                                            value=""
                                            id="checkboxDefault"
                                            :disabled="transaction.is_confirmed == 1"
                                            :checked="transaction.is_confirmed == 1"
                                            @change="toggleTransactionInConfirmList(transaction.id, transactionIndex)"/>
                                        <label
                                            class="inline-block ps-[0.15rem] hover:cursor-pointer"
                                            for="checkboxDefault">
                                            Is Confirmed
                                        </label>
                                    </div>
                                    <button
                                    data-te-toggle="modal" data-te-target="#editModal" id="edit-btn" class="pr-3"
                                    @click="editBtnClicked(transaction.id)">
                                    <i class="fal fa-pen"></i>
                                    </button>
                                    <!-- <button id="edit-btn" class="pr-3">
                                    <i class="fal fa-check"></i>
                                    </button> -->
                                    <button
                                    data-te-toggle="modal" data-te-target="#deleteModal" id="edit-btn" class="pr-1"
                                    @click="deleteBtnClicked(transaction.id)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>
                        <!-- looping end -->
                    </tbody>
                </table>
            </div>

            <div class="mt-2 ml-2">
                <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                    <li v-if="!isFirstGroup">
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="previousPaginationGroupBtnClicked"
                        :disabled="isFirstGroup">
                            Previous
                        </button>
                    </li>

                    <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                (current)
                            </span>
                        </button>
                        <button v-else
                            class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                        </button>
                    </li>
                    <li v-if="!isLastGroup">
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="nextPaginationGroupBtnClicked"
                        :disabled="isLastGroup">
                            Next
                        </button>
                    </li>
                </ul>

                <ul v-else class="list-style-none flex">
                    <li v-for="(pageNumber, pageNumberIndex) in pageNumbers" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                (current)
                            </span>
                        </button>
                        <button v-else
                            class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>


        <!-- Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                            Add Transaction
                        </h5>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Parent Account
                            </label>
                            <select name="" id="" v-model="selectedSubAccount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0" @change="subAccountSelectChanged">
                                <option :value="subAccount" v-for="(subAccount, subAccountIndex) in subAccountList" :key="subAccountIndex">
                                    {{ subAccount.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Target Account
                            </label>
                            <select name="" id="" v-model="selectedAccount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="account" v-for="(account, accountIndex) in accountList" :key="accountIndex">
                                    {{ account.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Cash Account
                            </label>
                            <select name="" id="" v-model="selectedCashAccount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <!-- <option value="1" > 1 </option> -->
                                <option :value="cashAccount" v-for="(cashAccount, cashAccountIndex) in cashAccountList" :key="cashAccountIndex">
                                    {{ cashAccount.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Type
                            </label>
                            <select name="" id="" v-model="action"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Amount
                            </label>
                            <input type="number" placeholder="Amount" v-model="amount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Description
                            </label>
                            <input type="text" placeholder="Description" v-model="description"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                    </div>
                    <div class="flex justify-center px-12 mb-6">
                        <button data-te-modal-dismiss type="button"
                        class="add-btn focus:outline-none focus:ring-0 " @click="createBtnClicked">
                            Add New
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                            Edit
                        </h5>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-12 py-4" data-te-modal-body-ref v-if="editTransaction">
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Parent Account
                            </label>
                            <select name="" id="" v-model="selectedEditSubAccount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0" @change="editSubAccountSelectChanged">
                                <option :value="subAccount" v-for="(subAccount, subAccountIndex) in subAccountList" :key="subAccountIndex">
                                    {{ subAccount.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Target Account
                            </label>
                            <select name="" id="" v-model="selectedEditAccount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="account" v-for="(account, accountIndex) in accountList" :key="accountIndex">
                                    {{ account.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Cash Account
                            </label>
                            <select name="" id="" v-model="selectedEditCashAccount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <!-- <option value="1" > 1 </option> -->
                                <option :value="cashAccount" v-for="(cashAccount, cashAccountIndex) in cashAccountList" :key="cashAccountIndex">
                                    {{ cashAccount.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Type
                            </label>
                            <select name="" id="" v-model="editTransactionAction"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :selected="editTransactionAction == 'debit'" value="debit" > Debit </option>
                                <option :selected="editTransactionAction == 'credit'" value="credit" > Credit </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Amount
                            </label>
                            <input type="number" placeholder="Amount" v-model="editTransactionAmount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Remark
                            </label>
                            <input type="text" placeholder="Remark" v-model="editTransactionDescription"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                    </div>
                    <div class="flex justify-center px-12 mb-6">
                        <button data-te-modal-dismiss type="button"
                        class="add-btn focus:outline-none focus:ring-0 " @click="confirmEditBtnClicked">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete Modal -->
        <div
        data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div
            data-te-modal-dialog-ref
            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5
                    class="text-xl font-medium leading-normal text-neutral-800 "
                    id="exampleModalLabel">
                    Delete ?
                    </h5>
                    <!--Close button-->
                    <button
                    type="button"
                    class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss
                    aria-label="Close">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p>
                        Are you sure ?
                    </p>
                </div>

                <!--Modal footer-->
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button
                    type="button"
                    class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                    data-te-modal-dismiss
                    >
                        Close
                    </button>
                    <button @click="confirmDeleteBtnClicked"
                    type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                    class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 "
                    >
                        Delete
                    </button>
                </div>
                </div>
            </div>
        </div>

        <!--Confirm Modal -->
        <div
            data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="confirm_modal"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div
                data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                    <div
                        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                        <!--Modal title-->
                        <h5
                        class="text-xl font-medium leading-normal text-neutral-800 "
                        id="exampleModalLabel">
                        Confirm Selected Transactions ?
                        </h5>
                        <!--Close button-->
                        <button
                        type="button"
                        class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss
                        aria-label="Close">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-6 w-6">
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        </button>
                    </div>

                    <!--Modal body-->
                    <div class="relative flex-auto p-4" data-te-modal-body-ref>
                        <p>
                            Are you sure ?
                        </p>
                    </div>

                    <!--Modal footer-->
                    <div
                        class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                        <button
                        type="button"
                        class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss
                        >
                            Close
                        </button>
                        <button @click="confirmTransactionsBtnClicked"
                        type="button" data-te-toggle="modal" data-te-target="#confirm_modal"
                        class="ml-1 inline-block rounded bg-orange-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 "
                        >
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>

</template>

<script>
    import { Modal, Ripple, Select, initTE, Input, Dropdown } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

    import { getCurrentDate } from '../../utilities/datetime-helpers';

    export default {
        data() {
            return {
                transactionList: [],

                cashAccountList: [],
                subAccountList: [],
                accountList: [],

                selectedCashAccount: null,
                selectedSubAccount: null,
                selectedAccount: null,

                action: null,
                amount: null,
                description: null,

                toBeConfirmTransactionList: [],

                deleteId: null,
                deleteIndex: null,

                selectedEditCashAccount: null,
                selectedEditSubAccount: null,
                selectedEditAccount: null,

                editId: null,
                editIndex: null,
                editTransaction: null,
                editTransactionDescription: null,
                editTransactionAmount: null,
                editTransactionAction: null,

                searchCategory: '-1',
                fromDate: null,
                toDate: null,

                per_page: 20,
                currentPage: 1,
                pageNumbers: [],
                paginationGroupsCount: 1,
                groupedPageNumbers: [],
                currentGroup: 0,
                isFirstGroup: true,
                isLastGroup: false,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getTransactionList(pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }
                let url = `/api/transactions?page=${this.currentPage}&is_confirm=${this.searchCategory}`;
                if(this.fromDate && this.toDate){
                    url = `${url}&from_date=${this.fromDate}&to_date=${this.toDate}`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.transactionList = response.data.data;
                    this.per_page = response.data.per_page;

                    this.pageNumbers = [];
                    this.lastPageNumber = response.data.last_page;

                    for(let i=1; i<=response.data.last_page; i++){
                        this.pageNumbers.push(i);
                    }

                    if(this.pageNumbers.length > 10){
                        this.groupedPageNumbers = [];
                        this.paginationGroupsCount = this.pageNumbers.length % 10;
                        for(let i=0; i<this.pageNumbers.length; i+=10){
                            let chunk = this.pageNumbers.slice(i, i+10);
                            this.groupedPageNumbers.push(chunk);
                        }

                        let lastGroupIndex = this.groupedPageNumbers.length - 1;
                        this.isFirstGroup = (this.currentGroup === 0);
                        this.isLastGroup = (lastGroupIndex === this.currentGroup);
                    }
                }
            },

            searchCategorySelectChanged(){
                this.getTransactionList(null);
            },

            async getCashAccountList(){
                let url = `/api/get_cash_account`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.cashAccountList = response.data;
                }
            },

            async getSubAccountList(){
                let url = `/api/sub_accounts`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.subAccountList = response.data;
                }
            },

            subAccountSelectChanged(){
                this.getAccountList(this.selectedSubAccount.id);
            },

            async editSubAccountSelectChanged(){
                let url = `/api/account_by_sub_account/${this.selectedEditSubAccount.id}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.accountList = response.data;
                }
            },

            async getAccountList(subAccountId){
                let url = `/api/account_by_sub_account/${subAccountId}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.accountList = response.data;
                }
            },

            alertValidationMessage(field){
                alert(`You forgot to provide ${field}, please try again`);
            },

            async createBtnClicked(){
                if(!this.description || !this.selectedAccount || !this.amount || !this.selectedCashAccount || !this.action){
                    if(!this.description){
                        this.alertValidationMessage("description");
                    }
                    if(!this.selectedAccount){
                        this.alertValidationMessage("target account");
                    }
                    if(!this.amount){
                        this.alertValidationMessage("value");
                    }
                    if(!this.selectedCashAccount){
                        this.alertValidationMessage("cash account");
                    }
                    if(!this.action){
                        this.alertValidationMessage("action type");
                    }

                    return 1;
                }

                let formData = new FormData();
                formData.append('description', this.description);
                formData.append('account_id', this.selectedAccount.id);
                formData.append('value', this.amount);
                formData.append('cash_account_id', this.selectedCashAccount.id);
                formData.append('action', this.action);

                let url = `/api/transactions`;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    window.location.reload();
                }
            },

            toggleTransactionInConfirmList(transactionId, transactionIndex){
                let index = this.toBeConfirmTransactionList.findIndex(confirmTransactionId => confirmTransactionId == transactionId);
                if(index > -1){
                    this.toBeConfirmTransactionList.splice(index, 1);
                }
                else{
                    this.toBeConfirmTransactionList.push(transactionId);
                }
            },

            async confirmTransactionsBtnClicked(){
                let formData = new FormData();
                this.toBeConfirmTransactionList.forEach((transactionId)=>{
                    formData.append('ids[]', transactionId);
                });
                formData.append('value', 1);
                let url = `/api/transaction_confirmed`;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    window.location.reload();
                }

            },

            deleteBtnClicked(id){
                this.deleteId = id;
                let index = this.transactionList.findIndex(transaction => transaction.id == id);
                if(index != -1){
                    this.deleteIndex = index;
                }
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/transactions/${this.deleteId}`;
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    this.transactionList.splice(this.deleteIndex, 1);
                }
            },

            async editBtnClicked(id){
                this.editId = id;
                let index = this.transactionList.findIndex(transaction => transaction.id == id);
                if(index != -1){
                    this.editIndex = index;
                }
                let url = `/api/transactions/${this.editId}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.editTransaction = response.data;
                    this.editTransactionAction = this.editTransaction.action;
                    this.editTransactionDescription = this.editTransaction.description;
                    this.editTransactionAmount = this.editTransaction.value;
                    let cashAccountIndex = this.cashAccountList.findIndex(cashAccount => cashAccount.id == this.editTransaction.cash_account_id);
                    if(cashAccountIndex != -1){
                        this.selectedEditCashAccount = this.cashAccountList[index];
                    }
                    let fetchAccountUrl = `/api/accounts/${this.editTransaction.account_id}`;
                    let accountDetailResponse = await getApiData({url: fetchAccountUrl, token: this.getToken()});
                    if(accountDetailResponse.data){
                        let subAccountIndex = this.subAccountList.findIndex(subAccount => subAccount.id == accountDetailResponse.data.sub_account_id);
                        if(subAccountIndex != -1){
                            this.selectedEditSubAccount = this.subAccountList[subAccountIndex];
                        }

                        let fetchEditAccountsUrl = `/api/account_by_sub_account/${this.selectedEditSubAccount.id}`;
                        let fetchedEditAccountsResponse = await getApiData({url: fetchEditAccountsUrl, token: this.getToken()});
                        if(fetchedEditAccountsResponse.data){
                            this.accountList = fetchedEditAccountsResponse.data;
                            let accountIndex = this.accountList.findIndex(account => account.id == this.editTransaction.account_id);
                            if(accountIndex != -1){
                                this.selectedEditAccount = this.accountList[index];
                            }
                        }
                    }
                }
            },

            async confirmEditBtnClicked(){
                let formData = new FormData();
                formData.append('description', this.editTransactionDescription);
                formData.append('account_id', this.selectedEditAccount.id);
                formData.append('value', this.editTransactionAmount);
                formData.append('cash_account_id', this.selectedEditCashAccount.id);
                formData.append('action', this.editTransactionAction);
                formData.append('credit_ledger_id', this.editTransaction.credit_ledger_id);
                formData.append('debit_ledger_id', this.editTransaction.debit_ledger_id);
                formData.append('id', this.editTransaction.id);
                let url = `/api/transactions`;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    window.location.reload();
                }
            },

            searchBtnClicked(){
                this.getTransactionList(null);
            },

            clearSearchBtnClicked(){
                this.searchCategory = '-1';
                this.fromDate = null;
                this.toDate = null;
                this.getTransactionList(null);
            },

            pageBtnClicked(pageNumber){
                this.currentPage = pageNumber;
                this.getTransactionList(this.currentPage);
            },

            nextPaginationGroupBtnClicked(){
                this.currentGroup += 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getTransactionList(this.currentPage);
            },

            previousPaginationGroupBtnClicked(){
                this.currentGroup -= 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getTransactionList(this.currentPage);
            },

            firstPaginationGroupBtnClicked(){
                this.currentGroup = 0;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getTransactionList(this.currentPage);
            },

            lastPaginationGroupBtnClicked(){
                this.currentGroup = this.paginationGroupsCount - 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getTransactionList(this.currentPage);
            }

        },

        created(){
            this.getTransactionList();
            this.getCashAccountList();
            this.getSubAccountList();
        },

        mounted()
        {
            initTE({ Modal,Select, Ripple, Dropdown });
        }
    }
</script>
