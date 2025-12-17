<template>
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Used Defected Items
                </p>
            </div>
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
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                                <tr class="">
                                    <td class="  ">
                                        {{ perPage * (currentPage - 1) + (++itemIndex) }}
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
                                        <div v-if="item.is_confirmed == 1">Yes</div>
                                        <div v-else>No</div>
                                    </td>

                                    <td class="whitespace-nowrap ">
                                        <button id="edit-btn" class="pr-1" :disabled="item.is_confirmed == 1">
                                            <i class="fas fa-check" @click="checkBtnClicked(item)"
                                                data-te-toggle="modal" data-te-target="#checkModal"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="itemList.length < 1 && !loading">
                                <td class="" colspan="8">
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
                                @click="getItemList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
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
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmCheckBtnClicked" type="button" data-te-toggle="modal"
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
import { Modal, Ripple, initTE, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, putApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { convertToFriendlyDate } from '../../utilities/datetime-helpers';
import { mapGetters } from "vuex";
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            itemList: [],
            confirmItem: null,
            confirmItemId: null,
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            loading: true,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getItemList(pageNumber) {
            this.loading = true;
            let url = `/api/used_defected_items?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.itemList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
                this.itemList.forEach((item) => {
                    item.date = convertToFriendlyDate(item.date);
                });

            }
        },

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        checkBtnClicked(item) {
            this.confirmItem = item;
            this.confirmItemId = item.id;
        },

        async confirmCheckBtnClicked() {
            let url = `/api/used_defected_items/${this.confirmItemId}/confirm`;
            let response = await postApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Used defected item confirmed`,
                    type: "info"
                });
            }
            else {
                this.$notify({
                    text: `Used defected item confirmation error`,
                    type: "error"
                });
            }
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.searchCategory = null;
            this.getItemList(1);
        },


    },

    created() {
        this.getItemList(1);
    },

    mounted() {
        initTE({ Modal, Ripple, Select, Dropdown });
    }
}
</script>
