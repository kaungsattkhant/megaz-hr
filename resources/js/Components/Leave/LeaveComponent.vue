<template>
    
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class="page-title">
                    Leave
                </p>
            </div>
            <div class="btn-container">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <!-- <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button> -->
                    <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Department"
                            @change="searchDepartmentChange()" data-te-select-filter="true" name="" id=""
                            v-model="searchDepartment" class="input-ui">
                            <option :value="department" v-for="(department, departmentIndex) in departmentList"
                                :key="departmentIndex"> {{ department.name }} </option>
                        </select>
                    </div>
                    <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type" @change="searchRoleChange()"
                            data-te-select-filter="true" name="" id="" v-model="searchRole" class="input-ui">
                            <option :value="role" v-for="(role, roleIndex) in searchRoleList" :key="roleIndex"> {{ role.name
                                }} </option>
                        </select>
                    </div>
                </div>
                <div class="flex pr-0 gap-x-4">

                    <button data-te-toggle="modal" data-te-target="#add_leave_modal" @click="addBtnClicked"
                        class="add-btn  h-8 whitespace-nowrap">
                        Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Date
                                </th>
                                <th scope="col" class="">
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Leave Category
                                </th>
                                <th scope="col" class="">
                                    Days
                                </th>
                                <th scope="col" class="">
                                    Title
                                </th>
                                <th scope="col" class="">
                                    Details
                                </th>
                                <th scope="col" class="">
                                    Accepted By
                                </th>
                                <th scope="col" class="">
                                    Unpaid
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
                            <div class="contents" v-for="(leave, index) in leaveList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leave.start_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leave.staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leave.leave_category.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leave.day }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leave.title }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leave.detail }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leave.confirmed_by ? leave.confirmed_by.name : '--' }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-show="leave.is_unpaid_leave != null">
                                            {{ leave.is_unpaid_leave == 1 ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- <a :href="'/okr_duty/' + duty.id + '/edit'">
                                            <i class="far fa-pen cursor-pointer mr-3"></i>
                                        </a> -->
                                        <button @click="deleteBtnClicked(leave.id)" v-if="leave.status != 'received' && feature.includes('leave.delete')"
                                            data-te-toggle="modal" data-te-target="#deleteModal" id="delete-btn"
                                            class="pr-1">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                        <div class="contents" v-if="leave.status == 'received'">
                                            <button @click="btnClickedConfirmedLeave(leave)" data-te-toggle="modal"
                                                data-te-target="#confirm_modal">
                                                <i class="far fa-check text-sm mr-3 p-1"></i>
                                            </button>
                                            <button @click="cancelledLeave(leave)">
                                                <i class="far fa-times text-sm p-1"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="leaveList.length < 1 && !loading">
                                <td class="" colspan="10">
                                    No Data Here
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="leaveList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="leaveList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_leave_modal" tabindex="-1" aria-labelledby="add_duty_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_leave_modalLabel">
                            Create Leave
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="close_leave_type_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Department
                            </label>

                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Department"
                                    @change="selectedDepartmentChange()" name="" id="" v-model="selectedDepartment"
                                    class="input-ui !text-black text-sm">
                                    <option :value="department" v-for="(department, index) in departmentList"
                                        :key="index"> {{ department.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Role
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Role"
                                    @change="selectedRoleChange()" name="" id="" v-model="selectedRole"
                                    class="input-ui !text-black text-sm">
                                    <option :value="role" v-for="(role, index) in roleList" :key="index"> {{ role.name
                                        }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Staff Name
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Staff"
                                    data-te-select-filter="true" name="" id="" v-model="selectedStaff"
                                    class="input-ui !text-black text-sm">
                                    <option :value="staff" v-for="(staff, index) in staffList" :key="index"> {{
                                        staff.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Leave Category
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Leave Type" name="" id=""
                                    v-model="selectedLeaveCategory" class="input-ui !text-black text-sm">
                                    <option :value="leave" v-for="(leave, index) in leaveCategoryList" :key="index"> {{
                                        leave.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Leave Title
                            </label>
                            <input type="text" v-model="selectedLeaveTitle" class="input-ui " placeholder="Leave Title">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Detail
                            </label>
                            <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]" data-te-select-wrapper-ref>
                                <textarea type='text' v-model='detail' class="input-ui w-full !p-1 text-xs" rows="8"
                                    placeholder="Description"></textarea>
                            </div>

                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Status
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Status" disabled name=""
                                    id="" v-model="selectedStatus" class="input-ui !text-black text-sm">
                                    <option :value="status" v-for="status in statusList">{{ status.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Image
                            </label>
                            <input type="file" class="input-ui" @change="handleFileChange"
                                accept="image/png, image/gif, image/jpeg" ref="image">
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 mb-4">
                            <div>
                                <label for="" class="block text-sm text-black mb-3">
                                    Start Date
                                </label>
                                <input type="date" v-model="startDate"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div>
                                <label for="" class="block text-sm text-black mb-3">
                                    End Date
                                </label>
                                <input type="date" v-model="endDate"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                        </div>
                        <!-- <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Day
                            </label>
                            <input type="number" v-model="selectedDay" class="input-ui ">
                        </div>
                        <label for="isIncludeWeekends" class=" focus:outline-none focus:ring-0 focus:shadow-none cursor-pointer flex items-center gap-x-3 pl-1">
                            <input type="checkbox" id="isIncludeWeekends" v-model="isIncludeWeekends" class=" focus:outline-none focus:ring-0 focus:shadow-none">
                            Is Include Weekends
                        </label> -->
                        <label for="unpaidLeave"
                            class=" focus:outline-none focus:ring-0 focus:shadow-none cursor-pointer flex items-center gap-x-3 pl-1">
                            <input type="checkbox" id="unpaidLeave" v-model="isUnpaid"
                                class=" focus:outline-none focus:ring-0 focus:shadow-none">
                            Unpaid Leave
                        </label>

                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnClickedCrateLeave()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!--Confirm Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="confirm_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[550px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                    <div
                        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                        <h5 v-if="leaveDetail" class="text-xl font-medium leading-normal text-neutral-800 text-left pl-8 w-full pt-3" id="exampleModalLabel">
                            Confirm Leave For {{ leaveDetail.staff.name }} 
                        </h5>
                        <button type="button" id="close_confirm_modal"
                            class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative flex-auto px-12 py-8 text-left" data-te-modal-body-ref>
                        <p class="mb-16">
                            Leave Balance : {{ leaveDetail ? leaveDetail.total_remaining_leave_balance + ' Day': '' }} 
                        </p>
                        <label for="unpaidLeave"
                            class=" focus:outline-none focus:ring-0 focus:shadow-none cursor-pointer flex items-center gap-x-3 pl-1">
                            <input type="checkbox" id="unpaidLeave" v-model="isUnpaidConfirmModal"
                                class=" focus:outline-none focus:ring-0 focus:shadow-none">
                            Unpaid Leave
                        </label>
                    </div>
                    <div
                        class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                        <button type="button"
                            class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                            data-te-modal-dismiss>
                            Close
                        </button>
                        <button @click="confirmedLeave()" type="button" 
                            class="add-btn focus:outline-none focus:ring-0">
                            Confirm
                        </button>
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-6 w-6">
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
                        <button @click="deleteItem()" type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                            class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                            Delete
                        </button>
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
import { getCurrentTime, getCurretDateTime } from "../../utilities/datetime-helpers";
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            leaveList: [],
            departmentList: [],
            searchRoleList: [],
            roleList: [],
            staffList: [],
            leaveCategoryList: [],
            statusList: [
                {value: 'received', name: 'Received'},
                {value: 'confirmed', name: 'Confirmed'},
                {value: 'cancelled', name: 'Cancelled'},
            ],

            searchDepartment: null,
            searchRole: null,

            selectedDepartment: null,
            selectedRole: null,
            selectedStaff: null,
            selectedLeaveCategory: null,
            selectedLeaveTitle: null,
            detail: null,
            startDate: null,
            endDate: null,
            selectedDay: null,
            selectedImage: null,
            selectedStatus: {value: 'confirmed', name: 'Confirmed'},
            isIncludeWeekends: false,
            isUnpaid: false,

            currentTime: getCurretDateTime(),
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/hr/leaves',
            url_search:'',
            url_department:'',
            url_role:'',
            deleteId:null,

            leaveDetail:null,
            isUnpaidConfirmModal: false,

            feature: this.getFeature(),

            loading: true,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken','getFeature']),

        async getLeaveList(pageNumber) {
            this.loading = true;
            let url = this.url + this.url_department + this.url_role;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.leaveList = response.data.leave_records;
                this.currentPage = response.data.pagination.current_page;
                this.perPage = response.data.pagination.per_page;
                this.lastPage = response.data.pagination.last_page;
                this.totalData = response.data.pagination.total;
            }
            console.log(this.getUser());
        },
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        searchDepartmentChange(){
            this.url_department = '?department_id='+this.searchDepartment.id;
            this.roleList = this.searchDepartment.roles;
            this.getLeaveList();
        },
        searchRoleChange(){
            this.url_role = '&role_id='+this.selectedRole.id;
            this.getLeaveList();
        },
        async searchBtnClicked() {
            this.url_search = '&search=' + this.searchInput
            this.getLeaveList(1);
        },
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.url_search = '';
            this.getLeaveList(1);
        },


        selectedDepartmentChange(){
            this.roleList = this.selectedDepartment.roles;
            // this.getStaffList();
        },
        async selectedRoleChange(){
            let response = await getApiData({ url: '/api/hr/staff_lists_by_role/' + this.selectedRole.id + '/department/' + this.selectedDepartment.id, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data.data;
            }
        },
        // async getStaffList(){
        //         const response = await getApiData({ url: '/api/departments/' + this.selectedDepartment.id + '/staffs', token: this.getToken() });
        //         if(response.data){
        //             this.staffList = response.data;
        //         }
        //     },
        async getLeaveType(){
            let response = await getApiData({ url: '/api/hr/leave_categories', token: this.getToken() });
            if (response.data) {
                this.leaveCategoryList = response.data.data;
            }
        },
        handleFileChange(event) {
            const selectedFile = event.target.files[0];
            this.selectedImage = selectedFile;
        },

        btnClickedCrateLeave(){
            if(!this.selectedStaff){
                this.alertValidationMessage(`Employee`);
                return 1;
            }
            else if(!this.selectedLeaveCategory){
                this.alertValidationMessage(`Leave Category`);
                return 1;
            }
            else if(!this.selectedLeaveTitle){
                this.alertValidationMessage(`Title`);
                return 1;
            }
            else if(!this.detail){
                this.alertValidationMessage(`Detail`);
                return 1;
            }
            else if(!this.startDate){
                this.alertValidationMessage(`Start Date`);
                return 1;
            }
            else if(!this.endDate){
                this.alertValidationMessage(`End Date`);
                return 1;
            }
            else if(!this.selectedStatus){
                this.alertValidationMessage(`Status`);
                return 1;
            }
            else if(!this.selectedImage){
                this.alertValidationMessage(`Image`);
                return 1;
            }
            else{
                this.createLeave();
            }
        },
        async createLeave(){
            let formData = new FormData();
            formData.append('staff_id', this.selectedStaff.id);
            formData.append('leave_category_id', this.selectedLeaveCategory.id);
            formData.append('title', this.selectedLeaveTitle);
            formData.append('detail', this.detail);
            formData.append('start_date', this.startDate);
            formData.append('end_date', this.endDate);
            // formData.append('day', this.selectedDay);
            formData.append('status', this.selectedStatus.value);
            formData.append('image', this.selectedImage);
            if(this.isUnpaid == false){
                formData.append('is_unpaid_leave', 0);
            }
            else{
                formData.append('is_unpaid_leave', 1);
            }
            formData.append('confirmed_at', this.currentTime);
            formData.append('confirmed_by', this.getUser().id);
            let response = await postApiData({url:`/api/hr/leaves`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/leave`);
            }
            else {
                console.log(response.message)
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        addBtnClicked(){
            this.selectedDepartment = null;
            this.roleList = [];
            this.selectedRole = null;
            this.staffList = [];
            this.selectedStaff = null;
            this.selectedLeaveCategory = null;
            this.selectedLeaveTitle = null;
            this.detail = null;
            this.startDate = null;
            this.endDate = null;
            this.isUnpaid = false;
        },

        btnClickedConfirmedLeave(leave){
            this.leaveDetail = leave
        },
        async confirmedLeave(){
            let formData = new FormData();
            formData.append('status', 'confirmed');
            formData.append('confirmed_at', this.currentTime);
            formData.append('confirmed_by', this.getUser().id);
            if(this.isUnpaidConfirmModal == false){
                formData.append('is_unpaid_leave', 0);
            }
            else{
                formData.append('is_unpaid_leave', 1);
            }
            let response = await postApiData({url:`/api/hr/leaves/` + this.leaveDetail.id, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                document.getElementById('close_confirm_modal').click();
                this.getLeaveList();
            }
            else {
                console.log(response.message)
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        async cancelledLeave(leave){
            let formData = new FormData();
            formData.append('status', 'cancelled');
            formData.append('cancelled_at', this.currentTime);
            formData.append('cancelled_by', this.getUser().id);
            let response = await postApiData({url:`/api/hr/leaves/` + leave.id, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                // window.location.replace(`/leave`);
                this.getLeaveList();
            }
            else {
                console.log(response.message)
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/hr/leaves/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getLeaveList(1);
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
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getLeaveList(1);
        this.getDepartmentList();
        this.getLeaveType();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>