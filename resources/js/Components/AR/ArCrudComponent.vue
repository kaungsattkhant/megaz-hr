<template>
    <div class="flex justify-between mb-3">
        <notifications position="top center" />

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
    <div class="box-container-table">
        <div class="overflow-x-auto">
            <div class="table-container">
                <table class="primary-table">
                    <thead>
                        <tr>
                            <th scope="col" class="">
                                #
                            </th>
                            <th scope="col" class="">
                                Name
                            </th>
                            <th scope="col" class="">
                                Receivable Amount
                            </th>

                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- looping start -->
                        <div class="contents" v-for="(ar,index) in arList" :key="index">
                            <tr class="">
                                <td class=" font-medium ">
                                    {{ index+1 }}
                                </td>
                                <td class="whitespace-nowrap">
                                    <a :href="'/account_receivable/' + ar.id + '/detail'"  class="contents">{{ ar.name }}</a>
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ ar.ar_balance }}
                                </td>
                                <td class="whitespace-nowrap">
                                    <button id="edit-btn" class="pr-3 transition duration-150 ease-in-out"
                                    @click="btnClickedPaidModal(ar)"
                                    data-te-toggle="modal" data-te-target="#paid_modal">
                                        <i class="fal fa-plus"></i>
                                    </button>
                                </td>
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
                            Add New
                        </h5>
                        <button type="button" id="close_create_modal" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="btnClickedCreateAr()">
                        <div class="relative px-12 py-4" data-te-modal-body-ref>


                            <div class="mb-4">
                                <label class="label-form mb-3">Sub Account</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedSubAccount"
                                    @change="getAccountList()" >
                                    <option class="text-sm" :value="sub" v-for="(sub,index) in subAccountList" :key="index">
                                        {{ sub.name }}
                                    </option>

                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Account</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedAccount">
                                    <option class="text-sm" :value="acc" v-for="(acc,index) in accountList" :key="index">
                                        {{ acc.name }}
                                    </option>

                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Amount
                                </label>
                                <input type="number" placeholder="Amount" v-model="amount_create"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Cashbook</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedCashbookCreate">
                                    <option class="text-sm" :value="cashbook" v-for="(cashbook,index) in cashbookList" :key="index">
                                        {{ cashbook.name }}
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div class="flex justify-center px-12 mb-6">
                            <button type="submit"
                            class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Paid Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="paid_modal" tabindex="-1" aria-labelledby="paid_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="paid_modalLabel">
                            Payment
                        </h5>
                        <button type="button" id="close_paid_modal" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="btnClickedCreatePaid()">
                        <div class="relative px-12 py-4" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Amount
                                </label>
                                <input type="number" placeholder="Amount" v-model="amount_paid"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Cashbook</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedCashbookPaid">
                                    <option class="text-sm" :value="cashbook" v-for="(cashbook,index) in cashbookList" :key="index">
                                        {{ cashbook.name }}
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div class="flex justify-center px-12 mb-6">
                            <button type="submit"
                            class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                        </div>
                    </form>
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
        data() {
            return {
                arList:[],
                subAccountList:[],
                accountList:[],
                cashbookList:[],

                arId:null,

                selectedAccount:null,
                selectedSubAccount:null,
                amount_create:null,
                amount_paid:null,
                selectedCashbookCreate:null,
                selectedCashbookPaid:null,



            };
        },

        methods: {
            ...mapGetters(['getToken']),




            async getArList(){
                const response = await getApiData({ url: '/api/account_receivable_lists', token: this.getToken() });
                if(response.data){
                    this.arList = response.data;
                }
            },
            async getSubAccountList(){
                const response = await getApiData({ url: '/api/ar_sub_accounts', token: this.getToken() });
                if(response.data){
                    this.subAccountList = response.data;
                }
            },
            async getAccountList(){
                const response = await getApiData({ url: '/api/account_by_sub_account/' + this.selectedSubAccount.id, token: this.getToken() });
                if(response.data){
                    this.accountList = response.data;
                }
            },
            async getCashbookList(){
                const response = await getApiData({ url: '/api/get_cash_account', token: this.getToken() });
                if(response.data){
                    this.cashbookList = response.data;
                }
            },


            btnClickedCreateAr(){
                this.createAr();
            },

            async createAr()
            {
                let formData = new FormData();
                formData.append('account_id', this.selectedAccount.id);
                formData.append('amount', this.amount_create);
                formData.append('cash_account_id', this.selectedCashbookCreate.id);
                let response = await postApiData({url: '/api/account_receivables', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getArList();
                    this.closeAndClearCreateModal();
                }
                else{
                    this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                    });
                }
            },


            btnClickedPaidModal(id){
                this.arId = id;
            },
            btnClickedCreatePaid(){
                this.createPaid();
            },
            async createPaid(){
                let formData = new FormData();
                formData.append('account_id', this.arId.id);
                formData.append('amount', this.amount_paid);
                formData.append('cash_account_id', this.selectedCashbookPaid.id);
                let response = await postApiData({url: '/api/paid_account_receivables', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getArList();
                    this.closeAndClearPaidModal();
                }
                else{
                    this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                    });
                }
            },


            closeAndClearCreateModal(){
                document.getElementById("close_create_modal").click();
                this.selectedSubAccount = null;
                this.selectedAccount = null;
                this.amount_create = null;
                this.selectedCashbookCreate = null;
            },
            closeAndClearPaidModal(){
                document.getElementById("close_paid_modal").click();
                this.amount_paid = null;
                this.selectedCashbookPaid = null;
            },

        },
        mounted()
        {
            initTE({ Modal,Select, Ripple });
        },
        created(){
            this.getArList();
            this.getSubAccountList();
            this.getCashbookList();
        }
    }
</script>
