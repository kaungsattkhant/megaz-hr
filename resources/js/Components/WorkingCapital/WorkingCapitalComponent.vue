<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Working Capital
        </p>
    </div>

    <div class="mt-4 bg-white">
        <div class="btn-container pt-10">
            <notifications position="top center" />
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>
                <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
            </div>
            <div class="flex pr-0 gap-x-4">
                <div class="relative">
                    <label for="search" class="border border-gray-200 rounded bg-white text-xs mx-2 px-2 py-2 absolute left-0 ml-0 -top-[90%] border-b-0"> From </label>
                    <input type="date" v-model="fromDate" class="search-input rounded " @change="fromDateChanged()">
                </div>

                <div class="relative">
                    <label for="search" class="border border-gray-200 rounded bg-white text-xs mx-2 px-2 py-2 absolute left-0 ml-0 -top-[90%] border-b-0"> To </label>
                    <input type="date" v-model="toDate" class="search-input rounded" @change="toDateChanged()">
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <!-- <thead>
                            <tr> -->
                                <!-- <th scope="col" class="">
                                    Schedule of Inventory Held
                                </th>
                                <th scope="col" class="">
                                    Cash & Bank Balances
                                </th>
                                <th scope="col" class="">
                                    Prepaid Balances
                                </th>
                                <th scope="col" class="">
                                    Receivable Balanes (Agent)
                                </th>
                                <th scope="col" class="">
                                    Staff Loan
                                </th>
                                <th scope="col" class="">
                                    Other Receivables
                                </th>
                                <th scope="col" class="">
                                    Closing Stock
                                </th>
                                <th scope="col" class="">
                                    
                                </th> -->
                            <!-- </tr>
                        </thead> -->
                        <tbody class="!rounded-none">
                            <tr class="bg-blue-50 !rounded-none">
                                <td scope="col" class=" !font-semibold !rounded-none">
                                    Current Asset
                                </td>
                                <td v-if="workingCapitalList.current_asset" scope="col" class=" !rounded-none" v-for="(month) in workingCapitalList.current_asset.inventory_held">
                                    {{ month.month }}
                                </td>
                            </tr>
                            <!-- looping start -->
                            <div class="contents" v-if="workingCapitalList.current_asset">
                                <tr class="">
                                    <td class=" font-medium ">
                                        Schedule of Inventory Held
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(inv,index) in workingCapitalList.current_asset.inventory_held" :key="index">
                                        {{ inv.value }}
                                    </td>   
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        Cash & Bank Balances 
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(cash,index) in workingCapitalList.current_asset.cashbook" :key="index">
                                        {{ cash.value }}
                                    </td>   
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        Prepaid Balances
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(pre_paid,index) in workingCapitalList.current_asset.prepaid" :key="index">
                                        {{ pre_paid.value }}
                                    </td>   
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        Receivable Balanes (Agent)
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(receivable,index) in workingCapitalList.current_asset.receivable_debtor" :key="index">
                                        {{ receivable.value }}
                                    </td>   
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        Staff Loan
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(loan,index) in workingCapitalList.current_asset.staff_loan" :key="index">
                                        {{ loan.value }}
                                    </td>   
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        Other Receivables
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(other_receiv,index) in workingCapitalList.current_asset.other_receivable" :key="index">
                                        {{ other_receiv.value }}
                                    </td>   
                                </tr>
                                <!-- <tr class="">
                                    <td class=" font-medium ">
                                        Closing Stock
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(inv,index) in workingCapitalList.current_asset.closing_stock" :key="index">
                                        {{ inv.value }}
                                    </td>   
                                </tr> -->
                            </div>





                            <tr class=" bg-blue-50 !rounded-none">
                                <td scope="col" class=" !font-semibold !rounded-none">
                                    Current Liabilities
                                </td>
                                <td v-if="workingCapitalList.current_liabilities" scope="col" class="!rounded-none" v-for="(month) in workingCapitalList.current_liabilities.creditor">
                                    {{ month.month_name }}
                                </td>
                            </tr>
                            <!-- looping start -->
                            <div class="contents" v-if="workingCapitalList.current_liabilities">
                                <tr class="">
                                    <td class=" font-medium ">
                                        Creditor Balances
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(item,index) in workingCapitalList.current_liabilities.creditor" :key="index">
                                        {{ item.value }}
                                    </td>   
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        Other Payable Balances 
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(item,index) in workingCapitalList.current_liabilities.other_payable" :key="index">
                                        {{ item.value }}
                                    </td>   
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        Deposit Balances
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(item,index) in workingCapitalList.current_liabilities.deposit" :key="index">
                                        {{ item.value }}
                                    </td>   
                                </tr>
                                <!-- <tr class="">
                                    <td class=" font-medium ">
                                        Accrued balances
                                    </td>
                                    <td class="whitespace-nowrap" v-for="(receivable,index) in workingCapitalList.current_liabilities.receivable_debtor" :key="index">
                                        {{ receivable.value }}
                                    </td>   
                                </tr> -->
                            </div>
                            
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getOkrList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOkrList(currentPage + 1)">
                                »</button>
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
            workingCapitalList: [],
            staffList:[],
            selectedStaff:null,
            fromDate:null,
            toDate:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/working_capital',
            url_from:'',
            url_to:'',
            deleteId:null,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getWorkingCapitalList(pageNumber) {
            let url = this.url + this.url_from + this.url_to;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.workingCapitalList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
            }
        },
        fromDateChanged(){
            this.url_staff = '';
            this.url_from = '?from_date=' + this.fromDate
            this.getWorkingCapitalList();
        },
        toDateChanged(){
            this.url_to = '&to_date=' + this.toDate
            this.getWorkingCapitalList();
        },
        
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
        this.getWorkingCapitalList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
