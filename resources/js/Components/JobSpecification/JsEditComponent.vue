<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Edit Job Specification
            </p>
        </div>


        <div class="grid !grid-cols-10 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">

            <div class="mb-4 col-span-3 pb-3 rounded-md">
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
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Role
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" @change="roleChange"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-4"></div>
            
            <div class="mb-3 col-span-6 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    JD
                </label>
                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select JD"
                        data-te-select-filter="true" name="" id="" v-model="selectedJd" class="input-ui !text-black text-sm">
                        <option :value="jd" v-for="(jd, index) in jdList"
                            :key="index"> {{ jd.job_description }} </option>
                    </select>
                </div>
            </div><div class="col-span-4"></div>
            <div class="mb-3 col-span-6 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    JS
                </label>
                <input type="text" v-model="selectedJs" class="input-ui ">
            </div><div class="col-span-4"></div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Skill
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Skill"
                            data-te-select-filter="true" name="" id="" v-model="selectedSkill" class="input-ui !text-black text-sm">
                            <option :value="skill" v-for="(skill, index) in skillList"
                                :key="index"> {{ skill.skill }} </option>
                        </select>
                    </div>
                    <button  class="px-2 pt-2"  data-te-toggle="modal" data-te-target="#add_modal" @click="addSkillBtnClicked"><i class="fal fa-plus"></i></button>
                </div>
            </div>
            <div class="col-span-4">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="btnClickedAddSkill()">
                    Add
                </button>
            </div>


        </div>
        <div class=" bg-white py-8 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="!text-left">
                        <tr>
                            <th scope="col" class="">
                                Skill
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="!text-left">
                        <tr class="" v-for="(skill, skillIndex) in selectedSkillList"
                            :key="skillIndex">
                            <td class="">
                                {{ skill.skill_name ? skill.skill_name : '--' }}
                            </td>
                            <td class="text-center">
                                <button @click="removeSkill(skillIndex)">
                                    <i class="fas fa-times  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class=" !text-center" v-if="selectedSkillList.length < 1">
                            <td class="" colspan="3">
                                No Data Here
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <button class="add-btn" @click="btnClickedCreateJs()">
                Edit Job Specification
            </button>
        </div>




        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_modal" tabindex="-1" aria-labelledby="add_modal_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_modal_modalLabel">
                            Create Skill
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close" id="close_add_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Skill
                            </label>
                            <input type="text" placeholder="Skill" v-model="selectedSkillName"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label class="label-form mb-3">Department</label>
                            <select
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                v-model="selectedDepartmentForSkill" @change="getRoleByDepartment(selectedDepartment)">
                                <option class="text-sm" :value="department"
                                    v-for="(department, index) in departmentListForSkill" :key="index">
                                    {{ department.name }}
                                </option>

                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="label-form mb-3">Role</label>
                            <select
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                v-model="selectedRoleForSkill">
                                <option class="text-sm" :value="role" v-for="(role, index) in roleListForSkill"
                                    :key="index">
                                    {{ role.name }}
                                </option>

                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnClickedCreateSkill()"
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
    props:['jsId'],
    components: {
        Multiselect
    },
    data() {
        return {
            departmentList:[],
            roleList:[],
            jdList:[],
            skillList: [],

            selectedSkillList: [],

            selectedDepartment:null,
            selectedRole:null,
            selectedJd:null,
            selectedJs:null,
            selectedSkill:null,

            // allowancetype modal
            departmentListForSkill: [],
            roleListForSkill: [],
            selectedSkillName: null,
            selectedDepartmentForSkill: null,
            selectedRoleForSkill: null,

            jsDetail: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getJsDetail(){
            let response = await getApiData({ url: '/api/job-specifications/' + this.jsId, token: this.getToken() });
            if (response.data) {
                this.jsDetail = response.data;
                this.selectedJs = response.data.job_specification;
                this.selectedDepartment = this.departmentList.find(dep => dep.id === response.data.job_description.role.department_id);
                this.roleList = this.selectedDepartment.roles;
                this.selectedRole = this.roleList.find(role => role.id === response.data.job_description.role.id);
                setTimeout(() => {
                    this.getJdList();
                }, 200);
            }
        },
        async getJdList(){
            this.selectedJd = null;
            let response = await getApiData({ url: '/api/job-descriptions?role_id=' + this.selectedRole.id, token: this.getToken() });
            if (response.data) {
                this.jdList = response.data;
                this.selectedJd = this.jdList.find(jd => jd.id === this.jsDetail.job_description_id)
            }
            this.getSkillList();
        },
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
                this.departmentListForSkill = response.data;
            }
        },
        changeDepartment(){
            this.selectedRole = null;
            this.roleList = this.selectedDepartment.roles;
            this.selectedSkill = null;
            this.skillList = [];
        },
        getRoleByDepartment(){
            this.selectedRoleForSkill = null;
            this.roleListForSkill = this.selectedDepartmentForSkill.roles;
        },
        async roleChange(){
            this.selectedJd = null;
            let response = await getApiData({ url: '/api/job-descriptions?role_id=' + this.selectedRole.id, token: this.getToken() });
            if (response.data) {
                this.jdList = response.data;
            }
            this.getSkillList();
        },
        async getSkillList(){
            this.selectedSkill = null;
            let response = await getApiData({ url: '/api/roles/' + this.selectedRole.id + '/skills', token: this.getToken() });
            if (response.data) {
                this.skillList = response.data;
            }
        },
        addSkillBtnClicked(){
            this.selectedDepartmentForSkill = null;
            this.roleListForSkill = [];
            this.selectedRoleForSkill = null;
            this.selectedSkillName = null;
        },
        btnClickedCreateSkill(){
            if(!this.selectedDepartmentForSkill){
                this.alertValidationMessage(`Department `);
                return 1;
            }
            else if(!this.selectedRoleForSkill){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            else if(!this.selectedSkillName){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else{
                this.createSkill();
            }
        },
        async createSkill(){
            let formData = new FormData();
            formData.append('skill', this.selectedSkillName);
            formData.append('role_id', this.selectedRoleForSkill.id);
            let response = await postApiData({ url: '/api/skills', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getSkillList(1);
                document.getElementById('close_add_modal').click();
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },




        btnClickedAddSkill(){
            if(!this.selectedSkill){
                this.alertValidationMessage(`Skill`);
                return 1;
            }
            else{
                this.addSkill();
            }
        },
        async addSkill(){
            
            this.selectedSkillList.push({
                skill_name: this.selectedSkill ? this.selectedSkill.skill : null,
                skill_id: this.selectedSkill ? this.selectedSkill.id : null,
            })
            this.selectedSkill = null;
        },
        removeSkill(index){
            this.selectedSkillList.splice(index, 1);
        },

        btnClickedCreateJs(){
            if(this.selectedSkillList.length < 1){
                this.alertValidationMessage(`Skill`);
                return 1;
            }
            else if(!this.selectedJd){
                this.alertValidationMessage(`Job Description`);
                return 1;
            }
            else if(!this.selectedJs){
                this.alertValidationMessage(`Job Specification`);
                return 1;
            }
            else{
                this.createJs();
            }
        },
        async createJs(){
            let formData = new FormData();
            formData.append('job_description_id',this.selectedJd.id);
            formData.append('job_specification',this.selectedJs);
            this.selectedSkillList.forEach((skill) => {
                formData.append('sills[]', skill.id);
            });
            let response = await postApiData({url:`/api/job-specifications`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/JS`);
            }
        },





        clearAllowanceTypeModal(){
            this.ATSelectedDepartment = null;
            this.ATRoleList = [];
            this.ATSelectedRole = null;
            this.ATName = null;
            this.ATSelectedType = null;
            this.ATAmount = null
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
        this.getJsDetail();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
