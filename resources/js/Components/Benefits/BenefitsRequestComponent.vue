<template>
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Benefits
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
                        <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
                            data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui">
                            <option :value="department.value" v-for="(department, departmentIndex) in departmentList"
                                :key="departmentIndex"> {{ department.name }} </option>
                        </select>
                    </div>
                    <div class=" !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Role" @change="selectedRoleChange()"
                            data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui">
                            <option :value="role.value" v-for="(role, roleIndex) in roleList"
                                :key="roleIndex"> {{ role.name }} </option>
                        </select>
                    </div> -->
                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
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
                                    Staff Name
                                </th>
                                <th scope="col" class="">
                                    Date
                                </th>
                                <th scope="col" class="">
                                    Type
                                </th>
                                <th scope="col" class="">
                                    Benefit
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
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                        <!-- {{ index+1 }} -->
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.staff_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.date_time }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.type }} 
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.name }} 
                                    </td>
                                    
                                    <td class="whitespace-nowrap">
                                        <button @click="confirm(item)">
                                            <i class="fal fa-check text-sm mr-2"></i>
                                        </button>
                                        <button @click="reject(item)" >
                                            <i class="fal fa-times text-sm"></i>
                                        </button>
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
                                @click="getPrimaryList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getPrimaryList(currentPage + 1)">
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
                        Create Benefit
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        id="close_create_modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    
                    

                    <div class="mb-4">
                        <label class="label-form mb-3">Type</label>
                        <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                            v-model="selectedType" @change="selectedTypeChange">
                            <option class="text-sm" :value="type" v-for="(type,index) in typeList" :key="index">
                                {{ type.name }}
                            </option>
    
                        </select>
                    </div>
                    <div v-show="selectedType?.value === 'menu'">
                        <div class="mb-4">
                            <label class="label-form mb-3">Menu</label>
                            <multiselect
                                v-model="selectedMenu"
                                :options="menuList"
                                :multiple="false"
                                :close-on-select="true"
                                :clear-on-select="false"
                                :preserve-search="true"
                                placeholder="Select Menu"
                                label="name"
                                track-by="id"
                                @select="selectedMenuChange"
                                :preselect-first="false">
                                <!-- <template #selection="{ values, search, isOpen }">
                                    <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                                    Staff selected</span>
                                </template> -->
                            </multiselect>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                 Cost
                            </label>
                            <input type="number" v-model="cost" disabled class="input-ui mb-2">
                        </div>
                    </div>
                    <div v-show="selectedType?.value === 'cost'">
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Menu Name
                            </label>
                            <input type="text" v-model="selectedMenuName" class="input-ui mb-2">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                 Cost
                            </label>
                            <input type="number" v-model="cost" class="input-ui mb-2">
                        </div>
                    </div>
                </div>

                
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="createBtnClicked()"
                        class="add-btn focus:outline-none focus:ring-0 ">
                        Create
                    </button>
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
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        Multiselect,
        TableSkeleton
    },
    data() {
        return {
            primaryList: [],

            typeList: [
                { name: 'Menu', value: 'menu'},
                { name: 'cost', value: 'cost'}
            ],
            menuList:[],
            
            selectedType: null,
            selectedMenu: null,
            selectedMenuName: null,
            cost: 0,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,

            

            url:'/api/admin/benefit_requests?page=',
            
            deleteId:null,

            feature: this.getFeature(),
            loading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getPrimaryList(pageNumber) {
            this.loading = true;
            let url = this.url + pageNumber;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.primaryList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },
        async confirm(item){
            let formData = new FormData();
            formData.append('id', item.id);
            formData.append('status', 'confirmed');
            let response = await postApiData({url:`/api/admin/benefit_requests/update_status`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getPrimaryList(1);
            }
        },
        async reject(item){
            let formData = new FormData();
            formData.append('id', item.id);
            formData.append('status', 'cancelled');
            let response = await postApiData({url:`/api/admin/benefit_requests/update_status`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getPrimaryList(1);
            }
        },


        async getMenuList(){
            let response = await getApiData({ url: '/api/menus', token: this.getToken() });
            if (response.data) {
                this.menuList = response.data;
            }
        },
        selectedMenuChange(){
            this.cost = this.selectedMenu.prices[0].price
        },
        selectedTypeChange(){
            this.cost = 0
            this.selectedMenu = null;
            this.selectedMenuName = null;
        },
        addBtnClicked(){
            this.selectedType = null;
            this.cost = 0
            this.selectedMenu = null;
            this.selectedMenuName = null;
        },
        createBtnClicked(){
            if(!this.selectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            else if(this.selectedType.value === 'cost' && !this.selectedMenuName){
                this.alertValidationMessage(`Menu Name`);
                return 1;
            }
            else if(this.selectedType.value === 'cost' && this.cost < 1){
                this.alertValidationMessage(`Cost`);
                return 1;
            }
            else if(this.selectedType.value === 'menu' && !this.selectedMenu){
                this.alertValidationMessage(`Menu`);
                return 1;
            }
            else{
                this.createBenefit();
            }
        },
        async createBenefit(){
            let formData = new FormData();
            formData.append('type', this.selectedType.value);
            if( this.selectedType.value === 'menu'){
                formData.append('menu_id', this.selectedMenu.id);
            }
            if( this.selectedType.value === 'cost'){
                formData.append('name', this.selectedMenuName);
                formData.append('cost', this.cost);
            }
            let response = await postApiData({url:`/api/benefits`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getPrimaryList(1);
                document.getElementById("close_create_modal").click();
            }
        },
        
        editBtnClicked(item){
            this.editId = item.id;
        },
        
        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getPrimaryList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getPrimaryList(1);
        // },



        // deleteBtnClicked(id) {
        //     this.deleteId = id;
        // },
        // async deleteItem() {
        //     let response = await deleteApiData({ url: `/api/hr/overtime_fees/` + this.deleteId, token: this.getToken() });
        //     if (response.success) {
        //         this.getPrimaryList(1);
        //     }
        //     else {
        //         this.$notify({
        //             title: `Input validation`,
        //             text: response.message,
        //             type: "warn"
        //         });
        //     }
        // },



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
        this.getMenuList();
        this.getPrimaryList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>