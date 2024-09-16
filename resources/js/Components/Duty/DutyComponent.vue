<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Duties
        </p>
    </div>
    <notifications position="top center" />

    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <!-- <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label> -->
                <input type="date" class="input-ui  mr-2 h-8" v-model="selectedDate" @change="dateChange()">
            </div>
            <div class="flex justify-end flex-col">
                <a href="/duty/create"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 ">
                    Add New
                </a>

                <!-- hidden btn -->
                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 hidden"
                    data-te-toggle="modal" data-te-target="#create_modal"></button>
            </div>
        </div>

        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    Id
                                </th>
                                <th scope="col" class="">
                                    From
                                </th>
                                <th scope="col" class="">
                                    Staff Name
                                </th>
                                <th scope="col" class="">
                                    Cooking Place
                                </th>
                                <th scope="col" class="">
                                    Tasks
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(listItem, dutyIndex) in dutyList" :key="dutyIndex">
                                <tr class="">
                                    <td class="">
                                        {{ dutyIndex+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ listItem.date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ listItem.staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ listItem.cooking_place.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-for="(taskItem,listTaskIndex) in listItem.tasks" :key="listTaskIndex" class="group">
                                            {{ taskItem.name }} 
                                            <span class="group-last:hidden">, </span>
                                        </span>
                                        <!-- <ul>
                                            <li v-for="(task) in duty.tasks">
                                                {{ task.name }}
                                            </li>
                                        </ul> -->
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- <a :href="'/duty/' + duty.id + '/edit'">
                                            <i class="far fa-pen cursor-pointer mr-3"></i>
                                        </a> -->
                                        
                                        <button @click="btnClickedEditModal(listItem)" class="mr-3"
                                            data-te-toggle="modal" data-te-target="#create_payment_modal" 
                                            >
                                            <i class="fal fa-pen" ></i>
                                        </button>
                                        <button @click="deleteBtnClicked(listItem.id)" data-te-toggle="modal"
                                            data-te-target="#deleteModal" id="edit-btn" class="pl-1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        
                                    </td>
                                </tr>

                            </div>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- edit Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="create_payment_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                        <div>
                            <div class="relative flex justify-between py-2 px-6 border-b">
                                <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                    id="create_modalLabel">
                                    Edit Duty
                                </h5>
                                <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                    data-te-modal-dismiss aria-label="Close" id="close_payment">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div  class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                                <div class="mb-6 rounded-md">
                                    <label for="" class="label-form mb-3">
                                        Date
                                    </label>
                                    <input type="date" v-model="selectedDutyDate" class="input-ui ">
                                </div>
                                <div class="mb-6 rounded-md">
                                    <label for="" class="label-form mb-3">
                                        Department
                                    </label>
                    
                                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black select-custom"
                                        data-te-select-wrapper-ref>
                                        <select data-te-select-init data-te-select-placeholder="Select Department" @change="changeDepartment(department)"
                                            data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black text-sm">
                                            <option :value="department.id" v-for="(department, index) in departmentList"
                                                :key="index"> {{ department.name }} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-6 rounded-md">
                                    <label for="" class="label-form mb-3">
                                        Staff
                                    </label>
                                    <div class="bg-white mb-0 w-full select-custom inline-block h-[34px] dark:bg-white !text-black"
                                        data-te-select-wrapper-ref>
                                        <select
                                            name="" id="" v-model="selectedStaff" class="input-ui !text-black" @change="changeStaff()"
                                            data-te-select-init data-te-select-placeholder="Select Staff" data-te-select-filter="true">
                                            <option :value="staff.id" v-for="(staff, index) in staffList"
                                                :key="index"> {{ staff.name }} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-6 rounded-md">
                                    <label for="" class="label-form mb-3">
                                        Cooking Place
                                    </label>
                                    <div class="bg-white mb-0 w-full select-custom inline-block h-[34px] dark:bg-white !text-black"
                                        data-te-select-wrapper-ref>
                                        <select data-te-select-init data-te-select-placeholder="Select Cooking Place"
                                            data-te-select-filter="true" name="" id="" v-model="selectedCookingPlace" class="input-ui">
                                            <option :value="cookingPlace.id" v-for="(cookingPlace, index) in cookingPlaceList"
                                                :key="index"> {{ cookingPlace.name }} </option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div  class="flex text-sm mb-4" v-if="selectedStaff">
                                    <p class=" w-36">Staff Name </p>
                                    <p v-if="selectedStaff.name">: {{ selectedStaff.name }}</p>
                                </div>
                                <div  class="flex text-sm mb-6" v-if="selectedCookingPlace">
                                    <p class=" w-36">Cooking Place </p>
                                    <p v-if="selectedCookingPlace.name">: {{ selectedCookingPlace.name }}</p>
                                </div> -->
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
                                            <button class="add-btn py-[9px] " @click="taskAddToSampleList()">
                                                Add
                                            </button>
                                    </div>
                                </div>
                                <div class="pl-2 pr-6 pt-4 text-sm pb-6" >
                                    <div v-for="(task,index) in sampleTaskList" :key="index" class="flex justify-between mb-3">
                                        <p>
                                            {{ task.name }}
                                        </p>
                                        <button @click="removeTask(index)">
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
                                <button type="button" @click="btnClickedEditDuty()"
                                    class="add-btn focus:outline-none focus:ring-0 ">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Delete Modal -->
            <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
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
                            <button @click="confirmDeleteBtnClicked" type="button" data-te-toggle="modal"
                                data-te-target="#deleteModal"
                                class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</template>

<script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import Multiselect from 'vue-multiselect';
    import { getCurrentDate } from '../../utilities/datetime-helpers';

    export default {
        data() {
        return {
            dutyList: [],
            selectedDate:getCurrentDate(),
            dutyDetailTasks:[],
            selectedDepartment:null,
            selectedStaff:null,
            selectedCookingPlace:null,
            tasks:[],
            taskList:[],
            editTasksList:[],
            departmentList:[],
            staffList:[],
            cookingPlaceList:[],
            sampleTaskList:[],
            taskIds:[],
            selectedEditDutyId:null,
            selectedDutyDate:null,
            selectedDutyDetail:null,
            deletedId:null,
        };
    },


        methods: {
            ...mapGetters(['getToken']),

            async getDutyList() {
                const response = await getApiData({ url: '/api/duties?date=' + this.selectedDate, token: this.getToken() });
                if (response.data) {
                    this.dutyList = response.data.data;
                }
            },
            dateChange(){
                this.getDutyList();
            },
            async getTaskList(id){
                let response = await getApiData({ url: '/api/task_by_role/' + id, token: this.getToken() });
                if (response.data) {
                    this.taskList = response.data;
                }
            },
            

            async getInitDutyList(selectedDate) {
                const response = await getApiData({ url: '/api/duties?date=' + selectedDate, token: this.getToken() });
                if (response.data) {
                    this.dutyList = response.data.data;
                }
            },

            async getDepartmentList(){
                let response = await getApiData({ url: '/api/departments', token: this.getToken() });
                if (response.data) {
                    this.departmentList = response.data;
                }
            },
            changeDepartment(){
                this.getStaffList();
                this.selectedStaff = null;
                this.taskList = []
            },
            async getStaffList(){
                let response = await getApiData({ url: '/api/departments/' + this.selectedDepartment + '/staffs', token: this.getToken() });
                if (response.data) {
                    this.staffList = response.data;
                }
            },
            changeStaff(){
                this.getTaskList(this.selectedStaff);
            },
            async getCookingPlaceList(){
                let response = await getApiData({ url: '/api/cooking_places', token: this.getToken() });
                if (response.data) {
                    this.cookingPlaceList = response.data.data;
                }
            },


            btnClickedEditModal(duty){
                this.selectedDutyDetail = duty;
                // this.selectedStaff = this.selectedDutyDetail.staff;
                // this.selectedCookingPlace = this.selectedDutyDetail.cooking_place
                this.getDataForEditModal()
                
            },
            getDataForEditModal(){
                this.selectedDutyDetail.tasks.forEach(item =>{
                    this.sampleTaskList.push({
                        name: item.name,
                        id: item.id,
                    })
                })
                // this.sampleTaskList = this.selectedDutyDetail.tasks
                this.getTaskList(this.selectedDutyDetail.staff_id);
                this.selectedDepartment = this.selectedDutyDetail.staff.department_id
                this.getStaffList();
                this.selectedStaff = this.selectedDutyDetail.staff.id
                this.selectedCookingPlace = this.selectedDutyDetail.cooking_place.id
                this.selectedEditDutyId = this.selectedDutyDetail.id
                this.selectedDutyDate = this.selectedDutyDetail.date.slice(0,10)
            },
            taskAddToSampleList(){
                this.sampleTaskList.push({
                        name: this.selectedTask.name,
                        id: this.selectedTask.id,
                    })
            },
            removeTask(index){
                this.sampleTaskList.splice(index, 1);
            },
            btnClickedEditDuty(){
                this.sampleTaskList.forEach(duty => {
                    this.taskIds.push(duty.id)
                    
                });
                console.log(this.taskIds)
                this.updateDuty();
            },
            async updateDuty(){
                let formData = new FormData();
                formData.append('date',this.selectedDutyDate);
                formData.append('staff_id',this.selectedStaff);
                formData.append('cooking_place_id',this.selectedCookingPlace);
                formData.append('taskIds',JSON.stringify(this.taskIds));
                let response = await postApiData({url:`/api/duties/`+ this.selectedEditDutyId, form_data:formData, token:this.getToken()})
                if(response.success){
                    console.log('successed')
                    window.location.reload();
                }
            },
            deleteBtnClicked(id){
                this.deleteId = id;
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/duties/${this.deleteId}`;
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    alert(`deleted`);
                    this.getInitDutyList(this.selectedDate);
                }
            }

        },
        mounted()
        {
            initTE({ Modal,Select, Ripple });
        },
        created(){
            this.getInitDutyList(this.selectedDate);
            this.getDepartmentList();
            this.getCookingPlaceList();
        }

    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
