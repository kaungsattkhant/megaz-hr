<template>
    <notifications position="top center" />

    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Prepaid
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex">
                    <!-- <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search">

                        <i class="fal fa-search"></i>
                    </label> -->
                    <input type="month" class="input-ui  mr-2 h-8" v-model="selectedMonth" @change="monthChange()">
                </div>
                <div class="flex justify-end flex-col">

                    <button type="button" v-show="feature.includes('prepaid.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
                        Add New
                    </button>
                </div>
            </div>
        </div>


        <!-- <p class="test">
            test
        </p>
        <br>
        <hr>
        <button @click="testingSomething()">
            click
        </button> -->
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Title
                                </th>
                                <th scope="col" class="">
                                    From
                                </th>
                                <th scope="col" class="">
                                    To
                                </th>
                                <th scope="col" class="">
                                    Duration
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">
                                    Prepaid Amount
                                </th>
                                <th scope="col" class="">
                                    cost
                                </th>
                                <th scope="col" class="">
                                    Opening
                                </th>
                                <th scope="col" class="">
                                    Monthly Cost
                                </th>
                                <th scope="col" class="">
                                    Closing
                                </th>
                                <th scope="col" class="">
                                    Payment
                                </th>
                                <th scope="col" class="" v-show="feature.includes('prepaid-payment.create')">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(prepaid, index) in prepaidList" :key="index">
                                <tr class="">
                                    <td class=" align-middle">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class=" align-middle">
                                        {{ prepaid.prepaid.title }}
                                    </td>
                                    <td class=" align-middle">
                                        {{ prepaid.prepaid.from_date }}
                                    </td>
                                    <td class=" align-middle">
                                        {{ prepaid.prepaid.to_date }}
                                    </td>
                                    <td class=" align-middle">
                                        ....
                                    </td>
                                    <td class=" align-middle">
                                        {{ prepaid.prepaid.total_amount }}
                                    </td>
                                    <td class=" align-middle">
                                        {{ prepaid.prepaid_amount }}
                                    </td>
                                    <td class=" align-middle">
                                        {{ prepaid.cost }}
                                    </td>
                                    <td class=" align-middle">
                                        {{ prepaid.opening_balance }}
                                    </td>
                                    <td class=" align-middle">
                                        {{ prepaid.monthly_cost }}
                                    </td>
                                    <td class=" align-middle">
                                        {{ prepaid.closing_balance }}
                                    </td>
                                    <td class="align-middle">
                                        {{ prepaid.prepaid.payment || 0 }}
                                    </td>
                                    <td class=" align-middle" v-show="feature.includes('prepaid-payment.create')">
                                        <button
                                        data-te-toggle="modal" data-te-target="#create_payment_modal" @click="btnClickedPaymentModal(prepaid)">
                                            <i class="fal fa-plus" ></i>
                                        </button>

                                    </td>

                                </tr>

                            </div>
                        </tbody>
                    </table>
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1"
                                @click="getPrepaidList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage"
                                @click="getPrepaidList(currentPage + 1)"> »</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- prepaid Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                        <div v-show="step == '1'">
                            <form @submit.prevent="btnClickedCreatePrepaid()">
                                <div class="relative flex justify-between py-2 px-6 border-b">
                                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                        id="create_modalLabel">
                                        Create Prepaid
                                    </h5>
                                    <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                        data-te-modal-dismiss aria-label="Close" id="close">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="relative px-12 py-4 border-b" data-te-modal-body-ref>
                                    <div class="mb-4">
                                        <label for="" class="label-form mb-3">
                                            Title
                                        </label>
                                        <input type="text" placeholder="Title" v-model="title" class="input-ui">
                                    </div>
                                    <div class="mb-4 relative">
                                        <label class="label-form mb-3">Account Name</label>
                                        <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                            v-model="selectedAccount">
                                            <option class="text-sm" :value="acc" v-for="(acc,index) in accountList" :key="index">
                                                {{ acc.name }}
                                            </option>
                                        </select>
                                        <button type="button" class="text-xs absolute -right-6 top-1/2" @click="step = 2">
                                            <i class="fal fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="mb-4">
                                        <label class="label-form block mb-3">From</label>
                                        <label for="start_date" class="relative">
                                            <input type="date" placeholder="From" id="start_date" v-model="start_date" class="input-ui">
                                        </label>
                                    </div>
                                    <div class="mb-4">
                                        <label class="label-form block mb-3">To</label>
                                        <label for="end_date" class="relative">
                                            <input type="date" placeholder="To" id="end_date" v-model="end_date" class="input-ui">
                                        </label>

                                    </div>
                                    <div class="mb-4">
                                        <label class="label-form mb-3">Total</label>
                                        <input type="Number" placeholder="Total" v-model="total" class="input-ui">
                                    </div>
                                    <div class="mb-4">
                                        <label class="label-form mb-3">Monthly Cost</label>
                                        <input type="Number" placeholder="Monthly Cost" v-model="monthly_cost" class="input-ui">
                                    </div>
                                    <div class="mb-4">
                                        <label class="label-form mb-3">Prepaid Amount</label>
                                        <input type="Number" placeholder="Amount" v-model="prepaid_amount" class="input-ui">
                                    </div>
                                    <div class="mb-4">
                                        <label class="label-form mb-3">Cashbook</label>
                                        <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                            v-model="selected_cashbook">
                                            <option class="text-sm" :value="cashbook" v-for="(cashbook,index) in cashbookList" :key="index">
                                                {{ cashbook.name }}
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                        data-te-modal-dismiss aria-label="Close">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="add-btn focus:outline-none focus:ring-0 ">
                                        Create
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div v-show="step == '2'">
                            <div class="relative flex justify-between py-2 px-6 border-b">
                                <button @click="[step = 1,selected_second_account = null , name = null]">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter">
                                    Create Account
                                </h5>
                                <div>

                                </div>
                            </div>
                            <div class="relative px-12 py-4 border-b" data-te-modal-body-ref>
                                <div class="mb-4">
                                    <label for="" class="label-form mb-3">
                                        Name
                                    </label>
                                    <input type="text" placeholder="Name" v-model="name" class="input-ui">
                                </div>
                                <div class="mb-4 relative">
                                    <label class="label-form mb-3">Account</label>
                                    <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                        v-model="selected_second_account">
                                        <option class="text-sm" :value="acc" v-for="(acc,index) in secondAccountList" :key="index">
                                            {{ acc.name }}
                                        </option>
                                    </select>
                                </div>

                            </div>
                            <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                                <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                    data-te-modal-dismiss aria-label="Close">
                                    Cancel
                                </button>
                                <button type="button" @click="btnClickedCreateAccount()"
                                    class="add-btn focus:outline-none focus:ring-0 ">
                                    Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Payment Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="create_payment_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                        <div>
                            <div class="relative flex justify-between py-2 px-6 border-b">
                                <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                    id="create_modalLabel">
                                    Create Payment
                                </h5>
                                <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                    data-te-modal-dismiss aria-label="Close" id="close_payment">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                                <div class="mb-4">
                                    <label for="" class="label-form mb-3">
                                        Payment
                                    </label>
                                    <input type="number" placeholder="Payment" v-model="payment_amount" class="input-ui">
                                </div>
                                <div class="mb-4">
                                    <label class="label-form mb-3">Cashbook</label>
                                    <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                        v-model="selectedPaymentCashbook">
                                        <option class="text-sm" :value="cashbook" v-for="(cashbook,index) in cashbookList" :key="index">
                                            {{ cashbook.name }}
                                        </option>
                                    </select>
                                </div>

                            </div>
                            <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                                <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                    data-te-modal-dismiss aria-label="Close">
                                    Cancel
                                </button>
                                <button type="button" @click="btnClickedCreatePayment()"
                                    class="add-btn focus:outline-none focus:ring-0 ">
                                    Create
                                </button>
                            </div>
                        </div>
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
    import Multiselect from 'vue-multiselect';
    import { getCurrentDate } from '../../utilities/datetime-helpers';

    export default {
        data() {
            return {


                prepaidList:[],
                cashbookList:[],
                accountList:[],
                secondAccountList:[],

                title:null,
                selectedAccount:null,
                start_date:null,
                end_date:null,
                total:null,
                monthly_cost:null,
                prepaid_amount:null,
                selected_cashbook:null,

                payment_amount:null,
                selectedPaymentCashbook:null,
                paymentToPrepaid:null,

                name:null,
                selected_second_account:null,
                isCreatePrepaid:false,
                isCreateAccount:true,

                selectedMonth:null,
                selectedNewMonth:null,
                currentMonth:null,
                step: 1,

                currentPage: 0,
                perPage: 0,
                lastPage: 0,
                totalData:0,

                feature: this.getFeature(),
            };
        },

        methods: {
            ...mapGetters(['getToken', 'getFeature']),

            monthChange(){
                this.selectedNewMonth = this.selectedMonth.slice(5,7);
                console.log(this.selectedNewMonth)
                this.getPrepaidList(1)
            },

            async getPrepaidList(pageNumber){
                const response = await getApiData({ url: '/api/prepaid_lists?month=' + this.selectedNewMonth+'&page=' + pageNumber , token: this.getToken() });
                if(response.data){
                    this.prepaidList = response.data.data;
                    this.lastPage = response.data.last_page;
                    this.currentPage = pageNumber;
                    this.perPage = response.data.per_page;
                    this.totalData = response.data.total;
                    console.log(response)
                }
            },
            async getAccountList(){
                const response = await getApiData({ url: '/api/prepaid_account_list', token: this.getToken() });
                if(response.data){
                    this.accountList = response.data;
                }
            },
            async getSecondAccountList(){
                const response = await getApiData({ url: '/api/account_by_sub_account/11', token: this.getToken() });
                if(response.data){
                    this.secondAccountList = response.data;
                }
            },
            async getCashbookList(){
                const response = await getApiData({ url: '/api/get_cash_account', token: this.getToken() });
                if(response.data){
                    this.cashbookList = response.data;
                }
            },

            btnClickedCreateAccount(){
                this.createAccount();
            },

            async createAccount()
            {
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('account_id', this.selected_second_account.id);
                formData.append('original_account_code', this.selected_second_account.account_code);
                formData.append('sub_account_id', this.selected_second_account.sub_account_id);
                formData.append('type', 'is_second');
                let response = await postApiData({url: '/api/create_prepaid_account', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getAccountList();
                    this.step = '1';
                    this.name = null;
                    this.selected_second_account = null;
                    this.$notify({
                    title: `Input validation`,
                    text: 'Account created successfully',
                    type: "success"
                    });

                }
                else{
                    this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                    });
                }
            },



            btnClickedCreatePrepaid(){
                this.createPrepaid();
            },

            async createPrepaid()
            {
                let formData = new FormData();
                formData.append('title', this.title);
                formData.append('account_id', this.selectedAccount.id);
                formData.append('from_date', this.start_date);
                formData.append('to_date', this.end_date);
                formData.append('total_amount', this.total);
                formData.append('monthly_cost', this.monthly_cost);
                formData.append('prepaid_amount', this.prepaid_amount);
                formData.append('cash_account_id', this.selected_cashbook.id);
                let response = await postApiData({url: '/api/prepaids', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getPrepaidList(1);
                    this.closeAndClearPrepaidModal();
                    this.$notify({
                    title: `Input validation`,
                    text: 'Prepaid created successfully',
                    type: "success"
                    });
                }
                else{
                    this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
                }
            },



            btnClickedPaymentModal(payment){
                this.paymentToPrepaid = payment
            },

            btnClickedCreatePayment(){
                this.createPayment();
            },

            async createPayment()
            {
                let formData = new FormData();
                formData.append('amount', this.payment_amount);
                formData.append('cash_account_id', this.selectedPaymentCashbook.id);
                formData.append('prepaid_id', this.paymentToPrepaid.prepaid.id);
                let response = await postApiData({url: '/api/prepaid_payments', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getPrepaidList(1);
                    this.closeAndClearPaymentModal();
                    this.$notify({
                    title: `Input validation`,
                    text: 'Payment added successfully',
                    type: "success"
                    });
                }
                else{
                    this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
                }
            },


            closeAndClearPrepaidModal(){
                this.title = null;
                this.selectedAccount = null;
                this.start_date = null;
                this.end_date = null;
                this.total = null;
                this.monthly_cost = null;
                this.prepaid_amount = null,
                this.selected_cashbook = null,
                document.getElementById("close").click();
            },
            closeAndClearPaymentModal(){
                this.payment_amount = null;
                this.selectedPaymentCashbook = null;
                this.paymentToPrepaid = null;
                document.getElementById("close_payment").click();
                // 2hrs is gone ggwp hubstaff

            },
            // testingSomething(){
            //     const errorMsgHtml = `
            //     <span class="error-msg" style="color:red; margin-left: 10px;">
            //         Invalid input, please try again.
            //     </span>
            //     `;
            //     const button = document.querySelector('.test');
            //     button.insertAdjacentHTML('afterend', errorMsgHtml);
            // }

        },
        mounted()
        {
            initTE({ Modal,Select, Ripple });
        },
        created(){
            const date = new Date();
            this.selectedNewMonth = date.getMonth() + 1;
            this.getCashbookList();
            this.getSecondAccountList();
            this.getAccountList();
            this.getPrepaidList(1);
            // this.formattedDate = new Date(this.dateTimeString).toLocaleDateString();
            // this.formattedDate = this.dateTimeString.split('T')[0]


        }

    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
