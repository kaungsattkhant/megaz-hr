<template>
    <div class="flex justify-between mb-3">

    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Date
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Purchase Order Id
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Item Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Uom
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Quantity
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Received?
                            </th>
                            <th scope="col" class="px-6 py-4 ">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(item, index) in itemList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ item.purchase_order.date }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ item.purchase_order.po_id }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ item.item.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ item.uom.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ item.quantity }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ (item.is_confirmed == 1) ? "Yes" : "No" }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 space-x-4">
                                    <button data-te-toggle="modal" data-te-target="#checkModal"
                                        @click="checkPurchaseOrderItemBtnClicked(item.id)"
                                        :disabled="item.is_confirmed == 1">
                                        <i class="far fa-check"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>
                    </tbody>
                </table>
                <!-- pagination -->
                <div class="flex justify-center">

                    <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                        <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                            @click="getItemList(currentPage - 1)">«</button>

                        <button class=" text-sm px-5 border">
                            Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                lastPage }}</span>
                        </button>

                        <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                            :disabled="currentPage === lastPage" @click="getItemList(currentPage + 1)">
                            »</button>
                    </div>
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
                        Received Purchase Order Item?
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
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmCheckPurchaseOrderItemBtnClicked" type="button" data-te-toggle="modal"
                        data-te-target="#checkModal"
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
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { getCurrentDate } from '../../utilities/datetime-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            itemList: [],
            checkId: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getItemList(pageNumber) {

            let url = `/api/purchase_order_item_confirmation_list?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.itemList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        checkPurchaseOrderItemBtnClicked(id) {
            this.checkId = id;
        },

        async confirmCheckPurchaseOrderItemBtnClicked() {
            if (this.checkId) {
                let url = `/api/confirm_purchase_order_item?id=${this.checkId}`;
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.success) {
                    this.$notify({
                        text: `Item received`,
                        type: 'info'
                    });
                    this.getItemList(1);
                }
            }

            this.checkId = null;
        }
    },

    created() {
        this.getItemList(1);
    },

    mounted() {
        initTE({ Modal });
    }
}
</script>
