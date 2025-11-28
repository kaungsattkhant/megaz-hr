<template>

    <div class="margin-bg">
        <div class="card-shadow">
            <div class="pt-2">
                <p class=" page-title">
                    Budget Accounts
                </p>
            </div>
            <div class="btn-container pt-6">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input w-24">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
                    <button class="add-btn h-8" data-te-toggle="modal" data-te-target="#create_modal">Add</button>
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
                                    Main Account
                                </th>
                                <th scope="col" class="">
                                    Sub Account
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">
                                    Priority
                                </th>
                                <th scope="col" class="">
                                    Stage
                                </th>
                                <th scope="col" class="">

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
                            <div class="contents" v-for="(budgetAccount, index) in budgetAccountList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        {{ perPage * (currentPage - 1) + (index + 1) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ budgetAccount.date }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ budgetAccount.main_account.name }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ budgetAccount.sub_account.name }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ budgetAccount.amount.toLocaleString() }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ budgetAccount.budget_priority.label }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ budgetAccount.status }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal"
                                        data-te-target="#update_modal" id="edit-btn" class="pr-3"
                                        @click="editBtnClicked(budgetAccount, index)">
                                            <i class="fal fa-pen"></i>
                                        </button>

                                        <button v-if="budgetAccount.status != 'confirmed'" data-te-toggle="modal"
                                        data-te-target="#confirm_modal" class="pr-3"
                                        @click="confirmBtnClicked(budgetAccount, index)">
                                            <i class="fal fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="budgetAccountList.length < 1 && !loading">
                                <td class="" colspan="7">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getBudgetAccountList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getBudgetAccountList(currentPage + 1)">
                                »</button>
                        </div>
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
                        New Budget Account
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
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Priority
                        </label>
                        <multiselect v-model="selectedBudgetPriority" :options="budgetPriorities"
                        :clear-on-select="false"
                        :preserve-search="false" placeholder="Priority" label="label" track-by="id"
                        :preselect-first="false"></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Main Account
                        </label>
                        <multiselect v-model="selectedMainAccount" :options="mainAccounts"
                        :clear-on-select="false"
                        :preserve-search="false" placeholder="Main Account" label="name" track-by="id"
                        :preselect-first="false"
                        @select="mainAccountSelectChanged"></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Sub Account
                        </label>
                        <multiselect v-model="selectedSubAccount" :options="subAccounts"
                        :clear-on-select="false"
                        :preserve-search="false" placeholder="Sub Account" label="name" track-by="id"
                        :preselect-first="false"
                        ></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Date
                        </label>
                        <input type="date" placeholder="Date" v-model="date" class="input-ui">
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Amount
                        </label>
                        <input type="number" placeholder="Amount" v-model="amount" class="input-ui">
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close" id="create_modal_close_btn">
                        Cancel
                    </button>
                    <LoadingButton
                    :loading="createBtnLoading"
                    text="Create"
                    loading-text="Loading..."
                    @click="createBtnClicked"></LoadingButton>
                </div>
            </div>
        </div>
    </div>

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="update_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Edit Budget Account
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
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Priority
                        </label>
                        <multiselect v-model="selectedBudgetPriority" :options="budgetPriorities"
                        :clear-on-select="false"
                        :preserve-search="false" placeholder="Priority" label="label" track-by="id"
                        :preselect-first="false"></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Main Account
                        </label>
                        <multiselect v-model="selectedMainAccount" :options="mainAccounts"
                        :clear-on-select="false"
                        :preserve-search="false" placeholder="Main Account" label="name" track-by="id"
                        :preselect-first="false"
                        @select="mainAccountSelectChanged"></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Sub Account
                        </label>
                        <multiselect v-model="selectedSubAccount" :options="subAccounts"
                        :clear-on-select="false"
                        :preserve-search="false" placeholder="Sub Account" label="name" track-by="id"
                        :preselect-first="false"
                        ></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Date
                        </label>
                        <input type="date" placeholder="Date" v-model="date" class="input-ui">
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Amount
                        </label>
                        <input type="number" placeholder="Amount" v-model="amount" class="input-ui">
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close" id="update_modal_close_btn">
                        Cancel
                    </button>
                    <LoadingButton
                    :loading="updateBtnLoading"
                    text="Edit"
                    loading-text="Loading..."
                    @click="confirmEditBtnClicked"></LoadingButton>
                </div>
            </div>
        </div>
    </div>
            <!--Confirm Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="confirm_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                        <div
                            class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                            <!--Modal title-->
                            <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                                Confirm Budget account
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
                            id="confirm_modal_close_btn"
                                class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                                data-te-modal-dismiss>
                                Close
                            </button>
                            <LoadingButton
                            :loading="updateBtnLoading"
                            text="Confirm"
                            loading-text="Loading..."
                            @click="confirmAccountBtnClicked"></LoadingButton>

                        </div>
                    </div>
                </div>
            </div>
</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import TableSkeleton from "../Common/TableSkeleton.vue";
import Multiselect from "vue-multiselect";
import LoadingButton from "../Common/LoadingButton.vue";

