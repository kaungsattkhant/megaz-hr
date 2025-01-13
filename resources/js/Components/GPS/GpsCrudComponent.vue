<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Time Shift Management
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
            </div>
            <div class="flex pr-0 gap-x-4">
                <!-- <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" @change="selectedTypeChanged()"
                        data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                        <option :value="type.value" v-for="(type, typeIndex) in typeList"
                            :key="typeIndex"> {{ type.name }} </option>
                    </select>
                </div> -->
                <!-- <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
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
                                    GPS
                                </th>
                                <th scope="col" class="">
                                    Latitude
                                </th>
                                <th scope="col" class="">
                                    Longitude
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(gps, index) in gpsList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ gps.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ gps.latitude }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ gps.longitude }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3"
                                            @click="editBtnClicked(gps, index)">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <!-- <button @click="deleteBtnClicked(gps.id)"
                                            data-te-toggle="modal" data-te-target="#deleteModal" id="delete-btn" class="pr-1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button> -->
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
                        <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                            Delete ?
                        </h5>
                        <button type="button"
                            class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative flex-auto p-4" data-te-modal-body-ref>
                        <p>
                            Are you sure ?
                        </p>
                    </div>
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
    
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="edit_modal" tabindex="-1" aria-labelledby="edit_modalLabel" aria-hidden="true">    
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="edit_modalLabel">
                        Edit GPS
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss id="close_edit_modal"
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <!-- <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Name
                        </label>
                        <input type="text" placeholder="Name" v-model="name" class="input-ui">
                    </div> -->
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Latitude
                        </label>
                        <input type="text" placeholder="Latitude" v-model="latitude" class="input-ui">
                    </div>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Longitude
                        </label>
                        <input type="text" placeholder="Longitude" v-model="longitude" class="input-ui">
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                    </button>
                    <button type="button" @click="btnClickedEditGps()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                    </button>
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
            gpsList: [],
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            name:null,
            latitude:null,
            longitude:null,
            editDetail:null,

            url:'/api/gps',
            url_search:'',
            url_department:'',
            url_role:'',
            deleteId:null,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getGpsList(pageNumber) {
            // let url = this.url + pageNumber + this.url_search + this.url_department + this.url_role;
            let url = this.url;
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
                this.gpsList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
            }
        },

        async editBtnClicked(gps, index){
            let url = `/api/gps/` + gps.id;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.editDetail = response.data;
                this.name = response.data.name;
                this.latitude = response.data.latitude;
                this.longitude = response.data.longitude;
            }
            
        },
        btnClickedEditGps(){
            if(!this.name){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else if(!this.latitude){
                this.alertValidationMessage(`Latitude`);
                return 1;
            }
            else if(!this.longitude){
                this.alertValidationMessage(`Longitude`);
                return 1;
            }
            else{
                this.editGps();
            }
        },
        async editGps(){
            let formData = new FormData();
            formData.append('name',this.name);
            formData.append('latitude',this.latitude);
            formData.append('longitude',this.longitude);
            let response = await postApiData({url:`/api/gps/`+this.editDetail.id, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getGpsList();
                document.getElementById("close_edit_modal").click();
            }
        },

        

        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getGpsList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getGpsList(1);
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
        this.getGpsList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>