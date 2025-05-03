<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Exit Pass
        </p>
    </div>
    <div class="mt-4 bg-white">
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
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="searchDepartmentChange()"
                        data-te-select-filter="true" name="" id="" v-model="searchDepartment" class="input-ui">
                        <option :value="department" v-for="(department, departmentIndex) in departmentList"
                            :key="departmentIndex"> {{ department.name }} </option>
                    </select>
                </div>
                <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" @change="searchRoleChange()"
                        data-te-select-filter="true" name="" id="" v-model="searchRole" class="input-ui">
                        <option :value="role" v-for="(role, roleIndex) in searchRoleList"
                            :key="roleIndex"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="flex pr-0 gap-x-4">
                
                <button  data-te-toggle="modal" data-te-target="#add_exit_modal" @click="btnClickedCreateExitModal"
                    class="add-btn  h-8 whitespace-nowrap">
                    Add New
            </button>
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
                                    Exit Category
                                </th>
                                <th scope="col" class="">
                                    Details
                                </th>
                                <th scope="col" class="">
                                    Exit Time
                                </th>
                                <th scope="col" class="">
                                    Arrival Time
                                </th>
                                <th scope="col" class="">
                                    Stage
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(exit, index) in exitList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        date needed
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ exit.staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ exit.exit_category.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ exit.detail }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ exit.exit_date_time }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ exit.arrival_date_time }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ exit.status }}
                                    </td>
                                    
                                    <td class="whitespace-nowrap">
                                        <!-- <a :href="'/okr_duty/' + duty.id + '/edit'">
                                            <i class="far fa-pen cursor-pointer mr-3"></i>
                                        </a> -->
                                        <button @click="deleteBtnClicked(exit.id)" v-if="exit.status == 'confirmed' || exit.status == 'arrival_confirmed'"
                                            data-te-toggle="modal" data-te-target="#deleteModal" id="delete-btn" class="pr-1">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                        <div class="contents" v-if="exit.status != 'confirmed' && exit.status != 'arrival_confirmed'">
                                            <button @click="confirmedExit(exit)">
                                                <i class="far fa-check text-sm mr-3 p-1"></i>
                                            </button>
                                            <button @click="cancelledExit(exit)">
                                                <i class="far fa-times text-sm p-1"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="exitList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="exitList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="add_exit_modal" tabindex="-1" aria-labelledby="add_duty_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_exit_modalLabel">
                            {{ isExitCategory ? 'Create Exit Category' : 'Create Exit' }}
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" @click="closeExitCategoryWithDelay"
                                data-te-modal-dismiss aria-label="Close" id="close_exit_create_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div v-show="!isExitCategory">
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Department
                                </label>
                
                                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                    data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
                                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black text-sm">
                                        <option :value="department" v-for="(department, index) in departmentList"
                                            :key="index"> {{ department.name }} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Role
                                </label>
                                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                    data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select Role" @change="selectedRoleChange"
                                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                                        <option :value="role" v-for="(role, index) in roleList"
                                            :key="index"> {{ role.name }} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Employee Name
                                </label>
                                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                    data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select Staff" 
                                        data-te-select-filter="true" name="" id="" v-model="selectedStaff" class="input-ui !text-black text-sm">
                                        <option :value="staff" v-for="(staff, index) in staffList"
                                            :key="index"> {{ staff.name }} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Exit Category
                                </label>
                                <div class="flex gap-x-2">
                                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                        data-te-select-wrapper-ref>
                                        <select data-te-select-init data-te-select-placeholder="Select Leave Type"
                                            data-te-select-filter="true" name="" id="" v-model="selectedExitCategory" class="input-ui !text-black text-sm">
                                            <option :value="exit" v-for="(exit, index) in exitCategoryList"
                                                :key="index"> {{ exit.name }} </option>
                                        </select>
                                    </div>
                                    <button @click="toggleStep()" class="px-2"><i class="fal fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Description
                                </label>
                                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                                    data-te-select-wrapper-ref>
                                    <textarea type='text' v-model='description' class="input-ui w-full !p-1 text-xs" rows="8" placeholder="Description" ></textarea>
                                </div>
                
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Exit Time
                                </label>
                                <input type="datetime-local" v-model="exitTime"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Arrival Time
                                </label>
                                <input type="datetime-local" v-model="arrivalTime"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                        </div>
                        <div v-show="isExitCategory">
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Exit Category Name
                                </label>
                                <input type="text" v-model="exitCategoryName"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="flex justify-end gap-x-4 mb-4 pt-4">
                                <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                        @click="toggleStep()">
                                        Cancel
                                </button>
                                <button type="button" @click="btnClickedCreateExitCategory()"
                                    class="add-btn focus:outline-none focus:ring-0 ">
                                    Create Category
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4" v-show="!isExitCategory">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                        </button>
                        <button type="button" @click="btnClickedCreateExit()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
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
                        <button @click="deleteItem()" type="button" data-te-toggle="modal"
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
import { getCurrentTime, getCurretDateTime } from "../../utilities/datetime-helpers";

