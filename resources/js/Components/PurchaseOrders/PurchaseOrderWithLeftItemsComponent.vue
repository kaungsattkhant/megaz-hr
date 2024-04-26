<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
        </div>
    </div>
    <div class="block rounded-xl">
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

                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>
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


    export default {
        data() {
            return {
                purchaseOrderList: [],
            };
        },

        methods: {
            ...mapGetters(['getToken','getUser', 'getRoles', 'getDepartment']),

            async getPurhaseOrderList(pageNumber){
                let url = `/api/purchase_order_item_lefts`;
                if(pageNumber){
                    url = `${url}?page=${pageNumber}`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
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
