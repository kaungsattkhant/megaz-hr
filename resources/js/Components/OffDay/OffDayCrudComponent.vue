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
                <button type="button" v-show="feature.includes('off-day.create')"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_holiday_modal" @click="addHolidayModalBtnClicked">
                    Add Holiday
                </button>
                <button type="button" v-show="feature.includes('off-day.create')"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
                    Add Off Day
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
                                    Holiday
                                </th>
                                <th scope="col" class="">
                                    Department
                                </th>
                                <th scope="col" class="">
                                    Repetition
                                </th>
                                <th scope="col" class="" v-show="['off-day.edit', 'off-day.delete'].some(f => feature.includes(f))">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(offDay, index) in offDayList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ offDay.day }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-for="day in offDay.off_day.off_day_assignments">
                                            {{ day.offdayable.name }} ,
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ offDay.off_day.repetition }}
                                    </td>
                                    <td class="whitespace-nowrap" v-show="['off-day.edit', 'off-day.delete'].some(f => feature.includes(f))">
                                        <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn"
                                            class="pr-3" @click="editBtnClicked(offDay, index)"  v-show="feature.includes('off-day.edit')">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <button @click="deleteBtnClicked(offDay.id)" data-te-toggle="modal" v-show="feature.includes('off-day.delete')"
                                            data-te-target="#deleteModal" id="delete-btn" class="pr-1">
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
                                @click="getOffDayList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOffDayList(currentPage + 1)">
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
                        Create Off Day
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
                        <label for="" class="label-form mb-3">
                            Day
                        </label>
                        <!-- <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Day"
                                data-te-select-filter="true" name="" id="" v-model="selectedDay" class="input-ui !text-black text-sm">
                                <option :value="day" v-for="(day, index) in dayList"
                                    :key="index"> {{ day.name }} </option>
                            </select>
                        </div> -->
                        <multiselect v-model="selectedDay" :options="dayList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                        :preserve-search="true" placeholder="Select Day" label="name" track-by="name" :preselect-first="false"
                        :taggable="true" @tag="addTag" id="tagging">
                            <!-- <template #selection="{ values, search, isOpen }">
                                <span class="multiselect__single"
                                    v-if="values.length"
                                    v-show="!isOpen">{{ values.length }} Day selected</span>
                            </template> -->
                        </multiselect>
                    </div>
                    <!-- <div><label class="typo__label">Tagging</label>
                        <multiselect id="tagging" v-model="selectedDay" tag-placeholder="Add this as new tag" placeholder="Search or add a tag" label="name"
                                     track-by="value" :options="dayList" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
                        <pre class="language-json"><code>{{ value }}</code></pre>
                      </div> -->
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Repetition
                        </label>
                        <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Repetition"
                                data-te-select-filter="true" name="" id="" v-model="selectedRepetition" class="input-ui !text-black text-sm">
                                <option :value="rep" v-for="(rep, index) in repetitionList"
                                    :key="index"> {{ rep.name }} </option>
                                <!-- <option value="Default"> Default </option>
                                <option value="Weekly"> Weekly </option>
                                <option value="Bi-weekly"> Bi-weekly </option>
                                <option value="Monthly"> Monthly </option> -->
                            </select>
                        </div>
                        <!-- <multiselect v-model="selectedRepetition" :options="repetitionList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                        :preserve-search="true" placeholder="Select Repetition" label="name" track-by="name" :preselect-first="false">
                            <template #selection="{ values, search, isOpen }">
                                <span class="multiselect__single"
                                    v-if="values.length"
                                    v-show="!isOpen">{{ values.length }} Repetition selected</span>
                            </template>
                        </multiselect> -->
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Type
                        </label>
                        <div class="bg-white mb-0 w-full inline-block h-[34px] !text-black !text-sm"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Type" @change="typeChange"
                                data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui !text-black text-sm">
                                <option :value="type" v-for="(type, typeIndex) in typeList" :key="typeIndex">
                                    {{ type.name }}
                                </option>
                                <!-- <option value="department"> Department </option>
                                <option value="staff"> Staff </option> -->
                            </select>
                        </div>
                    </div>
                    <div class="mb-4" v-show="selectedType && selectedType.value === 'department'">
                        <label for="" class="label-form mb-3">
                            Department
                        </label>
                        <!-- <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Department"
                                data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black text-sm">
                                <option :value="department" v-for="(department, index) in departmentList"
                                    :key="index"> {{ department.name }} </option>
                            </select>
                        </div> -->

                        <multiselect v-model="selectedDepartment" :options="departmentList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                        :preserve-search="true" placeholder="Select Department" label="name" track-by="id" :preselect-first="true">
                            <template #selection="{ values, search, isOpen }">
                                <span class="multiselect__single"
                                    v-if="values.length"
                                    v-show="!isOpen">{{ values.length }} Department selected</span>
                            </template>
                        </multiselect>
                    </div>
                    <div class="mb-4" v-show="selectedType && selectedType.value === 'staff'">
                        <label for="" class="label-form mb-3">
                            Staff
                        </label>
                        <!-- <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Staff"
                                data-te-select-filter="true" name="" id="" v-model="selectedStaff" class="input-ui !text-black text-sm">
                                <option :value="staff" v-for="(staff, index) in staffList"
                                    :key="index"> {{ staff.name }} </option>
                            </select>
                        </div> -->
                        <multiselect v-model="selectedStaff" :options="staffList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                        :preserve-search="true" placeholder="Select Staff" label="name" track-by="id" :preselect-first="true">
                            <template #selection="{ values, search, isOpen }">
                                <span class="multiselect__single"
                                    v-if="values.length"
                                    v-show="!isOpen">{{ values.length }} Staff selected</span>
                            </template>
                        </multiselect>
                    </div>
                </div>

                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="btnCreateOffDay()"
                        class="add-btn focus:outline-none focus:ring-0 ">
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- holiday modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="create_holiday_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create Holiday
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        id="close_create_holiday_modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Name
                        </label>
                        <input type="text" v-model="name" class="input-ui mb-2">
                    </div>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Day
                        </label>
                        <input type="text" id="daterange" v-model="selectedDateRange" class="form-control input-ui" />
                    </div>

                </div>

                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="btnCreateHoliday()"
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

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            offDayList: [],
            repetitionList:[
                // {value: 'Default', name: 'Default'},
                {value: 'Weekly', name: 'Weekly'},
                {value: 'Bi-weekly', name: 'Bi weekly'},
                {value: 'Monthly', name: 'Monthly'},
            ],
            dayList:[
                {value: 'Monday', name: 'Monday'},
                {value: 'Tuesday', name: 'Tuesday'},
                {value: 'Wednesday', name: 'Wednesday'},
                {value: 'Thursday', name: 'Thursday'},
                {value: 'Friday', name: 'Friday'},
                {value: 'Saturday', name: 'Saturday'},
                {value: 'Sunday', name: 'Sunday'},
                {value: 'Sabbath-day', name: 'Sabbath day'},
            ],
            typeList:[
                {value: 'staff', name: 'Staff'},
                {value: 'department', name: 'Department'},
            ],
            departmentList:[],
            staffList:[],

            selectedDay:[],
            selectedRepetition:null,
            selectedType:null,
            selectedDepartment:[],
            selectedStaff:[],

            name: null,
            selectedDate: [],

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,



            url:'/api/hr/off_days?off_day',
            url_search:'',
            url_department:'',
            url_role:'',
            deleteId:null,

            selectedDateRange: null,


            feature: this.getFeature(),
            test: null,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        async getStaffList(){
            let response = await getApiData({ url: '/api/staffs', token: this.getToken() });
            if (response.data) {
                this.staffList = response.data;
            }
        },
        typeChange(){
            this.selectedStaff = [];
            this.selectedDepartment = [];
        },
        btnCreateOffDay(){
            if(this.selectedDay.length < 1){
                this.alertValidationMessage(`Day`);
                return 1;
            }
            else if(this.selectedRepetition.length < 1){
                this.alertValidationMessage(`Repetition`);
                return 1;
            }
            else if(this.selectedType.value == 'department' && this.selectedDepartment.length < 1){
                this.alertValidationMessage(`Department`);
                return 1;
            }
            else if(this.selectedType.value == 'staff' && this.selectedStaff.length < 1){
                this.alertValidationMessage(`Staff`);
                return 1;
            }
            else{
                this.createOffDay();
            }
        },
        async createOffDay(){

            let selectedDayList = [];
            this.selectedDay.forEach((day) => {
                selectedDayList.push(String(day.value))
            })

            let formData = new FormData();
            formData.append('repetition', this.selectedRepetition.value);
            formData.append('days', JSON.stringify(selectedDayList));
            formData.append('offdayable_type',this.selectedType);

            if(this.selectedType == 'department'){
                let offdayable_id = [];
                this.selectedDepartment.forEach((department) => {
                    offdayable_id.push(String(department.id))
                })
                formData.append('offdayable_id', JSON.stringify(offdayable_id));
            }
            if(this.selectedType.value === 'staff'){
                let offdayable_id = [];
                this.selectedStaff.forEach((staff) => {
                    offdayable_id.push(String(staff.id))
                })
                formData.append('offdayable_id', JSON.stringify(offdayable_id));
            }
            let response = await postApiData({url:`/api/hr/off_days`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getOffDayList();
                document.getElementById("close_create_modal").click();
            }else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },



        async getOffDayList(pageNumber) {
            // let url = this.url + pageNumber + this.url_search + this.url_department + this.url_role;
            let url = this.url;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.offDayList = response.data.data;
            }
        },
        addBtnClicked(){
            this.selectedDay = [];
            this.selectedRepetition = [];
            this.selectedType = null;
            this.selectedDepartment = [];
            this.selectedStaff = []
        },

        addHolidayModalBtnClicked(){
            this.name = null;
            this.selectedDateRange = [];
        },
        btnCreateHoliday(){
            if(!this.name){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else if(this.selectedDate.length < 1){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            else{
                this.createHoliday();
            }
        },
        async createHoliday(){
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('date', JSON.stringify(this.selectedDate));

            let response = await postApiData({url:`/api/hr/public_holidays`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getOffDayList();
                document.getElementById("close_create_holiday_modal").click();
            }else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getOffDayList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getOffDayList(1);
        // },



        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/hr/off_days/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getOffDayList(1);
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

        addTag (newTag) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.dayList.push(tag)
            this.selectedDay.push(tag)
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
        const vm = this; // Save Vue instance reference
        const waitForLibraries = () => {
            if (window.$ && window.moment && window.$.fn.daterangepicker) {
                $('#daterange').daterangepicker({
                    opens: 'left',
                    locale: { format: 'YYYY-MM-DD' }
                },
                function(start, end) {
                    const allDates = [];
                    let currentDate = start.clone();

                    while (currentDate.isSameOrBefore(end)) {
                        allDates.push(currentDate.format('YYYY-MM-DD'));
                        currentDate.add(1, 'day');
                    }

                    console.log('📆 All Dates:', allDates);

                    // Update Vue data
                    vm.selectedDate = allDates;
            });
            } else {
                setTimeout(waitForLibraries, 100);
            }
        };

        waitForLibraries();
    },
    created() {
        this.getDepartmentList();
        this.getStaffList();
        this.getOffDayList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
