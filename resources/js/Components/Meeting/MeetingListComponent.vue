<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Meeting
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
                <a href="/meeting/create" class="add-btn ">
                    Add New
                </a>

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
                                    Date
                                </th>
                                <th scope="col" class=" ">
                                    Title
                                </th>
                                <th scope="col" class=" ">
                                    Department
                                </th>
                                <th scope="col" class=" ">
                                    Place
                                </th>
                                <th scope="col" class=" ">
                                    From
                                </th>
                                <th scope="col" class=" ">
                                    To
                                </th>
                                <th scope="col" class=" ">
                                    Chaired By
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(meeting, meetingIndex) in meetingList" :key="meetingIndex">
                                <tr class="">
                                    <td class="  font-medium ">
                                        {{ perPage * (currentPage - 1) + (++meetingIndex) }}

                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ meeting.date_time }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        title ??
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <!-- {{ meeting.date_time }} -->
                                          department
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        
                                        {{ meeting.place }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        
                                        {{ meeting.from_date }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        
                                        {{ meeting.to_date }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        
                                        {{ meeting.chaired_by.name }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        <a class="pr-2" :href="'/meeting/' + meeting.id + '/edit'">
                                            <i class="fal fa-pen"></i>
                                        </a>

                                        <button data-te-toggle="modal" data-te-target="#deleteModal" id="edit-btn"
                                            class="pl-2">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getMeetingList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getMeetingList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
import { Modal, Ripple, initTE, Input } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, deleteApiData } from '../../utilities/ajax-helpers';

export default {
    data() {
        return {
            meetingList: [],

            searchInput:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getMeetingList(pageNumber) {
            let url = `/api/meetings`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.meetingList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
                // this.totalData = response.data.total;

            }
        },
        async searchBtnClicked() {
            this.getMeetingList(1);
        },
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.getMeetingList(1);
        },
    },

    created() {
        this.getMeetingList(1);
    },

    mounted() {

    }
}
</script>
