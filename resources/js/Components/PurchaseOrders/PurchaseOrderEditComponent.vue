<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Edit Purchase Order
            </p>
        </div>

        <div class="grid grid-cols-12 gap-x-8 gap-y-6 bg-white pt-4 pb-8 px-4 rounded-md shadow-md mb-8">
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    Date
                </label>
                <input type="date" v-model="date" class="input-ui">
            </div>
            <div class="col-span-9"></div>

            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    Item Name
                </label>
                <select name="" id="" v-model="selectedItem" class="input-ui" @change="itemSelectChanged">
                    <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                        {{ item.name }}
                    </option>
                </select>

            </div>
            <div class="col-span-9"></div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    Qty
                </label>
                <input type="number" v-model="quantity" class="input-ui" placeholder="Qty">
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    UOM
                </label>
                <div class="bg-white mb-0 w-full text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select UOM" data-te-select-filter="true"
                        name="" id="" v-model="selectedUom"
                        class="">
                        <option :value="uom" v-for="(uom, uomIndex) in itemUoms" :key="uomIndex">
                            {{ uom.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-span-6"></div>

            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    Base UOM Qty
                </label>
                <input type="number" v-model="baseQuantity" class="input-ui" placeholder="Qty">
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    Base UOM
                </label>
                <div class="bg-white mb-0 w-full text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select UOM" data-te-select-filter="true"
                        name="" id="" v-model="selectedBaseUom"
                        class="">
                        <option :value="uom" v-for="(uom, uomIndex) in itemUoms" :key="uomIndex">
                            {{ uom.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    &nbsp;
                </label>
                <button class="add-btn" @click="addItemBtnClicked"> Add </button>
            </div>

        </div>

        <div class="bg-white px-4 py-4 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="">
                                Item
                            </th>
                            <th scope="col" class="">
                                Qty
                            </th>
                            <th scope="col" class="">
                                UOM
                            </th>
                            <th scope="col" class="">
                                Unit Price
                            </th>
                            <th scope="col" class="">
                                Total
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(purchaseOrderItem, purchaseOrderItemsIndex) in purchaseOrderItems"
                            :key="purchaseOrderItemsIndex">
                            <tr class="">
                                <td class="">
                                    {{ purchaseOrderItem.name }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.quantity }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.uom_name }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.amount.toLocaleString() }}
                                </td>
                                <td class="">
                                    <!-- {{ (purchaseOrderItem.amount * purchaseOrderItem.quantity).toLocaleString() }} -->
                                    {{ purchaseOrderItem.price.toLocaleString() }}

                                </td>
                                <td class="">
                                    <button @click="editQuantityBtnClicked(purchaseOrderItemsIndex)"
                                    data-te-toggle="modal" data-te-target="#editModal">
                                        <i class="fal fa-pen  pr-3"></i>
                                    </button>
                                    
                                    <button @click="removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex)"
                                    data-te-toggle="modal" data-te-target="#deleteModal">
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                        </div>
                        <div class="contents">
                            <tr class="">
                                <td class="" colspan="4">
                                    &nbsp;
                                </td>
                                <td class="">
                                    {{ totalPrice.toLocaleString() }}
                                </td>
                            </tr>
                        </div>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div>
        <button class="add-btn" @click="editPurchaseOrderBtnClicked">
            Edit Purchase Order
        </button>
    </div>

    <!-- Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="editModal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative  p-4">
                    <!--Modal title-->
                    <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                        Edit Quantity
                    </h5>
                    <!--Close button-->
                    <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative px-12 py-4" data-te-modal-body-ref>
                    <div class="grid grid-cols-12 gap-x-4 w-full">
                        <div class=" col-span-8 mb-4">
                            <label for="" class="label-form mb-3">
                                Qty
                            </label>
                            <input type="number" v-model="quantityEdit" class="input-ui" placeholder="Qty">
                        </div>
            
                        <div class=" col-span-4 mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                UOM
                            </label>
                            <div class="bg-white mb-0 w-full text-xs h-auto rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select UOM" disabled data-te-select-filter="true"
                                    name="" id="" v-model="selectedUomEdit"
                                    class="">
                                    <option :value="uom" v-for="(uom, uomIndex) in itemUoms" :key="uomIndex">
                                        {{ uom.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-x-4 w-full">
                        <div class="col-span-8 mb-4">
                            <label for="" class="label-form mb-3">
                                Base UOM Qty
                            </label>
                            <input type="number" v-model="baseQuantityEdit" class="input-ui" placeholder="Qty">
                        </div>
            
                        <div class="col-span-4 mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Base UOM
                            </label>
                            <div class="bg-white mb-0 w-full text-xs h-full rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select UOM" disabled data-te-select-filter="true"
                                    name="" id="" v-model="selectedBaseUomEdit"
                                    class="input-ui">
                                    <option :value="uom" v-for="(uom, uomIndex) in itemUoms" :key="uomIndex">
                                        {{ uom.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    

                    <!-- <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Quantity
                        </label>
                        <input type="number" placeholder="Quantity" v-model="editQuantity" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div> -->
                    <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                    <!-- <div v-if="!(getDepartment().name == 'HR' && isStaff)" class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]"></div> -->
                        <input
                            @change="laterBuyToggled"
                            v-model="isLaterBuy"
                            class="relative float-left -ml-[1.5rem] mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                            type="checkbox"
                            value=""
                            :checked="isLaterBuy"
                            id="checkboxDefault" />
                        <label
                            class="inline-block pl-[0.15rem] hover:cursor-pointer"
                            for="checkboxDefault">
                            Later Buy
                        </label>
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-center px-12 mb-6">
                    <button type="button" @click="confirmEditQuantityBtnClicked()" class="add-btn focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#editModal">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
            min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Delete ?
                    </h5>
                    <!--Close button-->
                    <button type="button" class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                <div class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmRemovePurchaseOrderItemBtnClicked" type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                    class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import { initTE, Select, Dropdown } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { getCurrentDate } from '../../utilities/datetime-helpers';
    import { mapGetters } from "vuex";

    export default {
        props: ['poId'],
        data() {
            return {
                date: getCurrentDate(),
                itemList: [],
                selectedItem: null,

                uomList: [],
                itemUoms: [],
                selectedUom: null,
                quantity: null,
                selectedBaseUom: null,
                baseQuantity: null,
                purchaseOrderItems: [],

                totalPrice: 0,

                poDetail:null,

                isLaterBuy: false,
                selectedUomEdit: null,
                quantityEdit: null,
                selectedBaseUomEdit: null,
                baseQuantityEdit: null,
                editPurchaseOrderItem: null,
                isLaterBuy:false,

                deleteId:null,
                deleteIndex:null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),
            async getPoDetail(){
                let response = await getApiData({url: `/api/purchase_orders/${this.poId}`, token: this.getToken()});
                if(response.data){
                    this.poDetail = response.data;
                    this.addDetail();
                    // this.date = this.purchaseOrder.date;
                    // this.purchaseOrderItems = this.purchaseOrder.items;
                    // this.updateTotalPrice(this.purchaseOrderItems);
                }
            },
            addDetail(){
                this.data = this.poDetail.date;
                this.poDetail.items.forEach(po => {
                    let quantity = (po.base_uom_quantity * po.uom_conversion.conversion) + po.uom_quantity
                    let price = ((po.base_uom_quantity * po.uom_conversion.conversion) + po.uom_quantity) * po.item.average_price
                    this.purchaseOrderItems.push({
                        id:po.id,
                        item_id: po.item_id,
                        quantity: po.quantity,
                        amount: po.amount,
                        price: price,
                        uom_id: po.uom_id,
                        uom_quantity: po.uom_quantity,
                        uom_name: po.uom.name,
                        uom_conversion_id: po.uom_conversion_id,
                        base_uom_id: po.base_uom_id,
                        base_uom_quantity: po.base_uom_quantity,
                        base_uom_name: po.base_uom.name,
                        name: po.item.name,
                    });
                });
                this.updateTotalPrice(this.purchaseOrderItems);
            },
            async getItemList(){
                let response = await getApiData({url: `/api/items`, token: this.getToken()});
                if(response.data){
                    this.itemList = response.data;
                }
            },

            async getUomList() {
                let response = await getApiData({ url: `/api/uoms`, token: this.getToken() });
                if (response.data) {
                    this.uomList = response.data;
                    console.log(response.data);
                }
            },

            itemSelectChanged(){
                this.itemUoms = [];
                let index = this.uomList.findIndex(uom => uom.id == this.selectedItem.base_uom_id);
                if(index != -1){
                    let baseUom = this.uomList[index];
                    this.itemUoms.push(baseUom);
                }
                index = this.uomList.findIndex(uom => uom.id == this.selectedItem.uom_id);
                if(index != -1){
                    let itemUom = this.uomList[index];
                    this.itemUoms.push(itemUom);
                }
            },

            alertValidationMessage(field){
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to prvide ${field}, please try again`,
                    type: 'warn'
                });
            },

            async addItemBtnClicked(){
                if(!this.selectedItem){
                    this.alertValidationMessage('an item');
                    return 1;
                }
                if(!this.selectedUom){
                    this.alertValidationMessage('a uom');
                    return 1;
                }
                if(this.quantity < 1){
                    this.alertValidationMessage('quantity');
                    return 1;
                }
                if(!this.selectedBaseUom){
                    this.alertValidationMessage('base uom');
                    return 1;
                }
                if(this.baseQuantity < 1){
                    this.alertValidationMessage('quantity');
                    return 1;
                }
                let floatItemPrice = parseFloat(this.selectedItem.average_price)
                console.log(floatItemPrice)
                // let url = `/api/get_uom_conversion_by_uom?po_uom_id=${this.selectedUom.id}&item_uom_id=${this.selectedItem.uom_id}&item_price=${floatItemPrice}&base_uom_id=${this.selectedItem.base_uom_id}`;
                // let response = await getApiData({url: url, token: this.getToken()});
                // let uomConversion = null;
                // let amount = 0;
                // let price = 0;
                // if(response.data){
                //     uomConversion = response.data;
                //     amount = parseInt(response.data.price);
                //     price = this.quantity * amount;
                //     this.$notify({
                //         text: `Uom conversion by uom value ${amount}`,
                //         type: 'info'
                //     });
                // }
                // else{
                //     this.$notify({
                //         title: 'Error',
                //         text: response.message,
                //         type: 'error'
                //     });

                //     return 1;
                // }
                // amount = (this.selectedItem.item_prices)? this.selectedItem.item_prices.price: 0;
                let quantity = (this.baseQuantity * this.selectedItem.uom_conversion) + this.quantity
                let price = ((this.baseQuantity * this.selectedItem.uom_conversion) + this.quantity) * this.selectedItem.average_price
                this.purchaseOrderItems.push({
                    item_id: this.selectedItem.id,
                    quantity: quantity,
                    amount: this.selectedItem.average_price,
                    price: price,
                    uom_id: this.selectedUom.id,
                    uom_quantity: this.quantity,
                    uom_name: this.selectedUom.name,
                    uom_conversion_id: this.selectedItem.uom_conversion_id,
                    base_uom_id: this.selectedBaseUom.id,
                    base_uom_quantity: this.baseQuantity,
                    base_uom_name: this.selectedBaseUom.name,
                    name: this.selectedItem.name,
                });

                this.updateTotalPrice(this.purchaseOrderItems);
                this.selectedItem = null;
                this.selectedUom = null;
                this.quantity = null;
                this.selectedBaseUom = null;
                this.baseQuantity = null;
            },
            editQuantityBtnClicked(purchaseOrderItemsIndex){
                this.editPurchaseOrderItem = this.purchaseOrderItems[purchaseOrderItemsIndex];
                console.log(this.editPurchaseOrderItem);
                if(this.editPurchaseOrderItem.later_buy == 1){
                    this.isLaterBuy = true;
                }
                else{
                    this.isLaterBuy = false;
                }
                this.selectedItem = this.itemList.find(item => item.id == this.editPurchaseOrderItem.item_id);
                this.itemSelectChanged();
                this.selectedUomEdit = this.itemUoms.find(uom => uom.id == this.editPurchaseOrderItem.uom_id);
                this.selectedBaseUomEdit = this.itemUoms.find(uom => uom.id == this.editPurchaseOrderItem.base_uom_id);
                this.quantityEdit = this.editPurchaseOrderItem.uom_quantity;
                this.baseQuantityEdit = this.editPurchaseOrderItem.base_uom_quantity;

            },
            confirmEditQuantityBtnClicked(){
                let index = this.purchaseOrderItems.findIndex(poItem => poItem.id == this.editPurchaseOrderItem.id);
                if(index != -1){
                    this.purchaseOrderItems[index].uom_quantity = this.quantityEdit;
                    this.purchaseOrderItems[index].base_uom_quantity = this.baseQuantityEdit;
                    this.purchaseOrderItems[index].quantity = (this.baseQuantityEdit * this.selectedItem.uom_conversion) + this.quantityEdit
                    this.purchaseOrderItems[index].price = ((this.baseQuantityEdit * this.selectedItem.uom_conversion) + this.quantityEdit) * this.selectedItem.average_price
                    this.purchaseOrderItems[index].later_buy = (this.isLaterBuy)? 1: 0;
                    console.log(this.purchaseOrderItems[index]);
                }

                this.updateTotalPrice(this.purchaseOrderItems);
                this.quantityEdit = null;
                this.baseQuantityEdit = null;
                this.editPurchaseOrderItem = null;
                this.isLaterBuy = false;
            },
            removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex){
                if(this.purchaseOrderItems[purchaseOrderItemsIndex].id){
                    this.deleteId = this.purchaseOrderItems[purchaseOrderItemsIndex].id;
                }
                this.deleteIndex = purchaseOrderItemsIndex;
            },

            async confirmRemovePurchaseOrderItemBtnClicked(){
                if(this.deleteId){
                    let url = `/api/purchase_orders_items/${this.deleteId}`;
                    let response = await deleteApiData({url: url, token: this.getToken()});
                    if(response.success){
                        this.purchaseOrderItems.splice(this.deleteIndex, 1);
                    }
                }
                else{
                    this.purchaseOrderItems.splice(this.deleteIndex, 1);
                }
                this.updateTotalPrice(this.purchaseOrderItems);
                this.deleteId = null;
                this.deleteIndex = null;
            },

            async editPurchaseOrderBtnClicked(){
                if(!this.date){
                    this.alertValidationMessage('date');
                    return 1;
                }
                if(this.purchaseOrderItems.length<1){
                    this.alertValidationMessage('items');
                    return 1;
                }
                let priceTotal = 0;
                this.purchaseOrderItems.forEach((purchaseOrderItem)=>{
                    priceTotal += purchaseOrderItem.price;
                });
                let formData = new FormData();
                formData.append('id', this.poId);
                formData.append('date', this.date);
                formData.append('total_price', priceTotal);
                formData.append('items', JSON.stringify(this.purchaseOrderItems));
                let response = await postApiData({url: `/api/purchase_orders`, form_data:  formData, token: this.getToken()});
                if(response.success){
                    this.$notify({
                        text: `A new purchase order created`,
                        type: 'info'
                    });
                    window.location.replace(`/purchase_orders`);
                    // setTimeout(()=>{
                    //     window.location.replace(`/purchase_orders`);
                    // }, 3000);
                }
                else{
                    this.$notify({
                        text: `Some errors occurred`,
                        type: 'error'
                    });
                }
            },

            updateTotalPrice(poItems){
                this.totalPrice = 0;
                poItems.forEach((item)=>{
                    this.totalPrice += (item.amount * item.quantity);
                });
            },

        },

        created(){
            this.getItemList();
            this.getUomList();
            this.getPoDetail();
        },

        mounted(){
            initTE({Select, Dropdown});
        }
    }
</script>
