<template>
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" text-lg font-semibold font-inter p-4">
                    Fixed Assets
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8 text-[13px] font-inter" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8 text-[13px] font-inter" @click="clearSearchBtnClicked()">Clear</button>
                </div>
                <div class="flex justify-end flex-col">
                    <button type="button" class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
                        Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table ">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
    
                                <th scope="col" class="">
                                    Date
                                </th>
    
                                <th scope="col" class="">
                                    Fixed Asset Id
                                </th>
    
                                <th scope="col" class="">
                                    Name
                                </th>
    
                                <th scope="col" class="">
                                    Description
                                </th>
    
                                <th scope="col" class="">
                                    Total Price
                                </th>
    
                                <th scope="col" class="">
                                    Depreciation Amount
                                </th>
    
                                <th scope="col" class="">
                                    Status
                                </th>
    
                                <th scope="col" class="">
                                    Manager Checked
                                </th>
    
                                <th scope="col" class="">
                                    Financial Checked
                                </th>
    
                                <th scope="col" class="">
                                    MD Check
                                </th>
    
                                <th scope="col" class="px-6 py-4 col-span-3">
    
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(fixedAsset, fixedAssetIndex) in fixedAssetPurchases"
                                :key="fixedAssetIndex">
                                <tr class="">
                                    <td class="font-medium ">
                                        {{ perPage * (currentPage - 1) + (++fixedAssetIndex) }}
                                    </td>
    
                                    <td class="font-medium ">
                                        {{ fixedAsset.date }}
                                    </td>
    
                                    <td class="font-medium ">
                                        {{ fixedAsset.fixed_asset_id }}
                                    </td>
    
                                    <td class="font-medium ">
                                        {{ fixedAsset.name }}
                                    </td>
    
                                    <td class="font-medium ">
                                        {{ fixedAsset.description }}
                                    </td>
    
                                    <td class="font-medium ">
                                        {{ (fixedAsset.total_price).toLocaleString() }}
                                    </td>
    
                                    <td class="font-medium ">
                                        {{ (fixedAsset.depreciation_amount).toLocaleString() }}
                                    </td>
    
                                    <td class="font-medium ">
                                        {{ fixedAsset.status }}
                                    </td>
    
                                    <td class="">
                                        {{ fixedAsset.manager_check_id == null ? 'No' : 'Yes' }}
                                    </td>
    
                                    <td class="">
                                        {{ fixedAsset.finance_check_id == null ? 'No' : 'Yes' }}
                                    </td>
    
                                    <td class="">
                                        {{ fixedAsset.is_md_checked == 0 ? 'No' : 'Yes' }}
                                    </td>
    
                                    <td class="  font-medium ">
                                        <button data-te-toggle="modal" data-te-target="#checkModal"
                                            v-if="fixedAsset.is_md_checked != 1" @click="checkBtnClicked(fixedAsset.id)">
                                            <i class="fal fa-check  pr-3"></i>
                                        </button>
                                    
                                        <button class="pr-1" data-te-toggle="modal" data-te-target="#buyModal"
                                            v-if="(fixedAsset.is_md_checked == 1) && (fixedAsset.is_bought == 0) && getDepartment().name == 'Finance'"
                                            @click="fixedAssetBuyBtnClicked(fixedAsset.id)">
                                            <i class="far fa-shopping-basket"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="fixedAssetPurchases.length < 1 && !loading">
                                <td class="" colspan="12">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>
    
                    <!-- pagination -->
                    <div class="flex justify-center">
    
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getFixedAssetPurchases(currentPage - 1)">«</button>
    
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
    
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getFixedAssetPurchases(currentPage + 1)"> »</button>
                        </div>
                    </div>
                </div>
    
    
    
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative  p-4">
                    <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                        Purchase Fixed Asset
                    </h5>
                    <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-12 py-4" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Fixed Asset Name
                        </label>
                        <input type="text" placeholder="Name" v-model="name"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Description
                        </label>
                        <input type="text" placeholder="Description" v-model="description"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Register Date
                        </label>
                        <input type="date" placeholder="Register date" v-model="date"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Total Price
                        </label>
                        <input type="number" placeholder="Total price" v-model="total_price"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Depreciation Amount
                        </label>
                        <input type="number" placeholder="Depreciation amount" v-model="depreciation_amount"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Depreciation Start Date
                        </label>
                        <input type="date" placeholder="Depreciation start date" v-model="start_date"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Total Duration
                        </label>
                        <input type="number" placeholder="Total duration" v-model="total_duration"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                </div>
                <div class="flex justify-center px-12 mb-6">
                    <button data-te-modal-dismiss type="button" class="add-btn focus:outline-none focus:ring-0 "
                        @click="createBtnClicked">
                        Add New
                    </button>
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
                        Check Fixed Asset Purchase
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
                    <button @click="confirmCheckBtnClicked" type="button" data-te-toggle="modal"
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
                        Buy this fixed asset?
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
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Cash Account
                        </label>
                        <select name="" id="" v-model="selectedCashAccount" class="input-ui">
                            <option :value="cashAccount" v-for="(cashAccount, cashAccountIndex) in cashAccountList"
                                :key="cashAccountIndex">
                                {{ cashAccount.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <!--Modal footer-->
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmBuyFixedAssetBtnClicked" type="button" data-te-toggle="modal"
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
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            fixedAssetPurchases: [],


            name: null,
            date: null,
            description: null,
            total_price: null,
            depreciation_amount: null,
            total_duration: null,
            start_date: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0,

            isManager: false,
            isStaff: false,
            isMD: false,
            checkId: null,

            searchInput:null,

            buyFixedAssetId: null,
            cashAccountList: [],
            selectedCashAccount: null,
            loading: false,

        };
    },

    methods: {
        ...mapGetters(['getToken', 'getUser', 'getRoles', 'getDepartment']),

        async getCashAccountList() {
            let url = `/api/get_cash_account`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.cashAccountList = response.data;
            }
        },

        async getFixedAssetPurchases(pageNumber) {
            this.loading = true;
            let url = `/api/fixed_asset_purchases?page=${pageNumber}`;
            if(this.searchInput){
                url = '/api/fixed_asset_purchases?page=' + pageNumber + '&search=' + this.searchInput;
            }
            let response = await getApiData({ url: url, token: this.getToken() });
            // let response = await getApiData({ url: `/api/fixed_asset_purchases?page=${pageNumber}`, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.fixedAssetPurchases = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        async createBtnClicked() {
            if (!this.name || !this.date || !this.description || !this.total_price
                || !this.depreciation_amount || !this.start_date || !this.total_duration) {
                this.$notify({
                    title: `Input validation`,
                    text: `Please fill all requrired fields`,
                    type: 'warn'
                });
            }
            else {
                let formData = new FormData();
                formData.append('date', this.date);
                formData.append('name', this.name);
                formData.append('description', this.description);
                formData.append('total_price', this.total_price);
                formData.append('depreciation_amount', this.depreciation_amount);
                formData.append('start_date', this.start_date);
                formData.append('total_duration', this.total_duration);
                let response = await postApiData({ url: `/api/fixed_asset_purchases`, form_data: formData, token: this.getToken() });
                if (response.data) {
                    this.fixedAssetPurchases.unshift(response.data);
                }
            }
        },

        checkBtnClicked(fixedAssetId) {
            this.checkId = fixedAssetId;
        },

        async confirmCheckBtnClicked() {
            let url = `/api/fixed_asset_purchases/is_update_checked`;
            let formData = new FormData();
            formData.append('id', this.checkId);
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: 'Fixed asset checked',
                    type: 'info'
                });

                setTimeout(() => {
                    window.location.reload();
                }, 900);
            }
            else {
                this.$notify({
                    text: response.message,
                    type: 'error'
                });
            }
            // console.log(response);
        },

        fixedAssetBuyBtnClicked(id) {
            this.buyFixedAssetId = id;
        },

        async confirmBuyFixedAssetBtnClicked() {
            if (!this.selectedCashAccount) {
                this.$notify({
                    text: 'No cash account selected',
                    type: 'warn'
                });

                return 1;
            }
            let url = `/api/fixed_asset_purchases/bought`;
            let formData = new FormData();
            formData.append('id', this.buyFixedAssetId);
            formData.append('cash_account_id', this.selectedCashAccount.id);
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Fixed asset bought successuflly`,
                    type: 'info'
                });
            }
            else {
                this.$notify({
                    text: `Fixed asset buy not success`,
                    type: 'error'
                });
            }

            setTimeout(() => {
                window.location.reload();
            }, 900);
        },
        async searchBtnClicked() {
            this.getFixedAssetPurchases(1);
        },
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.getFixedAssetPurchases(1);
        },
    },

    created() {
        this.getRoles().forEach((role) => {
            if (role.name == 'Manager') {
                this.isManager = true;
            }
            if (role.name == 'MD') {
                this.isMD = true;
            }
            if (role.name == 'Staff') {
                this.isStaff = true;
            }
        });
        this.getFixedAssetPurchases(1);
        this.getCashAccountList();
    },

    mounted() {
        initTE({ Modal });
    }
}
</script>
