<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Account Payable Balances
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
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Code
                                </th>
                                <th scope="col" class="">
                                    Other Payable
                                </th>
                                <th scope="col" class="">
                                    Opening Balanes
                                </th>
                                <th scope="col" class="">
                                    Addition
                                </th>
                                <th scope="col" class="">
                                    Cash Paid
                                </th>
                                <th scope="col" class="">
                                    Rebate / Adjust
                                </th>
                                <th scope="col" class="">
                                    Closing Balances
                                </th>
                                <th scope="col" class="">
                                    Location
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="" v-for="(ap,index) in apBalanceList.payable_accounts" :key="index">
                                <td class="">
                                    {{ index+1 }}
                                </td>
                                <td class="">
                                    {{ ap.account_id }}
                                </td>
                                <td class="">
                                    {{ ap.account_name }}
                                </td>
                                <td class="">
                                    {{ ap.opening_balance }}
                                </td>
                                <td class="">
                                    {{ ap.total_addition }}
                                </td>
                                <td class="">
                                    {{ ap.total_settlement }}
                                </td>
                                <td class="">
                                    --
                                </td>
                                <td class="">
                                    {{ ap.closing_balance }}
                                </td>
                                <td class="">
                                    --
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"  class="text-right bg-blue-50">
                                    Total
                                </td>
                                <td class="bg-blue-50">
                                    {{ apBalanceList.total_opening_balance }} 
                                </td> 
                                <td class="bg-blue-50">
                                    {{ apBalanceList.total_addition }} 
                                </td>
                                <td class="bg-blue-50">
                                    {{ apBalanceList.total_settlement }} 
                                </td>
                                <td class="bg-blue-50"></td>
                                <td class="bg-blue-50">
                                    {{ apBalanceList.total_closing_balance }} 
                                </td>
                                <td class=" bg-blue-50"></td>
                            </tr>
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
            apBalanceList: [],
            staffList:[],
            selectedStaff:null,
            fromDate:null,
            toDate:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/get_account_payable_balance',
            url_from:'',
            url_to:'',
            deleteId:null,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getApBalanceList(pageNumber) {
            let url = this.url + this.url_from + this.url_to;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.apBalanceList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
            }
        },
        fromDateChanged(){
            this.url_staff = '';
            this.url_from = '?from_date=' + this.fromDate
            this.getApBalanceList();
        },
        toDateChanged(){
            this.url_to = '&to_date=' + this.toDate
            this.getApBalanceList();
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
        this.getApBalanceList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