export default {
    data() {
        return {
            exitList: [],
            departmentList: [],
            searchRoleList: [],
            roleList: [],
            staffList: [],
            exitCategoryList: [],

            searchDepartment: null,
            searchRole: null,

            selectedDepartment: null,
            selectedRole: null,
            selectedStaff: null,
            selectedExitCategory: null,
            description: null,
            exitTime: null,
            arrivalTime: null,

            isExitCategory: false,

            exitCategoryName: null,

            currentTime: getCurretDateTime(),
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/hr/exit_passes',
            url_search:'',
            url_department:'',
            url_role:'',
            deleteId:null,

            testdata: null,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken']),

        async getExitList(pageNumber) {
            let url = this.url + this.url_department + this.url_role;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.exitList = response.data.data;
            }
            console.log(this.getUser());
        },
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        searchDepartmentChange(){
            this.url_department = '?department_id='+this.selectedDepartment.id;
            this.roleList = this.selectedDepartment.roles;
            this.getExitList();
        },
        searchRoleChange(){
            this.url_role = '&role_id='+this.selectedRole.id;
            this.getExitList();
        },
        async searchBtnClicked() {
            this.url_search = '&search=' + this.searchInput
            this.getExitList(1);
        },
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.url_search = '';
            this.getExitList(1);
        },


        selectedDepartmentChange(){
            this.roleList = this.selectedDepartment.roles;
            this.selectedRole = null;
            this.staffList = [];
            this.selectedStaff = null;
            // this.getStaffList();
        },
        async selectedRoleChange(){
            let response = await getApiData({ url: '/api/hr/staff_lists_by_role/' + this.selectedRole.id + '/department/' + this.selectedDepartment.id, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data.data;
            }
        },
        // async getStaffList(){
        //     const response = await getApiData({ url: '/api/departments/' + this.selectedDepartment.id + '/staffs', token: this.getToken() });
        //     if(response.data){
        //         this.staffList = response.data;
        //     }
        // },
        // async getStaffList(){
        //     const response = await getApiData({ url: '/api/staff_lists_by_role/' + this.selectedRole.id + '/department/' +this.selectedDepartment.id, token: this.getToken() });
        //     if(response.data){
        //         this.staffList = response.data;
        //     }
        // },
        async getExitCategory(){
            let response = await getApiData({ url: '/api/hr/exit_categories', token: this.getToken() });
            if (response.data) {
                this.exitCategoryList = response.data.data;
            }
        },
        toggleStep(){
            if(this.isExitCategory){
                this.isExitCategory = false;
            }
            else{
                this.isExitCategory = true;
            }
        },
        async btnClickedCreateExitCategory(){
            let formData = new FormData();
            formData.append('name', this.exitCategoryName);
            let response = await postApiData({url:`/api/hr/exit_categories`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                this.isExitCategory = false;
                this.getExitCategory();
                this.selectedExitCategory = response.data;
            }
        },
        btnClickedCreateExitModal(){
            this.selectedDepartment = null;
            this.roleList = [];
            this.selectedRole = null;
            this.staffList = [];
            this.selectedStaff = null;
            this.selectedExitCategory = null;
            this.description = null;
            this.exitTime = null;
            this.arrivalTime = null;
        },
        btnClickedCreateExit(){
            if(!this.selectedStaff){
                this.alertValidationMessage(`Employee`);
                return 1;
            }
            // else if(this.exitList.length < 1){
            //     this.alertValidationMessage(`Leave`);
            //     return 1;
            // }
            else if(!this.selectedExitCategory){
                this.alertValidationMessage(`Exit Category`);
                return 1;
            }
            else if(!this.description){
                this.alertValidationMessage(`Description`);
                return 1;
            }
            else if(!this.exitTime){
                this.alertValidationMessage(`Exit Time`);
                return 1;
            }
            else if(!this.arrivalTime){
                this.alertValidationMessage(`Arrival Time`);
                return 1;
            }
            else{
                this.createExit();
            }
        },
        async createExit(){
            let formData = new FormData();
            formData.append('staff_id', this.selectedStaff.id);
            formData.append('exit_category_id', this.selectedExitCategory.id);
            formData.append('detail', this.description);
            formData.append('exit_date_time', this.exitTime);
            formData.append('arrival_date_time', this.arrivalTime);
            formData.append('status', 'confirmed');

            // formData.append('confirmed_at', this.currentTime);
            // formData.append('confirmed_by', this.getUser().id);
            let response = await postApiData({url:`/api/hr/exit_passes`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                document.getElementById('close_exit_create_modal').click();
                this.getExitList();
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },

        
        async confirmedExit(exit){
            let formData = new FormData();
            formData.append('status', 'confirmed');
            let response = await postApiData({url:`/api/hr/exit_passes/` + exit.id, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                // window.location.replace(`/exit`);
                this.getExitList();
            }
        },
        async cancelledExit(exit){
            let formData = new FormData();
            formData.append('status', this.selectedStatus);
            let response = await postApiData({url:`/api/hr/exit_passes/` + exit.id, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                // window.location.replace(`/exit`);
                this.getExitList();
            }
        },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/hr/leave_allowances/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getExitList(1);
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },
        closeExitCategoryWithDelay() {
            setTimeout(() => {
                this.isExitCategory = false;
            }, 300); // 300ms delay (adjust as needed)
        },


    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getExitList(1);
        this.getDepartmentList();
        this.getExitCategory();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>