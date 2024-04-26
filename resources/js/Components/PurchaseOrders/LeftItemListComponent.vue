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
                                Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Price
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Quantity
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(item, index) in itemList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ item.item.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ item.amount }}
                                </td>

                                <td class=" px-6 py-4 ">
                                    {{ item.purchase_order_item_left.quantity }}
                                </td>

                                <td class=" px-6 py-4 ">
                                    {{ (item.purchase_order_item_left.quantity * item.amount).toLocaleString() }}
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
        props: ['purchaseOrderId'],

        data() {
            return {
                itemList: [],
            };
        },

        methods: {
            ...mapGetters(['getToken','getUser', 'getRoles', 'getDepartment']),

            async getPurhaseOrderDetail(){
                let url = `/api/purchase_order_item_lefts/${this.purchaseOrderId}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.itemList = response.data.items;
                }
            },
        },

        created()
        {
            this.getPurhaseOrderDetail();
        },

        mounted()
        {
            initTE({Modal});
        }
    }
</script>
