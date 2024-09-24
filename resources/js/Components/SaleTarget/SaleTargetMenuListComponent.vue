<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Sale Target Menu
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>

                <button class="add-btn h-8 text-[13px] font-inter">Search</button>
            </div>
            <div class="flex justify-end flex-col">
                <a href="/sale_target_menu/create" class="add-btn text-[13px] font-inter">
                    Add New
                </a>
            </div>
        </div>
        <div class="box-container-table">
            <div class=" overflow-x-auto">
                <div class=" table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class=" text-left">
                                    Month
                                </th>
                                <!-- <th scope="col" class="text-center">
                                    Category
                                </th>
                                <th scope="col" class=" text-left">
                                    Area
                                </th>
                                <th scope="col" class=" ">
                                    Target
                                </th> -->
                                <th scope="col" class=" ">
                                    Quantity
                                </th>
                                <th scope="col" class="">

                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(saleTarget, index) in saleTargetList" :key="index">
                                <tr class="">
                                    <td>
                                        {{ (currentPage - 1) * perPage + index + 1 }}
                                    </td>

                                    <td class="whitespace-nowrap text-left  ">
                                        {{ saleTarget.month }}
                                    </td>
                                    <!-- <td class="whitespace-nowrap text-left  ">
                                    </td>
                                    <td class="whitespace-nowrap text-left  ">
                                    </td>
                                    <td class="whitespace-nowrap text-left  ">
                                    </td> -->

                                    <td class="whitespace-nowrap text-center  ">
                                        {{ saleTarget.total_quantity }}
                                    </td>
                                    <td class="whitespace-nowrap   relative">
                                        <a :href="'/sale_target_menu/' + saleTarget.id + '/edit'" class="pr-3">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                        <button @click="deleteBtnClicked(saleTarget.id)" data-te-toggle="modal"
                                            data-te-target="#deleteModal" id="delete-btn" class="pr-1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" ms-4 bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded-s-xl px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1" @click="getSaleTargetMenuList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded-e-xl px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getSaleTargetMenuList(currentPage + 1)"> »</button>
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
                            <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                                Delete ?
                            </h5>
                            <button type="button"
                                class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative flex-auto p-4" data-te-modal-body-ref>
                            <p>
                                Are you sure ?
                            </p>
                        </div>
                        <div
                            class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                            <button type="button"
                                class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                                data-te-modal-dismiss>
                                Close
                            </button>
                            <button @click="confirmDeleteBtnClicked()" type="button" data-te-toggle="modal"
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
            saleTargetList: [],
            deleteId: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getSaleTargetMenuList(pageNumber) {
            let url = `/api/sale_target_menus?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.saleTargetList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total
            }
        },
        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        async confirmDeleteBtnClicked() {
            let url = `/api/sale_target_menus/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.getSaleTargetMenuList(1);
                this.deleteId = null;
            }
        }



    },

    created() {
        this.getSaleTargetMenuList(1);
    },

    mounted() {
        initTE({ Modal, Ripple, Input, Select, Dropdown })
    }
}
</script>
