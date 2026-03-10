<template>
    <div class="px-0">


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-12">
                <p class="text-lg font-semibold">
                    Add Objective Key Results
                </p>
            </div>
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Department
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black">
                        <option :value="department" v-for="(department, index) in departmentList"
                            :key="index"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Role
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" @change="roleChanged()"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-6"></div>

            <div class="mb-6 col-span-6">
                <label for="" class="label-form mb-3">
                    SOP
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select SOP"
                        data-te-select-filter="true" name="" id="" v-model="selectedSop" class="input-ui !text-black">
                        <option :value="sop" v-for="(sop, index) in sopList"
                            :key="index"> {{ sop.sop }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Project
                </label>
                <multiselect
                    v-model="selectedProject"
                    :options="projectList"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="false"
                    placeholder="Project"
                    label="name"
                    track-by="id"
                    :preselect-first="false"
                    >
                </multiselect>
                <!-- <input type="number" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
            </div>
            <div class="mb-4 col-span-1 pb-0 rounded-md">
                <label for="" class="label-form mb-5">
                    &nbsp;
                </label>
                <button data-te-toggle="modal" data-te-target="#addProjectModal" > <i class="fal fa-plus"></i> </button>
            </div>
            <div class="col-span-2"></div>
            <div class="mb-4 col-span-4">
                <label for="" class="label-form mb-3">
                    Accountable
                </label>
                <multiselect v-model="accountableStaff" :options="staffList"
                    :clear-on-select="false"
                    :preserve-search="false" placeholder="Accountable" label="name" track-by="id"
                :preselect-first="false"></multiselect>
            </div>
            <div class="mb-4 col-span-4">
                <label for="" class="label-form mb-3">
                    Consulted
                </label>
                <multiselect v-model="consultedStaff" :options="staffList"
                    :clear-on-select="false"
                    :preserve-search="false" placeholder="Consulted" label="name" track-by="id"
                :preselect-first="false"></multiselect>
            </div>
            <div class="mb-4 col-span-4">
                <label for="" class="label-form mb-3">
                    Informed
                </label>
                <multiselect v-model="informedStaff" :options="staffList"
                    :clear-on-select="false"
                    :preserve-search="false" placeholder="Informed" label="name" track-by="id"
                :preselect-first="false"></multiselect>
            </div>
            <!-- <div class="col-span-6">
                <label for="" class="label-form mb-3">
                    Consulted
                </label>

            </div> -->
            <div class="mb-4 col-span-2">
                <label for="" class="label-form mb-3">
                    Objective
                </label>
                <input type="text" v-model="objName" class="input-ui ">
            </div>
            <div class="col-span-2">
                <label for="" class="label-form mb-3">
                    OKR Point
                </label>
                <input type="number" v-model="selectedOkrPoint" class="input-ui ">
            </div>
            <div class="col-span-2">
                <label for="" class="label-form mb-3">
                    Priority
                </label>
                <input type="number" v-model="selectedPriority" class="input-ui ">
            </div>
            <div class="mb-6 col-span-2">
                <label for="" class="label-form mb-3">
                    Type
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type"
                        data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui !text-black">
                        <option :value="type" v-for="(type, index) in typeList"
                            :key="index"> {{ type.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-2" v-if="selectedType?.value === 'daily'">
                <label for="" class="label-form mb-3">
                    Repetition
                </label>
                <input type="number" v-model="selectedRepition" class="input-ui ">
            </div>
            <div class="col-span-2" v-else></div>

            <div class="col-span-2"></div>

            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Key Result
                </label>
                <input type="text" v-model="key_result" class="input-ui ">
            </div>
            <div class="">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="addObj()">
                    Add
                </button>
            </div>


            <!-- <div class="mb-4 col-span-3"> -->
                <!-- <label for="" class="label-form mb-3">
                    Date Assigned
                </label>
                <multiselect v-model="selectedDate" :options="dateList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                    :preserve-search="false" placeholder="Select Date" label="" :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single"
                            v-if="values.length"
                            v-show="!isOpen">{{ values.length }} Date selected</span>
                    </template>
                </multiselect> -->
                <!-- <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role"
                        data-te-select-filter="true" name="" id="" v-model="selectedDate" class="input-ui !text-black">
                        <option :value="date" v-for="(date, index) in dateList"
                            :key="index"> {{ date }} </option>
                    </select>
                </div> -->
            <!-- </div> -->
            <!-- <div class="col-span-6"></div> -->



            <!-- <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Duration
                </label>
                <input type="number" v-model="duration" class="input-ui ">
            </div> -->





            <div class="contents">


                <!-- <div class="col-span-3">
                    <label for="" class="label-form mb-3">
                        Lead Time ( minutes )
                    </label>
                    <input type="number" v-model="duration" class="input-ui ">
                </div> -->

            </div>
        </div>



        <div class=" bg-white py-4 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="text-left">
                                Key Results
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(keyResult, keyResultIndex) in key_result_list"
                            :key="keyResultIndex">

                            <td class="text-left">
                                {{ keyResult.name }}
                            </td>

                            <td class="">
                                <button @click="deleteKey(keyResultIndex)">
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class=" !text-center" v-if="key_result_list.length < 1">
                            <td class="" colspan="2">
                                No Data Here
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <div class="mt-6">
                <button class="add-btn" @click="btnclickedCreateOkr()">
                    Create OKR
                </button>
            </div>


        </div>

<div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="addProjectModal" tabindex="-1" aria-labelledby="add_category_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_category_modalLabel">
                            Create Project
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="btn-close-create-project-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4 pb-0 rounded-md">
                            <label for="" class="block text-sm text-black mb-3">
                                Name
                            </label>
                            <input type="text" v-model="projectName" autocomplete="off"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <LoadingButton
                            :loading="projectCreateLoading"
                            text="Create"
                            loadingText="Loading..."
                            @click="createProject"
                        />
                        <!-- <button type="button" @click="createCategory()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button> -->
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
import LoadingButton from "../Common/LoadingButton.vue";

export default {
    components: {
        Multiselect,
        LoadingButton
    },
    data() {
        return {
            objName: null,
            departmentList:[],
            roleList:[],
            dateList: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
            // dateList: [
            //     { name: 'Monday', value: 'Monday' },
            //     { name: 'Tuesday', value: 'Tuesday' },
            //     { name: 'Wednesday', value: 'Wednesday' },
            //     { name: 'Thursday', value: 'Thursday' },
            //     { name: 'Friday', value: 'Friday' },
            //     { name: 'Saturday', value: 'Saturday' },
            //     { name: 'Sunday', value: 'Sunday' }
            // ],
            selectedDepartment:null,
            selectedRole:null,
            key_result:null,
            selectedOkrPoint:null,
            selectedPriority: null,
            selectedDate:null,
            duration:null,

            key_result_list:[],
            selected_obj_keys:[],
            testKey:[],

            sopList: [],
            selectedSop: null,
            typeList: [
                { name: 'Daily', value: 'daily' },
                { name: 'Occasionally', value: 'occasionally' },
            ],
            selectedType: null,
            selectedRepition: null,
            staffList: [],
            accountableStaff: null,
            consultedStaff: null,
            informedStaff: null,

            projectList: [],
            selectedProject: null,
            projectName: null,
            projectCreateLoading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        addObj() {
            if (!this.key_result) {
                this.alertValidationMessage(`Key Result`);
                return 1;
            }
            else{
                this.key_result_list.push({
                    name: this.key_result,
                    // department_name:this.selectedDepartment.name,
                    // role_id:this.selectedRole.id,
                    // role_name:this.selectedRole.name,
                    // okr_point: this.selectedOkrPoint,
                    // assigned_days: this.selectedDate,
                    // duration: this.duration
                });  // Add a new input box
                // this.selectedDepartment = null;
                // this.selectedRole = null;
                this.key_result = null;
                // this.selectedOkrPoint = null;
                // this.selectedDate = null;
                // this.duration = null;
            }
        },
        deleteKey(index) {
            this.key_result_list.splice(index, 1);
        },


        async getDepartment(){
            let response = await getApiData({url: `/api/departments`, token: this.getToken()});
            if(response.data){
                this.departmentList = response.data;
            }
        },
        selectedDepartmentChange(){
            this.selectedRole = null;
            this.roleList = [];
            this.sopList = [];
            this.selectedSop = null;
            this.getRoleList();
        },
        async getRoleList(){
            let response = await getApiData({url: '/api/roles_department/' + this.selectedDepartment.id , token: this.getToken()});
            if(response.data){
                this.roleList = response.data;
            }
        },
        roleChanged(){
            this.sopList = [];
            this.getSopList();
        },
        async getSopList(){
            let response = await getApiData({url: `/api/sops?role_id=` + this.selectedRole.id, token: this.getToken()});
            if(response.data){
                // this.sopList = response.data;
                response.data.forEach(sops => {
                    sops.sops.forEach(sop => {
                        this.sopList.push(sop)
                    })
                });
            }
        },
        createProject(){
            this.projectCreateLoading = true;
            if(!this.projectName){
                this.projectCreateLoading = false;
                this.showToastMessage("Name of the project is required");
                return;
            }
            let formData = new FormData();
            formData.append('name', this.projectName);
            postApiData({url: `/api/projects`, form_data: formData, token: this.getToken()})
            .then((response)=>{
                this.projectCreateLoading = false;
                if(response.data){
                    this.projectList.push(response.data);
                    this.selectedProject = response.data;
                }                
            });
            document.getElementById('btn-close-create-project-modal').click();
        },
        getProjectList(){
            getApiData({url: `/api/projects`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.projectList = response.data;
                }
            });
        },


        btnclickedCreateOkr(){
            // if(!this.objName){
            //     this.alertValidationMessage(`Objective Name`);
            //     return 1;
            // }
            // else if(this.key_result_list < 1){
            //     this.alertValidationMessage(`Objective Key`);
            //     return 1;
            // }
            if(!this.objName){
                this.alertValidationMessage(`Objective Name`);
                return 1;
            }
            else if(!this.selectedRole){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            else if(!this.selectedSop){
                this.alertValidationMessage(`SOP`);
                return 1;
            }
            else if(!this.selectedProject){
                this.alertValidationMessage(`Project`);
                return 1;
            }
            else if(!this.selectedOkrPoint){
                this.alertValidationMessage(`OKR Point`);
                return 1;
            }
            else if(!this.selectedPriority){
                this.alertValidationMessage('Priority');
                return;
            }

            else if(!this.selectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }

            else if(this.selectedType.value === 'daily' && !this.selectedRepition){
                this.alertValidationMessage(`Repitition`);
                return 1;
            }
            else if(this.key_result_list < 1){
                this.alertValidationMessage(`Objective Key`);
                return 1;
            }
            else{
                this.createOkr()
            }
        },
        async createOkr(){
            // let selectedDate = [];
            // this.selectedDate.forEach(date => {
            //     selectedDate.push(date.value)
            // });
            // console.log(selectedDate)

            if(!this.accountableStaff){
                this.alertValidationMessage("accountable person");
                return;
            }
            if(!this.consultedStaff){
                this.alertValidationMessage("consulted person");
                return;
            }
            if(!this.informedStaff){
                this.alertValidationMessage("informed person");
                return;
            }
            let formData = new FormData();
            formData.append('objective_name', this.objName);
            formData.append('okr_point', this.selectedOkrPoint);
            formData.append('type', this.selectedType.value);
            if(this.selectedType.value === 'daily'){
                formData.append('repetition', this.selectedRepition);
            }

            formData.append('role_id', this.selectedRole.id);
            formData.append('sop_id', this.selectedSop.id);
            formData.append('project_id', this.selectedProject.id);
            // formData.append('assigned_days', JSON.stringify(selectedDate));
            formData.append('objective_key', JSON.stringify(this.key_result_list));
            formData.append('accountable_id', this.accountableStaff.id);
            formData.append('consulted_id', this.consultedStaff.id);
            formData.append('informed_id', this.informedStaff.id);
            formData.append('priority', this.selectedPriority);
            let response = await postApiData({ url: '/api/objectives', form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace('/OKR');
                console.log('success')
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }

        },


        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        getStaffList(){
            getApiData({url: `/api/staffs`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.staffList = response.data;
                }
            });
        },
    },

    watch: {
    },

    async created() {
        this.getDepartment();
        this.getStaffList();
        // this.getSopList();
        this.getProjectList();

    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>
<style scoped>
    .multiselect__placeholder{
        margin-bottom: 6px!important;
    }
</style>

<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
