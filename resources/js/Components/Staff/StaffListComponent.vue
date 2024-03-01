<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
        </div>
        <div class="flex justify-end flex-col">
            <a href="/staff/create" class="add-btn ">
                Add New
            </a>

        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Phone Number
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Address
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Department
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- looping start -->
                        <div class="contents" v-for="(staff, index) in staffList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ per_page * (currentPage - 1) + (++index) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ staff.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ staff.phone_number }}
                                </td>
                                <td class=" px-6 py-4 ">
                                    {{ staff.address }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ staff.department.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button @click="deleteBtnClicked(staff.id)"
                                    data-te-toggle="modal" data-te-target="#deleteModal"
                                        id="edit-btn" class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>


                        <!-- looping end -->

                        <!-- <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                            <td class=" px-6 py-4 font-medium ">
                                1
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 ">
                                Mg Mg
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 ">
                                09960327201
                            </td>
                            <td class=" px-6 py-4 ">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis voluptatem incidunt
                                repellendus
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 ">
                                Kitchen
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button id="edit-btn" class="pr-1">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="">
                            <td class=" py-2 ">

                            </td>

                        </tr>
                        <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                            <td class=" px-6 py-4 font-medium ">
                                1
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 ">
                                Mg Mg
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 ">
                                09960327201
                            </td>
                            <td class=" px-6 py-4 ">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis voluptatem incidunt
                                repellendus
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 ">
                                Kitchen
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button id="edit-btn" class="pr-1">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="">
                            <td class=" py-2 ">

                            </td>

                        </tr> -->

                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <ul v-if="pageNumbers.length > 1 && pageNumbers.length < 10" class="list-style-none flex">
                <li v-for="(pageNumber, pageNumberIndex) in pageNumbers" :key="pageNumberIndex"
                :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                    <button v-if="pageNumber == currentPage"
                        class="relative block rounded bg-black px-3 py-1.5 text-sm font-medium text-white transition-all duration-300"
                        :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                        {{ pageNumber }}
                        <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]"> (current) </span>
                    </button>
                    <button v-else
                        class="relative block rounded px-3 py-1.5 text-sm transition-all duration-300 hover:bg-neutral-200"
                        :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                        {{ pageNumber }}
                    </button>
                </li>
            </ul>

            <ul v-if="pageNumbers.length >= 10" class="list-style-none flex">
                <li>
                    <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-200 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="firstPaginationGroupBtnClicked">
                        First
                    </button>
                </li>
                <li>
                    <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-200 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="previousPaginationGroupBtnClicked">
                        Previous
                    </button>
                </li>

                <!-- loop link 1 , 2 ,3  ... replace normal-pagination with active-pagination for active pagination page-->
                <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]" :key="pageNumberIndex"
                    :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                    <button v-if="pageNumber == currentPage"
                        class="relative block rounded bg-black px-3 py-1.5 text-sm font-medium text-white transition-all duration-300"
                        :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                        {{ pageNumber }}
                        <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]"> (current) </span>
                    </button>
                    <button v-else
                        class="relative block rounded px-3 py-1.5 text-sm transition-all duration-300 hover:bg-neutral-200"
                        :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                        {{ pageNumber }}
                    </button>
                </li>
                <li>
                    <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-200 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="nextPaginationGroupBtnClicked">
                        Next
                    </button>
                </li>
                <li>
                    <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-200 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="lastPaginationGroupBtnClicked">
                        Last
                    </button>
                </li>
            </ul>
        </div>



        <!--Delete Modal -->
        <div
        data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div
            data-te-modal-dialog-ref
            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5
                    class="text-xl font-medium leading-normal text-neutral-800 "
                    id="exampleModalLabel">
                    Delete ?
                    </h5>
                    <!--Close button-->
                    <button
                    type="button"
                    class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss
                    aria-label="Close">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
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
                    <button
                    type="button"
                    class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                    data-te-modal-dismiss
                    >
                        Close
                    </button>
                    <button @click="confirmDeleteBtnClicked"
                    type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                    class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 "
                    >
                        Delete
                    </button>
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
                staffList: [],
                name: null,
                edit_name: null,
                team_id: null,
                keyword:null,
                department:null,
                departmentList:[],
                selectedDepartment:null,
                editDepartment:null,
                deleteId: null,

                per_page: 10,
                pageNumbers: [],
                currentPage: 1,
                paginationGroupsCount: 1,
                per_group: 10,
                groupedPageNumbers: [],
                currentGroup: 0,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getStaffsList(pageNumber)
            {
                let url = `api/staffs?per_page=${this.per_page}`;
                if(pageNumber){
                    url = `${url}&page=${pageNumber}`
                }
                const response = await getApiData({ url: url, token: this.getToken() });
                if (response.data != null) {
                    this.staffList = response.data.staffs;
                    this.pageNumbers = [];
                    for (let j = 1; j <= response.last_page; j++) {
                        this.pageNumbers.push(j);
                    }
                    if(this.pageNumbers.length > 10){
                        this.paginationGroupsCount = this.pageNumbers.length % 10;
                        for(let i=0; i<this.pageNumbers.length; i+=this.per_group){
                            let chunk = this.pageNumbers.slice(i, i+this.per_group);
                            this.groupedPageNumbers.push(chunk);
                        }
                    }
                }

                // console.log(response.data);
                // this.staffList = response.data;
            },

            async searchStaffs()
            {
                const response = await getApiData({url:`/api/staffs?keyword=${this.keyword}`});
                if(response)
                {
                    this.staffList = response.data;
                }
            },

            // async deleteStaff(id)
            // {
            //     const response = await deleteApiData({ url: `api/staffs/${id}` });
            //     const index = this.staffList.findIndex(item => item.id == id);
            //     this.staffList.splice(index, 1);
            // },

            deleteBtnClicked(id){
                this.deleteId = id;
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/staff/${this.deleteId}`;
                let response = await deleteApiData({url: url});
                if(response.success){
                    alert(`deleted`);
                }
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
        mounted()
        {
            this.getStaffsList(null);
        }
    }
</script>
