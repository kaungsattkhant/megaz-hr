<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Task Reports
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="date" class="" placeholder="Search"
                        v-model="searchInput">
                    <!-- <i class="fal fa-search"></i> -->
                </label>

                <!-- <select name="" id="" class="input-ui focus:ring-0 h-8 text-xs py-0">
                                <option value="1" > Title </option>
                            </select> -->
                <div class=" min-w-[212px] multi-select">

                    <multiselect v-model="selectedDepartment" :options="departmentList" :close-on-select="true"
                    :clear-on-select="false" :preserve-search="true" placeholder="Department" label="name"
                    track-by="id" :preselect-first="false"></multiselect>
                </div>
                
                <div class=" min-w-[212px] multi-select">
                    <multiselect v-model="selectedDepartment" :options="departmentList" :close-on-select="true"
                    :clear-on-select="false" :preserve-search="true" placeholder="Team" label="name"
                    track-by="id" :preselect-first="false"></multiselect>
                </div>

                <!-- <div class="bg-white mb-0 w-[40%] text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Filter by department"
                        data-te-select-filter="true" v-model="searchCategory" class="h-full">
                        <option :value="department" v-for="department in departmentList">
                            {{ department.name }}
                        </option>
                    </select>
                </div> -->

                <!-- <button class="add-btn h-8 text-[13px] font-inter" @click="searchBtnClicked">Search</button>
                <button class="add-btn h-8 text-[13px] font-inter" @click="clearSearchBtnClicked">Clear</button> -->
            </div>
            <div class="flex justify-end flex-col">

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
                                    Name
                                </th>
                                <th scope="col" class=" text-left">
                                    Position
                                </th>
                                <th scope="col" class=" ">
                                    Task
                                </th>
                                <th scope="col" class=" ">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <!-- <div class="contents" v-for="(staff, index) in staffList" :key="index">
                                <tr class="">
                                    <td class=" ">
                                        {{ per_page * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap text-left  ">
                                        {{ staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap text-left  ">
                                        {{ staff.phone_number }}

                                    </td>
                                    <td class="   ">
                                        {{ staff.address }}
                                    </td>
                                    <td class="whitespace-nowrap   ">
                                        <div v-for="role in staff.roles">
                                            {{ role.name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap   ">
                                        {{ staff.department.name }}
                                    </td>
                                    <td class="whitespace-nowrap   relative">
                                        <a :href="'/staff/' + staff.id + '/edit'" class="pr-2 ">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    </td>
                                </tr>
                            </div> -->


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
</template>

<script>
    import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
    import { mapGetters } from "vuex";
    import { getApiData } from '../../utilities/ajax-helpers';
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect
        },
        data() {
            return {
                searchInput: null,
                searchCategory: null,

                departmentList: [],
                selectedDepartment: null,
                teamList: [],
                selectedTeam: null,

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

            async getDepartmentList(){
                let url = `/api/departments`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.departmentList = response.data;
                }
            },

            async getStaffsList(pageNumber)
            {
                if(pageNumber){
                    this.currentPage = pageNumber;
                }
                let url = `/api/staffs?page=${this.currentPage}`;
                const response = await getApiData({ url: url, token: this.getToken() });
                if (response.data != null) {
                    this.staffList = response.data.data;
                    this.per_page = response.data.per_page;

                    this.pageNumbers = [];
                    this.lastPageNumber = response.data.last_page;

                    for(let i=1; i<=response.data.last_page; i++){
                        this.pageNumbers.push(i);
                    }

                    if(this.pageNumbers.length > 10){
                        this.groupedPageNumbers = [];
                        this.paginationGroupsCount = this.pageNumbers.length % 10;
                        for(let i=0; i<this.pageNumbers.length; i+=10){
                            let chunk = this.pageNumbers.slice(i, i+10);
                            this.groupedPageNumbers.push(chunk);
                        }

                        let lastGroupIndex = this.groupedPageNumbers.length - 1;
                        this.isFirstGroup = (this.currentGroup === 0);
                        this.isLastGroup = (lastGroupIndex === this.currentGroup);
                    }
                }
            },

            async searchBtnClicked(){
                let url = null;
                if(this.searchInput && this.searchCategory){
                    url = `/api/staffs?search_input=${this.searchInput}&department_id=${this.searchCategory.id}&page=1`;
                }
                if(this.searchInput && !this.searchCategory){
                    url = `/api/staffs?search_input=${this.searchInput}&page=1`;
                }
                if((!this.searchInput) && this.searchCategory){
                    url = `/api/staffs?department_id=${this.searchCategory.id}&page=1`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.staffList = response.data.data;
                }
            },

            clearSearchBtnClicked(){
                this.searchInput = null;
                this.searchCategory = null;
                this.getStaffsList(null);
            },

            pageBtnClicked(pageNumber){
                this.currentPage = pageNumber;
                this.getStaffsList(this.currentPage);
            },

            nextPaginationGroupBtnClicked(){
                this.currentGroup += 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getStaffsList(this.currentPage);
            },

            previousPaginationGroupBtnClicked(){
                this.currentGroup -= 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getStaffsList(this.currentPage);
            },

            firstPaginationGroupBtnClicked(){
                this.currentGroup = 0;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getStaffsList(this.currentPage);
            },

            lastPaginationGroupBtnClicked(){
                this.currentGroup = this.paginationGroupsCount - 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getStaffsList(this.currentPage);
            }

        },

        created(){
            this.getDepartmentList();
        },

        mounted(){
            initTE({ Modal, Ripple, Input, Select, Dropdown })
        }
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
