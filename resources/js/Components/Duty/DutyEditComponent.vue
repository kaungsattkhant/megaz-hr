<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add Duty
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Date
                </label>
                <input type="date" v-model="selectedDate" class="input-ui ">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Department
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="changeDepartment(department)"
                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black text-sm">
                        <option :value="department" v-for="(department, index) in departmentList"
                            :key="index"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-6"></div>
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
                    Cooking Place
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Cooking Place"
                        data-te-select-filter="true" name="" id="" v-model="selectedCookingPlace" class="input-ui">
                        <option :value="cookingPlace" v-for="(cookingPlace, index) in cookingPlaceList"
                            :key="index"> {{ cookingPlace.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="addDutyStaff()">
                    Add
                </button>
                <button class="!bg-[#df845a] add-btn py-[9px] ml-4" :disabled="!selectedstaff && !selectedCookingPlace"
                    data-te-toggle="modal" data-te-target="#add_duty_modal">
                    Add Task
                </button>
            </div>
        </div>
        <div class="bg-white py-8 px-8 rounded-md shadow-md mb-8">
            <div class="table-container w-3/4">
                <table class="primary-table !border-none">
                    <thead class=" !border-none">
                        <tr>
                            <th scope="col" class=" text-left !border-none">
                                Staff
                            </th>
                            <th scope="col" class=" text-left !border-none">
                                Cooking Place
                            </th>

                            <th scope="col" class=" text-left !border-none">
                                Date
                            </th>

                            <th scope="col" class=" !border-none">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div v-for="(task, parentIndex) in staffAndTaskList" class="contents">
                            <tr class=" !border-none"
                                :key="parentIndex">
                                <td class=" !border-none text-left ">
                                    {{ task.staff_name }}
                                </td>

                                <td class=" !border-none text-left">
                                    {{ task.cooking_place }}
                                </td>
                                <td class=" !border-none text-left">
                                    {{ formatDate(task.date) }}
                                </td>
                                <td class=" !border-none">
                                    <button @click="btnAddTaskToStaff(parentIndex)"
                                        data-te-toggle="modal" data-te-target="#add_task_to_staff">
                                        <i class="fal fa-plus  pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="!border-none">
                                <td colspan="4" class="!font-semibold text-left !border-none">
                                    Tasks
                                </td>
                            </tr>
                            <div class="contents ">
                                <tr v-for="(taskItem,childIndex) in task.tasks" :key="childIndex" class="!border-none group">
                                    <td colspan="3" class="text-left !border-none group-last:!pb-12">
                                        {{ taskItem.name }}
                                    </td>
                                    <td class="!border-none">
                                        <button @click="removeTask(childIndex)">
                                            <i class="fal fa-trash  pr-3"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                        </div>
                        
                    </tbody>
                </table>
            </div>

        </div>


        <div>
            <button class="add-btn" @click="btnClickedAddDuty()">
                Create Duty
            </button>
        </div>
        



        <div data-te-modal-init
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
                                <p v-if="selectedCookingPlace">: {{ selectedCookingPlace.name }}</p>
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
        </div>


        <!-- add task modal in list -->
        <div data-te-modal-init
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
                            <button type="button" @click="btnClickedCreateJournal()"
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
            staffList:[],
            cookingPlaceList:[],
            taskList:[],

            selectedDate:null,
            selectedDepartment:null,
            selectedStaff:null,
            selectedCookingPlace:null,

            staffAndTaskList:[],
            taskIds:[],

            selectedIndex:null,
            sampleTaskList:[],
            selectedSampleTask:null,
            duty_array:[],
            // testTaskList:[
            //                 {
            //                     staff_name: 'kaung Khant min Tun',
            //                     cooking_place: 'Chinese Cooking Place',
            //                     date: 'Aug 1',
            //                     tasks:[
            //                         {
            //                             name:'tasks 1'
            //                         },
            //                         {
            //                             name:'tasks 2'
            //                         }
            //                     ]
            //                 },
            //                 {
            //                     staff_name: 'kaung Khant min Tun',
            //                     cooking_place: 'Chinese Cooking Place',
            //                     date: 'Aug 1',
            //                     tasks:[
            //                         {
            //                             name:'tasks 1'
            //                         },
            //                         {
            //                             name:'tasks 2'
            //                         }
            //                     ]
            //                 }
            //             ],



            
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
        async getStaffList(){
            let response = await getApiData({ url: '/api/departments/' + this.selectedDepartment.id + '/staffs', token: this.getToken() });
            if (response.data) {
                this.staffList = response.data;
            }
        },
        changeStaff(){
            this.getTaskList();
        },
        async getCookingPlaceList(){
            let response = await getApiData({ url: '/api/cooking_places', token: this.getToken() });
            if (response.data) {
                this.cookingPlaceList = response.data.data;
            }
        },
        async getTaskList(){
            let response = await getApiData({ url: '/api/task_by_role/' + this.selectedStaff.id, token: this.getToken() });
            if (response.data) {
                this.taskList = response.data;
            }
        },
        addDutyStaff(){
            this.staffAndTaskList.push({
                staff_name: this.selectedStaff.name,
                staff_id: this.selectedStaff.id,
                cooking_place: this.selectedCookingPlace.name,
                cooking_place_id: this.selectedCookingPlace.id,
                date: this.selectedDate,
                tasks:[],
            })
            this.selectedStaff = null
            this.selectedDate = null
            this.selectedDepartment = null
            this.selectedCookingPlace = null
        },
        btnAddTaskToStaff(index){
            this.selectedIndex = index;
        },
        addTaskToStaff(){
            this.staffAndTaskList[this.selectedIndex].tasks.push({
                name: this.selectedTask.name,
                task_id : this.selectedTask.id
            });
            this.selectedTask = null;
            // document.getElementById("close_second_modal").click();

        },


        addSampleTask(){
            this.sampleTaskList.push({
                name: this.selectedSampleTask.name,
                task_id: this.selectedSampleTask.id
            })
            this.selectedSampleTask = null;
            
        },
        btnClickedAddSampleTaskToList(){
            this.staffAndTaskList.push({
                staff_name: this.selectedStaff.name,
                staff_id: this.selectedstaff.id,
                cooking_place: this.selectedCookingPlace.name,
                cooking_place_id: this.selectedCookingPlace.id,
                date: this.selectedDate,
                tasks:this.sampleTaskList,
            })
            document.getElementById("close_first_modal").click();
        },
        removeTask(i,j){
            this.staffAndTaskList[i].tasks.splice(j, 1);
        },
        removeSampleTask(index){
            this.sampleTaskList.splice(index, 1);
        },


        btnClickedAddDuty(){
            
            this.staffAndTaskList.forEach(duty => {
                let taskIds = [];
                duty.tasks.forEach(duty_task =>{
                    taskIds.push(duty_task.task_id)
                })
                this.duty_array.push({
                    date: duty.date,
                    cooking_place_id: duty.cooking_place_id,
                    staff_id: duty.staff_id,
                    taskIds: taskIds
                })
            });
            this.addDuty();
        },
        async addDuty(){
            let formData = new FormData();
            formData.append('duty_array',this.duty_array);
            let response = await postApiData({url:`/api/duties`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
            }
        }





       

    },

    watch: {
    },

    async created() {
        
    },

    mounted() {
        this.getDepartmentList();
        this.getCookingPlaceList();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
