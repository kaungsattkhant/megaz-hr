<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Customer Detail
        </p>
    </div>
    <div class="py-1 bg-white">
        <!-- <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">
                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button>
            </div>
        </div> -->
        <div class="box-container-table" v-if="customer">
            <div class="mt-2 mb-4 border p-1 shadow-md">
                <table class="primary-table">
                    <thead class="  ">
                        <tr>
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
                        <tr class="">
                            <td> {{ customer.name }} </td>
                            <td> {{ customer.phone_number }} </td>
                            <td> {{ customer.township_name }} </td>
                            <td> {{ customer.address }} </td>
                            <td> {{ (customer.total_amount).toLocaleString() }} </td>
                            <td> {{ customer.rentation }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>

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
                                    Datetime
                                </th>
                                <th scope="col" class="">
                                    KTV Room
                                </th>
                                <th scope="col" class="">
                                    Menu
                                </th>
                                <th scope="col" class="">
                                    KTV Fees
                                </th>
                                <th scope="col" class="">
                                    Menu Fees
                                </th>
                                <th scope="col" class="">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div v-for="(invoice,index) in invoices" :key="index" class="contents">
                                <tr class="">
                                    <td class="  ">
                                        {{ (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ invoice.complete_date }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="invoice.sessions.length > 0">
                                            {{ invoice.sessions[invoice.sessions.length-1].entity.name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="invoice.orders.length > 0">
                                            <div v-for="order in invoice.orders">
                                                <span v-for="item in order.order_items"> {{ item.menu.name }}, </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="invoice.sessions.length > 0">
                                            {{ (invoice.ktv_fee).toLocaleString() }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div v-if="invoice.orders.length > 0">
                                            {{ (invoice.menu_fee).toLocaleString() }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ (invoice.total).toLocaleString() }}
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
import { convertToFriendlyDateTime } from "../../utilities/datetime-helpers";

export default {
    props: ['customerId'],
    data() {
        return {
            customer: null,
            invoices: [],

        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getCustomerDetail() {
            let url = `/api/customer_list/${this.customerId}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                let data = response.data;
                this.invoices = JSON.parse(JSON.stringify(data.customer.invoices));
                delete data.customer.invoices;
                this.customer = data.customer;
                this.customer.township_name = data.customer_detail[0].township_name;
                this.customer.total_amount = data.customer_detail[0].total_amount;
                console.log(this.invoices);
                this.invoices.forEach((invoice)=>{
                    invoice.ktv_fee = 0;
                    invoice.menu_fee = 0;
                    invoice.invoice_date = convertToFriendlyDateTime(invoice.invoice_date);
                    invoice.complete_date = convertToFriendlyDateTime(invoice.complete_date);
                    invoice.sessions.forEach((session)=>{
                        invoice.ktv_fee += session.price;
                    });

                    invoice.orders.forEach((order)=>{
                        order.order_items.forEach((item)=>{
                            invoice.menu_fee += item.price;
                        });
                    });
                });
            }
        },
    },

    created() {
        this.getCustomerDetail();
    },

    mounted() {

    }
}
</script>
