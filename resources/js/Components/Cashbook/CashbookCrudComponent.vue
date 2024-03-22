<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">

                <i class="fal fa-search"></i>
            </label>
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
            <button type="button" class="mt-4 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
            data-te-toggle="modal" data-te-target="#confirm_modal">
                Close Cash Book
            </button>
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                Code
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Title
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Amount
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Type
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Remark
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Balance
                            </th>
                            <!-- <th scope="col" class="px-6 py-4">

                            </th> -->
                        </tr>
                    </thead>
                    <tbody>

                        <div class="contents">
                            <tr class="bg-gray rounded-lg overflow-hidden shadow-sm mb-10">
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> {{ (openingBalance).toLocaleString() }} </td>
                                <!-- <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td> -->
                            </tr>
                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>
                        <!-- looping start -->
                        <div class="contents" v-for="(cashBook, cashBookIndex) in cashBookList" :key="cashBookIndex">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ cashBook.id }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ cashBook.title }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ (cashBook.amount).toLocaleString() }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ cashBook.type }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ cashBook.description }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ (cashBook.amount).toLocaleString() }}
                                </td>
                                <!-- <td class="whitespace-nowrap px-6 py-4">
                                    <button
                                    data-te-toggle="modal" data-te-target="#editModal" id="edit-btn" class="pr-3">
                                    <i class="fal fa-pen"></i>
                                    </button>
                                    <button
                                    data-te-toggle="modal" data-te-target="#deleteModal" id="edit-btn" class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td> -->
                            </tr>
                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>

                            <!-- looping end -->

                        <div class="contents">
                            <tr class="bg-gray rounded-lg overflow-hidden shadow-sm mb-10">
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td>
                                <td class="whitespace-nowrap px-6 py-4 "> {{ (remainingBalance).toLocaleString() }} </td>
                                <!-- <td class="whitespace-nowrap px-6 py-4 "> &nbsp; </td> -->
                            </tr>
                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>
                    </tbody>
                </table>
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
                            Add New Cashbook
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
                                Remark
                            </label>
                            <textarea v-model="description" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                 name="" id="" cols="30" rows="10"></textarea>

                        </div>

                    </div>
                    <div class="flex justify-center px-12 mb-6">
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
                    <!-- <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Parent Account
                            </label>
                            <select name="" id=""
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="1" > 1 </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Target Account
                            </label>
                            <select name="" id=""
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="1" > 1 </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Cash Account
                            </label>
                            <select name="" id=""
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="1" > 1 </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Type
                            </label>
                            <select name="" id=""
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="1" > 1 </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Amount
                            </label>
                            <input type="text" placeholder="Area Name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Remark
                            </label>
                            <input type="text" placeholder="Remark"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                    </div> -->
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

            async getCashbookList(cashAccountId){
                let url = `/api/cash_books?cash_account_id=${cashAccountId}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.openingBalance = response.data.opening_balance;
                    this.remainingBalance = response.data.remaining_balance;
                    this.cashBookList = response.data.cashbook_list;
                }
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
            this.getCashbookList(this.cashAccountId);
            this.getSubAccountList();
        },

        mounted()
        {
            // alert(this.cashAccountId);
            initTE({ Modal,Select, Ripple });
        }
    }
</script>
