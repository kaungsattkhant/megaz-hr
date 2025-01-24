<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Purchase Order Invoices
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <notifications position="top center" />
            <div class="flex pr-0 gap-x-4">
                <button type="button" hidden disabled
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#check_modal">
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
                                    Item(s)
                                </th>
                                <th scope="col" class="">
                                    Invoice Number
                                </th>
                                <th scope="col" class="">
                                    Supplier
                                </th>
                                <th scope="col" class="">
                                    Qty ( UOM )
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class=" font-medium ">
                                        {{ (index + 1) }}
                                    </td>
                                    <td class="whitespace-nowrap" @click="toggleItems(index)">
                                        {{ item.item_names }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.invoice_no }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.supplier_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.total_invoice_quantity"> {{ item.total_invoice_quantity.toLocaleString() }} </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.total_invoice_amount"> {{ item.total_invoice_amount.toLocaleString() }} </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div v-if="item.arrival_items.length <= 1" >
                                            <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3">
                                                <i class="fal fa-pen"></i>
                                            </button>
                                            <button data-te-toggle="modal" data-te-target="#check_modal" class="pr-3"
                                            @click="checkBtnClicked(item.arrival_items[0], index)">
                                                <i class="fal fa-check"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="item.showDatails" v-for="(arrival, arrivalIndex) in item.arrival_items" :key="arrivalIndex">
                                    <td> &nbsp; </td>
                                    <td class="whitespace-nowrap"> {{ arrival.item_name }} </td>
                                    <td class="whitespace-nowrap"> {{ item.invoice_no }} </td>
                                    <td class="whitespace-nowrap"> {{ item.supplier_name }} </td>
                                    <td class="whitespace-nowrap"> {{ arrival.quantity.toLocaleString() }} </td>
                                    <td class="whitespace-nowrap"> {{ arrival.amount.toLocaleString() }} </td>
                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3">
                                                <i class="fal fa-pen"></i>
                                        </button>
                                        <button data-te-toggle="modal" data-te-target="#check_modal" class="pr-3"
                                        @click="checkBtnClicked(arrival, index)">
                                            <i class="fal fa-check"></i>
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

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="check_modal" tabindex="-1" aria-labelledby="check_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="check_modalLabel">
                        Confirm Invoice
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss id="close_create_modal"
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-4 ">
                        <label for="total-amount" class="text-sm">Invoice Amount: {{ totalInvoiceAmount.toLocaleString() }}</label>
                    </div>
                    <div class="mb-4 ">
                        <label for="amount" class="text-sm">Amount</label>
                        <input type="number" id="amount" placeholder="Invoice Amount" v-model="invoiceAmount" class="input-ui">
                    </div>
                    <div class="mb-4 ml-6">

                    </div>

                    <div class="mb-4">
                        <label for="cashbook" class="text-sm">Cash Book</label>
                        <select id="cashbook" v-model="selectedCashAccount" @change=""
                        class="text-sm border border-gray-300 input-ui w-12
                        bg-transparent rounded-lg focus:ring-0">
                            <option :value="cashAccount" v-for="(cashAccount, cashAccountIndex) in cashAccountList" :key="cashAccountIndex">
                                {{ cashAccount.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button"
                    class="add-btn focus:outline-none focus:ring-0 "
                    @click="confirmBtnClicked" data-te-modal-dismiss>
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, Select, initTE } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            items: [],
            cashAccountList: [],
            selectedCashAccount: null,
            invoiceAmount: 0,

            totalInvoiceAmount: 0,

            currentPage: 1,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        }
    },

    methods: {
        ...mapGetters(['getToken']),

        showInput() {
            this.isShowInput = true;
        },

        async getCashAccounts(){
            let response = await getApiData({url: `/api/get_cash_account`, token: this.getToken()});
            if(response.data){
                this.cashAccountList = response.data;
            }
        },

        async getItems(page) {
            let url = `/api/invoices`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.items = response.data;
                this.items.map(item => ({ ...item, showDatails: false}));

                // this.lastPage = response.data.last_page;
                // this.currentPage = page;
                // this.perPage = response.data.per_page;
            }
        },

        toggleItems(index) {
            if(this.items[index].arrival_items.length > 1){
                this.items[index].showDatails = !this.items[index].showDatails;
            }
        },

        checkBtnClicked(arrivalItem, index){
            console.log(arrivalItem);
            this.totalInvoiceAmount = this.items[index].total_invoice_amount;
            this.selectedCashAccount = null;
        },

        async confirmBtnClicked(){

        },

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });
        },
    },

    created() {
        this.getItems();
        this.getCashAccounts();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
