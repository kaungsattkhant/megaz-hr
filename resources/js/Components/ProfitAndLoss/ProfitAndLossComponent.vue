<template>

    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Profit And Loss
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
                                <th scope="col" class="" colspan="2">
                                    No
                                </th>
                                <th scope="col" class="">
                                    Particular
                                </th>
                                <th scope="col" class="">
                                    Restarant P&L
                                </th>
                                <th scope="col" class="">
                                    KTV P&L
                                </th>
                                <th scope="col" class="">
                                    Consolidated P&L
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody class="!rounded-none">
                            <div class="contents">
                                <tr class="bg-blue-50 !rounded-none">
                                    <td  class=" !font-semibold !rounded-none">
                                        1
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        Operating Income
                                    </td>
                                    <td  class=" !rounded-none" colspan="3">
                                        
                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-for="(item,index) in primaryList[0]?.data">
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ index+1 }}
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ item.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ item.total }} 

                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-if="primaryList[0]?.total">
                                    <td  class=" !font-semibold !rounded-none">
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none text-right">
                                        {{ primaryList[0].total.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[0].total.total_restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[0].total.total_ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ primaryList[0].total.total }} 

                                    </td>
                                </tr>
                            </div>


                            <div class="contents">
                                <tr class="bg-blue-50 !rounded-none">
                                    <td  class=" !font-semibold !rounded-none">
                                        2
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        Indirect Cost(Overhead)
                                    </td>
                                    <td  class=" !rounded-none" colspan="3">
                                        
                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-for="(item,index) in primaryList[1]?.data">
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ index+1 }}
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ item.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ item.total }} 

                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-if="primaryList[1]?.total">
                                    <td  class=" !font-semibold !rounded-none">
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none text-right">
                                        {{ primaryList[1].total.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[1].total.total_restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[1].total.total_ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ primaryList[1].total.total }} 

                                    </td>
                                </tr>
                            </div>

                            <div class="contents">
                                <tr class="bg-blue-50 !rounded-none">
                                    <td  class=" !font-semibold !rounded-none">
                                        3
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        Fixed Cost
                                    </td>
                                    <td  class=" !rounded-none" colspan="3">
                                        
                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-for="(item,index) in primaryList[2]?.data">
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ index+1 }}
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ item.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ item.total }} 

                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-if="primaryList[2]?.total">
                                    <td  class=" !font-semibold !rounded-none">
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none text-right">
                                        {{ primaryList[2].total.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[2].total.total_restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[2].total.total_ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ primaryList[2].total.total }} 

                                    </td>
                                </tr>
                            </div>


                            <div class="contents">
                                <tr class="bg-blue-50 !rounded-none">
                                    <td  class=" !font-semibold !rounded-none">
                                        4
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        Net Profit
                                    </td>
                                    <td  class=" !rounded-none" colspan="3">
                                        
                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-for="(item,index) in primaryList[3]?.data">
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ index+1 }}
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ item.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ item.total }} 

                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-if="primaryList[3]?.total">
                                    <td  class=" !font-semibold !rounded-none">
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none text-right">
                                        {{ primaryList[3].total.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[3].total.total_restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[3].total.total_ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ primaryList[3].total.total }} 

                                    </td>
                                </tr>
                            </div>

                            <div class="contents">
                                <tr class="bg-blue-50 !rounded-none">
                                    <td  class=" !font-semibold !rounded-none">
                                        5
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        Interest & Taxes
                                    </td>
                                    <td  class=" !rounded-none" colspan="3">
                                        
                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-for="(item,index) in primaryList[4]?.data">
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ index+1 }}
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ item.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ item.total }} 

                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-if="primaryList[4]?.total">
                                    <td  class=" !font-semibold !rounded-none">
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none text-right">
                                        {{ primaryList[4].total.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[4].total.total_restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[4].total.total_ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ primaryList[4].total.total }} 

                                    </td>
                                </tr>
                            </div>

                            <div class="contents">
                                <tr class="bg-blue-50 !rounded-none">
                                    <td  class=" !font-semibold !rounded-none">
                                        6
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        Net Profit After Interest & Tax
                                    </td>
                                    <td  class=" !rounded-none" colspan="3">
                                        
                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-for="(item,index) in primaryList[5]?.data">
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ index+1 }}
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        {{ item.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ item.ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ item.total }} 

                                    </td>
                                </tr>
                                <tr class=" !rounded-none" v-if="primaryList[5]?.total">
                                    <td  class=" !font-semibold !rounded-none">
                                    </td>
                                    <td  class=" !font-semibold !rounded-none">
                                        
                                    </td>
                                    <td  class=" !font-semibold !rounded-none text-right">
                                        {{ primaryList[5].total.name }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[5].total.total_restaurant_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        {{ primaryList[5].total.total_ktv_pl }}
                                    </td>
                                    <td  class=" !rounded-none">
                                        <!-- consolidate P&L  -->
                                        {{ primaryList[5].total.total }} 

                                    </td>
                                </tr>
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
            primaryList: [],
            staffList:[],
            selectedStaff:null,
            fromDate:null,
            toDate:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/profit_and_loss',
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
