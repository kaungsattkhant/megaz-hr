<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                Confirm Purchase Order
            </p>
        </div>

        <div class="grid grid-rows-3 grid-cols-12 grid-flow-col gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 row-span-2 col-span-12 w-48 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date
                </label>
                <input type="date" v-model="date" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    Item Name
                </label>
                <select name="" id="" v-model="selectedItem"
                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex" > {{ item.name }} </option>
                </select>

            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    Qty
                </label>
                <input type="number" v-model="quantity" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0" placeholder="Qty">
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    &nbsp;
                </label>
                <button class="add-btn" @click="addItemBtnClicked"> Add </button>
            </div>

        </div>

        <div class="grid grid-rows-3 grid-flow-col gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <table class="w-full primary-table rounded-xl text-center text-sm font-light ">
                <thead class="border-b font-medium ">
                    <tr>
                        <th scope="col" class=" px-6 py-4 ">
                            Item
                        </th>
                        <th scope="col" class=" px-6 py-4 ">
                            Qty
                        </th>
                        <th scope="col" class=" px-6 py-4 ">
                            Amount
                        </th>
                        <th scope="col" class=" px-6 py-4 ">
                            Total
                        </th>
                        <th scope="col" class=" px-6 py-4 ">

                        </th>
                    </tr>
                </thead>
                <tbody>
                    <div class="contents" v-for="(purchaseOrderItem, purchaseOrderItemsIndex) in purchaseOrderItems" :key="purchaseOrderItemsIndex">
                        <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                            <td class=" px-6 py-4 font-medium ">
                                {{ purchaseOrderItem.item.name }}
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                {{ purchaseOrderItem.quantity }}
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                {{ purchaseOrderItem.amount.toLocaleString() }}
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                {{ (purchaseOrderItem.amount * purchaseOrderItem.quantity).toLocaleString() }}
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                <button @click="removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex)">
                                    <i class="fal fa-pencil  pr-3"></i>
                                </button>
                                <button @click="removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex)">
                                    <i class="fal fa-check  pr-3"></i>
                                </button>
                                <button @click="removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex)">
                                    <i class="fal fa-times  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="">
                            <td class=" py-2 "></td>
                        </tr>
                    </div>

                </tbody>
            </table>
        </div>
        <div>
            <button class="add-btn" @click="createPurchaseOrderBtnClicked">
                Create Purchase Order
            </button>
        </div>
    </div>
</template>

<script>
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';

    export default {
        props: ['purchaseOrderId'],
        data() {
            return {
                purchaseOrder: null,
                date: null,
                itemList: [],
                selectedItem: null,
                quantity: null,
                purchaseOrderItems: [],
            };
        },

        methods: {
            async getItemList(){
                let response = await getApiData({url: `/api/items`});
                if(response.data){
                    this.itemList = response.data;
                }
            },

            async getPurchaseOrder(){
                let response = await getApiData({url: `/api/purchase_orders/${this.purchaseOrderId}`});
                if(response.data){
                    this.purchaseOrder = response.data;
                    this.date = this.purchaseOrder.date;
                    this.purchaseOrderItems = this.purchaseOrder.items;
                }
            },

            addItemBtnClicked(){
                if(!this.selectedItem){
                    alert('Choose an item first');
                    return 1;
                }
                if(this.quantity < 1){
                    alert('Input quantity');
                    return 1;
                }
                let existingItemIndex = this.purchaseOrderItems.findIndex(poItem => poItem.item_id == this.selectedItem.id);
                if(existingItemIndex != -1){
                    this.purchaseOrderItems[existingItemIndex].quantity = +this.quantity;
                }
                else{
                    let amount = (this.selectedItem.item_prices)? this.selectedItem.item_prices.price: 0;
                    this.purchaseOrderItems.push({
                        item_id: this.selectedItem.id,
                        item: this.selectedItem,
                        quantity: this.quantity,
                        amount: amount,
                    });
                }
                this.selectedItem = null;
                this.quantity = null;
            },

            async removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex){
                console.log(this.purchaseOrderItems[purchaseOrderItemsIndex]);
                if(this.purchaseOrderItems[purchaseOrderItemsIndex].id){
                    let url = `/api/purchase_orders_items/${this.purchaseOrderItems[purchaseOrderItemsIndex].id}`;
                    let response = await deleteApiData({url: url});
                    if(response.success){
                        this.purchaseOrderItems.splice(purchaseOrderItemsIndex, 1);
                    }
                }
                else{
                    this.purchaseOrderItems.splice(purchaseOrderItemsIndex, 1);
                }
            },

            async createPurchaseOrderBtnClicked(){
                if(!this.date){
                    alert('Input date');
                    return 1;
                }
                if(this.purchaseOrderItems.length<1){
                    alert('Select items');
                    return 1;
                }
                let priceTotal = 0;
                this.purchaseOrderItems.forEach((purchaseOrderItem)=>{
                    priceTotal += purchaseOrderItem.amount;
                });
                let updatedPurchaseOrderItems = JSON.parse(JSON.stringify(this.purchaseOrderItems));
                updatedPurchaseOrderItems.forEach((orderItem)=>{
                    delete orderItem.item;
                    if(typeof orderItem.id === 'undefined'){
                        orderItem.id = null;
                    }
                });

                let formData = new FormData();
                formData.append('id', this.purchaseOrderId);
                formData.append('po_id', this.purchaseOrder.po_id);
                formData.append('date', this.date);
                formData.append('total_price', priceTotal);
                formData.append('items', JSON.stringify(updatedPurchaseOrderItems));
                let response = await postApiData({url: `/api/purchase_orders`, form_data:  formData});
                alert(`Operation success ${response.success}`);
            },

        },

        created(){
            this.getPurchaseOrder();
            this.getItemList();
        },

        mounted(){

        }
    }
</script>
