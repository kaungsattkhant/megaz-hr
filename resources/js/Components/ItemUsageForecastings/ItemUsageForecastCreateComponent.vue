<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                Create Item Usage Forecast
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Month
                </label>
                <input type="date" v-model="date" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                @change="dateInputChanged">
            </div>

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                &nbsp;
            </div>

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                &nbsp;
            </div>

            <div class="col-span-3">
                &nbsp;
            </div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Item
                </label>
                <div class="text-sm input-ui w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Item"
                        data-te-select-filter="true" name="" id="" v-model="selectedItem"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                        >
                        <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex"> {{ item.name }} </option>
                    </select>
                </div>
            </div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Amount
                </label>
                <input type="number" v-model="amount" class="mt-2 text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Quantity
                </label>
                <input type="number" v-model="quantity" class="mt-2 text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    &nbsp;
                </label>
                <button class="mt-2 h-8 py-1 add-btn" @click="addItemBtnClicked">
                    Add Item
                </button>
            </div>

        </div>

        <div class=" bg-white p-8 rounded-md shadow-md mb-8">
            <div>
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Quantity
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Amount
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(forecastItem, forecastItemIndex) in forecastItems" :key="ingredientIndex">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ forecastItem.name }}
                                </td>
                                <td class=" px-6 py-4 font-medium ">
                                    {{ forecastItem.quantity }}
                                </td>
                                <td class=" px-6 py-4 font-medium ">
                                    {{ forecastItem.amount }}
                                </td>
                                <td class=" px-6 py-4 font-medium ">
                                    <button @click="removeForecastItemBtnClicked(forecastItemIndex)">
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <button class="add-btn" @click="createBtnClicked">
                Create Forecast
            </button>
        </div>
    </div>
</template>

<script>
    import { Modal, Ripple, initTE, Input, Tab, Select } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { getCurrentDate } from "../../utilities/datetime-helpers";
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                menuCategoryList: [],
                itemCategoryList: [],
                itemList: [],

                date: getCurrentDate(),
                menuCategoryId: null,
                name: null,
                price: null,

                selectedItemCategory: null,
                selectedItem: null,

                amount: null,
                quantity: null,
                weight: null,
                isMakePack: false,
                ingredientItems: [],
                forecastItems: [],

            };
        },

        methods: {
            ...mapGetters(['getToken']),
            async getItemList(){
                let response = await getApiData({url: `/api/items`, token: this.getToken()});
                if(response.data){
                    this.itemList = response.data;
                }
            },

            dateInputChanged(){
                let date = new Date(this.date);
                // Set the date to 1 to get the first date of the month
                date.setDate(1);
                // Format the result as "YYYY-mm-dd"
                let firstDateOfMonth = date.toISOString().split('T')[0];
                this.date = firstDateOfMonth;
            },

            addItemBtnClicked(){
                if(!this.amount){
                    alert('You forgot to specify amount');
                }
                else if(!this.quantity){
                    alert('You forgot to specify quantity');
                }
                else if(!this.selectedItem){
                    alert('You forgot to select item');
                }
                else{
                    this.forecastItems.push({
                        item_id: this.selectedItem.id,
                        name: this.selectedItem.name,
                        amount: this.amount,
                        quantity: this.quantity
                    });
                }
                this.amount = null;
                this.quantity = null;
                this.selectedItem = null;
            },

            removeForecastItemBtnClicked(forecastItemIndex){
                this.forecastItems.splice(forecastItemIndex, 1);
            },

            async createBtnClicked(){
                if(!this.date || this.forecastItems.length<1){
                    alert('Required data must be filled');
                }
                else{
                    let formData = new FormData();
                    formData.append('date', this.date);
                    formData.append('id', null);
                    formData.append('items', JSON.stringify(this.forecastItems));

                    let response = await postApiData({url: `/api/item_usage_forecasts`, form_data: formData, token: this.getToken()});

                    if(response.success){
                        window.location.replace(`/item_usage_forecasts`);
                    }
                }
            }
        },

        created(){
            this.getItemList();
            this.dateInputChanged();
        },

        mounted(){
            initTE({ Modal, Select, Tab, Ripple });
        }
    }
</script>
