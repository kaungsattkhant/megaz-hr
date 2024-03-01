<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
        </div>
        <div class="flex justify-end flex-col">

            <button type="button" class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                data-te-toggle="modal" data-te-target="#create_modal">
                Add New
            </button>
        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Department
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- looping start -->
                        <div class="contents" v-for="(task, index) in tasksList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ task.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ task.role.department.name }}
                                </td>
                                <td class=" px-6 py-4 ">
                                    {{ task.role.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button id="edit-btn" class="pr-1"
                                    @click="deleteBtnClicked(task.id)"
                                    data-te-toggle="modal" data-te-target="#deleteModal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>

                            <!-- looping end -->
                    </tbody>
                </table>
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

                    <div class="relative  p-4">
                        <!--Modal title-->
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                            Create Task
                        </h5>
                        <!--Close button-->
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!--Modal body-->
                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Task Name
                            </label>
                            <input type="text" placeholder="Task Name" v-model="name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Tasks
                            </label>
                            <textarea name="" placeholder="Tasks" v-model="tasks"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id=""
                                cols="30" rows="4"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Area
                            </label>
                            <select name="" id="" v-model="selectedArea"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="area" v-for="(area, areaIndex) in areaList" :key="areaIndex">
                                    {{ area.name }}
                                 </option>

                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Role
                            </label>
                            <select data-te-select-init data-te-select-placeholder="Select Roles"
                                data-te-select-filter="true" name="" id="" v-model="selectedRole"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="role" v-for="(role, roleIndex) in roleList" :key="roleIndex"> {{ role.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Assigned Days
                            </label>
                            <select data-te-select-init data-te-select-placeholder="Select Days"
                                data-te-select-filter="true" name="" id="" multiple v-model="selectedDays"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="day" v-for="(day, dayIndex) in dayList" :key="dayIndex"> {{ day }} </option>
                            </select>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" @click="createTasksBtnClicked"
                        class="add-btn focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!--Delete Modal -->
        <div
        data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div
            data-te-modal-dialog-ref
            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5
                    class="text-xl font-medium leading-normal text-neutral-800 "
                    id="exampleModalLabel">
                    Delete ?
                    </h5>
                    <!--Close button-->
                    <button
                    type="button"
                    class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss
                    aria-label="Close">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
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
                    <button
                    type="button"
                    class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                    data-te-modal-dismiss
                    >
                        Close
                    </button>
                    <button @click="confirmDeleteBtnClicked"
                    type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                    class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 "
                    >
                        Delete
                    </button>
                </div>
                </div>
            </div>
        </div>


    </div>

</template>

<script>
    import { mapGetters } from "vuex";
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';


    export default {
        data() {
            return {
                tasksList: [],
                areaList: [],
                roleList: [],
                deleteId: null,
                name: null,
                tasks: null,
                selectedArea: null,
                selectedRole: null,
                dayList: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                selectedDays: [],


                per_page: 10,
                pageNumbers: [],
                currentPage: 1,
                paginationGroupsCount: 1,
                per_group: 10,
                groupedPageNumbers: [],
                currentGroup: 0,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getTasksList(pageNumber){
                let url = `/api/tasks?per_page=${this.per_page}`;
                if(pageNumber){
                    url = `${url}&page=${pageNumber}`
                }
                let response = await getApiData({ url: url, token: this.getToken() });
                if(response.data){
                    this.tasksList = response.data.tasks;
                }
            },

            async getAreaList(){
                const response = await getApiData({ url: '/api/areas' , token: this.getToken()});
                if(response.data){
                    this.areaList = response.data;
                }
            },

            async getRoleList(){
                const response = await getApiData({ url: '/api/roles', token: this.getToken() });
                if(response.data){
                    this.roleList = response.data;
                }
            },

            createTasksBtnClicked(){
                this.createTasks();
            },

            async createTasks()
            {
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('description', this.tasks);
                formData.append('area_id', this.selectedArea.id);
                formData.append('role_id', this.selectedRole.id);
                formData.append('assigned_days', this.selectedDays);
                let response = await postApiData({url: '/api/tasks', form_data: formData, token: this.getToken()});
                if(response.success){
                    // window.location.replace('/tasks');
                    this.getTasksList(null);
                    // alert('test');
                }
                else{
                    alert('some errors occur');
                }
            },

            deleteBtnClicked(id){
                this.deleteId = id;
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/tasks/${this.deleteId}`;
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    this.getTasksList(null);
                    console.log(`deleted`);
                }
            }

        },
        mounted()
        {
            this.getTasksList(null);
            this.getAreaList();
            this.getRoleList();

            initTE({ Modal,Select, Ripple });
        }
    }
</script>
