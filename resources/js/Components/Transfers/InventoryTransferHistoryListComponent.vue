<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Inventory Transfer History
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">

            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Transfer Id
                                </th>
                                <th scope="col" class="  ">
                                    Created Date
                                </th>
                                <th scope="col" class="  ">
                                    Confirmed Date
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
                                    Trasnferred By
                                </th>
                                <th scope="col" class="  ">
                                    Received By
                                </th>
                                <!-- <th scope="col" class="px-6 py-4">

                            </th> -->
                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(transfer, index) in transferList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ per_page * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ transfer.transfer_id }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ transfer.date }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ transfer.confirmed_date }}
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
                                        {{ transfer.created_by.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="transfer.confirmed_by"> {{ transfer.confirmed_by.name }} </div>
                                    </td>
                                </tr>
                            </div>

                            <!-- looping end -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 ml-2">
                    <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                        <li v-if="!isFirstGroup">
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                @click="previousPaginationGroupBtnClicked" :disabled="isFirstGroup">
                                Previous
                            </button>
                        </li>

                        <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]"
                            :key="pageNumberIndex" :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                            <button v-if="pageNumber == currentPage"
                                class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                                <span
                                    class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                    (current)
                                </span>
                            </button>
                            <button v-else
                                class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                            </button>
                        </li>
                        <li v-if="!isLastGroup">
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                @click="nextPaginationGroupBtnClicked" :disabled="isLastGroup">
                                Next
                            </button>
                        </li>
                    </ul>

                    <ul v-else class="list-style-none flex">
                        <li v-for="(pageNumber, pageNumberIndex) in pageNumbers" :key="pageNumberIndex"
                            :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                            <button v-if="pageNumber == currentPage"
                                class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                                <span
                                    class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                    (current)
                                </span>
                            </button>
                            <button v-else
                                class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                            </button>
                        </li>
                    </ul>
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
            transferList: [],
            transferId: null,

            per_page: 20,
            pageNumbers: [],
            currentPage: 1,
            paginationGroupsCount: 1,
            per_group: 10,
            groupedPageNumbers: [],
            currentGroup: 0,
            isFirstGroup: true,
            isLastGroup: false,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getInventoryTransferHistoryList(pageNumber) {
            if (pageNumber) {
                this.currentPage = pageNumber;
            }
            let url = `/api/transfer_confirmation_list?status=complete&page=${this.currentPage}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.transferList = response.data.data;
                console.log(this.transferList);
                this.transferList.forEach((transfer) => {
                    transfer.date = convertToFriendlyDate(transfer.date);
                    if(transfer.confirmed_at){
                        transfer.confirmed_date = convertToFriendlyDate(transfer.confirmed_at);
                    }
                });
                this.per_page = response.data.per_page;

                this.pageNumbers = [];
                this.lastPageNumber = response.data.last_page;

                for (let i = 1; i <= response.data.last_page; i++) {
                    this.pageNumbers.push(i);
                }

                if (this.pageNumbers.length > 10) {
                    this.groupedPageNumbers = [];
                    this.paginationGroupsCount = this.pageNumbers.length % 10;
                    for (let i = 0; i < this.pageNumbers.length; i += 10) {
                        let chunk = this.pageNumbers.slice(i, i + 10);
                        this.groupedPageNumbers.push(chunk);
                    }

                    let lastGroupIndex = this.groupedPageNumbers.length - 1;
                    this.isFirstGroup = (this.currentGroup === 0);
                    this.isLastGroup = (lastGroupIndex === this.currentGroup);
                }
            }
        },

        pageBtnClicked(pageNumber) {
            this.currentPage = pageNumber;
            this.getInventoryTransferHistoryList(this.currentPage);
        },

        nextPaginationGroupBtnClicked() {
            this.currentGroup += 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getInventoryTransferHistoryList(this.currentPage);
        },

        previousPaginationGroupBtnClicked() {
            this.currentGroup -= 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getInventoryTransferHistoryList(this.currentPage);
        },

        firstPaginationGroupBtnClicked() {
            this.currentGroup = 0;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getInventoryTransferHistoryList(this.currentPage);
        },

        lastPaginationGroupBtnClicked() {
            this.currentGroup = this.paginationGroupsCount - 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getInventoryTransferHistoryList(this.currentPage);
        }
    },

    created() {
        this.getInventoryTransferHistoryList(null);
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
