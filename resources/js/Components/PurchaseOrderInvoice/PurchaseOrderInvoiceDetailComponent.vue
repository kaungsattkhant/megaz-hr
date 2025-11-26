<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Purchase Order Invoices
        </p>
        <!-- {{ poInvoice }} -->
    </div>
    <div class="bg-white pt-4 pb-8 px-4 rounded-md shadow-md mb-8 mt-4">
        <div class="pb-2">
            <div class="flex gap-x-8 mb-4 text-sm">
                <p class=" min-w-20">
                    Invoice Id 
                </p>
                <p>
                    {{ poInvoice ? poInvoice.invoice_no : '' }}
                </p>
            </div>
            <div class="flex gap-x-8 mb-4 text-sm">
                <p class=" min-w-20">
                    Invoice Date 
                </p>
                <p>
                    {{ poInvoice ? poInvoice.date_time : '' }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-x-8 gap-y-6 ">
            <div class="col-span-4">
                <label for="" class="label-form mb-3">
                    Amount
                </label>
                <input type="number" v-model="amount" class="input-ui" min="0">
            </div>
            <div class="col-span-4">
                <label for="" class="label-form mb-3">
                    Cash Account
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedCashAccount" class="input-ui" @change="cashAccountChange">
                        <option :value="cashAccount" v-for="(cashAccount, cashAccountIndex) in cashAccountList" :key="cashAccount.code">
                            {{ cashAccount.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-span-4">
                <label for="" class="label-form mb-3">
                    Discount
                </label>
                <input type="number" v-model="discount" class="input-ui" min="0">
            </div>
        </div>
    </div>

    <div class="mt-4 mb-4 bg-white">
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
                                    Item
                                </th>
                                <th scope="col" class="">
                                    Supplier
                                </th>
                                <th scope="col" class="">
                                    Brand
                                </th>
                                <th scope="col" class="">
                                    Qty
                                </th>
                                <th scope="col" class="">
                                    Unit Price
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">
    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(item, index) in arrivalItems" :key="index">
                                <tr>
                                    <td class="whitespace-nowrap">
                                        {{ item.item_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.supplier_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.brand_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.quantity }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.unit_price.toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div>
                                            <button data-te-toggle="modal" data-te-target="#check_modal" class="pr-3"
                                            @click="editbtnClicked(item)">
                                                <i class="fal fa-check"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </div>

                            <tr class="">
                                <td class="" colspan="5">
                                    &nbsp;
                                </td>
                                <td class="">
                                    {{ totalPrice.toLocaleString() }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div>
        <button class="add-btn" @click="confirmBtnClicked">
            Confirm
        </button>
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
                        Edit Unit Price
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
                        <label for="unit_price" class="text-sm">Unit Price</label>
                        <input type="number" id="unit_price" placeholder="Unit Price" v-model="selectedUnitPrice" class="input-ui">
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button"
                    class="add-btn focus:outline-none focus:ring-0 "
                    @click="editUnitPrice()">
                        Confirm
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
    props: ["poInvoiceId"],
    data() {
        return {
            poInvoice:null,

            arrivalItems:[],
            cashAccountList:[],

            amount:0,
            discount:0,
            selectedCashAccount: null,

            selectedArrivalItem:null,
            selectedUnitPrice:0,
            totalPrice: 0,
        }
    },

    methods: {
        ...mapGetters(['getToken']),

        async getPoInvoice(page) {
            let url = `/api/invoices/${this.poInvoiceId}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.poInvoice = response.data[0];
                this.arrivalItems = response.data[0].arrival_items
                this.updateTotalPrice(response.data[0].arrival_items);
                this.amount = response.data[0].total_invoice_amount
                // this.lastPage = response.data.last_page;
                // this.currentPage = page;
                // this.perPage = response.data.per_page;
            }
        },
        async getCashAccounts(){
            let response = await getApiData({url: `/api/get_cash_account`, token: this.getToken()});
            if(response.data){
                this.cashAccountList = response.data;
            }
        },
        editbtnClicked(item){
            this.selectedUnitPrice = item.unit_price;
            this.selectedArrivalItem = item;
        },
        async editUnitPrice(){
            let formData = new FormData();
            formData.append('unit_price', this.selectedUnitPrice);
            let response = await postApiData({url: `/api/po_arrival_list/${this.selectedArrivalItem.id}`, form_data:  formData, token: this.getToken()});
            if(response.success){
                this.getPoInvoice();
                document.getElementById("close_check_modal").click();
            }
            else{
                this.$notify({
                    text: `Some errors occurred`,
                    type: 'error'
                });
            }
        },
        async confirmBtnClicked(){
            if(!this.selectedCashAccount){
                this.alertValidationMessage('Cash Account');
                return 1;
            }
            if(!this.amount){
                this.alertValidationMessage('Amount');
                return 1;
            }
            // if(!this.discount){
            //     this.alertValidationMessage('Discount');
            //     return 1;
            // }
            let apAmount = this.poInvoice.total_invoice_amount - this.amount
            let formData = new FormData();
            formData.append('cash_account_id', this.selectedCashAccount.id);
            formData.append('paid_amount', this.amount);
            formData.append('amount', this.amount);
            formData.append('discount_value', this.discount);
            formData.append('ap_amount', apAmount);
            formData.append('total_invoice_amount', this.poInvoice.total_invoice_amount);
            formData.append('po_invoice_id', this.poInvoice.id);
            formData.append('supplier_id', this.poInvoice.supplier_id);
            formData.append('supplier_account_id', this.poInvoice.account_id);
            let response = await postApiData({url: `/api/invoice_transaction`, form_data:  formData, token: this.getToken()});
            if(response.success){
                this.$notify({
                    text: `A new PO Invoice created`,
                    type: 'info'
                });
                window.location.replace('/purchase_order_invoices')
            }
            else{
                this.$notify({
                    text: `Some errors occurred`,
                    type: 'error'
                });
            }
        },

        

        updateTotalPrice(arrivalItem){
            arrivalItem.forEach((item)=>{
                this.totalPrice += (item.amount);
            });
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
        this.getPoInvoice();
        this.getCashAccounts();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
