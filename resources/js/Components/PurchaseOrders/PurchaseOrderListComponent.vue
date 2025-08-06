<template>
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Purchase Orders
                </p>
            </div>
            <div class="btn-container pt-10">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
                </div>
                <div class="flex pr-0 gap-x-4">
                    <div class="relative">
                        <label for="search" class="border border-gray-200 rounded bg-white text-xs mx-2 px-2 py-2 absolute left-0 ml-0 -top-[90%] border-b-0"> From </label>
                        <input type="date" v-model="fromDate" class="search-input rounded " @change="dateChange()">
                    </div>

                    <div class="relative">
                        <label for="search" class="border border-gray-200 rounded bg-white text-xs mx-2 px-2 py-2 absolute left-0 ml-0 -top-[90%] border-b-0"> To </label>
                        <input type="date" v-model="toDate" class="search-input rounded" @change="dateChange()">
                    </div>
                    <div class="flex justify-end flex-col">
                        <a href="/purchase_orders/create" class="add-btn " v-if="feature.includes('purchase-order.create')">
                            Add New
                        </a>
        
                    </div>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table ">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Date
                                </th>
                                <th scope="col" class="  ">
                                    Purchase Order Id
                                </th>

                                <th scope="col" class="  ">
                                    Status
                                </th>

                                <th scope="col" class="  ">
                                    Manager Check
                                </th>
                                <th scope="col" class="  ">
                                    Procurement Check
                                </th>

                                <th scope="col" class="  ">
                                    Financial Check
                                </th>

                                <th scope="col" class="  ">
                                    MD Checked
                                </th>

                                <th scope="col" colspan="4">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(purchaseOrder, index) in purchaseOrderList" :key="index">
                                <tr class="">
                                    <td class=" ">
                                        {{ perPage * (currentPage - 1) + (index+1) }}
                                        <!-- {{ index+1 }} -->
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ purchaseOrder.date }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ purchaseOrder.po_id }}
                                    </td>

                                    <td class="  ">
                                        {{ purchaseOrder.status }}
                                    </td>

                                    <td class="">
                                        {{ purchaseOrder.manager_check_id == null ? 'No' : 'Yes' }}
                                    </td>
                                    <td class="">
                                        {{ purchaseOrder.procurement_manager_check_id == null ? 'No' : 'Yes' }}
                                    </td>
                                    <td class="">
                                        {{ purchaseOrder.financial_check_id == null ? 'No' : 'Yes' }}
                                    </td>
                                    <td class="">
                                        {{ purchaseOrder.is_md_checked == 0 ? 'No' : 'Yes' }}
                                    </td>

                                    <td class="whitespace-nowrap  space-x-4">
                                        <button v-if="purchaseOrder.is_md_checked != 1 && !isStaff" class="pr-1"
                                            @click="checkPurchaseOrderBtnClicked(purchaseOrder.id)"
                                            data-te-toggle="modal" data-te-target="#checkModal">
                                            <i class="far fa-check"></i>
                                        </button>
                                    </td>

                                    <td class="whitespace-nowrap  space-x-4">
                                        <!-- <button v-if="(purchaseOrder.is_md_checked == 1) && (purchaseOrder.is_bought == 0) && getDepartment().name == 'Finance'" class="pr-1" @click="buyPurchaseOrderBtnClicked(purchaseOrder.id)" data-te-toggle="modal" data-te-target="#buyModal">
                                        <i class="far fa-shopping-basket"></i>
                                    </button> -->

                                        <a :href="'/purchase_orders/' + purchaseOrder.id + '/buy'"
                                            v-if="(purchaseOrder.is_md_checked == 1) && (purchaseOrder.is_bought == 0) && getDepartment().name == 'Finance'"
                                            id="" class="pr-1">
                                            <i class="far fa-shopping-basket"></i>
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap  space-x-4" v-if="feature.includes('purchase-order.edit')">
                                        <a :href="'/purchase_orders/' + purchaseOrder.id + '/edit'" id="" class="pr-1" >
                                            <i class="far fa-pen"></i>
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap  space-x-4" v-if="feature.includes('purchase-order.confirm')">
                                        <a :href="'/purchase_orders/' + purchaseOrder.id + '/confirm'" id="" class="pr-1" >
                                            <i class="far fa-bars"></i>
                                        </a>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getPurhaseOrderList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getPurhaseOrderList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Check Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="checkModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
            min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Check Purchase Order
                    </h5>
                    <!--Close button-->
                    <button type="button"
                        class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p>
                        Are you sure ?
                    </p>
                </div>

                <!--Modal footer-->
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmCheckPurchaseOrderBtnClicked" type="button" data-te-toggle="modal"
                        data-te-target="#checkModal"
                        class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--Buy Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="buyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
            min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Buy this purchase order
                    </h5>
                    <!--Close button-->
                    <button type="button"
                        class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p>
                        Are you sure ?
                    </p>
                </div>

                <!--Modal footer-->
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmBuyPurchaseOrderBtnClicked" type="button" data-te-toggle="modal"
                        data-te-target="#buyModal"
                        class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, initTE } from "tw-elements";

