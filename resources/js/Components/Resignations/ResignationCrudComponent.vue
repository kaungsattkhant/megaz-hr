<template>
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Resignations
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
                    <!-- <div class="w-full !text-sm" data-te-select-wrapper-ref>
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
                    </div> -->
                </div>
                <div class="flex pr-0 gap-x-4">
                    
                    <button  data-te-toggle="modal" data-te-target="#add_modal" @click="btnClickedAddModal" v-show="feature.includes('resignation.create')"
                        class="add-btn  h-8 whitespace-nowrap">
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
                                    Date
                                </th>
                                <th scope="col" class="">
                                    Staff
                                </th>
                                <th scope="col" class="">
                                    Resignation Category
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
                        <tbody>
                            <div class="contents" v-for="(item, index) in primaryList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- {{ exit.created_at.split("T")[0] }} -->
                                        {{ item.resignation_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.resign_category.name }}
                                    </td>
                                    <td class="whitespace-nowrap capitalize">
                                        {{ item.status }}
                                    </td>
                                    
                                    <td class="whitespace-nowrap">
                                        <!-- <a :href="'/okr_duty/' + duty.id + '/edit'">
                                            <i class="far fa-pen cursor-pointer mr-3"></i>
                                        </a> -->

                                        
                                        <!-- <button @click="deleteBtnClicked(item.id)" v-if="item.status == 'confirmed' || item.status == 'arrival_confirmed'"
                                            data-te-toggle="modal" data-te-target="#deleteModal" id="delete-btn" class="pr-1">
                                            <i class="far fa-trash-alt"></i>
                                        </button> -->
                                        <div class="contents" v-if="item.status === 'received' && feature.includes('resignation.status')">
                                            <button @click="confirmedItem(item)">
                                                <i class="far fa-check text-sm mr-3 p-1"></i>
                                            </button>
                                            <button @click="cancelledItem(item)">
                                                <i class="far fa-times text-sm p-1"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </div>

                            <tr class=" !text-center" v-if="primaryList.length < 1 && !loading">
                                <td class="" colspan="6">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="primaryList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="primaryList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="add_modal" tabindex="-1" aria-labelledby="add_duty_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_modalLabel">
                            {{ isCategory ? 'Create Resignation Category' : 'Create Resignation' }}
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" @click="closeExitCategoryWithDelay"
                                data-te-modal-dismiss aria-label="Close" id="close_create_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div v-show="!isCategory">
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Resignation Category
                                </label>
                                <div class="flex gap-x-2">
                                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                        data-te-select-wrapper-ref>
                                        <select data-te-select-init data-te-select-placeholder="Select Leave Type"
                                            data-te-select-filter="true" name="" id="" v-model="selectedCategory" class="input-ui !text-black text-sm">
                                            <option :value="resign" v-for="(resign, index) in resignCategoryList"
                                                :key="index"> {{ resign.name }} </option>
                                        </select>
                                    </div>
                                    <button @click="toggleStep()" class="px-2"><i class="fal fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Staff
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
                                <label for="" class="block text-sm text-black mb-3">
                                    Date
                                </label>
                                <input type="date" v-model="selectedDate"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Status
                                </label>
                                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                    data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select Status" 
                                        data-te-select-filter="true" name="" id="" v-model="selectedStatus" class="input-ui !text-black text-sm">
                                        <option :value="status" v-for="(status, index) in statusList"
                                            :key="index"> {{ status.name }} </option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Detail
                                </label>
                                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                                    data-te-select-wrapper-ref>
                                    <textarea type='text' v-model='detail' class="input-ui w-full !p-1 text-xs" rows="8" placeholder="Detail" ></textarea>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Image
                                </label>
                                <input type="file" class="input-ui" @change="handleFileChange"
                                    accept="image/png, image/gif, image/jpeg" ref="image">
                            </div>
                        </div>
                        <div v-show="isCategory">
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Name
                                </label>
                                <input type="text" v-model="categoryName" class="input-ui mb-2">
                            </div>
                            <div class="flex justify-end gap-x-4 mb-4 pt-4">
                                <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                        @click="toggleStep()">
                                        Cancel
                                </button>
                                <button type="button" @click="btnClickedCreateCategory()"
                                    class="add-btn focus:outline-none focus:ring-0 ">
                                    Create Category
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4" v-show="!isCategory">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                        </button>
                        <button type="button" @click="btnClickedCreateResignation()"
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
                        <button type="button" id="close_delete_modal"
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
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            primaryList: [],

            resignCategoryList: [],
            statusList: [
                {value: 'confirmed', name: 'Confirmed'},
                {value: 'cancelled', name: 'Cancelled'}
            ],
            staffList: [],

            selectedCategory: null,
            selectedStaff: null,
            selectedDate: null,
            selectedStatus: null,
            detail: null,
            selectedImage: null,

            isCategory: false,
            categoryName: null,

            currentTime: getCurretDateTime(),
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/hr/resignations',
            url_search:'',
            deleteId:null,

            feature: this.getFeature(),
            loading: true,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken', 'getFeature']),

        async getPrimaryList(pageNumber) {
            this.loading = true;
            let url = this.url + this.url_search;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.primaryList = response.data.data;
            }
        },
        async getCategoryList(){
            let response = await getApiData({ url: '/api/hr/resignation_categories', token: this.getToken() });
            if (response.data) {
                this.resignCategoryList = response.data;
            }
        },
        async getStaffList(){
            let url = '/api/staffs'
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data;
            }
        },
        // searchDepartmentChange(){
        //     this.url_department = '?department_id='+this.searchDepartment.id;
        //     this.searchRoleList = this.searchDepartment.roles;
        //     this.searchRole = null;
        //     this.getExitList();
        // },
        // searchRoleChange(){
        //     this.url_role = '&role_id='+this.searchRole.id;
        //     this.getExitList();
        // },
        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getExitList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getExitList(1);
        // },

        
        toggleStep(){
            if(this.isCategory){
                this.isCategory = false;
                this.categoryName = null;
            }
            else{
                this.isCategory = true;
                this.categoryName = null;
            }
        },
        async btnClickedCreateCategory(){
            let formData = new FormData();
            formData.append('name', this.categoryName);
            let response = await postApiData({url:`/api/hr/resignation_categories`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                this.isCategory = false;
                this.getCategoryList();
                this.selectedCategory = response.data;
            }
        },
        handleFileChange(event) {
            const selectedFile = event.target.files[0];
            this.selectedImage = selectedFile;
        },
        btnClickedAddModal(){
            this.selectedCategory = null;
            this.selectedDate = null;
            this.selectedStatus = null;
            this.selectedStaff = null;
            this.detail = null;
            this.selectedImage = null;
            this.categoryName = null;
        },
        btnClickedCreateResignation(){
            if(!this.selectedCategory){
                this.alertValidationMessage(`Category`);
                return 1;
            }
            else if(!this.selectedDate){
                this.alertValidationMessage(`Resign Date`);
                return 1;
            }
            else if(!this.selectedStatus){
                this.alertValidationMessage(`Status`);
                return 1;
            }
            else{
                this.createResign();
            }
        },
        async createResign(){
            let formData = new FormData();
            formData.append('resign_category_id', this.selectedCategory.id);
            formData.append('resignation_date', this.selectedDate);
            formData.append('status', 'received');
            formData.append('detail', this.detail);
            formData.append('image', this.selectedImage);
            formData.append('staff_id', this.selectedStaff.id);
            if(this.selectedStatus.value === 'confirmed'){
                formData.append('confirmed_by', this.getUser().id);
                formData.append('confirmed_at', this.currentTime);
            }
            else{
                formData.append('cancelled_by', this.getUser().id);
                formData.append('cancelled_at', this.currentTime);
            }
            let response = await postApiData({url:`/api/hr/resignations`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                document.getElementById('close_create_modal').click();
                this.getPrimaryList();
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },

        
        async confirmedItem(item){
            let formData = new FormData();
            formData.append('status', 'confirmed');
            formData.append('confirmed_by', this.getUser().id);
            formData.append('confirmed_at', this.currentTime);
            let response = await postApiData({url:`/api/hr/resignations/` + item.id, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                // window.location.replace(`/exit`);
                this.getPrimaryList();
            }else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        async cancelledItem(item){
            let formData = new FormData();
            formData.append('status', 'cancelled');
            formData.append('cancelled_by', this.getUser().id);
            formData.append('cancelled_at', this.currentTime);
            let response = await postApiData({url:`/api/hr/resignations/` + item.id, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                // window.location.replace(`/exit`);
                this.getPrimaryList();
            }else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/hr/resignations/` + this.deleteId, token: this.getToken() });
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
                this.isCategory = false;
            }, 300); // 300ms delay (adjust as needed)
        },


    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getPrimaryList(1);
        this.getCategoryList();
        this.getStaffList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>