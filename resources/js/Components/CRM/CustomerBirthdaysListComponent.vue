<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Upcoming Customer Bithdays
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table  ">
                        <thead class="  ">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Phone Number
                                </th>
                                <th scope="col" class="">
                                    Township
                                </th>
                                <th scope="col" class="">
                                    Address
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">
                                    Rentation
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div v-for="(customer,index) in customerList" :key="index" class="contents">
                                <tr class="">
                                    <td class="  ">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ customer.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ customer.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ customer.township.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ customer.address }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        amount
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ customer.rentation }}
                                    </td>
                                </tr>
                            </div>
                            <!-- looping end -->
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

export default {
    data() {
        return {
            customerList: [],
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getCustomerList(){
            let url = `/api/crm/upcoming_birthdays`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.customerList = response.data;
            }
        },
    },

    created(){
        this.getCustomerList();
    },

    mounted() {

    }
}
</script>
