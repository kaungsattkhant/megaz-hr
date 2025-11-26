<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            AR Detail
        </p>
    </div>
    <div class="margin-bg">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label>
                <!-- <input type="month" class="input-ui  mr-2 h-8" v-model="selectedMonth" @change="monthChange()"> -->
            </div>
            <div class="flex justify-end flex-col">
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
                                    Date
                                </th>
                                <th scope="col" class="">
                                    Type
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">
                                    Receivable Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="arDetail">
                            <div class="contents" v-for="(ar,index) in arDetail" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ formatDateTime(ar.date_time) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ar.type }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ar.type == 'ar_paid' ? '-' : '' }}{{ (ar.amount).toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ar.receivable_amount.toLocaleString() }}
                                    </td>
                                </tr>
                            </div>
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
        props: ['arId'],
        data() {
            return {
                arDetail:[],
                receivable_amount:0
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            formatDateTime(dateString) {
                const date = new Date(dateString.replace(' ', 'T')); // Convert to ISO format

                const options = {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                };

                // Format the date to '16 Aug 2024, 4:10 PM'
                return date.toLocaleString('en-US', options).replace(',', '');
            },
            async getArDetail(){
                const response = await getApiData({ url: '/api/account/'+this.arId + '/account_receivable_lists' , token: this.getToken() });
                if(response.data){
                    this.arDetail = response.data;
                    this.arDetail.forEach((ar,index)=>{
                        if(ar.type == 'ar_paid'){
                            this.receivable_amount -= ar.amount
                        }
                        else{
                            this.receivable_amount += ar.amount
                        }
                        console.log(this.receivable_amount,'test',index)
                        // this.arDetail[index].push({
                        //     receivable_amount :this.receivable_amount
                        // })
                        this.arDetail[index] = {
                            ...ar, // Spread the existing properties of the object
                            receivable_amount: this.receivable_amount // Add the new property
                        };
                    });
                }
            },
        },
        mounted()
        {
            this.getArDetail();
            initTE({ Modal,Select, Ripple });
        },
        created(){



        }

    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
