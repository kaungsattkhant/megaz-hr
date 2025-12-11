<template>
   
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Advances
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex">
                    <!-- <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search">

                        <i class="fal fa-search"></i>
                    </label> -->
                    <!-- <input type="month" class="input-ui  mr-2 h-8" v-model="selectedMonth" @change="monthChange()"> -->
                </div>
                <div class="flex justify-end flex-col">

                    <button type="button" v-show="feature.includes('staff-balance.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
                        Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Advance Ref No
                                </th>
                                <th scope="col" class="">
                                    Staff Name
                                </th>
                                <th scope="col" class="">
                                    Left Month
                                </th>
                                <th scope="col" class="">
                                    Monthly advanced
                                </th>
                                <th scope="col" class="">
                                    Advanced Balance
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(advance, index) in primaryList" :key="index">
                                
                                    <tr class="">
                                        <td class=" ">
                                            {{ perPage * (currentPage - 1) + (++index) }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            <a :href="'/advances/' + advance.id + '/detail'" class=" underline underline-offset-2 text-blue-600 text-sm">
                                                {{ advance.advance_ref_no }}
                                            </a>
                                        </td>
                                        <td class="whitespace-nowrap">
                                            {{ advance.staff.name }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            {{ advance.remaining_months }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            {{ advance.current_deduction_amount }}
                                        </td>
                                        
                                        <td class="whitespace-nowrap">
                                            {{ advance.remaining_amount }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            <!-- <button data-te-toggle="modal" data-te-target="#advance_payment_modal" id="edit-btn"
                                                class="pr-3" @click="btnClickedAddMonthlyAmount(advance, index)">
                                                <i class="fal fa-pen"></i>
                                            </button> -->
                                        
                                        </td>
                                    </tr>

                            </div>
                            <tr class=" !text-center" v-if="primaryList.length < 1 && !loading">
                                <td class="" colspan="6">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1"
                                @click="getArList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage"
                                @click="getArList(currentPage + 1)"> »</button>
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
                    <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Create Advances
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label class="label-form mb-3">Staff</label>
                                <multiselect
                                    v-model="selectedStaff"
                                    :options="staffList"
                                    :multiple="false"
                                    :close-on-select="true"
                                    :clear-on-select="false"
                                    :preserve-search="true"
                                    placeholder="Select Staff"
                                    label="name"
                                    track-by="id"
                                    :preselect-first="false">
                                    <template #selection="{ values, search, isOpen }">
                                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                                        Staff selected</span>
                                    </template>
                                </multiselect>
                                <!-- <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedStaff">
                                    <option class="text-sm" :value="staff" v-for="(staff,index) in staffList" :key="index">
                                        {{ staff.name }}
                                    </option>

                                </select> -->
                            </div>

                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Loan
                                </label>
                                <input type="number" placeholder="Loan" v-model="loan" class="input-ui" @input="getMonthlyAmount">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Month
                                </label>
                                <input type="number" placeholder="Month" v-model="month" class="input-ui" @input="getMonthlyAmount">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Monthly Amount
                                </label>
                                <input type="number" placeholder="Monthly Amount" v-model="monthly_amount" class="input-ui" disabled>
                            </div>


                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" @click="btnClickedCreateAdvance()"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="advance_payment_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Add Monthly Amount
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_monthly_amount_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Month
                                </label>
                                <input type="date" class="input-ui  mr-2 h-8" v-model="selected_month"> 
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Monthly Amount
                                </label>
                                <input type="number" placeholder="Monthly Amount" v-model="advance_monthly_amount" class="input-ui">
                            </div>
                            

                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" @click="btnClickedAddAdvancePayment()"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import Multiselect from 'vue-multiselect';
    import TableSkeleton from "../Common/TableSkeleton.vue";

    export default {
        components: {
            TableSkeleton,
            Multiselect
        },
        data() {
            return {

                primaryList:[],

                departmentList:[],
                staffList:[],
                typeList:['addition','settlement'],
                cashbookList:[],

                selectedDepartment:null,
                selectedStaff:null,
                loan: 0,
                month: 0,
                monthly_amount: 0,

                selectedType:null,
                selectedCashbook:null,
                amounts:null,

                selectedNewMonth:null,
                currentMonth:null,

                currentPage: 0,
                perPage: 0,
                lastPage: 0,
                totalData:0,

                feature: this.getFeature(),
                loading: false,

                advance_id: null,
                advance_monthly_amount: null,
            };
        },

        methods: {
            ...mapGetters(['getToken', 'getFeature']),

            monthChange(){
                let selectedNewMonth = this.selectedMonth.slice(5,7);
                this.getAdvanceList(selectedNewMonth);
            },
            async getAdvanceList(pageNumber){
                this.loading = true;
                const response = await getApiData({ url: '/api/advances' + '?page='+pageNumber, token: this.getToken() });
                if(response.message.data){
                    this.loading = false;
                    this.primaryList = response.message.data;
                    this.lastPage = response.message.last_page;
                    this.currentPage = pageNumber;
                    this.perPage = response.message.per_page;
                    this.totalData = response.message.total;
                }
            },
            async getDepartmentList(){
                const response = await getApiData({ url: '/api/departments' , token: this.getToken() });
                if(response.data){
                    this.departmentList = response.data;
                    console.log('department')
                }
            },
            async getStaffList(){
                let url = '/api/staffs'
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.staffList = response.data;
                }
            },
            // async getStaffList(){
            //     const response = await getApiData({ url: '/api/departments/' + this.selectedDepartment.id + '/staffs', token: this.getToken() });
            //     if(response.data){
            //         this.staffList = response.data;
            //     }
            // },
            getMonthlyAmount(){
                const loan = Number(this.loan)
                const month = Number(this.month)

                if (!month || month === 0) {
                    this.monthly_amount = 0 
                } else {
                    this.monthly_amount = loan / month
                }
                console.log('getmonthlyamount')
            },
            btnClickedCreateAdvance(){
                this.createAdvance();
            },
            
            async createAdvance()
            {
                this.monthly_amount = this.loan / this.month
                let formData = new FormData();
                formData.append('staff_id', this.selectedStaff.id);
                formData.append('advance_amount', this.loan);
                formData.append('total_months', this.month);
                formData.append('remaining_amount', this.loan);
                formData.append('remaining_months', this.month);
                formData.append('current_deduction_amount', this.monthly_amount);
                let response = await postApiData({url: '/api/advances', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getAdvanceList(1);
                    this.closeAndClearModal();
                }
                else{
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            },

            btnClickedAddMonthlyAmount(advance){
                this.advance_id = advance;
                this.advance_monthly_amount = null;
            },
            btnClickedAddAdvancePayment(){
                if(!this.advance_monthly_amount){
                    this.alertValidationMessage(`Amount`);
                    return 1;
                }
                else{
                    this.addAdvancePayment();
                }
            },
            async addAdvancePayment(){
                let formData = new FormData();
                formData.append('paid_amount', this.advance_monthly_amount);
                formData.append('advance_id', this.advance_id.id);
                formData.append('payment_month', this.selected_month);
                let response = await postApiData({url: '/api/advances/create_advance_payments', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getAdvanceList(1);
                    document.getElementById('close_monthly_amount_modal').click();
                }
                else{
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            } ,
            closeAndClearModal(){
                this.selectedDepartment = null;
                this.amounts = null;
                this.selectedStaff = null;
                this.selectedType = null;
                this.selectedCashbook = null;
                document.getElementById("close").click();
            }

        },
        mounted()
        {

            initTE({ Modal,Select, Ripple });
        },
        created(){
            this.getStaffList();
            this.getAdvanceList(1);
            this.getDepartmentList();


        }

    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
