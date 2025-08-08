<template>
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    CV Forms
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
                        <select data-te-select-init data-te-select-placeholder="Select Status" @change="statusFilter"
                            data-te-select-filter="true" name="" id="" v-model="selectedStatus" class="input-ui">
                            <option :value="status" v-for="(status, statusIndex) in statusList" :key="statusIndex"> {{
                                status.name }} </option>
                        </select>
                    </div>
                    <div class=" !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Department"
                            @change="departmentFilter" data-te-select-filter="true" name="" id=""
                            v-model="selectedDepartment" class="input-ui">
                            <option :value="department" v-for="(department, departmentIndex) in departmentList"
                                :key="departmentIndex"> {{ department.name }} </option>
                        </select>
                    </div>
                    <div class=" !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Role" @change="roleFilter"
                            data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui">
                            <option :value="role" v-for="(role, roleIndex) in roleList" :key="roleIndex"> {{ role.name }}
                            </option>
                        </select>
                    </div>
                    <!-- <a href="/salary_setup/create" class="add-btn  h-8 whitespace-nowrap">
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
                                    Date
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
                                    Stages
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(item, index) in primaryList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.joined_date ? item.joined_date : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.department.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.roles[0] ? item.roles[0].name : '' }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.status }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <button @click="addJoinedDateBtnClicked(item)" data-te-toggle="modal"
                                            
                                            data-te-target="#add_date" id="date-btn" class="pr-3">
                                            <i class="fal fa-calendar-check"></i>
                                        </button>
                                        <button @click="addSalaryBtnClicked(item)" data-te-toggle="modal"
                                            v-show="feature.includes('cv-salary.create')"
                                            data-te-target="#add_salary" id="salary-btn" class="">
                                            <i class="fal fa-money-bill-wave pr-3"></i>
                                        </button>
                                        <a :href="'/cv/' + item.id + '/detail'" class="pr-3"
                                            v-show="feature.includes('cv.edit')">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                        <button @click="deleteBtnClicked(item.id)" data-te-toggle="modal"
                                            v-show="feature.includes('cv.delete')"
                                            data-te-target="#deleteModal" id="delete-btn" class="pr-1">
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


        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_salary" tabindex="-1" aria-labelledby="add_question_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[600px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_question_modalLabel">
                            Salary And Allowances
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="close_add_salary_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="grid grid-cols-6 gap-x-5 gap-y-1 text-sm">
                            <div class="mb-0 col-span-3 pb-0 rounded-md">
                                <label for="" class="block text-sm text-black mb-3">
                                    Salary
                                </label>
                                <input type="number" v-model="selectedSalary" autocomplete="off" :max="salarySetup?.basic_salary" @change="salaryChange"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <p class="mt-2 text-xs">
                                    Allowed Amount : <span class="pl-2">{{ salarySetup?.basic_salary.toLocaleString() }}</span>
                                </p>
                                
                            </div><div class="col-span-3"></div>
                            <p v-if="isExceed" class="col-span-6 mb-0 text-sm text-red-600">
                                The entered salary exceeds the role-based Salary
                            </p>
                            <div class="mb-8 mt-8 col-span-3 pb-0 rounded-md">
                                <label for="" class="block text-sm text-black mb-3">
                                    Allowance
                                </label>
                                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                    data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select Allowance" @change="allowanceChange"
                                        data-te-select-filter="true" name="" id="" v-model="selectedAllowance" class="input-ui !text-black text-sm">
                                        <option :value="allowance" v-for="(allowance, index) in allowanceList"
                                            :key="index"> {{ allowance.name }} </option>
                                    </select>
                                </div>
                                <!-- <input type="text" v-model="selectedAllowance" autocomplete="off"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
                            </div>
                            <div class="mb-8 mt-8 col-span-2 pb-0 rounded-md">
                                <label for="" class="block text-sm text-black mb-3">
                                    Amount
                                </label>
                                <input type="number" v-model="selectedAmount" autocomplete="off"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            
                            <div class="col-span-1 mb-8 mt-8">
                                <label class="label-form mb-3">&nbsp;</label>
                                <button type="button" class=" add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10" @click="addAllowanceAmount()" >
                                    Add
                                </button>
                            </div>


                            <div class="col-span-3">
                                <p>
                                    Allowance
                                </p>
                            </div>
                            <div class="col-span-1">
                                <p>
                                    Amount
                                </p>
                            </div>
                            <div class="col-span-1">
                                <p>
                                    
                                </p>
                            </div>
                            <div class="contents" v-if="selectedAllowanceAmountList.length > 0" v-for="(q,index) in selectedAllowanceAmountList">
                                <div class="col-span-3">
                                    <p>
                                        {{ q.name }}
                                    </p>
                                </div>
                                <div class="col-span-1">
                                    <p>
                                        {{ q.amount }}
                                    </p>
                                </div>
                                <div class="col-span-1">
                                    <button>
                                        <i class="fal fa-times" @click="deleteSeleted(index,selectedAllowanceAmountList)" ></i>
                                    </button>
                                </div>
                            </div>
                            <div v-else class="col-span-6 text-center mb-3 pt-2">
                                <p class="text-gray-500">
                                    No Data Here!
                                </p>
                            </div>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="addSalaryAndAllowance"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- joined date -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_date" tabindex="-1" aria-labelledby="add_question_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_question_modalLabel">
                            Add Joined Date
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="close_add_joined_date_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-0 col-span-3 pb-0 rounded-md">
                            <label for="" class="block text-sm text-black mb-3">
                                Date
                            </label>
                            <input type="date" v-model="selectedJoinedDate" autocomplete="off"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="addJoinedDate"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Add
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
import Multiselect from 'vue-multiselect';
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import { getCurrentTime, getCurretDateTime } from "../../utilities/datetime-helpers";

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            primaryList: [],

            departmentList: [],
            roleList: [],
            statusList: [
                { value: 'received', name: 'Received' },
                { value: 'pending', name: 'Pending' },
                { value: 'confirmed', name: 'Confirmed' },
                { value: 'cancelled', name: 'Cancelled' },
            ],

            selectedDepartment: null,
            selectedRole: null,
            selectedStatus: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,

            overtime: null,

            url: '/api/hr/cvs',
            url_search: '',
            url_department: '',
            url_role: '',
            url_status: '',
            deleteId: null,

            salarySetup: null,
            selectedItem: null,
            selectedSalary: null,
            allowanceList: [],
            selectedAllowance: null,
            selectedAmount: null,
            selectedAllowanceAmountList: [],

            isExceed: false,
            selectedJoinedDate: null,

            feature: this.getFeature(),
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment', 'getToken', 'getFeature']),

        async getPrimaryList(pageNumber) {
            let url = '';
            if (this.url_search) {
                url = this.url + this.url_search;
            }
            else {
                url = this.url + this.url_status + this.url_department + this.url_role;
            }
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.primaryList = response.data.data;
            }
        },
        async getDepartmentList() {
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        statusFilter() {
            this.url_status = '?status=' + this.selectedStatus.value
            if (this.selectedRole) {
                this.roleFilter();
            }
            else {
                this.getPrimaryList();
            }
        },
        departmentFilter() {
            this.selectedRole = null;
            this.roleList = this.selectedDepartment.roles;
        },
        roleFilter() {
            if (this.url_status) {
                this.url_department = '&departmentIds[]=' + this.selectedDepartment.id;
                this.url_role = '&roleIds[]=' + this.selectedRole.id;
            }
            else {
                this.url_department = '?departmentIds[]=' + this.selectedDepartment.id;
                this.url_role = '&roleIds[]=' + this.selectedRole.id;
            }
            this.getPrimaryList();
        },
        skillFilter() {

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


        async addSalaryBtnClicked(item){
            this.selectedItem = item;
            this.selectedSalary = null;
            this.selectedAllowanceAmountList = [];
            let response = await getApiData({ url: '/api/hr/salary_setup/department/' + item.department.id + '/role/' + item.roles[0].id, token: this.getToken() });
            if (response.data) {
                this.salarySetup = response.data;
                response.data.salary_allowances.forEach(item => {
                    this.allowanceList.push(item.allowance)
                });
            }
            else{
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },
        allowanceChange(){
            this.selectedAmount = this.selectedAllowance.amount
        },
        async addAllowanceAmount(){
            this.selectedAllowanceAmountList.push({
                name: this.selectedAllowance ? this.selectedAllowance.name : null,
                allowance_id: this.selectedAllowance ? this.selectedAllowance.id : null,
                amount: this.selectedAmount,
            })
            this.selectedAllowance = null;
            this.selectedAmount = null;
        },
        salaryChange(){
            if(this.selectedSalary > this.salarySetup.basic_salary){
                this.isExceed = true;
            }
            else{
                this.isExceed = false;
            }
        },
        async addSalaryAndAllowance(){
            if(!this.selectedSalary){
                this.alertValidationMessage(`Salary `);
                return 1;
            }
            else{
                let formData = new FormData();
                formData.append('basic_salary',this.selectedSalary);
                formData.append('role_id',this.selectedItem.roles[0].id);
                formData.append('staff_id',this.selectedItem.id);
                formData.append('salary_setup_id',this.salarySetup.id);
                formData.append('salary_allowances',JSON.stringify(this.selectedAllowanceAmountList));
                let response = await postApiData({url:`/api/hr/new-staff-salary`, form_data:formData, token:this.getToken()})
                if(response.success){
                    this.getPrimaryList();
                    document.getElementById('close_add_salary_modal').click();
                }
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            }
        },


        async addJoinedDateBtnClicked(item){
            this.selectedJoinedDate = null;
            this.selectedItem = item;
            // let response = await getApiData({ url: '/api/hr/new-staff-join-date/', token: this.getToken() });
            // if (response.data) {
            //     this.salarySetup = response.data;
            //     response.data.salary_allowances.forEach(item => {
            //         this.allowanceList.push(item.allowance)
            //     });
            // }
        },
        async addJoinedDate(){
            if(!this.selectedJoinedDate){
                this.alertValidationMessage(`Join Date `);
                return 1;
            }
            else{
                let formData = new FormData();
                formData.append('staff_id',this.selectedItem.id);
                formData.append('joined_date',this.selectedJoinedDate);
                let response = await postApiData({url:`/api/hr/new-staff-join-date`, form_data:formData, token:this.getToken()})
                if(response.success){
                    this.getPrimaryList();
                    document.getElementById('close_add_joined_date_modal').click();
                }
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            }
        },


        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/hr/cvs/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getPrimaryList(1);
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
        this.getPrimaryList();
        this.getDepartmentList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>