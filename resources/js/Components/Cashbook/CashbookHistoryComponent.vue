<template>
    
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class="page-title">
                    CashBook History
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex gap-x-4">
                    <input type="date" class="h-8 mr-2 rounded-md" v-model="selectedDate">

                    <button class="add-btn " @click="searchBtnClicked">Search</button>
                    <button class="add-btn " @click="clearSearchBtnClicked">Clear</button>
                </div>
                <div class="flex justify-end flex-col">

                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    Id
                                </th>
                                <th scope="col" class="">
                                    Date
                                </th>
                                <th scope="col" class="">
                                    Opening Balance
                                </th>
                                <th scope="col" class="">
                                    Closing Balance
                                </th>
                                <!-- <th scope="col" class="px-6 py-4">

                                </th> -->
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, index) in primaryList" :key="index">
                                <tr class="">
                                    <td class="">
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ item.created_at }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ item.opening_balance }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ item.closing_balance }}
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="primaryList.length < 1 && !loading">
                                <td class="" colspan="4">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- <div class="mt-2 ml-2">
                    <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                        <li v-if="!isFirstGroup">
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                            hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="previousPaginationGroupBtnClicked"
                            :disabled="isFirstGroup">
                                Previous
                            </button>
                        </li>

                        <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]" :key="pageNumberIndex"
                            :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                            <button v-if="pageNumber == currentPage"
                                class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                                <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
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
                            hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="nextPaginationGroupBtnClicked"
                            :disabled="isLastGroup">
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
                                <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
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
                </div> -->


                 <!-- pagination -->
                 <div class="flex justify-center">

                    <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                        <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                            @click="getAccessoriesList(currentPage - 1)">«</button>

                        <button class=" text-sm px-5 border">
                            Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                lastPage }}</span>
                        </button>

                        <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                            :disabled="currentPage === lastPage" @click="getAccessoriesList(currentPage + 1)"> »</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
        



</template>

<script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import { getCurrentDate } from "../../utilities/datetime-helpers";
    import TableSkeleton from "../Common/TableSkeleton.vue";

    export default {
        components: {
            TableSkeleton
        },
        data() {
            return {
                primaryList: [],

                url: '/api/get_cashbook_closing_history',
                selectedDate : getCurrentDate(),
                url_date: '&date=' + getCurrentDate(),
                currentPage: 0,
                perPage: 0,
                lastPage: 0,
                totalData: 0,
                loading: false,
            };
        },

        methods: {
            ...mapGetters(['getUser', 'getDepartment','getToken', 'getFeature']),

            async getPrimaryList(pageNumber) {
                this.loading = true;
                // let url = this.url + this.url_search + this.url_department + this.url_role;
                let url = this.url + '?is_pos=0' + '&page=' + pageNumber + this.url_date;
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.loading = false;
                    if(response.data.data){
                        this.primaryList = response.data.data;
                        this.lastPage = response.data.last_page;
                        this.currentPage = pageNumber;
                        this.perPage = response.data.per_page;
                        this.totalData = response.data.total;
                    }
                    else{
                        this.primaryList = response.data;
                    }
                }
            },
            dateChange(){
                this.url_date = '&date=' + this.selectedDate;
                this.getPrimaryList(1);
            },
        
        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getPrimaryList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getPrimaryList(1);
        // },



        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/hr/hand-books/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getPrimaryList(1);
                document.getElementById('close_delete_modal').click();
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },
        

        alertValidationMessage(field) {
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to provide ${field}, please try again`,
                    type: 'warn'
                });
            },

            
        },

        created(){
            this.getPrimaryList(1);
        },

        mounted()
        {
            initTE({ Modal,Select, Ripple });
        }
    }
</script>
