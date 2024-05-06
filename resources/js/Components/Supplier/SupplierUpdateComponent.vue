<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                Edit Supplier
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
                    data-te-select-filter="true" multiple @change="itemSelectChanged">
                        <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                            {{ item.name }}
                        </option>
                    </select>
                </div>
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
                        <tr class="" v-for="(existingItem, existingItemIndex) in existingItems" :key="existingItemIndex">
                            <td class=" px-6 py-2 font-medium ">
                                {{ existingItem.name }}
                            </td>
                            <td class=" px-6 py-2 font-medium ">
                                <button>
                                    <i class="fal fa-trash  pr-3" @click="deleteExistingItemBtnClicked(existingItem.id)" ></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="">
                            <td class=" "></td>
                        </tr>

                        <tr class="" v-for="(selectedItem, selectedItemIndex) in selectedItems" :key="selectedItemIndex">
                            <td class=" px-6 py-2 font-medium ">
                                {{ selectedItem.name }}
                            </td>
                            <td class=" px-6 py-2 font-medium ">
                                <button>
                                    <i class="fal fa-trash  pr-3" @click="deleteSelectedItemBtnClicked(selectedItem.id)" ></i>
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
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    props: ["supplierId"],

    data() {
        return {
            itemList: [],
            selectedItems: [],
            existingItems: [],
            supplier: null,
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

        deleteExistingItemBtnClicked(id){
            let index = this.existingItems.findIndex(item => item.id == id);
            if(index != -1){
                this.existingItems.splice(index, 1);
            }
        },

        itemSelectChanged(){
            console.log(`Existing items`,this.existingItems);
            console.log(`Selected items`,this.selectedItems);
            this.existingItems.forEach((existingItem)=>{
                this.selectedItems.forEach((selectedItem)=>{
                    if(existingItem.id == selectedItem.id){
                        this.selectedItems.pop();
                    }
                });
            });
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
                this.existingItems = this.supplier.items;
            }
        },

        async getItemList(){
            let url = `/api/items`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.itemList = response.data;
            }
        },

        alertValidationMessage(field){
            alert(`You forgot to provide ${field}, please try again`);
        },

        async createBtnClicked(){
            if(this.existingItems.length > 1){
                this.existingItems.forEach((item)=>{
                    this.selectedItems.push(item);
                });
                this.existingItems = [];
            }

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

            let formData = new FormData();
            formData.append("id", this.supplierId);
            formData.append("name", this.name);
            formData.append("shop_name", this.shopName);
            formData.append("phone_number", this.phoneNumber);
            formData.append("credit_limit", this.maxCredit);
            formData.append("address", this.address);
            this.selectedItems.forEach((item)=>{
                formData.append("items[]", item.id);
            });

            let url = `/api/suppliers`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                window.location.replace("/suppliers");
            }
        },
    },

    created(){
        this.getItemList();
        this.getSupplierDetail();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>
