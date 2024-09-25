<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Advanced
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <!-- <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label> -->
                <input type="month" class="input-ui  mr-2 h-8" v-model="selectedMonth" @change="monthChange()">
            </div>
            <div class="flex justify-end flex-col">

                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button>
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
                                    Staff Name
                                </th>
                                <th scope="col" class="">
                                    Opening
                                </th>
                                <th scope="col" class="">
                                    Addition
                                </th>
                                <th scope="col" class="">
                                    settlement
                                </th>
                                <th scope="col" class="">
                                    Balance
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(advance, index) in advancedList" :key="index">
                                <a :href="'/advanced/' + advance.staff_id + '/detail'" class="contents">
                                    <tr class="">
                                        <td class=" ">
                                            {{ index+1 }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            {{ advance.staff_name }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            {{ advance.opening_balance }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            {{ advance.addition }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            {{ advance.settlement }}
                                        </td>
                                        <td class="whitespace-nowrap">
                                            {{ advance.closing_balance }}
                                        </td>
                                    </tr>
                                </a>

                            </div>
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
                                Create Advanced
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
                                <label class="label-form mb-3">Department</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedDepartment"
                                    @change="getStaffList()" >
                                    <option class="text-sm" :value="department" v-for="(department,index) in departmentList" :key="index">
                                        {{ department.name }}
                                    </option>

                                </select>
                            </div>
                            <div class="mb-4">

                                <label class="label-form mb-3">Staff</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedStaff">
                                    <option class="text-sm" :value="staff" v-for="(staff,index) in staffList" :key="index">
                                        {{ staff.name }}
                                    </option>

                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Type</label>
                                <select class="text-sm border capitalize border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedType">
                                    <option class="text-sm capitalize" :value="type" v-for="(type,index) in typeList" :key="index">
                                        {{ type }}
                                    </option>

                                </select>
                            </div>




                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Amount
                                </label>
                                <input type="text" placeholder="Amount" v-model="amounts" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Cashbook</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedCashbook">
                                    <option class="text-sm" :value="cashbook" v-for="(cashbook,index) in cashbookList" :key="index">
                                        {{ cashbook.name }}
                                    </option>

                                </select>
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

                advancedList:[],

                departmentList:[],
                staffList:[],
                typeList:['addition','settlement'],
                cashbookList:[],

                selectedDepartment:null,
                selectedStaff:null,
                selectedType:null,
                selectedCashbook:null,
                amounts:null,

                selectedNewMonth:null,
                currentMonth:null,

                currentPage: 0,
                perPage: 0,
                lastPage: 0,
                totalData:0,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            monthChange(){
                let selectedNewMonth = this.selectedMonth.slice(5,7);
                this.getAdvanceList(selectedNewMonth);
            },
            async getAdvanceList(pageNumber){
                const response = await getApiData({ url: '/api/staff_balances?month='+this.selectedNewMonth + '&page='+pageNumber, token: this.getToken() });
                if(response.data){
                    this.advancedList = response.data;
                    this.lastPage = response.data.last_page;
                    this.currentPage = pageNumber;
                    this.perPage = response.data.per_page;
                    this.totalData = response.data.total;
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
                const response = await getApiData({ url: '/api/departments/' + this.selectedDepartment.id + '/staffs', token: this.getToken() });
                if(response.data){
                    this.staffList = response.data;
                }
            },
            async getCashbookList(){
                const response = await getApiData({ url: '/api/get_cash_account' , token: this.getToken()});
                if(response.data){
                    this.cashbookList = response.data;
                }
            },

            btnClickedCreateAdvance(){
                this.createAdvance();
            },

            async createAdvance()
            {
                let formData = new FormData();
                formData.append('staff_id', this.selectedStaff.id);
                formData.append('type', this.selectedType);
                formData.append('amount', this.amounts);
                formData.append('cash_account_id', this.selectedCashbook.id);
                let response = await postApiData({url: '/api/staff_advances', form_data: formData, token: this.getToken()});
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
            const date = new Date();
            this.selectedNewMonth = date.getMonth() + 1;
            this.getAdvanceList(1);
            this.getDepartmentList();
            this.getCashbookList();


        }

    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
