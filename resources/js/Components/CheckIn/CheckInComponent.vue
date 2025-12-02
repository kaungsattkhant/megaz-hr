<template>

    <div class="margin-bg">
        <div class="card-shadow">
            <div class="pt-2">
                <p class=" page-title">
                    Check In
                </p>
            </div>
            <div class="btn-container pt-6">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input w-24">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
                    <button class="add-btn h-8" data-te-toggle="modal" data-te-target="#create_modal">Add</button>
                </div>
                <div class="flex pr-0 gap-x-4">
                    <div class="relative">
                        <label for="search" class="border border-gray-200 rounded bg-white text-xs mx-2 px-2 py-2 absolute left-0 ml-0 -top-[90%] border-b-0"> From </label>
                        <input type="date" v-model="fromDate" class="search-input rounded " @change="fromDateChanged()">
                    </div>

                    <div class="relative">
                        <label for="search" class="border border-gray-200 rounded bg-white text-xs mx-2 px-2 py-2 absolute left-0 ml-0 -top-[90%] border-b-0"> To </label>
                        <input type="date" v-model="toDate" class="search-input rounded" @change="toDateChanged()">
                    </div>
                    <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Staff" @change="selectedStaffChanged()"
                            data-te-select-filter="true" name="" id="" v-model="selectedStaff" class="input-ui">
                            <option :value="staff" v-for="(staff, staffIndex) in staffList"
                                :key="staffIndex"> {{ staff.name }} </option>
                        </select>
                    </div>
                </div>
                <!-- <div class="flex pr-0 gap-x-4">
                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
                        Add New
                    </button>
                </div> -->
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
                                    Name
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
                                    Total
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
                            <!-- looping start -->
                            <div class="contents" v-for="(checkIn, index) in checkInList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ checkIn.staff_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ checkIn.time_shift.shift.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div class="flex w-full justify-center gap-x-4">
                                            {{ checkIn.check_in_date_time }}
                                            <img class="w-12 rounded-sm" :src="checkIn.check_in_photo_url" alt="">
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div class="flex w-full justify-center gap-x-4">
                                            {{ checkIn.check_out_date_time }}
                                            <img class="w-12 rounded-sm" :src="checkIn.check_out_photo_url" alt="">
                                        </div>
                                        {{ checkIn.check_out_date_time }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ checkIn.total }}
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
                            <tr class=" !text-center" v-if="checkInList.length < 1 && !loading">
                                <td class="" colspan="7">
                                    No Data Here
                                </td>
                            </tr>
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
                        Create Check In
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
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Staff
                        </label>
                        <multiselect v-model="createCheckinStaff" :options="staffList"
                        :clear-on-select="false"
                        :preserve-search="false" placeholder="Staff" label="name" track-by="id"
                        :preselect-first="false"></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Shift
                        </label>
                        <multiselect v-model="selectedTimeshift" :options="timeshifts"
                        :clear-on-select="false"
                        :preserve-search="false" placeholder="Shift"
                        :custom-label="timeshiftCustomLabel"
                        track-by="id"
                        :preselect-first="false"></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Check In Datetime
                        </label>
                        <input type="datetime-local" placeholder="Check In At" v-model="checkInDateTime" class="input-ui">
                    </div>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Check Out Datetime
                        </label>
                        <input type="datetime-local" placeholder="Check Out At" v-model="checkOutDateTime" class="input-ui">
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close" id="create_modal_close_btn">
                        Cancel
                    </button>
                    <LoadingButton
                    :loading="checkInCreateBtnLoading"
                    text="Create"
                    loading-text="Loading..."
                    @click="createCheckInBtnClicked"></LoadingButton>
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
import Multiselect from "vue-multiselect";
import LoadingButton from "../Common/LoadingButton.vue";

export default {
    components: {
        TableSkeleton,
        Multiselect,
        LoadingButton
    },
    data() {
        return {
            checkInList: [],
            staffList:[],
            selectedStaff:null,
            fromDate:null,
            toDate:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/check_ins',
            url_search:'',
            url_staff:'',
            url_from:'',
            url_to:'',
            deleteId:null,

            loading: true,

            checkInCreateBtnLoading: false,
            createCheckinStaff: null,
            timeshifts: [],
            selectedTimeshift: null,
            checkInDateTime: null,
            checkOutDateTime: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getStaffList(){
            let url = '/api/staffs'
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data;
            }
        },
        async getCheckInList(pageNumber) {
            this.loading = true;
            // let url = this.url + pageNumber + this.url_search + this.url_staff + this.url_from + this.url_to;
            let url = this.url + this.url_search + this.url_staff + this.url_from + this.url_to;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.checkInList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
            }
        },
        selectedStaffChanged(){
            this.url_from = '';
            this.url_to = '';
            this.url_staff = '?staff_id=' + this.selectedStaff.id
            this.getCheckInList();
        },
        fromDateChanged(){
            this.url_staff = '';
            this.url_from = '?from_date=' + this.fromDate
            this.getCheckInList();
        },
        toDateChanged(){
            this.url_to = '&to_date=' + this.toDate
            this.getCheckInList();
        },

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });
        },

        getTimeshifts(){
            getApiData({url: `/api/time_shifts`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.timeshifts = response.data;
                }
            });
        },

        timeshiftCustomLabel(timeshift){
            return `${timeshift.shift.name} (${timeshift.from_time} - ${timeshift.to_time})`;
        },

        createCheckInBtnClicked(){
            if(!this.createCheckinStaff){
                this.alertValidationMessage("check-in staff");
                return;
            }
            if(!this.selectedTimeshift){
                this.alertValidationMessage("check-in timeshift");
                return;
            }
            if(!this.checkInDateTime){
                this.alertValidationMessage("check-in time");
                return;
            }
            if(!this.checkOutDateTime){
                this.alertValidationMessage("check-out time");
                return;
            }
            this.checkInCreateBtnLoading = true;
            let formData = new FormData();
            formData.append('staff_id', this.createCheckinStaff.id);
            formData.append('time_shift_id', this.selectedTimeshift.id);
            formData.append('chek_in_date_time', this.checkInDateTime);
            formData.append('chek_out_date_time', this.checkOutDateTime);
            postApiData({url: `/api/check_ins/admin_post`, form_data: formData, token: this.getToken()})
            .then((response)=>{
                this.checkInCreateBtnLoading = false;
                if(response.success){
                    document.getElementById('create_modal_close_btn').click();
                    this.createCheckinStaff = null;
                    this.selectedTimeshift = null;
                    this.checkInDateTime = null;
                    this.getCheckInList(1);
                }else{
                    this.$notify({
                        title: 'Error',
                        text: `${response.message}`,
                        type: 'error'
                    });
                }
            });
        },
    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getCheckInList(1);
        this.getStaffList();
        this.getTimeshifts();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
