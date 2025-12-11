<template>
    
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Advances Detail
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex">
                    <!-- <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search">

                        <i class="fal fa-search"></i>
                    </label> -->
                    <!-- <input type="month" class="input-ui  mr-2 h-8" v-model="selectedMonth" @change="monthChange()"> -->
                </div>
                <div class="flex justify-end flex-col">

                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#advance_payment_modal">
                        Add New
                    </button>
                </div>
            </div>
        </div>


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
                                    Date
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <!-- <th scope="col" class="">
                                    Type
                                </th> -->
                                <th scope="col" class="">
                                    Balance
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="" v-for="(adv,index) in advance_detail" :key="index">
                                <td class=" ">
                                    {{ index+1 }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ adv.payment_month}}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ adv.paid_amount}}
                                </td>
                                <!-- <td class="whitespace-nowrap">
                                    -
                                </td> -->
                                <td class="whitespace-nowrap">
                                    {{ adv.remaining_balance}}
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="advance_payment_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Add Monthly Amount
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close" id="close_monthly_amount_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Month
                            </label>
                            <input type="date" class="input-ui  mr-2 h-8" v-model="selected_month"> 
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Monthly Amount
                            </label>
                            <input type="number" placeholder="Monthly Amount" v-model="advance_monthly_amount" class="input-ui">
                        </div>
                        

                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnClickedAddAdvancePayment()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
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
    import { getCurrentDate } from "../../utilities/datetime-helpers";

    export default {
        props: ['advanceId'],
        data() {
            return {
                
                currentdate : getCurrentDate(),

                advance_detail: null,

                selected_month: null,
                advance_monthly_amount: null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            // formatDate(dateTime) {
            //     const date = new Date(dateTime);
            //     const options = { month: 'short', day: 'numeric' };
            //     return date.toLocaleDateString('en-US', options);
            // },

            // monthChange(){
            //     let selectedNewMonth = this.selectedMonth.slice(5,7);
            //     this.getStaffAdvance(selectedNewMonth);
            // },
            async getAdvanceDetail(selectedmonth){
                const response = await getApiData({ url: '/api/advances/advance_payment_detail/'+this.advanceId , token: this.getToken() });
                if(response.message){
                    this.advance_detail = response.message;
                    // this.staff_advance = response.data.staff_advances;
                    // this.staff_balance = response.data.staff_balance;
                }
            },

            btnClickedAddMonthlyAmount(advance){
                this.advance_id = advance;
                this.advance_monthly_amount = null;
            },
            btnClickedAddAdvancePayment(){
                if(!this.advance_monthly_amount){
                    this.alertValidationMessage(`Amount`);
                    return 1;
                }
                else{
                    this.addAdvancePayment();
                }
            },
            async addAdvancePayment(){
                let formData = new FormData();
                formData.append('paid_amount', this.advance_monthly_amount);
                formData.append('advance_id', this.advanceId);
                formData.append('payment_month', this.selected_month);
                let response = await postApiData({url: '/api/advances/create_advance_payments', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getAdvanceDetail(1);
                    document.getElementById('close_monthly_amount_modal').click();
                }
                else{
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            } ,
        },
        mounted()
        {
            // const date = new Date();
            // this.currentMonth = date.getMonth() + 1;
            this.getAdvanceDetail();
            initTE({ Modal,Select, Ripple });
        },
        created(){



        }

    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
