<template>

    <div class="margin-bg">
        <div class="card-shadow">
            <div class="pt-2">
                <p class=" page-title">
                    Cash Flow
                </p>
            </div>
            <div class="btn-container pt-6">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input w-48">
                        <input type="month" class="input-search" placeholder="Search" v-model="month">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="getReport()">Search</button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Account
                                </th>
                                <th scope="col" class="">
                                    Priority
                                </th>
                                <th v-for="date in dateColumns" :key="date">
                                    {{ getFriendlyDate(date) }}
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(item, index) in reportData" :key="index">
                                <tr class="">
                                    <td> {{ index + 1 }} </td>
                                    <td> {{ item.account }} </td>
                                    <td> {{ item.priority }} </td>
                                    <td v-for="date in dateColumns" :key="date">
                                        {{ item.dates[date].toLocaleString() }}
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="reportData.length < 1 && !loading">
                                <td class="" colspan="7">
                                    No Data Here
                                </td>
                            </tr>
                            <!-- looping start -->
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <!-- <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getReport(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getReport(currentPage + 1)">
                                »</button>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import TableSkeleton from "../Common/TableSkeleton.vue";
import Multiselect from "vue-multiselect";
import LoadingButton from "../Common/LoadingButton.vue";
import { getCurrentDate, convertToFriendlyDate } from "../../utilities/datetime-helpers";

export default {
    components: {
        TableSkeleton,
        Multiselect,
        LoadingButton
    },
    data() {
        return {
            reportData: [],
            dateColumns: [],

            month: this.getCurrentMonth(),

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            perPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/budget_accounts',
            url_search:'',

            deleteId:null,

            loading: true,

            createBtnLoading: false,
            updateBtnLoading: false,

            budgetPriorities: [],
            selectedBudgetPriority: null,
            amount: 0,
            mainAccounts: [],
            selectedMainAccount: null,
            subAccounts: [],
            selectedSubAccount: null,
            date: null,

            editId: null,
            editIndex: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        getCurrentMonth(){
            let yearMonthDay = getCurrentDate().split("-");
            return `${yearMonthDay[0]}-${yearMonthDay[1]}`;
        },

        getFriendlyDate(date){
            return convertToFriendlyDate(date).split(",")[0];
        },

        async getReport() {
            this.reportData = [];
            this.dateColumns = [];
            this.loading = true;
            let url = `/api/report/cashflows?month=${this.month}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.reportData = response.data;
                if (this.reportData.length > 0) {
                    this.dateColumns = Object.keys(this.reportData[0].dates)
                }
            }
        },

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });
        },
    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getReport();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
