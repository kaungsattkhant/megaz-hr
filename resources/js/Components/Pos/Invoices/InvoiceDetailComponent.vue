<template>
    <div>
        <div class="">
            <div class="w-full pt-9 px-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <button class="pos-add-btn">
                            Date
                        </button>
                    </div>
                    <div class="flex gap-x-3">
                    </div>
                </div>
                <div>
                    <div class="bg-white w-1/3 mx-auto px-12 pt-6">
                        <div>
                            <div class="flex justify-between mb-8">
                                <p class="text-sm font-semibold text-black">
                                    Invoice Id
                                </p>
                                <p class="text-sm font-normal text-gray-600">
                                    Feb 5
                                </p>
                            </div>

                            <div class="flex justify-between">
                                <p class="text-sm font-semibold text-black">
                                    Invoice Id
                                </p>
                                <p class="text-sm font-normal text-gray-600">
                                    Feb 5
                                </p>
                            </div>
                        </div>


                        <table class="min-w-full text-left text-sm font-light">
                            <thead class=" font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Date</th>
                                    <th scope="col" class="px-6 py-4">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        2
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        3
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

    export default {
        data() {
            return {
                customerList:[],

            };
        },

        methods: {
            // ...mapGetters(['getToken']),

            // async getCashbookList(){
            //     const response = await getApiData({ url: '/api/cashbook' , token: this.getToken()});
            //     if(response.data){
            //         this.cashbookList = response.data;
            //     }
            // },
        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
