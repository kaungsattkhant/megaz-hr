<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Indirect Cash Flow Statement
        </p>
    </div>
    <div class="mt-4 bg-white mb-12">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <!-- <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label> -->
                <!-- <input type="month" class="input-ui  mr-2 h-8" v-model="selectedMonth" @change="timeChange()"> -->
                <input type="month" class="input-ui  mr-2 h-8" v-model="selectedTime" @change="timeChange()">
            </div>
            <div class="flex justify-end flex-col">

                <button type="button" class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    NO
                                </th>
                                <th scope="col" class="">
                                    Particular
                                </th>
                                <th scope="col" class="">
                                    Detail
                                </th>
                                
                                <th scope="col">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-gray-200 rounded-none">
                                <td class=" font-medium !rounded-none">
                                    &#8544;
                                </td>
                                <td class="whitespace-nowrap text-left">
                                    Operating Activities
                                </td>
                                <td class="whitespace-nowrap">
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                            </tr>

                            <!-- cash in flow -->
                            <tr class="">
                                <td class=" font-medium !rounded-none">
                                    A)
                                </td>
                                <td class="whitespace-nowrap text-left">
                                    Cash Inflows:
                                </td>
                                <td class="whitespace-nowrap">
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                            </tr>
                            <tr v-if="cashInFlow" class="" v-for="(inflow,index) in cashInFlow">
                                <td class=" font-medium !rounded-none">
                                    {{ index+1 }}
                                </td>
                                <td class="whitespace-nowrap text-left">
                                    {{ inflow.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ inflow.amount.toLocaleString() }}
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                            </tr>
                            <tr class="">
                                <td class=" font-medium !rounded-none">
                                    
                                </td>
                                <td class="whitespace-nowrap text-right !font-semibold">
                                    Total Cash Inflow
                                </td>
                                <td class="whitespace-nowrap !border-t border-t-black">
                                    {{ total_cash_inflow.toLocaleString() }}
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                            </tr>

                            <!-- cash out flow -->
                            <tr class="">
                                <td class=" font-medium !rounded-none">
                                    B)
                                </td>
                                <td class="whitespace-nowrap text-left">
                                    Cash Outflows:
                                </td>
                                <td class="whitespace-nowrap">
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                            </tr>
                            <tr v-if="cashOutFlow" class="" v-for="(outflow,index) in cashOutFlow">
                                <td class=" font-medium !rounded-none">
                                    {{ index+1 }}
                                </td>
                                <td class="whitespace-nowrap text-left">
                                    {{ outflow.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ outflow.amount.toLocaleString() }}
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                            </tr>
                            <tr class="">
                                <td class=" font-medium !rounded-none">
                                    
                                </td>
                                <td class="whitespace-nowrap text-right !font-semibold">
                                    Total Cash Outflow
                                </td>
                                <td class="whitespace-nowrap !border-t border-t-black">
                                    {{ total_cash_outflow.toLocaleString() }}
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                            </tr>

                            <tr class="">
                                <td class=" font-medium !rounded-none">
                                    
                                </td>
                                <td class="whitespace-nowrap text-right !font-semibold">
                                    Net Cash provided by Operating Activities
                                </td>
                                <td class=" whitespace-nowrap ">
                                </td>
                                <td class="whitespace-nowrap ">
                                    {{ (total_cash_inflow - total_cash_outflow).toLocaleString() }}
                                </td>
                            </tr>

                            <!-- investing activities -->
                            <tr class="bg-gray-200 rounded-none">
                                <td class=" font-medium !rounded-none">
                                    &#8545;
                                </td>
                                <td class="whitespace-nowrap text-left">
                                    Investing Activities
                                </td>
                                <td class="whitespace-nowrap">
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                            </tr>
                            <tr v-if="purchaseOfFixedAsset" class="" v-for="(pfa,index) in purchaseOfFixedAsset">
                                <td class=" font-medium !rounded-none">
                                    {{ index+1 }}
                                </td>
                                <td class="whitespace-nowrap text-left">
                                    {{ pfa.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ pfa.amount.toLocaleString() }}
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                            </tr>
                            <!-- all total of purchase_of_fixed_asset -->
                            <tr class="" v-if="netFA">
                                <td class=" font-medium !rounded-none"></td>
                                <td class="whitespace-nowrap text-right !font-semibold">
                                    Net Cash used in investing activities
                                </td>
                                <td class=" whitespace-nowrap !border-t border-t-black">
                                </td>
                                <td class="whitespace-nowrap !border-t border-t-black">
                                    {{ (netFA.amount).toLocaleString() }}
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr class="">
                                <td class=" font-medium !rounded-none">
                                    
                                </td>
                                <td class="whitespace-nowrap text-right !font-semibold">
                                    Net Cash Flow Before Financing Activities
                                </td>
                                <td class=" whitespace-nowrap">
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ (total_cash_inflow - total_cash_outflow - netFA.amount).toLocaleString() }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import { getCurrentDate } from "../../utilities/datetime-helpers";

    export default {
        data() {
            return {
                indirectCashFlowList:[],
                cashInFlow:[],
                cashOutFlow:[],
                purchaseOfFixedAsset:[],

                total_cash_inflow:0,
                total_cash_outflow:0,
                netFA:0,

                purchase_of_fixed_asset:null,



                currentDate: getCurrentDate(),
                selectedNewTime:null,
                selectedTime:null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            timeChange(){
                let selectedNewTime = this.selectedTime
                this.getCashFlowStatement(selectedNewTime);
            },

            async getIndirectCashFlowStatement(){
                const response = await getApiData({ url: '/api/indirect_cash_flow_statement', token: this.getToken() });
                if(response.data){
                    this.indirectCashFlowList = response.data;
                    this.cashInFlow = response.data.operating_activities.cash_in_flow;
                    this.cashOutFlow = response.data.operating_activities.cash_out_flow;
                    this.total_cash_inflow = response.data.operating_activities.total_cash_in_flow;
                    this.total_cash_outflow = response.data.operating_activities.total_cash_out_flow;
                    this.purchaseOfFixedAsset = response.data.investing_activities.purchase_of_fixed_asset;
                    this.netFA = response.data.investing_activities.purchase_of_fixed_asset[0];
                    console.log(response.data)
                    
                }
            },
        },
        mounted()
        {   
            this.getIndirectCashFlowStatement();
            initTE({ Modal,Select, Ripple });
        },
        created(){
            
        }
    }
</script>
