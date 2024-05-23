<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Add Purchase Order
            </p>
        </div>

        <div class="grid grid-cols-12 gap-x-8 bg-white pt-4 pb-8 px-4 rounded-md shadow-md mb-8">
            <div class="mb-6 col-span-3">
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
                <select name="" id="" v-model="selectedItem" class="input-ui">
                    <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex"> {{ item.name }}
                    </option>
                </select>

            </div>

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
                        <option :value="uom" v-for="(uom, uomIndex) in uomList" :key="uomIndex">
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
                                Amount
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

                uomList: [],
                selectedUom: null,

                quantity: null,
                purchaseOrderItems: [],

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
                let url = `/api/get_uom_conversion_by_uom?po_uom_id=${this.selectedUom.id}&item_uom_id=${this.selectedItem.item_prices.uom_id}&item_price=${this.selectedItem.item_prices.price}&base_uom_id=${this.selectedItem.base_uom_id}`;
                let response = await getApiData({url: url, token: this.getToken()});
                let uomConversion = null;
                let amount = 0;
                let price = 0;
                if(response.data){
                    uomConversion = response.data;
                    amount = parseInt(response.data.price);
                    price = this.quantity * amount;
                    // amount = uomConversion.conversion;
                    // amount = amount * (response.data.price);
                    this.$notify({
                        text: `Uom conversion by uom value ${amount}`,
                        type: 'info'
                    });
                }
                else{
                    this.$notify({
                        title: 'Error',
                        text: response.message,
                        type: 'error'
                    });

                    return 1;
                }
                // amount = (this.selectedItem.item_prices)? this.selectedItem.item_prices.price: 0;
                this.purchaseOrderItems.push({
                    item_id: this.selectedItem.id,
                    name: this.selectedItem.name,
                    quantity: this.quantity,
                    amount: amount,
                    price: price,
                    uom_id: this.selectedUom.id,
                    uom_name: this.selectedUom.name,
                    uom_conversion_id: uomConversion.id,
                });

                this.updateTotalPrice(this.purchaseOrderItems);
                this.selectedItem = null;
                this.selectedUom = null;
                this.quantity = null;
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
                    priceTotal += purchaseOrderItem.amount;
                });
                let formData = new FormData();
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
        },

        mounted(){
            initTE({Select, Dropdown});
        }
    }
</script>
