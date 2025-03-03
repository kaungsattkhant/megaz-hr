<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Menu Area
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
                <!-- <a href="/warning/create" class="add-btn ">
                    Add New
                </a> -->
                <div class="w-full multiselect-fontsize" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Selling Area" v-model="selectedSellingArea"
                    data-te-select-filter="true" @change="selectedSellingAreaChanged()" class="input-ui w-full !text-sm">
                        <!-- <option value="all">All</option> -->
                        <option v-for="(sellingArea,index) in sellingAreaList" :key="index" :value="sellingArea"> {{ sellingArea.name }} </option>
                    </select>
                </div>

            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class=" ">
                                    #
                                </th>
                                <th scope="col" class=" ">
                                    Menu Category
                                </th>
                                <th scope="col" class=" ">
                                    Cooking Area
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(menuArea, menuAreaIndex) in menuAreaList" :key="menuAreaIndex">
                                <tr class="">
                                    <td class="  font-medium ">
                                        {{ perPage * (currentPage - 1) + (++menuAreaIndex) }}

                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ menuArea.menu_category.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ menuArea.selling_area.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div class="w-full" data-te-select-wrapper-ref>
                                            <select data-te-select-init data-te-select-placeholder="Select Area" :id="'test'+menuAreaIndex"
                                            data-te-select-filter="true" @change="selectedCookingAreaChanged($event,menuAreaIndex)" class="input-ui w-full select-box">
                                                <option v-for="(area, index) in menuArea.menu_areas" :key="index" :value="area.id" :selected="area.is_default == 1">
                                                    {{ area.cooking_area.name }}
                                                </option>
                                            </select>
                                        </div>
                                        <!-- <select class="text-xs pl-0 border-0 focus:shadow-none focus:outline-none focus:ring-0 select-box area-select-box" placeholder="Select Area">
                                            <option v-for="(area, index) in menuArea.menu_areas" :key="index" :value="area" :selected="area.is_default == 1">
                                                    {{ area.cooking_area.name }}
                                            </option>
                                        </select> -->
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        <!-- <a class="pr-2" :href="'/warning/' + warning.id + '/edit'">
                                            <i class="fal fa-pen"></i>
                                        </a>

                                        <button data-te-toggle="modal" data-te-target="#deleteModal" id="edit-btn"
                                            @click="deleteBtnClicked(warning.id)"
                                            class="pl-2">
                                            <i class="fas fa-trash-alt"></i>
                                        </button> -->
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getMenuAreaList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getMenuAreaList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
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
                    <button @click="confirmDeleteBtnClicked" type="button" data-te-toggle="modal"
                        data-te-target="#deleteModal"
                        class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { Modal, Ripple, initTE, Input } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, deleteApiData, postApiData } from '../../utilities/ajax-helpers';

export default {
    data() {
        return {
            sellingAreaList: [],
            menuAreaList: [],

            selectedSellingArea:null,
            selectedCookingAreaId:null,

            searchInput:null,

            deleteId:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getSellingArea(pageNumber) {
            let url = `/api/menu_selling_areas`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.sellingAreaList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
                // this.totalData = response.data.total;
                this.selectedSellingArea = response.data[0]
                this.getMenuAreaList();
            }
        },
        selectedSellingAreaChanged(){
            this.getMenuAreaList();
        },
        async getMenuAreaList(pageNumber) {
            let url = `/api/menu_category_cooking_areas?selling_area_id=` + this.selectedSellingArea.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.menuAreaList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
                // this.totalData = response.data.total;

            }
        },
        async selectedCookingAreaChanged(event, index) {
            this.selectedCookingAreaId = $("#test"+index).val();
            let formData = new FormData();
            let response = await postApiData({url: "/api/menu_area/" + this.selectedCookingAreaId,form_data: formData,token: this.getToken()});
            if (response.success) {
                console.log("ok");
                this.$notify({
                    title: `Cooking Area`,
                    text: "Cooking Area successfully confirmed",
                    type: "info",
                });
                this.getMenuAreaList();
            }
            else{
                this.$notify({
                    title: 'Error',
                    text: response.message,
                    type: 'error'
                });
            }
            
        },
        
    },
    created() {
        this.getSellingArea();
    },

    mounted() {

    }
}
</script>
