<template>
    
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Kitchen For Sky
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
                    <!-- <button type="button" 
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
                        Add New
                    </button> -->
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
                                    Menu Item Name
                                </th>
                                <th scope="col" class="">
                                    Category
                                </th>
                                <th scope="col" class="">
                                    Balance
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, index) in primaryList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.menu_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.category }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.total_balance }}
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="primaryList.length < 1 && !loading">
                                <td class="" colspan="5">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getOkrList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOkrList(currentPage + 1)">
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


</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        Multiselect,
        TableSkeleton
    },
    data() {
        return {
            primaryList: [],
            
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/report/monthly-kitchen-menu-total?area_type=ktv',
            url_search:'',
            deleteId:null,

            feature: this.getFeature(),
            loading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getPrimaryList(pageNumber) {
            this.loading = true;
            let url = this.url;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.primaryList = response.data;
                    this.lastPage = response.data.last_page;
                    this.currentPage = pageNumber;
                    this.perPage = response.data.per_page;
            }
        },
        async getApAccountList(){
            if(this.selectedType && this.selectedCategory){
                let url = `/api/other-payable-accounts`;
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.apAccountList = response.data;
                }
                else{
                    this.$notify({
                        title: 'Input validation',
                        text: response.error,
                        type: 'warn'
                    });
                }
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
            this.selectedCategory = null;
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
            if(this.selectedType.value === 'addition' && this.selectedCategory.value === 'other_payable'){
                formData.append('other_payable_account_id',this.selectedApAccount.id);
                formData.append('other_payable_account_code',this.selectedApAccount.account_code);
            }
            formData.append('expense_account_id', this.selectedAccount.id);
            formData.append('expense_account_code', this.selectedAccount.account_code);
            formData.append('amount',this.amount);
            if(this.selectedType.value === 'settlement'){
                
                formData.append('cash_account_id',this.selectedCashbook.id);
            }
            let response = await postApiData({url:`/api/accruals`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getPrimaryList();
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

        async searchBtnClicked() {
            this.url_search = '&search=' + this.searchInput
            this.getPrimaryList(1);
        },
        clearSearchBtnClicked() {
            this.searchInput = null;
            this.url_search = '';
            this.getPrimaryList(1);
        },



        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async deleteItem() {
            let response = await deleteApiData({ url: `/api/accruals/` + this.deleteId, token: this.getToken() });
            if (response.success) {
                this.getPrimaryList(1);
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
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