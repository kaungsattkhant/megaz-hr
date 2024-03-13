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
                        <!-- <button class="pos-add-btn !bg-[#F15181]">
                            Close
                        </button> -->
                        <button class="pos-add-btn" data-te-toggle="modal" data-te-target="#create_cashbook_modal">
                            
                                Add
                            
                        </button>
                    </div>
                </div>
                <div>
                    <div class="bg-white px-4 min-h-[20vh] mb-8">

                    </div>
                    <div class="bg-white px-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Id</th>
                                    <th scope="col" class="px-6 py-4">Date</th>
                                    <th scope="col" class="px-6 py-4">Customer Name</th>
                                    <th scope="col" class="px-6 py-4">Room</th>
                                    <th scope="col" class="px-6 py-4">Room Charges</th>
                                    <th scope="col" class="px-6 py-4">Food</th>
                                    <th scope="col" class="px-6 py-4">Services</th>
                                    <th scope="col" class="px-6 py-4">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">
                                        <td class="whitespace-nowrap px-6 py-4 font-medium">
                                            1
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            2
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            3
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            4
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            5
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            6
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
