<template>
    
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Accruals
                </p>
            </div>
            <div class="btn-container">
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
                    <!-- <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type" @change="selectedTypeChanged()"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type.value" v-for="(type, typeIndex) in typeList"
                                :key="typeIndex"> {{ type.name }} </option>
                        </select>
                    </div> -->
                    <button type="button" 
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
                        Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Date 
                                </th>
                                <th scope="col" class="">
                                    Type 
                                </th>
                                <th scope="col" class="">
                                    Amount 
                                </th>
                                <th scope="col" class="">
                                    Balance
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, index) in primaryList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.type }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.amount }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.balance }}
                                    </td>
                                    
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="primaryList.length < 1">
                                <td class="" colspan="3">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getPrimaryList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getPrimaryList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                    <div
                        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                        <!--Modal title-->
                        <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                            Delete ?
                        </h5>
                        <!--Close button-->
                        <button type="button"
                            class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
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
                        <button type="button"
                            class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                            data-te-modal-dismiss>
                            Close
                        </button>
                        <button @click="deleteItem()" type="button" data-te-toggle="modal"
                            data-te-target="#deleteModal"
                            class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">    
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss id="close_create_modal"
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-6 col-span-3">
                        <label for="" class="label-form mb-3">
                            Type
                        </label>
                        <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Type"
                                data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui !text-black">
                                <option :value="type" v-for="(type, index) in typeList"
                                    :key="index"> {{ type.name }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-6 col-span-3">
                        <label for="" class="label-form mb-3">
                            Category
                        </label>
                        <!-- <multiselect
                        v-model="selectedCategory"
                        :options="categoryList"
                        :close-on-select="true"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select Category"
                        label="name"
                        track-by="id"
                        :preselect-first="false" ></multiselect> -->
                        <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Category" disabled
                                data-te-select-filter="true" name="" id="" v-model="selectedCategory" class="input-ui !text-black">
                                <option :value="category" v-for="(category, index) in categoryList"
                                    :key="index"> {{ category.name }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-6 col-span-3">
                        <label for="" class="label-form mb-3">
                            Account
                        </label>
                        <multiselect
                        v-model="selectedAccount"
                        :options="accountList"
                        :close-on-select="true"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select Account"
                        label="name"
                        track-by="id"
                        :preselect-first="false" ></multiselect>
                        <!-- <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Account"
                                data-te-select-filter="true" name="" id="" v-model="selectedAccount" class="input-ui !text-black">
                                <option :value="account" v-for="(account, index) in accountList"
                                    :key="index"> {{ account.name }} </option>
                            </select>
                        </div> -->
                    </div>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Amount
                        </label>
                        <input type="text" placeholder="Amount" v-model="amount" class="input-ui">
                    </div>
                    <div class="mb-6 col-span-3" v-show="selectedType?.value === 'settlement'">
                        <label for="" class="label-form mb-3">
                            Cashbook
                        </label>
                        <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                            data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Account"
                                data-te-select-filter="true" name="" id="" v-model="selectedCashbook" class="input-ui !text-black">
                                <option :value="cashbook" v-for="(cashbook, index) in cashbookList"
                                    :key="index"> {{ cashbook.name }} </option>
                            </select>
                        </div>
                    </div>
                </div>

                    <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                    </button>
                    <button type="button" @click="btnClickedAddAccrual()"
                            class="add-btn focus:outline-none focus:ring-0 " >
                            Create
                    </button>
                </div>
            </div>
        </div>
    </div>  
    
    
    








</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    props: ["accrualsId"],
    data() {
        return {
            primaryList: [],
            typeList: [
                {name : 'Addition', value: 'addition'},
                {name : 'Settlement', value: 'settlement'},
            ],
            categoryList: [
                {name : 'Accrued', value: 'accrued'},
                {name : 'Other_payable', value: 'other_payable'},
            ],
            cashbookList: [],
            accountList: [],

            selectedType: null,
            selectedCategory: null,
            selectedAccount: null,
            amount: null,
            selectedCashbook: null,
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/accruals',
            url_search:'',
            url_department:'',
            url_role:'',
            deleteId:null,

            feature: this.getFeature(),
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),
        async getPrimaryList() {
            let url = this.url + '/' + this.accrualsId
            let response = await getApiData({ url: url, token: this.getToken() })

            // console.log("Raw API Response:", response);

            // if (response) {
            //     this.primaryList = response.data ? response.data : response;
            // }
            if (response.data) {
                this.primaryList = response.data;
                this.lastPage = response.data.last_page;
                this.currentPage = response.pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
                this.selectedCategory = this.categoryList.find(item => item.value = this.primaryList[0].category);
                this.selectedAccount = this.accountList.find(item => item.value = this.primaryList[0].category);
            }
        },
        async getExpenseAccountList() {
            let url = `/api/get_expense_accounts`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.accountList = response.data;
            }
            else{
                this.$notify({
                    title: 'Input validation',
                    text: response.error,
                    type: 'warn'
                });
            }
        },
        async getCashAccountList() {
            let url = `/api/get_cash_account`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.cashbookList = response.data;
            }
            else{
                this.$notify({
                    title: 'Input validation',
                    text: response.error,
                    type: 'warn'
                });
            }
        },

        

        addBtnClicked(){
            this.selectedType = null;
            this.amount = null;
            this.selectedAccount = null;
            this.selectedCashbook = null;
        },
        btnClickedAddAccrual(){
            if(!this.selectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            else if(!this.selectedCategory){
                this.alertValidationMessage(`Category`);
                return 1;
            }
            else if(!this.selectedAccount){
                this.alertValidationMessage(`Account`);
                return 1;
            }
            else if(!this.amount){
                this.alertValidationMessage(`Amount`);
                return 1;
            }
            else if(this.selectedType.value === 'settlement' && !this.selectedCashbook){
                this.alertValidationMessage(`Cashbook`);
                return 1;
            }
            else{
                this.addAccrual();
            }
        },
        async addAccrual(){
            let formData = new FormData();
            formData.append('type', this.selectedType.value);
            formData.append('category', this.selectedCategory.value);
            formData.append('expense_account_id', this.selectedAccount.id);
            formData.append('expense_account_code', this.selectedAccount.account_code);
            formData.append('amount',this.amount);
            if(this.selectedType.value === 'settlement'){
                
                formData.append('cash_account_id',this.selectedCashbook.id);
            }
            let response = await postApiData({url:`/api/accruals`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getPrimaryList(1);
                document.getElementById('close_create_modal').click();
            }
            else{
                this.$notify({
                    title: 'Input validation',
                    text: response.error,
                    type: 'warn'
                });
            }
        },


        alertValidationMessage(field) {
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to provide ${field}, please try again`,
                    type: 'warn'
                });
            },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {

        this.getPrimaryList(1);
        this.getExpenseAccountList();
        this.getCashAccountList();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>