import { mapGetters } from 'vuex';
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { convertToFriendlyDate } from '../../utilities/datetime-helpers';


export default {
    data() {
        return {
            purchaseOrderList: [],
            checkId: null,
            buyId: null,
            isManager: false,
            isMD: false,
            isFinance: false,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
            isStaff: false,

            searchInput:null,
            fromDate:null,
            toDate:null,

            url_search:'',
            url_from:'',
            ur_to:'',
            url_date:'',

            feature: this.getFeature(),
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getUser', 'getRoles', 'getDepartment', 'getFeature']),

        async getPurhaseOrderList(pageNumber) {
            let url_page = '';
            if(pageNumber){
                url_page = 'page='+pageNumber
            }
            let url = `/api/purchase_orders?${this.url_date}${this.url_search}${url_page}`;

            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.purchaseOrderList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = response.data.current_page;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
                this.purchaseOrderList.forEach((po) => {
                    po.date = convertToFriendlyDate(po.date);
                });
            }
        },

        checkPurchaseOrderBtnClicked(purchaseOrderId) {
            this.checkId = purchaseOrderId;
        },

        async confirmCheckPurchaseOrderBtnClicked() {
            if (this.checkId) {
                let url = `/api/updateIsCheck`;
                let formData = new FormData();
                formData.append('type', 'purchase_order');
                formData.append('id', this.checkId);
                formData.append('value', 1);
                let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.$notify({
                        text: `PO Checked`,
                        type: 'info'
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 200);
                }
                else {
                    this.$notify({
                        text: response.message,
                        type: 'error'
                    });
                }
            }

            this.checkId = null;
        },

        buyPurchaseOrderBtnClicked(purchaseOrderId) {
            this.buyId = purchaseOrderId;
        },

        async confirmBuyPurchaseOrderBtnClicked() {
            if (this.buyId) {
                let url = `/api/purchase_orders_bought`;
                let formData = new FormData();
                formData.append('ids[]', this.buyId);
                formData.append('value', 1);
                let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.$notify({
                        text: `PO Checked`,
                        type: 'info'
                    });
                    // setTimeout(()=>{
                    //     window.location.reload();
                    // }, 3000);
                }
                else {
                    this.$notify({
                        text: response.message,
                        type: 'error'
                    });
                }
            }

            this.checkId = null;
        },


        dateChange(){
            if(this.fromDate && this.toDate){
                this.url_date = 'from_date='+this.fromDate+'&to_date='+this.toDate
                this.getPurhaseOrderList();
                this.searchInput = null;
                this.url_search = ''
            }
        },
        searchBtnClicked(){
            this.url_search = 'po_id=' + this.searchInput
            this.getPurhaseOrderList();
            this.url_date = '';
            this.fromDate = null;
            this.toDate = null;
        },
        clearSearchBtnClicked(){
            this.url_date = '';
            this.url_search = '';
            this.fromDate = null;
            this.toDate = null;
            this.searchInput = null;
            this.getPurhaseOrderList(1)
        }
    },

    created() {
        this.getRoles().forEach((role) => {
            if (role.name == 'Manager') {
                this.isManager = true;
            }
            if (role.name == 'MD') {
                this.isMD = true;
            }
            if (role.name == 'Finance') {
                this.isFinance = true;
            }
            if (role.name == 'Staff'){
                this.isStaff = true;
            }
        });
        this.getPurhaseOrderList(1);
    },

    mounted() {
        initTE({ Modal });
    }
}
</script>
