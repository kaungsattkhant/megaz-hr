<template>
    <div class="px-0">
        <div class="mb-4 mt-2">
            <p class="text-xl  text-black font-normal">
                Confirm Purchase Order
            </p>
        </div>

        <div class="grid grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date
                </label>
                <input type="date" v-model="date" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div><div class="col-span-9"></div>

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

        <div class=" bg-white p-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="">
                                Item
                            </th>
                            <th scope="col" class="">
                                Uom
                            </th>
                            <th scope="col" class="">
                                Original Qty
                            </th>
                            <th scope="col" class="">
                                Current Qty
                            </th>
                            <th scope="col" class="">
                                Left Qty
                            </th>
                            <th scope="col" class="">
                                Amount
                            </th>
                            <th scope="col" class="">
                                Total
                            </th>
                            <th scope="col" class="  ">
                                Manager Checked
                            </th>
                            <th scope="col" class="  ">
                                Financial Checked
                            </th>
                            <th scope="col" class="  ">
                                MD Checked
                            </th>
                            <th scope="col" class="" colspan="3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(purchaseOrderItem, purchaseOrderItemsIndex) in purchaseOrderItems" :key="purchaseOrderItemsIndex">
                            <tr class="">
                                <td class="">
                                    {{ purchaseOrderItem.item.name }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.uom.name }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.original_quantity }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.quantity }}
                                </td>
                                <td class="">
                                    <div v-if="purchaseOrderItem.purchase_order_item_left">
                                        {{ purchaseOrderItem.purchase_order_item_left.quantity }}
                                    </div>
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.amount.toLocaleString() }}
                                </td>
                                <td class="">
                                    {{ (purchaseOrderItem.amount * purchaseOrderItem.quantity).toLocaleString() }}
                                </td>
                                <td  class="">
                                    {{ purchaseOrderItem.is_manager_checked == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.is_financial_checked == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td class="">
                                    {{ purchaseOrderItem.is_md_checked == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td class=" ">
                                    <button @click="editPurchaseOrderItemBtnClicked(purchaseOrderItemsIndex)"
                                    data-te-toggle="modal" data-te-target="#editModal">
                                        <i class="fal fa-pencil  pr-3"></i>
                                    </button>
                                </td>
                                <td class=" ">
                                    <button @click="checkPurchaseOrderItemBtnClicked(purchaseOrderItem.id)" :disabled="purchaseOrderItem.id == null"
                                    data-te-toggle="modal" data-te-target="#checkModal">
                                        <i class="fal fa-check  pr-3"></i>
                                    </button>
                                </td>
                                <td class=" ">
                                    <!-- <button @click="editPurchaseOrderItemBtnClicked(purchaseOrderItemsIndex)" data-te-toggle="modal" data-te-target="#editModal">
                                        <i class="fal fa-pencil  pr-3"></i>
                                    </button>
                                    <button @click="checkPurchaseOrderItemBtnClicked(purchaseOrderItem.id)" :disabled="purchaseOrderItem.id == null"
                                    data-te-toggle="modal" data-te-target="#checkModal">
                                        <i class="fal fa-check  pr-3"></i>
                                    </button> -->
                                    <button @click="removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex)"
                                    data-te-toggle="modal" data-te-target="#deleteModal">
                                        <i class="fal fa-times pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                        </div>
                        <div class="contents">
                            <tr class="">
                                <td class=" ">
                                    &nbsp;
                                </td>
                                <td class=" ">
                                    &nbsp;
                                </td>
                                <td class=" ">
                                    &nbsp;
                                </td>
                                <td class=" ">
                                    &nbsp;
                                </td>
                                <td class=" ">
                                    &nbsp;
                                </td>
                                <td class="">
                                    {{ totalPrice.toLocaleString() }}
                                </td>
                                <td class="  ">
                                    &nbsp;
                                </td>
                                <td class="  ">
                                    &nbsp;
                                </td>
                                <td class="  ">
                                    &nbsp;
                                </td>
                                <td class="  ">
                                    &nbsp;
                                </td>
                                <td class="  ">
                                    &nbsp;
                                </td>
                                <td class="  ">
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
                Update Purchase Order
            </button>
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

    <!-- Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="editModal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative  p-4">
                    <!--Modal title-->
                    <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                        Edit
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
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Quantity
                        </label>
                        <input type="number" placeholder="Quantity" v-model="editQuantity" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div v-if="!(getDepartment().name == 'HR' && isStaff)" class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
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
                    <button type="button" @click="confirmEditPurchaseOrderItemBtnClicked" class="add-btn focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#editModal">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--Check Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="checkModal"
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
                        Check Item
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
                    <button @click="confirmCheckPurchaseOrderItemBtnClicked" type="button" data-te-toggle="modal" data-te-target="#checkModal"
                    class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Modal, initTE } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

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
                deleteId: null,
                deleteIndex: null,
                checkId: null,
                isManager: false,
                isMD: false,
                isStaff: false,

                isLaterBuy: false,
                editQuantity: 0,
                editPurchaseOrderItem: null,

                totalPrice: 0,
            };
        },

        methods: {
            ...mapGetters(['getToken','getUser', 'getRoles', 'getDepartment']),

            async getItemList(){
                let response = await getApiData({url: `/api/items`, token: this.getToken()});
                if(response.data){
                    this.itemList = response.data;
                }
            },

            async getPurchaseOrder(){
                let response = await getApiData({url: `/api/purchase_orders/${this.purchaseOrderId}`, token: this.getToken()});
                if(response.data){
                    this.purchaseOrder = response.data;
                    this.date = this.purchaseOrder.date;
                    this.purchaseOrderItems = this.purchaseOrder.items;
                    this.updateTotalPrice(this.purchaseOrderItems);
                }
            },

            alertValidationMessage(field){
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to provide ${field}, please try again`,
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
                this.updateTotalPrice(this.purchaseOrderItems);
                this.selectedItem = null;
                this.quantity = null;
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

            checkPurchaseOrderItemBtnClicked(purchaseOrderItemId){
                this.checkId = purchaseOrderItemId;
            },

            async confirmCheckPurchaseOrderItemBtnClicked(){
                if(this.checkId){
                    let url = `/api/updateIsCheck`;
                    let formData = new FormData();
                    formData.append('type', 'purchase_order_item');
                    formData.append('id',this.checkId);
                    formData.append('value', 1);
                    let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                    if(response.success){
                        this.$notify({
                            text: `Item checked`,
                            type: 'info'
                        });
                        window.location.reload();
                    }
                }

                this.checkId = null;
            },

            editPurchaseOrderItemBtnClicked(purchaseOrderItemsIndex){
                this.editPurchaseOrderItem = this.purchaseOrderItems[purchaseOrderItemsIndex];
                console.log(this.editPurchaseOrderItem);
                if(this.editPurchaseOrderItem.later_buy == 1){
                    this.isLaterBuy = true;
                }
                else{
                    this.isLaterBuy = false;
                }
                this.editQuantity = this.editPurchaseOrderItem.quantity;
            },

            laterBuyToggled(){
                // alert(this.isLaterBuy);
            },

            confirmEditPurchaseOrderItemBtnClicked(){
                let index = this.purchaseOrderItems.findIndex(poItem => poItem.id == this.editPurchaseOrderItem.id);
                if(index != -1){
                    this.purchaseOrderItems[index].quantity = this.editQuantity;
                    this.purchaseOrderItems[index].later_buy = (this.isLaterBuy)? 1: 0;
                    console.log(this.purchaseOrderItems[index]);
                }

                this.editQuantity = 0;
                this.editPurchaseOrderItem = null;
                this.isLaterBuy = false;
            },

            async createPurchaseOrderBtnClicked(){
                if(!this.date){
                    this.alertValidationMessage(`date`);
                    return 1;
                }
                if(this.purchaseOrderItems.length<1){
                    this.alertValidationMessage(`an item`);
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

                let isGRN = '0';
                isGRN = parseInt(isGRN);

                let formData = new FormData();
                formData.append('id', this.purchaseOrderId);
                // formData.append('po_id', this.purchaseOrder.id);
                formData.append('date', this.date);
                formData.append('total_price', priceTotal);
                formData.append('is_grn', isGRN);
                formData.append('items', JSON.stringify(updatedPurchaseOrderItems));
                let response = await postApiData({url: `/api/purchase_orders`, form_data:  formData, token: this.getToken()});
                if(response.success){
                    this.$notify({
                        text: `Purchase order update success`,
                        type: 'info'
                    });

                    window.location.replace(`/purchase_orders`);
                }
                else{
                    this.$notify({
                        text: `Purchase order update error`,
                        type: 'error'
                    });
                }
                // window.location.replace(`/purchase_orders`);
            },

            updateTotalPrice(poItems){
                this.totalPrice = 0;
                poItems.forEach((item)=>{
                    this.totalPrice += (item.amount * item.quantity);
                });
            },

        },

        watch: {
            purchaseOrderItems: function(){
                // alert('PO items changed');
            },
        },

        created(){
            this.getRoles().forEach((role)=>{
                if(role.name == 'Manager'){
                    this.isManager = true;
                }
                if(role.name == 'MD'){
                    this.isMD = true;
                }
                if(role.name == 'Staff'){
                    this.isStaff = true;
                }
            });
            this.getPurchaseOrder();
            this.getItemList();
        },

        mounted(){
            initTE({Modal});
        }
    }
</script>
