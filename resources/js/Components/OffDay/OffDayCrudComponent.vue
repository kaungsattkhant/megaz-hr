<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Day Off
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
                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
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
                                    Shift
                                </th>
                                <th scope="col" class="">
                                    From
                                </th>
                                <th scope="col" class="">
                                    To
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(shift, index) in timeShiftList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ shift.shift.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ shift.from_time }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ shift.to_time }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3"
                                            @click="editBtnClicked(shift, index)">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <button @click="deleteBtnClicked(shift.id)"
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
        id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">    
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create Time Shift
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss id="close_create_modal"
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div v-show="!isShiftStep">
                        <div class="mb-4">
                            <div>
                                <label class="block text-sm text-black mb-3">Time Shift</label>
                                <div class="flex gap-x-2">
                                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                                        data-te-select-wrapper-ref>
                                        <select data-te-select-init data-te-select-placeholder="Select Shift"
                                            data-te-select-filter="true" name="" id="" v-model="selectedShift" class="input-ui">
                                            <option v-if="shiftList.length < 1" selected disabled> Not Found! </option>
                                            <option :value="shift" v-for="(shift, index) in shiftList"
                                                :key="index"> {{ shift.name }} </option>
                                        </select>
                                        
                                    </div>
                                    <button @click="isShiftStep = true" class="px-2"><i class="fal fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                From
                            </label>
                            <input type="time" placeholder="From" v-model="fromTime" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                To
                            </label>
                            <input type="time" placeholder="To" v-model="toTime" class="input-ui">
                        </div>
                    </div>
                    <div v-show="isShiftStep">
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" placeholder="Name" v-model="shiftName" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <button type="button" @click="btnClickedAddShift()"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                        </div>
                    </div>
                </div>

                    <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                    </button>
                    <button type="button" @click="btnCreateTimeShift()"
                            class="add-btn focus:outline-none focus:ring-0 " >
                            Create
                    </button>
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
                        Edit Time Shift
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
                    <div v-show="!isShiftStep">
                        <div class="mb-4">
                            <div>
                                <label class="block text-sm text-black mb-3">Time Shift</label>
                                <div class="flex gap-x-2">
                                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                                        data-te-select-wrapper-ref>
                                        <select data-te-select-init data-te-select-placeholder="Select Shift"
                                            data-te-select-filter="true" name="" id="" v-model="selectedShiftEdit" class="input-ui">
                                            <option v-if="shiftList.length < 1" selected disabled> Not Found! </option>
                                            <option :value="shift" v-for="(shift, index) in shiftList"
                                                :key="index"> {{ shift.name }} </option>
                                        </select>
                                    </div>
                                    <button @click="isShiftStep = true" class="px-2">
                                        <i class="fal fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="ftime" class="label-form mb-3">
                                From
                            </label>
                            <input type="time" placeholder="From" id="ftime" v-model="fromTimeEdit" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                To
                            </label>
                            <label for="toTime">
                                <input type="time" placeholder="To" id="toTime" v-model="toTimeEdit" class="input-ui">
                            </label>
                        </div>
                    </div>
                    <div v-show="isShiftStep">
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" placeholder="Name" v-model="shiftName" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <button type="button" @click="btnClickedAddShift()"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                        </div>
                    </div>
                </div>

                    <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                    </button>
                    <button type="button" @click="btnEditTimeShift()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Edit
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
            timeShiftList: [],
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            shiftName:null,
            isShiftStep:false,
            shiftList:[],
            selectedShift:null,
            fromTime:null,
            toTime:null,

            editDetail:null,
            selectedShiftEdit:null,
            fromTimeEdit:null,
            toTimeEdit:null,

            url:'/api/time_shifts',
            url_search:'',
            url_department:'',
            url_role:'',
            deleteId:null,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getShiftList(){
            let response = await getApiData({ url: '/api/shifts', token: this.getToken() });
            if (response.data) {
                this.shiftList = response.data;
            }
        },
        addBtnClicked(){
            this.isShiftStep = false;
            this.shiftName = null;
            this.selectedShift = null;
            this.fromTime = null;
            this.toTime = null;
        },
        btnClickedAddShift(){
            if(!this.shiftName){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else{
                this.addShift();
            }
        },
        async addShift(){
            let formData = new FormData();
            formData.append('name',this.shiftName);
            let response = await postApiData({url:`/api/shifts`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getShiftList();
                this.isShiftStep = false;
                this.shiftName = null;
            }
        },

        btnCreateTimeShift(){
            if(!this.selectedShift){
                this.alertValidationMessage(`Time Shift`);
                return 1;
            }
            else if(!this.fromTime){
                this.alertValidationMessage(`From`);
                return 1;
            }
            else if(!this.toTime){
                this.alertValidationMessage(`To`);
                return 1;
            }
            else{
                this.createShift();
            }
        },
        async createShift(){
            let formData = new FormData();
            formData.append('shift_id',this.selectedShift.id);
            formData.append('from_time',this.fromTime);
            formData.append('to_time',this.toTime);
            let response = await postApiData({url:`/api/time_shifts`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getTimeShiftList();
                document.getElementById("close_create_modal").click();
            }
        },

        async editBtnClicked(shift, index){
            let url = `/api/time_shifts/` + shift.id;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.editDetail = response.data;
            }
            this.selectedShiftEdit = this.shiftList.find(shift => shift.id == this.editDetail.shift_id);
            if(this.editDetail.from_time){
                let from_time = this.editDetail.from_time;
                let Ftime = from_time.split(':').slice(0, 2).join(':');
                this.fromTimeEdit = Ftime;
                console.log(Ftime)
            }
            if(this.editDetail.to_time){
                let to_time = this.editDetail.to_time;
                let Ttime = to_time.split(':').slice(0, 2).join(':');
                this.toTimeEdit = Ttime;
            }
        },
        btnEditTimeShift(){
            if(!this.selectedShiftEdit){
                this.alertValidationMessage(`Time Shift`);
                return 1;
            }
            else if(!this.fromTimeEdit){
                this.alertValidationMessage(`From`);
                return 1;
            }
            else if(!this.toTimeEdit){
                this.alertValidationMessage(`To`);
                return 1;
            }
            else{
                this.editShift();
            }
        },
        async editShift(){
            let formData = new FormData();
            formData.append('shift_id',this.selectedShiftEdit.id);
            formData.append('from_time',this.fromTimeEdit);
            formData.append('to_time',this.toTimeEdit);
            let response = await postApiData({url:`/api/time_shifts/`+this.editDetail.id, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getTimeShiftList();
                document.getElementById("close_edit_modal").click();
            }
        },

        async getTimeShiftList(pageNumber) {
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
                this.timeShiftList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
            }
        },

        async searchBtnClicked() {
            this.url_search = '&search=' + this.searchInput
            this.getTimeShiftList(1);
        },
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.url_search = '';
            this.getTimeShiftList(1);
        },



        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/time_shifts/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getTimeShiftList(1);
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

        this.getShiftList();
        this.getTimeShiftList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>