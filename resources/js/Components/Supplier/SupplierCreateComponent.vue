<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                New Supplier
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6">
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
            <div class="col-span-3"></div>

            <div class="mb-4 col-span-6 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Address
                </label>
                <textarea name="" v-model="address"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="10"></textarea>
            </div>
            <div class="col-span-6"></div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Credit Limit
                </label>
                <input type="number" v-model="maxCredit"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Items
                </label>
                <div class="bg-white mb-0 w-[90%] text-sm inline-block" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Items" v-model="selectedItems"
                    data-te-select-filter="true" multiple>
                        <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                            {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    AP Account
                </label>
                <multiselect v-model="selectedAccount" :options="apAccountList" :close-on-select="true"
                :clear-on-select="false" :preserve-search="true" placeholder="Select Payable Acount" label="name"
                track-by="id" :preselect-first="true"></multiselect>
            </div>

            <div class=" col-span-12">
                <table class="min-w-[40%] text-sm font-light ">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                Item
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
            selectedItems: [],
            selectedAccount: null,
            name: null,
            shopName: null,
            phoneNumber: null,
            address: null,
            maxCredit: null,
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

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
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

            let formData = new FormData();
            formData.append("name", this.name);
            formData.append("shop_name", this.shopName);
            formData.append("phone_number", this.phoneNumber);
            formData.append("credit_limit", this.maxCredit);
            formData.append("address", this.address);
            formData.append("account_id", this.selectedAccount.id);
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
    },

    created(){
        this.getAPAccounts();
        this.getItemList();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>
