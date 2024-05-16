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
                    &nbsp;
                </label>
                <button class="add-btn" @click="addItemBtnClicked"> Add </button>
            </div>

        </div>

        <div class=" bg-white px-4 py-4 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="text-left">
                                Item
                            </th>
                            <th scope="col" class="">
                                Qty
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
                                <td class="text-left">
                                    {{ purchaseOrderItem.name }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.quantity }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.amount.toLocaleString() }}
                                </td>
                                <td class="">
                                    {{ (purchaseOrderItem.amount * purchaseOrderItem.quantity).toLocaleString() }}
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
                                <td class="" colspan="3">
                                    &nbsp;
                                </td>
                                
                                <td class="">
                                    {{ totalPrice.toLocaleString() }}
                                </td>
                                <td class="">
                                    &nbsp;
                                </td>
                            </tr>
                        </div>

                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <button class="add-btn" @click="createPurchaseOrderBtnClicked">
                Create Purchase Order
            </button>
        </div>
    </div>
</template>

<script>
    import { getApiData, postApiData } from '../../utilities/ajax-helpers';
    import { getCurrentDate } from '../../utilities/datetime-helpers';
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                date: getCurrentDate(),
                itemList: [],
                selectedItem: null,
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

            alertValidationMessage(field){
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to prvide ${field}, please try again`,
                    type: 'warn'
                });
            },

            addItemBtnClicked(){
                if(!this.selectedItem){
                    this.alertValidationMessage('an item');
                    return 1;
                }
                if(this.quantity < 1){
                    this.alertValidationMessage('quantity');
                    return 1;
                }
                let amount = (this.selectedItem.item_prices)? this.selectedItem.item_prices.price: 0;
                this.purchaseOrderItems.push({
                    item_id: this.selectedItem.id,
                    name: this.selectedItem.name,
                    quantity: this.quantity,
                    amount: amount,
                });

                this.updateTotalPrice(this.purchaseOrderItems);
                this.selectedItem = null;
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
        },

        mounted(){

        }
    }
</script>
