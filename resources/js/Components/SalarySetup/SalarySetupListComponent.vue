<template>
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Salary Setup
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
                        <select data-te-select-init data-te-select-placeholder="Select Department" @change="changeDepartment"
                            data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui">
                            <option :value="department" v-for="(department, departmentIndex) in departmentList"
                                :key="departmentIndex"> {{ department.name }} </option>
                        </select>
                    </div>
                    <div class=" !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Role" @change="changeRole"
                            data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui">
                            <option :value="role" v-for="(role, roleIndex) in roleList"
                                :key="roleIndex"> {{ role.name }} </option>
                        </select>
                    </div>
                    <a href="/salary_setup/create" v-if="feature.includes('salary-setup.create')"
                        class="add-btn  h-8 whitespace-nowrap">
                        Add New
                    </a>
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
                                    Role
                                </th>
                                <th scope="col" class="">
                                    Department
                                </th>
                                <th scope="col" class="">
                                    Salary
                                </th>
                                <th scope="col" class="">
                                    Allowance
                                </th>
                                <th scope="col" class="">
                                    Net Salary
                                </th>
                                <th scope="col" class="" v-show="['salary-setup.edit', 'salary-setup.delete'].some(f => feature.includes(f))">

                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(salary, index) in salaryList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.role.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.role.department.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.basic_salary }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.total_allowances_amount - salary.total_deductions_amount }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.net_salary }}
                                    </td>
                                    
                                    <td class="whitespace-nowrap" v-show="['salary-setup.edit', 'salary-setup.delete'].some(f => feature.includes(f))">
                                        <a :href="'/salary_setup/'+salary.id+'/edit'" class="pr-3" v-if="feature.includes('salary-setup.edit')">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                        <button @click="deleteBtnClicked(salary.id)" data-te-toggle="modal" v-show="feature.includes('salary-setup.delete')"
                                            data-te-target="#deleteModal" id="delete-btn" class="pr-1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="salaryList.length < 1 && !loading">
                                <td class="" colspan="7">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getSalaryList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getSalaryList(currentPage + 1)">
                                »</button>
                        </div>
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
            salaryList: [],
            
            departmentList: [],
            roleList: [],
            
            selectedDepartment:null,
            selectedRole:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,

            overtime: null,

            url:'/api/hr/salary_setups',
            url_search:'',
            url_department:'',
            url_role:'',
            deleteId:null,

            feature: this.getFeature(),
            loading: false,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken', 'getFeature']),

        async getSalaryList(pageNumber) {
            this.loading = true;
            let url = this.url + this.url_search + this.url_department + this.url_role;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.salaryList = response.data.data;
            }
        },
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        changeDepartment(){
            this.roleList = this.selectedDepartment.roles;
            this.selectedRole = null;
            this.url_role = '';
            this.url_department = '?department_id=' + this.selectedDepartment.id;
            this.getSalaryList();
        },
        changeRole(){
            this.url_role = '&role_id=' + this.selectedRole.id;
            this.getSalaryList();
        },

        
        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getSalaryList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getSalaryList(1);
        // },



        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/hr/overtime_fees/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getSalaryList(1);
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
        this.getSalaryList();
        this.getDepartmentList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>