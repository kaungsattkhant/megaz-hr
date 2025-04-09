<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add Leave Allowance
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">

            <div class="mb-4 col-span-3 pb-6 rounded-md">
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
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Role
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" 
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-6"></div>
            <div class="mb-8 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Day
                </label>
                <input type="number" v-model="selectedDay" class="input-ui ">
            </div>
            <div class="mb-8 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Leave Type
                </label>
                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Leave Type"
                        data-te-select-filter="true" name="" id="" v-model="selectedLeaveType" class="input-ui !text-black text-sm">
                        <option :value="leave" v-for="(leave, index) in leaveTypeList"
                            :key="index"> {{ leave.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-8 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button  class="px-2 pt-2"  data-te-toggle="modal" data-te-target="#add_leave_type"><i class="fal fa-plus"></i></button>
            </div>
            

            <div class="col-span-12">
                <button class="add-btn py-[9px]" @click="btnClickedAddDuty()">
                    Add Leave
                </button>
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
            <button class="add-btn" @click="btnClickedCreateDuty()">
                Create Duty
            </button>
        </div>




        <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="add_leave_type" tabindex="-1" aria-labelledby="add_duty_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_duty_modalLabel">
                            Create Leave Type
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_first_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-8 col-span-3 rounded-md">
                            <label for="" class="label-form mb-3">
                                Type
                            </label>
                            <input type="text" v-model="modalLeaveType" class="input-ui ">
                        </div>

                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" @click="btnClickedAddLeaveType()"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                    </div>
                </div>
            </div>
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
            departmentList:[],
            roleList:[],
            leaveTypeList:[],

            LeaveAllowanceList:[],

            selectedDepartment:null,
            selectedRole:null,
            selectedDay:null,
            selectedLeaveType:null,

            modalLeaveType: null,

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
        changeDepartment(){
            this.roleList = this.selectedDepartment.roles;
        },

        
        btnClickedAddLeaveType(){
            if(!this.modalLeaveType){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else{
                this.addLeaveType();
            }
        },
        async addLeaveType(){
            let formData = new FormData();
            formData.append('modal_leave_type',this.selectedDate);
            formData.append('due_date',this.selectedDueDate);
            formData.append('assign_duty',JSON.stringify(this.dutyList));
            let response = await postApiData({url:`/api/assign_duties`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/okr_duty`);
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
                objective_key_name: this.selectedOkr.name,
                objective_key_id: this.selectedOkr.id, // objective_id = objective_key_id ????
            })
            this.selectedStaff = null
            this.selectedDepartment = null
            this.selectedOkr = null
        },
        


        removeDuty(index){
            this.dutyList.splice(index, 1);
        },

        btnClickedCreateDuty(){
            if(!this.selectedDate){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            else if(!this.selectedDueDate){
                this.alertValidationMessage(`Due Date`);
                return 1;
            }
            else if(this.dutyList.length < 1){
                this.alertValidationMessage(`Duty`);
                return 1;
            }
            else{
                this.createDuty();
            }
        },
        async createDuty(){
            let formData = new FormData();
            formData.append('assign_date',this.selectedDate);
            formData.append('due_date',this.selectedDueDate);
            formData.append('assign_duty',JSON.stringify(this.dutyList));
            let response = await postApiData({url:`/api/assign_duties`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/okr_duty`);
            }
        },





        clearModal(){

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
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
