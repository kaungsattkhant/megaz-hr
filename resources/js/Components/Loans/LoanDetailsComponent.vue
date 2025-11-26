<template>
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Loan Details ({{ this.creditorName }})
                </p>
            </div>
            <div class="btn-container">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="date" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                </div>
                <div class="flex pr-0 gap-x-4">
                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
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
                                        {{ perPage * (currentPage - 1) + (index + 1) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ convertToFriendlyDate(item.date) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.type }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ parseFloat(item.amount).toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ parseFloat(item.balance).toLocaleString() }}
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

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
                            Add New
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
                            <multiselect
                            v-model="type"
                            :options="['addition','settlement','interest_settlement']"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="true"
                            placeholder="Type"
                            ></multiselect>
                        </div>
                        <div class="mb-6 col-span-3" v-if="type == 'settlement' || type=='interest_settlement'">
                            <label for="" class="label-form mb-3">
                                Category
                            </label>
                            <multiselect
                            v-model="category"
                            :options="['loan','interest']"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="true"
                            placeholder="Category"
                            ></multiselect>
                        </div>

                        <div class="mb-6 col-span-3">
                            <label for="" class="label-form mb-3">
                                Cash Account
                            </label>
                            <multiselect
                            v-model="selectedCashAccount"
                            :options="cashAccountList"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="true"
                            label="name"
                            track-by="id"
                            placeholder="Choose cash account"
                            ></multiselect>
                        </div>

                        <div class="mb-6 col-span-3">
                            <label for="" class="label-form mb-3">
                                Amount
                            </label>
                            <input type="number" placeholder="Amount" v-model="amount" class="input-ui">
                        </div>

                        <div class="mb-6 col-span-3">
                            <label for="" class="label-form mb-3">
                                Interest Rate
                            </label>
                            <input type="number" placeholder="Interest (%)" v-model="interestRate" class="input-ui">
                        </div>
                    </div>

                        <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                        </button>
                        <button type="button" @click="confirmCreateNewBtnClicked()"
                                class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
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
import { convertToFriendlyDate } from "../../utilities/datetime-helpers";

export default {
    components: {
        Multiselect
    },
    props: ["accountId"],
    data() {
        return {
            primaryList: [],
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            creditorName: null,

            cashAccountList: [],

            type: null,
            category: null,
            selectedCashAccount: null,
            amount: 0,
            interestRate: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        alertValidationMessage(message, title='Input validation', type='warn') {
            this.$notify({
                title: title,
                text: message,
                type: type
            });
        },

        getCashAccountList() {
            let url = `/api/get_cash_account`;
            getApiData({ url: url, token: this.getToken() }).then((response)=>{
                if (response.data) {
                    this.cashAccountList = response.data;
                }
            });
        },

        getPrimaryList(pageNumber){
            getApiData({url: `/api/loans/${this.accountId}`, token: this.getToken()}).then((response)=>{
                if(response.success){
                    this.primaryList = response.data.data;
                    this.creditorName = this.primaryList[0].account_name;
                    this.lastPage = response.data.last_page;
                    this.currentPage = pageNumber;
                    this.perPage = response.data.per_page;
                }
            });
        },

        convertToFriendlyDate(datetimestr){
            return convertToFriendlyDate(datetimestr);
        },

        confirmCreateNewBtnClicked(){
            if(!this.type){
                this.alertValidationMessage('Type must be selected');
                return;
            }
            if(this.type != 'addition' && !this.category){
                this.alertValidationMessage('Category must be selected when settling');
                return;
            }
            if(this.type == 'addition' && !this.selectedCashAccount){
                this.alertValidationMessage('Cash account must be selected');
                return;
            }
            if(!this.amount){
                this.alertValidationMessage('Amount is required');
                return;
            }
            if(this.type == 'addition' && !this.interestRate){
                this.alertValidationMessage('Interest rate is required for addition');
                return;
            }

            let formData = new FormData();
            formData.append('type', this.type);
            if(this.category){
                formData.append('category', this.category);
            }
            formData.append('account_id', this.accountId);
            formData.append('account_code', this.primaryList[0].account_code);
            if(this.selectedCashAccount){
                formData.append('cash_account_id', this.selectedCashAccount.id);
            }
            formData.append('amount', this.amount);
            if(this.interestRate){
                formData.append('interest_rate', this.interestRate);
            }
            postApiData({url: `/api/loans`, form_data: formData, token: this.getToken()}).then((response)=>{
                if(response.success){
                    this.type = null;
                    this.category = null;
                    this.selectedCashAccount = null;
                    this.amount = 0;
                    this.interestRate = 0;
                    this.getPrimaryList(this.currentPage);
                }else{
                    if(response.message){
                        this.alertValidationMessage(response.message, 'Error', 'error');
                    }
                }
            });
        },
    },

    created() {
        this.getPrimaryList(1);
        this.getCashAccountList();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    },
}
</script>
