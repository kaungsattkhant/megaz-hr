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
            <button class="add-btn" @click="btnClickedCreateDuty()">
                Create Duty
            </button>
        </div>




        <!-- <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="add_duty_modal" tabindex="-1" aria-labelledby="add_duty_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_duty_modalLabel">
                            Create Task
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
                        <div class="flex text-sm mb-4">
                            <p class=" w-36">Staff Name </p>
                            <p v-if="selectedStaff">: {{ selectedStaff.name }}</p>
                        </div>
                        <div class="flex text-sm mb-4">
                                <p class=" w-36">Cooking Place </p>
                                <p v-if="selectedOkr">: {{ selectedOkr.name }}</p>
                        </div>
                        <div class="grid grid-cols-10 mb-4">
                            <div class="col-span-8">
                                    <label class="label-form mb-3">Task</label>
                                    <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                        v-model="selectedSampleTask">
                                        <option class="text-sm" :value="task" v-for="(task,index) in taskList" :key="index">
                                            {{ task.name }}
                                        </option>

                                    </select>
                            </div>
                            <div class="col-span-2 text-right">
                                <label class="label-form mb-3">&nbsp;</label>
                                <button class="add-btn py-[9px] " @click="addSampleTask()">
                                        Add
                                </button>
                            </div>
                        </div>
                        <div class="pl-2 pr-6 pt-4 text-sm pb-6"  v-if="sampleTaskList.length > 0">
                            <div v-for="(task,index) in sampleTaskList" class="flex justify-between mb-3">
                                <p>
                                    {{ task.name }}
                                </p>
                                <button @click="removeSampleTask(index)">
                                    <i class="fal fa-trash"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" @click="btnClickedAddSampleTaskToList()"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                    </div>
                </div>
            </div>
        </div> -->


        <!-- add task modal in list -->
        <!-- <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="add_task_to_staff" tabindex="-1" aria-labelledby="add_task_to_staffLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_task_to_staffLabel">
                            Create Task
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_second_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                    <div v-if="staffAndTaskList.length > 0" class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div v-if="staffAndTaskList[selectedIndex]" class="flex text-sm mb-4">
                            <p class=" w-36">Staff Name </p>
                            <p>: {{ staffAndTaskList[selectedIndex].staff_name }}</p>
                        </div>
                        <div v-if="staffAndTaskList[selectedIndex]" class="flex text-sm mb-6">
                                <p class=" w-36">Cooking Place </p>
                            <p>: {{ staffAndTaskList[selectedIndex].cooking_place }}</p>
                        </div>
                        <div class="grid grid-cols-10 mb-4">
                            <div class="col-span-8">
                                <label class="label-form mb-3">Task</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                        v-model="selectedTask">
                                        <option class="text-sm" :value="task" v-for="(task,index) in taskList" :key="index">
                                            {{ task.name }}
                                        </option>

                                </select>
                            </div>
                            <div class="col-span-2 text-right">
                                    <label class="label-form mb-3">&nbsp;</label>
                                    <button class="add-btn py-[9px] " @click="addTaskToStaff()">
                                        Add
                                    </button>
                            </div>
                        </div>
                        <div class="pl-2 pr-6 pt-4 text-sm pb-6"  v-if="staffAndTaskList[selectedIndex]">
                            <div v-for="(task,index) in staffAndTaskList[selectedIndex].tasks" :key="index" class="flex justify-between mb-3">
                                <p>
                                    {{ task.name }}
                                </p>
                                <button @click="removeTask(selectedIndex,index)">
                                    <i class="fal fa-trash"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" data-te-modal-dismiss
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                    </div>
                </div>
            </div>
        </div> -->




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
            staffList:[],
            okrList:[],
            dutyList:[],

            selectedDate:null,
            selectedDepartment:null,
            selectedStaff:null,
            selectedOkr:null,

            selectedIndex:null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        formatDate(dateTime) {
            const date = new Date(dateTime);
            const options = { month: 'short', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
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
        // async getStaffList(){
        //     let response = await getApiData({ url: '/api/departments/' + this.selectedDepartment.id + '/staffs', token: this.getToken() });
        //     if (response.data) {
        //         this.staffList = response.data;
        //     }
        // },
        async getStaffList(){
            let response = await getApiData({ url: '/api/staff_by_department_slug/' + this.selectedDepartment.name, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data;
            }
        },
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
        


        removeDuty(index){
            this.dutyList.splice(index, 1);
        },

        btnClickedCreateDuty(){
            if(!this.selectedDate){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            else if(!this.dutyList){
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
            formData.append('assign_duty',JSON.stringify(this.dutyList));
            let response = await postApiData({url:`/api/assign_duties`, form_data:formData, token:this.getToken()})
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
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
