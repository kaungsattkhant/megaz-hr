<template>
    <div class="px-0">
        


        <div class="grid !grid-cols-10 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-10">
                <p class="text-lg font-semibold ">
                    Create Salary Setup
                </p>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
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
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Role
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" 
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-8 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Basic Salary
                </label>
                <input type="number" v-model="basic_salary" class="input-ui ">
            </div>
            <div class="col-span-1"></div>
            
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Allowance Type
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Allowance Type"
                            data-te-select-filter="true" name="" id="" v-model="selectedAllowanceType" class="input-ui !text-black text-sm">
                            <option :value="allowance" v-for="(allowance, index) in allowanceTypeList"
                                :key="index"> {{ allowance.name }} </option>
                        </select>
                    </div>
                    <button  class="px-2 pt-2"  data-te-toggle="modal" data-te-target="#add_allowance_type" @click="clearAllowanceTypeModal"><i class="fal fa-plus"></i></button>
                </div>
            </div>
            
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Amount
                </label>
                <input type="number" v-model="amount" class="input-ui ">
            </div>
            
            

            <div class="col-span-4">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="btnClickedAddAllowance()">
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
                                Allowance
                            </th>
                            <th scope="col" class="">
                                Amount
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="!text-left">
                        <tr class="" v-for="(allowance, allowanceIndex) in allowanceList"
                            :key="allowanceIndex">
                            <td class="">
                                {{ allowance.allowance_name }}
                            </td>
                            <td class="">
                                {{ allowance.amount }}
                            </td>
                            <td class="text-center">
                                <button @click="removeAllowance(allowanceIndex)">
                                    <i class="fas fa-times  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class=" !text-center" v-if="allowanceList.length < 1">
                            <td class="" colspan="3">
                                No Data Here
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                <button class="add-btn" @click="btnClickedEditSalarySetup()">
                    Edit
                </button>
            </div>
        </div>

        




        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_allowance_type" tabindex="-1" aria-labelledby="add_allowance_type_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal"
                            id="add_allowance_type_modalLabel">
                            Create Allowance Type
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close" id="close_allowance_type_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Department
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Department" @change="ATSelectedDepartmentChange()"
                                    data-te-select-filter="true" name="" id="" v-model="ATSelectedDepartment" class="input-ui !text-black text-sm">
                                    <option :value="department" v-for="(department, index) in departmentList"
                                        :key="index"> {{ department.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Role
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Role"
                                    data-te-select-filter="true" name="" id="" v-model="ATSelectedRole" class="input-ui !text-black text-sm">
                                    <option :value="role" v-for="(role, index) in ATRoleList"
                                        :key="index"> {{ role.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" v-model="ATName" class="input-ui mb-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Type
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Type"
                                    data-te-select-filter="true" name="" id="" v-model="ATSelectedType" class="input-ui !text-black text-sm">
                                    <option value="allowance"> Allowance </option>
                                    <option value="deduction"> Deduction </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Amount
                            </label>
                            <input type="text" v-model="ATAmount" class="input-ui mb-0">
                        </div>

                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnClickedAddAllowanceType()"
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
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { each } from "lodash";

export default {
    components: {
        Multiselect
    },
    props: ["salarySetupId"],
    data() {
        return {
            departmentList:[],
            roleList:[],
            allowanceTypeList:[],

            allowanceList:[],

            selectedDepartment:null,
            selectedRole:null,
            basic_salary:null,
            selectedAllowanceType:null,
            amount: null,

            // allowancetype modal
            ATRoleList: [],
            ATSelectedDepartment: null,
            ATSelectedRole: null,
            ATName: null,
            ATSelectedType: null,
            ATAmount: null,



            detail:null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getSalarySetupDetail() {
            let response = await getApiData({ url: `/api/hr/salary_setups/${this.salarySetupId}`, token: this.getToken() });
            if (response.data) {
                this.detail = response.data;
                setTimeout(() => {
                    this.addDetail(response.data);
                }, 200);
                
            }
        },
        addDetail(detail){
            this.selectedDepartment = this.departmentList.find(department => department.id == detail.role.department_id);
            this.roleList = this.selectedDepartment.roles;
            this.selectedRole = this.roleList.find(role => role.id == detail.role_id);
            this.basic_salary = detail.basic_salary;
            detail.salary_allowances.forEach(allowance => {
                this.allowanceList.push({
                    allowance_name: allowance.allowance.name,
                    allowance_id: allowance.allowance_id,
                    amount: allowance.amount,
                    id:allowance.id
                }) 
            });
        },
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        changeDepartment(){
            this.roleList = this.selectedDepartment.roles;
        },
        async getAllowanceTypeList(){
            let response = await getApiData({ url: '/api/hr/salary_allowances', token: this.getToken() });
            if (response.data) {
                this.allowanceTypeList = response.data.data;
            }
        },
        ATSelectedDepartmentChange(){
            this.ATRoleList = this.ATSelectedDepartment.roles;
        },
        btnClickedAddAllowanceType(){
            if(!this.ATSelectedRole){
                this.alertValidationMessage(`Role `);
                return 1;
            }
            else if(!this.ATName){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else if(!this.ATSelectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            else if(!this.ATAmount){
                this.alertValidationMessage(`Amount`);
                return 1;
            }
            else{
                this.addAllowanceType();
            }
        },
        async addAllowanceType(){
            let formData = new FormData();
            formData.append('role_id',this.ATSelectedRole.id);
            formData.append('name',this.ATName);
            formData.append('type',this.ATSelectedType);
            formData.append('amount',this.ATAmount);
            let response = await postApiData({url:`/api/hr/salary_allowances`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getAllowanceTypeList();
                this.clearAllowanceTypeModal();
                document.getElementById('close_allowance_type_modal').click();
            }
        },




        btnClickedAddAllowance(){
            if(!this.selectedAllowanceType){
                this.alertValidationMessage(`Allowance Type`);
                return 1;
            }
            else if(!this.amount){
                this.alertValidationMessage(`Amount`);
                return 1;
            }
            else{
                this.addAllowance();
            }
        },
        async addAllowance(){
            this.allowanceList.push({
                allowance_name: this.selectedAllowanceType.name,
                allowance_id: this.selectedAllowanceType.id,
                amount: this.amount,
            })
            this.selectedAllowanceType = null;
            this.amount = null;
        },
        async removeAllowance(index){
            if(this.allowanceList[index].id){
                console.log('id shi')
                let response = await deleteApiData({ url: `/api/hr/salary_setup/salary_allowances/` + this.allowanceList[index].id, token: this.getToken() });
                if (response.success) {
                    this.allowanceList.splice(index, 1);
                }
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            }
            else{
                console.log('id ma shi')
                this.allowanceList.splice(index, 1);
            }
            // this.allowanceList.splice(index, 1);
        },

        btnClickedEditSalarySetup(){
            if(this.allowanceList.length < 1){
                this.alertValidationMessage(`Allowance`);
                return 1;
            }
            else if(!this.selectedRole){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            else if(!this.basic_salary){
                this.alertValidationMessage(`Basic Salary`);
                return 1;
            }
            else{
                this.createSalarySetup();
            }
        },
        async createSalarySetup(){
            let formData = new FormData();
            formData.append('basic_salary',this.basic_salary);
            formData.append('role_id',this.selectedRole.id);
            formData.append('salary_allowances', JSON.stringify(this.allowanceList));
            let response = await postApiData({url:`/api/hr/salary_setups/${this.salarySetupId}`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/salary_setup`);
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
        this.getAllowanceTypeList();
        this.getSalarySetupDetail();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
