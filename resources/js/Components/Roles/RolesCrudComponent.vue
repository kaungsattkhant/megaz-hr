<template>
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Roles
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex">
                    <!-- <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search">
                        <i class="fal fa-search"></i>
                    </label> -->
                    <div class="w-full multiselect-fontsize" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Department" v-model="filterDepartment"
                        data-te-select-filter="true" @change="filterDepartmentChange()" class="input-ui w-full !text-sm">
                            <!-- <option value="all">All</option> -->
                            <option v-for="(department,index) in departmentList" :key="index" :value="department"> {{ department.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end flex-col">

                    <button type="button" v-if="getFeature().includes('role.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal" @click="name = null, selectedDepartment = null, selectedRole = null">
                        Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Name
                                </th>
                                <th scope="col" class="  ">
                                    Department
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
                        <tbody v-else>
                            <!-- looping start -->
                            <div class="contents" v-for="(role, index) in roleList" :key="index">
                                <tr class="">
                                    <td class="">
                                        {{ perPage * (currentPage - 1) + (index+1) }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ role.name }}
                                    </td>

                                    <td class="whitespace-nowrap ">
                                        {{ role.department.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button type="button" class="pr-3" v-if="getFeature().includes('role.edit')"
                                            data-te-toggle="modal" data-te-target="#edit_modal" @click="editRolesBtnClicked(role)">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <input :checked="role.is_available == 1" @change="isActiveToggled(role.id)"
                                            class="me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                            before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5
                                            after:w-5 after:rounded-full after:border-none after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                            after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                            checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                            checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                            focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                            focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                            checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                            checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] "
                                            type="checkbox" role="switch" />


                                        <!-- <button @click="deleteBtnClicked(role.id)" data-te-toggle="modal"
                                            data-te-target="#deleteModal" id="delete-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button> -->
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="roleList.length < 1 && !loading">
                                <td class="" colspan="4">
                                    No Data Here
                                </td>
                            </tr>

                            <!-- looping end -->
                        </tbody>
                    </table>
                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getRolesList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getRolesList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Create Role
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none" id="close_create_modal"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Role Name
                                </label>
                                <input type="text" placeholder="Role Name" v-model="name" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Department
                                </label>
                                <select name="" id="" v-model="selectedDepartment" class="input-ui">
                                    <option :value="department.id"
                                        v-for="(department, departmentIndex) in departmentList" :key="departmentIndex">
                                        {{ department.name }} </option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Parent Role
                                </label>
                                <multiselect
                                v-model="selectedRole"
                                :options="parentRoleList"
                                :close-on-select="true"
                                :clear-on-select="false"
                                :preserve-search="true"
                                placeholder="Select Parent Role"
                                label="name"
                                track-by="id"
                                :preselect-first="false" >
                                <template #option="{ option }">
                                    <span>
                                      {{ option.name }} ({{ option.department?.name }})
                                    </span>
                                  </template>
                                </multiselect>
                            </div>
                            

                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <!-- <button type="button" @click="createRolesBtnClicked"
                                class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                                Create
                            </button> -->
                            <LoadingButton
                                :loading="buttonLoading"
                                text="Create"
                                loadingText="Creating..."
                                @onClick="createRolesBtnClicked"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="edit_modal" tabindex="-1" aria-labelledby="edit_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="edit_modalLabel">
                                Update Role
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none" id="close_edit_modal"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Role Name
                                </label>
                                <input type="text" placeholder="Role Name" v-model="nameEdit" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Department
                                </label>
                                <select name="" id="" v-model="selectedDepartmentEdit" class="input-ui">
                                    <option :value="department.id"
                                        v-for="(department, departmentIndex) in departmentList" :key="departmentIndex">
                                        {{ department.name }} </option>
                                </select>
                                
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Parent Role
                                </label>
                                <multiselect
                                v-model="selectedRoleEdit"
                                :options="parentRoleList"
                                :close-on-select="true"
                                :clear-on-select="false"
                                :preserve-search="true"
                                placeholder="Select Parent Role"
                                label="name"
                                track-by="id"
                                :preselect-first="false" ></multiselect>
                            </div>

                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <!-- <button type="button" @click="editRole"
                                class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                                Create
                            </button> -->
                            <LoadingButton
                                :loading="buttonLoading"
                                text="Edit"
                                loadingText="Editing..."
                                @onClick="editRole"
                            />
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
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

import Multiselect from 'vue-multiselect';
import TableSkeleton from "../Common/TableSkeleton.vue";
import LoadingButton from "../Common/LoadingButton.vue";

export default {
    components: {
        Multiselect,
        TableSkeleton,
        LoadingButton
    },
    data() {
        return {


            departmentList: [],
            roleList: [],
            parentRoleList: [],
            name: null,
            selectedDepartment: null,
            selectedItem: null,
            deleteId: null,

            selectedRole:null,
            nameEdit:null,
            selectedDepartmentEdit:null,
            selectedRoleEdit: null,

            filterDepartment:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            department_url:'',
            feature: this.getFeature(),

            loading: true,
            buttonLoading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getDepartmentList() {
            const response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        async filterDepartmentChange(){
            // const response = await getApiData({ url: `/api/roles?department_id=${this.filterDepartment.id}&page=2`, token: this.getToken() });
            // if (response.data) {
            //     this.roleList = response.data;
            // }
            this.department_url = 'department_id='+this.filterDepartment.id+'&'
            this.getRolesList(1);
        },
        async getRolesList(pageNumber) {
            this.loading = true;
            console.log('loading');
            const response = await getApiData({ url: `/api/roles?${this.department_url}page=${pageNumber}`, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.roleList = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
                console.log('loading done');
            }
        },
        async getParentRoleList() {
            const response = await getApiData({ url: `/api/roles`, token: this.getToken() });
            if (response.data) {
                this.parentRoleList = response.data;
            }
        },

        createRolesBtnClicked() {
            this.createRole();
        },

        async createRole() {
            this.buttonLoading = true;
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('department_id', this.selectedDepartment);
            if(this.selectedRole){
                formData.append('parent_id', this.selectedRole.id);
            }
            
            let response = await postApiData({ url: '/api/roles', form_data: formData, token: this.getToken() });
            if (response.success) {
                if(this.filterDepartment){
                    this.filterDepartmentChange();
                }
                else{
                    this.getRolesList(1);
                }
                console.log("success")
                document.getElementById('close_create_modal').click();
                setTimeout(() => {
                    this.buttonLoading = false
                }, 500)
            }
            else {
                this.buttonLoading = false;
                this.$notify({
                    text: message,
                    type: "error"
                });
            }
        },
        editRolesBtnClicked(role) {
            this.selectedItem = role;
            this.nameEdit = role.name;
            this.selectedDepartmentEdit = role.department.id
            if(role.parent != null){
                this.selectedRoleEdit = this.parentRoleList.find(item => item.id === role.parent.id);
                console.log('role parent id', role)
            }
            else{
                this.selectedRoleEdit = null;
                console.log('no role parent id', role)
            }
            // this.editRole();
        },

        async editRole() {
            this.buttonLoading = true;
            let formData = new FormData();
            formData.append('name', this.nameEdit);
            formData.append('department_id', this.selectedDepartmentEdit);
            if(this.selectedRoleEdit){
                formData.append('parent_id', this.selectedRoleEdit.id);
            }
            else {
                formData.append('parent_id', "")
            }
            let response = await postApiData({ url: '/api/roles/'+this.selectedItem.id, form_data: formData, token: this.getToken() });
            if (response.success) {
                if(this.filterDepartment){
                    this.filterDepartmentChange();
                }
                else{
                    this.getRolesList(1);
                }
                console.log("success")
                document.getElementById('close_edit_modal').click();
                setTimeout(() => {
                    this.buttonLoading = false
                }, 500)
            }
            else {
                this.buttonLoading = false;
                this.$notify({
                    text: message,
                    type: "error"
                });
            }
        },


        async isActiveToggled(id) {
            let index = this.roleList.findIndex(role => role.id == id);
            if (index != -1) {
                let url = `/api/roles/${id}/available_toggle`;
                let formData = new FormData();
                let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
                if(response.success){
                    if (this.roleList[index].is_available == 1) {
                        this.roleList[index].is_available = 0;
                    }
                    else {
                        this.roleList[index].is_available = 1;
                    }
                }
                else{
                    if (this.roleList[index].is_available == 0) {
                        this.roleList[index].is_available = 1;
                    }
                    else {
                        this.roleList[index].is_available = 0;
                    }
                    this.$notify({
                        title: 'Error',
                        text: response.message,
                        type: 'error'
                    });
                }
                this.getRolesList(1);
            }
        },
        // deleteBtnClicked(id) {
        //     this.deleteId = id;
        // },



        
        // async confirmDeleteBtnClicked() {
        //     let url = `/api/roles/${this.deleteId}`;
        //     let response = await deleteApiData({ url: url, token: this.getToken() });
        //     if (response.success) {
        //         alert(`deleted`);
        //     }
        // }

    },
    mounted() {

        this.getRolesList(1);
        this.getParentRoleList();
        this.getDepartmentList();
        initTE({ Modal, Select, Ripple });
    }
}
</script>
