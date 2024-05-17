<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Tables
        </p>
    </div>
    <div class="mt-4 bg-white">
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
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Room
                                </th>
                                <th scope="col" class="">
                                    Category
                                </th>
                                <th scope="col" class="">
                                    Price Per Hour
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(room, index) in tableList" :key="index">
                                <tr class="">
                                    <td class="">
                                        {{ ++index }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ room.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="room.service_category"> {{ room.service_category.name }} </div>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ room.price_per_hour }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        <!-- <button id="edit-btn" class="pr-1" @click="deleteBtnClicked(room.id)"
                                    data-te-toggle="modal" data-te-target="#deleteModal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> -->
                                        <input :checked="room.is_available == 1" @change="isActiveToggled(room.id)"
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
                                Create Room / Table
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
                                    Room / Table Name
                                </label>
                                <input type="text" placeholder="Room / Table Name" v-model="name" class="input-ui ">
                            </div>
                            <!-- <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Category
                            </label>
                            <select name="" id="" v-model="service_category_id"
                                class="input-ui ">
                                <option :value="service.id" v-for="(service,index) in serviceCategoryList">{{ service.name }}</option>
                            </select>
                        </div> -->
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Price Per Hour
                                </label>
                                <input type="text" placeholder="Price Per Hour" v-model="pricePerHour"
                                    class="input-ui ">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Selling Area
                                </label>
                                <select name="" id="" v-model="area_id" class="input-ui ">
                                    <option :value="area.id" v-for="(area,index) in areaList">{{ area.name }}</option>
                                </select>
                            </div>



                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" @click="createBtnClicked"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
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

    export default {
        data() {
            return {
                tableList:[],
                entityTypeList:['Room','Table'],
                serviceCategoryList:[],
                areaList:[],

                name: null,
                pricePerHour:null,
                entityType:'table',
                area_id:null,
                service_category_id:null,
                deleteId: null,

                searchInput: null,

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

            async getTableList(pageNumber){
                const response = await getApiData({ url: '/api/entities?type=table', token: this.getToken() });
                if(response.data){
                    this.tableList = response.data;
                    console.log(this.tableList)
                }
            },

            async getAreaList(){
                const response = await getApiData({ url: '/api/areas', token: this.getToken() });
                if(response.data){
                    this.areaList = response.data;
                    console.log(this.areaList)
                }
            },
            async getServiceCategoryList(){
                const response = await getApiData({ url: '/api/service_categories', token: this.getToken() });
                if(response.data){
                    this.serviceCategoryList = response.data;
                }
            },

            // inventoryableTypeChanged(){
            //     if(this.selectedInventoryType == 'area'){
            //         this.getAreaList();
            //     }
            //     if(this.selectedInventoryType == 'department'){
            //         this.getDepartmentList();
            //     }
            // },

            createBtnClicked(){
                this.createTableAndRoom();
            },

            async createTableAndRoom()
            {
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('price_per_hour', this.pricePerHour);
                formData.append('entity_type', this.entityType);
                formData.append('area_id', this.area_id);
                // formData.append('service_category_id', this.service_category_id);
                let response = await postApiData({url: '/api/entities', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getTable(null);
                    console.log("success")
                    this.closeModal();
                    this.clearForm();
                }
                else{
                    alert('some errors occur');
                }
            },

            closeModal() {
                document.getElementById("close").click();
            },

            clearForm() {
                this.name = null,
                this.selectedInventoryType = null,
                this.inventoryable_id = null,
                this.typeList = []
            },

            isActiveToggled(id){
                let index = this.tableList.findIndex(table => table.id == id);
                if(index != -1){
                    if(this.tableList[index].is_available == 1){
                        this.tableList[index].is_available = 0;
                    }
                    else{
                        this.tableList[index].is_available = 1;
                    }

                    let url = `/api/is_active`;
                    let formData = new FormData();
                    formData.append('id', id);
                    formData.append('type', 'entity');
                    let response = postApiData({url: url, form_data: formData, token: this.getToken()});
                }
            },

            deleteBtnClicked(id){
                this.deleteId = id;
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/entities/${this.deleteId}`;
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    this.getTable();
                }
                else{
                    alert('some errors occur');
                }
            },

            async searchBtnClicked(){
                let url = null;
                if(this.searchInput){
                    url = `/api/entities?type=table&search_input=${this.searchInput}&page=1`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.tableList = response.data.data;
                }
            },

            clearSearchBtnClicked(){
                this.searchInput = null;
                this.getTableList(null);
            },
        },

        created(){
            this.getTableList(null);
            this.getAreaList();
            this.getServiceCategoryList();
        },

        mounted()
        {
            initTE({ Modal,Select, Ripple });
        }
    }
</script>
