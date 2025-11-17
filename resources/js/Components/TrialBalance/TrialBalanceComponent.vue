<template>

    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Trial Balance
                </p>
            </div>
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
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    No
                                </th>
                                <th scope="col" class=" text-left">
                                    A kyaung a yar
                                </th>
                                <th scope="col" class="">
                                    Dr 
                                </th>
                                <th scope="col" class="">
                                    Cr
                                </th>
                            </tr>
                        </thead>
                        <tbody class="!rounded-none">
                            <!-- <tr class="bg-blue-50 !rounded-none">
                                <td></td>
                                <td scope="col" class=" !font-semibold !rounded-none text-left">
                                    Credit Balances
                                </td>
                                <td colspan="2"></td>
                            </tr> -->
                            <!-- looping start -->
                            <div class="contents" v-if="primaryList.credit_balance">
                                <tr class="bg-gray-100 !rounded-none">
                                    <td></td>
                                    <td scope="col" class=" !font-semibold !rounded-none text-left">
                                        Credit Balances
                                    </td>
                                    <td colspan="2"></td> 
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.credit_balance[0]" :key="index">
                                    <td class=" font-medium ">
                                        {{ index === 0 ? '1' : '' }}
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name }}
                                    </td>   
                                    <td></td>
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.credit_balance[1]" :key="index">
                                    <td class=" font-medium ">
                                        {{ index === 0 ? '2' : '' }}
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name }}
                                    </td>   
                                    <td></td>
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.credit_balance[2]" :key="index">
                                    <td class=" font-medium ">
                                        {{ index === 0 ? '3' : '' }}
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name }}
                                    </td>   
                                    <td></td>
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        4
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        Long Term Liabilities
                                    </td>   
                                    <td></td>
                                    <td class="whitespace-nowrap">
                                        
                                    </td>
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.credit_balance[3]" :key="index">
                                    <td class=" font-medium ">
                                        
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name }}
                                    </td>   
                                    <td></td>
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        5
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        Current Liabilities
                                    </td>   
                                    <td></td>
                                    <td class="whitespace-nowrap">
                                        
                                    </td>
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.credit_balance[4]" :key="index">
                                    <td class=" font-medium ">
                                        
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name }}
                                    </td>   
                                    <td></td>
                                    <td class="whitespace-nowrap">
                                        {{ item.total_amount }}
                                    </td>
                                </tr>
                            </div>








                            <div class="contents" v-if="primaryList.debit_balance">
                                <tr class="bg-gray-100 !rounded-none">
                                    <td></td>
                                    <td scope="col" class=" !font-semibold !rounded-none text-left">
                                        Debit Balances
                                    </td>
                                    <td colspan="2"></td> 
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.debit_balance[0]" :key="index">
                                    <td class=" font-medium ">
                                        {{ index === 0 ? '1' : '' }}
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name }}
                                    </td>   
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.debit_balance[1]" :key="index">
                                    <td class=" font-medium ">
                                        {{ index === 0 ? '2' : '' }}
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name }}
                                    </td>   
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        3
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        Current Assets
                                    </td>   
                                    <td class="whitespace-nowrap">
                                        
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.debit_balance[2]" :key="index">
                                    <td class=" font-medium ">
                                        
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name }}
                                    </td>   
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        4
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        Cost & Expenses
                                    </td>   
                                    <td class="whitespace-nowrap">
                                        
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.debit_balance[3]" :key="index">
                                    <td class=" font-medium ">
                                        
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name }}
                                    </td>   
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="">
                                    <td class=" font-medium ">
                                        5
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        Fixed Overhead
                                    </td>   
                                    <td class="whitespace-nowrap">
                                        
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="" v-for="(item,index) in primaryList.debit_balance[4]" :key="index">
                                    <td class=" font-medium ">
                                        
                                    </td>
                                    <td class="whitespace-nowrap text-left">
                                        {{ item.name ? item.name : item.account_name }}
                                    </td>   
                                    <td class="whitespace-nowrap">
                                        {{ item.amount ? item.amount : item.total_amount }}
                                    </td>
                                    <td></td>
                                </tr>
                            </div>
                            <tr class="">
                                <td></td>
                                <td class=" font-medium ">
                                    Tiral Balances
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ primaryList.debit_total_balance }}
                                </td>   
                                <td class="whitespace-nowrap">
                                    {{ primaryList.credit_total_balance }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getPrimaryList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getPrimaryList(currentPage + 1)">
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
            primaryList: [],
            fromDate:null,
            toDate:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/trial_balance',
            url_from:'',
            url_to:'',
            deleteId:null,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getPrimaryList(pageNumber) {
            let url = this.url + this.url_from + this.url_to;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.primaryList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
            }
        },
        fromDateChanged(){
            this.url_staff = '';
            this.url_from = '?from_date=' + this.fromDate
            this.getPrimaryList();
        },
        toDateChanged(){
            this.url_to = '&to_date=' + this.toDate
            this.getPrimaryList();
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
        this.getPrimaryList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
