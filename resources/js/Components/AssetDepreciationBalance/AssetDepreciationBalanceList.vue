<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Current Asset Depreciation
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

                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
                        Add New
                    </button>
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
                                <th scope="col" class="">
                                    Sr.No
                                </th>
                                <th scope="col" class=" text-left">
                                    Particular
                                </th>
                                <th scope="col" class="">
                                    Original Cost
                                </th>
                                <th scope="col" class="">
                                    Addition During Year
                                </th>
                                <th scope="col" class="">
                                    Total Cost
                                </th>
                                <th scope="col" class="">
                                    Current Month
                                </th>
                                <th scope="col" class="">
                                    Addition Duration Year
                                </th>
                                <th scope="col" class="">
                                    Total Depreciation
                                </th>
                                <th scope="col" class="">
                                    Book Value
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <div class="contents" v-for="(asset, index) in current_asset?.data" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ ++index }}
                                    </td>
                                    <td class="whitespace-nowrap text-left ">
                                        {{ asset.name }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        {{ asset.original_cost }}

                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ asset.addition_year_cost }}

                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ asset.total_cost }}

                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ asset.current_month_depreciation }}

                                    </td>

                                    <td class="whitespace-nowrap ">
                                        {{ asset.addition_year_depreciation }}

                                    </td>

                                    <td class="whitespace-nowrap ">
                                        {{ asset.total_depreciation }}

                                    </td>

                                    <td class="whitespace-nowrap ">
                                        {{ asset.book_value }}

                                    </td>
                                </tr>
                            </div>
                            <tr class=" !rounded-none">
                                <td></td>
                                <td class="  !font-semibold !rounded-none  text-left" colspan="1">
                                    Total
                                </td>

                                <td class="whitespace-nowrap  !font-semibold">
                                    {{ current_asset?.total_original_cost }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ current_asset?.total_addition_year }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ current_asset?.total }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">

                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ current_asset?.total_addition_during_year }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold">
                                    {{ current_asset?.total_depreciation }}
                                </td>
                                <td class="whitespace-nowrap !font-semibold !rounded-none">
                                    {{ current_asset?.boototal_book_valuek_value }}
                                </td>
                            </tr>
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

                assetDepreciationBalance:[],

                current_asset: null,
                currentDate:'',
                filterDate:null
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getAssetDepreciationBalanceList(date) {
                const response = await getApiData({ url: `/api/get_depreciation_balance?type=current_asset&date=${date}`, token: this.getToken() });
                console.log(response);
                if(response){
                    this.current_asset = response.data.current_asset;
                }
            },

            filterDateAsset(date)
            {
             this.getAssetDepreciationBalanceList(date);
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
            this.getAssetDepreciationBalanceList(this.currentDate);

        }
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
