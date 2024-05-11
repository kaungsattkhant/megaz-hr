<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Staff
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="flex justify-between mb-3 px-4 py-4 border-b">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input h-8">
                    <input type="text" class="input-search h-full !pt-1 pb-0 text-xs" placeholder="Search"
                        v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>

                <div class="bg-white mb-0 w-[40%] text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Filter by department"
                        data-te-select-filter="true" v-model="searchCategory" class="h-full">
                        <option :value="department" v-for="department in departmentList">
                            {{ department.name }}
                        </option>
                    </select>
                </div>

                <button class="add-btn h-8 text-[13px] font-inter" @click="searchBtnClicked">Search</button>
                <button class="add-btn h-8 text-[13px] font-inter" @click="clearSearchBtnClicked">Clear</button>
            </div>
            <div class="flex justify-end flex-col">
                <a href="/staff/create" class="add-btn text-[13px] font-inter">
                    Add New
                </a>

            </div>
        </div>
        <div class="block mx-4 mt-4 pb-4">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="border">
                    <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                        <thead class="border-b font-medium ">
                            <tr>
                                <th scope="col" class=" p-3 border-r font-inter">
                                    #
                                </th>
                                <th scope="col" class=" p-3 border-r text-left font-inter">
                                    Name
                                </th>
                                <th scope="col" class=" p-3 border-r text-left font-inter">
                                    Phone Number
                                </th>
                                <th scope="col" class=" p-3 border-r font-inter">
                                    Address
                                </th>
                                <th scope="col" class=" p-3 border-r font-inter">
                                    Roles
                                </th>
                                <th scope="col" class=" p-3 border-r font-inter">
                                    Department
                                </th>
                                <th scope="col" class=" p-3">

                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(staff, index) in staffList" :key="index">
                                <tr class="bg-white border-b overflow-hidden">
                                    <td class=" p-4 border-r font-inter font-medium ">
                                        {{ per_page * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap text-left p-4 border-r font-inter ">
                                        {{ staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap text-left p-4 border-r font-inter ">
                                        {{ staff.phone_number }}
                                        
                                    </td>
                                    <td class="  p-4 border-r font-inter ">
                                        {{ staff.address }}
                                    </td>
                                    <td class="whitespace-nowrap  p-4 border-r font-inter ">
                                        <div v-for="role in staff.roles">
                                            {{ role.name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap  p-4 border-r font-inter ">
                                        {{ staff.department.name }}
                                    </td>
                                    <td class="whitespace-nowrap  p-4 border-r font-inter relative">
                                        <a :href="'/staff/' + staff.id + '/edit'" class="pr-2 ">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                        <input :checked="staff.is_active == 1" @change="isActiveToggled(staff.id)"
                                            class="me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                            before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5
                                            after:w-5 after:rounded-full after:border-none after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                            after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                            checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                            checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                            focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                            focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                            checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                            checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-white/25 dark:after:bg-surface-dark dark:checked:bg-primary dark:checked:after:bg-primary"
                                            type="checkbox" role="switch" />
                                    </td>
                                </tr>
                            </div>


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

                searchInput: null,
                searchCategory: null,

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

            isActiveToggled(id){
                let index = this.staffList.findIndex(staff => staff.id == id);
                if(index != -1){
                    if(this.staffList[index].is_active == 1){
                        this.staffList[index].is_active = 0;
                    }
                    else{
                        this.staffList[index].is_active = 1;
                    }

                    let url = `/api/is_active`;
                    let formData = new FormData();
                    formData.append('id', id);
                    formData.append('type', 'staff');
                    let response = postApiData({url: url, form_data: formData, token: this.getToken()});
                }
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/staff/${this.deleteId}`;
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    alert(`deleted`);
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
            this.getStaffsList(null);
        },

        mounted()
        {
            initTE({ Modal, Ripple, Input, Select, Dropdown })
        }
    }
</script>
