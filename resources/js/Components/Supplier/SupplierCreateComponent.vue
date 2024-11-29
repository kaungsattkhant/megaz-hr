<template>
    <div class="px-0">
        <div class="mb-4">
            <p class="text-lg font-semibold font-inter">
                Add New Supplier
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white p-8 rounded-md">
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Supplier Name
                </label>
                <input type="text" v-model="name"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Supplier Shop Name
                </label>
                <input type="text" v-model="shopName"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Supplier Ph Number
                </label>
                <input type="tel" v-model="phoneNumber"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Credit Limit
                </label>
                <input type="number" v-model="maxCredit"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-6 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Address
                </label>
                <textarea name="" v-model="address"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="10"></textarea>
            </div>
            <!-- <div class="col-span-6"></div> -->
            <div class="col-span-3 mb-4 flex gap-x-4">
                <div class=" flex-grow">
                    <label for="" class="block text-sm text-black mb-3">
                        AP Account
                    </label>
                    <multiselect
                    v-model="selectedAccount"
                    :options="apAccountList"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="true"
                    placeholder="Select Payable Acount"
                    label="name"
                    track-by="id"
                    :preselect-first="false"></multiselect>
                </div>
                <div class=" ">
                    <label for="" class="block text-sm text-black mb-3">
                        &nbsp;
                    </label>
                    <button class="add-btn mt-0.5" data-te-toggle="modal" data-te-target="#create_modal">
                        <i class="fal fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="col-span-3 mb-4 flex gap-x-4">
                <div class=" flex-grow">
                    <label for="" class="block text-sm text-black mb-3">
                        Creditor Account
                    </label>
                    <multiselect
                    v-model="selectedCreditAccount"
                    :options="creditAccList"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="true"
                    placeholder="Select Payable Acount"
                    label="name"
                    track-by="id"
                    :preselect-first="false"></multiselect>
                </div>
                <div class=" rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        &nbsp;
                    </label>
                    <button class="add-btn mt-0.5" data-te-toggle="modal" data-te-target="#create_credit_modal">
                        <i class="fal fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Items
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Item" v-model="selectedItem" class="input-ui !text-black"
                    data-te-select-filter="true" @change="itemSelected()" >
                        <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                            {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label class="label-form mb-3">Brands</label>
                <multiselect
                v-model="selectedItemBrands"
                :options="itemBrandsList"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Brands"
                label="name"
                track-by="id"
                :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        brands selected</span>
                    </template>
                </multiselect>
            </div>

            <div class="col-span-3">
                <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 " @click="addItemBtnClicked()" >
                    Add
                </button>
            </div>

            <div class=" col-span-12">
                <table class="min-w-[40%] text-sm font-light ">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                Item
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Brand(s)
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(selectedItem, selectedItemIndex) in selectedItems" :key="selectedItemIndex">
                            <td class=" px-6 py-4 font-medium ">
                                {{ selectedItem.name }}
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                <span class="text-sm"v-for="(brand) in selectedItem.brands" > {{ brand.name }}, </span>
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                <button>
                                    <i class="fal fa-trash  pr-3" @click="deleteSelectedItemBtnClicked(selectedItem.id)" ></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="">
                            <td class=" py-2 "></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <button class="add-btn" @click="createBtnClicked">
                Create Supplier
            </button>
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

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create Supplier AP Account
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
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
                                Name
                            </label>
                            <input type="text" v-model="apAccountName" placeholder="AP Account Name"  class="input-ui">
                        </div>

                        <div class="mb-4">
                            <div>
                                <label class="label-form mb-3">AP Sub Account</label>
                                <multiselect v-model="selectedPayableSubAccount" :options="payableSubAccountList" :close-on-select="true"
                                :clear-on-select="false" :preserve-search="true" placeholder="Select Sub Account" label="name"
                                track-by="id" :preselect-first="true"></multiselect>
                            </div>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="createAPAccountBtnClicked"
                            class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- credit Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="create_credit_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create Credit Account
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
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
                            Name
                        </label>
                        <input type="text" v-model="creditAccountName" placeholder="Credit Account Name"  class="input-ui">
                    </div>
                    <div class="mb-4">
                        <div>
                            <label class="label-form mb-3">Credit Sub Account</label>
                            <multiselect v-model="selectedCreditSubAcc" :options="creditSubAccList" :close-on-select="true"
                                :clear-on-select="false" :preserve-search="true" placeholder="Select Sub Account" label="name"
                                track-by="id" :preselect-first="true"></multiselect>
                        </div>
                    </div>
                </div>

                    <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="createCreditAccountBtnClicked"
                        class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            itemList: [],
            apAccountList: [],
            creditAccList:[],
            selectedItems: [],
            selectedItem: null,

            selectedAccount: null,
            selectedCreditAccount:null,
            name: null,
            shopName: null,
            phoneNumber: null,
            address: null,
            maxCredit: null,

            payableSubAccountList: [],
            selectedPayableSubAccount: null,
            apAccountName: null,

            creditSubAccList:[],
            creditAccountName:null,
            selectedCreditSubAcc:null,

            itemBrandsList: [],
            selectedItemBrands: []
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        deleteSelectedItemBtnClicked(id){
            let index = this.selectedItems.findIndex(item => item.id == id);
            if(index != -1){
                this.selectedItems.splice(index, 1);
            }
        },

        async getItemList(){
            let url = `/api/items`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.itemList = response.data;
            }
        },

        async getAPAccounts(){
            let url = `/api/get_payable_account`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.apAccountList = response.data;
            }
        },
        async getCreditAccList(){
            let url = `/api/get_creditor_account_list`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.creditAccList = response.data;
            }
        },

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        async itemSelected(){
            this.selectedItemBrands = [];
            let url = `/api/get_brand_by_item?item_ids[]=${this.selectedItem.id}`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.success){
                this.itemBrandsList = response.data;
            }
        },

        addItemBtnClicked(){
            if(this.selectedItemBrands.length < 1){
                alertValiationMessage(`brands for item`);
                return;
            }
            this.selectedItems.push({
                name: this.selectedItem.name,
                id: this.selectedItem.id,
                brands: this.selectedItemBrands,
            });

            this.selectedItem = null;
            this.selectedItemBrands = [];
            this.itemBrandsList = [];
        },

        async createBtnClicked(){
            if(!this.name){
                this.alertValidationMessage(`supplier name`);
                return 1;
            }
            if(!this.shopName){
                this.alertValidationMessage(`supplier shop`);
                return 1;
            }
            if(!this.phoneNumber){
                this.alertValidationMessage(`supplier phone number`);
                return 1;
            }
            if(!this.address){
                this.alertValidationMessage(`supplier address`);
                return 1;
            }
            if(this.selectedItems.length < 1){
                this.alertValidationMessage(`supplier selling items`);
                return 1;
            }
            if(!this.maxCredit){
                this.alertValidationMessage(`supplier credit limit`);
                return 1;
            }
            if(!this.selectedAccount){
                this.alertValidationMessage(`supplier account payable`);
                return 1;
            }
            if(!this.selectedCreditAccount){
                this.alertValidationMessage(`Credit account`);
                return 1;
            }

            let formData = new FormData();
            formData.append("name", this.name);
            formData.append("shop_name", this.shopName);
            formData.append("phone_number", this.phoneNumber);
            formData.append("credit_limit", this.maxCredit);
            formData.append("address", this.address);
            formData.append("account_id", this.selectedAccount.id);
            formData.append("creditor_account_id", this.selectedCreditAccount.id);
            this.selectedItems.forEach((item)=>{
                formData.append("items[]", item.id);
            });

            let url = `/api/suppliers`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                // console.log(response);
                window.location.replace("/suppliers");
            }
        },

        async getPayableSubAccountList(){
            let url = `/api/sub_account_by_head_account/4`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.payableSubAccountList = response.data;
                this.creditSubAccList = response.data;
            }
        },

        async createAPAccountBtnClicked(){
            let url = `/api/create_payable_account`;
            let formData = new FormData();
            formData.append("name", this.apAccountName);
            formData.append("sub_account_id", this.selectedPayableSubAccount.id);
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.apAccountList.unshift(response.data);
                this.selectedAccount = response.data;
            }
        },
        async createCreditAccountBtnClicked(){
            let url = `/api/create_creditor_account`;
            let formData = new FormData();
            formData.append("name", this.creditAccountName);
            formData.append("sub_account_id", this.selectedCreditSubAcc.id);
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.apAccountList.unshift(response.data);
                this.selectedCreditAccount = response.data;
            }
        },
    },

    created(){
        this.getAPAccounts();
        this.getItemList();
        this.getPayableSubAccountList();
        this.getCreditAccList();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
