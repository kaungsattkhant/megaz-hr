<template>
    <div>
        <div class="">
            <div class="w-full pt-9 px-6">

                <div v-if="isList == true">

                    <!-- <div class="bg-white px-4 min-h-[20vh] mb-8">

                    </div> -->
                    <div class="bg-white px-4">
                        <div class="px-4 pt-6 mb-3">
                            <span class="datepicker-toggle">
                                <span class="datepicker-toggle-button pos-add-btn">
                                    Date
                                </span>
                                <input type="date" class="datepicker-input"
                                    @change="dateChange" v-model="invoice_date">
                            </span>
                        </div>
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
                                    <th scope="col" class="px-6 py-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(invoice,index) in invoiceList">
                                        <td class="whitespace-nowrap px-6 py-4 font-medium" @click="btnClickedInvoice(invoice)">
                                            {{ invoice.invoice_id }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ invoice.invoice_date }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ invoice.customer.name }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ invoice.room.name }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ invoice.total_session_price}}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ invoice.sub_total - invoice.total_session_price }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            Service?
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ invoice.total }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <select name=""
                                                class="text-xs pl-0 border-0 focus:shadow-none focus:outline-none focus:ring-0 select-box area-select-box"
                                                placeholder="Select Area">
                                                <option disabled selected>Select Payment</option>
                                                <option>Cash</option>
                                                <option>Bank</option>
                                            </select>
                                            <button>Confirm</button>
                                        </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-if="isDetail == true" class="relative mt-6">
                    <div class="absolute top-0 left-8">
                        <button @click="btnClickedBack()" class="px-4 py-1 rounded-full bg-white text-black">
                            <i class="fas fa-long-arrow-alt-left"></i>
                        </button>
                    </div>
                    <div class="bg-white w-1/3 mx-auto px-12 py-6 relative">
                        <div class="absolute left-1 top-2">
                            <button @click="btnClickedBack()" class="px-4 py-1 rounded-full">
                                <i class="fas fa-long-arrow-alt-left"></i>
                            </button>
                        </div>
                        <table class="min-w-full text-left text-sm font-light">
                            <thead>
                                <tr>
                                    <th class="py-6">Invoice Id</th>
                                    <th class="py-6">{{ invoiceDetail.invoice_id }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Date
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        {{ invoiceDetail.invoice_date.substr(5, 6) }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Customer Name
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        {{ invoiceDetail.customer.name }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Room
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        {{ invoiceDetail.room.name }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Room Charges
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        {{ invoiceDetail.total_session_price }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Food
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        {{ invoiceDetail.sub_total - invoiceDetail.total_session_price }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Services
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        Service?
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Discount
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        {{ invoiceDetail.discount_value }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Service Charge
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        {{ invoiceDetail.service_charge }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Tax
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        {{ invoiceDetail.tax }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap py-2">
                                        Amount
                                    </td>
                                    <td class="whitespace-nowrap py-2">
                                        {{ invoiceDetail.total }}
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
                invoiceList:[],
                customerList:[],
                roomList:[],
                invoiceDetail:null,
                isList:true,
                isDetail:false,
                invoice_date:null,

            };
        },

        methods: {
            ...mapGetters(['getToken', 'getDepartment','getRoles']),

            async getCustomerList(){
                const response = await getApiData({ url: '/api/customers' , token: this.getToken()});
                if(response.data){
                    this.customerList = response.data;
                }
            },
            async getInvoiceList(){
                const response = await getApiData({ url: '/api/invoices' , token: this.getToken()});
                if(response.data){
                    this.invoiceList = response.data;
                }
            },
            async getRoomList(){
                const response = await getApiData({ url: '/api/rooms' , token: this.getToken()});
                if(response.data){
                    this.roomList = response.data;
                }
            },
            btnClickedInvoice(invoice){
                this.invoiceDetail = invoice
                this.isList = false
                this.isDetail = true
            },
            btnClickedBack(){
                this.isList = true
                this.isDetail = false
            },
            dateChange(){
                this.getDateInvoiceList();
            },
            async getDateInvoiceList(){
                const response = await getApiData({ url: '/api/invoices?date='+ this.invoice_date , token: this.getToken()});
                if(response.data){
                    this.invoiceList = response.data;
                }
                else{
                    this.invoiceList = 'test'
                }
            },

            isCateringCashier(){
                let isCashier = false;
                isCashier = this.getRoles().find((role)=>role.name == 'Cashier');
                console.log(isCashier);
                // if(this.getDepartment().name == 'Catering');
            },
        },
        mounted()
        {
            this.getCustomerList();
            this.getInvoiceList();
            this.getRoomList();
            initTE({ Modal, Select, Ripple, Datepicker });
            this.isCateringCashier();
        }
    }
</script>
