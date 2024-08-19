<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <!-- <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">

                <i class="fal fa-search"></i>
            </label> -->
            <!-- <input type="month" class="input-ui  mr-2 h-8" v-model="selectedMonth" @change="timeChange()"> -->
            <input type="date" class="input-ui  mr-2 h-8" v-model="selectedTime" @change="timeChange()">
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
                        <!-- <tr>
                            <th scope="col" class="">
                                #
                            </th>
                            <th scope="col" class="">
                                A/C Code
                            </th>
                            <th scope="col" class="">
                                Account Name
                            </th>
                            
                            <th scope="col" class="!px-0 !py-0">
                                <div class="grid grid-cols-4">
                                    <div class=" col-span-4 border">
                                        Total Cash & Bank
                                    </div>
                                    <div class="col-span-2 border">
                                        Detail
                                    </div>
                                    <div class="col-span-2 row-span-2 border">
                                        SubTotal
                                    </div>
                                    <div class=" border">
                                        Debit
                                    </div>
                                    <div class=" border">
                                        Credit
                                    </div>
                                </div>
                            </th>
                        </tr> -->

                        <tr>
                            <th scope="col" rowspan="3" class="">
                                #
                            </th>
                            <th scope="col" rowspan="3" class="">
                                A/C Code
                            </th>
                            <th scope="col" rowspan="3" class="">
                                Account Name
                            </th>
                            
                            <th scope="col" colspan="4">
                                Total Cash & Bank
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" colspan="2" class=" border-t">
                                Detail
                            </th>
                            <th scope="col" colspan="2" rowspan="2" class=" border-t">
                                Subtotal
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" rowspan="3" class=" border-t">
                                Debit
                            </th>
                            <th scope="col" rowspan="3" class=" border-t">
                                Credit
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- looping start -->
                        <div class="contents" v-for="(cashflow,index) in cashFlowAccount" :key="index">
                            <tr class="bg-gray-200 rounded-none">
                                <td class=" font-medium !rounded-none">
                                    {{ index+1 }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ cashflow.account_code }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ cashflow.name }}
                                </td>
                                <td colspan="2" class="whitespace-nowrap">
                                    
                                </td>
                                <td colspan="2"  class=" font-medium !px-0 !py-0 !rounded-none">
                                    {{ cashflow.total_amount }}
                                </td>
                            </tr>

                            <tr class="" v-for="(cf,cfIndex) in cashflow.accounts" :key="cfIndex">
                                <td class=" font-medium ">
                                    
                                </td>        
                                <td class="whitespace-nowrap">
                                    {{ cf.account_code }}
                                </td>
                                <td class="whitespace-nowrap">
                                        {{ cf.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ cashflow.type == 'debit' ? cf.amount : ''}}
                                </td>
                                <td class=" font-medium">
                                    {{ cashflow.type == 'credit' ? cf.amount : ''}}
                                </td>
                                <td class=" font-medium">
                                </td>
                            </tr>
                            <div v-if="index < cashFlowAccount.length - 1 && cashflow.type !== cashFlowAccount[index + 1].type" class="contents">

                                <!-- <tr>
                                    <td colspan="7"></td>
                                </tr> -->
                                <tr>
                                    <td class=" font-medium ">
                                        
                                    </td>
                                    <td class="whitespace-nowrap !font-semibold">
                                        
                                    </td>
                                    <td class="whitespace-nowrap !font-semibold">
                                        {{ cashflow.type == 'debit' ? 'Total Receipt Amount' : ''}}
                                    </td>
                                    <td colspan="2" class="whitespace-nowrap">
                                        
                                    </td>
                                    <td colspan="2"  class=" font-medium !px-0 !py-0">
                                        {{ cashFlowList.total_receipt_amount }}
                                    </td>
    
                                </tr>

                                <tr>
                                    <td colspan="7"></td>
                                </tr>
                                <tr>
                                    <td colspan="7" class=" !font-semibold">Payment</td>
                                </tr>
                            </div>
                            
                        </div>
                        <tr>
                            <td colspan="7"></td>
                        </tr>
                        <tr>
                            <td class=" font-medium ">
                                
                            </td>
                            <td class="whitespace-nowrap !font-semibold">
                                
                            </td>
                            <td class="whitespace-nowrap !font-semibold">
                                Total Payment Amount
                            </td>
                            <td colspan="2" class="whitespace-nowrap">
                                
                            </td>
                            <td colspan="2"  class=" font-medium !px-0 !py-0">
                                {{ cashFlowList.total_payment_amount }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7"></td>
                        </tr>
                        <tr>
                            <td class=" font-medium ">
                                
                            </td>
                            <td class="whitespace-nowrap !font-semibold">
                                
                            </td>
                            <td class="whitespace-nowrap !font-semibold">
                                Cash Inflow/Outflow
                            </td>
                            <td colspan="2" class="whitespace-nowrap">
                                
                            </td>
                            <td colspan="2"  class=" font-medium !px-0 !py-0">
                                {{ cashFlowList.cash_in_out_flow }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7"></td>
                        </tr>
                        <tr>
                            <td class=" font-medium ">
                                
                            </td>
                            <td class="whitespace-nowrap !font-semibold">
                                
                            </td>
                            <td class="whitespace-nowrap !font-semibold">
                                Cash In Hand (Opening)
                            </td>
                            <td colspan="2" class="whitespace-nowrap">
                                
                            </td>
                            <td colspan="2"  class=" font-medium !px-0 !py-0">
                                {{ cashFlowList.opening_balance }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7"></td>
                        </tr>
                        <tr>
                            <td class=" font-medium ">
                                
                            </td>
                            <td class="whitespace-nowrap !font-semibold">
                                
                            </td>
                            <td class="whitespace-nowrap !font-semibold">
                                Cash In Hand (Closing)
                            </td>
                            <td colspan="2" class="whitespace-nowrap">
                                
                            </td>
                            <td colspan="2"  class=" font-medium !px-0 !py-0">
                                {{ cashFlowList.closing_balance }}
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                cashFlowList:[],
                cashFlowAccount:[],
                currentDate: getCurrentDate(),
                selectedNewTime:null,
                selectedTime:null,
                items: [
      { id: 1, type: 'type1', name: 'Item 1' },
      { id: 2, type: 'type1', name: 'Item 2' },
      { id: 3, type: 'type2', name: 'Item 3' },
      { id: 4, type: 'type2', name: 'Item 4' },
      { id: 5, type: 'type3', name: 'Item 5' },
      { id: 6, type: 'type3', name: 'Item 6' },
      // More items...
    ]
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            timeChange(){
                let selectedNewTime = this.selectedTime
                this.getCashFlowStatement(selectedNewTime);
            },

            async getCashFlowStatement(selectedDate){
                const response = await getApiData({ url: '/api/cash_flow_statement?date=' + selectedDate, token: this.getToken() });
                if(response.data){
                    this.cashFlowList = response.data;
                    this.cashFlowAccount = response.data.cash_flow_statement;
                }
            },
        },
        mounted()
        {
            initTE({ Modal,Select, Ripple });
        },
        created(){
            this.getCashFlowStatement(this.currentDate);
        }
    }
</script>
