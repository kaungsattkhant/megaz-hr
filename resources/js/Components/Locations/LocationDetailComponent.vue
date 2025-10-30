<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Location : {{ selectedFloor }}
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
                
                <!-- <button  data-te-toggle="modal" data-te-target="#add_modal" @click="btnClickedAddModal"
                    class="add-btn  h-8 whitespace-nowrap">
                    Add New
            </button> -->
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
                                    Place
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
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.staff ? item.staff.name : 'Unassigned' }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button @click="btnClickedAddModal(item)"
                                            data-te-toggle="modal" data-te-target="#add_modal" id="add-btn" class="pr-1">
                                            <i class="far fa-plus"></i>
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
                            Add Staff Location
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_create_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="grid grid-cols-11 gap-x-4">
                            
                            <div class="mb-4 col-span-11">
                                <label for="" class="label-form mb-3">
                                    Name
                                </label>
                                <multiselect
                                    v-model="selectedStaff"
                                    :options="staffList"
                                    :multiple="false"
                                    :close-on-select="true"
                                    :clear-on-select="false"
                                    :preserve-search="true"
                                    placeholder="Select Staff"
                                    label="name" class=" capitalize"
                                    track-by="id"
                                    :preselect-first="false">
                                    </multiselect>
                            </div>
                        </div>
                        
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                        </button>
                        <button type="button" @click="btnClickedAssignedStaff()"
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
import Multiselect from 'vue-multiselect';
import { getCurrentTime, getCurretDateTime } from "../../utilities/datetime-helpers";

export default {
    components: {
        Multiselect
    },
    props: ["locationId","floorId"],
    data() {
        return {
            primaryList: [],
            staffList: [],

            selectedStaff: null,
            selectedItem: null,

            selectedFloor: null,
            

            currentTime: getCurretDateTime(),
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:`/api/hr/locations/${this.locationId}/floor/${this.floorId}`,
            url_search:'',
            deleteId:null,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken']),

        async getPrimaryList(pageNumber) {
            let url = this.url + this.url_search;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.primaryList = response.data;
                this.selectedFloor = response.data[0].floor.name;
            }
        },
        async getStaffList() {
            let response = await getApiData({ url: '/api/staffs', token: this.getToken() });
            if (response.data) {
                this.staffList = response.data;
            }
        },
        btnClickedAddModal(item){
            this.selectedItem = item
            this.selectedStaff = null;
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
        
        btnClickedAssignedStaff(){
            if(!this.selectedStaff){
                this.alertValidationMessage(`Staff`);
                return 1;
            }
            else{
                this.assignedStaff();
            }
        },
        async assignedStaff(){
            let formData = new FormData();
            formData.append('staff_id', this.selectedStaff.id);
            let response = await postApiData({url:'/api/hr/places/' + this.selectedItem.id + '/assign-staff', form_data:formData, token:this.getToken()})
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
        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },



    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getPrimaryList(1);
        this.getStaffList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>