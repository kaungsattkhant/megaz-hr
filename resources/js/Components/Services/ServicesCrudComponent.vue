<template>
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Services
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">

                        <i class="fal fa-search"></i>
                    </label>

                    <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
                    <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button>
                </div>
                <div class="flex justify-end flex-col">

                    <button type="button" v-show="feature.includes('service.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
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
                                    Services
                                </th>
                                <th scope="col" class="">
                                    Category
                                </th>
                                <th scope="col" class="">
                                    Price Per Hour
                                </th>
                                <th scope="col" class="" v-show="['service.toggle', 'service.update'].some(f => feature.includes(f))">

                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(service, index) in serviceList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ service.name ? service.name : service.staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="service.service_category"> {{ service.service_category.name }} </div>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ service.price_per_hour }}
                                    </td>
                                    <td class="whitespace-nowrap " v-if="feature.includes('service.toggle')">
                                        <input :checked="service.is_available == 1" @change="isActiveToggled(service.id)"
                                            class="me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                    before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5
                                    after:w-5 after:rounded-full after:border-none after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                    after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                    checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                    checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                    focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                    focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                    checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                    checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-white/25 dark:after:bg-surface-dark dark:checked:bg-primary dark:checked:after:bg-primary"
                                            type="checkbox" role="switch" />
                                    </td>
                                    <td class="whitespace-nowrap " v-if="feature.includes('service.update')">
                                        <button id="edit-btn" class="pr-1" @click="editBtnClick(service.id)"
                                            data-te-toggle="modal" data-te-target="#edit_modal">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <!-- <button id="delete-btn" class="pr-1" @click="deleteBtnClicked(service.id)"
                                            data-te-toggle="modal" data-te-target="#deleteModal">
                                            <i class="fas fa-trash-alt"></i>
                                        </button> -->
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="serviceList.length < 1 && !loading">
                                <td class="" colspan="6">
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
                                @click="getServiceList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getServiceList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Create Modal -->
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
                                Create Service
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none" id="closeCreateModal"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <!-- <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Service Name
                                </label>
                                <input type="text" placeholder="Service Name" v-model="name" class="input-ui">
                            </div> -->
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Service Category
                                </label>
                                <select name="" id="" v-model="selectedServiceCategory" class="input-ui">
                                    <option :value="service" v-for="(service, index) in serviceCategoryList"
                                        :key="index">{{
                                            service.name }}</option>
                                </select>
                            </div>
                            <div class="mb-4" v-if="selectedServiceCategory ? selectedServiceCategory.name == 'Lady' : ''">
                                <label for="" class="label-form mb-3">
                                    Lady
                                </label>
                                <select name="" id="" v-model="selectedLady" class="input-ui">
                                    <option :value="lady" v-for="(lady, index) in ladyList"
                                        :key="index">{{
                                            lady.name }}</option>
                                </select>
                            </div>
                            <div class="mb-4" v-if="selectedServiceCategory ? selectedServiceCategory.name == 'DJ' : ''">
                                <label for="" class="label-form mb-3">
                                    DJ
                                </label>
                                <input type="text" placeholder="DJ Name" v-model="djName" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Price Per Hour
                                </label>
                                <input type="text" placeholder="Price Per Hour" v-model="pricePerHour" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Selling Area
                                </label>
                                <select name="" id="" v-model="area_id" class="input-ui">
                                    <option :value="area.id" v-for="(area, index) in areaList" :key="index">{{ area.name
                                        }}</option>
                                </select>
                            </div>



                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <!-- <button type="button" @click="createBtnClicked"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button> -->
                            <LoadingButton
                                :loading="buttonLoading"
                                text="Create"
                                loadingText="Creating..."
                                @click="createBtnClicked"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="edit_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Edit Service
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none" id="closeEditModal"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <!-- <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Service Name
                                </label>
                                <input type="text" placeholder="Service Name" v-model="name" class="input-ui">
                            </div> -->
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Service Category
                                </label>
                                <select name="" id="" v-model="selectedServiceCategoryEdit" class="input-ui">
                                    <option :value="service" v-for="(service, index) in serviceCategoryList"
                                        :key="index">{{
                                            service.name }}</option>
                                </select>
                            </div>
                            <div class="mb-4" v-if="selectedServiceCategoryEdit ? selectedServiceCategoryEdit.name == 'Lady' : ''">
                                <label for="" class="label-form mb-3">
                                    Lady
                                </label>
                                <select name="" id="" v-model="selectedLadyEdit" class="input-ui">
                                    <option :value="lady.id" v-for="(lady, index) in ladyList"
                                        :key="index">{{
                                            lady.name }}</option>
                                </select>
                            </div>
                            <div class="mb-4" v-if="selectedServiceCategoryEdit ? selectedServiceCategoryEdit.name == 'DJ' : ''">
                                <label for="" class="label-form mb-3">
                                    DJ
                                </label>
                                <input type="text" placeholder="DJ Name" v-model="djNameEdit" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Price Per Hour
                                </label>
                                <input type="text" placeholder="Price Per Hour" v-model="pricePerHourEdit" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Selling Area
                                </label>
                                <select name="" id="" v-model="area_id_edit" class="input-ui">
                                    <option :value="area.id" v-for="(area, index) in areaList" :key="index">{{ area.name
                                        }}</option>
                                </select>
                            </div>



                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" @click="editService"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Update
                            </button>
                            <LoadingButton
                                :loading="buttonLoading"
                                text="Update"
                                loadingText="Updating..."
                                @click="editService"
                            />
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
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
import TableSkeleton from "../Common/TableSkeleton.vue";
import LoadingButton from "../Common/LoadingButton.vue";

