<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Salary Batch
        </p>
    </div>
    <div class="mt-4 bg-white">
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
                <a href="/salary_batch/create"
                    class="add-btn  h-8 whitespace-nowrap">
                    Add New
                </a>
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
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
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
                                    <td class="whitespace-nowrap">
                                        <a :href="'/salary_batch/'+batch.id+'/edit'" class="pr-3">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                        <button @click="deleteBtnClicked(batch.id)" data-te-toggle="modal"
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

        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken']),

        async getSalaryBatchList(pageNumber) {
            let url = this.url + this.url_search + this.url_department + this.url_role;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
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