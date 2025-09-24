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
                                    <th scope="col" class="px-6 py-4">Room/Table</th>
                                    <th scope="col" class="px-6 py-4">Room Charges</th>
                                    <th scope="col" class="px-6 py-4">Food</th>
                                    <!-- <th scope="col" class="px-6 py-4">Services</th> -->
                                    <th scope="col" class="px-6 py-4">Amount</th>
                                    <th scope="col" class="px-6 py-4">Status</th>
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
                                        {{ invoice.customer ? invoice.customer.name : 'Default' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span v-if="invoice.entity">{{ invoice.entity.name }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ invoice.total_session_price}}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ invoice.sub_total - invoice.total_session_price }}
                                    </td>
                                    <!-- <td class="whitespace-nowrap px-6 py-4">
                                        Service?
                                    </td> -->
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ invoice.sub_total }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span :class="invoice.payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800 '" class="px-2 py-1 rounded capitalize font-semibold text-sm">
                                            {{ invoice.payment_status }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <!-- <select name=""
                                            class="text-xs pl-0 border-0 focus:shadow-none focus:outline-none focus:ring-0 select-box area-select-box"
                                            placeholder="Select Payment" :disabled="!isCashier">
                                            <option disabled selected>Select Payment</option>
                                            <option>Cash</option>
                                            <option>Bank</option>
                                        </select> -->
                                        <!-- <button :disabled="invoice.payment_status === 'paid'" :class="invoice.payment_status === 'paid' ? 'cursor-not-allowed opacity-60' : ''"  class=" text-black px-4 py-1.5 text-base rounded-lg"
                                         data-te-toggle="modal" data-te-target="#confirm_invoice_modal" @click="confirmBtnClicked(invoice)">
                                            Confirm
                                        </button> -->

                                        <button :disabled="invoice.payment_status === 'paid'" 
                                            :class="invoice.payment_status === 'paid' ? 'bg-gray-300 text-gray-600 cursor-not-allowed opacity-60' : 'bg-blue-500 text-white hover:bg-blue-600'"  
                                            class=" px-4 py-1.5 text-sm rounded-lg"
                                            data-te-toggle="modal" data-te-target="#confirm_invoice_modal" @click="confirmBtnClicked(invoice)">
                                            <i class="far fa-check-double" :class="invoice.payment_status === 'paid' ? 'text-gray-500' : ''"></i>
                                            Confirm
                                        </button>
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
                                        {{ invoiceDetail.customer  ? invoiceDetail.customer.name : "Default" }}
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



        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="confirm_invoice_modal" tabindex="-1" aria-labelledby="createCashbookModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Confirm Invoice
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none
                        " id="closeModal" data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-16 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Payment Type
                            </label>
                            <select name="" id="" v-model="selectedPayment"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                >
                                    <option disabled selected>Select Payment</option>
                                    <option value="bank"> Bank </option>
                                    <option value="cash" selected> Cash </option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Paid Amount
                            </label>
                            <input type="number" placeholder="Amount" v-model="paidAmount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="confirmInvoice" class="pos-add-btn !px-16 focus:outline-none focus:ring-0 ">
                            Create
                        </button>
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

                selectedInvoice:null,
                paidAmount: 0,
                selectedPayment: null,
                isCashier: false,
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
                const response = await getApiData({ url: '/api/pos/invoices' , token: this.getToken()});
                if(response.data){
                    this.invoiceList = response.data;
                }
            },
            // async getRoomList(){
            //     const response = await getApiData({ url: '/api/rooms' , token: this.getToken()});
            //     if(response.data){
            //         this.roomList = response.data;
            //     }
            // },
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
                const response = await getApiData({ url: '/api/pos/invoices?date='+ this.invoice_date , token: this.getToken()});
                if(response.data){
                    this.invoiceList = response.data;
                }
                this.$notify({
                    title: 'Error',
                    text: response.message,
                    type: 'error'
                });
            },

            isCateringCashier(){
                let cashierRole = this.getRoles().find((role)=>role.name == 'Cashier');
                if(cashierRole)
                    return true;
                else
                    return false;
            },
            confirmBtnClicked(invoice){
                this.selectedInvoice = invoice;
                this.selectedPayment = null;
                this.paidAmount = invoice.total;
            },
            async confirmInvoice(){
                console.log('test confirm invoice')
                let formData = new FormData();
                formData.append('id', this.selectedInvoice.id);
                formData.append('paid_amount', this.paidAmount);
                formData.append('payment_type', this.selectedPayment);
                let response = await postApiData({ url: '/api/pos/invoices', form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.getInvoiceList();
                    document.getElementById("closeModal").click();
                }

            }
        },
        mounted()
        {
            this.getCustomerList();
            this.getInvoiceList();
            // this.getRoomList();
            initTE({ Modal, Select, Ripple, Datepicker });
            this.isCashier = this.isCateringCashier();
        }
    }
</script>
