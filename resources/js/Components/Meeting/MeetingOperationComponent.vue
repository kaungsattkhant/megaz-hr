<template>
    <div class="px-0">
        <div class="mb-4">
            <p class="text-lg font-semibold font-inter">
                Meeting : Operation Meeting
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white p-8 rounded-md">
            <div class="mb-4 col-span-12">
                <p class="text-lg font-normal font-inter">
                    Meeting : Operation Meeting
                </p>
            </div>
            
            <div class="col-span-3 rounded-md mb-8">
                <label for="" class="label-form mb-3">
                    Department
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" data-te-select-filter="true"
                        name="" id=""  class="input-ui"
                        @change="departmentSelectChanged">
                        <option :value="department" v-for="(department, departmentIndex) in departmentList"
                        :key="departmentIndex"> {{ department.name }} </option>
                    </select>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-8">
                <label class="label-form mb-3">Roles</label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" data-te-select-filter="true"
                        name="" id=""  @change="roleChange()" class="input-ui">
                        <option :value="role" v-for="(role, roleIndex) in roleList"
                            :key="roleIndex"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-3 rounded-md mb-8">
                <label class="label-form mb-3">Staff</label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Staff" data-te-select-filter="true"
                        name="" id=""  class="input-ui">
                        <option :value="staff" v-for="(staff, staffIndex) in staffList"
                            :key="staffIndex"> {{ staff.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-3">
                <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10">
                    Add
                </button>
            </div>

            <div class=" col-span-12 mb-10">
                <table class="min-w-[50%] text-sm font-light ml-2">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Department
                            </th>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Role
                            </th>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Staff
                            </th>
                            <th scope="col" class=" px-6 py-3">
                                
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="">
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                Dep
                            </td>
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                Role
                            </td>
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                admin
                            </td>
                            <td class=" px-6 py-3 font-medium text-left">
                                <button>
                                    <i class="fal fa-times  pr-3" ></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-0 col-span-7 rounded-md">
                <label for="" class="label-form mb-3">
                    Meeting Minutes
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <textarea type='text' class="input-ui w-full !p-1 text-xs" rows="8" placeholder="Description" ></textarea>
                </div>

            </div>
            <hr class="col-span-12 my-6">
            <div class="mb-4 col-span-12">
                <p class="text-lg font-normal font-inter">
                    Add Instruction
                </p>
            </div>
            <div class="mb-4 col-span-4 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Objective Name
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-4 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    OKR Point
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div><div class="col-span-4"></div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Project
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Tag
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Assigned Person
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div><div class="col-span-3"></div>

            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Start Date 
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Due Date
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Remark
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div><div class="col-span-3"></div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Accountable
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Consulted
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Informed
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div><div class="col-span-3"></div>

            <div class="mb-4 col-span-6 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Key Result
                </label>
                <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3">
                <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10">
                    Add
                </button>
            </div>
            <div class=" col-span-12 mb-10">
                <table class="min-w-[50%] text-sm font-light ml-2">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Key Results
                            </th>
                            <th scope="col" class=" px-6 py-3">
                                
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="">
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                Key REsult 1
                            </td>
                            <td class=" px-6 py-3 font-medium text-left">
                                <button>
                                    <i class="fal fa-times  pr-3" ></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-12">
                <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10">
                    Add
                </button>
            </div>

            <hr class="col-span-12 my-6">
            <div class=" col-span-12 mb-10">
                <table class="min-w-[50%] text-sm font-light ml-2">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Instruction
                            </th>
                            <th scope="col" class=" px-6 py-3">
                                
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="">
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                Key REsult 1
                            </td>
                            <td class=" px-6 py-3 font-medium text-left">
                                <button>
                                    <i class="fal fa-times  pr-3" ></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <div>
            <button class="add-btn" @click="createBtnClicked">
                Create 
            </button>
        </div> -->
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
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Delete 
                    </h5>
                    <button type="button"
                        class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-6 w-6">
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
                    <button @click="deleteItem()" type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                        class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            departmentList: [],
            roleList: [],
            staffList: [],
            
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        
    },
    created(){
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
