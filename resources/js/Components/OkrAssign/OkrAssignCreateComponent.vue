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
        };
    },

    methods: {
        ...mapGetters(['getToken']),
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

    },

    mounted() {
        this.getDepartmentList();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
