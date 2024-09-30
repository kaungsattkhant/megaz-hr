<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Item Usage Forecast with {{ getMonthName(month) }}
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">
                <a href="/create_item_usage_forecasts" class="add-btn ">
                    Add New
                </a>

            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class=" primary-table ">
                        <thead class=" ">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>

                                <th scope="col" class="  ">
                                    Month
                                </th>

                                <th scope="col" class="  ">
                                    Department
                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            <div class="contents" v-for="(itemForecast, itemForecastIndex) in itemForecastList"
                                :key="itemForecastIndex">
                                <tr class="">
                                    <td class="  font-medium ">
                                        {{ perPage * (currentPage - 1) + (++itemForecastIndex) }}

                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ getMonthName(month) }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        <span class="cursor-pointer"
                                            @click="iufWithMonthAndDepartment(itemForecast.department_id)">{{
                                                itemForecast.department_name }}</span>
                                    </td>

                                </tr>
                            </div>
                        </tbody>
                    </table>


                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getItemForecastsList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                        lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getItemForecastsList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { Modal, Ripple, initTE, Input, Tab, Select } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { convertToMonth } from "../../utilities/datetime-helpers";
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            itemForecastList: [],
            month: this.getMonthFromUrl(),
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        getMonthName(monthNumber) {
            const monthIndex = parseInt(monthNumber, 10) - 1;

            const monthNames = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];

            return monthNames[monthIndex] || 'Invalid month';
        },

        async getItemForecastsList(pageNumber) {
            let response = await getApiData({ url: `/api/item_usage_forecast_items_with_month/${this.month}?page=${pageNumber}`, token: this.getToken() });
            console.log(response.data.data);
            this.itemForecastList = response.data.data;
            this.lastPage = response.data.last_page;
            this.currentPage = pageNumber;
            this.perPage = response.data.per_page;
            this.totalData = response.data.total;
        },

        getMonthFromUrl() {
            const url = window.location.href;
            const urlObject = new URL(url);
            const pathParts = urlObject.pathname.split('/');
            return pathParts[pathParts.length - 1];
        },

        iufWithMonthAndDepartment(department_id) {
            window.location.href = `/item_usage_forecasts_with_month/${this.month}/department/${department_id}`;
        }
    },

    created() {
        this.getItemForecastsList(1);
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>
