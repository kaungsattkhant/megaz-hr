<template>
    <div class="px-0">
        <div class="mb-3">
            <p class="text-lg font-semibold font-inter">
                Supplier Detail
            </p>
        </div>

        <div class="bg-white grid grid-cols-2 gap-x-4 p-8">
            <div class="grid grid-cols-5 gap-x-4 gap-y-4 bg-white rounded-md text-sm h-fit">
                <div class=" col-span-2">
                    <p class="font-semibold">
                        Supplier Name
                    </p>
                </div>
                <div class=" col-span-3">
                    <p class="text-gray-700">
                        : {{ name }}
                    </p>
                </div>
    
                <div class=" col-span-2">
                    <p class="font-semibold">
                        Supplier Shop Name
                    </p>
                </div>
                <div class=" col-span-3">
                    <p class="text-gray-700">
                        : {{ shopName }}
                    </p>
                </div>
    
                <div class=" col-span-2">
                    <p class="font-semibold">
                        Credit Limit
                    </p>
                </div>
                <div class=" col-span-3">
                    <p class="text-gray-700">
                        : {{ maxCredit }}
                    </p>
                </div>
    
                <div class=" col-span-2">
                    <p class="font-semibold">
                        Lead Time
                    </p>
                </div>
                <div class=" col-span-3">
                    <p class="text-gray-700">
                        : {{ lead_time }}
                    </p>
                </div>

                
    
    
            </div>
            <div class="grid grid-cols-3 gap-x-4 gap-y-4 bg-white rounded-md text-sm h-fit">
                <div>
                    <p class="font-semibold">
                        AP Account
                    </p>
                </div>
                <div class=" col-span-2">
                    <p class="text-gray-700">
                        : {{ selectedAccount ? selectedAccount.name : '' }}
                    </p>
                </div>
                <div>
                    <p class="font-semibold">
                        Creditor Account
                    </p>
                </div>
                <div class=" col-span-2">
                    <p class="text-gray-700">
                        : {{ selectedAccount ? selectedCreditAccount.name : '' }}
                    </p>
                </div>
                <div>
                    <p class="font-semibold">
                        Credit Terms
                    </p>
                </div>
                <div class=" col-span-2">
                    <p class="text-gray-700">
                        : {{ credit_terms }}
                    </p>
                </div>
                <div>
                    <p class="font-semibold">
                        Address
                    </p>
                </div>
                <div class=" col-span-2">
                    <p class="text-gray-700">
                        : {{ address }}
                    </p>
                </div>
            </div>
            <!-- <div class="col-span-2 pt-6">
                <table class="min-w-[50%] text-sm font-light ml-0">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" pr-6 py-4 ">
                                Item
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Brand
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(selectedItem,selectedItemIndex) in selectedItemList" >
                            <td class=" pr-6 py-3 font-medium ">
                                {{ selectedItem.item_name }}
                            </td>
                            <td class=" px-6 py-3 font-medium ">
                                {{ selectedItem.brand_name }}
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="col-span-2 pt-6">
                <table class="min-w-[50%] text-sm font-light ml-2">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" pr-6 py-4 ">
                                Item
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Brand
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(selectedItem,selectedItemIndex) in selectedItemList" >
                            <td class=" pr-6 py-3 font-medium ">
                                {{ selectedItem.item_name }}
                            </td>
                            <td class=" px-6 py-3 font-medium ">
                                {{ selectedItem.brand_name }}
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="col-span-2 pt-6">
                <table class="min-w-[50%] text-sm font-light ml-2">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" pr-6 py-4 ">
                                Item
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Brand
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(selectedItem,selectedItemIndex) in selectedItemList" >
                            <td class=" pr-6 py-3 font-medium ">
                                {{ selectedItem.item_name }}
                            </td>
                            <td class=" px-6 py-3 font-medium ">
                                {{ selectedItem.brand_name }}
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
            </div> -->
            <div class="col-span-2 pt-12 mb-0 table-container" v-if="selectedItemList.length > 0">
                <table class="primary-table !text-left !min-w-[50%] !w-1/2">
                    <thead class=" ">
                        <tr>
                            <th scope="col" class="  ">
                                Item
                            </th>
                            <th scope="col" class="  ">
                                Brand
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(selectedItem,selectedItemIndex) in selectedItemList" >
                            <td class="  ">
                                {{ selectedItem.item_name }}
                            </td>
                            <td class="  ">
                                {{ selectedItem.brand_name }}
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-2 pt-8 mb-0 table-container" v-if="phoneNumberList.length > 0">
                <table class="primary-table !text-left !min-w-[50%] !w-1/2">
                    <thead class=" ">
                        <tr>
                            <th scope="col" class="">
                                Phone Number
                            </th>
                            <th scope="col" class="">
                                Type
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(phone,phoneIndex) in phoneNumberList" >
                            <td class="">
                                {{ phone.phone_number }}
                            </td>
                            <td class="">
                                {{ phone.type }}
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-2 pt-8 mb-0 table-container" v-if="bankAccountList.length > 0">
                <table class="primary-table !text-left !min-w-[50%] !w-1/2">
                    <thead class=" ">
                        <tr>
                            <th scope="col" class="">
                                Account Name
                            </th>
                            <th scope="col" class="">
                                Account Number
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(acc,accIndex) in bankAccountList" >
                            <td class="">
                                {{ acc.account_name }}
                            </td>
                            <td class="">
                                {{ acc.account_number }}
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
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
            phoneNumberList:[],
            bankAccountList:[],

            accName:null,
            selectedAccount: null,
            selectedCreditAccount:null,
            supplier: null,
            name: null,
            shopName: null,
            address: null,
            maxCredit: null,
            lead_time:null,
            credit_terms:null,

            ph_number: null,
            selectedType:null,

            account_name:null,
            account_number:null,

            payableSubAccountList: [],
            selectedPayableSubAccount: null,
            apAccountName: null,

            creditSubAccList:[],
            creditAccountName:null,
            selectedCreditSubAcc:null,

            itemBrandsList: [],
            selectedItem:null,
            selectedItemBrands: [],

            typeList:[
                {value:'phone',name:'Phone'},
                {value:'kpay',name:'Kpay'}
            ],
            test:null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
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
                this.lead_time = this.supplier.lead_time;
                this.credit_terms = this.supplier.credit_terms;
                // this.existingItems = this.supplier.items;
                this.selectedAccount = this.apAccountList.find(ap => ap.id === this.supplier.account_id )
                this.selectedCreditAccount = this.creditAccList.find(crd => crd.id === this.supplier.creditor_account_id )
                this.supplier.supplier_items.forEach(item =>{
                    this.selectedItemList.push({
                        id:item.id,
                        is_active:item.is_active,
                        item_name:item.item.name,
                        item_id:item.item.id,
                        brand_id:item.brand.id,
                        brand_name:item.brand.name,
                        // brand_name:item.brands.find(brandId => brandId.id === item.pivot.brand_id).name
                    })
                    // this.selectedItem.push(this.itemList.find(itemid => itemid.id === item.id))
                })
                
                this.phoneNumberList = this.supplier.supplier_phone;
                this.bankAccountList = this.supplier.supplier_bank_account;
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
        async getPayableSubAccountList(){
            let url = `/api/sub_account_by_head_account/4`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.payableSubAccountList = response.data;
                this.creditSubAccList = response.data;
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
