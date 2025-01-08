<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add Okr Duty
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Date
                </label>
                <input type="date" v-model="selectedDate" class="input-ui ">
            </div>
            <div class="col-span-9"></div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Department
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="changeDepartment(department)"
                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black text-sm">
                        <option :value="department" v-for="(department, index) in departmentList"
                            :key="index"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Staff
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select
                        name="" id="" v-model="selectedStaff" class="input-ui !text-black" @change="changeStaff()"
                        data-te-select-init data-te-select-placeholder="Select Staff" data-te-select-filter="true">
                        <option :value="staff" v-for="(staff, index) in staffList"
                            :key="index"> {{ staff.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Key Result
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Objective"
                        data-te-select-filter="true" name="" id="" v-model="selectedOkr" class="input-ui">
                        <option v-if="okrList.length < 1" selected disabled> Not Found! </option>
                        <option :value="okr" v-for="(okr, index) in okrList"
                            :key="index"> {{ okr.objective.objective_name }} </option>
                    </select>
                </div>
            </div>

            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="btnClickedAddDuty()">
                    Add
                </button>
                <!-- <button class="add-btn py-[9px]" :disabled="!selectedOkr && !selectedDate && !selectedstaff && !selectedDate" @click="addDutyStaff()">
                    Add
                </button> -->
                <!-- <button class="!bg-[#df845a] add-btn py-[9px] ml-4" :disabled="!selectedstaff && !selectedOkr && !selectedDate"
                    data-te-toggle="modal" data-te-target="#add_duty_modal">
                    Add Task
                </button> -->
            </div>


        </div>
        <div class=" bg-white py-8 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="">
                                Department
                            </th>
                            <th scope="col" class="">
                                Staff Name
                            </th>
                            <th scope="col" class="">
                                Key Result
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(duty, dutyIndex) in dutyList"
                            :key="dutyIndex">
                            <td class="">
                                {{ duty.department_name }}
                            </td>
                            <td class="">
                                {{ duty.staff_name }}
                            </td>
                            <td class="">
                                {{ duty.objective_key_name }}
                            </td>
                            <td class="">
                                <button @click="removeDuty(dutyIndex)">
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <button class="add-btn" @click="btnClickedEditDuty()">
                Create Duty
            </button>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Tab, Select } from "tw-elements";
import { getApiData, postApiData ,deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { each } from "lodash";

export default {
    components: {
        Multiselect
    },
    props: ["okrDutyId"],
    data() {
        return {
            departmentList:[],
            staffList:[],
            okrList:[],
            dutyList:[],

            selectedDate:null,
            selectedDepartment:null,
            selectedStaff:null,
            selectedOkr:null,

            selectedIndex:null,

            detail:null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        formatDate(dateTime) {
            const date = new Date(dateTime);
            const options = { month: 'short', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        },
        async getOkrDutyDetail() {
            let response = await getApiData({ url: `/api/assign_duties/${this.okrDutyId}`, token: this.getToken() });
            if (response.data) {
                this.detail = response.data;
                this.addDetail(response.data);
            }
        },
        addDetail(detail){
            this.selectedDate = detail.assign_date;
            detail.objectivekey_staff.forEach(duty => {
                this.dutyList.push({
                    id:duty.id,
                    department_name:duty.department_name,
                    staff_name: duty.staff_name,
                    staff_id: duty.staff_id,
                    objective_key_name: duty.objective_key.objective.objective_name,
                    objective_key_id: duty.objective_key_id,
                }) 
            });
        },


        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        changeDepartment(){
            this.getStaffList();
            this.selectedstaff = null;
        },

        // need to change ,wrong  api
        async getStaffList(){
            let response = await getApiData({ url: '/api/departments/' + this.selectedDepartment.id + '/staffs', token: this.getToken() });
            if (response.data) {
                this.staffList = response.data;
            }
        },
        // async getStaffList(){
        //     let response = await getApiData({ url: '/api/staff_by_department_slug/' + this.selectedDepartment.id, token: this.getToken() });
        //     if (response.data) {
        //         this.staffList = response.data;
        //     }
        // },
        changeStaff(){
            this.getOkrList();
        },
        async getOkrList(){
            let response = await getApiData({ url: '/api/objectives_keys_by_staff/' + this.selectedStaff.id, token: this.getToken() });
            if (response.data) {
                this.okrList = response.data;
            }
        },
        
        
        btnClickedAddDuty(){
            if(!this.selectedDepartment){
                this.alertValidationMessage(`Department`);
                return 1;
            }
            else if(!this.selectedStaff){
                this.alertValidationMessage(`Staff`);
                return 1;
            }
            else if(!this.selectedOkr){
                this.alertValidationMessage(`Okr`);
                return 1;
            }
            else{
                this.assignDuty();
            }
        },
        async assignDuty(){
            this.dutyList.push({
                department_name:this.selectedDepartment.name,
                staff_name: this.selectedStaff.name,
                staff_id: this.selectedStaff.id,
                objective_key_name: this.selectedOkr.objective.objective_name,
                objective_key_id: this.selectedOkr.objective_id, // objective_id = objective_key_id ????
            })
            this.selectedStaff = null
            this.selectedDepartment = null
            this.selectedOkr = null
        },
        


        async removeDuty(index){
            if(this.dutyList[index].id){
                let id = this.dutyList[index].id;
                let response = await deleteApiData({ url: `/api/assign_duties/objective_key_staff/` + id, token: this.getToken() });
                if (response.success) {
                    this.dutyList.splice(index, 1);
                }
            }
            else{
                this.dutyList.splice(index, 1);
            }
            
        },

        btnClickedEditDuty(){
            if(!this.selectedDate){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            else if(!this.dutyList){
                this.alertValidationMessage(`Duty`);
                return 1;
            }
            else{
                this.editDuty();
            }
        },
        async editDuty(){
            let formData = new FormData();
            formData.append('assign_date',this.selectedDate);
            formData.append('assign_duty',JSON.stringify(this.dutyList));
            let response = await postApiData({url:`/api/assign_duties/${this.okrDutyId}`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/okr_duty`);
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
        this.getOkrDutyDetail();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
