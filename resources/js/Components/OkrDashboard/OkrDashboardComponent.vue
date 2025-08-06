<template>
    

    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    OKR Dashboard
                </p>
            </div>
            <div class="btn-container pt-10">
                <notifications position="top center" />
                <!-- <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
                </div> -->
                <div class="flex pr-0 gap-x-4">
                    <div class="relative">
                        <label for="search" class="border border-gray-200 rounded bg-white text-xs mx-2 px-2 py-2 absolute left-0 ml-0 -top-[90%] border-b-0"> From </label>
                        <input type="date" v-model="fromDate" class="search-input rounded " @change="fromDateChanged()">
                    </div>

                    <div class="relative">
                        <label for="search" class="border border-gray-200 rounded bg-white text-xs mx-2 px-2 py-2 absolute left-0 ml-0 -top-[90%] border-b-0"> To </label>
                        <input type="date" v-model="toDate" class="search-input rounded" @change="toDateChanged()">
                    </div>
                    
                </div>
                <div class="flex pr-0 gap-x-4">
                    <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
                            data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui">
                            <option :value="department" v-for="(department, departmentIndex) in departmentList"
                                :key="departmentIndex"> {{ department.name }} </option>
                        </select>
                    </div>
                    <!-- <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Role" @change="selectedRoleChange()"
                            data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui">
                            <option :value="role" v-for="(role, roleIndex) in roleList"
                                :key="roleIndex"> {{ role.name }} </option>
                        </select>
                    </div> -->
                    <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Role" @change="selectedRoleChange()"
                            data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui">
                            <option :value="role" v-for="(role, roleIndex) in roleList"
                                :key="roleIndex"> {{ role.name }} </option>
                        </select>
                    </div>
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
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Department
                                </th>
                                <th scope="col" class="">
                                    Position
                                </th>
                                <th scope="col" class="">
                                    Assigned Task
                                </th>
                                <th scope="col" class="">
                                    In progess Task
                                </th>
                                <th scope="col" class="">
                                    Completed Task
                                </th>
                                <th scope="col" class="">
                                    Approve Task
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(okr, index) in okrList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ okr.staff_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ okr.department_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ okr.role_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ okr.assigned_tasks }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ okr.in_progress_tasks }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ okr.completed_tasks }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ okr.approved_tasks }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- {{ okr.okr_total_point }} -->
                                    </td>
                                    
                                    <!-- <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3"
                                            @click="editBtnClicked(gps, index)">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <button @click="deleteBtnClicked(gps.id)"
                                            data-te-toggle="modal" data-te-target="#deleteModal" id="delete-btn" class="pr-1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td> -->
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getOkrList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOkrList(currentPage + 1)">
                                »</button>
                        </div>
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

export default {
    data() {
        return {
            okrList: [],
            staffList:[],
            departmentList:[],
            roleList:[],
            selectedStaff:null,
            selectedRole:null,
            selectedDepartment:null,
            fromDate:null,
            toDate:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/dashboard-okr',
            url_department:'',
            url_role:'',
            url_staff:'',
            url_from:'',
            url_to:'',
            deleteId:null,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        
        
        async getOkrList(pageNumber) {
            // let url = this.url + pageNumber + this.url_search + this.url_staff + this.url_from + this.url_to;
            let url = this.url + this.url_staff + this.url_from + this.url_to + this.url_department + this.url_role;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.okrList = response.data.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
            }
        },
        async getStaffList(){
            let url = '/api/staffs'
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data.data;
            }
        },
        async getDepartmentList(){
            let response = await getApiData({url: `/api/departments`, token: this.getToken()});
            if(response.data){
                this.departmentList = response.data;
            }
        },
        selectedDepartmentChange(){
            // this.getRoleList();
            this.roleList = this.selectedDepartment.roles;
            this.selectedRole = null;
            this.url_from = '';
            this.url_to = '';
            this.url_role = '';
            this.url_department = '?department_id=' + this.selectedDepartment.id;
            this.getOkrList();
            this.fromDate = null;
            this.toDate = null;
            // this.selectedStaff = null;
        },
        async getRoleList(){
            let response = await getApiData({url: '/api/roles_department/' + this.selectedDepartment.id , token: this.getToken()});
            if(response.data){
                this.roleList = response.data;
            }
        },
        selectedRoleChange(){
            this.url_from = '';
            this.url_to = '';
            this.url_staff = '';
            this.url_role = '&role_id=' + this.selectedRole.id;
            this.getOkrList();
            this.fromDate = null;
            this.toDate = null;
            this.selectedStaff = null;
        },
        selectedStaffChanged(){
            this.url_from = '';
            this.url_to = '';
            this.url_department = '';
            this.url_role = '';
            this.url_staff = '?staff_id=' + this.selectedStaff.id
            this.getOkrList();
            this.selectedDepartment = null;
            this.selectedRole = null;
            this.roleList = null;
            this.fromDate = null;
            this.toDate = null;
        },
        fromDateChanged(){
            this.url_staff = '';
            this.url_department = '';
            this.url_role = '';
            this.url_from = '?from_date=' + this.fromDate
            this.getOkrList();
            this.selectedDepartment = null;
            this.selectedRole = null;
            this.roleList = [];
            this.selectedStaff = null;
        },
        toDateChanged(){
            this.url_to = '&to_date=' + this.toDate
            this.getOkrList();
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
        this.getOkrList(1);
        this.getStaffList();
        this.getDepartmentList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
