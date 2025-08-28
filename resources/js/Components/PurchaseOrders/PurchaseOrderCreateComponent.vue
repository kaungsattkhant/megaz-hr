<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Add Purchase Order
            </p>
        </div>

        <div class="bg-white pt-4 pb-8 px-4 rounded-md shadow-md mb-8">
            <div class="grid grid-cols-12 w-3/5 min-w-fit gap-x-8 gap-y-6 ">
                <div class="col-span-4">
                    <label for="" class="label-form mb-3">
                        Date
                    </label>
                    <input type="date" v-model="date" class="input-ui">
                </div>
                <div class="col-span-4">
                    <label for="" class="label-form mb-3">
                        Type
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Item"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type" v-for="(type, typeIndex) in typeList" :key="typeIndex">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-4" v-if="selectedType && selectedType.value === 'event'">
                    <label for="" class="label-form mb-3">
                        Event
                    </label>
                    <div class="flex w-full gap-x-4">
                        <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Item"
                                data-te-select-filter="true" name="" id="" v-model="selectedEvent" class="input-ui">
                                <option :value="event" v-for="(event, eventIndex) in eventList" :key="eventIndex">
                                    {{ event.name }}
                                </option>
                            </select>
                        </div>
                        <button data-te-toggle="modal" data-te-target="#add_event_modal" @click="addBtnClicked" class="inline-block py-2">
                            <i class="fal fa-plus  pr-3"></i>
                        </button>
                    </div>
                </div>
                <div class="col-span-4" v-else></div>

                <div class="col-span-4">
                    <label for="" class="label-form mb-3">
                        Item Name
                    </label>
                    <!-- <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Item"
                            data-te-select-filter="true" name="" id="" v-model="selectedItem" class="input-ui" @change="itemSelectChanged">
                            <option :value="item" v-for="(item, itemIndex) in itemList" :key="item.code">
                                {{ item.name }}
                            </option>
                        </select>
                    </div> -->
                    <multiselect v-model="selectedItem" :options="itemList" :multiple="false" :close-on-select="true"
                        @select="itemSelectChanged"
                        :clear-on-select="false" :preserve-search="true" placeholder="Select Item" label="name"
                        track-by="name" :preselect-first="false">
                    </multiselect>
                    <!-- <select name="" id="" v-model="selectedItem" class="input-ui" @change="itemSelectChanged">
                        <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                            {{ item.name }}
                        </option>
                    </select> -->

                </div>
                <div class="col-span-4">
                    <label for="" class="label-form mb-3">
                        Item Code
                    </label>
                    <!-- <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            data-te-select-filter="true" name="" id="" v-model="selectedItem" class="input-ui" @change="itemSelectChanged">
                            <option :value="item" v-for="(item, itemIndex) in itemList" :key="item.code">
                                {{ item.code }}
                            </option>
                        </select>
                    </div> -->
                    <multiselect v-model="selectedItem" :options="itemList" :multiple="false" :close-on-select="true"
                        @select="itemSelectChanged"
                        :clear-on-select="false" :preserve-search="true" placeholder="Select Item" label="code"
                        track-by="name" :preselect-first="false">
                    </multiselect>
                </div>
                <div class="col-span-4">
                    <label for="" class="label-form mb-3">
                        Brand
                    </label>
                    <select name="" id="" v-model="selectedBrand" class="input-ui" @change="selectedBrandChange()">
                        <option :value="brand" v-for="(brand, brandIndex) in brandList" :key="brandIndex">
                            {{ brand.name }}
                        </option>
                    </select>
                </div>
                <div class="col-span-8 grid grid-cols-2 gap-y-8 gap-x-8">
                    <div class="col-span-1">
                        <label for="" class="label-form mb-3">
                            Base UOM Qty
                        </label>
                        <input type="number" v-model="baseQuantity" class="input-ui" placeholder="Base Qty">
                    </div>
        
                    <div class="col-span-1">
                        <label for="" class="block text-sm text-black mb-3">
                            Base UOM
                        </label>
                        <select name="" id="" v-model="selectedBaseUom" class="input-ui" disabled>
                            <option :value="uom" v-for="(uom, uomIndex) in itemUoms" :key="uomIndex">
                                {{ uom.name }}
                            </option>
                        </select>
                        <!-- <div class="bg-white mb-0 w-full text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select UOM" data-te-select-filter="true"
                                name="" id="" v-model="selectedBaseUom"
                                class="">
                                <option :value="uom" v-for="(uom, uomIndex) in itemUoms" :key="uomIndex">
                                    {{ uom.name }}
                                </option>
                            </select>
                        </div> -->
                    </div>
                    <div class="col-span-1">
                        <label for="" class="label-form mb-3">
                            Qty
                        </label>
                        <input type="number" v-model="quantity" class="input-ui" placeholder="Qty">
                    </div>
        
                    <div class="col-span-1">
                        <label for="" class="block text-sm text-black mb-3">
                            UOM
                        </label>
                        <select name="" id="" v-model="selectedUom" class="input-ui" disabled>
                            <option :value="uom" v-for="(uom, uomIndex) in itemUoms" :key="uomIndex">
                                {{ uom.name }}
                            </option>
                        </select>
                        <!-- <div class="bg-white mb-0 w-full text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select UOM" data-te-select-filter="true"
                                name="" id="" v-model="selectedUom"
                                class="">
                                <option :value="uom" v-for="(uom, uomIndex) in itemUoms" :key="uomIndex">
                                    {{ uom.name }}
                                </option>
                            </select>
                        </div> -->
                    </div>
                    <!-- <div class="col-span-6"></div> -->
        
                    
                </div>
                <div class="col-span-4">
                    <label for="" class="block text-sm text-black mb-3">
                        Remark
                    </label>
                    <textarea v-model="remark" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                         name="" id="" cols="30" rows="6"></textarea>
                </div>
                <div class="col-span-8">
                    <p class="text-red-600" v-if="limitWarning">
                        {{ limitWarning }}
                    </p>
                </div>
                <div class="col-span-4 justify-end flex">
                    <label for="" class="block text-sm text-black mb-3">
                        &nbsp;
                    </label>
                    <button class="add-btn" @click="addItemBtnClicked"> Add </button>
                </div>

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
                                Brand
                            </th>
                            <th scope="col" class="">
                                Qty
                            </th>
                            <!-- <th scope="col" class="">
                                UOM
                            </th> -->
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
                                    {{ purchaseOrderItem.brand_name }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.base_uom_quantity }}{{ purchaseOrderItem.base_uom_name }}
                                    {{ purchaseOrderItem.uom_quantity }}{{ purchaseOrderItem.uom_name }}
                                </td>
                                <!-- <td class="">
                                    {{ purchaseOrderItem.uom_name }}
                                </td> -->
                                <td class="">
                                    {{ purchaseOrderItem.unit_price.toLocaleString() }} ({{ purchaseOrderItem.base_uom_name }})
                                </td>
                                <td class="">
                                    <!-- {{ (purchaseOrderItem.amount * purchaseOrderItem.quantity).toLocaleString() }} -->
                                    {{ purchaseOrderItem.amount.toLocaleString() }}

                                </td>
                                <td class="">
                                    <button @click="removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex)">
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
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_event_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create Event
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            id="close_create_modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" v-model="event_name" class="input-ui mb-2">
                        </div>
                    </div>

                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnCreateEvent()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div>
        <button class="add-btn" @click="createPurchaseOrderBtnClicked">
            Create Purchase Order
        </button>
    </div>

    <button data-te-toggle="modal" data-te-target="#add_event_modal" class="opacity-0 w-0">
    </button>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import { initTE, Select, Dropdown, Modal } from "tw-elements";
    import { getApiData, postApiData } from '../../utilities/ajax-helpers';
    import { getCurrentDate } from '../../utilities/datetime-helpers';
    import { mapGetters } from "vuex";
