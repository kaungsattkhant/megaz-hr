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
                        <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                            <td class=" px-6 py-4 font-medium ">
                                1
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 ">
                                Mg Mg
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 ">
                                Depart 1
                            </td>
                            <td class=" px-6 py-4 ">
                                role 2
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button id="edit-btn" class="pr-1">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                            </tr>
                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
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
                                Department
                            </label>
                            <select name="" id="" v-model="selectedDepartment"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="department" v-for="(department, departmentIndex) in departmentList" :key="departmentIndex">
                                    {{ department.name }}
                                 </option>
                                
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Role
                            </label>
                            <select name="" id=""  v-model="selectedRole"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="role" v-for="(role, roleIndex) in roleList" :key="roleIndex">{{ role.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Date Assign
                            </label>
                            <select name="" id=""
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="1">1/12/1999</option>
                                <option value="2">Manager</option>
                                <option value="3">Waiter</option>
                            </select>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>

</template>

<script>
    import { Modal, Ripple, initTE, Input } from "tw-elements";
    import { getApiData, deleteApiData } from '../../utilities/ajax-helpers';

    export default {
        data() {
            return {
                departmentList: [],
                roleList: [],

                name: null,
                selectedDepartment: null,
                selectedRole: null,
                selectedDate: null,
                tasks: null,

            };
        },

        methods: {
            

            async getDepartmentList(){
                const response = await getApiData({ url: '/api/departments?all_minified=true' });
                if(response.data){
                    this.departmentList = response.data;
                }
            },

            async getRoleList(){
                const response = await getApiData({ url: '/api/roles?all_minified=true' });
                if(response.data){
                    this.roleList = response.data;
                }
            },

            createTaskaBtnClicked(){
                // alert(`name = ${this.name}`);
                // alert(`phone num = ${this.phoneNumber}`);
                // alert(`password = ${this.password}`);
                // alert(`nrc = ${this.nrcNumber}`);
                // alert(`department = ${this.selectedDepartment.name}`);
                // alert(`role = ${this.selectedRole.name}`);
                // alert(`gender = ${this.selectedGender.name}`);
                // alert(`address = ${this.address}`);
                this.createStaff();
            },

            createStaff()
            {
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('department_id', this.selectedDepartment.id);
                // formData.append('role', this.role);
                formData.append('description', this.tasks);
                formData.append('assigned_days', this.date);
                // formData.append();
                let response = postApiData({url: '/api/staffs', form_data: formData});
                if(response.data.success){
                    window.location.replace('/staff');
                }
                else{
                    alert('some errors occur');
                }
            },

        },
        mounted()
        {
            this.getDepartmentList();
            this.getRoleList();

            initTE({ Modal, Ripple });
        }
    }
</script>
