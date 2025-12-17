<template>
    
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Inventory Receives
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex">
                    <div>
                        <label for="search" class="search-input mx-2 px-2 py-1"> From Date </label>
                        <input type="date" v-model="fromDate" class="search-input rounded">
                    </div>

                    <div>
                        <label for="search" class="search-input mx-2 px-2 py-1"> To Date </label>
                        <input type="date" v-model="toDate" class="search-input rounded">
                    </div>
                    <div class="ml-2 px-2">
                        <button class="mx-1 add-btn h-8 text-[13px] font-inter" @click="searchBtnClicked">Filter</button>
                        <button class="mx-1 add-btn h-8 text-[13px] font-inter"
                            @click="clearSearchBtnClicked">Clear</button>
                    </div>
                </div>
                <div class="flex justify-end flex-col">

                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container ">
                    <table class=" primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Transfer Id
                                </th>
                                <th scope="col" class="  ">
                                    Date
                                </th>
                                <th scope="col" class="  ">
                                    Source Inventory
                                </th>
                                <th scope="col" class="  ">
                                    Destination Inventory
                                </th>
                                <th scope="col" class="  ">
                                    Item
                                </th>
                                <th scope="col" class="  ">
                                    Quantity
                                </th>
                                <th scope="col" class="  ">
                                    UOM
                                </th>
                                <th scope="col" class="  ">
                                    Trasnferred By
                                </th>
                                <th scope="col" class="  ">
                                    Status
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(receive, index) in receivesList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ receive.transfer_id }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ receive.date }}
                                    </td>
                                    <td class="  ">
                                        {{ receive.source_inventory.name }}
                                    </td>
                                    <td class="  ">
                                        {{ receive.destination_inventory.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ receive.item.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ receive.quantity }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ receive.uom.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ receive.created_by.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ receive.status }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button class="mx-1" data-te-toggle="modal" data-te-target="#confirmModal"
                                            :disabled="receive.status != 'pending'"
                                            @click="receiveBtnClicked(receive.id)">
                                            <i class="fal fa-check"></i>
                                        </button>
                                        <button class="mx-1" data-te-toggle="modal" data-te-target="#cancelModal"
                                            :disabled="receive.status != 'pending'"
                                            @click="cancelReceiveBtnClicked(receive.id)">
                                            <i class="fal fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="receivesList.length < 1 && !loading">
                                <td class="" colspan="11">
                                    No Data Here
                                </td>
                            </tr>

                            <!-- looping end -->
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getInventoryReceivesList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getInventoryReceivesList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!--Check Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
            min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Confirm Receive
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
                    <button @click="confirmReceiveBtnClicked" type="button" data-te-toggle="modal"
                        data-te-target="#confirmModal"
                        class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--Check Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="cancelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
            min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Cancel Receive
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
                    <button @click="confirmCancelReceiveBtnClicked" type="button" data-te-toggle="modal"
                        data-te-target="#cancelModal"
                        class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { convertToFriendlyDate } from '../../utilities/datetime-helpers';
import { mapGetters } from "vuex";
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            receivesList: [],
            receiveId: null,

            fromDate: null,
            toDate: null,
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
            loading: true,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getInventoryReceivesList(pageNumber) {
            this.loading = true;
            if (pageNumber) {
                this.currentPage = pageNumber;
            }
            let url = `/api/transfer_confirmation_list?page=${pageNumber}`;
            if (this.fromDate && this.toDate) {
                url = `${url}&from_date=${this.fromDate}&to_date=${this.toDate}`;
            }
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.receivesList = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;

            }
        },

        receiveBtnClicked(id) {
            this.receiveId = id;
        },

        async confirmReceiveBtnClicked() {
            let url = `/api/confirm_transfer_item?id=${this.receiveId}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.receivesList = [];
                this.getInventoryReceivesList(1);
            }
        },

        cancelReceiveBtnClicked(id) {
            this.receiveId = id;
        },

        async confirmCancelReceiveBtnClicked() {
            let url = `/api/cancel_transfer_item?id=${this.receiveId}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.receivesList = [];
                this.getInventoryReceivesList(1);
            }
        },

        searchBtnClicked() {
            this.getInventoryReceivesList(1);
        },

        clearSearchBtnClicked() {
            this.fromDate = null;
            this.toDate = null;
            this.getInventoryReceivesList(1);
        },


    },

    created() {
        this.getInventoryReceivesList(1);
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
