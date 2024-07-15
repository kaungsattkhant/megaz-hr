<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Areas
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">

                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Area Type
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(area, index) in areaList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ ++index }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ area.name }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        {{ area.area_type.name }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        <!-- <button @click="deleteBtnClicked(area.id)"
                                    data-te-toggle="modal" data-te-target="#deleteModal" id="edit-btn" class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> -->
                                        <input :checked="area.is_active == 1" @change="isActiveToggled(area.id)"
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
                                </tr>
                            </div>

                            <!-- looping end -->
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Modal -->
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
                                Create Area
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Area Name
                                </label>
                                <input type="text" placeholder="Area Name" v-model="name" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Department</label>
                                <multiselect v-model="selectedDepartment" :options="departmentList" :close-on-select="true"
                                    :clear-on-select="false" :preserve-search="true" placeholder="Select Department" label="name"
                                    track-by="id" :preselect-first="false"></multiselect>
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Area Category</label>
                                <multiselect v-model="selectedCategory" :options="categoryList" :close-on-select="true"
                                    :clear-on-select="false" :preserve-search="true" placeholder="Select Area Category" label="name"
                                    track-by="id" :preselect-first="false"></multiselect>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">Area Type</label>
                                <multiselect v-model="selectedType" :options="typeList" :close-on-select="true"
                                    :clear-on-select="false" :preserve-search="true" placeholder="Select Area Type" label="name"
                                    track-by="id" :preselect-first="false"></multiselect>
                                <!-- <select name="" id="" v-model="selectedType"
                                    class="input-ui">
                                    <option :value="type.id" v-for="(type, index) in typeList" :key="index">
                                        {{ type.name }}
                                    </option>
                                </select> -->
                            </div>

                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button data-te-modal-dismiss type="button" @click="createAreasBtnClicked"
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
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect
        },
        data() {
            return {

                departmentList: [],
                areaList:[],
                typeList:[],
                categoryList: [],
                name: null,
                selectedType:null,
                selectedCategory: null,
                selectedDepartment: null,
                deleteId: null,


                per_page: 10,
                pageNumbers: [],
                currentPage: 1,
                paginationGroupsCount: 1,
                per_group: 10,
                groupedPageNumbers: [],
                currentGroup: 0,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getAreasList(){
                const response = await getApiData({ url: '/api/areas', token: this.getToken() });
                if(response.data){
                    this.areaList = response.data;
                }
            },

            async getDepartmentList(){
                const response = await getApiData({ url: '/api/departments', token: this.getToken() });
                if(response.data){
                    this.departmentList = response.data;
                }
            },

            async getCategoryList(){
                const response = await getApiData({ url: '/api/area_categories' , token: this.getToken()});
                if(response.data){
                    this.categoryList = response.data;
                }
            },

            async getTypeList(){
                const response = await getApiData({ url: '/api/area_types' , token: this.getToken()});
                if(response.data){
                    this.typeList = response.data;
                }
            },

            createAreasBtnClicked(){

                this.createArea();
            },

            async createArea()
            {
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('area_type_id', this.selectedType.id);
                formData.append('area_category_id', this.selectedCategory.id);
                formData.append('department_id', this.selectedDepartment.id);
                let response = await postApiData({url: '/api/areas', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getAreasList(null);
                    this.selectedType = null;
                    this.selectedCategory = null;
                    this.selectedDepartment = null;
                }
                else{

                }
            },

            isActiveToggled(id){
                let index = this.areaList.findIndex(area => area.id == id);
                if(index != -1){
                    if(this.areaList[index].is_active == 1){
                        this.areaList[index].is_active = 0;
                    }
                    else{
                        this.areaList[index].is_active = 1;
                    }

                    let url = `/api/is_active`;
                    let formData = new FormData();
                    formData.append('id', id);
                    formData.append('type', 'area');
                    let response = postApiData({url: url, form_data: formData, token: this.getToken()});
                }
            },

            deleteBtnClicked(id){
                this.deleteId = id;
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/areas/${this.deleteId}`;
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    this.getAreasList(null);
                    console.log(`deleted`);
                }
            }

        },
        mounted()
        {
            this.getCategoryList();
            this.getAreasList();
            this.getDepartmentList();
            this.getTypeList();
            initTE({ Modal,Select, Ripple });
        }
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
