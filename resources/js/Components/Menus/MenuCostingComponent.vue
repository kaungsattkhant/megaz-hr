<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Menu Costing
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container mb-2">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>
                <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
            </div>
            <div class="flex pr-0 gap-x-4">
                
                <div class="w-full" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category" v-model="selectedMenuCategory"
                    data-te-select-filter="true" @change="menuCategoryChanged()" class="input-ui w-full">
                    <option value="all">All</option>
                    <option v-for="(category,index) in menuCategoryList" :key="index"> {{ category.name }} </option>
                    </select>
                </div>
                <input type="date" class="input-ui h-8" v-model="selectedDate" @change="selectedDateChange()">
            </div>
        </div>
        <div class="block mx-4 mt-4 pb-4">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table ">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    No
                                </th>
                                <th scope="col" class="">
                                    Code
                                </th>
                                <th scope="col" class="text-left">
                                    Menu Name
                                </th>
                                <th scope="col" class="text-left">
                                    Category
                                </th>
                                <th>test</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(saleReport, index) in saleReportList"
                                :key="index">
                                <tr class="">
                                    <td class="font-medium ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="font-medium ">
                                        {{ saleReport.menu_code }}
                                    </td>
                                    <td class="font-medium ">
                                        {{ saleReport.menu_name }}
                                    </td>
                                    <td class="font-medium ">
                                        {{ saleReport.menu_category }}
                                    </td>
                                    <td class="font-medium ">
                                        {{ saleReport.total_quantity }}
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
                                @click="getTransactionList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getTransactionList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>

            </div>


            


        </div>
    </div>

</template>

<script>
import { Modal, Ripple, Select, initTE, Input, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

import { getCurrentDate } from '../../utilities/datetime-helpers';

export default {
    data() {
        return {
            saleReportList: [],
            menuCategoryList:[],
            searchInput:null,
            selectedMenuCategory:null,
            fromDate:null,
            toDate:null,

            url:'/api/menu_report?page=',
            url_search:'',
            url_category:'',
            url_month:'',

            perPage:null,
            currentPage:null,

            
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getSaleReportList(pageNumber) {
            let url = this.url + pageNumber + this.url_search + this.url_category + this.url_month;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.saleReportList = response.data.data;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
            }
        },
        async searchBtnClicked() {
            this.url_search = '&search=' + this.searchInput
            this.getSaleReportList(1);
        },
        menuCategoryChanged(){
            this.url_search = '';
            this.searchInput = null;
            if(this.selectedMenuCategory == 'all'){
                this.url_category = ''
            }
            else{
                this.url_category = '&menu_category_id=' + this.selectedMenuCategory;
            }
            this.getSaleReportList(1)
        },
        monthChanged(){
            this.url_month = '&from_date=' + this.fromDate + '&to_date=' + this.toDate
            this.getSaleReportList(1)
        },
        


















        // async getSaleReportList(pageNumber) {
        //     let url = `/api/menu_report?page=${pageNumber}`;
        //     if (this.fromDate && this.toDate) {
        //         url = `${url}&from_date=${this.fromDate}&to_date=${this.toDate}`;
        //     }
        //     if(this.searchInput){
        //         url = '/api/menu_report?page=' + pageNumber + '&search=' + this.searchInput;
        //     }
        //     if(this.selectedMenuCategory){
        //         url = '/api/menu_report?page=' + pageNumber + '&menu_category_id=' + this.selectedMenuCategory;
        //     }
        //     let response = await getApiData({ url: url, token: this.getToken() });
        //     if (response.data) {
        //         this.saleReportList = response.data.data;
        //         this.currentPage = pageNumber;
        //         this.perPage = response.data.per_page;
        //     }
        // },
        async getMenuCategoryList(){
            let response = await getApiData({ url: '/api/menu_categories', token: this.getToken() });
            if (response.data) {
                this.menuCategoryList = response.data;
            }
        },
        
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.url_search = '';
            this.getSaleReportList(1);
        },

        


    },

    created() {
        this.getSaleReportList(1);
        this.getMenuCategoryList();
    },
    

    mounted() {
        initTE({ Modal, Select, Ripple, Dropdown });
    }
}
</script>
