<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Task Reports
        </p>
    </div>
    <div class="margin-bg">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="month" class="" placeholder="Date" v-model="dateInput" @change="searchBtnClicked">
                    <!-- <i class="fal fa-search"></i> -->
                </label>

                <!-- <select name="" id="" class="input-ui focus:ring-0 h-8 text-xs py-0">
                                <option value="1" > Title </option>
                            </select> -->
                <!-- <div class=" min-w-[212px] multi-select">
                    <select placeholder="Department" class='border border-gray-400 rounded-md h-8 text-xs py-1' v-model="selectedDepartment"
                        @change="searchBtnClicked">
                        <option value="" disabled>Select a department</option>
                        <option v-for="department in departmentList" :key="department.id" :value="department">
                            {{ department.name }}
                        </option>
                    </select>
                </div> -->

                <div class=" min-w-[212px] multi-select">

                    <multiselect v-model="selectedDepartment" @select='searchBtnClicked()' :options="departmentList" :close-on-select="true"
                        :clear-on-select="false" :preserve-search="true" placeholder="Department" label="name"
                        track-by="id" :preselect-first="false"></multiselect>
                </div>



                <button class="add-btn h-8 text-[13px] font-inter" @click="clearSearchBtnClicked">Clear</button>
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
                                    Task
                                </th>
                                <th scope="col" class=" ">
                                    Sale Target
                                </th>
                                <th scope="col" class=" ">
                                    Attendance
                                </th>

                                <th scope="col" class=" ">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(staff, index) in staffReportList" :key="index">
                                <tr class="">
                                    <td class=" ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap text-left  ">
                                        {{ staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap text-left  ">
                                        {{ staff.kpi || 0 }}

                                    </td>
                                    <td class="   ">
                                        0
                                    </td>
                                    <td class="whitespace-nowrap   ">
                                        0
                                    </td>
                                    <td class="whitespace-nowrap   ">
                                        {{ staff.kpi || 0 }}
                                    </td>

                                </tr>
                            </div>


                            <!-- looping end -->
                        </tbody>
                    </table>
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1"
                                @click="getStaffReportList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage"
                                @click="getStaffReportList(currentPage + 1)"> »</button>
                        </div>
                    </div>
                </div>
                <!-- <div class="mt-2 ml-2">
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
                </div> -->
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
            dateInput: null,
            searchCategory: null,

            departmentList: [],
            selectedDepartment: null,
            teamList: [],
            selectedTeam: null,


            isFirstGroup: true,
            isLastGroup: false,
            staffReportList: [],

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getDepartmentList() {
            let url = `/api/departments`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },

        async getStaffReportList(pageNumber) {
            let url = `/api/staff_reports?page=${pageNumber}`;
            const response = await getApiData({ url: url, token: this.getToken() });
            if (response.data != null) {
                this.staffReportList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;

            }
        },

        async searchBtnClicked() {
            let url = '/api/staff_reports?';
            const queryParams = [];

            if (this.dateInput) {
                const month = new Date(this.dateInput + '-01').getMonth() + 1; // Extract month and adjust for 0-based index
                queryParams.push(`month=${month}`);
            }

            if (this.selectedDepartment) {
                queryParams.push(`department_id=${this.selectedDepartment.id}`);
            }
            queryParams.push('page=1');
            url += queryParams.join('&');

            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.staffReportList = response.data.data;
            }
        },

        clearSearchBtnClicked() {
            this.dateInput = null;
            this.selectedDepartment = null;
            this.getStaffReportList(1);
        },

    },

    created() {
        this.getDepartmentList();
    },

    mounted() {
        initTE({ Modal, Ripple, Input, Select, Dropdown })
        this.getStaffReportList(1);
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
