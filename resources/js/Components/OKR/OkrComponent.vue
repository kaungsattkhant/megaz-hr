<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Objective Key Results
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <notifications position="top center" />

            <!-- <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">

                <a href="/OKR/create"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 ">
                    Add New
                </a>
            </div> -->
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>
                <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
            </div>
            <div class="flex pr-0 gap-x-4">
                <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" v-model="selectedDepartment"
                    data-te-select-filter="true" class="input-ui w-full">
                        <option value="all">All</option>
                        <option v-for="(department,index) in departmentList" :key="index" :value="department"> {{ department.name }} </option>
                    </select>
                </div>
                <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" v-model="selectedRole"
                    data-te-select-filter="true" class="input-ui w-full">
                        <option value="all">All</option>
                        <option v-for="(role,index) in roleList" :key="index" :value="role"> {{ role.name }} </option>
                    </select>
                </div>
                <a href="/OKR/create"
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
                                    Objective Name
                                </th>
                                <th scope="col" class="">
                                    Department
                                </th>

                                <th scope="col" class="">
                                    Role
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(okr, index) in okrList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ okr.objective_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ departmentList.find(department=>department.id = okr.role.department_id)?.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ okr.role.name }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <a :href="'/OKR/' + okr.id + '/edit'">
                                            <i class="far fa-pen cursor-pointer mr-3"></i>
                                        </a>
                                        <button @click="deleteBtnClicked(okr.id)"
                                            data-te-toggle="modal" data-te-target="#deleteModal" id="delete-btn" class="pr-1">
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
                                @click="getOkrList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOkrList(currentPage + 1)">
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
                        <button @click="deleteOkr" type="button" data-te-toggle="modal"
                            data-te-target="#deleteModal"
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
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            okrList: [],
            departmentList:[],
            roleList:[],
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,
            selectedDepartment:null,
            selectedRole:null,

            url:'/api/objectives?page=',
            url_search:'',
            url_department:'',
            url_role:'',

            deleteId:null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getOkrList(pageNumber) {
            let url = this.url + pageNumber + this.url_search + this.url_department + this.url_role;
            // let url = `/api/objectives?page=${pageNumber}`;
            // if (this.searchInput && this.searchCategory) {
            //     url = `/api/objectives?search_input=${this.searchInput}&menu_category_id=${this.searchCategory.id}&page=${pageNumber}`;
            // }
            // if (this.searchInput && !this.searchCategory) {
            //     url = `/api/objectives?search_input=${this.searchInput}&page=${pageNumber}`;
            // }
            // if ((!this.searchInput) && this.searchCategory) {
            //     url = `/api/objectives?menu_category_id=${this.searchCategory.id}&page=${pageNumber}`;
            // }
            // let url = `/api/objectives`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.okrList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                // this.totalData = response.data.total;
            }
        },
        async searchBtnClicked() {
            this.url_search = '&search=' + this.searchInput
            this.getOkrList(1);
        },
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.url_search = '';
            this.getOkrList(1);
        },

        async getDepartment(){
            let response = await getApiData({url: `/api/departments`, token: this.getToken()});
            if(response.data){
                this.departmentList = response.data;
            }
        },
        // changeDepartment(){
        //     this.getRoleList();
        //     this.getOkrList(1);
        //     console.log('hello bro')
        // },
        // async getRoleList(){
        //     let response = await getApiData({url: '/api/roles_department/' + this.selectedDepartment.id , token: this.getToken()});
        //     if(response.data){
        //         this.roleList = response.data;
        //     }
        // },
        // selectedRoleChange(){
        //     this.getOkrList(1);
        // },



        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteOkr() {
            let response = await deleteApiData({ url: `/api/objectives/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getOkrList(1);
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getOkrList(1);
        this.getDepartment();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>