<template>
    <div class="card-shadow">
        <div>
            <p class=" page-title">
                Purchase Orders left Item
            </p>
        </div>
        <div class="btn-container pt-4`">
            <notifications position="top center" />
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>
                
            </div>
            <div class="flex pr-0 gap-x-4">
                
            </div>
        </div>
    </div>
    <div class="box-container-table">
        <div class="overflow-x-auto">
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Date
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Purchase Order Id
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Status
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Manager Check
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Financial Check
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                MD Checked
                            </th>

                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <TableSkeleton
                    v-if="loading"
                    :rows="20"
                    :cols="6"
                    />
                    <tbody>
                        <div class="contents" v-for="(purchaseOrder, index) in purchaseOrderList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ purchaseOrder.date }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ purchaseOrder.po_id }}
                                </td>

                                <td class=" px-6 py-4 ">
                                    {{ purchaseOrder.status }}
                                </td>

                                <td  class="px-6 py-4">
                                    {{ purchaseOrder.manager_check_id == null ? 'No' : 'Yes' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ purchaseOrder.financial_check_id == null ? 'No' : 'Yes' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ purchaseOrder.is_md_checked == 0 ? 'No' : 'Yes' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 space-x-4">
                                    <a :href="'/purchase_order_left_items/'+purchaseOrder.id" id="" class="pr-1">
                                        <i class="far fa-bars"></i>
                                    </a>
                                </td>
                            </tr>
                        </div>
                        <tr class=" !text-center" v-if="purchaseOrderList.length < 1 && !loading">
                            <td class="" colspan="8">
                                No Data Here
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import { Modal, initTE } from "tw-elements";

    import { mapGetters } from 'vuex';
    import { getApiData, postApiData } from '../../utilities/ajax-helpers';
    import { convertToFriendlyDate } from '../../utilities/datetime-helpers';
    import TableSkeleton from "../Common/TableSkeleton.vue";


    export default {
        components: {
            TableSkeleton
        },
        data() {
            return {
                purchaseOrderList: [],
                loading: false,
            };
        },

        methods: {
            ...mapGetters(['getToken','getUser', 'getRoles', 'getDepartment']),

            async getPurhaseOrderList(pageNumber){
                this.loading = true;
                let url = `/api/purchase_order_item_lefts`;
                if(pageNumber){
                    url = `${url}?page=${pageNumber}`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.loading = false;
                    this.purchaseOrderList = response.data.data;
                    this.purchaseOrderList.forEach((po)=>{
                        po.date = convertToFriendlyDate(po.date);
                    });
                }
            },
        },

        created()
        {
            this.getPurhaseOrderList(null);
        },

        mounted()
        {
            initTE({Modal});
        }
    }
</script>
