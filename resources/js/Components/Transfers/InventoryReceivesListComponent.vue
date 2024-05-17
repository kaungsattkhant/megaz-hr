<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Inventory Receives
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
                                    Trasnferred By
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
                            <div class="contents" v-for="(receive, index) in receivesList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ per_page * (currentPage - 1) + (++index) }}
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
                                        {{ receive.created_by.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ receive.status }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#confirmModal"
                                            :disabled=" receive.status != 'pending'"
                                            @click="receiveBtnClicked(receive.id)">
                                            <i class="fal fa-bars"></i>
                                        </button>
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
</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { convertToFriendlyDate } from '../../utilities/datetime-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            receivesList: [],
            receiveId: null,

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

        async getInventoryReceivesList(pageNumber) {
            if (pageNumber) {
                this.currentPage = pageNumber;
            }
            let url = `/api/transfer_confirmation_list?page=${this.currentPage}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.receivesList = response.data.data;
                this.receivesList.forEach((receive) => {
                    receive.date = convertToFriendlyDate(receive.date);
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

        receiveBtnClicked(id){
            this.receiveId = id;
        },

        async confirmReceiveBtnClicked(){
            let url = `/api/confirm_transfer_item?id=${this.receiveId}`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.success){
                this.receivesList = [];
                this.getInventoryReceivesList(this.currentPage);
            }
        },

        pageBtnClicked(pageNumber) {
            this.currentPage = pageNumber;
            this.getInventoryReceivesList(this.currentPage);
        },

        nextPaginationGroupBtnClicked() {
            this.currentGroup += 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getInventoryReceivesList(this.currentPage);
        },

        previousPaginationGroupBtnClicked() {
            this.currentGroup -= 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getInventoryReceivesList(this.currentPage);
        },

        firstPaginationGroupBtnClicked() {
            this.currentGroup = 0;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getInventoryReceivesList(this.currentPage);
        },

        lastPaginationGroupBtnClicked() {
            this.currentGroup = this.paginationGroupsCount - 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getInventoryReceivesList(this.currentPage);
        }
    },

    created() {
        this.getInventoryReceivesList(null);
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
