<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            CashBook
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <input type="date" class="h-8 mr-2 rounded-md" v-model="fromDate">
                <input type="date" class="h-8 mx-2 rounded-md" v-model="toDate">

                <button class="add-btn " @click="searchBtnClicked">Search</button>
                <button class="add-btn " @click="clearSearchBtnClicked">Clear</button>
            </div>
            <div class="flex justify-end flex-col">

                <button type="button" class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <button type="button" class="mt-4 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                data-te-toggle="modal" data-te-target="#confirm_modal">
                    Close Cash Book
                </button>
                <div class="table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    Code
                                </th>
                                <th scope="col" class="">
                                    Title
                                </th>
                                <th scope="col" class="">
                                    Debit
                                </th>
                                <th scope="col" class="">
                                    Credit
                                </th>
                                <th scope="col" class="">
                                    Type
                                </th>
                                <th scope="col" class="">
                                    Remark
                                </th>
                                <th scope="col" class="">
                                    Balance
                                </th>
                                <!-- <th scope="col" class="px-6 py-4">

                                </th> -->
                            </tr>
                        </thead>
                        <tbody>

                            <div class="contents">
                                <tr class="">
                                    <td class="whitespace-nowrap " colspan="6"> &nbsp; </td>
                                    <td class="whitespace-nowrap "> {{ (openingBalance).toLocaleString() }} </td>
                                    <!-- <td class="whitespace-nowrap "> &nbsp; </td> -->
                                </tr>
                            </div>
                            <!-- looping start -->
                            <div class="contents" v-for="(cashBook, cashBookIndex) in cashBookList" :key="cashBookIndex">
                                <tr class="">
                                    <td class="">
                                        {{ cashBook.id }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ cashBook.title }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="cashBook.action == 'debit'">
                                            {{ (cashBook.amount).toLocaleString() }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="cashBook.action == 'credit'">
                                            {{ (cashBook.amount).toLocaleString() }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ cashBook.type }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ cashBook.description }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ (cashBook.amount).toLocaleString() }}
                                    </td>
                                </tr>
                            </div>

                            <!-- looping end -->

                            <div class="contents">
                                <tr class="">
                                    <td class="whitespace-nowrap  " colspan="6"> &nbsp; </td>
                                    <td class="whitespace-nowrap  "> {{ (remainingBalance).toLocaleString() }} </td>
                                    <!-- <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td> -->
                                </tr>
                            </div>
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

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter" id="create_modalLabel">
                            Add New Cashbook
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="labelform mb-3">
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
                            <label for="" class="labelform mb-3">
                                Title
                            </label>
                            <select name="" id="" v-model="selectedAccount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="account" v-for="(account, accountIndex) in accountList" :key="accountIndex">
                                    {{ account.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="labelform mb-3">
                                Type
                            </label>
                            <select name="" id="" v-model="action"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="labelform mb-3">
                                Amount
                            </label>
                            <input type="number" placeholder="Amount" v-model="amount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="labelform mb-3">
                                Remark
                            </label>
                            <textarea v-model="description" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                 name="" id="" cols="30" rows="10"></textarea>

                        </div>

                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button data-te-modal-dismiss type="button"
                        class="add-btn focus:outline-none focus:ring-0 " @click="createBtnClicked">
                            Create
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
                            Edit Account
                        </h5>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button data-te-modal-dismiss type="button"
                        class="add-btn focus:outline-none focus:ring-0 ">
                            Create
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
                        Confirm Cashbook Close ?
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
                        <button @click="confirmCashBookCloseBtnClicked"
                        type="button" data-te-toggle="modal" data-te-target="#confirm_modal"
                        class="ml-1 inline-block rounded bg-orange-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 "
                        >
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

    

</template>

<script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

    export default {
        props: ["cashAccountId", "cashAccountName"],
        data() {
            return {
                openingBalance: 0,
                remainingBalance: 0,
                cashBookList: [],

                subAccountList: [],
                selectedSubAccount: null,
                accountList: [],
                selectedAccount: null,
                action: null,
                amount: null,
                description: null,

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

            async getSubAccountList(){
                let url = `/api/sub_accounts`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.subAccountList = response.data;
                }
            },

            subAccountSelectChanged(){
                this.getAccountList();
            },

            async getAccountList(){
                let url = `/api/account_by_sub_account/${this.selectedSubAccount.id}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.accountList = response.data;
                }
            },

            async getCashbookList(cashAccountId, pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }
                let url = `/api/cash_books?cash_account_id[]=${cashAccountId}&page=${this.currentPage}`;
                if(this.fromDate && this.toDate){
                    url = `${url}&from_date=${this.fromDate}&to_date=${this.toDate}`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.openingBalance = response.data.opening_balance;
                    this.remainingBalance = response.data.remaining_balance;
                    this.cashBookList = response.data.cashbook_list;
                    // this.cashBookList = response.data.data;
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

            searchBtnClicked(){
                this.getCashbookList(this.cashAccountId, null);
            },

            clearSearchBtnClicked(){
                this.fromDate = null;
                this.toDate = null;
                this.getCashbookList(this.cashAccountId, null);
            },

            alertValidationMessage(field){
                alert(`You forgot to provide ${field}, please try again`);
            },

            async createBtnClicked(){
                if(!this.description || !this.selectedAccount || !this.amount || !this.action){
                    if(!this.description){
                        this.alertValidationMessage("description");
                    }
                    if(!this.selectedAccount){
                        this.alertValidationMessage("target account");
                    }
                    if(!this.amount){
                        this.alertValidationMessage("value");
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
                formData.append('cash_account_id', this.cashAccountId);
                formData.append('action', this.action);
                formData.append('is_confirmed', 1);

                let url = `/api/transactions`;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    window.location.reload();
                }
            },

            async confirmCashBookCloseBtnClicked(){
                let url = `/api/close_cashbook_transaction?cash_account_id=${this.cashAccountId}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.success){
                    window.location.reload();
                }
            },
        },

        created(){
            this.getCashbookList(this.cashAccountId, null);
            this.getSubAccountList();
        },

        mounted()
        {
            initTE({ Modal,Select, Ripple });
        }
    }
</script>
