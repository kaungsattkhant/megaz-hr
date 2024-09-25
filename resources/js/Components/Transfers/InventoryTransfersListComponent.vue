<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Inventory Transfer
        </p>
    </div>
    <div class="mt-4 bg-white">

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
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container ">
                    <table class="primary-table ">
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
                                    Received By
                                </th>
                                <th scope="col" class="  ">
                                    Status
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(transfer, index) in transfersList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ transfer.transfer_id }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ transfer.date }}
                                    </td>
                                    <td class="  ">
                                        {{ transfer.source_inventory.name }}
                                    </td>
                                    <td class="  ">
                                        {{ transfer.destination_inventory.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ transfer.item.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ transfer.quantity }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ transfer.uom.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="transfer.confirmed_by">
                                            {{ transfer.confirmed_by.name }}
                                        </div>

                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ transfer.status }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        <!-- <button data-te-toggle="modal" data-te-target="#confirmModal" @click="transferBtnClicked(transfer.id)">
                                        <i class="fal fa-bars"></i>
                                    </button> -->
                                    </td>
                                </tr>
                            </div>

                            <!-- looping end -->
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getInventoryTransfersList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getInventoryTransfersList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
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

export default {
    data() {
        return {
            transfersList: [],
            transferId: null,

            fromDate: null,
            toDate: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getInventoryTransfersList(pageNumber) {

            let url = `/api/transfers?page=${pageNumber}`;
            if (this.fromDate && this.toDate) {
                url = `${url}&from_date=${this.fromDate}&to_date=${this.toDate}`;
            }
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.transfersList = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;

                this.transfersList.forEach((transfer) => {
                    transfer.date = convertToFriendlyDate(transfer.date);
                });

            }
        },

        searchBtnClicked() {
            this.getInventoryTransfersList(1);
        },

        clearSearchBtnClicked() {
            this.fromDate = null;
            this.toDate = null;
            this.getInventoryTransfersList(1);
        },

    },

    created() {
        this.getInventoryTransfersList(1);
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
