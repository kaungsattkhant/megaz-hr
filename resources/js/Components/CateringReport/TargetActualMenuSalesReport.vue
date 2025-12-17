<template>
    <div class="margin-bg">
        <!-- Date Pickers -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Start Month -->
                <div class="flex flex-col w-40">
                    <label for="startMonth" class="text-sm font-medium text-gray-600 mb-1">
                    Month
                    </label>
                    <input
                    type="month"
                    id="startMonth"
                    v-model="month"
                    class="border border-gray-300 rounded-md p-2 w-full focus:ring focus:ring-indigo-200 focus:border-indigo-400"
                    />
                </div>

                <!-- Filter Button -->
                <div class="flex items-end">
                    <loading-button
                    text="Get Report"
                    loadingText="Loading..."
                    :loading="buttonLoading"
                    @click="getReport"
                    />
                </div>
            </div>

        </div>

        <!-- Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="primary-table">
                <thead class="">
                    <tr>
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">Menu</th>
                        <th class="py-3 px-4 text-left">Area</th>
                        <th class="py-3 px-4 text-left">Target Qty</th>
                        <th class="py-3 px-4 text-left">Target Sales</th>
                        <th class="py-3 px-4 text-left">Actual Qty</th>
                        <th class="py-3 px-4 text-left">Actual Sales</th>
                        <th class="py-3 px-4 text-left">Acheived Percentage</th>
                    </tr>
                </thead>
                <TableSkeleton
                v-if="tableLoading"/>
                <tbody id="reportTable" class="divide-y divide-gray-200">
                    <!-- Rows dynamically generated -->
                     <tr v-for="(row, index) in reportRows" :key="index">
                        <td> {{ index + 1 }} </td>
                        <td> {{ row.menu_name }} </td>
                        <td> {{ row.area_name }} </td>
                        <td> {{ row.target_qty }} </td>
                        <td> {{ row.target_amount.toLocaleString() }} </td>
                        <td> {{ row.actual_qty }} </td>
                        <td> {{ row.actual_amount.toLocaleString() }} </td>
                        <td> {{ row.percentage_hit }} %</td>
                     </tr>
                     <tr class=" !text-center" v-if="reportRows.length < 1 && !tableLoading">
                        <td class="" colspan="4">
                            No Data Here
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
</template>

<script>
    import LoadingButton from '../Common/LoadingButton.vue';
    import { getApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import TableSkeleton from "../Common/TableSkeleton.vue";
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            TableSkeleton,
            LoadingButton,
            Multiselect
        },

        data() {
            return {
                buttonLoading: false,
                tableLoading: false,

                month: null,
                areas: [],
                selectedArea: null,

                reportRows: [],
            }
        },
        methods: {
            ...mapGetters(['getToken', 'getFeature']),

            getReport(){
                if(!this.month){
                    this.showToastMessage("Month must be provided");
                    return;
                }

                console.log(this.month);
                const [year, month] = this.month.split("-");
                // return;

                this.buttonLoading = true;
                this.tableLoading = true;
                let url = `/api/report/catering/target_actual_menu_sales?year=${year}&month=${month}`;
                getApiData({
                    url: url,
                    token: this.getToken()
                }).then((response)=>{
                    this.buttonLoading = false;
                    this.tableLoading = false;
                    if(response.success){
                        this.reportRows = response.data;
                    }
                });
            },

            getAreas(){
                getApiData({
                    url: `/api/areas?area_category_id=2`,
                    token: this.getToken()
                }).then((response)=>{
                    if(response.success){
                        this.areas = response.data;
                        const filtered = this.areas.filter(r =>
                            r.name.toLowerCase().includes("Sky".toLowerCase())
                        );
                        if(filtered){
                            this.selectedArea = filtered[0];
                        }
                    }
                });
            },

            showToastMessage(message, type="warn", title="Warning") {
                this.$notify({
                    title: `${title}`,
                    text: `${message}`,
                    type: `${type}`
                });
            },
        },

        mounted() {
            // this.getAreas();
        },
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
