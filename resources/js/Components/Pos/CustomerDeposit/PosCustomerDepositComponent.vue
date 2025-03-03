<template>
    <div>
        <div class="">
            <div class="w-full pt-9 px-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <button class="pos-add-btn">
                            Date
                        </button>
                    </div>
                    <div class="flex gap-x-3">
                        <button class="pos-add-btn !bg-[#F15181]">
                            Close
                        </button>
                        <button class="pos-add-btn">
                            <a href="/pos/customer/create" class="a-clear">
                                Add
                            </a>
                        </button>
                    </div>
                </div>
                <div>
                    <div class="bg-white px-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                <th scope="col" class="px-6 py-4">#</th>
                                <th scope="col" class="px-6 py-4">Date </th>
                                <th scope="col" class="px-6 py-4">Name</th>
                                <th scope="col" class="px-6 py-4">Amount</th>
                                <th scope="col" class="px-6 py-4">Account</th>
                                <th scope="col" class="px-6 py-4">Cashier Confirmed</th>
                                <th scope="col" class="px-6 py-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(deposit,index) in depositList" :key="index">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ deposit.date_time }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ deposit.customer.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ deposit.amount }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ deposit.account.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ deposit.is_cashier_confirmed == 1 ? 'yes' : 'no' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <button v-if="deposit.is_cashier_confirmed != 1" class="pr-1"
                                            @click="btnClickedCheckDeposit(deposit)"
                                            data-te-toggle="modal" data-te-target="#checkModal">
                                            <i class="far fa-check"></i>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>



        <!--Check Modal -->
        <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="checkModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
                items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
                min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                    rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                    <div
                        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                        <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                            Check Purchase Order
                        </h5>
                        <button type="button"
                            class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative flex-auto p-4" data-te-modal-body-ref>
                        <p>
                            Are you sure ?
                        </p>
                    </div>
                    <div
                        class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                        <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                            data-te-modal-dismiss>
                            Close
                        </button>
                        <button @click="confirmCheckDeposit" type="button" data-te-toggle="modal"
                            data-te-target="#checkModal"
                            class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                            Confirm
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
                depositList:[],

                selectedDeposit: null,

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getDepositList(){
                const response = await getApiData({ url: '/api/customer_deposits' , token: this.getToken()});
                if(response.data){
                    this.depositList = response.data;
                }
            },
            btnClickedCheckDeposit(deposit){
                this.selectedDeposit = deposit;
            },
            confirmCheckDeposit(){
                this.checkDeposit();
            },
            async checkDeposit(){
                let url = `/api/customer_deposits/${this.selectedDeposit.id}/confirm`;
                let formData = new FormData();
                // formData.append('type', 'purchase_order');
                let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.$notify({
                        text: `Customer Deposit Checked`,
                        type: 'info'
                    });
                    // setTimeout(() => {
                    //     window.location.reload();
                    // }, 200);
                    this.getDepositList();
                }
                else {
                    this.$notify({
                        text: response.message,
                        type: 'error'
                    });
                }
            }
        },
        mounted()
        {
            this.getDepositList();
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
