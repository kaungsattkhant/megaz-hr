<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Packages
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>
                <button class="add-btn h-8 text-[13px] font-inter" @click="searchBtnClicked()">Search</button>
                <button class="add-btn h-8 text-[13px] font-inter" @click="clearSearchBtnClicked()">Clear</button>
            </div>
            <div class="flex justify-end flex-col">
                <a href="/packages/create" class="add-btn text-[13px] font-inter">
                    Add New
                </a>

            </div>
        </div>
        <div class="box-container-table">
            <div class=" overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class=" table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class=" text-left">
                                    Package Name
                                </th>
                                <th scope="col" class="text-center  ">
                                    Image
                                </th>
                                <th scope="col" class=" text-left">
                                    Price
                                </th>
                                <th scope="col" class=" ">
                                    Item
                                </th>
                                <th scope="col" class="">

                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(promotionPackage, index) in promotionPackageList"
                                :key="index">
                                <tr class="">
                                    <td class=" ">
                                        {{ (currentPage - 1) * perPage + index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap text-left  ">
                                        {{ promotionPackage.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div
                                            class="relative flex border  text-center shrink-0 overflow-hidden rounded-md h-12 w-12">
                                            <img width="80" height="100" style="aspect-ratio: 4/3; object-fit: cover;"
                                                :src="promotionPackage.image_url" alt="Menu image">
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap text-left align-top">
                                        {{ (promotionPackage.price).toLocaleString() }}
                                    </td>
                                    <td class=" align-top">
                                        <div class="block mb-2" v-if="promotionPackage.menu_packages.length > 0">
                                            <span class="mr-2">
                                                Menus
                                            </span>
                                            : 
                                            <span v-for="menuPackage in promotionPackage.menu_packages"> {{
                                                menuPackage.menu.name }} {{ promotionPackage.menu_packages.length-1 < promotionPackage.menu_packages.length ? ',' : '' }}
                                            </span>
                                        </div>
                                        <div class="block mb-2">
                                            <!-- Accessories: <span v-for="accessory in promotionPackage.accessories"> {{
                                                accessory.menu.name }}, </span> -->
                                        </div>
                                        <div v-if="promotionPackage.rooms.length > 0">
                                            <span class="mr-2">
                                                Rooms
                                            </span>
                                            : 
                                             <span v-for="room in promotionPackage.rooms"> {{ room.name }},
                                            </span>
                                        </div>

                                    </td>
                                    <td class="whitespace-nowrap   relative">
                                        <!-- <a href="#" class="pr-2 ">
                                            <i class="fal fa-pen"></i>
                                        </a> -->
                                    </td>
                                </tr>
                            </div>


                            <!-- looping end -->
                        </tbody>
                    </table>
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1" @click="getPromotionPackageList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getPromotionPackageList(currentPage + 1)"> »</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--Delete Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                        <div
                            class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                            <!--Modal title-->
                            <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                                Delete ?
                            </h5>
                            <!--Close button-->
                            <button type="button"
                                class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
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
                            <button type="button"
                                class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                                data-te-modal-dismiss>
                                Close
                            </button>
                            <button @click="confirmDeleteBtnClicked" type="button" data-te-toggle="modal"
                                data-te-target="#deleteModal"
                                class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';

export default {
    data() {
        return {
            promotionPackageList: [],

            searchInput:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0

        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getPromotionPackageList(pageNumber) {
            let url = `/api/packages?page=${pageNumber}`;
            if(this.searchInput){
                url = '/api/packages?page=' + pageNumber + '&search=' + this.searchInput;
            }
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.promotionPackageList = response.data.data;
                this.lastPage = response.data.last_page;

                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total
            }
        },
        async searchBtnClicked() {
            this.getPromotionPackageList(1);
        },
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.getPromotionPackageList(1);
        },
    },

    created() {
        this.getPromotionPackageList(1);
    },

    mounted() {
        initTE({ Modal, Ripple, Input, Select, Dropdown })
    }
}
</script>
