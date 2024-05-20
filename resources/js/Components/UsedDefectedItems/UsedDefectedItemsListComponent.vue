<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Used Defected Items
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>

                <!-- <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
            <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button> -->

            </div>
            <div class="flex justify-end flex-col">
                <!-- <div class="flex gap-3">
                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#uom">
                        Create Uom
                    </button>
                </div> -->
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>

                                <th scope="col" class="  ">
                                    Date
                                </th>

                                <th scope="col" class="  ">
                                    Name
                                </th>

                                <th scope="col" class="  ">
                                    Quantity
                                </th>

                                <th scope="col" class="  ">
                                    Type
                                </th>

                                <th scope="col" class="  ">
                                    Remark
                                </th>

                                <th scope="col" class="  ">
                                    Is Confirmed?
                                </th>

                                <th scope="col" class="">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                                <tr class="">
                                    <td class="  ">
                                        {{ per_page * (currentPage - 1) + (++itemIndex) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ item.date }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        {{ item.item.name }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        {{ item.quantity }} {{ item.uom.name }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        {{ item.type }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        {{ item.remark }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        <div v-if="item.is_confirmed == 1" >Yes</div>
                                        <div v-else >No</div>
                                    </td>

                                    <td class="whitespace-nowrap ">
                                        <button id="edit-btn" class="pr-1" :disabled="item.is_confirmed == 1" >
                                            <i class="fas fa-check" @click="checkBtnClicked(item)"
                                                data-te-toggle="modal" data-te-target="#checkModal"></i>
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
                        Check Used Defected Item
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
                <div class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmCheckBtnClicked" type="button" data-te-toggle="modal" data-te-target="#checkModal"
                    class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 " >
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, putApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { convertToFriendlyDate } from '../../utilities/datetime-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            itemList: [],
            confirmItem: null,
            confirmItemId: null,

            per_page: 20,
            currentPage: 1,
            pageNumbers: [],
            paginationGroupsCount: 1,
            groupedPageNumbers: [],
            currentGroup: 0,
            isFirstGroup: true,
            isLastGroup: false,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getItemList(pageNumber) {
            if (pageNumber) {
                this.currentPage = pageNumber;
            }
            let url = `/api/used_defected_items?page=${this.currentPage}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.itemList = response.data.used_defected_items;
                this.itemList.forEach((item)=>{
                    item.date = convertToFriendlyDate(item.date);
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

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        checkBtnClicked(item){
            this.confirmItem = item;
            this.confirmItemId = item.id;
        },

        async confirmCheckBtnClicked(){
            let url = `/api/used_defected_items/${this.confirmItemId}/confirm`;
            let response = await postApiData({url: url, token: this.getToken()});
            if(response.success){
                this.$notify({
                    text: `Used defected item confirmed`,
                    type: "info"
                });
            }
            else{
                this.$notify({
                    text: `Used defected item confirmation error`,
                    type: "error"
                });
            }
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.searchCategory = null;
            this.getItemList(null);
        },

        pageBtnClicked(pageNumber) {
            this.currentPage = pageNumber;
            this.getItemList(this.currentPage);
        },

        nextPaginationGroupBtnClicked() {
            this.currentGroup += 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getItemList(this.currentPage);
        },

        previousPaginationGroupBtnClicked() {
            this.currentGroup -= 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getItemList(this.currentPage);
        },

        firstPaginationGroupBtnClicked() {
            this.currentGroup = 0;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getItemList(this.currentPage);
        },

        lastPaginationGroupBtnClicked() {
            this.currentGroup = this.paginationGroupsCount - 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getItemList(this.currentPage);
        }
    },

    created() {
        this.getItemList(null);
    },

    mounted() {
        initTE({ Modal, Ripple, Select, Dropdown });
    }
}
</script>
