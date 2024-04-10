<template>
    <div>
        <div class="">
            <div class="w-full pt-9 px-6">
                <div class="flex justify-between mb-4">
                    <div class="flex gap-x-3">
                        <button class="pos-add-btn">
                            Date
                        </button>
                        <select name="" id="" v-model="bookType" @change="cashOrBank"
                            class="text-sm border border-gray-300 input-ui w-full bg-white rounded-lg focus:ring-0">
                            <option :value="account" v-for="account in cashAccountList"> {{ account.name }} </option>
                        </select>
                    </div>
                    <div class="flex gap-x-3">
                        <!-- <button class="pos-add-btn !bg-[#F15181]">
                            Close
                        </button> -->
                        <button class="pos-add-btn" data-te-toggle="modal" data-te-target="#create_cashbook_modal">

                            Add

                        </button>
                    </div>
                </div>

                <div>
                    <div class="bg-white px-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Id</th>
                                    <th scope="col" class="px-6 py-4">Title</th>
                                    <th scope="col" class="px-6 py-4">Type</th>
                                    <th scope="col" class="px-6 py-4">Amount</th>
                                    <th scope="col" class="px-6 py-4">Remark</th>
                                    <th scope="col" class="px-6 py-4">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">

                                    <td colspan="5" class="whitespace-nowrap px-6 py-4">

                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ (openingBalance).toLocaleString() }}
                                    </td>
                                </tr>

                                <tr class="" v-for="(cashbook,index) in cashbookList">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div v-if="cashbook.transactionable.invoice_id">
                                            {{ cashbook.transactionable.invoice_id }}
                                        </div>
                                        <div v-else>
                                            {{ cashbook.title }}
                                        </div>

                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ cashbook.action }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ (cashbook.amount).toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ cashbook.description }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ (cashbook.balance).toLocaleString() }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td colspan="5" class="whitespace-nowrap px-6 py-4">

                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ remainingBalance.toLocaleString() }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_cashbook_modal" tabindex="-1" aria-labelledby="createCashbookModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Create Cashbook
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none
                        " id="closeModal" data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-16 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Parent Account
                            </label>
                            <select name="" id="" v-model="selectedSubAccount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                @change="subAccountSelectChanged">
                                <option :value="subAccount" v-for="(subAccount, subAccountIndex) in subAccountList"
                                    :key="subAccountIndex">
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
                                <option :value="account.id" v-for="(account, accountIndex) in accountList"
                                    :key="accountIndex">
                                    {{ account.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Account Type
                            </label>
                            <select name="" id="" v-model="accType"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="account" v-for="account in cashAccountList"> {{ account.name }}
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
                            <input type="text" placeholder="Amount" v-model="amount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Remark
                            </label>
                            <textarea v-model="description"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                name="" id="" cols="30" rows="10"></textarea>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="createBtnClicked" class="pos-add-btn !px-16 focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>



    </div>

</template>
<script>
    import { Modal, Ripple, Select, Datepicker, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                cashbookList:[],
                // bankbookList:[],
                totalBookList:null,
                cashAccountList:[],
                cashbookId:null,
                bankbookId:null,
                bookType:null,

                subAccountList: [],
                selectedSubAccount: null,
                accountList: [],
                selectedAccount: null,
                accType:null,
                action: null,
                amount: null,
                description: null,
                cashAccId:null,

                openingBalance: 0,
                remainingBalance: 0,
                currentBalance: 0,

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getCashAccount(){
                const response = await getApiData({ url: '/api/get_cash_account', token: this.getToken() });
                if (response.data) {
                    let posCashAccount = response.data.find(account => account.account_code === '2-1011');
                    if (posCashAccount) {
                        // this.cashAccId = posCashAccount.id;
                        // this.getCashbookList(posCashAccount.id);
                        this.cashAccountList.push(posCashAccount);
                    }
                    let posBankAccount = response.data.find(account => account.account_code === '2-1012');
                    if (posBankAccount) {
                        // this.getCashbookList(posBankAccount.id);
                        this.cashAccountList.push(posBankAccount);
                    }

                    this.getCashbookList(this.cashAccountList);
                }
            },

            async getCashbookList(cashAccountList){
                let url = `/api/cash_books?`;
                cashAccountList.forEach((cashAccount)=>{
                    url += `cash_account_id[]=${cashAccount.id}&`;
                });
                url = url.substring(0, url.length - 1);

                const response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {

                    this.openingBalance = response.data.opening_balance;
                    this.remainingBalance = response.data.remaining_balance;
                    // this.currentBalance = this.openingBalance;
                    response.data.cashbook_list.forEach((cashBook)=>{
                        cashBook.balance = 0;
                        this.cashbookList.push(cashBook);
                    });
                    console.log(this.cashbookList);
                    this.cashbookList.forEach((cashBook)=>{
                        if(cashBook.action == 'debit'){
                            this.currentBalance += cashBook.amount;
                        }
                        if(cashBook.action == 'credit'){
                            this.currentBalance -= cashBook.amount;
                        }
                        // this.currentBalance += cashBook.amount;
                        cashBook.balance += this.currentBalance;

                    });
                }
            },
            async cashOrBank(){
                let url = `/api/cash_books?cash_account_id[]=` + this.bookType.id;
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.currentBalance = 0;
                    this.testlist = response.data
                    this.cashbookList = []
                    this.openingBalance = response.data.opening_balance;
                    this.remainingBalance = response.data.remaining_balance;
                    response.data.cashbook_list.forEach((cashBook) => {
                        cashBook.balance = 0;
                        this.cashbookList.push(cashBook);
                    });
                    this.cashbookList.forEach((cashBook)=>{
                        if(cashBook.action == 'debit'){
                            this.currentBalance += cashBook.amount;
                        }
                        if(cashBook.action == 'credit'){
                            this.currentBalance -= cashBook.amount;
                        }
                        // this.currentBalance += cashBook.amount;
                        cashBook.balance += this.currentBalance;

                    });
                }
            },

            async getSubAccountList() {
                let url = `/api/sub_accounts`;
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.subAccountList = response.data;
                }
            },

            subAccountSelectChanged() {
                this.getAccountList();
            },

            async getAccountList() {
                let url = `/api/account_by_sub_account/${this.selectedSubAccount.id}`;
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.accountList = response.data;
                }
            },
            async createBtnClicked() {
                let formData = new FormData();
                formData.append('description', this.description);
                formData.append('account_id', this.selectedAccount);
                formData.append('value', this.amount);
                formData.append('cash_account_id', this.accType.id);
                formData.append('action', this.action);
                formData.append('is_confirmed', 1);

                let url = `/api/transactions`;
                let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
                if (response.success) {
                    window.location.reload();
                }
            },

                // for close transaction
            // let url = '/api/close_cashbook_transaction?cash_account_id=298';
            //     let response = await getApiData({ url: url, token: this.getToken() });
            //     let url = '/api/close_cashbook_transaction?cash_account_id=298';
            //     let response = await getApiData({ url: url, token: this.getToken() });
            //     if(response.data){
            //         this.subAccountList = response.data;
            //     }
        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        },

        created(){
            this.getCashAccount();
            this.getSubAccountList();
            // this.getTotalBookList();
        }
    }
</script>