import { find } from "lodash";

    export default {
        components: {
            Multiselect
        },
        data() {
            return {
                date: getCurrentDate(),
                itemList: [],
                selectedItem: null,
                brandList:[],
                selectedBrand:null,

                uomList: [],
                itemUoms: [],
                selectedUom: null,
                quantity: 0,
                selectedBaseUom: null,
                baseQuantity: 0,
                purchaseOrderItems: [],
                remark:null,

                unitPrice:null,
                totalPrice: 0,

                typeList:[
                    {'name': 'KTV', 'value': 'ktv'},
                    {'name': 'Restaurant', 'value': 'restaurant'},
                    {'name': 'Event', 'value': 'event'},
                ],
                selectedType: null,
                eventList: [],
                selectedEvent: null,
                limitWarning: null,

                event_name: null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

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
            async getEventList(){
                let response = await getApiData({url: `/api/events`, token: this.getToken()});
                if(response.data){
                    this.eventList = response.data;
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
                this.selectedUom = this.itemUoms.find(uom => uom.id == this.selectedItem.uom_id)
                this.selectedBaseUom = this.itemUoms.find(uom => uom.id == this.selectedItem.base_uom_id)
                this.brandList = this.selectedItem.brands;
            },
            async selectedBrandChange(){
                console.log('brand change')
                let response = await getApiData({url: '/api/items/' + this.selectedItem.id + '/brands/' + this.selectedBrand.id, token: this.getToken()});
                if(response.data){
                    this.unitPrice = response.data;
                    console.log(response)
                }
            },
            alertValidationMessage(field){
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to prvide ${field}, please try again`,
                    type: 'warn'
                });

            },
            addBtnClicked(){
                this.event_name = null;
            },
            btnCreateEvent(){
                if(!this.event_name){
                    this.alertValidationMessage(`Name`);
                    return 1;
                }
                else{
                    this.createEvent();
                }
            },
            async createEvent() {
                let formData = new FormData();
                formData.append('name', this.event_name);
                let response = await postApiData({ url: '/api/events', form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.getEventList(1);
                    document.getElementById('close_create_modal').click();
                }
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            },  


            async addItemBtnClicked(){
                if(!this.selectedItem){
                    this.alertValidationMessage('an item');
                    return 1;
                }
                if(!this.selectedBrand){
                    this.alertValidationMessage('Brand');
                    return 1;
                }
                if(!this.selectedUom){
                    this.alertValidationMessage('UOM');
                    return 1;
                }
                if(this.quantity < 1 && this.baseQuantity < 1){
                    this.alertValidationMessage('Quantity');
                    return 1;
                }
                if(!this.selectedBaseUom){
                    this.alertValidationMessage('Base UOM');
                    return 1;
                }
                if(!this.remark){
                    this.alertValidationMessage('Remark');
                    return 1;
                }
                
                // amount = (this.selectedItem.item_prices)? this.selectedItem.item_prices.price: 0;
                // let price = ((this.baseQuantity * this.selectedItem.uom_conversion) + this.quantity) * this.selectedItem.average_price
                // let price = (this.baseQuantity * this.selectedItem.average_price) + (this.quantity * (this.selectedItem.average_price  / this.selectedItem.uom_conversion))
                let quantity = (this.baseQuantity * this.selectedItem.uom_conversion) + this.quantity
                let price = quantity * (this.unitPrice / this.selectedItem.uom_conversion);

                let isLimitExceed = 0;
                let formData = new FormData();
                formData.append('item_id', this.selectedItem.id);
                formData.append('base_uom_quantity', this.baseQuantity);
                formData.append('uom_quantity', this.quantity);
                formData.append('amount', price);
                let response = await postApiData({ url: '/api/purchase_orders_items/check_limitation', form_data: formData, token: this.getToken() });
                if (response.success) {
                    isLimitExceed = 0;
                    
                    // document.getElementById('close_create_modal').click();
                    // this.limitWarning = null;
                }
                else {
                    isLimitExceed = 1;
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "error"
                    });
                    // this.limitWarning = response.message;
                }


                    if(this.purchaseOrderItems.some((item) => item.item_id === this.selectedItem.id && item.brand_id === this.selectedBrand.id) && this.purchaseOrderItems.length > 0){
                        let index = this.purchaseOrderItems.findIndex(item => item.item_id == this.selectedItem.id && item.brand_id == this.selectedBrand.id)
                        // alert('item brand match' + index)
                        this.purchaseOrderItems[index].quantity += quantity;
                        this.purchaseOrderItems[index].amount += price;
                        this.purchaseOrderItems[index].uom_quantity += this.quantity;
                        this.purchaseOrderItems[index].base_uom_quantity += this.baseQuantity;
                        this.purchaseOrderItems[index].remark = this.remark;
                    }
                    else{
                        this.purchaseOrderItems.push({
                            item_id: this.selectedItem.id,
                            brand_id:this.selectedBrand.id,
                            brand_name:this.selectedBrand.name,
                            quantity: quantity,
                            // amount: this.selectedItem.average_price,
                            amount:price,
                            // unit_price: this.unitPrice / this.selectedItem.uom_conversion,
                            unit_price:this.unitPrice,
                            uom_id: this.selectedUom.id,
                            uom_quantity: this.quantity,
                            uom_name: this.selectedUom.name,
                            uom_conversion_id: this.selectedItem.uom_conversion_id,
                            base_uom_id: this.selectedBaseUom.id,
                            base_uom_quantity: this.baseQuantity,
                            base_uom_name: this.selectedBaseUom.name,
                            name: this.selectedItem.name,
                            remark: this.remark,
                            is_exceed_max_limitation: isLimitExceed
                            // total_unit_price:this.unitPrice*quantity
                        });
                    }
                    
                    this.updateTotalPrice(this.purchaseOrderItems);
                    this.selectedItem = null;
                    this.selectedUom = null;
                    this.quantity = 0;
                    this.selectedBaseUom = null;
                    this.baseQuantity = 0;
                    this.selectedBrand = null;
                    this.remark = null;
                    // this.unitPrice = null;
                
            },

            removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex){
                this.purchaseOrderItems.splice(purchaseOrderItemsIndex, 1);
                this.updateTotalPrice(this.purchaseOrderItems);
            },

            async createPurchaseOrderBtnClicked(){
                if(!this.date){
                    this.alertValidationMessage('Date');
                    return 1;
                }
                if(!this.selectedType){
                    this.alertValidationMessage('Type');
                    return 1;
                }
                if(this.selectedType.value === 'event' && !this.selectedEvent){
                    this.alertValidationMessage('Event');
                    return 1;
                }
                if(this.purchaseOrderItems.length<1){
                    this.alertValidationMessage('items');
                    return 1;
                }
                let priceTotal = 0;
                this.purchaseOrderItems.forEach((purchaseOrderItem)=>{
                    priceTotal += purchaseOrderItem.amount;
                });
                console.log(priceTotal)
                let formData = new FormData();
                formData.append('date', this.date);
                formData.append('total_price', priceTotal);
                formData.append('type', this.selectedType.value);
                if(this.selectedType.value === 'event'){
                    formData.append('event_id', this.selectedEvent.id);
                }
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
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            },

            updateTotalPrice(poItems){
                this.totalPrice = 0;
                poItems.forEach((item)=>{
                    // this.totalPrice += (item.amount * item.quantity);
                    this.totalPrice += item.amount;
                    console.log(this.totalPrice)
                });
            },

        },

        created(){
            this.getItemList();
            this.getUomList();
            this.getEventList();
        },

        mounted(){
            initTE({Select, Dropdown, Modal});
        }
    }
</script>
