<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Create Salary Batch
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-8 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Batch Name
                </label>
                <input type="text" v-model="batch_name" class="input-ui ">
            </div>
            <div class="mb-8 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Date
                </label>
                <input type="number" v-model="date" class="input-ui " min="1" max="31">
            </div><div class="col-span-6"></div>

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Department
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
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
                    <select data-te-select-init data-te-select-placeholder="Select Role" @change="selectedRoleChange()"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Name
                </label>
                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Name" 
                        data-te-select-filter="true" name="" id="" v-model="selectedStaff" class="input-ui !text-black text-sm">
                        <option :value="staff" v-for="(staff, index) in staffList"
                            :key="index"> {{ staff.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="btnClickedAddSalaryBatch()">
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
                                Department
                            </th>
                            <th scope="col" class="">
                                Role
                            </th>
                            <th scope="col" class="">
                                Name
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="!text-left">
                        <tr class="" v-for="(salaryBatch, salaryBatchIndex) in salaryBatchList"
                            :key="salaryBatchIndex">
                            <td class="">
                                {{ salaryBatch.department_name }}
                            </td>
                            <td class="">
                                {{ salaryBatch.role_name }}
                            </td>
                            <td class="">
                                {{ salaryBatch.staff_name }}
                            </td>
                            <td class="text-center">
                                <button @click="removeSalaryBatch(salaryBatchIndex)">
                                    <i class="fas fa-times  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class=" !text-center" v-if="salaryBatchList.length < 1">
                            <td class="" colspan="3">
                                No Data Here
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <button class="add-btn" @click="btnClickedCreateSalaryBatch()">
                Edit Salary Batch
            </button>
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
    props: ["salaryBatchId"],
    data() {
        return {
            departmentList: [],
            roleList: [],
            staffList: [],

            batch_name: null,
            date: null,
            selectedDepartment: null,
            selectedRole: null,
            selectedStaff: null,

            salaryBatchList: [],

            detail:null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getSalaryBatchDetail() {
            let response = await getApiData({ url: `/api/hr/salary_batches/` + this.salaryBatchId, token: this.getToken() });
            if (response.data) {
                this.detail = response.data[0];
                this.addDetail(response.data[0]);
            }
        },
        addDetail(detail){
            this.batch_name = detail.name;
            this.date = detail.day_of_monthly;

            // this.selectedDepartment = this.departmentList.find(department => department.id == detail.role.department_id);
            // this.roleList = this.selectedDepartment.roles;
            // this.selectedRole = this.roleList.find(role => role.id == detail.role_id);
            // this.basic_salary = detail.basic_salary;
            detail.salary_batch_staff.forEach(batch => {
                this.salaryBatchList.push({
                    department_name: batch.staff.department.name,
                    role_name: batch.staff.roles[0].name,
                    staff_name: batch.staff.name,
                    staff_id: batch.staff_id,
                    id: batch.id,
                }) 
            });
        },
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        selectedDepartmentChange(){
            this.roleList = this.selectedDepartment.roles;
        },
        async selectedRoleChange(){
            let response = await getApiData({ url: '/api/hr/staff_lists_by_role/' + this.selectedRole.id + '/department/' + this.selectedDepartment.id, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data.data;
            }
        },

        btnClickedAddSalaryBatch(){
            if(!this.selectedDepartment){
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
            else{
                this.addSalaryBatch();
            }
        },
        async addSalaryBatch(){
            this.salaryBatchList.push({
                department_name: this.selectedDepartment.name,
                role_name: this.selectedRole.name,
                staff_name: this.selectedStaff.name,
                staff_id: this.selectedStaff.id,
            })
            this.roleList = [];
            this.staffList = [];
            this.selectedDepartment = null;
            this.selectedRole = null;
            this.selectedStaff = null;
        },
        async removeSalaryBatch(index){
            if(this.salaryBatchList[index].id){
                console.log('id shi')
                let response = await deleteApiData({ url: `/api/hr/salary_batch_staffs/` + this.salaryBatchList[index].id, token: this.getToken() });
                if (response.success) {
                    this.salaryBatchList.splice(index, 1);
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
                this.salaryBatchList.splice(index, 1);
            }
            // this.salaryBatchList.splice(index, 1);
        },

        btnClickedCreateSalaryBatch(){
            if(this.salaryBatchList.length < 1){
                this.alertValidationMessage(`Salary Batch list`);
                return 1;
            }
            else if(!this.batch_name){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else if(!this.date){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            else{
                this.createSalaryBatch();
            }
        },
        async createSalaryBatch(){
            let staff_ids = [];
            this.salaryBatchList.forEach(batch => {
                if(batch.id){
                    staff_ids.push({
                        id : batch.id,
                        staff_id : batch.staff_id
                    })
                }
                else{
                    staff_ids.push({
                        staff_id : batch.staff_id
                    })
                }
                
            });
            console.log(staff_ids)
            let formData = new FormData();
            formData.append('name',this.batch_name);
            formData.append('day_of_monthly',this.date);
            formData.append('salary_batch_staffs', JSON.stringify(staff_ids));
            let response = await postApiData({url:`/api/hr/salary_batches/` + this.salaryBatchId, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/salary_batch`);
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
        this.getSalaryBatchDetail();
        this.getDepartmentList();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
