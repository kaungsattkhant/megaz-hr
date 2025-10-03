<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Purchase Order Invoices
                </p>
            </div>
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
                                    Invoice Number
                                </th>
                                <th scope="col" class="">
                                    Item(s)
                                </th>
                                <th scope="col" class="">
                                    Brand
                                </th>
                                <th scope="col" class="">
                                    Supplier
                                </th>
                                <th scope="col" class="">
                                    Qty
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">
                                    Status
                                </th>
                                <th scope="col" class="">
                                    Action  
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class=" font-medium ">
                                        {{ (index + 1) }}
                                    </td>
                                    <td class="whitespace-nowrap" @click="toggleItems(index)">
                                        {{ item.invoice_no }}
                                    </td>
                                    <td class="whitespace-nowrap" @click="toggleItems(index)">
                                        {{ item.item_names }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.brands }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.supplier_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        
                                    </td>
                                    <!-- <td class="whitespace-nowrap">
                                        <span v-if="item.total_invoice_quantity"> {{ item.total_invoice_quantity.toLocaleString() }} </span>
                                    </td> -->
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.total_invoice_amount"> {{ item.total_invoice_amount.toLocaleString() }} </span>
                                    </td>
                                    <span :class="item.is_complete === 1 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800 '" class="px-2 py-1 rounded capitalize font-semibold text-sm">
                                        {{ item.is_complete === 1 ? 'Complete' : 'Not Yet' }}
                                    </span>
                                    <td class="whitespace-nowrap">
                                        <div class="flex gap-x-6 justify-center">
                                            <!-- <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3">
                                                <i class="fal fa-pen"></i>
                                            </button> -->
                                            <button :disabled="item.is_complete === 1" 
                                                :class="item.is_complete === 1 ? 'bg-gray-300 text-gray-600 cursor-not-allowed opacity-60' : 'bg-blue-500 text-white hover:bg-blue-600'"  
                                                class=" px-4 py-1.5 text-sm rounded-lg" data-te-toggle="modal" data-te-target="#check_modal" 
                                                @click="checkBtnClicked(item.arrival_items[0], index, item)" v-show="feature.includes('po-order-invoice.paid')">
                                                <i class="far fa-check-double" :class="item.is_complete === 1 ? 'text-gray-500' : ''"></i>
                                            </button>
                                            <!-- <button data-te-toggle="modal" data-te-target="#check_modal" class="pr-6"
                                            @click="checkBtnClicked(item.arrival_items[0], index, item)" v-show="feature.includes('po-order-invoice.paid')">
                                                <i class="fal fa-check"></i>
                                            </button> -->
                                            <a class="pr-2 py-1.5" :href="'/purchase_order_invoices/' + item.id + '/confirm'">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                        </div>
                                        <!-- <div v-else>
                                            <span class=" text-green-600">Complete</span>
                                        </div> -->
                                    </td>
                                </tr>
                                <tr v-if="item.showDatails" v-for="(arrival, arrivalIndex) in item.arrival_items" :key="arrivalIndex">
                                    <!-- <td> &nbsp; </td> -->
                                    <td colspan="2" class="whitespace-nowrap"> &nbsp; </td>
                                    <td class="whitespace-nowrap"> {{ arrival.item_name }} </td>
                                    <td class="whitespace-nowrap"> {{ arrival.brand_name }} </td>
                                    <td class="whitespace-nowrap"> {{ item.supplier_name }} </td>
                                    <td class="whitespace-nowrap"> 
                                        {{ arrival.uom_quantity > 0 ? arrival.uom_quantity : '' }} {{ arrival.uom_quantity > 0 ? arrival.uom_name : '' }}  
                                        {{ arrival.base_uom_quantity > 0 ? arrival.base_uom_quantity : '' }}  {{ arrival.base_uom_quantity > 0 ? arrival.base_uom_name : '' }}     
                                    </td>
                                    <td class="whitespace-nowrap"> {{ arrival.amount.toLocaleString() }} </td>
                                    <td></td>
                                    <td class="whitespace-nowrap">
                                        <!-- <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3">
                                                <i class="fal fa-pen"></i>
                                        </button>
                                        <button data-te-toggle="modal" data-te-target="#check_modal" class="pr-3"
                                        @click="checkBtnClicked(arrival, index, item)">
                                            <i class="fal fa-check"></i>
                                        </button> -->
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="items.length < 1 && !loading">
                                <td class="" colspan="9">
                                    No Data Here
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
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss id="close_check_modal"
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
                        <input type="number" id="amount" placeholder="Invoice Amount" :disabled="is_credit" v-model="invoiceAmount" @input="invoiceAmountChange()" class="input-ui">
                    </div>
                    <div class="mb-4 ml-6">

                    </div>

                    <div class="mb-4">
                        <label for="cashbook" class="text-sm">Cash Book</label>
                        <select id="cashbook" v-model="selectedCashAccount" :disabled="is_credit"
                            class="text-sm border border-gray-300 input-ui w-12
                            bg-transparent rounded-lg focus:ring-0">
                            <option :value="cashAccount" v-for="(cashAccount, cashAccountIndex) in cashAccountList" :key="cashAccountIndex">
                                {{ cashAccount.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4 ">
                        <label for="total-amount" class="text-sm">AP Amount: {{ apAmount.toLocaleString() }}</label>
                    </div>

                    <div>
                        <label for="unpaidLeave"
                            class=" focus:outline-none focus:ring-0 focus:shadow-none cursor-pointer flex items-center gap-x-3 pl-1">
                            <input type="checkbox" id="unpaidLeave" v-model="is_credit" @change="isCreditChange"
                                class=" focus:outline-none focus:ring-0 focus:shadow-none">
                            Is Credit
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button"
                    class="add-btn focus:outline-none focus:ring-0 "
                    @click="confirmBtnClicked">
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
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            items: [],
            cashAccountList: [],
            selectedCashAccount: null,
            invoiceAmount: 0,

            totalInvoiceAmount: 0,
            apAmount:0,
            selectedPoInvoice:null,
            selectedInvoice: null,
            is_credit: false,

            currentPage: 1,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            feature: this.getFeature(),
            loading: false,
        }
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

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
            this.loading = true;
            let url = `/api/invoices`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
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

        checkBtnClicked(arrivalItem, index, invoice){
            this.is_credit = false;
            console.log(arrivalItem);
            this.selectedPoInvoice = arrivalItem;
            this.selectedInvoice = invoice;
            this.invoiceAmount = invoice.total_invoice_amount;
            this.totalInvoiceAmount = this.items[index].total_invoice_amount;
            this.selectedCashAccount = null;
            this.apAmount = this.items[index].total_invoice_amount - this.invoiceAmount;
            this.selectedPoInvoice = invoice;
        },
        invoiceAmountChange(){
            this.apAmount = this.totalInvoiceAmount - this.invoiceAmount
        },
        isCreditChange(){
            if(this.is_credit){
                this.invoiceAmount = 0;
                this.selectedCashAccount = null;
            }
            else{
                this.invoiceAmount = this.selectedInvoice.total_invoice_amount;
            }
            this.invoiceAmountChange();
        },
        async confirmBtnClicked(){
            if(this.invoiceAmount < 0){
                this.alertValidationMessage('Amount');
                console.log(this.invoiceAmount)
                return 1;
            }
            if(this.invoiceAmount > 0 && !this.selectedCashAccount){
                this.alertValidationMessage('Account');
                return 1;
            }
            let formData = new FormData();
            if(this.invoiceAmount > 0){
                formData.append('cash_account_id', this.selectedCashAccount.id);
            }
            else{
                this.selectedCashAccount = null;
                formData.append('cash_account_id', this.selectedCashAccount);
            }
            formData.append('amount', this.invoiceAmount);
            formData.append('ap_amount', this.apAmount);
            formData.append('total_invoice_amount', this.totalInvoiceAmount);
            formData.append('po_invoice_id', this.selectedPoInvoice.id);
            formData.append('supplier_id', this.selectedPoInvoice.supplier_id);
            formData.append('supplier_account_id', this.selectedPoInvoice.account_id);
            formData.append('creditor_account_id', this.selectedPoInvoice.creditor_account_id);
            let response = await postApiData({url: `/api/invoice_transaction`, form_data:  formData, token: this.getToken()});
            if(response.success){
                this.$notify({
                    text: `A new PO Invoice created`,
                    type: 'info'
                });
                document.getElementById("close_check_modal").click();
                this.getItems();
            }
            else{
                this.$notify({
                    text: `Some errors occurred`,
                    type: 'error'
                });
            }
        },

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });
        },
    },
    watch: {
        apAmount: function () {
            this.invoiceAmountChange();
        },
        // apAmount(val,oldVal) {
        //     console.log(`new: ${val}, old: ${oldVal}`)
        // }
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
