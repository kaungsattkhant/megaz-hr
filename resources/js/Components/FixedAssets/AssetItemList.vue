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
                                    <td class=" ">
                                        {{ ++index }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ asset_item.name }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{asset_item.item_code}}
                                    </td>

                                </tr>
                            </div>


                            <!-- looping end -->
                        </tbody>
                    </table>
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

                per_page: 20,
                pageNumbers: [],
                currentPage: 1,
                paginationGroupsCount: 1,
                per_group: 10,
                groupedPageNumbers: [],
                currentGroup: 0,
                isFirstGroup: true,
                isLastGroup: false,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getAssetItemList(pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }

                let url = `/api/asset_items?page=${this.currentPage}&per_page=${this.per_page}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.assetItemList = response.data.data;
                }
            },
        },

        created(){
            this.getAssetItemList();
        },

        mounted(){
            initTE({ Modal, Ripple, Input, Select, Dropdown })
        }
    }
</script>
