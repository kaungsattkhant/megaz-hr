<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                Buy Purchase Order
            </p>
        </div>

        <div class="grid grid-rows-3 grid-cols-12 grid-flow-col gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 row-span-2 col-span-12 w-48 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date
                </label>
                <input type="date" v-model="date" disabled
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    Item Name
                </label>
                <select name="" id="" v-model="selectedItem" disabled
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex"> {{ item.name }}
                    </option>
                </select>
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    Qty
                </label>
                <input type="number" v-model="quantity" disabled
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                    placeholder="Qty">
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    &nbsp;
                </label>
                <button class="add-btn" disabled @click="addItemBtnClicked"> Add </button>
            </div>
        </div>

        <!-- <div>
            <label for="" class="block text-sm text-black mb-3">
                Item Name
            </label>
            <select name="" id="" v-model="selectedItem"
                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex"> {{ item.name }} </option>
            </select>
        </div> -->

        <div class="grid grid-rows-3 grid-flow-col gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <table class="w-full primary-table rounded-xl text-center text-sm font-light ">
                <thead class="border-b font-medium ">
                    <tr>
                        <th scope="col" class=" px-6 py-4 ">
                            Item
                        </th>
                        <th scope="col" class=" px-4 py-4 ">
                            Original Qty
                        </th>
                        <th scope="col" class=" px-4 py-4 ">
                            Current Qty
                        </th>
                        <th scope="col" class=" px-4 py-4 ">
                            Left Qty
                        </th>
                        <th scope="col" class=" px-6 py-4 ">
                            Amount
                        </th>
                        <th scope="col" class=" px-8 py-4 ">
                            Supplier
                        </th>
                        <th scope="col" class=" px-2 py-4 ">
                            Paid Amount
                        </th>
                        <th scope="col" class=" px-2 py-4 ">
                            Invoice Id
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <div class="contents" v-for="(purchaseOrderItem, purchaseOrderItemsIndex) in purchaseOrderItems"
                        :key="purchaseOrderItemsIndex">
                        <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                            <td class=" px-6 py-4 font-medium ">
                                {{ purchaseOrderItem.item.name }}
                            </td>
                            <td class=" px-4 py-4 font-medium ">
                                {{ purchaseOrderItem.original_quantity }}
                            </td>
                            <td class=" px-4 py-4 font-medium ">
                                {{ purchaseOrderItem.quantity }}
                            </td>
                            <td class=" px-4 py-4 font-medium ">
                                <div v-if="purchaseOrderItem.purchase_order_item_left">
                                    {{ purchaseOrderItem.purchase_order_item_left.quantity }}
                                </div>
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                {{ purchaseOrderItem.amount.toLocaleString() }}
                            </td>
                            <td class=" px-8 py-4 font-medium ">
                                <div>
                                    <select name="" id="" v-model="purchaseOrderItem.supplier"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                        @input="selectPOItemSupplierBtnClicked(purchaseOrderItem, purchaseOrderItemsIndex)">
                                        <option :value="poItemSupplier"
                                            v-for="(poItemSupplier, poItemSupplierIndex) in purchaseOrderItem.item.suppliers"
                                            :key="poItemSupplierIndex">
                                            {{ poItemSupplier.name }}
                                        </option>
                                    </select>
                                </div>
                            </td>
                            <td class=" px-2 py-4 font-medium ">
                                <input type="number" @input="validateNumberInput(purchaseOrderItemsIndex)"
                                    pattern="[0-9]" title="Please enter only numbers"
                                    v-model="purchaseOrderItem.invoice_amount"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    placeholder="Paid Amount">
                            </td>
                            <td class=" px-2 py-4 font-medium ">
                                <input type="text" v-model="purchaseOrderItem.invoice_no"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    placeholder="Invoice Id">
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
            <button class="add-btn" data-te-toggle="modal" data-te-target="#buyModal" @click="buyPurchaseOrderBtnClicked">
                Buy Purchase Order
            </button>
        </div>
    </div>

    <!--Check Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="buyModal"
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
                        Buy Purchase Order
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
                        Select cash account
                    </p>
                    <div>
                        <select name="" id="" v-model="selectedCashAccountId"
                            class="text-sm border border-gray-300 input-ui w-50 bg-transparent rounded-lg focus:ring-0">
                            <option :value="cashAccount.id" v-for="(cashAccount, cashAccountIndex) in cashAccountList" :key="cashAccountIndex"> {{ cashAccount.name }} </option>
                        </select>
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmBuyPurchaseOrderBtnClicked" type="button" data-te-toggle="modal" data-te-target="#checkModal"
                    class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, initTE, Select, Dropdown } from "tw-elements";
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

            supplierList: [],
            selectedSupplier: null,

            cashAccountList: [],
            selectedCashAccountId: null,

            editQuantity: 0,
            editPurchaseOrderItem: null,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getUser', 'getRoles', 'getDepartment']),

        async getItemList() {
            let response = await getApiData({ url: `/api/items`, token: this.getToken() });
            if (response.data) {
                this.itemList = response.data;
            }
        },

        async getCashAccountList() {
            let url = `/api/get_cash_account`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.cashAccountList = response.data;
            }
        },

        async getPurchaseOrder() {
            let response = await getApiData({ url: `/api/purchase_orders/${this.purchaseOrderId}`, token: this.getToken() });
            if (response.data) {
                this.purchaseOrder = response.data;
                this.date = this.purchaseOrder.date;
                this.purchaseOrderItems = this.purchaseOrder.items;
            }
        },

        selectPOItemSupplierBtnClicked(purchaseOrderItem, purchaseOrderItemsIndex) {
            // this.purchaseOrderItems[purchaseOrderItemsIndex].supplier_id = this.purchaseOrderItems[purchaseOrderItemsIndex].supplier.id;
            // console.log(this.purchaseOrderItems[purchaseOrderItemsIndex]);
        },

        addItemBtnClicked() {
            if (!this.selectedItem) {
                alert('Choose an item first');
                return 1;
            }
            if (this.quantity < 1) {
                alert('Input quantity');
                return 1;
            }
            let existingItemIndex = this.purchaseOrderItems.findIndex(poItem => poItem.item_id == this.selectedItem.id);
            if (existingItemIndex != -1) {
                this.purchaseOrderItems[existingItemIndex].quantity = +this.quantity;
            }
            else {
                let amount = (this.selectedItem.item_prices) ? this.selectedItem.item_prices.price : 0;
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

        buyPurchaseOrderBtnClicked(){

        },

        async confirmBuyPurchaseOrderBtnClicked() {
            let priceTotal = 0;
            let stop = true;
            if(!this.selectedCashAccountId){
                this.$notify({
                    text: `Cash account must be selected`,
                    type: 'error'
                });
                stop = true;
                return 1;
            }

            this.purchaseOrderItems.forEach((poItem) => {
                if (poItem.supplier == undefined || !poItem.supplier) {
                    this.$notify({
                        text: `Supplier must be selected for ${poItem.item.name}`,
                        type: 'error'
                    });
                    stop = true;
                    return 1;
                }

                else if (poItem.invoice_no == undefined || !poItem.invoice_no) {
                    this.$notify({
                        text: `Invoice number must be filled for ${poItem.item.name}`,
                        type: 'error'
                    });
                    stop = true;
                    return 1;
                }

                else if (poItem.invoice_amount == undefined || !poItem.invoice_amount) {
                    this.$notify({
                        text: `Invoice amount must be filled for ${poItem.item.name}`,
                        type: 'error'
                    });
                    stop = true;
                    return 1;
                }

                else {
                    poItem.supplier_id = poItem.supplier.id;
                    priceTotal += poItem.invoice_amount;
                    stop = false;
                }
            });

            // console.log(this.purchaseOrderItems);
            console.log(stop);
            // return 1;
            let poItems = JSON.parse(JSON.stringify(this.purchaseOrderItems));
            poItems.forEach((poItem) => {
                delete poItem.suppliers;
                delete poItem.supplier;
                console.log(poItem);
            });
            // console.log(poItems);
            // return 1;
            let isGRN = '1';
            // console.log(typeof(isGRN));
            isGRN = parseInt(isGRN);
            // console.log(typeof(isGRN));
            // return 0;
            if (!stop) {
                let formData = new FormData();
                formData.append('id', this.purchaseOrder.id);
                formData.append('date', this.date);
                formData.append('total_price', priceTotal);
                formData.append('is_grn', isGRN);
                formData.append('cash_account_id', this.selectedCashAccountId);
                formData.append('items', JSON.stringify(poItems));
                let response = await postApiData({ url: `/api/purchase_orders`, form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.$notify({
                        text: `Request successful`,
                        type: 'info'
                    });

                    window.location.replace(`/purchase_orders`);
                }
                else {
                    this.$notify({
                        text: `Unknown error`,
                        type: 'error'
                    });
                }
            }
            else {
                this.$notify({
                    text: `Please check your input`,
                    type: 'warn'
                });
            }
        },

        validateNumberInput(purchaseOrderItemsIndex) {
            // this.purchaseOrderItems[purchaseOrderItemsIndex].invoice_amount = this.purchaseOrderItems[purchaseOrderItemsIndex].invoice_amount.replace(/[^0-9]/g, "");
        }
    },

    created() {
        this.getRoles().forEach((role) => {
            if (role.name == 'Manager') {
                this.isManager = true;
            }
            if (role.name == 'MD') {
                this.isMD = true;
            }
        });
        this.getPurchaseOrder();
        this.getItemList();
        this.getCashAccountList();
    },

    mounted() {
        initTE({ Modal, Select, Dropdown });
    }
}
</script>
