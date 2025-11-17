<template>
    <div class="margin-bg">
        <!-- Date Pickers -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex flex-col w-40">
                    <label for="startDate" class="text-sm font-medium text-gray-600 mb-1">Start Date</label>
                    <input type="date" id="startDate"
                    v-model="startDate"
                    class="border border-gray-300 rounded-md p-2 focus:ring focus:ring-indigo-200 focus:border-indigo-400" />
                </div>
                <div class="flex flex-col w-40">
                    <label for="endDate" class="text-sm font-medium text-gray-600 mb-1">End Date</label>
                    <input type="date" id="endDate"
                    v-model="endDate"
                    class="border border-gray-300 rounded-md p-2 focus:ring focus:ring-indigo-200 focus:border-indigo-400" />
                </div>
                <!-- Select Box Example -->
                <div class="flex flex-col w-40">
                    <label for="reportType" class="text-sm font-medium text-gray-600 mb-1">Area</label>
                    <multiselect v-model="selectedArea" :options="areas"
                    :clear-on-select="false"
                    :preserve-search="true" placeholder="Area" label="name" track-by="id"
                    :preselect-first="false"></multiselect>
                </div>

                <div class="p-2 mt-5">
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
                        <th class="py-3 px-4 text-left">Date</th>
                        <th class="py-3 px-4 text-left">Name</th>
                        <th class="py-3 px-4 text-left">Total Pax</th>
                        <th class="py-3 px-4 text-left">Total Amount</th>
                        <th class="py-3 px-4 text-left">Per Pax</th>
                    </tr>
                </thead>
                <TableSkeleton
                v-if="tableLoading"/>
                <tbody id="reportTable" class="divide-y divide-gray-200">
                    <!-- Rows dynamically generated -->
                     <tr v-for="(row, index) in reportRows" :key="index">
                        <td> {{ index + 1 }} </td>
                        <td> {{ startDate }} to {{ endDate }} </td>
                        <td> {{ row.staff_name }} </td>
                        <td> {{ row.total_pax }} </td>
                        <td> {{ row.total_amount.toLocaleString() }} </td>
                        <td> {{ row.total_per_pax.toLocaleString() }} </td>
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

                startDate: null,
                endDate: null,
                areas: [],
                selectedArea: null,

                reportRows: [],
            }
        },
        methods: {
            ...mapGetters(['getToken', 'getFeature']),

            getReport(){
                if(!this.startDate){
                    this.showToastMessage("Start date must be provided");
                    return;
                }
                if(!this.endDate){
                    this.showToastMessage("End date must be provided");
                    return;
                }

                this.buttonLoading = true;
                this.tableLoading = true;
                let url = `/api/report/catering/daily_area_sales_by_staff?start_date=${this.startDate}&end_date=${this.endDate}`;
                if(this.selectedArea) {
                    url += `&area_id=${this.selectedArea.id}`;
                }
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
            this.getAreas();
        },
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
