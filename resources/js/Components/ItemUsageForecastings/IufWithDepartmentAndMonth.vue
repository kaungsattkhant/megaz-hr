<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Item Usage Forecast with {{ getMonthName(path_month) }} and Department of {{ departmentName }}
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
                                    Date
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
                                        {{ ++itemForecastIndex }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ itemForecast.date }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        {{ itemForecast.department.name }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        <a :href="'/item_usage_forecasts/'+itemForecast.id+'/detail'" id=""
                                            class="pr-4">
                                            <i class="far fa-bars"></i>
                                        </a>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
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
            // month: this.getMonthFromUrl(),
            path_month:null,
            path_department:null,
            departmentName:null

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

        async getItemForecastsList() {

            let response = await getApiData({ url: `/api/item_usage_forecast_items_with_month/${this.path_month}/department/${this.path_department}`, token: this.getToken() });
            this.itemForecastList = response.data;
            this.departmentName = response.data[0].department.name;

        },


        extractParams() {
            const path = window.location.pathname;
            const pathParts = path.split('/');

            this.path_month = pathParts[pathParts.indexOf('item_usage_forecasts_with_month') + 1];
            this.path_department = pathParts[pathParts.indexOf('department') + 1];

        },

        iufWithMonthAndDepartment(department_id) {
            window.location.href = `/item_usage_forecasts_with_month/${this.month}/department/${department_id}`;
        }
    },

    created() {

        this.extractParams();
        this.getItemForecastsList();
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>
