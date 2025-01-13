<template>
    <div class="px-0">
        <div class="mb-3">
            <p class="text-lg font-semibold font-inter">
                Edit Supplier
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
            <div class="col-span-6">
                <div class="flex justify-between mb-4">
                    <label for="" class="block text-base text-black mb-3">
                        Account
                    </label>
                    <button class="add-btn mt-0.5" data-te-toggle="modal" data-te-target="#create_modal">
                        <i class="fal fa-plus"></i>
                    </button>
                </div>
                <div>
                    <p class="mb-3 text-sm">
                        <span class="text-gray-600 pr-2 w-32 inline-block">AP Account</span>  : <span class="pl-1">{{ selectedAccount ? selectedAccount.name : '' }}</span>
                    </p>
                    <p class="text-sm">
                        <span class="text-gray-600 pr-2 w-32 inline-block">Creditor Account</span> : <span class="pl-1">{{ selectedCreditAccount ? selectedCreditAccount.name : '' }}</span>
                    </p>
                </div>
            </div>
            
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Items
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Items" v-model="selectedItem"  class="input-ui !text-black"
                    data-te-select-filter="true" @change="itemSelectChanged">
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
                                Brand
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr class="" v-for="(existingItem, existingItemIndex) in existingItems" :key="existingItemIndex">
                            <td class=" px-6 py-2 font-medium ">
                                {{ existingItem.item_name }}
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                {{ existingItem.brand_name }}
                                <span v-for="(brand,index) in existingItem.brands" class="after:content-[','] last:after:hidden pr-1">
                                    {{ brand.name }}
                                </span>
                            </td>
                            <td class=" px-6 py-2 font-medium ">
                                <button>
                                    <i class="fal fa-trash  pr-3" @click="deleteExistingItemBtnClicked(existingItem.id)" ></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="">
                            <td class=" "></td>
                        </tr> -->

                        <tr v-for="(selectedItem,selectedItemIndex) in selectedItemList" >
                            <td class=" px-6 py-4 font-medium ">
                                {{ selectedItem.item_name }}
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                {{ selectedItem.brand_name }}
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                <button>
                                    <i class="fal fa-trash  pr-3" @click="deleteSelectedItemBtnClicked(selectedItemIndex)" ></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="">
                            <td class=" "></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <button class="add-btn" @click="createBtnClicked">
                Update Supplier
            </button>
        </div>
    </div>

        <!-- Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
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
                        <input type="text" v-model="accName" placeholder="AP Account Name"  class="input-ui">
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="createAccountBtnClicked()"
                        class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Create
                    </button>
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
    props: ["supplierId"],

    data() {
        return {
            itemList: [],
            apAccountList: [],
            creditAccList:[],
            selectedItemList: [],
            existingItems: [],

            accName:null,
            selectedAccount: null,
            selectedCreditAccount:null,
            supplier: null,
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
            selectedItem:null,
            selectedItemBrands: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        deleteSelectedItemBtnClicked(index){
            // let index = this.selectedItemList.findIndex(item => item.id == id);
            if(index != -1){
                this.selectedItemList.splice(index, 1);
            }
        },

        deleteExistingItemBtnClicked(id){
            let index = this.existingItems.findIndex(item => item.id == id);
            if(index != -1){
                this.existingItems.splice(index, 1);
            }
        },

        async itemSelectChanged(){
            this.selectedItemBrands = [];
            let url = `/api/get_brand_by_item?item_ids[]=${this.selectedItem.id}`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.success){
                this.itemBrandsList = response.data;
            }
            // console.log(`Existing items`,this.existingItems);
            // console.log(`Selected items`,this.selectedItems);
            // this.existingItems.forEach((existingItem)=>{
            //     this.selectedItems.forEach((selectedItem)=>{
            //         if(existingItem.id == selectedItem.id){
            //             this.selectedItems.pop();
            //         }
            //     });
            // });
        },

        async getSupplierDetail(){
            let url = `/api/suppliers/${this.supplierId}`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.supplier = response.data;
                this.name = this.supplier.name;
                this.shopName = this.supplier.shop_name;
                this.phoneNumber = this.supplier.phone_number;
                this.maxCredit = this.supplier.credit_limit;
                this.address = this.supplier.address;
                // this.existingItems = this.supplier.items;
                this.selectedAccount = this.apAccountList.find(ap => ap.id === this.supplier.account_id )
                this.selectedCreditAccount = this.creditAccList.find(crd => crd.id === this.supplier.creditor_account_id )
                this.supplier.items.forEach(item =>{
                    this.selectedItemList.push({
                        id:item.id,
                        item_name:item.name,
                        item_id:item.id,
                        brand_id:item.pivot.brand_id,
                        brand_name:item.brands.find(brandId => brandId.id === item.pivot.brand_id).name
                    })
                    // this.selectedItem.push(this.itemList.find(itemid => itemid.id === item.id))
                })
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

        alertValidationMessage(field){
            alert(`You forgot to provide ${field}, please try again`);
        },

        addItemBtnClicked(){
            if(this.selectedItemBrands.length < 1){
                alertValiationMessage(`brands for item`);
                return;
            }
            this.selectedItemBrands.forEach(item =>{
                this.selectedItemList.push({
                    item_name: this.selectedItem.name,
                    item_id: this.selectedItem.id,
                    brand_name: item.name,
                    brand_id: item.id,
                })
            })

            this.selectedItem = null;
            this.selectedItemBrands = [];
            this.itemBrandsList = [];
        },
        async createBtnClicked(){
            // if(this.existingItems.length > 0){
            //     this.existingItems.forEach((item)=>{
            //         this.selectedItemList.push(item);
            //     });
            //     this.existingItems = [];
            //     console.log(this.existingItem)
            // }

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
            if(this.selectedItemList.length < 1 ){
                this.alertValidationMessage(`supplier selling items`);
                return 1;
            }
            if(!this.maxCredit){
                this.alertValidationMessage(`supplier credit limit`);
                return 1;
            }

            let formData = new FormData();
            formData.append("id", this.supplierId);
            formData.append("name", this.name);
            formData.append("shop_name", this.shopName);
            formData.append("phone_number", this.phoneNumber);
            formData.append("credit_limit", this.maxCredit);
            formData.append("address", this.address);
            formData.append("account_id", this.selectedAccount.id);
            formData.append("creditor_account_id", this.selectedCreditAccount.id);
            let itemBrandList = [];
            this.selectedItemList.forEach((item)=>{
                itemBrandList.push({
                    item_id:item.item_id,
                    brand_id:item.brand_id
                })
            });
            formData.append("supplier_items", JSON.stringify(this.selectedItemList));
            let url = `/api/suppliers`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                // window.location.replace("/suppliers");
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

        async createAccountBtnClicked(){
            let url = '/api/create_supplier_account';
            let formData = new FormData();
            formData.append("name", this.accName);
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.selectedAccount = response.data.other_payable;
                this.selectedCreditAccount = response.data.creditor;
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
        this.getCreditAccList();
        this.getItemList();
        this.getSupplierDetail();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>
