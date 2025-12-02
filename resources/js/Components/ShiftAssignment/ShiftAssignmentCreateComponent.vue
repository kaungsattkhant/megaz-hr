<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Assign Shift
            </p>
        </div>


        <div class="grid !grid-cols-10 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-6 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date
                </label>
                <input type="date" v-model="selectedDate" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div><div class="col-span-7"></div>
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

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Staff"
                        data-te-select-filter="true" name="" id="" v-model="selectedStaff" class="input-ui !text-black text-sm">
                        <option :value="staff" v-for="(staff, index) in staffList"
                            :key="index"> {{ staff.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Shift
                </label>

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
            
            <div class="col-span-4">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="btnClickedAddShift()">
                    Add
                </button>
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
                            <td class="">
                                {{ item.timeshift_name }}
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
                            <td class="" colspan="3">
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

            selectedDate: null,
            selectedDepartment: null,
            selectedRole: null,
            selectedStaff: null,
            selectedTimeShift: null,
            selectedArea: null,
            
            selectedAssignList: [],
        };
    },

    methods: {
        ...mapGetters(['getToken']),
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
            this.selectedStaff = null;
            this.staffList = null;
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
            else if(!this.selectedStaff){
                this.alertValidationMessage(`Staff`);
                return 1;
            }
            else if(!this.selectedTimeShift){
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
            
            this.selectedAssignList.push({
                date_time: this.selectedDate,
                department_name: this.selectedDepartment.name,
                role_name: this.selectedRole.name,
                staff_name: this.selectedStaff.name,
                staff_id: this.selectedStaff.id,
                timeshift_name: this.selectedTimeShift.shift.name,
                timeshift_id: this.selectedTimeShift.id,
                area_name: this.selectedArea?.name,
                area_id: this.selectedArea?.id,
            })
            // this.selectedDate = null;
            this.selectedDepartment = null;
            this.selectedRole = null;
            this.selectedStaff = null;
            this.selectedTimeShift = null;
            this.selectedArea = null;

            this.roleList = [];
            this.staffList = [];
            this.areaList = [];
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
    },

    watch: {
    },

    async created() {

    },

    mounted() {
        this.getDepartmentList();
        this.getTimeShiftList();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
