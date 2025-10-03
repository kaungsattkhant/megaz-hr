<template>
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Lead Time
                </p>
            </div>
            <div class="btn-container !border-0 !mb-1">
                <notifications position="top center" />
                <div class="flex pr-0 gap-x-4">
                    <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Supplier" @change="getLeadTimeList"
                            data-te-select-filter="true" name="" id="" v-model="selectedSupplier" class="input-ui">
                            <option :value="supplier" v-for="(supplier, supplierIndex) in supplierList"
                                :key="supplierIndex"> {{ supplier.name }} </option>
                        </select>
                    </div>
                    <!-- <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Item"
                            data-te-select-filter="true" name="" id="" v-model="selectedItem" class="input-ui">
                            <option :value="item.value" v-for="(item, itemIndex) in itemList"
                                :key="itemIndex"> {{ item.name }} </option>
                        </select>
                    </div> -->
                    
                </div>
            </div>
        </div>
        <div class="flex justify-between mb-4 px-3 pt-3 font-semibold">
            <p v-if="selectedSupplier">
                Supplier : {{ selectedSupplier.name }}
            </p>
            <p v-if="totalAvgLeadTime">
                Average Lead Time : {{ totalAvgLeadTime }}
            </p>
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
                                    Item
                                </th>
                                <th scope="col" class="">
                                    Average Lead Time
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <tr v-if="leadTimeList.length < 1 && errorMessage">
                                <td colspan="3">
                                    {{ errorMessage }}
                                </td>
                            </tr>
                            <!-- looping start -->
                            <div class="contents" v-for="(leadTime, index) in leadTimeList.details" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leadTime.item_name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leadTime.average_order_time }}
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="leadTimeList.length < 1 && !loading">
                                <td class="" colspan="3">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getOkrList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOkrList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
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

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            leadTimeList: [],
            supplierList: [],
            itemList: [],

            errorMessage:null,

            selectedSupplier: null,
            totalAvgLeadTime:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/supplier_lead_time/',
            url_supplier:'',
            loading: false,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getLeadTimeList(pageNumber) {
            // this.loading = true;
            // let url = this.url + pageNumber + this.url_search + this.url_department + this.url_role;
            let url = this.url + this.selectedSupplier.id;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.leadTimeList = response.data;
                this.totalAvgLeadTime = response.data.total_average_lead_time;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
            }
            else{
                this.errorMessage = response.message
            }
        },
        async getSupplierList(){
            let url = '/api/suppliers';
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.supplierList = response.data;
                this.selectedSupplier = response.data[0]
                this.getLeadTimeList();
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
        this.getSupplierList();

    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>