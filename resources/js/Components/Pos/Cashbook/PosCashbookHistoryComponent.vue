<template>
    <div>
        <div class="">
            <div class="w-full pt-9 px-6">
                <div class="flex justify-between mb-4">
                    <div class="flex gap-x-3">
                        <div class=" flex gap-x-4">
                            <input type="date" class="h-8 mr-2 rounded-md" v-model="selectedDate" @change="dateChanged()">
                        </div>
                        
                    </div>
                    <div class="flex gap-x-3">
                        
                    </div>
                </div>

                <div>
                    <div class="bg-white px-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Id</th>
                                    <th scope="col" class="px-6 py-4">Date</th>
                                    <th scope="col" class="px-6 py-4">Opening Balance</th>
                                    <th scope="col" class="px-6 py-4">Closing Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                

                                <tr class="" v-for="(cashbook,index) in primaryList">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ cashbook.created_at }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ cashbook.opening_balance }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ cashbook.closing_balance }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


        


    </div>

</template>
<script>
    import { Modal, Ripple, Select, Datepicker, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import { getCurrentDate } from "../../../utilities/datetime-helpers";

    export default {
        data() {
            return {
                primaryList:[],
                // bankbookList:[],



                toDate: null,
                selectedDate : getCurrentDate(),
                url_date: '&date=' + getCurrentDate(),

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getPrimaryList(pageNumber){
                let url = '/api/get_cashbook_closing_history?is_pos=1' + this.url_date
                const response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.primaryList = response.data

                }
            },
            dateChanged(){
                if(this.selectedDate){
                    // this.primaryList = [];
                    this.url_date = '&date=' + this.selectedDate;
                    this.getPrimaryList();
                }
                
            },
            
            alertValidationMessage(field) {
                this.$notify({
                    title: `Input validation`,
                    text: `You forgot to provide ${field}, please try again`,
                    type: "warn"
                });
            },
            


                // for close transaction
            // let url = '/api/close_cashbook_transaction?cash_account_id=298';
            //     let response = await getApiData({ url: url, token: this.getToken() });
            //     let url = '/api/close_cashbook_transaction?cash_account_id=298';
            //     let response = await getApiData({ url: url, token: this.getToken() });
            //     if(response.data){
            //         this.subAccountList = response.data;
            //     }
        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        },

        created(){
            this.getPrimaryList();
            // this.getTotalBookList();
        }
    }
</script>
