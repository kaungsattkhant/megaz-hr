<template>
    <div class="px-0">
        


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-8 col-span-12">
                <p class="text-lg font-semibold font-inter">
                    Okr Assign
                </p>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Date
                </label>
                <input type="date" v-model="start_date" class="input-ui " :max="end_date">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Due Date
                </label>
                <input type="date" v-model="end_date" class="input-ui " :min="start_date">
            </div>
            <div class="col-span-6"></div>
            <div class="mb-4 col-span-3 rounded-md">
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
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Role
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" @change="roleChanged()"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Staff
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select
                        name="" id="" v-model="selectedStaff" class="input-ui !text-black"
                        data-te-select-init data-te-select-placeholder="Select Staff" data-te-select-filter="true">
                        <option :value="staff" v-for="(staff, index) in staffList"
                            :key="index"> {{ staff.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Key Result
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Objective"
                        data-te-select-filter="true" name="" id="" v-model="selectedOkr" class="input-ui">
                        <option v-if="okrList.length < 1" selected disabled> Not Found! </option>
                        <option :value="okr" v-for="(okr, index) in okrList"
                            :key="index"> {{ okr.objective_name }} </option>
                    </select>
                </div>
            </div>

            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="btnClickedAddAssign()">
                    Add
                </button>
            </div>


        </div>
        <div class=" bg-white py-8 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table !text-left">
                    <thead class="">
                        <tr>
                            <th scope="col" class="">
                                Department
                            </th>
                            <th scope="col" class="">
                                Role
                            </th>
                            <th scope="col" class="">
                                Staff
                            </th>
                            <th scope="col" class="">
                                Objective
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(assign, assignIndex) in assignList"
                            :key="assignIndex">
                            <td class="">
                                {{ assign.department_name }}
                            </td>
                            <td class="">
                                {{ assign.role_name }}
                            </td>
                            <td class="">
                                {{ assign.staff_name }}
                            </td>
                            <td class="">
                                {{ assign.objective_key_name }}
                            </td>
                            <td class="">
                                <button @click="editModalBtnClicked(assign,assignIndex)" data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn">
                                    <i class="fal fa-pen  pr-3"></i>
                                </button>
                                <button @click="removeItem(assignIndex)">
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <button class="add-btn" @click="btnClickedCreateOkrAssign()">
                Create
            </button>
        </div>
        <!-- Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="edit_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Edit
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" id="close_edit_modal"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4 ">
                            <label for="" class="label-form mb-3">
                                Date
                            </label>
                            <input type="date" v-model="edit_start_date" class="input-ui " :max="edit_end_date">
                        </div>
                        <div class="mb-4 ">
                            <label for="" class="label-form mb-3">
                                Due Date
                            </label>
                            <input type="date" v-model="edit_end_date" class="input-ui " :min="edit_start_date">
                        </div>
                        <div class="mb-4 ">
                            <label for="" class="label-form mb-3">
                                Department
                            </label>
            
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Department" @change="changeDepartment(department)"
                                    data-te-select-filter="true" name="" id="" v-model="selectedEditDepartment" class="input-ui !text-black text-sm">
                                    <option :value="department" v-for="(department, index) in departmentList"
                                        :key="index"> {{ department.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4 ">
                            <label for="" class="label-form mb-3">
                                Role
                            </label>
            
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Role" @change="editRoleChanged()"
                                    data-te-select-filter="true" name="" id="" v-model="selectedEditRole" class="input-ui !text-black text-sm">
                                    <option :value="role" v-for="(role, index) in editRoleList"
                                        :key="index"> {{ role.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4 ">
                            <label for="" class="label-form mb-3">
                                Staff
                            </label>
                            <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                                data-te-select-wrapper-ref>
                                <select
                                    name="" id="" v-model="selectedEditStaff" class="input-ui !text-black"
                                    data-te-select-init data-te-select-placeholder="Select Staff" data-te-select-filter="true">
                                    <option :value="staff" v-for="(staff, index) in editStaffList"
                                        :key="index"> {{ staff.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4 ">
                            <label for="" class="label-form mb-3">
                                Key Result
                            </label>
                            <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Objective"
                                    data-te-select-filter="true" name="" id="" v-model="selectedEditOkr" class="input-ui">
                                    <option v-if="editOkrList.length < 1" selected disabled> Not Found! </option>
                                    <option :value="okr" v-for="(okr, index) in editOkrList"
                                        :key="index"> {{ okr.objective_name }} </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="editBtnClicked"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button  data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="hidden">
        <i class="fal fa-pen  pr-3"></i>
    </button>
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
    props: ["okrAssignId"],
    data() {
        return {
            departmentList:[],
            roleList: [],
            staffList:[],
            okrList:[],
            assignList:[],

            start_date:null,
            end_date:null,
            selectedDepartment:null,
            selectedRole: null,
            selectedStaff:null,
            selectedOkr:null,

            selectedIndex:null,

            detail: null,

            editDetail: null,
            editIndex: null,

            edit_start_date:null,
            edit_end_date:null,
            selectedEditDepartment:null,
            editRoleList: [],
            selectedEditRole: null,
            editStaffList:[],
            selectedEditStaff:null,
            editOkrList: [],
            selectedEditOkr:null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getDetail() {
            let response = await getApiData({ url: `/api/okr-assigns/${this.okrAssignId}`, token: this.getToken() });
            if (response.data) {
                this.detail = response.data;
                this.addDetail(response.data)
            }
        },
        async addDetail(detail){
            this.assignList.push({
                id: detail.id,
                department_name: detail.staff.department.name,
                department_id: detail.staff.department.id,
                role_name: detail.staff.roles[0].name,
                role_id: detail.staff.roles[0].id,
                staff_name: detail.staff.name,
                objective_key_name: detail.objective.objective_name,

                staff_id: detail.staff.id,
                objective_id: detail.objective.id,
                start_date: detail.start_date,
                end_date: detail.end_date,
            })
        },


        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        changeDepartment(){
            this.selectedRole = null;
            this.roleList = this.selectedDepartment.roles;
        },
        async roleChanged(){
            this.getStaffList();
            this.getOkrList();
        },
        async getStaffList(){
            let response = await getApiData({ url: '/api/hr/staff_lists_by_role/' + this.selectedRole.id + '/department/' + this.selectedDepartment.id, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data.data;
            }
        },
        async getOkrList(){
            let response = await getApiData({ url: '/api/objectives?roleId=' + this.selectedRole.id + '&type=occasionally', token: this.getToken() });
            if (response.data) {
                this.okrList = response.data;
            }
        },
        
        
        btnClickedAddAssign(){
            if(!this.start_date){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            else if(!this.end_date){
                this.alertValidationMessage(`Due Date`);
                return 1;
            }
            else if(!this.selectedDepartment){
                this.alertValidationMessage(`Department`);
                return 1;
            }
            else if(!this.selectedRole){
                this.alertValidationMessage(`Role`);
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
                this.assignOkr();
            }
        },
        async assignOkr(){
            this.assignList.push({
                department_name:this.selectedDepartment.name,
                role_name: this.selectedRole.name,
                role_id: this.selectedRole.id,
                staff_name: this.selectedStaff.name,
                objective_key_name: this.selectedOkr.objective_name,

                staff_id: this.selectedStaff.id,
                objective_id: this.selectedOkr.id,
                start_date: this.start_date,
                end_date: this.end_date,
            })

            this.selectedDepartment = null;
            this.roleList = [];
            this.selectedRole = null;
            this.staffList = [];
            this.selectedStaff = null;
            this.okrList = [];
            this.selectedOkr = null;
            this.start_date = null;
            this.end_date = null;
        },
        removeItem(index){
            this.assignList.splice(index, 1);
        },

        editModalBtnClicked(editDetail,editIndex){
            this.editDetail = editDetail;
            this.editIndex = editIndex;

            this.selectedEditDepartment= this.departmentList.find(dep => dep.id === editDetail.department_id);
            this.editRoleList = this.selectedEditDepartment.roles;
            this.selectedEditRole=  this.editRoleList.find(role => role.id === editDetail.role_id);
            this.edit_start_date= editDetail.start_date.split(' ')[0];
            this.edit_end_date= editDetail.end_date.split(' ')[0];
            this.getStaffForEdit(editDetail.staff_id);
            this.getOkrForEdit(editDetail.objective_id);
        },
        async getStaffForEdit(staffEditDetail){
            let response = await getApiData({ url: '/api/hr/staff_lists_by_role/' + this.selectedEditRole.id + '/department/' + this.selectedEditDepartment.id, token: this.getToken() });
            if (response.data) {
                this.editStaffList = response.data.data;
                this.selectedEditStaff= this.editStaffList.find(staff => staff.id === staffEditDetail);
            }
        },
        async getOkrForEdit(okrEditDetail){
            let response = await getApiData({ url: '/api/objectives?roleId=' + this.selectedEditRole.id + '&type=occasionally', token: this.getToken() });
            if (response.data) {
                this.editOkrList = response.data;
                this.selectedEditOkr= this.editOkrList.find(okr => okr.id === okrEditDetail);
            }
        },
        editBtnClicked(){
            this.assignList[this.editIndex].department_name= this.selectedEditDepartment.name;
            this.assignList[this.editIndex].department_id= this.selectedEditDepartment.id;
            this.assignList[this.editIndex].role_name= this.selectedEditRole.name;
            this.assignList[this.editIndex].role_id= this.selectedEditRole.id;
            this.assignList[this.editIndex].staff_name= this.selectedEditStaff.name;
            this.assignList[this.editIndex].objective_key_name= this.selectedEditOkr.objective_name;
            this.assignList[this.editIndex].staff_id= this.selectedEditStaff.id;
            this.assignList[this.editIndex].objective_id= this.selectedEditOkr.id;
            this.assignList[this.editIndex].start_date= this.edit_start_date;
            this.assignList[this.editIndex].end_date= this.edit_end_date;
            document.getElementById("close_edit_modal").click();
        },
        async editRoleChanged(){
            this.getEditStaffList();
            this.getEditOkrList();
        },
        async getEditStaffList(){
            let response = await getApiData({ url: '/api/hr/staff_lists_by_role/' + this.selectedEditRole.id + '/department/' + this.selectedEditDepartment.id, token: this.getToken() });
            if (response.data) {
                this.editStaffList = response.data.data;
            }
        },
        async getEditOkrList(){
            let response = await getApiData({ url: '/api/objectives?roleId=' + this.selectedEditRole.id + '&type=occasionally', token: this.getToken() });
            if (response.data) {
                this.editOkrList = response.data;
            }
        },

        btnClickedCreateOkrAssign(){
            // if(!this.start_date){
            //     this.alertValidationMessage(`Date`);
            //     return 1;
            // }
            // else if(!this.end_date){
            //     this.alertValidationMessage(`Due Date`);
            //     return 1;
            // }
            // else 
            if(this.assignList.length < 1){
                this.alertValidationMessage(`OKR Assign`);
                return 1;
            }
            else{
                this.createOkrAssign();
            }
        },
        async createOkrAssign(){
            let formData = new FormData();
            // formData.append('assign_date',this.start_date);
            // formData.append('due_date',this.end_date);
            formData.append('okr_assign',JSON.stringify(this.assignList));
            let response = await postApiData({url:`/api/okr-assigns`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/okr_assign`);
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
        this.getDetail();
    },

    mounted() {
        this.getDepartmentList();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
