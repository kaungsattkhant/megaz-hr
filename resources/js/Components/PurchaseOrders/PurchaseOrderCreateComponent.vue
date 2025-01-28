<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Add Purchase Order
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
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    Brand
                </label>
                <select name="" id="" v-model="selectedBrand" class="input-ui" @change="selectedBrandChange()">
                    <option :value="brand" v-for="(brand, brandIndex) in brandList" :key="brandIndex">
                        {{ brand.name }}
                    </option>
                </select>

            </div>
            <div class="col-span-6"></div>
            <div class="col-span-6 grid grid-cols-4 gap-x-8">
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
                                Brand
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
                                    {{ purchaseOrderItem.brand_name }}
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
    </div>
    <div>
        <button class="add-btn" @click="createPurchaseOrderBtnClicked">
            Create Purchase Order
        </button>
    </div>

</template>

<script>
    import { initTE, Select, Dropdown } from "tw-elements";
    import { getApiData, postApiData } from '../../utilities/ajax-helpers';
    import { getCurrentDate } from '../../utilities/datetime-helpers';
    import { mapGetters } from "vuex";

    export default {
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

                unitPrice:null,
                totalPrice: 0,
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
                // if(this.baseQuantity < 1){
                //     this.alertValidationMessage('Base Quantity');
                //     return 1;
                // }
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
                // let price = ((this.baseQuantity * this.selectedItem.uom_conversion) + this.quantity) * this.selectedItem.average_price
                // let price = (this.baseQuantity * this.selectedItem.average_price) + (this.quantity * (this.selectedItem.average_price  / this.selectedItem.uom_conversion))
                let price = quantity * (this.unitPrice / this.selectedItem.uom_conversion);
                this.purchaseOrderItems.push({
                    item_id: this.selectedItem.id,
                    brand_id:this.selectedBrand.id,
                    brand_name:this.selectedBrand.name,
                    quantity: quantity,
                    // amount: this.selectedItem.average_price,
                    amount:this.unitPrice,
                    price: price,
                    uom_id: this.selectedUom.id,
                    uom_quantity: this.quantity,
                    uom_name: this.selectedUom.name,
                    uom_conversion_id: this.selectedItem.uom_conversion_id,
                    base_uom_id: this.selectedBaseUom.id,
                    base_uom_quantity: this.baseQuantity,
                    base_uom_name: this.selectedBaseUom.name,
                    name: this.selectedItem.name,
                    unit_price:this.unitPrice,
                    total_unit_price:this.unitPrice*quantity
                });

                this.updateTotalPrice(this.purchaseOrderItems);
                this.selectedItem = null;
                this.selectedUom = null;
                this.quantity = 0;
                this.selectedBaseUom = null;
                this.baseQuantity = 0;
                this.selectedBrand = null;
                // this.unitPrice = null;
            },

            removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex){
                this.purchaseOrderItems.splice(purchaseOrderItemsIndex, 1);
                this.updateTotalPrice(this.purchaseOrderItems);
            },

            async createPurchaseOrderBtnClicked(){
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
                console.log(priceTotal)
                let formData = new FormData();
                formData.append('date', this.date);
                formData.append('total_price', priceTotal);
                formData.append('items', JSON.stringify(this.purchaseOrderItems));
                let response = await postApiData({url: `/api/purchase_orderswwww`, form_data:  formData, token: this.getToken()});
                if(response.success){
                    this.$notify({
                        text: `A new purchase order created`,
                        type: 'info'
                    });
                    // window.location.replace(`/purchase_orders`);
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
                    // this.totalPrice += (item.amount * item.quantity);
                    this.totalPrice += item.price;
                    console.log(this.totalPrice)
                });
            },

        },

        created(){
            this.getItemList();
            this.getUomList();
        },

        mounted(){
            initTE({Select, Dropdown});
        }
    }
</script>
