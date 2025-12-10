<template>

    <div class="margin-bg ">
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
                                    Status
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

                        <tr class=" !text-center" v-else-if="paySlipList.length < 1">
                            <td class="" colspan="5">
                                No Data Here
                            </td>
                        </tr>
                        <tbody v-else>
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
                                        <button @click="btnClickedComfirmPayslip(salary)"
                                            class=" px-4 py-1.5" data-te-toggle="modal" data-te-target="#check_modal" >
                                            <i class="far fa-check-double" :class="salary.is_confirm === 1 ? 'text-green-600' : 'text-gray-500'"></i>
                                        </button>
                                        
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

                    <button 
                        class=" px-4 py-1.5" data-te-toggle="modal" data-te-target="#check_modal" >
                    </button>
                    <div data-te-modal-init
                        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                        id="check_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div data-te-modal-dialog-ref
                            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                            <div
                                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                                <div
                                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                                    <!--Modal title-->
                                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                                        Confirm ?
                                    </h5>
                                    <!--Close button-->
                                    <button type="button" id="close_confirm_modal"
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
                                    <button @click="confirmPaySlip()" type="button"
                                        class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                                        Confirm
                                    </button>
                                </div>
                            </div>
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

            loading: true,

            paySlipDetail: null,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getPaySlipList() {
            this.loading = true;
            let url = '/api/hr/pay_slips';
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.loading = false;
                this.paySlipList = response.data.data;
            }
        },
        btnClickedComfirmPayslip(salary){
            this.paySlipDetail = salary;
        },
        async confirmPaySlip() {
            this.loading = true;
            let url = '/api/hr/pay_slips/confirm/' + this.paySlipDetail.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.loading = false;
                this.getPaySlipList();
                document.getElementById('close_confirm_modal').click();
            }
            else{
                this.$notify({
                    title: 'Error',
                    text: response.message,
                    type: 'error'
                });
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
