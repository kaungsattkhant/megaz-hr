<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search" v-model="searchInput">

                <i class="fal fa-search"></i>
            </label>

            <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
            <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button>
        </div>
        <div class="flex justify-end flex-col">

            <button type="button" class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                data-te-toggle="modal" data-te-target="#create_modal">
                Add New
            </button>
        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Room
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Price Per Hour
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- looping start -->
                        <div class="contents" v-for="(room, index) in roomList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ room.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ room.service_category.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ room.price_per_hour }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button id="edit-btn" class="pr-1" @click="deleteBtnClicked(room.id)"
                                    data-te-toggle="modal" data-te-target="#deleteModal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>

                            <!-- looping end -->
                    </tbody>
                </table>
            </div>

            <div class="mt-2 ml-2">
                <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                    <li v-if="!isFirstGroup">
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="previousPaginationGroupBtnClicked"
                        :disabled="isFirstGroup">
                            Previous
                        </button>
                    </li>

                    <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                (current)
                            </span>
                        </button>
                        <button v-else
                            class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                        </button>
                    </li>
                    <li v-if="!isLastGroup">
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="nextPaginationGroupBtnClicked"
                        :disabled="isLastGroup">
                            Next
                        </button>
                    </li>
                </ul>

                <ul v-else class="list-style-none flex">
                    <li v-for="(pageNumber, pageNumberIndex) in pageNumbers" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                (current)
                            </span>
                        </button>
                        <button v-else
                            class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                        </button>
                    </li>
                </ul>
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

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                            Create Room / Table
                        </h5>
                        <button type="button" id="close" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Room / Table Name
                            </label>
                            <input type="text" placeholder="Room / Table Name" v-model="name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Category
                            </label>
                            <select name="" id="" v-model="service_category_id"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="service.id" v-for="(service,index) in serviceCategoryList">{{ service.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Price Per Hour
                            </label>
                            <input type="text" placeholder="Price Per Hour" v-model="pricePerHour"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Selling Area
                            </label>
                            <select name="" id="" v-model="area_id"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="area.id" v-for="(area,index) in areaList">{{ area.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" @click="createBtnClicked"
                        class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div
        data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div
            data-te-modal-dialog-ref
            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5
                    class="text-xl font-medium leading-normal text-neutral-800 "
                    id="exampleModalLabel">
                    Delete ?
                    </h5>
                    <!--Close button-->
                    <button
                    type="button"
                    class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss
                    aria-label="Close">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
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
                    <button
                    type="button"
                    class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                    data-te-modal-dismiss
                    >
                        Close
                    </button>
                    <button @click="confirmDeleteBtnClicked"
                    type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                    class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 "
                    >
                        Delete
                    </button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Modal, Ripple, Select, initTE, Input, Dropdown } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                roomList:[],
                entityTypeList:['Room','Table'],
                serviceCategoryList:[],
                areaList:[],

                name: null,
                pricePerHour:null,
                entityType:'room',
                area_id:null,
                service_category_id:null,
                deleteId: null,

                searchInput: null,

                per_page: 20,
                pageNumbers: [],
                currentPage: 1,
                paginationGroupsCount: 1,
                per_group: 10,
                groupedPageNumbers: [],
                currentGroup: 0,
                isFirstGroup: true,
                isLastGroup: false,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getRoomList(pageNumber){
                const response = await getApiData({ url: '/api/entities?type=room', token: this.getToken() });
                if(response.data){
                    this.roomList = response.data;
                }
            },

            async getAreaList(){
                const response = await getApiData({ url: '/api/areas', token: this.getToken() });
                if(response.data){
                    this.areaList = response.data;
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
                formData.append('service_category_id', this.service_category_id);
                let response = await postApiData({url: '/api/entities', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getRoomList(null);
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

            deleteBtnClicked(id){
                this.deleteId = id;
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/entities/${this.deleteId}`;
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    this.getRoomList(null);
                }
                else{
                    alert('some errors occur');
                }
            },

            async searchBtnClicked(){
                let url = null;
                if(this.searchInput){
                    url = `/api/entities?type=room&search_input=${this.searchInput}&page=1`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.roomList = response.data.data;
                }
            },

            clearSearchBtnClicked(){
                this.searchInput = null;
                this.searchCategory = null;
                this.getRoomList(null);
            },

            pageBtnClicked(pageNumber){
                this.currentPage = pageNumber;
                this.getRoomList(this.currentPage);
            },

            nextPaginationGroupBtnClicked(){
                this.currentGroup += 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getRoomList(this.currentPage);
            },

            previousPaginationGroupBtnClicked(){
                this.currentGroup -= 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getRoomList(this.currentPage);
            },

            firstPaginationGroupBtnClicked(){
                this.currentGroup = 0;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getRoomList(this.currentPage);
            },

            lastPaginationGroupBtnClicked(){
                this.currentGroup = this.paginationGroupsCount - 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getRoomList(this.currentPage);
            }
        },

        created(){
            this.getRoomList(null);
            this.getAreaList();
            this.getServiceCategoryList();
        },

        mounted()
        {
            initTE({ Modal,Select, Ripple, Dropdown });
        }
    }
</script>
