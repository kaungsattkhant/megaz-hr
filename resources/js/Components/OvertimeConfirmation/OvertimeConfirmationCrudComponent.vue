<template>
    
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Overtime Confirmation
                </p>
            </div>
            <div class="btn-container">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
                </div>
                <div class="flex pr-0 gap-x-4">
                    <div class=" !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Department" @change="searchDepartmentChange"
                            data-te-select-filter="true" name="" id="" v-model="searchDepartment" class="input-ui">
                            <option :value="department" v-for="(department, departmentIndex) in departmentList"
                                :key="departmentIndex"> {{ department.name }} </option>
                        </select>
                    </div>
                    <div class=" !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Role" @change="searchRoleChange"
                            data-te-select-filter="true" name="" id="" v-model="searchRole" class="input-ui">
                            <option :value="role" v-for="(role, roleIndex) in roleList"
                                :key="roleIndex"> {{ role.name }} </option>
                        </select>
                    </div>
                    <button type="button" v-show="feature.includes('overtime-confirmation.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
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
                                <!-- <th scope="col" class="">
                                    Date
                                </th> -->
                                <th scope="col" class="">
                                    Name
                                </th>
                                <th scope="col" class="">
                                    From Date
                                </th>
                                <th scope="col" class="">
                                    To Date
                                </th>
                                <th scope="col" class="">
                                    Shift
                                </th>
                                <th scope="col" class="">
                                    From Time
                                </th>
                                <th scope="col" class="">
                                    To Time
                                </th>
                                <th scope="col" class="">
                                    Category
                                </th>
                                <th scope="col" class="">
                                    Description
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
                            <div class="contents" v-for="(ot, index) in overtimeList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <!-- <td class="whitespace-nowrap">
                                        Date ??
                                    </td> -->
                                    <td class="whitespace-nowrap">
                                        {{ ot.staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ot.from_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ot.to_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ot.time_shift.shift.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ot.from_time }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ot.to_time }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ot.overtime_category.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ot.remark }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div class="contents" v-if="ot.status == 'received'">
                                            <button @click="btnConfirmedOvertime(ot)" data-te-toggle="modal" data-te-target="#confirm_modal">
                                                <i class="far fa-check text-sm mr-2 p-1"></i>
                                            </button>
                                            <button @click="btnCancelledOvertime(ot)" data-te-toggle="modal" data-te-target="#cancel_modal">
                                                <i class="far fa-times text-sm p-1"></i>
                                            </button>
                                        </div>
                                        <span v-if="ot.status != 'received'" class=" capitalize">
                                            {{ ot.status }}
                                        </span>
                                        <!-- <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn"
                                            class="pr-3" @click="editBtnClicked(ot, index)">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <button @click="deleteBtnClicked(ot.id)" data-te-toggle="modal"
                                            data-te-target="#deleteModal" id="delete-btn" class="pr-1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button> -->
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="overtimeList.length < 1 && !loading">
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
                                @click="getOvertimeList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOvertimeList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
    <!--Confirm Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="confirm_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Confirm ?
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
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p>
                        Are you sure ?
                    </p>
                </div>
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button"
                        class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss>
                        Close
                    </button>
                    <button type="button" @click="confirmedOvertime()"
                        class="add-btn focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--Cancel Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="cancel_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Cancel ?
                    </h5>
                    <button type="button" id="close_cancel_modal"
                        class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p>
                        Are you sure ?
                    </p>
                </div>
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button"
                        class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss>
                        Close
                    </button>
                    <button type="button" @click="cancelOvertime()"
                        class="add-btn focus:outline-none focus:ring-0 ">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[650px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create {{ isCategory ? 'Category' : 'Overtime Fees' }}
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        id="close_create_modal" aria-label="Close" @click="closeCategoryWithDelay">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="content" v-show="!isCategory">
                    <div class="relative px-6 py-4 border-b grid grid-cols-2 gap-4" data-te-modal-body-ref>
                        <div class="mb-3">
                            <label for="" class="label-form mb-3">
                                Department
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
                                    data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black text-sm">
                                    <option :value="department" v-for="(department, index) in departmentList"
                                        :key="index"> {{ department.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="label-form mb-3">
                                Role
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Role" @change="selectedRoleChange()"
                                    data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                                    <option :value="role" v-for="(role, index) in roleList"
                                        :key="index"> {{ role.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Staff"
                                    data-te-select-filter="true" name="" id="" v-model="selectedStaff" class="input-ui !text-black text-sm">
                                    <option :value="staff" v-for="(staff, index) in staffList"
                                        :key="index"> {{ staff.name }} </option>
                                </select>
                            </div>
                        </div><div></div>

                        <div class="mb-3">
                            <label for="" class="label-form mb-3">
                                From Date
                            </label>
                            <input type="date" v-model="fromDate" class="input-ui mb-0">
                        </div>
                        <div class="mb-3">
                            <label for="" class="label-form mb-3">
                                To Date
                            </label>
                            <input type="date" v-model="toDate" class="input-ui mb-0">
                        </div>
                        <div class="mb-3">
                            <label for="" class="label-form mb-3">
                                Shift
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Shift" @change="selectedShiftChange()"
                                    data-te-select-filter="true" name="" id="" v-model="selectedShift" class="input-ui !text-black text-sm">
                                    <option :value="shift" v-for="(shift, index) in shiftList"
                                        :key="index"> {{ shift.shift.name }} </option>
                                </select>
                            </div>
                        </div><div></div>

                        <div class="mb-3">
                            <label for="" class="label-form mb-3">
                                From Time
                            </label>
                            <input type="time" v-model="fromTime" class="input-ui mb-0">
                        </div>
                        <div class="mb-3">
                            <label for="" class="label-form mb-3">
                                To Time
                            </label>
                            <input type="time" v-model="toTime" class="input-ui mb-0">
                        </div>
                        <div class="mb-3">
                            <label for="" class="label-form mb-3">
                                Category
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Category"
                                    data-te-select-filter="true" name="" id="" v-model="selectedCategory" class="input-ui !text-black text-sm">
                                    <option :value="category" v-for="(category, index) in categoryList"
                                        :key="index"> {{ category.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="label-form mb-3">
                                &nbsp;
                            </label>
                            <button class="px-2 mt-1" @click="toggleIsCategory"><i class="fal fa-plus"></i></button>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Remark
                            </label>
                            <textarea name="" v-model="remark"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                                rows="6"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnCreateOvertime()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
                <div class="content" v-show="isCategory">
                    <div class="relative px-12 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-6">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" v-model="categoryName" class="input-ui mb-0">
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" @click="toggleIsCategory">
                            Cancel
                        </button>
                        <button type="button" @click="btnCreateCategory()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
import Multiselect from 'vue-multiselect';
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import { getCurrentTime, getCurretDateTime } from "../../utilities/datetime-helpers";
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        Multiselect,
        TableSkeleton
    },
    data() {
        return {
            overtimeList: [],
            
            departmentList: [],
            roleList: [],
            staffList: [],
            shiftList: [],
            categoryList: [],

            selectedDepartment: null,
            selectedRole: null,
            selectedStaff: null,
            fromDate: null,
            toDate: null,
            selectedShift: null,
            fromTime: null,
            toTime: null,
            selectedCategory: null,
            remark: null,
            isCategory: false,
            categoryName: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,

            overtime: null,

            url:'/api/hr/overtimes',
            url_search:'',
            url_department:'',
            url_role:'',
            searchDepartment: null,
            searchRole: null,
            deleteId:null,

            currentTime: getCurretDateTime(),

            feature: this.getFeature(),
            loading: false,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken', 'getFeature']),

        async getOvertimeList(pageNumber) {
            this.loading = true;
            let url = this.url + this.url_search + this.url_department + this.url_role;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.overtimeList = response.data.data;
            }
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
            this.getOvertimeList();
        },
        searchRoleChange(){
            this.url_role = '&role_id='+this.searchRole.id;
            this.getOvertimeList();
        },
        async getShiftList(){
            let response = await getApiData({ url: '/api/time_shifts', token: this.getToken() });
            if (response.data) {
                this.shiftList = response.data;
            }
        },
        async getCategoryList(){
            let response = await getApiData({ url: '/api/hr/overtime_categories', token: this.getToken() });
            if (response.data) {
                this.categoryList = response.data.data;
            }
        },
        selectedDepartmentChange(){
            this.roleList = this.selectedDepartment.roles;
            this.selectedRole = null;
            this.staffList = [];
            this.selectedStaff = null;
        },
        selectedRoleChange(){
            this.staffList = [];
            this.selectedStaff = null;
            this.getStaffList();
        },
        async getStaffList(){
            let response = await getApiData({ url: '/api/hr/staff_lists_by_role/' + this.selectedRole.id + '/department/' + this.selectedDepartment.id, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data.data;
            }
        },
        selectedShiftChange(){
            this.fromTime = this.selectedShift.from_time;
            this.toTime = this.selectedShift.to_time;
        },







        btnCreateOvertime(){
            if(!this.selectedRole){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            else if(!this.selectedStaff){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else if(!this.fromDate){
                this.alertValidationMessage(`From Date`);
                return 1;
            }
            else if(!this.toDate){
                this.alertValidationMessage(`To Date`);
                return 1;
            }
            else if(!this.selectedShift){
                this.alertValidationMessage(`Shift`);
                return 1;
            }
            else if(!this.fromTime){
                this.alertValidationMessage(`From Time`);
                return 1;
            }
            else if(!this.toTime){
                this.alertValidationMessage(`To Time`);
                return 1;
            }
            else if(!this.selectedCategory){
                this.alertValidationMessage(`Category`);
                return 1;
            }
            // else if(!this.remark){
            //     this.alertValidationMessage(`Category`);
            //     return 1;
            // }
            else{
                this.createOvertime();
            }
        },
        async createOvertime(){
            let formData = new FormData();
            formData.append('from_date', this.fromDate);
            formData.append('to_date', this.toDate);
            formData.append('staff_id', this.selectedStaff.id);
            formData.append('overtime_category_id', this.selectedCategory.id);
            formData.append('time_shift_id', this.selectedShift.id);
            formData.append('from_time', this.fromTime);
            formData.append('to_time', this.toTime);
            formData.append('remark', this.remark);
            formData.append('status', 'confirmed');
            formData.append('confirmed_at', this.currentTime);
            formData.append('confirmed_by', this.getUser().id);
            let response = await postApiData({url:`/api/hr/overtimes`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getOvertimeList();
                document.getElementById("close_create_modal").click();
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
            this.roleList = [];
            this.selectedDepartment = null;
            this.selectedRole = null;
            this.staffList = [];
            this.selectedStaff = null;
            this.fromDate = null;
            this.toDate = null;
            this.selectedShift = null;
            this.fromTime = null;
            this.toTime = null;
            this.selectedCategory = null;
            this.remark = null;
        },
        async btnCreateCategory(){
            let formData = new FormData();
            formData.append('name', this.categoryName);
            let response = await postApiData({url:`/api/hr/overtime_categories`, form_data:formData, token:this.getToken()})
            if(response.success){
                let categoryLength = this.categoryList.length;
                this.getCategoryList();
                this.isCategory = false;
                this.categoryName = null;
                setTimeout(() => {
                    this.selectedCategory = this.categoryList.find(category => category.id == response.data.id)
                }, 100);
                console.log('test' + response.data.id + 'length' + this.categoryList.length)
            }
            else {
                console.log(response.message)
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },





        btnConfirmedOvertime(ot){
            this.overtime = ot;
        },
        async confirmedOvertime(){
            let formData = new FormData();
            formData.append('status', 'confirmed');
            let response = await postApiData({url:`/api/hr/approval/overtimes/` + this.overtime.id, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed');
                document.getElementById('close_confirm_modal').click();
                this.getOvertimeList();
            }
        },
        btnCancelledOvertime(ot){
            this.overtime = ot;
        },
        async cancelOvertime(){
            let formData = new FormData();
            formData.append('status', 'cancelled');
            let response = await postApiData({url:`/api/hr/approval/overtimes/` + this.overtime.id, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed');
                document.getElementById('close_cancel_modal').click();
                this.getOvertimeList();
            }
        },
        
        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getOvertimeList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getOvertimeList(1);
        // },



        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/hr/overtime_fees/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getOvertimeList(1);
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },
        toggleIsCategory(){
            if(this.isCategory){
                this.isCategory = false;
            }
            else{
                this.isCategory = true;
            }
            this.categoryName = null;
        },
        closeCategoryWithDelay() {
            setTimeout(() => {
                this.isCategory = false;
                this.categoryName = null;
            }, 300); // 300ms delay (adjust as needed)
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
        this.getDepartmentList();
        this.getOvertimeList(1);
        this.getShiftList();
        this.getCategoryList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>