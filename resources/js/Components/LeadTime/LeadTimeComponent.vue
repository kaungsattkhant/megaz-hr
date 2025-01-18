<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Lead Time
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container !border-0 !mb-1">
            <notifications position="top center" />
            <div class="flex pr-0 gap-x-4">
                <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Supplier"
                        data-te-select-filter="true" name="" id="" v-model="selectedSupplier" class="input-ui">
                        <option :value="supplier.value" v-for="(supplier, supplierIndex) in supplierList"
                            :key="supplierIndex"> {{ supplier.name }} </option>
                    </select>
                </div>
                <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Item"
                        data-te-select-filter="true" name="" id="" v-model="selectedItem" class="input-ui">
                        <option :value="item.value" v-for="(item, itemIndex) in itemList"
                            :key="itemIndex"> {{ item.name }} </option>
                    </select>
                </div>
                
            </div>
        </div>
        <div class="flex justify-between mb-4 px-6 font-semibold">
            <p>
                Supplier : {{ selectedSupplier }}
            </p>
            <p>
                Average Lead Time : 1 Day 3 Hour
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
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(leadTime, index) in leadTimeList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leadTime.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ leadTime.latitude }}
                                    </td>
                                </tr>
                            </div>
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

export default {
    data() {
        return {
            leadTimeList: [],
            supplierList: [],
            itemList: [],

            selectedSupplier: null,
            selectedItem: null,
            
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput:null,

            url:'/api/gps',
            url_search:'',
            url_department:'',
            url_role:'',
            deleteId:null,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getLeadTimeList(pageNumber) {
            // let url = this.url + pageNumber + this.url_search + this.url_department + this.url_role;
            let url = this.url;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.leadTimeList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
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
        this.getLeadTimeList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>