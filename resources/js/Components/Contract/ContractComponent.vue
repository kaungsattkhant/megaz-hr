<template>

    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Contract List
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
                        <select data-te-select-init data-te-select-placeholder="Select Status" @change="typeFilter"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type" v-for="(type, typeIndex) in typeList" :key="typeIndex"> {{
                                type.name }} </option>
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
                    <a href="/contract/create" class="add-btn  h-8 whitespace-nowrap">
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
                                    Category
                                </th>
                                <th scope="col" class="">
                                    Type
                                </th>
                                <th scope="col" class="">
                                    Department
                                </th>
                                <th scope="col" class="">
                                    Role
                                </th>
                                <th scope="col" class="">
                                    Authorizer
                                </th>
                                <th scope="col" class="">
                                    Witness
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
                                        {{ item.contract_category.name }}
                                    </td>
                                    <td class="whitespace-nowrap capitalize">
                                        {{ item.type }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.role_id }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.role_id }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.company_authorizer_id }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.witness_id }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- <a :href="'/exam/' + item.id + '/edit'" class="pr-3">
                                            <i class="fal fa-pen"></i>
                                        </a> -->
                                        <button data-te-toggle="modal"
                                        @click="addStaffBtnClicked(item.id)"
                                            data-te-target="#add_staff_modal" id="delete-btn" class="pr-1">
                                            <i class="fas fa-users-medical"></i>
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


    <!-- Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_staff_modal" tabindex="-1" aria-labelledby="add_category_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_category_modalLabel">
                            Add Staff to Contract
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="btn-close-add-staff-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4 pb-0 rounded-md">
                            <label for="" class="block text-sm text-black mb-3">
                                Name
                            </label>
                            <multiselect
                            v-model="selectedStaff"
                            :options="staffList"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Select Staff"
                            label="name"
                            track-by="id"
                            :preselect-first="false">
                            </multiselect>
                            <!-- <input type="text" v-model="categoryName" autocomplete="off"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <LoadingButton
                            :loading="addStaffLoading"
                            text="Add"
                            loadingText="Loading..."
                            @click="addStaffToContract"
                        />
                        <!-- <button type="button" @click="createCategory()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button> -->
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
import LoadingButton from '../Common/LoadingButton.vue';

export default {
    components: {
        Multiselect,
        LoadingButton
    },
    data() {
        return {
            primaryList: [],

            departmentList: [],
            roleList: [],
            typeList: [
                {value: 'orientation', name: 'Orientation'},
                {value: 'occasional', name: 'Occasional'}
            ],

            selectedDepartment: null,
            selectedRole: null,
            selectedType: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,

            overtime: null,

            url: '/api/contracts',
            url_search: '',
            url_department: '',
            url_role: '',
            url_type: '',
            deleteId: null,

            staffList: [],
            selectedStaff: [],

            addStaffLoading: false,

            id: null,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment', 'getToken']),

        async getPrimaryList(pageNumber) {
            let url = '';
            if (this.url_search) {
                url = this.url + this.url_search;
            }
            else {
                url = this.url + this.url_type + this.url_department + this.url_role;
            }
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.primaryList = response.data;
            }
        },
        async getDepartmentList() {
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        typeFilter() {
            this.url_type = '?type=' + this.selectedType.value
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
            if (this.url_type) {
                this.url_department = '&department_id=' + this.selectedDepartment.id;
                this.url_role = '&role_id=' + this.selectedRole.id;
            }
            else {
                this.url_department = '?department_id=' + this.selectedDepartment.id;
                this.url_role = '&role_id=' + this.selectedRole.id;
            }
            this.getPrimaryList();
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
            let response = await deleteApiData({ url: `/api/` + this.deleteId, token: this.getToken() });
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

        getStaffList(){
            getApiData({url: `/api/staffs`, token: this.getToken()})
            .then((response)=>{
                this.staffList = response.data;
            });
        },

        addStaffBtnClicked(id){
            this.id = id;
        },

        addStaffToContract(){
            this.addStaffLoading = true;
            if(this.selectedStaff.length < 1){
                this.alertValidationMessage('staff for contract');
                this.addStaffLoading = false;
            }
            let formData = new FormData();
            formData.append('contract_id', this.id);
            let staffIds = [];
            this.selectedStaff.forEach(staff => {
                formData.append('staff_ids[]', staff.id);
                staffIds.push(staff.id);
            });
            postApiData({url: `/api/contracts/add_staff`, form_data: formData, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.id = null;
                    this.selectedStaff = [];
                    this.addStaffLoading = false;
                    this.$notify({
                        text: `${response.message}`,
                        type: 'success'
                    });
                    document.getElementById('btn-close-add-staff-modal').click();
                }
            });
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
        this.getStaffList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
