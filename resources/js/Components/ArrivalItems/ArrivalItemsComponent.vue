<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Arrival Items
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
                                    Item
                                </th>
                                <th scope="col" class="">
                                    PO Number
                                </th>
                                <th scope="col" class="">
                                    Qty ( UOM )
                                </th>
                                <th scope="col" class="">
                                    Supplier
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, index) in items" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        {{ perPage * (currentPage - 1) + (index + 1) }}
                                    </td>
                                    <td class="whitespace-nowrap" @click="getItemDetail(item,index)">
                                        {{ item.item_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.po_numbers }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- {{ item.total_quantity }} -->
                                        <span class="pr-2">
                                            {{ item.total_base_uom_quantity }}  {{ item.base_uom_name }}
                                        </span>  
                                        <span>
                                            {{ item.total_uom_quantity }}  {{ item.uom_name }} 
                                        </span> 
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.supplier_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- <div v-if="item.arrival_details.length <= 1" >
                                            <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3">
                                                <i class="fal fa-pen"></i>
                                            </button>
                                            <button data-te-toggle="modal" data-te-target="#check_modal" class="pr-3">
                                                <i class="fal fa-check"></i>
                                            </button>
                                        </div> -->
                                    </td>
                                </tr>
                                <tr v-if="item.showDatails" v-for="(arrival, arrivalIndex) in item.arrival_details" :key="arrivalIndex">
                                    <td> &nbsp; </td>
                                    <td class="whitespace-nowrap"> {{ arrival.item_name }} </td>
                                    <td class="whitespace-nowrap"> {{ arrival.po_id }} </td>
                                    <td class="whitespace-nowrap"> 
                                        <span class="pr-2">
                                            {{ arrival.base_uom_quantity }}  {{ arrival.base_uom_name }}
                                        </span>  
                                        <span>
                                            {{ arrival.uom_quantity }}  {{ arrival.uom_name }} 
                                        </span> 
                                        
                                    </td>
                                    <td class="whitespace-nowrap"> {{ arrival.supplier_name }} </td>
                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <button data-te-toggle="modal" data-te-target="#check_modal" class="pr-3"
                                        @click="checkBtnClicked(arrival)" v-show="feature.includes('arrival-item.confirm')">
                                            <i class="fal fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="items.length < 1 && !loading">
                                <td class="" colspan="6">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getItems(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getItems(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
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
                        Confirm Arrival Item
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss id="close_confirm_modal"
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-4 flex gap-x-4 ">
                        <div>
                            <input type="number"
                            placeholder="Base UOM Qty"
                            v-model="baseUomQty"
                            min="0"
                            class="input-ui !w-20"
                            @change="baseUomQtyChanged">
                        </div>
                        <div>
                            <input type="text"
                            placeholder="Base UOM"
                            v-model="baseUomName"
                            disabled
                            class="input-ui">
                        </div>
                        <div>
                            <input type="number"
                            placeholder="UOM Qty"
                            v-model="uomQty"
                            min="0"
                            :max="uomUpperLimit"
                            class="input-ui !w-20"
                            @change="uomQtyChanged">
                        </div>
                        <div>
                            <input type="text"
                            placeholder="UOM"
                            v-model="uomName"
                            disabled
                            class="input-ui">
                        </div>
                    </div>
                    <div class="mb-4 ml-6">
                        <input
                            v-model="isLaterArrival"
                            class="relative float-left -ml-[1.5rem] mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem]
                            appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300
                            outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem]
                            before:scale-0 before:rounded-full before:bg-transparent before:opacity-0
                            before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary
                            checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem]
                            checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45
                            checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid
                            checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer
                            hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none
                            focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12]
                            focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                            focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem]
                            focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100
                            checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                            checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem]
                            checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0
                            checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent
                            dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)]
                            dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                            type="checkbox"
                            value=""
                            :checked="isLaterArrival"
                            id="checkboxDefault" />
                        <label
                            class="inline-block pl-[0.15rem] hover:cursor-pointer text-sm"
                            for="checkboxDefault">
                            Later Arrival
                        </label>
                    </div>
                    <div class="mb-4">
                        <label for="invoice" class="text-sm">Quality (%)</label>
                        <input type="number" placeholder="Quality" v-model="selectedQuality" class="input-ui" min="0" max="100">
                        <!-- <select id="invoice" v-model="selectedQuality" @change="invoiceSelectChanged()"
                            class="text-sm border border-gray-300 input-ui w-12
                            bg-transparent rounded-lg focus:ring-0">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select> -->
                    </div>
                    <div class="mb-4">
                        <label for="invoice" class="text-sm">Invoice</label>
                        <select id="invoice" v-model="selectedInvoice" @change="invoiceSelectChanged()"
                        class="text-sm border border-gray-300 input-ui w-12
                        bg-transparent rounded-lg focus:ring-0">
                            <option :value="invoice" v-for="(invoice, invoiceIndex) in invoices" :key="invoiceIndex">
                                {{ invoice.invoice_no }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4 ml-6">
                        <input
                            v-model="isCreateNewInvoice"
                            class="relative float-left -ml-[1.5rem] mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem]
                            appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300
                            outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem]
                            before:scale-0 before:rounded-full before:bg-transparent before:opacity-0
                            before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary
                            checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem]
                            checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45
                            checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid
                            checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer
                            hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none
                            focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12]
                            focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                            focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem]
                            focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100
                            checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                            checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem]
                            checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0
                            checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent
                            dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)]
                            dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                            type="checkbox"
                            value=""
                            :checked="isCreateNewInvoice"
                            @change="createInvoiceCheckboxChanged"
                            id="createInvoiceChkb" />
                        <label
                            class="inline-block pl-[0.15rem] hover:cursor-pointer text-sm"
                            for="createInvoiceChkb">
                            Create new invoice?
                        </label>
                    </div>
                    <div class="mb-4">
                        <label for="invoice-number" class="text-sm">Invoice Number</label>
                        <input type="text"
                        placeholder="Invoice Number"
                        v-model="newInvoiceNumber"
                        :disabled="!isCreateNewInvoice"
                        class="input-ui"
                        >
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                    @click="confirmBtnClicked()">
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

            currentPage: 1,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            search: '',
            isShowInput: false,

            uomConversions: [],
            selectedUomConversion: null,

            confirmArrivalItem: null,
            uomName: null,
            uomQty: 0,
            baseUomName: null,
            baseUomQty: 0,
            isLaterArrival: false,
            originalArrivalTotalQty: 0,
            confirmArrivalTotalQty: 0,

            invoices:  [],
            selectedInvoice: null,
            isCreateNewInvoice: false,
            newInvoiceNumber: null,

            totalPrice: 0,
            uomUpperLimit: 0,
            isLoading:true,

            feature: this.getFeature(),

            selectedQuality: null,
            loading: false,
        }
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        showInput() {
            this.isShowInput = true;
        },

        async getUomConversions(){
            let response = await getApiData({ url: '/api/uom_conversions', token: this.getToken() });
            if(response.data){
                this.uomConversions = response.data;
            }
        },

        async getItems(page) {
            this.loading = true;
            let url = `/api/po_arrival_list?page=${page}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.items = response.data.data;
                this.items.map(item => ({ ...item, showDatails: false, arrival_details: [] }));
                this.items.forEach(item => {
                    item.showDatails = false;
                    item.arrival_details = [];

                });
                // arrivalItemList.forEach(item => {
                //     let response = await getApiData({ url: `/api/po_arrival_list/${item.item_id}`, token: this.getToken() });
                //     resposne.then(response => {
                //             item.arrival_details = response.data;
                //     });
                // });
                this.lastPage = response.data.last_page;
                this.currentPage = page;
                this.perPage = response.data.per_page;
            }
        },
        async getItemDetail(item, index){
            
            if(this.items[index].arrival_details.length > 0){
                this.items[index].showDatails = !this.items[index].showDatails;
            }
            else{
                let response = await getApiData({ url: `/api/po_arrival_list/${item.item_id}`, token: this.getToken() });
                if(response.data){
                    this.items[index].arrival_details = response.data
                    this.items[index].showDatails = !this.items[index].showDatails;
                }
            }
        },

        toggleItems(index) {
            if(this.items[index].arrival_details.length > 0){
                this.items[index].showDatails = !this.items[index].showDatails;
            }
        },

        async getInvoices(supplierId){
            let response = await getApiData({ url: `/api/invoice_by_supplier/${supplierId}`, token: this.getToken() });
            if(response.data){
                this.invoices = response.data;
            }
        },

        checkBtnClicked(arrival){
            this.confirmArrivalItem = arrival;
            this.baseUomName = this.confirmArrivalItem.base_uom_name;
            this.baseUomQty = this.confirmArrivalItem.base_uom_quantity;
            this.uomName = this.confirmArrivalItem.uom_name;
            this.uomQty = this.confirmArrivalItem.uom_quantity;
            this.selectedUomConversion = this.confirmArrivalItem.uom_conversion;
            this.uomUpperLimit = this.selectedUomConversion - 1;

            this.originalArrivalTotalQty = (this.baseUomQty * this.selectedUomConversion) + this.uomQty;
            this.confirmArrivalTotalQty = this.originalArrivalTotalQty;
            this.calculateTotalPrice();
            console.log(this.confirmArrivalItem);
            this.getInvoices(this.confirmArrivalItem.supplier_id);
        },

        baseUomQtyChanged(){
            if(this.confirmArrivalItem){
                this.confirmArrivalTotalQty = (this.baseUomQty * this.selectedUomConversion) + this.uomQty;
                if(this.confirmArrivalTotalQty < this.originalArrivalTotalQty){
                    // this.isLaterArrival = true;
                }else{
                    // this.isLaterArrival = false;
                }
            }
            this.calculateTotalPrice();
        },

        uomQtyChanged(){
            if(this.confirmArrivalItem){
                this.confirmArrivalTotalQty = (this.baseUomQty * this.selectedUomConversion) + this.uomQty;
                if(this.confirmArrivalTotalQty < this.originalArrivalTotalQty){
                    // this.isLaterArrival = true;
                }else{
                    // this.isLaterArrival = false;
                }
            }
            this.calculateTotalPrice();
        },

        calculateTotalPrice(){
            this.totalPrice = (this.confirmArrivalTotalQty / this.selectedUomConversion) * this.confirmArrivalItem.item_price;
            this.totalPrice = Math.round(this.totalPrice * 100) / 100;
        },

        createInvoiceCheckboxChanged(){
            if(this.isCreateNewInvoice){
                this.selectedInvoice = null;
            }
        },

        invoiceSelectChanged(){
            this.isCreateNewInvoice = false;
            this.newInvoiceNumber = null;
        },

        async confirmBtnClicked(){
            if(this.isCreateNewInvoice && !this.newInvoiceNumber){
                this.alertValidationMessage(`new invoice number`);
                return;
            }
            if(!this.selectedQuality){
                this.alertValidationMessage(`Quality`);
                return;
            }
            if(!this.isCreateNewInvoice && !this.selectedInvoice){
                this.alertValidationMessage(`existing invoice number`);
                return;
            }
            let formData = new FormData();
            formData.append('base_uom_quantity', this.baseUomQty);


            formData.append('uom_quantity', this.uomQty);
            formData.append('base_uom_id', this.confirmArrivalItem.base_uom_id);
            formData.append('uom_id', this.confirmArrivalItem.uom_id);
            formData.append('uom_conversion_unit_id', this.confirmArrivalItem.uom_conversion_id);
            formData.append('quantity', this.confirmArrivalTotalQty);
            formData.append('amount', this.totalPrice);
            formData.append('item_id', this.confirmArrivalItem.item_id);
            formData.append('quality', this.selectedQuality);


            formData.append('purchase_order_id', this.confirmArrivalItem.purchase_order_id);
            if(this.selectedInvoice){
                formData.append('po_invoice_id', this.selectedInvoice.id);
            }
            formData.append('later_buy', (this.isLaterArrival) ? 1 : 0);
            formData.append('is_new_invoice', (this.isCreateNewInvoice) ? 1 : 0);
            formData.append('item_leftable_type', 'arrival_item');
            if(this.newInvoiceNumber){
                formData.append('invoice_no', this.newInvoiceNumber);
            }
            formData.append('supplier_id', this.confirmArrivalItem.supplier_id);
            formData.append('item_left_id', this.confirmArrivalItem.item_left_id);
            formData.append('unit_price', this.confirmArrivalItem.unit_price);
            formData.append('brand_id',this.confirmArrivalItem.brand_id);
            let response = await postApiData({url: `/api/po_arrival_items`, form_data: formData, token: this.getToken()});
            if(response.success){
                this.$notify({
                    text: `Arrival item confirmed successfully`,
                    type: 'info'
                });
                this.getItems(this.currentPage);
                document.getElementById('close_confirm_modal').click();
                this.confirmArrivalItem = null;
                this.selectedUomConversion = null;
                this.uomQty = 0;
                this.uomName = null;
                this.baseUomQty = 0;
                this.isLaterArrival = false;
                this.isCreateNewInvoice = false;
                this.newInvoiceNumber = null;
                this.selectedInvoice = null;
                this.totalPrice = 0;
            }else{
                this.$notify({
                    text: `Arrival item confirmation failed`,
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
    created() {
        this.getItems(this.currentPage);
        // this.getUomConversions();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
