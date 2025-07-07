<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class="page-title">
                    Creditor
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search">
                        <i class="fal fa-search"></i>
                    </label>
                    <button hidden class="add-btn mt-0.5" data-te-toggle="modal" data-te-target="#create_modal">
                        <i class="fal fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class=" box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Supplier Name
                                </th>
                                <th scope="col" class="">
                                    Total Credit
                                </th>
                                <!-- <th scope="col" class="">
                                    Total Debit
                                </th> -->
                                <th scope="col" class="">
                                    Outstanding Debt
                                </th>
                                <th scope="col" class="">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(creditor, index) in creditorList" :key="index">
                                <tr class="">
                                    <td class="  font-medium ">
                                        {{ ++index }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ creditor.supplier_name }}
                                        <a :href="`/creditor/suppliers/${creditor.supplier_id}/transactions`" class="text-blue-600 hover:underline" > Detail </a>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ (creditor.credit_amount).toLocaleString() }}
                                    </td>

                                    <!-- <td class="  ">
                                        {{ (creditor.debit_amount).toLocaleString() }}
                                    </td> -->

                                    <td class="  ">
                                        {{ (creditor.total_credit_amount).toLocaleString() }}
                                    </td>

                                    <td class="  ">
                                        <button class="add-btn mt-0.5" data-te-toggle="modal" data-te-target="#create_modal"
                                        @click="payCreditBtnClicked(creditor)">
                                            <i class="fal fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
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
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Pay Credit
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
                            <label for="" class="label-form mb-3">
                                Credit Amount
                            </label>
                            <input type="number" v-model="payAmount" placeholder="Credit Amount"  class="input-ui">
                        </div>

                        <div class="mb-4">
                            <div>
                                <label class="label-form mb-3">Cash Account</label>
                                <multiselect v-model="selectedCashAccount" :options="cashAccountList" :close-on-select="true"
                                :clear-on-select="false" :preserve-search="true" placeholder="Select Cash Account" label="name"
                                track-by="id" :preselect-first="true"></multiselect>
                            </div>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="confirmPayCreditBtnClicked"
                            class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";

    import { mapGetters } from 'vuex';
    import { getApiData, postApiData } from '../../utilities/ajax-helpers';
    import { convertToFriendlyDate } from '../../utilities/datetime-helpers';
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect
        },
        data() {
            return {
                creditorList: [],

                selectedCreditor: null,

                cashAccountList: [],
                selectedCashAccount: null,
                payAmount: null,

                per_page: 20,
                pageNumbers: [],
                currentPage: 1,
                paginationGroupsCount: 1,
                per_group: 10,
                groupedPageNumbers: [],
                currentGroup: 0,
                isFirstGroup: true,
                isLastGroup: false,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            alertValiationMessage(field) {
                this.$notify({
                    title: `Input validation`,
                    text: `You forgot to provide ${field}, please try again`,
                    type: "warn"
                });
            },

            async getCashAccountList() {
                let url = `/api/get_cash_account`;
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.cashAccountList = response.data;
                }
            },

            async getCreditorList(pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }

                let url = `/api/creditors?page=${this.currentPage}&per_page=${this.per_page}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.creditorList = response.data;
                    this.creditorList.forEach((ap)=>{
                        ap.credit_amount = parseFloat(ap.credit_amount);
                        ap.debit_amount = parseFloat(ap.debit_amount);
                        ap.total_credit_amount = parseFloat(ap.total_credit_amount);
                    });
                }
            },

            payCreditBtnClicked(creditor){
                this.selectedCreditor = creditor;
                this.payAmount = this.selectedCreditor.total_credit_amount;
            },

            async confirmPayCreditBtnClicked(){
                if(!this.payAmount){
                    this.alertValiationMessage(`credit pay amount`);
                    return 1;
                }
                if(!this.selectedCashAccount){
                    this.alertValiationMessage(`cash account`);
                    return 1;
                }
                let formData = new FormData();
                formData.append('supplier_id', this.selectedCreditor.supplier_id);
                formData.append('value', this.payAmount);
                formData.append('cash_account_id', this.selectedCashAccount.id);
                formData.append('creditor_account_id', this.selectedCreditor.creditor_account_id);
                let url = `/api/create_creditor_transaction`;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    this.$notify({
                        text: `Credit transaction added successfully`,
                        type: "info"
                    });

                    this.getCreditorList(this.currentPage);
                }
                else{
                    this.$notify({
                        text: `Credit transaction added failed`,
                        type: "error"
                    });
                }
            }
        },

        created(){
            this.getCreditorList();
            this.getCashAccountList();
        },

        mounted() {
            initTE({Modal, Ripple, Input, Select, Dropdown});
        },
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
