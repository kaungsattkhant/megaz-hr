<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Inventory Stocks
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <div>
                    <label for="search" class="search-input mx-2 px-2 py-1"> From Date </label>
                    <input type="date" v-model="fromDate" class="search-input rounded">
                </div>

                <div>
                    <label for="search" class="search-input mx-2 px-2 py-1"> To Date </label>
                    <input type="date" v-model="toDate" class="search-input rounded">
                </div>
                <div class="ml-2 px-2">
                    <button class="mx-1 add-btn h-8 text-[13px] font-inter" @click="searchBtnClicked">Filter</button>
                    <button class="mx-1 add-btn h-8 text-[13px] font-inter" @click="clearSearchBtnClicked">Clear</button>
                </div>

            </div>
            <div></div>
        </div>
        <div class="box-container-table">

            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col">
                                    #
                                </th>
                                <th scope="col">
                                    Item
                                </th>
                                <th scope="col">
                                    Opening
                                </th>
                                <th scope="col">
                                    In
                                </th>
                                <th scope="col">
                                    Out
                                </th>
                                <th scope="col">
                                    Balance
                                </th>
                                <th scope="col">
                                    Total Balance
                                </th>
                                <th scope="col">
                                    Valuation
                                </th>
                                <th scope="col">

                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <div class="contents" v-for="(ledger, index) in inventoryLegderList" :key="index">
                                <tr>
                                    <td>
                                        {{ ++index }}
                                    </td>
                                    <td>
                                        {{ ledger.name }}
                                    </td>
                                    <td>
                                        {{ ledger.opening_balance }} {{ ledger.conversion_uom_name }}
                                    </td>
                                    <td>
                                        {{ ledger.in_balance }} {{ ledger.conversion_uom_name }}
                                    </td>
                                    <td>
                                        {{ (ledger.out_balance).toLocaleString() }} {{ ledger.conversion_uom_name }}
                                    </td>
                                    <td>
                                        {{ (ledger.closing_balance).toLocaleString() }} {{ ledger.conversion_uom_name }}
                                    </td>
                                    <td>
                                        {{ ledger.base_balance }} {{ ledger.conversion_balance }}
                                    </td>
                                    <td>
                                        {{ (ledger.total_value).toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <button id="edit-btn" class="pr-1" @click="transferBtnClicked(ledger, index-1)"
                                            data-te-toggle="modal" data-te-target="#transfer_modal">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <button class="pl-2" @click="addDefectBtnClicked(ledger.item_id, index-1)"
                                        data-te-toggle="modal" data-te-target="#add_defect_modal">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </button>
                                        <button class="pl-2" @click="btnClickedAddMinimum(ledger,index-1)"
                                        data-te-toggle="modal" data-te-target="#add_minimum_modal">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>

                            <!-- <div class="contents" > -->
                                <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                    <td colspan="7" class=" px-6 py-4 font-medium ">
                                        &nbsp;
                                    </td>
                                    <td class=" px-6 py-4 font-medium ">
                                        {{ (totalValuation).toLocaleString() }}
                                    </td>
                                    <td class=" px-6 py-4 font-medium ">
                                        &nbsp;
                                    </td>
                                </tr>
                            <!-- </div> -->

                            <!-- looping end -->
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Transfer Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="transfer_modal" tabindex="-1" aria-labelledby="transfer_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative  p-4">
                            <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black"
                                id="create_modalLabel">
                                Transfer 
                            </h5>
                            <button type="button" id="close"
                                class="absolute top-4 right-4 focus:shadow-none focus:outline-none" data-te-modal-dismiss
                                aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-12 py-4" data-te-modal-body-ref>

                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Source Inventory
                                </label>
                                <div class="bg-white mb-0 w-full text-sm inline-block" data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select Source Inventory"
                                    data-te-select-filter="true" v-model="selectedSourceInventory">
                                        <option :value="sourceInventory.id" v-for="(sourceInventory, sourceInventoryIndex) in sourceInventories">
                                            {{ sourceInventory.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Destination Inventory
                                </label>
                                <div class="bg-white mb-0 w-full text-sm inline-block" data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select Destination Inventory"
                                    data-te-select-filter="true" v-model="selectedDestinationInventory">
                                        <option :value="destinationInventory.id" v-for="(destinationInventory, destinationInventoryIndex) in destinationInventories">
                                            {{ destinationInventory.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Quantity
                                </label>
                                <input type="number" placeholder="Quantity" v-model="quantity" class="text-sm border border-gray-300
                                input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>

                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    UOM
                                </label>
                                <div class="bg-white mb-0 w-full text-sm inline-block" data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select UOM"
                                    data-te-select-filter="true" v-model="selectedUom">
                                        <option :value="uom" v-for="(uom, uomIndex) in itemUoms">
                                            {{ uom.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="flex justify-center px-12 mb-6">
                            <button type="button" @click="confirmTransferBtnClicked"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Check Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="add_defect_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
            min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Add Used/Defect Item
                    </h5>
                    <!--Close button-->
                    <button type="button"
                        class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Defect / Used
                        </label>
                        <div class="bg-white mb-0 w-full text-sm inline-block" data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Type"
                            data-te-select-filter="true" v-model="type">
                                <option value="defect"> Defect </option>
                                <option value="used"> Used </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            UOM
                        </label>
                        <div class="bg-white mb-0 w-full text-sm inline-block" data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select UOM"
                            data-te-select-filter="true" v-model="selectedUom">
                                <option :value="uom" v-for="(uom, uomIndex) in itemUoms">
                                    {{ uom.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Quantity
                        </label>
                        <input type="number" placeholder="Quantity" v-model="defectQuantity" class="text-sm border border-gray-300
                        input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Remark
                        </label>
                        <textarea v-model="defectRemark" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                        name="" id="" cols="15" rows="5"></textarea>
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmAddDefectBtnClicked" type="button" data-te-toggle="modal" data-te-target="#add_defect_modal"
                    class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--Check Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="checkModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
            min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Check Item
                    </h5>
                    <!--Close button-->
                    <button type="button"
                        class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p>
                        Are you sure ?
                    </p>
                </div>

                <!--Modal footer-->
                <!-- <div class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmCheckPurchaseOrderItemBtnClicked" type="button" data-te-toggle="modal" data-te-target="#checkModal"
                    class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div> -->
            </div>
        </div>
    </div>


    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="add_minimum_modal" tabindex="-1" aria-labelledby="add_minimum_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="add_minimum_modalLabel">
                        Add Minimum Holding Amount
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss id="close_add_minimum_modal"
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <!-- <div class="mb-4 ">
                        <label for="total-amount" class="text-sm">Invoice Amount: {{ totalInvoiceAmount.toLocaleString() }}</label>
                    </div> -->
                    <!-- <div class="mb-4 ">
                        <label for="amount" class="text-sm">Amount</label>
                        <input type="number" id="amount" placeholder="Invoice Amount" v-model="invoiceAmount" @input="invoiceAmountChange()" class="input-ui">
                    </div>
                    <div class="mb-4 ml-6">

                    </div>

                    <div class="mb-4">
                        <label for="cashbook" class="text-sm">Cash Book</label>
                        <select id="cashbook" v-model="selectedCashAccount"
                        class="text-sm border border-gray-300 input-ui w-12
                        bg-transparent rounded-lg focus:ring-0">
                            <option :value="cashAccount" v-for="(cashAccount, cashAccountIndex) in cashAccountList" :key="cashAccountIndex">
                                {{ cashAccount.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4 ">
                        <label for="total-amount" class="text-sm">AP Amount: {{ apAmount.toLocaleString() }}</label>
                    </div> -->
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
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            inventoryLegderList: [],
            inventoryList: [],
            totalValuation: 0,
            selectedInventory: null,
            quantity: null,
            itemId: null,

            transferItem: null,
            deleteId: null,

            sourceInventories: [],
            selectedSourceInventory: null,
            destinationInventories: [],
            selectedDestinationInventory: null,

            defectItemId: null,
            defectItem: null,
            type: null,
            defectQuantity: null,
            uomList: [],
            itemUoms: [],
            selectedUom: null,
            defectRemark: null,

            fromDate: null,
            toDate: null,
            ledger:null,
            minimumLedger:null,

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
    // props: ['inventory_id'],

    methods: {
        ...mapGetters(['getToken']),

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });

        },

        async getInventoryLegderList(pageNumber) {
            if(pageNumber){
                this.currentPage = pageNumber;
            }
            let url = `/api/inventory_ledger_list`;
            if(this.fromDate && this.toDate){
                url = `${url}?from_date=${this.fromDate}&to_date=${this.toDate}`;
            }
            const response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.totalValuation = 0;
                this.inventoryLegderList = response.data;
                this.inventoryLegderList.forEach(ledger => {
                    ledger.base_balance = Math.floor(ledger.closing_balance / ledger.conversion) + ' ' + ledger.base_uom_name;
                    ledger.conversion_balance = null;
                    let conversionBalance = ledger.closing_balance % ledger.conversion;
                    if(conversionBalance > 0){
                        ledger.conversion_balance = conversionBalance + ' ' + ledger.conversion_uom_name;
                    }

                    this.totalValuation += ledger.total_value;
                });
            }
        },
        async getInventoryList() {
            const response = await getApiData({ url: '/api/inventories', token: this.getToken() });
            if (response.data) {
                this.inventoryList = response.data;
            }
        },

        async getUomList() {
            let url = `/api/uoms`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.uomList = response.data;
            }
        },

        btnClickedAddMinimum(ledger, index){
            this.minimumLedger = ledger;
        },
        addDefectBtnClicked(id, ledgerIndex){
            this.defectItemId = id;
            this.defectItem = this.inventoryLegderList[ledgerIndex];

            this.itemUoms = [];
            let index = this.uomList.findIndex(uom => uom.id == this.defectItem.base_unit_id);
            if(index != -1){
                let baseUom = this.uomList[index];
                this.itemUoms.push(baseUom);
            }
            index = this.uomList.findIndex(uom => uom.id == this.defectItem.item_uom_id);
            if(index != -1){
                let itemUom = this.uomList[index];
                this.itemUoms.push(itemUom);
            }
        },

        async confirmAddDefectBtnClicked(){
            if(!this.type){
                this.alertValidationMessage(`defect type`);
                return 1;
            }
            if(!this.selectedUom){
                this.alertValidationMessage(`UOM`);
                return 1;
            }
            if(!this.defectQuantity){
                this.alertValidationMessage(`quantity`);
                return 1;
            }
            let formData = new FormData();
            formData.append('item_id', this.defectItemId);
            // formData.append('inventory_id', this.inventory_id);/
            formData.append('quantity', this.defectQuantity);
            formData.append('uom_id', this.selectedUom.id);
            formData.append('type', this.type);
            formData.append('base_uom_id', this.defectItem.base_unit_id);
            if(this.defectRemark){
                formData.append('remark', this.defectRemark);
            }

            let url = `/api/used_defected_items`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.$notify({
                    text: `Success`,
                    type: 'info'
                });
            }
            else{
                this.$notify({
                    text: response.message,
                    type: 'info'
                });
            }

            this.selectedUom = null;
            this.defectItemId = null;
            this.defectRemark = null;
            this.defectQuantity = null;
            this.type = null;
            this.defectItem = null;
        },

        async transferBtnClicked(ledger, ledgerIndex) {
            this.ledger = ledger
            this.itemId = ledger.item_id;
            this.transferItem = this.inventoryLegderList[ledgerIndex];
            let url = `/api/inventory_list`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.sourceInventories = response.data.source_inventories;
                this.destinationInventories = response.data.destination_inventories;
            }

            this.itemUoms = [];
            let index = this.uomList.findIndex(uom => uom.id == this.transferItem.base_unit_id);
            if(index != -1){
                let baseUom = this.uomList[index];
                this.itemUoms.push(baseUom);
            }
            index = this.uomList.findIndex(uom => uom.id == this.transferItem.item_uom_id);
            if(index != -1){
                let itemUom = this.uomList[index];
                this.itemUoms.push(itemUom);
            }
        },

        confirmTransferBtnClicked() {
            if (!this.selectedSourceInventory) {
                this.alertValidationMessage(`source inventory`);
                return 1;
            }
            if (!this.selectedDestinationInventory) {
                this.alertValidationMessage(`destination inventory`);
                return 1;
            }
            if (!this.quantity) {
                this.alertValidationMessage(`quantity`);
                return 1;
            }
            if(!this.selectedUom){
                this.alertValidationMessage(`UOM`);
                return 1;
            }
            this.transferInventory();
        },

        async transferInventory() {
            let formData = new FormData();
            formData.append('source_inventory_id', this.selectedSourceInventory);
            formData.append('destination_inventory_id', this.selectedDestinationInventory);
            formData.append('quantity', this.quantity);
            formData.append('item_id', this.itemId);
            if(this.selectedUom.id == this.transferItem.base_unit_id){
                formData.append('type', 'base_uom');
            }
            else{
                formData.append('type', 'uom');
            }
            formData.append('uom_id', this.selectedUom.id);
            formData.append('base_uom_id', this.transferItem.base_unit_id);
            formData.append('conversion_uom_id', this.transferItem.conversion_unit_id);
            formData.append('uom_conversion', this.transferItem.conversion);

            let response = await postApiData({ url: '/api/transfers', form_data: formData, token: this.getToken() });
            if (response.success) {
                // this.getInventoryLegderList(null);
                this.closeModal();
                this.clearForm();
                this.$notify({
                    text: `Success`,
                    type: 'info'
                });
                this.selectedUom = null;
                this.transferItem = null;
            }
            else {
                this.$notify({
                    text: `some errors occur`,
                    type: 'error'
                });
            }

            
        },

        searchBtnClicked(){
            this.getInventoryLegderList();
        },

        clearSearchBtnClicked(){
            this.fromDate = null;
            this.toDate = null;
            this.getInventoryLegderList();
        },

        closeModal() {
            document.getElementById("close").click();
        },

        clearForm() {
            this.selectedInventory = null,
                this.quantity = null
        },
    },

    created() {
        this.getInventoryLegderList(null);
        this.getInventoryList();
        this.getUomList();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
