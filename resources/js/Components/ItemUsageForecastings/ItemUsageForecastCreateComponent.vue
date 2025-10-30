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
                        name="" id="" v-model="selectedItem" @change="itemSelectChanged" class="input-ui">
                        <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex"> {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    UOM
                </label>
                <div class="bg-white mb-0 w-full text-xs rounded-bl-[4px] rounded-br-[4px]  inline-block"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select UOM" data-te-select-filter="true"
                        name="" id="" v-model="selectedUom" class="">
                        <option :value="uom" v-for="(uom, uomIndex) in itemUoms" :key="uomIndex">
                            {{ uom.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Quantity
                </label>
                <input type="number" v-model="quantity" class="input-ui">
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
                                Uom
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
                                    {{ forecastItem.uom_name }}
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
            amount: null,
            quantity: null,
            weight: null,
            forecastItems: [],

            uomList: [],
            itemUoms: [],
            selectedUom: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getItemList() {
            let response = await getApiData({ url: `/api/items`, token: this.getToken() });
            if (response.data) {
                this.itemList = response.data;
            }
        },

        itemSelectChanged() {
            this.itemUoms = [];
            let index = this.uomList.findIndex(uom => uom.id == this.selectedItem.base_uom_id);
            if (index != -1) {
                let baseUom = this.uomList[index];
                this.itemUoms.push(baseUom);
            }
            index = this.uomList.findIndex(uom => uom.id == this.selectedItem.uom_id);
            if (index != -1) {
                let itemUom = this.uomList[index];
                this.itemUoms.push(itemUom);
            }
        },


        dateInputChanged() {
            let date = new Date(this.date);
            date.setDate(1);
            this.date = getFirstDate(date.toISOString().split('T')[0]);
        },

        async addItemBtnClicked() {
            if (!this.quantity) {
                alert('You forgot to specify quantity');
            }
            else if (!this.selectedItem) {
                alert('You forgot to select item');
            } else if (!this.selectedUom) {
                alert('You forgot to select uom');
            }
            else {
                let url = `/api/get_uom_conversion_by_uom?po_uom_id=${this.selectedUom.id}&item_uom_id=${this.selectedItem.uom_id}&item_price=${this.selectedItem.average_price}&base_uom_id=${this.selectedItem.base_uom_id}&item_id=${this.selectedItem.id}`;
                let response = await getApiData({ url: url, token: this.getToken() });
                let uomConversion = null;
                let amount = 0;
                let price = 0;
                if (response.data) {
                    uomConversion = response.data;
                    amount = parseInt(response.data.price);
                    price = this.quantity * amount;
                    // amount = uomConversion.conversion;
                    // amount = amount * (response.data.price);
                    this.$notify({
                        text: `Uom conversion by uom value ${amount}`,
                        type: 'info'
                    });
                }
                else {
                    this.$notify({
                        title: 'Error',
                        text: response.message,
                        type: 'error'
                    });

                    return 1;
                }

                this.forecastItems.push({
                    item_id: this.selectedItem.id,
                    name: this.selectedItem.name,
                    quantity: this.quantity,
                    uom_id: this.selectedUom.id,
                    uom_name: this.selectedUom.name,
                    // uom_conversion_id: uomConversion.id,

                });
            }
            this.quantity = null;
            this.selectedItem = null;
        },

        removeForecastItemBtnClicked(forecastItemIndex) {
            this.forecastItems.splice(forecastItemIndex, 1);
        },

        async getUomList() {
            let response = await getApiData({ url: `/api/uoms`, token: this.getToken() });
            if (response.data) {
                this.uomList = response.data;
                console.log(response.data);
            }
        },

        async createBtnClicked() {
            if (!this.date || this.forecastItems.length < 1) {
                alert('Required data must be filled');
            }
            else {
                let formData = new FormData();
                formData.append('date', this.date);
                formData.append('id', null);
                formData.append('items', JSON.stringify(this.forecastItems));
                console.log(this.forecastItems);
                let response = await postApiData({ url: `/api/item_usage_forecasts`, form_data: formData, token: this.getToken() });

                if (response.success) {
                    window.location.replace(`/item_usage_forecasts`);
                }
            }
        }
    },

    created() {
        this.getItemList();
        this.getUomList();
        // this.dateInputChanged();
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>
