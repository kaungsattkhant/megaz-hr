<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Skill
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <notifications position="top center" />
    
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
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Skill
                                </th>
                                <th scope="col" class="">
                                    Role
                                </th>
    
                                <th scope="col" class="">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(skill, index) in skillList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ skill.skill }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ skill.role.name }}
                                    </td>
    
                                    <td class="whitespace-nowrap">
                                    </td>
    
                                    <td class="whitespace-nowrap flex justify-center gap-3">
                                        <i class="fal fa-pen cursor-pointer" data-te-toggle="modal"
                                            data-te-target="#update_modal" @click="getSkillDetail(skill.id)"></i>
                                        <i class="far fa-trash-alt cursor-pointer" @click="deleteSkill(skill.id)"></i>
                                    </td>
    
                                </tr>
                            </div>
                        </tbody>
                    </table>
    
                    <!-- pagination -->
                    <div class="flex justify-center">
    
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getSkillList(currentPage - 1)">«</button>
    
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
    
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getSkillList(currentPage + 1)">
                                »</button>
                        </div>
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

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black"
                            id="create_modalLabel">
                            Add New
                        </h5>
                        <button type="button" id="close_create_modal"
                            class="absolute top-4 right-4 focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="createSkill()">
                        <div class="relative px-12 py-4" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Skill
                                </label>
                                <input type="text" placeholder="Skill" v-model="skill"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>

                            <div class="mb-4">
                                <label class="label-form mb-3">Department</label>
                                <select
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedDepartment" @change="getRoleByDepartment(selectedDepartment)">
                                    <option class="text-sm" :value="department.id"
                                        v-for="(department, index) in getDepartments" :key="index">
                                        {{ department.name }}
                                    </option>

                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="label-form mb-3">Role</label>
                                <select
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedRole">
                                    <option class="text-sm" :value="role.id" v-for="(role, index) in roleList"
                                        :key="index">
                                        {{ role.name }}
                                    </option>

                                </select>
                            </div>

                        </div>
                        <div class="flex justify-center px-12 mb-6">
                            <button type="submit" class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="update_modal" tabindex="-1" aria-labelledby="update_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black"
                            id="update_modalLabel">
                            Update Skill
                        </h5>
                        <button type="button" id="close_create_modal"
                            class="absolute top-4 right-4 focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="updateSkill()">
                        <div class="relative px-12 py-4" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Skill
                                </label>
                                <input type="text" placeholder="Skill" v-model="edit_skill"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>

                            <div class="mb-4">
                                <label class="label-form mb-3">Department</label>
                                <select
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedDepartment" @change="getRoleByDepartment(selectedDepartment)">
                                    <option class="text-sm" :value="department.id"
                                        v-for="(department, index) in getDepartments" :key="index">
                                        {{ department.name }}
                                    </option>

                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="label-form mb-3">Role</label>
                                <select
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="edit_role">
                                    <option class="text-sm" :value="role.id" v-for="(role, index) in roleList"
                                        :key="index">
                                        {{ role.name }}
                                    </option>

                                </select>
                            </div>

                        </div>
                        <div class="flex justify-center px-12 mb-6" data-te-modal-dismiss>
                            <button type="submit" class="add-btn focus:outline-none focus:ring-0 ">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            skillList: [],
            getDepartments: [],
            roleList: [],

            skill: null,
            selectedRole: null,
            selectedDepartment: null,

            edit_skill: null,
            edit_role: null,
            edit_skill_id: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getRoleByDepartment(id) {
            const response = await getApiData({ url: `/api/role_by_department/` + id, token: this.getToken() });
            if (response.data) {
                this.roleList = response.data;
            }
        },

        async getSkillList(pageNumber) {
            const response = await getApiData({ url: `/api/skills?page=${pageNumber}`, token: this.getToken() });
            if (response.data) {
                this.skillList = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        async getDepartment() {
            const response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.getDepartments = response.data;
            }
        },

        async createSkill() {
            let formData = new FormData();
            formData.append('skill', this.skill);
            formData.append('role_id', this.selectedRole);
            let response = await postApiData({ url: '/api/skills', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getSkillList(1);
                this.closeAndClearCreateModal();
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

        async getSkillDetail(id) {
            let skillDetailData = await getApiData({ url: `/api/skills/${id}`, token: this.getToken() });
            this.edit_skill = skillDetailData.data.skill
            this.selectedDepartment = skillDetailData.data.role.department_id
            this.getRoleByDepartment(skillDetailData.data.role.department_id);
            this.edit_role = skillDetailData.data.role.id
            this.edit_skill_id = id;
        },

        async updateSkill() {
            let formData = new FormData();
            formData.append('skill', this.edit_skill);
            formData.append('role_id', this.edit_role);
            let response = await postApiData({ url: '/api/skills/' + this.edit_skill_id, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getSkillList(1);
                this.closeAndClearCreateModal();
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },



        async deleteSkill(id) {
            let response = await deleteApiData({ url: `/api/skills/` + id, token: this.getToken() });
            if (response.success) {
                this.getSkillList(1);
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },


        closeAndClearCreateModal() {
            document.getElementById("close_create_modal").click();
            this.selectedSubAccount = null;
            this.selectedAccount = null;
            this.skill = null;
            this.selectedCashbookCreate = null;
        },


    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getSkillList(1);
        this.getDepartment();
    }
}
</script>
