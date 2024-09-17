<template>
    <div class="mt-4 bg-white">
        <notifications position="top center" />

        <h1 class="font-bold text-lg text-left px-8 pt-8 mb-4">Asset</h1>
        <div class=" px-8 pt-3 pb-8 flex justify-center">
            <div class="w-full justify-center flex">
                <form @submit.prevent="assetCreate" class="flex justify-center w-full ">
                    <div class="w-full grid grid-cols-11 gap-x-6">
                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" v-model="assetName"
                                class="py-2 px-2 w-full rounded-md border border-gray-300" placeholder="Name">
                        </div>

                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Cost
                            </label>
                            <input type="number" v-model="assetCost"
                                class="py-2 px-2 w-full rounded-md border border-gray-300" placeholder="Cost">
                        </div><div></div>

                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Quantity
                            </label>
                            <input type="number" v-model="assetQuantity"
                                class="py-2 px-2 w-full rounded-md border border-gray-300" placeholder="Quantity">
                        </div>

                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Useful Life (Month)
                            </label>
                            <input type="number" v-model="assetMonths"
                                class="py-2 px-2 w-full rounded-md border border-gray-300"
                                placeholder="Useful Life(Month)">
                        </div><div></div>

                        

                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Sub Account
                            </label>
                            <select placeholder="Unit" v-model="selectedSubAccForAssetItem" @change="subAccForAssetItemChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(subAcc, index) in subAccountList' :key=index
                                    :value=subAcc>{{ subAcc.name }}</option>
                            </select>
                        </div>
                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Third Account
                            </label>
                            <div class=" flex justify-center relative">
                                <select placeholder="Third Account" v-model="thirdAccount"
                                    class="py-2 px-2 w-full rounded-md border border-gray-300">
                                    <option :value="null" disabled selected>Third Account</option>
                                    <option v-for='(thirdAccount, index) in thirdAccountListForAsset' :key=index
                                        :value=thirdAccount.id>{{ thirdAccount.name }}</option>
                                </select>
                                <!-- <input type="text" class="py-2 px-2 w-11/12 rounded-md border border-gray-300" placeholder="Second Account"> -->

                                <button type="button"
                                    class="absolute -right-20 transition duration-150 ease-in-out focus:outline-none focus:ring-0 w-1/12 "
                                    data-te-toggle="modal" data-te-target="#createSecond">
                                    <i class="far fa-plus-circle text-xl"></i>

                                </button>
                            </div>

                        </div><div></div>

                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Sub Account
                            </label>
                            <select placeholder="Unit" v-model="selectedSubDepreciationAccForAssetItem" @change="subDepreciationAccForAssetItemChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(subDeAcc, index) in subDepreciationAccountList' :key=index
                                    :value=subDeAcc>{{ subDeAcc.name }}</option>
                            </select>
                        </div>
                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Third Account Depreciation
                            </label>
                            <div class="relative">
                                <select placeholder="Second Account Depreciation" v-model="thirdAccountDepreciation"
                                    @change="getAssetItemList()"
                                    class="py-2 px-2 w-full rounded-md border border-gray-300">
                                    <option :value="null" disabled selected>Third Account Depreciation</option>
                                    <option v-for='(thirdAccountDepreciation, index) in thirdAccountDepreciationForAsset'
                                        :key=index :value=thirdAccountDepreciation.id>{{ thirdAccountDepreciation.name }}
                                    </option>
                                </select>
                                <button type="button"
                                    class="absolute -right-20 transition duration-150 ease-in-out focus:outline-none focus:ring-0 w-1/12 "
                                    data-te-toggle="modal" data-te-target="#createSecondDepreciation">
                                    <i class="far fa-plus-circle text-xl"></i>

                                </button>
                            </div>
                        </div><div></div>

                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Item
                            </label>
                            <select placeholder="Item" v-model="itemId"
                                class="py-2 px-2 w-full rounded-md border border-gray-300">
                                <option :value="null" disabled selected>Item</option>
                                <option v-for='(item, index) in itemList' :key=index :value=item.id>{{ item.name }}
                                </option>
                            </select>

                        </div>

                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Inventory
                            </label>
                            <select placeholder="Inventory" v-model="inventoryId"
                                class="py-2 px-2 w-full rounded-md border border-gray-300">
                                <option :value="null" disabled selected>Inventory</option>
                                <option v-for='(inventory, index) in inventoryList' :key=index :value=inventory.id>{{
                                    inventory.name }}</option>
                            </select>

                        </div>
                        <div class="mb-5 relative col-span-5">
                            <label for="" class="label-form mb-3">
                                Purchase Date
                            </label>
                            <input type="date" v-model="purchaseDate"
                                class="py-2 px-2 w-full rounded-md border border-gray-300" placeholder="Purchase Date">
                        </div>
                        <div class="mb-5 col-span-5">
                            <label for="" class="label-form mb-3">
                                Cash Account
                            </label>
                            <select placeholder="Cash Account " v-model="cashAccountId"
                                class="py-2 px-2 w-full rounded-md border border-gray-300">
                                <option :value="null" disabled selected>Cash Account</option>
                                <option v-for='(cashAccount, index) in cashAccountList' :key=index
                                    :value=cashAccount.id>{{ cashAccount.name }}</option>
                            </select>

                        </div><div></div>

                        <div class="w-full flex justify-end col-span-10 pt-4">
                            <button type="submit" class="py-2 px-8 add-btn rounded-md text-white ">Confirm</button>
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
                        Create Third Account
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
                <form @submit.prevent="createThirdAccount">
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" placeholder="Third Account Name" v-model="thirdAccountName"
                                class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Sub Account
                            </label>
                            <select placeholder="Unit" v-model="selectedSubAccount" @change="subAccountForThirdChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(subAcc, index) in subAccountList' :key=index
                                    :value=subAcc>{{ subAcc.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Accounts
                            </label>
                            <select placeholder="Unit" v-model="thirdAccountOBJ"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(thirdAccount, index) in thirdAccountList' :key=index
                                    :value=thirdAccount>{{ thirdAccount.name }}</option>
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
                        Create Third Depreciation Account
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
                            <input type="text" placeholder="Third Account Depreciation Name"
                                v-model="thirdDepreciationName" class="input-ui">
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
                            <select placeholder="Accounts" v-model="thirdDepreciationOBJ"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for='(thirdDepreciation, index) in thirdDepreciationList' :key=index
                                    :value=thirdDepreciation>{{ thirdDepreciation.name }}
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
            thirdAccountName: null,
            thirdAccountOBJ: null,
            thirdDepreciationName: null,
            thirdDepreciationOBJ: null,
            thirdAccountList: [],
            secondAccountDepreciationList: [],
            thirdAccountListForAsset: [],
            thirdAccountDepreciationList: [],
            thirdAccount: null,
            thirdAccountDepreciation: null,
            assetName: null,
            assetCost: null,
            assetQuantity: null,
            assetMonths: null,
            purchaseDate: null,
            thirdAccountId: null,
            thirdDepreciationAcc: null,
            itemId: null,
            inventoryId: null,
            itemList: [],
            inventoryList: [],
            thirdDepreciationList: [],
            thirdAccountDepreciationForAsset: [],
            cashAccountList: [],
            cashAccountId: null,


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

        async getSubAccountForThird() {
            let url = `/api/get_depreciation_account_list?is_depreciation=0`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.subAccountList = response.data;
            }
        },
        subAccountForThirdChange(){
            this.getAccountForThird();
        },
        async getAccountForThird() {
            let url = `/api/get_second_account?type=is_second&sub_account_id=` + this.selectedSubAccount.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.thirdAccountList = response.data;
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
            this.getAccountForThirdDepreciation();
        },
        async getAccountForThirdDepreciation() {
            let url = `/api/get_second_account?type=is_second_depreciation&sub_account_id=` + this.selectedSubDepreciationAccount.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.thirdDepreciationList = response.data;
            }
            // console.log(this.secondAccoun)
        },


        subAccForAssetItemChange(){
            this.getThirdAccountList();
        },
        async getThirdAccountList() {
            let url = `/api/get_third_account?type=is_third&sub_account_id=` + this.selectedSubAccForAssetItem.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.thirdAccountListForAsset = response.data;
            }
        },

        subDepreciationAccForAssetItemChange(){
            this.thirdDepreciationForAsset()
        },
        async thirdDepreciationForAsset() {
            let url = `/api/get_third_account?type=is_third_depreciation&sub_account_id=` + this.selectedSubDepreciationAccForAssetItem.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.thirdAccountDepreciationForAsset = response.data;
            }
        },

        async getAssetItemList() {
            console.log(this.thirdAccountDepreciation)
            let url = `/api/get_asset_item_by_account?third_account_id=${this.thirdAccount}&third_depreciation_id=` + this.thirdAccountDepreciation;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.itemList = response.data;
            }
        },

        async getInventoryList() {
            let url = `/api/get_inventory`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.inventoryList = response.data;
            }
        },

        async getCashAccount() {
            let url = `/api/get_cash_account`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.cashAccountList = response.data;
            }
        },

        async createThirdAccount() {
            let url = `/api/create_third_account`;
            let formData = new FormData();
            let validate = this.formValidate([{ "Third Account Name": this.thirdAccountName, "Account": this.thirdAccountOBJ }])
            if (validate == false) {
                return false;
            }
            formData.append('name', this.thirdAccountName);
            formData.append('account_id', this.thirdAccountOBJ.id);
            formData.append('original_account_code', this.thirdAccountOBJ.account_code);
            formData.append('sub_account_id', this.thirdAccountOBJ.sub_account_id);
            formData.append('type', 'is_third');

            let response = await postApiData({ url: url, token: this.getToken(), form_data: formData });
            if (response.success == true) {
                this.thirdAccountName = '',
                    this.thirdAccountOBJ = null;
                this.getThirdAccountList();
                this.$notify({
                    title: `Input validation`,
                    text: `Third Account created successfully`,
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
            let url = `/api/create_third_account`;
            let formData = new FormData();
            let validate = this.formValidate([{ "Third Depreciation Name": this.thirdDepreciationName, "Account": this.thirdDepreciationOBJ }]);
            if (validate == false) {
                return false;
            }
            formData.append('name', this.thirdDepreciationName);
            formData.append('account_id', this.thirdDepreciationOBJ.id);
            formData.append('original_account_code', this.thirdDepreciationOBJ.account_code);
            formData.append('sub_account_id', this.thirdDepreciationOBJ.sub_account_id);
            formData.append('type', 'is_third_depreciation');

            let response = await postApiData({ url: url, token: this.getToken(), form_data: formData });
            if (response.success == true) {
                this.thirdDepreciationName = '',
                    this.thirdDepreciationOBJ = null;
                this.thirdDepreciationForAsset();

                this.$notify({
                    title: `Input validation`,
                    text: `Third Account Depreciation created successfully`,
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

        async assetCreate() {
            let formData = new FormData();

            let validate = this.formValidate([{'Asset Name':this.assetName,
                                                'Cost':this.assetCost,
                                                "Quantity":this.assetQuantity,
                                                'Userful Life':this.assetMonths,
                                                "Purchase Date":this.purchaseDate,
                                                "Third Account":this.thirdAccount,
                                                "Third Depreciation Account":this.thirdAccountDepreciation,
                                                "Inventory":this.inventoryId,
                                                "Asset Item":this.itemId,
                                                "Cash Account":this.cashAccountId
                                            }]);
            if(validate==false)
            {
                return false;
            }

            formData.append('name', this.assetName);
            formData.append('cost', this.assetCost);
            formData.append('useful_life', this.assetMonths);
            formData.append('purchase_date', this.purchaseDate);
            formData.append('third_account_id', this.thirdAccount);
            formData.append('third_depreciation_account_id', this.thirdAccountDepreciation)
            // formData.append('cash_account_id',)
            formData.append('inventory_id', this.inventoryId);
            formData.append('asset_item_id', this.itemId);
            formData.append('quantity', this.assetQuantity);
            formData.append('cash_account_id', this.cashAccountId);


            let url = `/api/create_asset`;
            let responseData = await postApiData({ url: url, token: this.getToken(), form_data: formData });

            if (responseData.success == true) {
                this.assetName = '';
                this.assetCost = '';
                this.assetMonths = null;
                this.purchaseDate = null;
                this.thirdAccount = null;
                this.thirdAccountDepreciation = null;
                this.inventoryId = null;
                this.itemId = null;
                this.assetQuantity = null;
                this.cashAccountId = null;

                this.$notify({
                    title: `Input validation`,
                    text: `Asset created successfully`,
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
        this.getSubAccountForDepreciation();
        this.getSubAccountForThird();
        // this.getAccountForThird();
        // this.getAccountForThirdDepreciation();
        // this.getThirdAccountList();
        // this.thirdDepreciationForAsset();
        this.getInventoryList();
        this.getCashAccount();
    },

    mounted() {
        initTE({ Modal });
    }
}
</script>