export default {
    components: {
        TableSkeleton,
        LoadingButton
    },
    data() {
        return {
            serviceList: [],
            entityTypeList: ['Room', 'Table'],
            serviceCategoryList: [],
            areaList: [],
            ladyList:[],

            selectedLady:null,
            djName:null,
            name: null,
            pricePerHour: null,
            entityType: 'service',
            area_id: null,
            selectedServiceCategory: null,

            selectedLadyEdit:null,
            djNameEdit:null,
            nameEdit: null,
            pricePerHourEdit: null,
            entityType: 'service',
            area_id_edit: null,
            selectedServiceCategoryEdit: null,

            editId:null,
            deleteId: null,

            serviceDetail:null,

            searchInput: null,
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            feature: this.getFeature(),
            loading: true,
            buttonLoading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getServiceList(pageNumber) {
            this.loading = true;
            let url = `/api/services?page=${pageNumber}`;
            if (this.searchInput) {
                url = `/api/services?search_input=${this.searchInput}&page=${pageNumber}`;
            }
            // let url = `/api/services`;
            const response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.serviceList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        async getAreaList() {
            const response = await getApiData({ url: '/api/area_categories/2/areas', token: this.getToken() });
            if (response.data) {
                this.areaList = response.data;
            }
        },

        async getServiceCategoryList() {
            const response = await getApiData({ url: '/api/service_categories', token: this.getToken() });
            if (response.data) {
                this.serviceCategoryList = response.data;
            }
        },

        async getLadyList(){
            const response = await getApiData({ url: '/api/staff_by_department_slug/entertainment', token: this.getToken() });
            if (response.data) {
                this.ladyList = response.data;
            }
        },

        createBtnClicked() {
            this.createService();
        },

        async createService() {
            this.buttonLoading = true;
            let formData = new FormData();
            if(this.selectedServiceCategory.name == 'DJ'){
                formData.append('name', this.djName);
            }
            if(this.selectedServiceCategory.name == 'Lady'){
                formData.append('staff_id', this.selectedLady.id);
            }
            formData.append('price_per_hour', this.pricePerHour);
            // formData.append('entity_type', this.entityType);
            formData.append('area_id', this.area_id);
            formData.append('service_category_id', this.selectedServiceCategory.id);
            let response = await postApiData({ url: '/api/services', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getServiceList(1);
                console.log("success")
                this.closeModal('closeCreateModal');
                this.clearForm();
                setTimeout(() => {
                    this.buttonLoading = false
                }, 500)
            }
            else {
                this.buttonLoading = false;
                this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
            }
        },
        async editBtnClick(serviceId){
            this.editId = serviceId;
            const response = await getApiData({ url: '/api/services/' + serviceId, token: this.getToken() });
            if (response.data) {
                this.serviceDetail = response.data;
                this.selectedServiceCategoryEdit =  response.data.service_category;
                this.selectedLadyEdit = response.data.staff.id;
                this.djNameEdit = response.data.name;
                this.pricePerHourEdit =  response.data.price_per_hour;
                this.area_id_edit =  response.data.area_id;
                
            }
        },
        async editService() {
            this.buttonLoading = true;
            let formData = new FormData();
            formData.append('id', this.editId);
            if(this.selectedServiceCategoryEdit.name == 'DJ'){
                formData.append('name', this.djNameEdit);
            }
            if(this.selectedServiceCategoryEdit.name == 'Lady'){
                formData.append('staff_id', this.selectedLadyEdit);
            }
            formData.append('price_per_hour', this.pricePerHourEdit);
            // formData.append('entity_type', this.entityType);
            formData.append('area_id', this.area_id_edit);
            formData.append('service_category_id', this.selectedServiceCategoryEdit.id);
            let response = await postApiData({ url: '/api/services', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getServiceList(1);
                console.log("success");
                this.editId = null;
                this.closeModal('closeEditModal');
                this.clearForm();
                setTimeout(() => {
                    this.buttonLoading = false
                }, 500)
            }
            else {
                this.buttonLoading = false;
                this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
            }
        },

        // closeModal() {
        //     document.getElementById("closeCreateModal").click();
        // },

        closeModal(modalId) {
                document.getElementById(modalId).click();
            },
        clearForm() {
            this.djName = null,
            this.selectedServiceCategory = null,
            this.selectedLady = null,
            this.pricePerHour = null,
            this.area_id = null,
            this.ladyList = []
        },

        isActiveToggled(id) {
            let index = this.serviceList.findIndex(table => table.id == id);
            if (index != -1) {
                if (this.serviceList[index].is_available == 1) {
                    this.serviceList[index].is_available = 0;
                }
                else {
                    this.serviceList[index].is_available = 1;
                }

                let url = `/api/is_active`;
                let formData = new FormData();
                formData.append('id', id);
                formData.append('type', 'entity');
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
                if(response.success){
                    this.getServiceList(1)
                }
            }
        },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        async confirmDeleteBtnClicked() {
            let url = `/api/entities/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.getServiceList(1);
            }
            else {
                alert('some errors occur');
            }
        },

        async searchBtnClicked() {
            this.getServiceList(1);
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.getServiceList(1);
        },

    },

    created() {
        this.getServiceList(1);
        this.getLadyList();
        this.getAreaList();
        this.getServiceCategoryList();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