export default {
    components: {
        TableSkeleton,
        Multiselect,
        LoadingButton
    },
    data() {
        return {
            budgetAccountList: [],

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            perPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/budget_accounts',
            url_search:'',

            deleteId:null,

            loading: true,

            createBtnLoading: false,
            updateBtnLoading: false,

            budgetPriorities: [],
            selectedBudgetPriority: null,
            amount: 0,
            mainAccounts: [],
            selectedMainAccount: null,
            subAccounts: [],
            selectedSubAccount: null,
            date: null,

            editId: null,
            editIndex: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        getBudgetPriorities(){
            getApiData({url: `/api/budget_priorities`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.budgetPriorities = response.data;
                }
            });
        },

        getMainAccounts(){
            getApiData({url: `/api/sub_accounts`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.mainAccounts = response.data;
                }
            });
        },

        mainAccountSelectChanged(){
            this.selectedSubAccount = null;
            this.subAccounts = [];
            // 'account_by_sub_account/{id}
            getApiData({url: `/api/account_by_sub_account/${this.selectedMainAccount.id}`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.subAccounts = response.data;
                }
            });
        },

        async getBudgetAccountList(pageNumber) {
            this.loading = true;
            // let url = this.url + pageNumber + this.url_search + this.url_staff + this.url_from + this.url_to;
            let url = `/api/budget_accounts?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.budgetAccountList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
            }
        },

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });
        },

        createBtnClicked(){
            if(!this.selectedBudgetPriority){
                this.alertValidationMessage("priority");
                return;
            }
            if(!this.selectedMainAccount){
                this.alertValidationMessage("main account");
                return;
            }
            if(!this.selectedSubAccount){
                this.alertValidationMessage("sub account");
                return;
            }
            if(!this.date){
                this.alertValidationMessage("date");
                return;
            }
            if(!this.amount){
                this.alertValidationMessage("amount");
                return;
            }
            this.createBtnLoading = true;
            let formData = new FormData();
            formData.append('budget_priority_id', this.selectedBudgetPriority.id);
            formData.append('main_account_id', this.selectedMainAccount.id);
            formData.append('main_account_type', 'sub_account');
            formData.append('sub_account_id', this.selectedSubAccount.id);
            formData.append('sub_account_type', 'account');
            formData.append('date', this.date);
            formData.append('amount', this.amount);
            postApiData({url: `/api/budget_accounts`, form_data: formData, token: this.getToken()})
            .then((response)=>{
                this.createBtnLoading = false;
                if(response.success){
                    document.getElementById('create_modal_close_btn').click();
                    this.selectedBudgetPriority = null;
                    this.selectedMainAccount = null;
                    this.selectedSubAccount = null;
                    this.subAccounts = [];
                    this.amount = 0;
                    this.date = null;
                    this.getBudgetAccountList(1);
                }else{
                    this.$notify({
                        title: 'Error',
                        text: `${response.message}`,
                        type: 'error'
                    });
                }
            });
        },

        editBtnClicked(budgetAccount, index){
            this.selectedBudgetPriority = budgetAccount.budget_priority;
            this.selectedMainAccount = budgetAccount.main_account;
            this.mainAccountSelectChanged();
            this.selectedSubAccount = budgetAccount.sub_account;
            this.date = budgetAccount.date;
            this.amount = budgetAccount.amount;
            this.editId = budgetAccount.id;
            this.editIndex = index;
        },

        confirmEditBtnClicked(){
            if(!this.selectedBudgetPriority){
                this.alertValidationMessage("priority");
                return;
            }
            if(!this.selectedMainAccount){
                this.alertValidationMessage("main account");
                return;
            }
            if(!this.selectedSubAccount){
                this.alertValidationMessage("sub account");
                return;
            }
            if(!this.date){
                this.alertValidationMessage("date");
                return;
            }
            if(!this.amount){
                this.alertValidationMessage("amount");
                return;
            }
            this.updateBtnLoading = true;
            let formData = new FormData();
            formData.append('budget_priority_id', this.selectedBudgetPriority.id);
            formData.append('main_account_id', this.selectedMainAccount.id);
            formData.append('main_account_type', 'sub_account');
            formData.append('sub_account_id', this.selectedSubAccount.id);
            formData.append('sub_account_type', 'account');
            formData.append('date', this.date);
            formData.append('amount', this.amount);
            postApiData({url: `/api/budget_accounts/${this.editId}`, form_data: formData, token: this.getToken()})
            .then((response)=>{
                this.updateBtnLoading = false;
                if(response.success){
                    document.getElementById('update_modal_close_btn').click();
                    this.selectedBudgetPriority = null;
                    this.selectedMainAccount = null;
                    this.selectedSubAccount = null;
                    this.subAccounts = [];
                    this.amount = 0;
                    this.date = null;
                    this.getBudgetAccountList(1);
                }else{
                    this.$notify({
                        title: 'Error',
                        text: `${response.message}`,
                        type: 'error'
                    });
                }
            });
        },

        confirmBtnClicked(budgetAccount, index){
            this.editId = budgetAccount.id;
            this.editIndex = index;
        },

        confirmAccountBtnClicked(){
            this.updateBtnLoading = true;
            postApiData({url: `/api/budget_accounts/${this.editId}/confirm`, token: this.getToken()})
            .then((response)=>{
                this.updateBtnLoading = false;
                if(response.success){
                    document.getElementById('confirm_modal_close_btn').click();
                    this.editId = null;
                    this.editIndex = null;
                    this.getBudgetAccountList(1);
                }
            });
        },
    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getBudgetAccountList(1);
        this.getBudgetPriorities();
        this.getMainAccounts();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
