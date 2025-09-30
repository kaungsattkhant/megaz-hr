<template>
    
    <div class="mt-4 bg-white ">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Pay Slip
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
                        <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
                            data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui">
                            <option :value="department" v-for="(department, departmentIndex) in departmentList"
                                :key="departmentIndex"> {{ department.name }} </option>
                        </select>
                    </div>
                    <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type" @change="selectedRoleChange()"
                            data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui">
                            <option :value="role" v-for="(role, roleIndex) in roleList"
                                :key="roleIndex"> {{ role.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="flex pr-0 gap-x-4">
                    
                    <!-- <a href="/leave_allowance/create"
                        class="add-btn  h-8 whitespace-nowrap">
                        Add New
                    </a> -->
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
                                    Role
                                </th>
                                <th scope="col" class="">
                                    Salary
                                </th>
                                <th scope="col" class="">
                                    Allowance
                                </th>
                                <th scope="col" class="">
                                    Overtime
                                </th>
                                <th scope="col" class="">
                                    Net Salary
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
                            <div class="contents" v-for="(salary, index) in paySlipList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.staff.department.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.staff.roles[0].name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.basic_salary }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.total_allowance }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.overtime }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.net_salary }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#add_allowance_modal" id="edit-btn"
                                            v-show="feature.includes('pay-slip.edit')"
                                            class="pr-3" @click="btnClickedAddAllowanceAndDeduction(salary, index)">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="paySlipList.length < 1 && !loading">
                                <td class="" colspan="9">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

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
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        Multiselect,
        TableSkeleton
    },
    data() {
        return {
            paySlipList: [],

            departmentList:[],
            roleList: [],
            selectedDepartment:null,
            selectedRole: null,
            
            feature: this.getFeature(),
            loading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getPaySlipList() {
            this.loading = true;
            let url = '/api/hr/pay_slips';
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.paySlipList = response.data.data;
            }
        },
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        selectedDepartmentChange(){
            // this.url_department = '?department_id='+this.selectedDepartment.id;
            this.roleList = this.selectedDepartment.roles;
            // this.getLeaveAllowanceList();
        },
        selectedRoleChange(){
            // this.url_role = '&role_id='+this.selectedRole.id;
            // this.getLeaveAllowanceList();
        },
        
        
        
        
        
        

        // deleteBtnClicked(id) {
        //     this.deleteId = id;
        // },
        // async deleteItem() {
        //     let response = await deleteApiData({ url: `/api/hr/overtime_fees/` + this.deleteId, token: this.getToken() });
        //     if (response.success) {
        //         this.getAllowanceList(1);
        //     }
        //     else {
        //         this.$notify({
        //             title: `Input validation`,
        //             text: response.message,
        //             type: "warn"
        //         });
        //     }
        // },



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
        this.getPaySlipList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>