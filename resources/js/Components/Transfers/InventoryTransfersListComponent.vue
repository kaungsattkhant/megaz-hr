<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">

                <i class="fal fa-search"></i>
            </label>
        </div>
        <div class="flex justify-end flex-col">

        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <div class="overflow ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Transfer Id
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Date
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Source Inventory
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Destination Inventory
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Item
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Quantity
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Received By
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- looping start -->
                        <div class="contents" v-for="(transfer, index) in transfersList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ per_page * (currentPage - 1) + (++index) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ transfer.transfer_id }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ transfer.date }}
                                </td>
                                <td class=" px-6 py-4 ">
                                    {{ transfer.source_inventory.name }}
                                </td>
                                <td class=" px-6 py-4 ">
                                    {{ transfer.destination_inventory.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ transfer.item.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ transfer.quantity }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    <div v-if="transfer.confirmed_by">
                                        {{ transfer.confirmed_by.name }}
                                    </div>

                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ transfer.status }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <!-- <button data-te-toggle="modal" data-te-target="#confirmModal" @click="transferBtnClicked(transfer.id)">
                                        <i class="fal fa-bars"></i>
                                    </button> -->
                                </td>
                            </tr>

                            <tr class="">
                                <td class=" py-2 "></td>
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

                    <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]" :key="pageNumberIndex"
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

        async getInventoryTransfersList(pageNumber) {
            if (pageNumber) {
                this.currentPage = pageNumber;
            }
            let url = `/api/transfers?page=${this.currentPage}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.transfersList = response.data.data;
                this.transfersList.forEach((transfer) => {
                    transfer.date = convertToFriendlyDate(transfer.date);
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
            this.getInventoryTransfersList(this.currentPage);
        },

        nextPaginationGroupBtnClicked() {
            this.currentGroup += 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getInventoryTransfersList(this.currentPage);
        },

        previousPaginationGroupBtnClicked() {
            this.currentGroup -= 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getInventoryTransfersList(this.currentPage);
        },

        firstPaginationGroupBtnClicked() {
            this.currentGroup = 0;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getInventoryTransfersList(this.currentPage);
        },

        lastPaginationGroupBtnClicked() {
            this.currentGroup = this.paginationGroupsCount - 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getInventoryTransfersList(this.currentPage);
        }
    },

    created() {
        this.getInventoryTransfersList(null);
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
