<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Assign Shift
            </p>
        </div>
        <!-- <p v-for="value in selectedDates">
            {{ value }}
        </p> -->

        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <!-- <div class="col-span-7"></div> -->
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Department
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="changeDepartment()"
                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black text-sm">
                        <option :value="department" v-for="(department, index) in departmentList"
                            :key="index"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Role
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" @change="roleChange()"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Staff
                </label>
                <multiselect class="multi-select"
                    v-model="selectedStaff"
                    :options="staffList"
                    :multiple="true"
                    :close-on-select="false"
                    :clear-on-select="false"
                    :preserve-search="true"
                    placeholder="Select Staff"
                    label="name"
                    track-by="id"
                    :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        Staff selected</span>
                    </template>
                </multiselect>
                <!-- <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Staff"
                        data-te-select-filter="true" name="" id="" v-model="selectedStaff" class="input-ui !text-black text-sm">
                        <option :value="staff" v-for="(staff, index) in staffList"
                            :key="index"> {{ staff.name }} </option>
                    </select>
                </div> -->
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Type
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type"
                        data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui !text-black text-sm">
                        <option :value="type" v-for="(type, index) in typeList"
                            :key="index"> {{ type.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-6 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date
                </label>
                <input v-model="selectedDate" autocomplete="off" ref="picker"   
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div v-show="selectedType?.value === 'shift'" class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Shift
                </label>
                <!-- <multiselect
                v-model="selectedTimeShift"
                :options="timeShiftList"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Shift"
                label="name"
                track-by="id"
                :custom-label="timeshiftCustomLabel"
                :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        Shift selected</span>
                    </template>
                </multiselect> -->

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Shift"
                        data-te-select-filter="true" name="" id="" v-model="selectedTimeShift" class="input-ui !text-black text-sm">
                        <option :value="timeShift" v-for="(timeShift, index) in timeShiftList"
                            :key="index"> {{ timeShift.shift.name }} ({{ timeShift.from_time }} - {{ timeShift.to_time }})</option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md" v-show="selectedDepartment?.slug === 'kitchen' || selectedDepartment?.slug === 'bar' || selectedDepartment?.slug === 'catering'">
                <label for="" class="label-form mb-3">
                    {{ selectedDepartment?.slug === 'bar' || selectedDepartment?.slug === 'catering' ? 'Selling' : 'Cooking' }}
                    Area
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Area"
                        data-te-select-filter="true" name="" id="" v-model="selectedArea" class="input-ui !text-black text-sm">
                        <option :value="area" v-for="(area, index) in areaList"
                            :key="index"> {{ area.name }} </option>
                    </select>
                </div>
            </div>
            
            <div class="col-span-4 flex gap-x-4">
                <div>
                    <label for="" class="label-form mb-3">
                        &nbsp;
                    </label>
                    <button class="add-btn py-[9px]" @click="btnClickedAddShift()">
                        Add
                    </button>
                </div>
                <div v-show="offDaySetting === 'default'">
                    <label for="" class="label-form mb-3">
                        &nbsp;
                    </label>
                    <a href="/off_day" class="add-btn py-[9px] block text-center">
                        Off Day
                    </a>
                </div>
                <!-- <div v-show="offDaySetting === 'custom'">
                    <label for="" class="label-form mb-3">
                        &nbsp;
                    </label>
                    <button class="add-btn py-[9px]">
                        Off Day
                    </button>
                </div> -->
            </div>
            <div class="col-span-2">
                
            </div>
        </div>

        <div class=" bg-white py-8 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="!text-left">
                        <tr>
                            <th scope="col" class="">
                                Date
                            </th>
                            <th scope="col" class="">
                                Department
                            </th>
                            <th scope="col" class="">
                                Role
                            </th>
                            <th scope="col" class="">
                                Staff
                            </th>
                            <th scope="col" class="">
                                Shift
                            </th>
                            <th scope="col" class="">
                                Area
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="!text-left">
                        <tr class="" v-for="(item, itemIndex) in selectedAssignList"
                            :key="itemIndex">
                            <td class="">
                                {{ item.date_time }}
                            </td>
                            <td class="">
                                {{ item.department_name }}
                            </td>
                            <td class="">
                                {{ item.role_name }}
                            </td>
                            <td class="">
                                {{ item.staff_name }}
                            </td>
                            <td class="" :class="item.type === 'off_day' ? '!text-red-600' : ''">
                                {{ item.type === 'off_day' ? 'Off Day' : item.timeshift_name }}
                                <!-- {{ item.timeshift_name }} -->
                            </td>
                            <td class="">
                                {{ item.area_name ? item.area_name : '-' }}
                            </td>
                            <td class="text-center">
                                <button @click="removeItem(itemIndex)">
                                    <i class="fas fa-times  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class=" !text-center" v-if="selectedAssignList.length < 1">
                            <td class="" colspan="6">
                                No Data Here
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <button class="add-btn" @click="btnClickedCreateShiftAssign()">
                Create
            </button>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Tab, Select } from "tw-elements";
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { each } from "lodash";

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            departmentList: [],
            roleList: [],
            staffList: [],
            timeShiftList: [],
            areaList: [],
            typeList: [
                {value: 'shift', name: 'Shift'},
                {value: 'off_day', name: 'Off Day'}
            ],

            selectedType: null,
            selectedDate: [],
            selectedDepartment: null,
            selectedRole: null,
            selectedStaff: [],
            selectedTimeShift: null,
            selectedArea: null,
            
            selectedAssignList: [],

            selectedDates: [],
            fpInstance: null,

            offDaySetting: this.getOffDaySetting(),
        };
    },

    methods: {
        ...mapGetters(['getToken' ,'getOffDaySetting']),
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        async getTimeShiftList(){
            let response = await getApiData({ url: '/api/time_shifts', token: this.getToken() });
            if (response.data) {
                this.timeShiftList = response.data;
            }
        },
        changeDepartment(){
            this.selectedRole = null;
            this.roleList = this.selectedDepartment.roles;
            this.selectedStaff = [];
            this.staffList = [];
            this.selectedArea = null;
            if(this.selectedDepartment.slug === 'kitchen'){
                this.getCookingArea();
            }
            else if(this.selectedDepartment.slug === 'bar' || this.selectedDepartment.slug === 'catering'){
                this.getSellingArea();
            }
            else{
                this.areaList = [];
            }

        },
        async roleChange(){
            let response = await getApiData({ url: '/api/hr/staff_lists_by_role/' + this.selectedRole.id + '/department/' + this.selectedDepartment.id, token: this.getToken() });
            if (response.data) {
                if(response.data.data){
                    this.staffList = response.data.data;
                }
                else{
                    this.staffList = response.data;
                }
            }
        },
        async getCookingArea(){
            let response = await getApiData({ url: '/api/cooking_areas', token: this.getToken() });
            if (response.data) {
                this.areaList = response.data;
            }
        },
        async getSellingArea(){
            let response = await getApiData({ url: '/api/sellings_areas', token: this.getToken() });
            if (response.data) {
                this.areaList = response.data;
            }
        },



        btnClickedAddShift(){
            if(!this.selectedDate){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            else if(!this.selectedDepartment){
                this.alertValidationMessage(`Department`);
                return 1;
            }
            else if(!this.selectedRole){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            else if(this.selectedStaff.length < 1){
                this.alertValidationMessage(`Staff`);
                return 1;
            }
            else if(!this.selectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            else if(this.selectedType?.value === 'shift' && !this.selectedTimeShift){
                this.alertValidationMessage(`Shift`);
                return 1;
            }
            else if((this.selectedDepartment.slug === 'bar' || this.selectedDepartment.slug === 'catering' || this.selectedDepartment.slug === 'kitchen') && !this.selectedArea){
                this.alertValidationMessage(`Area`);
                return 1;
            }
            else{
                this.addShift();
            }
        },
        async addShift(){
            this.selectedStaff.forEach(staff => {
                this.selectedDates.forEach(item => {
                    this.selectedAssignList.push({
                        date_time: item,
                        department_name: this.selectedDepartment.name,
                        role_name: this.selectedRole.name,
                        staff_name: staff.name,
                        staff_id: staff.id,
                        timeshift_name: this.selectedTimeShift?.shift.name,
                        timeshift_id: this.selectedTimeShift?.id,
                        area_name: this.selectedArea?.name,
                        area_id: this.selectedArea?.id,
                        type: this.selectedType.value,
                        type_name: this.selectedType.name
                    })
                });
            });
            

            
            // this.selectedDate = null;
            this.selectedDepartment = null;
            this.selectedRole = null;
            this.selectedStaff = [];
            this.selectedTimeShift = null;
            this.selectedArea = null;

            this.roleList = [];
            this.staffList = [];
            this.areaList = [];
            this.selectedDates = [];
            this.selectedType = null;
            this.fpInstance.clear();
        },
        removeItem(index){
            this.selectedAssignList.splice(index, 1);
        },


        btnClickedCreateShiftAssign(){
            if(this.selectedAssignList.length < 1){
                this.alertValidationMessage(`Assign Shift`);
                return 1;
            }
            else{
                this.createShiftAssign();
            }
        },
        async createShiftAssign(){
            let formData = new FormData();
            formData.append('staff_time_shifts',JSON.stringify(this.selectedAssignList));
            let response = await postApiData({url:`/api/hr/staff_time_shifts`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/shift_assignment`);
            }else {
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
        timeshiftCustomLabel(timeshift){
            return `${timeshift.shift.name} (${timeshift.from_time} - ${timeshift.to_time})`;
        },

    },

    watch: {
    },

    async created() {

    },

    mounted() {
        this.getDepartmentList();
        this.getTimeShiftList();
        initTE({ Modal, Select, Tab, Ripple });
        this.fpInstance = flatpickr(this.$refs.picker,{
          mode: "multiple",
          dateFormat: "Y-m-d",
          onChange: (dates,dateStr) => {

            console.log("Console shows:", dates)  // works for you
            console.log("Vue should update now", dateStr)

            // 🔥 THIS is the update Vue listens to
            // this.selectedDate = dates.map(d => d.toISOString().slice(0,10))
            this.selectedDate = dateStr.split(", ").map(d => d.trim()); // just testing
            // this.selectedDate = dates;
            this.selectedDates = dateStr.split(", ");
          }
        })

        this.offDaySetting = this.getOffDaySetting();
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
