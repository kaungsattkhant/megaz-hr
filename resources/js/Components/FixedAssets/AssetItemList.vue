<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Assets Item
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex gap-x-4">

            </div>
            <div class="flex justify-end flex-col">
                <a href="/asset_items/create" class="add-btn text-[13px] font-inter">
                    Add New
                </a>

            </div>
        </div>
        <div class="box-container-table">
            <div class=" overflow-x-auto">
                <div class=" table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Cost
                                </th>

                                <th scope="col" class="">

                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(asset_item, index) in assetItemList" :key="index">
                                <tr class="">
                                    <td class="">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ asset_item.name }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ asset_item.item_code }}
                                    </td>

                                </tr>
                            </div>


                            <!-- looping end -->
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getAssetItemList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getAssetItemList(currentPage + 1)"> »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';

export default {
    data() {
        return {
            assetItemList: [],
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getAssetItemList(pageNumber) {


            let url = `/api/asset_items?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.assetItemList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },
    },

    created() {
        this.getAssetItemList(1);
    },

    mounted() {
        initTE({ Modal, Ripple, Input, Select, Dropdown })
    }
}
</script>
