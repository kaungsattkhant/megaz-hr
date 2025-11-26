<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Supplier: {{ supplier.name }}
        </p>
    </div>
    <div class="margin-bg">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>
                <!-- <button class="add-btn h-8 text-[13px] font-inter" @click="searchBtnClicked()">Search</button>
                <button class="add-btn h-8 text-[13px] font-inter" @click="clearSearchBtnClicked()">Clear</button> -->
            </div>
            <div class="flex justify-end flex-col gap-y-4">
                <!-- <div>
                    <a href="/suppliers/create" class="add-btn ">
                        Add New
                    </a>
                </div> -->

                <div>
                    <p> Total Average Lead Time: {{ totalAvgLeadTime }} </p>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class=" ">
                                    #
                                </th>
                                <th scope="col" class=" ">
                                    Item
                                </th>
                                <th scope="col" class=" ">
                                    Average Lead Time
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />

                        <tr class=" !text-center" v-else-if="leadTimes.length < 1">
                            <td class="" colspan="5">
                                No Data Here
                            </td>
                        </tr>
                        <tbody v-else>
                            <tr v-if="leadTimes.length < 1 && errorMessage">
                                <td colspan="3">
                                    {{ errorMessage }}
                                </td>
                            </tr>
                            <div class="contents" v-for="(leadTime, index) in leadTimes" :key="index">
                                <tr class="">
                                    <td class="  font-medium ">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace>nowrap  ">
                                        {{ leadTime.item_name }}
                                    </td>
                                    <td class="whitespace>nowrap  ">
                                        {{ leadTime.average_order_time }}
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
import { Modal, Ripple, initTE, Input } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, deleteApiData } from '../../utilities/ajax-helpers';
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    props: ['supplier'],
    data() {
        return {
            leadTimes: [],

            searchInput:null,

            errorMessage:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            totalAvgLeadTime: null,
            averageOrderTime: null,

            loading: true,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async searchBtnClicked() {
            this.getSupplierList(1);
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.getSupplierList(1);
        },

        async getLeadTimes(){
            this.loading = true;
            let response = await getApiData({ url: `/api/supplier_lead_time/${this.supplier.id}`, token: this.getToken() });
            if (response.success) {
                this.loading = false;
                console.log('loading top');
                this.totalAvgLeadTime = response.data.total_average_lead_time;
                this.leadTimes = response.data.details;
            }
            else{
                this.errorMessage = response.message
            }
        },
    },

    created() {
        this.getLeadTimes();
    },

    mounted() {
        initTE({Modal, Ripple, Input});
    }
}
</script>
