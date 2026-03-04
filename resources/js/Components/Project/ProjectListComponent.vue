<template>

    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Projects
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
                    <!-- <div class=" !text-sm" data-te-select-wrapper-ref>
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
                    </div> -->
                    <button data-te-toggle="modal" data-te-target="#addProjectModal" class="add-btn  h-8 whitespace-nowrap">
                        Add New
                    </button>
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
                                        <a class="pr-2" :href="'/project/' + item.id + '/instruction'">
                                            <i class="fal fa-clock"></i>
                                        </a>            
                                        <!-- <button data-te-toggle="modal"
                                        @click="addStaffBtnClicked(item.id)"
                                            data-te-target="#add_staff_modal" id="delete-btn" class="pr-1">
                                            <i class="fas fa-users-medical"></i>
                                        </button> -->
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <!-- <div class="flex justify-center">
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
                    </div> -->
                </div>
            </div>
        </div>
    </div>        

        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="addProjectModal" tabindex="-1" aria-labelledby="add_category_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_category_modalLabel">
                            Create Project
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="btn-close-create-project-modal">
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
                            <input type="text" v-model="projectName" autocomplete="off"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <LoadingButton
                            :loading="projectCreateLoading"
                            text="Create"
                            loadingText="Loading..."
                            @click="createProject"
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

            url: '/api/projects',    

            projectName: null,

            projectCreateLoading: false,
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
            let response = await getApiData({ url: this.url, token: this.getToken() });
            if (response.data) {
                this.primaryList = response.data;
            }
        },

        createProject(){
            this.projectCreateLoading = true;
            if(!this.projectName){
                this.projectCreateLoading = false;
                this.alertValidationMessage("Name of the project");
                return;
            }
            let formData = new FormData();
            formData.append('name', this.projectName);
            postApiData({url: `/api/projects`, form_data: formData, token: this.getToken()})
            .then((response)=>{
                this.projectCreateLoading = false;
                if(response.data){
                    this.primaryList.push(response.data);
                    this.projectName = null;                    
                }                
            });
            document.getElementById('btn-close-create-project-modal').click();
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
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
