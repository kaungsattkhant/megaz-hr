<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Calculate Salary
        </p>
    </div>
    <div class="mt-4 bg-white ">
        <div class="border-b py-3 px-8 mb-8">
            <notifications position="top center" />
            <div class="grid grid-cols-4 pr-0 gap-x-4">
                
                <div class="mb-4">
                    <label for="" class="label-form mb-3">
                        From
                    </label>
                    <input type="date" v-model="fromDate" class="input-ui mb-0">
                </div>
                <div class="mb-4">
                    <label for="" class="label-form mb-3">
                        To
                    </label>
                    <input type="date" v-model="toDate" class="input-ui mb-0">
                </div>
                <div class="mb-4">
                    <label for="" class="label-form mb-3">
                        Batch
                    </label>
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Role"
                            data-te-select-filter="true" name="" id="" v-model="selectedBatch" class="input-ui !text-black text-sm">
                            <option :value="batch" v-for="(batch, index) in batchList"
                                :key="index"> {{ batch.name }} </option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="" class="label-form mb-3">
                        &nbsp;
                    </label>
                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 " @click="getSalaryList">
                        Add New
                    </button>
                </div>
                
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
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Department
                                </th>
                                <th scope="col" class="">
                                    Role
                                </th>
                                <th scope="col" class="">
                                    Salary
                                </th>
                                <th scope="col" class="">
                                    Allowance
                                </th>
                                <th scope="col" class="">
                                    Overtime
                                </th>
                                <th scope="col" class="">
                                    Net Salary
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(salary, index) in salaryList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.staff_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.department_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.role_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.basic_salary }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.total_allowance }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.overtime_pay }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ salary.net_salary }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#add_allowance_modal" id="edit-btn"
                                            class="pr-3" @click="btnClickedAddAllowanceAndDeduction(salary, index)">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                    <button data-te-toggle="modal" data-te-target="#add_allowance_modal" id="edit-btn"
                        class="pr-3 opacity-0 w-0 h-0">
                    </button>

                    <!-- pagination -->
                    <!-- <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getAllowanceList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getAllowanceList(currentPage + 1)">
                                »</button>
                        </div>
                    </div> -->
                
                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 " @click="btnCreateSalaryCalculate">
                        Publish
                    </button>
                </div>
            </div>
        </div>



        <!-- add allowance modal -->
        
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_allowance_modal" tabindex="-1" aria-labelledby="add_allowanceLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_allowanceLabel">
                            Choose Allowance / Deduction
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            id="close_add_allowance" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Type
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Type" @change="selectedTypeChange()"
                                    data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui !text-black text-sm">
                                    <option value="allowance"> Allowance </option>
                                    <option value="deduction"> Deduction </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Role"
                                    data-te-select-filter="true" name="" id="" v-model="selectedAllowance" class="input-ui !text-black text-sm">
                                    <option :value="allowance" v-for="(allowance, index) in allowanceList"
                                        :key="index"> {{ allowance.name }} </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnAddAllowanceOrDeduction()"
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
import Multiselect from 'vue-multiselect';
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            unChangedSalaryList: [],
            salaryList: [],

            batchList: [],

            fromDate:null,
            toDate: null,
            selectedBatch: null,
            
            allowanceList: [],
            selectedType: null,
            selectedAllowance: null,

            salaryDetail: null,
            salaryIndex: null,

            pay_slip:[],
            test: [],
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getBatchList() {
            let url = '/api/hr/salary_batches';
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.batchList = response.data.data;
            }
        },
        async getSalaryList(){
            if(!this.fromDate){
                this.alertValidationMessage(`From Date `);
                return 1;
            }
            else if(!this.toDate){
                this.alertValidationMessage(`To Date`);
                return 1;
            }
            else if(!this.selectedBatch){
                this.alertValidationMessage(`Batch`);
                return 1;
            }
            else{
                let url = '/api/hr/calculate_salary?salary_batch_id=' + this.selectedBatch.id + '&from_date=' + this.fromDate + '&to_date=' + this.toDate;
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.unChangedSalaryList = response.data.data;
                    this.salaryList = response.data.data;
                    this.salaryList.forEach(sa => {
                        sa.total_allowance = sa.allowance;
                        sa.added_allowance_amount = 0;
                        sa.added_deduction_amount = 0;
                        sa.overtime = sa.overtime_pay;
                        sa.net_salary = sa.netSalary;
                        sa.added_allowances = [];
                        sa.added_deductions = [];
                    });
                }
            }
            
        },
        btnClickedAddAllowanceAndDeduction(salary,index){
            this.salaryDetail = salary
            this.salaryIndex = index;
            this.selectedType = null;
            this.allowanceList = [];
            this.selectedAllowance = null;
        },
        async selectedTypeChange(){
            let url = '/api/hr/allowance_types?role_id=' + this.salaryDetail.role_id + '&type=' + this.selectedType;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.allowanceList = response.data;
            }
            else {
                console.log(response.message)
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        btnAddAllowanceOrDeduction(){
            if(!this.selectedType){
                this.alertValidationMessage(`Type `);
                return 1;
            }
            else if(!this.selectedAllowance){
                this.alertValidationMessage(`Allowance Or Deduction`);
                return 1;
            }
            else{
                if(this.selectedType == 'allowance'){
                    this.salaryList[this.salaryIndex].total_allowance += this.selectedAllowance.amount
                    this.salaryList[this.salaryIndex].net_salary += this.selectedAllowance.amount
                    this.salaryList[this.salaryIndex].added_allowance_amount += this.selectedAllowance.amount
                    this.salaryList[this.salaryIndex].added_allowances.push({
                        id: this.selectedAllowance.id,
                        amount: this.selectedAllowance.amount
                    })
                }
                else{
                    this.salaryList[this.salaryIndex].total_allowance -= this.selectedAllowance.amount
                    this.salaryList[this.salaryIndex].net_salary -= this.selectedAllowance.amount
                    this.salaryList[this.salaryIndex].added_deduction_amount += this.selectedAllowance.amount
                    this.salaryList[this.salaryIndex].added_deductions.push({
                        id: this.selectedAllowance.id,
                        amount: this.selectedAllowance.amount
                    })
                }
                document.getElementById('close_add_allowance').click();
            }
        },
        
        btnCreateSalaryCalculate(){
            // if(!this.selectedRole){
            //     this.alertValidationMessage(`Role `);
            //     return 1;
            // }
            // else if(!this.name){
            //     this.alertValidationMessage(`Name`);
            //     return 1;
            // }
            // else{
            //     this.createSalaryCalculate();
            // }
            this.createSalaryCalculate();

            // tset = [
            //         {
            //             "staff_id": 1,
            //             "salary_batch_id": 1,
            //             "salary_id": 1,
            //             "basic_salary": 45454.55,
            //             "allowance": 80000,
            //             "added_allowance_amount":5000,
            //             "added_deduction_amount":5000,
            //             "added_allowances": [
            //                 {
            //                 "id": 1,
            //                 "amount": 5000
            //                 }
            //             ],
            //             "added_deductions": [
            //                 {
            //                 "id": 3,
            //                 "amount": 5000
            //                 }
            //             ],
            //             "total_allowance": 80000,
            //             "overtime": 0,
            //             "net_salary": 125454.55
            //         },
            //         {
            //             "staff_id": 2,
            //             "salary_batch_id": 1,
            //             "salary_id": 2,
            //             "basic_salary": 38461.54,
            //             "allowance": 80000,
            //             "added_allowance_amount":20000,
            //             "added_allowances": [
            //                 {
            //                 "id": 4,
            //                 "amount": 20000
            //                 }
            //             ],
            //             "total_allowance": 100000,
            //             "overtime": 0,
            //             "net_salary": 138461.54
            //         }
            //     ]

        },
        async createSalaryCalculate(){
            let pay_slip = [];
            this.salaryList.forEach(item => 
                pay_slip.push({
                    staff_id: item.staff_id,
                    salary_batch_id: item.salary_batch_id,
                    salary_id: item.salary_id,
                    basic_salary: item.basic_salary,
                    allowance: item.allowance,
                    added_allowance_amount: item.added_allowance_amount,
                    added_deduction_amount: item.added_deduction_amount,
                    added_allowances: item.added_allowances,
                    added_deductions: item.added_deductions,
                    total_allowance: item.total_allowance,
                    overtime: item.overtime,
                    net_salary: item.net_salary,
                })
            )
            this.test = pay_slip
            let formData = new FormData();
            formData.append('pay_slips',JSON.stringify(pay_slip));
            let response = await postApiData({url:`/api/hr/pay_slips`, form_data:formData, token:this.getToken()})
            if(response.success){
                // this.getAllowanceList();
                // this.clearCreateModal();
                // document.getElementById('close_create_modal').click();
                window.location.replace('/pay_slip')
            }
        },
        // clearCreateModal(){
        //     this.selectedDepartment = null;
        //     this.roleList = [];
        //     this.selectedRole = null;
        //     this.name = null;
        //     this.selectedType = null;
        //     this.amount = null
        // },

        // deleteBtnClicked(id) {
        //     this.deleteId = id;
        // },
        // async deleteItem() {
        //     let response = await deleteApiData({ url: `/api/hr/overtime_fees/` + this.deleteId, token: this.getToken() });
        //     if (response.success) {
        //         this.getAllowanceList(1);
        //     }
        //     else {
        //         this.$notify({
        //             title: `Input validation`,
        //             text: response.message,
        //             type: "warn"
        //         });
        //     }
        // },



        alertValidationMessage(field) {
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to provide ${field}, please try again`,
                    type: 'warn'
                });
            },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });
        
    },
    created() {
        this.getBatchList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>