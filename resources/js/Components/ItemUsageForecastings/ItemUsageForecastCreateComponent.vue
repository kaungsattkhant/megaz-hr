<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Create Item Usage Forecast
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Month
                </label>
                <input type="date" v-model="date"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
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
                <label for="" class="label-form mb-3">
                    Item
                </label>
                <div class="text-sm w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Item" data-te-select-filter="true"
                        name="" id="" v-model="selectedItem"
                        class="input-ui">
                        <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex"> {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Amount
                </label>
                <input type="number" v-model="amount"
                    class="input-ui">
            </div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Quantity
                </label>
                <input type="number" v-model="quantity"
                    class="input-ui">
            </div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Departments
                </label>
                <div class="text-sm w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Item" data-te-select-filter="true"
                        name="" id="" v-model="selectedDepartment"
                        class="input-ui">
                        <option :value="department" v-for="(department, departmentIndex) in departmentList" :key="departmentIndex"> {{ department.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn" @click="addItemBtnClicked">
                    Add Item
                </button>
            </div>

        </div>

        <div class=" bg-white py-4 px-4 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class=" text-left ">
                                Name
                            </th>
                            <th scope="col" class="  ">
                                Quantity
                            </th>
                            <th scope="col" class="  ">
                                Amount
                            </th>
                            <th scope="col" class="  ">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(forecastItem, forecastItemIndex) in forecastItems"
                            :key="forecastItemIndex">
                            <tr class="">
                                <td class="text-left">
                                    {{ forecastItem.name }}
                                </td>
                                <td class="  ">
                                    {{ forecastItem.quantity }}
                                </td>
                                <td class="  ">
                                    {{ forecastItem.amount }}
                                </td>
                                <td class="  ">
                                    <button @click="removeForecastItemBtnClicked(forecastItemIndex)">
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                                </td>
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
    import { getCurrentDate, getFirstDate } from "../../utilities/datetime-helpers";
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                itemList: [],
                date: getFirstDate(getCurrentDate()),
                selectedItem: null,

                departmentList:[],

                amount: null,
                quantity: null,
                weight: null,
                forecastItems: [],
                selectedDepartment:null,
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

            async getDepartmentList()
            {
                let response = await getApiData({url: '/api/departments', token: this.getToken()});
                if(response.data){
                    this.departmentList = response.data;
                }
            },

            dateInputChanged(){
                let date = new Date(this.date);
                date.setDate(1);
                this.date = getFirstDate(date.toISOString().split('T')[0]);
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
                    formData.append('department_id',this.selectedDepartment.id);
                    // console.log(this.selectedDepartment.id);
                    let response = await postApiData({url: `/api/item_usage_forecasts`, form_data: formData, token: this.getToken()});

                    if(response.success){
                        window.location.replace(`/item_usage_forecasts`);
                    }
                }
            }
        },

        created(){
            this.getItemList();
            this.getDepartmentList();
            // this.dateInputChanged();
        },

        mounted(){
            initTE({ Modal, Select, Tab, Ripple });
        }
    }
</script>
