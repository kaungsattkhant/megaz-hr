<template>

    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Salary Batch
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
                    <a href="/salary_batch/create" v-if="feature.includes('salary-batch.create')"
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
                                    Batch Name
                                </th>
                                <th scope="col" class="">
                                    Staff
                                </th>
                                <th scope="col" class="">
                                    Salary Date
                                </th>
                                <th scope="col" class="" v-show="['salary-batch.delete', 'salary-batch.edit'].some(f => feature.includes(f))">

                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(batch, index) in salaryBatchList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ batch.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ batch.salary_batch_staff_count }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ batch.day_of_monthly }}
                                    </td>
                                    <td class="whitespace-nowrap" v-show="['salary-batch.delete', 'salary-batch.edit'].some(f => feature.includes(f))">
                                        <a :href="'/salary_batch/'+batch.id+'/edit'" class="pr-3"  v-if="feature.includes('salary-batch.edit')">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                        <button @click="deleteBtnClicked(batch.id)" data-te-toggle="modal" v-show="feature.includes('salary-batch.delete')"
                                            data-te-target="#deleteModal" id="delete-btn" class="pr-1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="salaryBatchList.length < 1 && !loading">
                                <td class="" colspan="5">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getSalaryBatchList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getSalaryBatchList(currentPage + 1)">
                                »</button>
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
                            <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                                Delete ?
                            </h5>
                            <button type="button" id="close_delete_modal"
                                class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
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
                            <button @click="deleteItem()" type="button"
                                class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button data-te-toggle="modal" data-te-target="#deleteModal" id="delete-btn" class="pr-1 hidden">
            </button>
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
            salaryBatchList: [],

            departmentList: [],
            roleList: [],

            selectedDepartment:null,
            selectedRole:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,

            url:'/api/hr/salary_batches',
            url_search:'',
            url_department:'',
            url_role:'',
            searchDepartment: null,
            searchRole: null,
            deleteId:null,

            feature: this.getFeature(),

            loading: true,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken', 'getFeature']),

        async getSalaryBatchList(pageNumber) {
            this.loading = true;
            let url = this.url + this.url_search + this.url_department + this.url_role;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.loading = false;
                this.salaryBatchList = response.data.data;
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
            this.getSalaryBatchList();
        },
        searchRoleChange(){
            this.url_role = '&role_id='+this.searchRole.id;
            this.getSalaryBatchList();
        },


        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getSalaryBatchList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getSalaryBatchList(1);
        // },



        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/hr/salary_batches/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getSalaryBatchList(1);
                document.getElementById('close_delete_modal').click();
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
        this.getSalaryBatchList();
        this.getDepartmentList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
