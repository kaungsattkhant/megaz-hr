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
                        <button class="pos-add-btn !bg-[#F15181]">
                            Close
                        </button>
                        <button class="pos-add-btn">
                            <a href="/pos/customer/create" class="a-clear">
                                Add
                            </a>
                        </button>
                    </div>
                </div>
                <div>
                    <div class="bg-white">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium dark:border-neutral-500">
                                <tr>
                                <th scope="col" class="px-6 py-4">#</th>
                                <th scope="col" class="px-6 py-4">Customer Name</th>
                                <th scope="col" class="px-6 py-4">Phone Number</th>
                                <th scope="col" class="px-6 py-4">Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(customer,index) in customerList" :key="index">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ index++ }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ customer.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ customer.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ customer.address }}
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

    export default {
        data() {
            return {
                customerList:[],

            };
        },

        methods: {
            async getCustomersList(){
                const response = await getApiData({ url: '/api/customers' });
                if(response.data){
                    this.customerList = response.data;
                }
            },
        },
        mounted()
        {
            this.getCustomersList();
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>