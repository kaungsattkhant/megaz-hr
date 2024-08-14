<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Prepaid
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <!-- <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label> -->
                <input type="date" class="input-ui  mr-2 h-8" v-model="selectedMonth" @change="monthChange()">
            </div>
            <div class="flex justify-end flex-col">

                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button>
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
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(prepaid, index) in prepaidList" :key="index">
                                <tr class="">
                                    <td class=" align-middle" rowspan="12">
                                        test
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
                    <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                        <div v-show="isCreatePrepaid">
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
                                    <input type="text" placeholder="Particular" v-model="title" class="input-ui">
                                </div>
                                <div class="mb-4 relative">
                                    <label class="label-form mb-3">Account Name</label>
                                    <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                        v-model="selected_accouunt">
                                        <option class="text-sm" :value="acc" v-for="(acc,index) in acc_list" :key="index">
                                            {{ acc.name }}
                                        </option>
                                    </select>

                                    <button class="text-xs absolute -right-6 top-1/2">
                                        <i class="fal fa-plus"></i>
                                    </button>
                                </div>
                                <div class="mb-4">
                                    <label class="label-form mb-3">From</label>
                                    <label for="start_date" class="relative">
                                        <input type="date" placeholder="From" id="start_date" v-model="selected_start_date" class="input-ui">
                                    </label>
                                </div>
                                <div class="mb-4">
                                    <label class="label-form mb-3">To</label>
                                    <label for="end_date" class="relative">
                                        <input type="date" placeholder="To" id="end_date" v-model="selected_end_date" class="input-ui">
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
                                <button type="button" @click="btnClickedCreatePrepaid()"
                                    class="add-btn focus:outline-none focus:ring-0 ">
                                    Create
                                </button>
                            </div>
                        </div>
                        <div v-show="isCreateAccount">
                            <div class="relative flex justify-between py-2 px-6 border-b">
                                <button>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter">
                                    Create Customer
                                </h5>
                                <div>
                                    
                                </div>
                            </div>
                            <div class="relative px-12 py-4 border-b" data-te-modal-body-ref>
                                <div class="mb-4">
                                    <label for="" class="label-form mb-3">
                                        Title
                                    </label>
                                    <input type="text" placeholder="Particular" v-model="title" class="input-ui">
                                </div>
                                <div class="mb-4 relative">
                                    <label class="label-form mb-3">Account Name</label>
                                    <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                        v-model="selected_accouunt">
                                        <option class="text-sm" :value="acc" v-for="(acc,index) in acc_list" :key="index">
                                            {{ acc.name }}
                                        </option>
                                    </select>

                                    <button class="text-xs absolute -right-6 top-1/2">
                                        <i class="fal fa-plus"></i>
                                    </button>
                                </div>
                               
                            </div>
                            <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                                <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                    data-te-modal-dismiss aria-label="Close">
                                    Cancel
                                </button>
                                <button type="button" @click="btnClickedCreatePrepaid()"
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
                id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
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
                                    data-te-modal-dismiss aria-label="Close" id="close">
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
                                    <input type="text" placeholder="Payment" v-model="title" class="input-ui">
                                </div>
                                <div class="mb-4">
                                    <label class="label-form mb-3">Cashbook</label>
                                    <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                        v-model="selectedAccouunt">
                                        <option class="text-sm" :value="sub" v-for="(cashbook,index) in cashbookList" :key="index">
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

                title:null,
                selected_accouunt:null,
                selected_start_date:null,
                selected_end_date:null,
                total:null,
                monthly_cost:null,
                prepaid_amount:null,
                selected_cashbook:null,

                isCreatePrepaid:false,
                isCreateAccount:true,




                // dateTimeString: '2024-08-12T14:30:00Z', 
                // formattedDate: '',
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getPrepaidList(){
                const response = await getApiData({ url: '/api/' , token: this.getToken() });
                if(response.data){
                    this.prepaidList = response.data;
                }
            },
            async getCashbookList(){
                const response = await getApiData({ url: '/api/get_cash_account', token: this.getToken() });
                if(response.data){
                    this.cashbookList = response.data;
                }
            },
            
            btnClickedCreateJournal(){
                this.createJournal();
            },

            async createJournal()
            {
                let formData = new FormData();
                formData.append('particular', this.particular);
                formData.append('amount', this.amount);
                formData.append('credit_account_id', this.selectedCreditAcc.id);
                formData.append('debit_account_id', this.selectedDebitAcc.id);
                let response = await postApiData({url: '/api/journals', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getJournalList(this.currentMonth);
                    this.closeAndClearModal();
                    console.log('journal created')
                }
                else{

                }
            },

            closeAndClearModal(){
                this.particular = null;
                this.amount = null;
                this.selectedCreditAcc = null;
                this.selectedCreditSubAcc = null;
                this.selectedDebitAcc = null;
                this.selectedDebitSubAcc = null;
                document.getElementById("close").click();
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
            this.getCashbookList();
            // this.formattedDate = new Date(this.dateTimeString).toLocaleDateString();
            // this.formattedDate = this.dateTimeString.split('T')[0]
            

        }

    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
