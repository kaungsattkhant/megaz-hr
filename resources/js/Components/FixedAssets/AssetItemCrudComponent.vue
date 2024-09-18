<template>
    <div class="mt-4 bg-white">
        <notifications position="top center" />

        <h1 class="font-bold text-lg text-center p-3">Asset Items</h1>
        <div class="p-3 flex justify-center">
            <div class="w-full p-1 justify-center flex">
                <form @submit.prevent="assetItemCreate" class="flex justify-center w-3/5 ">
                    <div class="w-full">
                        <div class="my-5">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" v-model="assetItemName"
                                class="py-2 px-2 w-full rounded-md border border-gray-300" placeholder="Name">
                        </div>

                        <div class="my-5">
                            <label for="" class="label-form mb-3">
                                Item Code
                            </label>
                            <input type="text" v-model="assetItemCode"
                                class="py-2 px-2 w-full rounded-md border border-gray-300" placeholder="Item Code">
                        </div>


                        <div class="my-5">
                            <label for="" class="label-form mb-3">
                                Sub Account
                            </label>
                            <select placeholder="Unit" v-model="selectedSubAccForAssetItem" @change="subAccForAssetItemChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(subAcc, index) in subAccountList' :key=index
                                    :value=subAcc>{{ subAcc.name }}</option>
                            </select>
                        </div>
                        
                        <div class="my-5">
                            <label for="" class="label-form mb-3">
                                Second Account
                            </label>
                            <div class=" flex justify-center relative">
                                <select placeholder="Second Account" v-model="secondAccount"
                                    class="py-2 px-2 w-full rounded-md border border-gray-300">
                                    <option :value="null" disabled selected>Secound Account</option>
                                    <option v-for='(secondAccount, index) in secondAccountListForAssetItem' :key=index
                                        :value=secondAccount.id>{{ secondAccount.name }}</option>
                                </select>
                                <!-- <input type="text" class="py-2 px-2 w-11/12 rounded-md border border-gray-300" placeholder="Second Account"> -->

                                <button type="button"
                                    class=" absolute -right-20 transition duration-150 ease-in-out focus:outline-none focus:ring-0 w-1/12 "
                                    data-te-toggle="modal" data-te-target="#createSecond">
                                    <i class="far fa-plus-circle text-xl"></i>

                                </button>
                            </div>

                        </div>
                        <div class="my-5">
                            <label for="" class="label-form mb-3">
                                Sub Account
                            </label>
                            <select placeholder="Unit" v-model="selectedSubDepreciationAccForAssetItem" @change="subDepreciationAccForAssetItemChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(subDeAcc, index) in subDepreciationAccountList' :key=index
                                    :value=subDeAcc>{{ subDeAcc.name }}</option>
                            </select>
                        </div>

                        <div class="my-5">
                            <label for="" class="label-form mb-3">
                                Second Account Depreciation
                            </label>
                            <div class="relative">
                                <select placeholder="Second Account Depreciation" v-model="secondAccountDepreciation"
                                    class="py-2 px-2 w-full rounded-md border border-gray-300">
                                    <option :value="null" disabled selected>Second Account Depreciation</option>
                                    <option
                                        v-for='(secondAccountDepreciation, index) in secondAccountListDepreciationForAssetItem'
                                        :key=index :value=secondAccountDepreciation.id>{{ secondAccountDepreciation.name }}
                                    </option>
                                </select>
                                <button type="button"
                                    class=" absolute -right-20 transition duration-150 ease-in-out focus:outline-none focus:ring-0 w-1/12 "
                                    data-te-toggle="modal" data-te-target="#createSecondDepreciation">
                                    <i class="far fa-plus-circle text-xl"></i>

                                </button>
                            </div>
                        </div>

                        <div class="w-full flex justify-center">
                            <button type="submit" class="py-2 px-4 add-btn rounded-md text-white ">Confirm</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!-- second account -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="createSecond" tabindex="-1" aria-labelledby="createSecondLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <!--Modal title-->
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="createSecondLabel">
                        Create Second Account
                    </h5>
                    <!--Close button-->
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <form @submit.prevent="createSecondAccount">
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" placeholder="Second Account Name" v-model="secondAccountName"
                                class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Sub Account
                            </label>
                            <select placeholder="Unit" v-model="selectedSubAccount" @change="subAccountForSecondChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(subAcc, index) in subAccountList' :key=index
                                    :value=subAcc>{{ subAcc.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Accounts
                            </label>
                            <select placeholder="Unit" v-model="secondAccountOBJ"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(secondAccount, index) in secondAccountList' :key=index
                                    :value=secondAccount>{{ secondAccount.name }}</option>
                            </select>
                        </div>
                    </div>
                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="submit" class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="createSecondDepreciation" tabindex="-1" aria-labelledby="createSecondDepreciationLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <!--Modal title-->
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="createSecondDepreciationLabel">
                        Create Second Depreciation Account
                    </h5>
                    <!--Close button-->
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <form @submit.prevent="createDepreciationAccount">
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" placeholder="Second Account Depreciation Name"
                                v-model="secondDepreciationName" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Sub Account
                            </label>
                            <select placeholder="Unit" v-model="selectedSubDepreciationAccount" @change="subAccountForDepreciationChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(subDeAcc, index) in subDepreciationAccountList' :key=index
                                    :value=subDeAcc>{{ subDeAcc.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Accounts
                            </label>
                            <select placeholder="Accounts" v-model="secondDepreciationAccountOBJ"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(secondAccountDepreciation, index) in secondAccountDepreciationList'
                                    :key=index :value=secondAccountDepreciation>{{ secondAccountDepreciation.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="submit" class="add-btn focus:outline-none focus:ring-0 "
                            @click="confirmCreateBtnClicked" data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, initTE } from "tw-elements";

import { mapGetters } from 'vuex';
import { getApiData, postApiData } from '../../utilities/ajax-helpers';

export default {
    data() {
        return {
            per_page: 20,
            currentPage: 1,
            pageNumbers: [],
            paginationGroupsCount: 1,
            groupedPageNumbers: [],
            currentGroup: 0,
            isFirstGroup: true,
            isLastGroup: false,
            secondAccountName: null,
            secondAccountOBJ: null,
            secondDepreciationName: null,
            secondDepreciationAccountOBJ: null,
            secondAccountList: [],
            secondAccountDepreciationList: [],
            secondAccountListForAssetItem: [],
            secondAccountListDepreciationForAssetItem: [],
            secondAccount: null,
            secondAccountDepreciation: null,
            assetItemCode: null,
            assetItemName: null,

            subAccountList:[],
            selectedSubAccount:null,
            subDepreciationAccountList:[],
            selectedSubDepreciationAccount:null,

            selectedSubAccForAssetItem:null,
            selectedSubDepreciationAccForAssetItem:null,

        };
    },

    methods: {
        ...mapGetters(['getToken', 'getUser']),

        formValidate(valueArray) {
            for (const obj of valueArray) {
                for (const [key, value] of Object.entries(obj)) {
                    if (value === null || value === "") {
                        this.$notify({
                            title: 'Input validation',
                            text: `${key} is required`,
                            type: 'warn'
                        });
                        return false;
                    }
                }
            }
            return true;
        },

        async getSubAccountForSecond() {
            let url = `/api/get_depreciation_account_list?is_depreciation=0`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.subAccountList = response.data;
            }
        },
        subAccountForSecondChange(){
            this.getAccountForSecond();
        },
        async getAccountForSecond() {
            let url = `/api/account_by_sub_account/` + this.selectedSubAccount.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.secondAccountList = response.data;
            }
        },

        async getSubAccountForDepreciation() {
            let url = `/api/get_depreciation_account_list?is_depreciation=1`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.subDepreciationAccountList = response.data;
            }
        },
        subAccountForDepreciationChange(){
            this.getAccountForSecondDepreciation();
        },

        async getAccountForSecondDepreciation() {
            let url = `/api/account_by_sub_account/` + this.selectedSubDepreciationAccount.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.secondAccountDepreciationList = response.data;
            }
        },


        subAccForAssetItemChange(){
            this.secondAccountsForAssetItem()
        },
        async secondAccountsForAssetItem() {
            let url = `/api/get_second_account?type=is_second&sub_account_id=` + this.selectedSubAccForAssetItem.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.secondAccountListForAssetItem = response.data;
            }
        },
        subDepreciationAccForAssetItemChange(){
            this.secondAccountDepreciationForAssetItem()
        },
        async secondAccountDepreciationForAssetItem() {
            let url = `/api/get_second_account?type=is_second_depreciation&sub_account_id=` + this.selectedSubDepreciationAccForAssetItem.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.secondAccountListDepreciationForAssetItem = response.data;
            }
        },

        async createSecondAccount() {
            let url = `/api/create_second_account`;
            let formData = new FormData();
            let validate = this.formValidate([{ "Second Account Name": this.secondAccountName, "Account": this.secondAccountOBJ }]);
            if (validate == false) {
                return false
            }
            formData.append('name', this.secondAccountName);
            formData.append('account_id', this.secondAccountOBJ.id);
            formData.append('original_account_code', this.secondAccountOBJ.account_code);
            formData.append('sub_account_id', this.selectedSubAccount.id);
            formData.append('type', 'is_second');

            let response = await postApiData({ url: url, token: this.getToken(), form_data: formData });
            if (response.success == true) {
                this.secondAccountName = '',
                    this.secondAccountOBJ = null;
                this.secondAccountsForAssetItem();
                this.$notify({
                    title: `Input validation`,
                    text: `Second Account created successfully`,
                    type: "success"
                });
            } else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

        async createDepreciationAccount() {
            let url = `/api/create_second_account`;
            let formData = new FormData();

            let validate = this.formValidate([{ "Second Account Depreciation Name": this.secondDepreciationName, "Account": this.secondDepreciationAccountOBJ }]);
            if (validate == false) {
                return false;
            }
            formData.append('name', this.secondDepreciationName);
            formData.append('account_id', this.secondDepreciationAccountOBJ.id);
            formData.append('original_account_code', this.secondDepreciationAccountOBJ.account_code);
            formData.append('sub_account_id', this.selectedSubDepreciationAccount.id);
            formData.append('type', 'is_second_depreciation');

            let response = await postApiData({ url: url, token: this.getToken(), form_data: formData });
            if (response.success == true) {
                this.secondDepreciationName = '',
                    this.secondDepreciationAccountOBJ = null;
                this.secondAccountDepreciationForAssetItem();
                this.$notify({
                    title: `Input validation`,
                    text: `Second Depreciation Account created successfully`,
                    type: "success"
                });
            } else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

        async assetItemCreate() {
            let formData = new FormData();
            // this.formValidate([this.assetItemName,this.assetItemCode,this.secondAccount,this.secondAccountDepreciation]);
            let validate = this.formValidate([{
                "Asset Item": this.assetItemName,
                "Item Code": this.assetItemCode,
                "Second Account": this.secondAccount,
                "Second Account Depreciation": this.secondAccountDepreciation
            }]);
            if (validate == false) {
                return false;
            }
            formData.append('name', this.assetItemName);
            formData.append('item_code', this.assetItemCode);
            formData.append('second_account_id', this.secondAccount);
            formData.append('second_depreciation_account_id', this.secondAccountDepreciation);

            let url = `/api/create_asset_item`;
            let responseData = await postApiData({ url: url, token: this.getToken(), form_data: formData });

            if (responseData.success == true) {
                this.assetItemName = '';
                this.assetItemCode = '';
                this.secondAccount = null;
                this.secondAccountDepreciation = null;
                this.$notify({
                    title: `Input validation`,
                    text: `Asset Item created successfully`,
                    type: "success"
                });
            } else {
                this.$notify({
                    title: `Input validation`,
                    text: responseData.message,
                    type: "warn"
                });
            }
        }
    },

    created() {
        this.getSubAccountForSecond();
        this.getSubAccountForDepreciation();
        this.getAccountForSecond();
        this.getAccountForSecondDepreciation();
        this.secondAccountsForAssetItem();
        this.secondAccountDepreciationForAssetItem();
    },

    mounted() {
        initTE({ Modal });
    }
}
</script>
