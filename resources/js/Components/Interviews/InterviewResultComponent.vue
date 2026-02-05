<template>
    
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Interview Results
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
                        <select data-te-select-init data-te-select-placeholder="Select Type"
                            @change="resultFilter" data-te-select-filter="true" name="" id=""
                            v-model="selectedType" class="input-ui">
                            <option :value="result" v-for="(result, resultIndex) in resultList"
                                :key="resultIndex"> {{ result.name }} </option>
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
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Department
                                </th>
                                <th scope="col" class="">
                                    Role
                                </th>
                                <th scope="col" class="">
                                    Interview
                                </th>
                                <th scope="col" class="">
                                    Psychology Test
                                </th>
                                <th scope="col" class="">
                                    Hard Skill
                                </th>
                                <th scope="col" class="">
                                    Total
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
                                        {{ item.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.department_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.role_names }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.interview_count }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ item.eq_mark }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.it_mark }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.total_mark }}
                                    </td>
                                    <!-- <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#create_interview_modal" class="mx-4"
                                            @click="createInterviewBtnClicked(item)">
                                            <i class="fal fa-plus"></i>
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


        <button data-te-toggle="modal" data-te-target="#create_interview_modal" class="mr-4 hidden">
            <i class="fas fa-bars"></i>
        </button>
            <!-- create interview Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_interview_modal" tabindex="-1" aria-labelledby="create_interview_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_interview_modalLabel">
                            Create Interview
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="close_interview_modal">  
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Interview
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Interview" @change="interviewSelectedChange"
                                    data-te-select-filter="true" name="" id="" v-model="selectedInterview" class="input-ui !text-black text-sm">
                                    <option :value="interview" v-for="(interview, index) in interviewList"
                                        :key="index"> {{ interview.name }} </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="createInterview()"
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

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            primaryList: [],

            departmentList: [],
            roleList: [],
            resultList: [
                {value: 'interview', name: 'Interview'},
                {value: 'exam', name: 'Exam'},
            ],

            selectedType: {value: 'interview', name: 'Interview'},
            selectedDepartment: null,
            selectedRole: null,

            interviewList: [],
            selectedInterview: null,    

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,
            selectedItem: null,


            url: '/api/hr/interviews-results',
            url_search: '',
            url_department: '',
            url_role: '',
            url_search: '',
            url_type: '',
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment', 'getToken']),

        async getPrimaryList(pageNumber) {
            let url = this.url + '?page=' + pageNumber + this.url_type + this.url_search + this.url_department + this.url_role;
            // if (this.url_search) {
            //     url = this.url + this.url_search;
            // }
            // else {
            //     url = this.url + this.url_department + this.url_role;
            // }
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
        departmentFilter() {
            this.selectedRole = null;
            this.roleList = this.selectedDepartment.roles;
        },
        roleFilter() {
            this.url_search = '';
            this.searchInput = null;
            this.url_department = '&departmentIds[]=' + this.selectedDepartment.id;
            this.url_role = '&roleIds[]=' + this.selectedRole.id;
            this.getPrimaryList(1);
        },
        resultFilter() {
            this.url_search = '';
            this.searchInput = null;
            this.url_department = '';
            this.url_role = '';
            this.selectedRole = null;
            this.selectedDepartment = null;
            this.url_type = '&type=' + this.selectedType.value;
            this.getPrimaryList(1);
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


        async createInterviewBtnClicked(item){
            this.selectedItem = item;
            let response = await getApiData({ url: '/api/hr/interviews-by-role/' + item.roles[0].id, token: this.getToken() });
            if (response.data) {
                this.interviewList = response.data.data;
            }
        },
        interviewSelectedChange(){

        },
        createInterview(){
            if(!this.selectedInterview){
                this.alertValidationMessage(`Interview`);
                return 1;
            }
            else{
                window.location.replace('/interviews/' + this.selectedInterview.id+ '/create/' + this.selectedItem.id + '/cv')
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
        this.getPrimaryList(1);
        this.getDepartmentList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>