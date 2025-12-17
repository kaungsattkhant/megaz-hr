<template>
    
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Assets
                </p>
            </div>
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
                    <a href="/assets/create" class="add-btn text-[13px] font-inter">
                        Add New
                    </a>

                </div>
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
                                <th scope="col" class="">
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Cost
                                </th>
                                <th scope="col" class="">
                                    Useful Life
                                </th>
                                <th scope="col" class="">
                                    Purchase Date
                                </th>
                                <th scope="col" class="">

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
                            <div class="contents" v-for="(asset, index) in assetList" :key="index">
                                <tr class="">
                                    <td class=" ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ asset.name }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ asset.cost }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ asset.useful_life }}
                                    </td>


                                    <td class="whitespace-nowrap">
                                        {{ asset.purchase_date }}
                                    </td>

                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="assetList.length < 1 && !loading">
                                <td class="" colspan="5">
                                    No Data Here
                                </td>
                            </tr>


                        </tbody>
                    </table>
                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getAssetList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getAssetList(currentPage + 1)">
                                »</button>
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
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            assetList: [],

            searchInput:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            loading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getAssetList(pageNumber) {
            this.loading = true;
            let url = `/api/assets?page=${pageNumber}`;
            if(this.searchInput){
                url = '/api/assets?page=' + pageNumber + '&search=' + this.searchInput;
            }
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.assetList = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },
        async searchBtnClicked() {
            this.getAssetList(1);
        },
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.getAssetList(1);
        },
    },

    created() {
        this.getAssetList(1);
    },

    mounted() {
        initTE({ Modal, Ripple, Input, Select, Dropdown })
    }
}
</script>
