<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Procurement Order Items
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <notifications position="top center" />
            <!-- <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>
                <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
            </div> -->
            <div class="flex pr-0 gap-x-4">
                <!-- <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" @change="selectedTypeChanged()"
                        data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                        <option :value="type.value" v-for="(type, typeIndex) in typeList"
                            :key="typeIndex"> {{ type.name }} </option>
                    </select>
                </div> -->
                <button type="button"
                class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                data-te-toggle="modal" data-te-target="#con">
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
                                    Item
                                </th>
                                <th scope="col" class="">
                                    Brand
                                </th>
                                <th scope="col" class="">
                                    PO Number
                                </th>
                                <th scope="col" class="">
                                    Qty ( UOM )
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, index) in orderItems" :key="index">
                                <tr class="" data-te-collapse-init :data-te-target="'#orderCollapse'+index"
                                aria-expanded="false" aria-controls="collapseExample">
                                    <td class=" font-medium ">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap" @click="togglePurchaseOrders(index)">
                                        {{ item.item_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span class="after:content-[','] last:after:content-[''] pr-1" v-for="brand in item.purchase_order_details">
                                            {{ brand.brand_name }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.po_numbers }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- {{ item.total_quantity }} -->
                                        {{ item.total_base_uom_quantity }} {{ item.base_uom_name }}
                                        {{ item.total_uom_quantity }} {{ item.uom_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div v-if="item.purchase_order_details.length <= 1" >
                                            <!-- <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3"
                                                @click="editBtnClicked(item, index)">
                                                <i class="fal fa-pen"></i>
                                            </button> -->
                                            <button data-te-toggle="modal" data-te-target="#check_modal" class="pr-3"
                                                v-show="feature.includes('po-order.confirm')"
                                                @click="checkBtnClicked(item.purchase_order_details[0], item.item_id, index)">
                                                <i class="fal fa-check"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="item.showPurchaseOrders" v-for="(po, poIndex) in item.purchase_order_details" :key="poIndex">
                                    <td class=" font-medium " colspan="2">

                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ po.brand_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ po.purchase_order.po_id }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ po.base_uom_quantity }} {{ po.base_uom_name }}
                                        {{ po.uom_quantity }} {{ po.uom_name }}
                                        <!-- {{ po.quantity }} -->
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3"
                                            @click="editBtnClicked(item, index)">
                                            <i class="fal fa-pen"></i>
                                        </button> -->
                                        <button data-te-toggle="modal" data-te-target="#check_modal" class="pr-3"
                                            @click="checkBtnClicked(po, item.item_id, index)">
                                            <i class="fal fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getOrderItems(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOrderItems(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- create modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="con" tabindex="-1" aria-labelledby="edit_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="edit_modalLabel">
                        Add
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

                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                    </button>
                    <button type="button" @click="btnClickedCreateContact()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-auto outline-none"
        id="check_modal" tabindex="-1" aria-labelledby="check_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="check_modalLabel">
                        Confirm Procurement Order Item
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
                    <div class="mb-4 grid grid-cols-4 gap-x-4 ">
                        <div>
                            <input type="number"
                            placeholder="Base UOM Qty"
                            v-model="baseUomQty"
                            min="0"
                            class="input-ui"
                            @input="baseUomQtyChanged">
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
                            class="input-ui"
                            @input="uomQtyChanged">
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
                            v-model="isLaterBuy"
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
                            :checked="isLaterBuy"
                            id="checkboxDefault" />
                        <label
                            class="inline-block pl-[0.15rem] hover:cursor-pointer text-sm"
                            for="checkboxDefault">
                            Later Buy
                        </label>
                    </div>

                    <div class="mb-4 text-xs">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th> Supplier Name </th>
                                    <th> Brand </th>
                                    <th> Price </th>
                                    <th> UOM </th>
                                    <th> Average Lead Time </th>
                                    <th> Average Quality </th>
                                    <th> Payment Date </th>
                                    <th> &nbsp; </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(supplier,index) in itemSuppliers" :key="index">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ supplier.supplier.name }}</td>
                                    <td> {{ supplier.brand.name }} </td>
                                    <td> {{ supplier.price.toLocaleString() }} </td>
                                    <td> {{ baseUomQty }} {{ baseUomName }} {{ uomQty }} {{ uomName }} </td>
                                    <td> {{ (supplier.lead_time)? supplier.lead_time: 'Unknown' }} </td>
                                    <td> {{ (supplier.average_quality)? supplier.average_quality: 0 }} % </td>
                                    <td> {{ supplier.payment_date[0] }} </td>
                                    <td class="px-6 py-4">
                                        <input
                                        type="radio"
                                        class="checkbox-style"
                                        :id="'opt-' + supplier.id"
                                        :value="supplier"
                                        v-model="selectedSupplier"
                                        name="selection"
                                        @change="supplierSelectChanged"
                                        >
                                        <label :for="'opt-' + supplier.id"></label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- <div class="mb-4">
                        <label for="supplier" class="text-sm">Supplier</label>
                        <select name="" id="supplier" v-model="selectedSupplier"
                        class="text-sm border border-gray-300 input-ui w-12
                        bg-transparent rounded-lg focus:ring-0" @change="getItemBrands">
                            <option :value="supplier" v-for="(supplier, supplierIndex) in itemSuppliers" :key="supplierIndex">
                                {{ supplier.supplier.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="brand" class="text-sm">Brand</label>
                        <select name="" id="brand" v-model="selectedBrand"
                        class="text-sm border border-gray-300 input-ui w-12
                        bg-transparent rounded-lg focus:ring-0" @change="brandSelectChanged" >
                            <option :value="brand" v-for="(brand, brandIndex) in itemBrands" :key="brandIndex">
                                {{ brand.brand.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="supplier" class="text-sm">Price</label>
                        <input type="number"
                        class="input-ui"
                        v-model="totalPrice"
                        disabled>
                    </div> -->
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 "
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
import Multiselect from 'vue-multiselect';
// import { set } from 'vue';

export default {
    components: {
        Multiselect,
    },
    data() {
        return {
            orderItems: [],

            searchInput:null,

            url:'/api/po_items',
            url_search:'',
            deleteId:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            confirmPO: null,
            uomName: null,
            uomQty: 0,
            baseUomName: null,
            baseUomQty: 0,
            originalPOTotalQty: 0,
            confirmPOTotalQty: 0,
            isLaterBuy: false,
            itemSuppliers: [],
            itemBrands: [],
            selectedSupplier: null,
            selectedBrand: null,
            itemId: null,
            totalPrice: 0,

            uomConversions: [],
            selectedUomConversion: null,
            uomUpperLimit: 0,

            item_left_id:null,

            feature: this.getFeature(),

            selected: null,
            options: [
                { id: 'opt1', name: 'Option One' },
                { id: 'opt2', name: 'Option Two' },
                { id: 'opt3', name: 'Option Three' }
            ]
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getUomConversions(){
            let response = await getApiData({ url: '/api/uom_conversions', token: this.getToken() });
            if(response.data){
                this.uomConversions = response.data;
            }
        },

        brandSelectChanged(){
            console.log(this.selectedBrand);
            if(this.selectedBrand && this.selectedBrand.item_price && this.confirmPO){
                this.confirmPOTotalQty = (this.baseUomQty * this.selectedUomConversion.conversion) + this.uomQty;
                this.totalPrice = (this.confirmPOTotalQty / this.selectedUomConversion.conversion) * this.selectedBrand.item_price.price;
                this.totalPrice = Math.round(this.totalPrice * 100) / 100;
            }else{
                this.totalPrice = 0;
            }
            if(!this.selectedBrand.item_price){
                this.alertValidationMessage('No Item Price Select Brand');
            }
        },

        baseUomQtyChanged(){
            if(this.baseUomQty < 0 || this.uomQty < 0){
                this.alertValidationMessage('Base UOM and UOM must be greater than zero');
                return;
            }
            // if(this.confirmPO){
            //     this.confirmPOTotalQty = (this.baseUomQty * this.selectedUomConversion.conversion) + this.uomQty;
            //     // if(this.confirmPOTotalQty < this.originalPOTotalQty){
            //     //     this.isLaterBuy = true;
            //     // }else{
            //     //     this.isLaterBuy = false;
            //     // }
            // }
            // this.brandSelectChanged();
            // this.getItemSuppliers(this.itemId);
            this.updatePriceOfSuppliers();
        },

        uomQtyChanged(){
            if(this.baseUomQty < 0 || this.uomQty < 0){
                this.alertValidationMessage('Base UOM and UOM must be greater than zero');
                return;
            }
            // if(this.confirmPO){
            //     this.confirmPOTotalQty = (this.baseUomQty * this.selectedUomConversion.conversion) + this.uomQty;
            //     // if(this.confirmPOTotalQty < this.originalPOTotalQty){
            //     //     this.isLaterBuy = true;
            //     // }else{
            //     //     this.isLaterBuy = false;
            //     // }
            // }
            // this.brandSelectChanged();
            // this.getItemSuppliers(this.itemId);
            this.updatePriceOfSuppliers();
        },

        updatePriceOfSuppliers(){
            this.confirmPOTotalQty = (this.baseUomQty * this.selectedUomConversion.conversion) + this.uomQty;
            // this.itemSuppliers.forEach(itemSupplier => {
            //     itemSupplier.price = (this.confirmPOTotalQty / this.selectedUomConversion.conversion) * itemSupplier.item.average_price;
            // });
            this.itemSuppliers = this.itemSuppliers.map(supplier => {
                // Defensive check for nested properties
                let averagePrice = 0;
                if(supplier.item_price){
                    averagePrice = supplier.item_price.price;
                }
                // else{
                //     let averagePrice = 0;
                // }
                // const averagePrice = (supplier.item_price)?(supplier.item_price.price):0;

                let newPrice = 0;
                // Ensure conversion is not zero to prevent division by zero
                if (averagePrice !== undefined && this.selectedUomConversion.conversion !== 0) {
                    newPrice = (this.confirmPOTotalQty / this.selectedUomConversion.conversion) * averagePrice;
                }

                return {
                    ...supplier, // Spread existing properties to create a new object
                    price: Math.round(newPrice * 100) / 100 // Assign the new, rounded price
                };
            });
        },

        async getItemSuppliers(itemId){
            // let response = await getApiData({ url: `/api/supplier_by_item/${itemId}`, token: this.getToken() });
            // if(response.data){
            //     this.itemSuppliers = response.data;
            // }
            this.itemSuppliers = [];
            this.confirmPOTotalQty = (this.baseUomQty * this.selectedUomConversion.conversion) + this.uomQty;
            // ${itemId}
            getApiData({ url: `/api/po-items/${itemId}/suppliers`, token: this.getToken() }).then(response => {
                if (response.success) {
                    this.itemSuppliers = response.data;
                    this.itemSuppliers.forEach(itemSupplier => {
                        itemSupplier.payment_date = [];
                        if(itemSupplier.item_price){
                            itemSupplier.price = (this.confirmPOTotalQty / this.selectedUomConversion.conversion) * itemSupplier.item_price.price;
                        }else{
                            itemSupplier.price = 0;
                        }
                        if(itemSupplier.supplier.credit_term_type == 'amount_limitation'){
                            itemSupplier.payment_date[0] = itemSupplier.supplier.amount_limitation;
                        }
                        if(itemSupplier.supplier.credit_term_type == 'exact_date'){
                            itemSupplier.payment_date = itemSupplier.supplier.exact_date;
                        }
                        if(itemSupplier.supplier.credit_term_type == 'day'){
                            itemSupplier.payment_date[0] = `${itemSupplier.supplier.day} days`;
                        }
                        console.log(itemSupplier.payment_date);
                    });
                }
            });
        },

        async getItemBrands(){
            let response = await getApiData({ url: `/api/brand_by_supplier?item_id=${this.itemId}&supplier_id=${this.selectedSupplier.supplier_id}`, token: this.getToken() });
            if(response.data){
                this.itemBrands = response.data;
                // this.totalPrice = this.itemBrands.find(brand => brand.brand_id == this.selectedBrand)
                this.selectedBrand = this.itemBrands.find(brand => brand.brand_id == this.confirmPO.brand_id)
                // this.confirmPOTotalQty = (this.baseUomQty * this.selectedUomConversion.conversion) + this.uomQty;
                this.confirmPOTotalQty = this.confirmPO.quantity;
                // this.totalPrice = (this.confirmPOTotalQty ) * (response.data[0].item_price.price / this.selectedUomConversion.conversion);
                // this.totalPrice = (this.confirmPOTotalQty / this.selectedUomConversion.conversion) * this.selectedBrand.item_price.price;
                // this.totalPrice = Math.round(this.totalPrice * 100) / 100;
                this.brandSelectChanged();
            }
        },

        async getOrderItems(pageNumber) {
            let url = this.url;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.orderItems = response.data.data;
                this.orderItems.map(item => ({ ...item, showPurchaseOrders: false }));
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
            }
        },

        togglePurchaseOrders(index) {
            if(this.orderItems[index].purchase_order_details.length > 1){
                this.orderItems[index].showPurchaseOrders = !this.orderItems[index].showPurchaseOrders;
            }
        },

        async editBtnClicked(poItem, index){

        },

        async checkBtnClicked(purchaseOrder, itemId, orderItemIndex){
            // console.log(this.orderItems[orderItemIndex]);
            this.itemId = itemId;
            this.selectedUomConversion = this.uomConversions.find(uomConversion => uomConversion.id === purchaseOrder.uom_conversion_id);
            if(typeof this.selectedUomConversion === 'undefined'){
                this.alertValidationMessage('UoM Conversion not Found');
            }
            this.uomUpperLimit = this.selectedUomConversion.conversion - 1;
            this.confirmPO = purchaseOrder;
            this.uomName = this.orderItems[orderItemIndex].uom_name;
            this.uomQty = purchaseOrder.uom_quantity;
            this.baseUomName = this.orderItems[orderItemIndex].base_uom_name;
            this.baseUomQty = purchaseOrder.base_uom_quantity;
            this.originalPOTotalQty = (purchaseOrder.base_uom_quantity * this.selectedUomConversion.conversion) + purchaseOrder.uom_quantity;
            this.confirmPOTotalQty = this.originalPOTotalQty;
            this.totalPrice = 0;
            this.item_left_id = purchaseOrder.item_left_id;
            this.selectedBrand = purchaseOrder;
            this.getItemSuppliers(this.itemId);
            console.log('uom = ' + this.uomQty + ' / base uom = ' + this.baseUomQty)
        },

        async confirmBtnClicked(){
            // let unit_price = this.selectedBrand.item_price.price / this.selectedBrand.item.uom_conversion;
            let formData = new FormData();
            if(!this.selectedBrand || !this.selectedSupplier){
                this.alertValidationMessage('you forgot to provide required data');
                return;
            }
            formData.append('base_uom_quantity', (this.baseUomQty)? this.baseUomQty: 0);
            formData.append('uom_quantity', (this.uomQty)? this.uomQty: 0);
            formData.append('base_uom_id', this.confirmPO.base_uom_id);
            formData.append('uom_id', this.confirmPO.uom_id);
            formData.append('uom_conversion_unit_id', this.selectedUomConversion.id);
            formData.append('item_id', this.itemId);
            formData.append('supplier_id', this.selectedSupplier.supplier_id);
            // formData.append('brand_id', this.selectedBrand.brand_id); // old
            formData.append('brand_id', this.selectedSupplier.brand.id); // new
            // formData.append('item_price_id', this.selectedBrand.item_price.id); // old, new needs to be provided with 'item_price' object from 'api/po-items/{itemId}/suppliers' api
            formData.append('item_price_id', this.selectedSupplier.item_price.id); // new
            formData.append('purchase_order_id', this.confirmPO.purchase_order.id);
            formData.append('quantity', this.confirmPOTotalQty);// total single qty // check for single value
            // formData.append('amount', this.totalPrice); // old
            formData.append('amount', this.selectedSupplier.price); // new
            formData.append('later_buy', (this.isLaterBuy) ? 1 : 0);
            formData.append('item_leftable_type', 'po_order');
            // formData.append('unit_price', unit_price); // old
            formData.append('unit_price', this.selectedSupplier.item_price.price); // new
            formData.append('item_left_id', this.item_left_id);
            let response = await postApiData({ url: '/api/po_items', form_data: formData , token: this.getToken() });
            if(response.success){
                document.getElementById('close_confirm_modal').click();
                this.getOrderItems(1);
                this.$notify({
                    text: `Procurement Order Item created successfully`,
                    type: 'info'
                });
                this.selectedBrand = null;
                this.selectedSupplier = null;
                this.selectedUomConversion = null;
                this.confirmPO = null;
                this.confirmPOTotalQty = 0;
                this.originalPOTotalQty = 0;
                this.baseUomQty = 0;
                this.uomQty = 0;
                this.baseUomName = null;
                this.uomName = null;
                this.itemId = null;
                this.isLaterBuy = false;
                this.totalPrice = 0;
                this.item_left_id = null;
            }else{
                this.$notify({
                    text: `Procurement Order Item creation failed`,
                    type: 'error'
                });
            }
        },

        supplierSelectChanged(){
            // this.itemBrands = this.selectedSupplier.item.brands;
        },

        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getGpsList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getGpsList(1);
        // },

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: field,
                type: 'warn'
            });
        },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getOrderItems(1);
        this.getUomConversions();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>

<style>
/* Hide original radio */
input[type="radio"].checkbox-style {
    display: none;
}

/* Styled label as checkbox */
input[type="radio"].checkbox-style+label {
    display: inline-block;
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid #ccc;
    border-radius: 0.25rem;
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
}

input[type="radio"].checkbox-style:checked+label {
    background-color: #3b82f6;
    /* Tailwind blue-500 */
    border-color: #3b82f6;
}

input[type="radio"].checkbox-style:checked+label::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 4px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}
</style>
