<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Sale Ledger Restaurant
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex gap-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search">

                        <i class="fal fa-search"></i>
                    </label>

                    <div>
                        <input type="date" @change="filterDateAsset(filterDate)"  v-model="filterDate" class="input h-8 border border-slate-300 rounded-md">
                    </div>

                </div>


                <div class="flex justify-end flex-col">

                    <!-- <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
                        Add New
                    </button> -->
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="text-center">
                                    Date
                                </th>
                                <th scope="col" class=" text-right">
                                    Food
                                </th>
                                <th scope="col" class=" text-right">
                                    Beverage
                                </th>
                                <th scope="col" class=" text-right">
                                    Other
                                </th>
                                <th scope="col" class=" text-right">
                                    Service
                                </th>
                                <th scope="col" class=" text-right">
                                    Amount
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(item, index) in primaryList" :key="index">
                                <tr class="">
                                    <td class=" text-center ">
                                        {{ item.report_date }}
                                    </td>
                                    <td class="whitespace-nowrap text-right ">
                                        {{ item.Food_Total }}
                                    </td>

                                    <td class="whitespace-nowrap text-right ">
                                        {{ item.Beverage_Total }}

                                    </td>
                                    <td class="whitespace-nowrap text-right">
                                        {{ item.Other_Charges }}

                                    </td>
                                    <td class="whitespace-nowrap text-right">
                                        {{ item.Service_Charge }}

                                    </td>

                                    <td class="whitespace-nowrap text-right">
                                        {{ item.All_Total }}

                                    </td>
                                    <td></td>
                                </tr>
                            </div>
                            <!-- <tr>
                                <td>
                                    <p>
                                        Total: {{
                                          primaryList.reduce((sum, item) => sum + Number(item.All_Total), 0)
                                        }}
                                      </p>
                                </td>
                            </tr> -->
                            <!-- 
                            <tr class=" !rounded-none">
                                <td></td>
                                <td class="  !font-semibold !rounded-none text-left" colspan="1">
                                    Total
                                </td>

                                <td class="whitespace-nowrap  !font-semibold">
                                    {{ fix_asset_untangible.total_original_cost }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ fix_asset_untangible.total_addition_year }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ fix_asset_untangible.total }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">

                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ fix_asset_untangible.total_addition_during_year }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ fix_asset_untangible.total_depreciation }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold !rounded-none">
                                    {{ fix_asset_untangible.boototal_book_valuek_value }}
                                </td>
                            </tr> -->

                            <!-- looping end -->


                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect
        },
        data() {
            return {

                primaryList:[],

                currentDate:'',
                filterDate:null
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getPrimaryList(date) {
                const response = await getApiData({ url: `/api/get_sale_ledgers?date=${date}&type=rt`, token: this.getToken() });
                console.log(response);
                if(response){
                    this.primaryList = response.data;
                }
            },

            filterDateAsset(date)
            {
             this.getPrimaryList(date);
            },

            getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
            }


        },

        mounted()
        {
            this.currentDate = this.getCurrentDate();
            this.filterDate = this.getCurrentDate();
            this.getPrimaryList(this.currentDate);

        }
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
