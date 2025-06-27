<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Custom Tasks
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">

                <button type="button" v-show="feature.includes('custom-task.create')"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Name
                                </th>
                                <th scope="col" class="  ">
                                    Department
                                </th>

                                <th scope="col" class="  ">
                                    Task
                                </th>

                                <th scope="col" class="  ">
                                    Start Date
                                </th>

                                <th scope="col" class="  ">
                                    Due Date
                                </th>

                                <th scope="col" class="  ">
                                    KPI
                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(task, index) in tasksList" :key="index">
                                <tr class="">
                                    <td class=" ">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="task.custom_task_detail">
                                            {{ task.custom_task_detail.staff.name }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ task.role.department.name }}
                                    </td>
                                    <td class="  ">
                                        {{ task.name }}
                                    </td>

                                    <td class="  ">
                                        {{ task.start_date }}
                                    </td>

                                    <td class="  ">
                                        {{ task.due_date }}
                                    </td>

                                    <td class="  ">
                                        {{ task.kpi }}
                                    </td>

                                    <td class="  " v-show="getFeature().includes('custom-task.edit')">
                                        <button type="button" class="pr-3" 
                                            data-te-toggle="modal" data-te-target="#update_task" @click="editTask(task.id)">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <!-- <i class="fas fa-pen" data-te-toggle="modal" data-te-target="#update_task"></i> -->
                                    </td>

                                </tr>
                            </div>

                            <!-- looping end -->
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getCustomTasks(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{ lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getCustomTasks(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <!--Modal title-->
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Create Task
                            </h5>
                            <!--Close button-->
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!--Modal body-->
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Task Name
                                </label>
                                <input type="text" placeholder="Task Name" v-model="name" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Tasks
                                </label>
                                <textarea name="" placeholder="Tasks" v-model="tasks" class="input-ui" id="" cols="30"
                                    rows="4"></textarea>
                            </div>

                            <div class="mb-4">
                                <div>
                                    <label class="label-form mb-3"> Department </label>
                                    <multiselect v-model="selectedDepartment" :options="departmentList"
                                        :close-on-select="true" :clear-on-select="false" :preserve-search="true"
                                        placeholder="Select Department" label="name" track-by="id"
                                        :preselect-first="true" @select="departmentSelectChanged()"></multiselect>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div>
                                    <label class="label-form mb-3"> Staff </label>
                                    <multiselect v-model="selectedStaff" :options="staffList" :close-on-select="true"
                                        :clear-on-select="false" :preserve-search="true" placeholder="Select Department"
                                        label="name" track-by="id" :preselect-first="true"></multiselect>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    KPI
                                </label>
                                <input type="number" placeholder="KPI" v-model="kpi" class="input-ui">
                            </div>


                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Start Date
                                </label>
                                <input type="date" placeholder="Start Date" v-model="start_date" class="input-ui">
                            </div>

                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Due Date
                                </label>
                                <input type="date" placeholder="Start Date" v-model="due_date" class="input-ui">
                            </div>
                        </div>

                        <!--Modal footer-->
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" @click="createTasksBtnClicked"
                                class="add-btn focus:outline-none focus:ring-0 " data-te-toggle="modal"
                                data-te-target="#create_modal">
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- update -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="update_task" tabindex="-1" aria-labelledby="update_taskLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <!--Modal title-->
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="update_taskLabel">
                                Create Task
                            </h5>
                            <!--Close button-->
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!--Modal body-->
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Task Name
                                </label>
                                <input type="text" placeholder="Task Name" v-model="name_edit" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Tasks
                                </label>
                                <textarea name="" placeholder="Tasks" v-model="tasks_edit" class="input-ui" id=""
                                    cols="30" rows="4"></textarea>
                            </div>

                            <div class="mb-4">
                                <div>
                                    <label class="label-form mb-3"> Department </label>
                                    <multiselect v-model="selectedDepartment" :options="departmentList"
                                        :close-on-select="true" :clear-on-select="false" :preserve-search="true"
                                        placeholder="Select Department" label="name" track-by="id"
                                        :preselect-first="true" @select="departmentSelectChanged()"></multiselect>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div>
                                    <label class="label-form mb-3"> Staff </label>
                                    <multiselect v-model="selectedStaffedit" :options="staffList"
                                        :close-on-select="true" :clear-on-select="false" :preserve-search="true"
                                        placeholder="Select Department" label="name" track-by="id"
                                        :preselect-first="true"></multiselect>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    KPI
                                </label>
                                <input type="number" placeholder="KPI" v-model="kpi_edit" class="input-ui">
                            </div>


                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Start Date
                                </label>
                                <input type="date" placeholder="Start Date" v-model="start_date_edit" class="input-ui">
                            </div>

                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Due Date
                                </label>
                                <input type="date" placeholder="Start Date" v-model="due_date_edit" class="input-ui">
                            </div>
                        </div>

                        <!--Modal footer-->
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button data-te-modal-dismiss type="button" @click="updateTasksBtnClicked"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Update
                            </button>
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
import { mapGetters } from "vuex";
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import Multiselect from 'vue-multiselect';
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';


export default {
    components: {
        Multiselect
    },
    data() {
        return {
            tasksList: [],
            areaList: [],
            departmentList: [],
            staffList: [],
            deleteId: null,
            name: null,
            tasks: null,
            kpi: null,
            selectedStaff: null,
            start_date: null,
            due_date: null,
            selectedDepartment: null,
            dayList: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],

            name_edit: null,
            tasks_edit: null,
            kpi_edit: null,
            selectedStaffedit: null,
            start_date_edit: null,
            due_date_edit: null,
            selectedDepartmentedit: null,
            editId: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            feature: this.getFeature(),
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async doubleChecked(id) {
            let url = `/api/tasks/${id}/double_checked`;
            let response = await postApiData({ url: url, token: this.getToken() });

            if (response.success == true) {
                this.getCustomTasks(1);
            } else {
                alert(response.message);
            }

        },

        async getCustomTasks(pageNumber) {
            let url = `/api/custom_tasks?page=${pageNumber}`;

            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.tasksList = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        async getAreaList(departmentId) {
            const response = await getApiData({ url: `/api/areas_by_department/${departmentId}`, token: this.getToken() });
            if (response.data) {
                this.areaList = response.data;
            }
        },

        async getDepartmentList() {
            const response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },

        departmentSelectChanged() {
            this.getStaffList(this.selectedDepartment.id);
        },

        async getStaffList(departmentId) {
            const response = await getApiData({ url: `/api/departments/${departmentId}/staffs`, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data;
            }
        },

        createTasksBtnClicked() {
            this.createTasks();
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },

        async editTask(id) {
            this.editId = id;
            let url = `/api/custom_tasks/${id}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            this.name_edit = response.data.name;
            this.tasks_edit = response.data.description;
            this.selectedDepartment = response.data.staff.department;
            this.departmentSelectChanged();
            this.kpi_edit = response.data.kpi;
            this.start_date_edit = this.formatDate(response.data.start_date);
            this.due_date_edit = this.formatDate(response.data.due_date);
            this.selectedStaffedit = response.data.staff;
        },

        departmentSelectChangedEdit() {

            this.getStaffList(this.selectedDepartmentedit);

        },

        async createTasks() {
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('description', this.tasks);
            formData.append('staff_id', this.selectedStaff.id);
            formData.append('kpi', this.kpi)
            formData.append('start_date', this.start_date);
            formData.append('due_date', this.due_date);
            let response = await postApiData({ url: '/api/custom_tasks', form_data: formData, token: this.getToken() });
            if (response.success) {
                // window.location.replace('/tasks');
                this.getCustomTasks(1);
                // alert('test');
                this.kpi = null;
                this.tasks = null;
                this.start_date = null;
                this.due_date = null;
                this.name = null;
                this.staffList = [];
            }
            else {
                alert('some errors occur');
            }
        },

        updateTasksBtnClicked() {
            this.updateTasks();
        },

        async updateTasks() {
            let formData = new FormData();
            formData.append('name', this.name_edit);
            formData.append('description', this.tasks_edit);
            formData.append('staff_id', this.selectedStaffedit.id);
            formData.append('kpi', this.kpi_edit)
            formData.append('start_date', this.start_date_edit);
            formData.append('due_date', this.due_date_edit);
            let response = await postApiData({ url: `/api/custom_tasks/${this.editId}`, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getCustomTasks(1);
                // alert('test');
                this.kpi = null;
                this.tasks = null;
                this.start_date = null;
                this.due_date = null;
                this.name = null;
                this.staffList = [];
            }
            else {
                alert('some errors occur');
            }
        },



        isActiveToggled(id) {
            let index = this.tasksList.findIndex(task => task.id == id);
            if (index != -1) {
                if (this.tasksList[index].is_active == 1) {
                    this.tasksList[index].is_active = 0;
                }
                else {
                    this.tasksList[index].is_active = 1;
                }

                let url = `/api/is_active`;
                let formData = new FormData();
                formData.append('id', id);
                formData.append('type', 'task');
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
            }
        },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        async confirmDeleteBtnClicked() {
            let url = `/api/tasks/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.getCustomTasks(1);
            }
        }

    },
    mounted() {
        this.getCustomTasks(1);
        // this.getAreaList();
        this.getDepartmentList();

        initTE({ Modal, Select, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
