<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Staff
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>

                <div class="bg-white mb-0 w-[40%] text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Filter by department"
                        data-te-select-filter="true" v-model="searchCategory" class="h-full">
                        <option :value="department" v-for="department in departmentList" :key="department.id">
                            {{ department.name }}
                        </option>
                    </select>
                </div>

                <button class="add-btn h-8 text-[13px] font-inter" @click="searchBtnClicked">Search</button>
                <button class="add-btn h-8 text-[13px] font-inter" @click="clearSearchBtnClicked">Clear</button>
            </div>
            <div class="flex justify-end flex-col">
                <a  v-if="feature.includes('staff.create')" href="/staff/create" class="add-btn text-[13px] font-inter">
                    Add New
                </a>

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
                                    Phone Number
                                </th>
                                <th scope="col" class=" ">
                                    Address
                                </th>
                                <th scope="col" class=" ">
                                    Roles
                                </th>
                                <th scope="col" class=" ">
                                    Department
                                </th>
                                <th scope="col" class="" v-show="['staff.toggle', 'staff.edit'].some(f => feature.includes(f))">

                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(staff, index) in staffList" :key="index">
                                <tr class="">
                                    <td class=" ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
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
                                        <div v-for="role in staff.roles" :key="role.id">
                                            {{ role.name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap   ">
                                        {{ staff.department.name }}
                                    </td>
                                    <td class="whitespace-nowrap   relative" v-show="['staff.toggle', 'staff.edit'].some(f => feature.includes(f))">
                                        <a v-if="feature.includes('staff.edit')" :href="'/staff/' + staff.id + '/edit'" class="pr-2 ">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                        <input v-show="feature.includes('staff.toggle')" :checked="staff.is_active == 1" @change="isActiveToggled(staff.id)"
                                            class="mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-white before:pointer-events-none before:absolute before:h-3.5
                                            before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:-mt-[0.1875rem] after:h-5
                                            after:w-5 after:rounded-full after:border-none after:bg-black after:transition-[background-color_0.2s,transform_0.2s]
                                            after:content-[''] checked:bg-black checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                            checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-black checked:after:shadow-switch-1
                                            checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                            focus:before:opacity-[0.12]  focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                            focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                             checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100
                                            checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] "
                                            type="checkbox" role="switch" />
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
                                @click="getStaffsList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage"
                                @click="getStaffsList(currentPage + 1)"> »</button>
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
            keyword: null,
            department: null,
            departmentList: [],
            selectedDepartment: null,
            editDepartment: null,
            deleteId: null,

            searchInput: null,
            searchCategory: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0,

            user: null,
            feature: this.getFeature(),
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getDepartmentList() {
            let url = `/api/departments`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },

        async getStaffsList(pageNumber) {

            let url = `/api/staffs?page=${pageNumber}`;
            if (this.searchInput && this.searchCategory) {
                url = `/api/staffs?search_input=${this.searchInput}&department_id=${this.searchCategory.id}&page=${pageNumber}`;
            }
            if (this.searchInput && !this.searchCategory) {
                url = `/api/staffs?search_input=${this.searchInput}&page=${pageNumber}`;
            }
            if ((!this.searchInput) && this.searchCategory) {
                url = `/api/staffs?department_id=${this.searchCategory.id}&page=${pageNumber}`;
            }
            const response = await getApiData({ url: url, token: this.getToken() });
            if (response.data != null) {

                this.staffList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        isActiveToggled(id) {
            let index = this.staffList.findIndex(staff => staff.id == id);
            if (index != -1) {
                if (this.staffList[index].is_active == 1) {
                    this.staffList[index].is_active = 0;
                }
                else {
                    this.staffList[index].is_active = 1;
                }

                let url = `/api/is_active`;
                let formData = new FormData();
                formData.append('id', id);
                formData.append('type', 'staff');
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
            }
        },

        async confirmDeleteBtnClicked() {
            let url = `/api/staff/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                alert(`deleted`);
            }
        },

        async searchBtnClicked() {
            this.getStaffsList(1);
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.searchCategory = null;
            this.getStaffsList(1);
        },

    },

    created() {
        this.getDepartmentList();
        this.getStaffsList(1);
    },

    mounted() {
        // this.feature = this.getFeature();
        // this.user = this.getUser();
        initTE({ Modal, Ripple, Input, Select, Dropdown })
    }
}
</script>
