<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
        </div>
        <div class="flex justify-end flex-col">
            <a href="/purchase_orders/create" class="add-btn ">
                Add New
            </a>

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
                                <td class="whitespace-nowrap px-6 py-4 space-x-4">
                                    <a :href="'/purchase_orders/'+purchaseOrder.id+'/confirm'" id="" class="pr-1">
                                        <i class="far fa-check"></i>
                                    </a>
                                    <a :href="'/purchase_orders/'+purchaseOrder.id+'/confirm'" id="" class="pr-1">
                                        <i class="far fa-bars"></i>
                                    </a>
                                    <button data-te-toggle="modal" id="edit-btn" class="pr-1"
                                    data-te-target="#deleteModal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
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
    import { mapGetters } from 'vuex';
    import { getApiData } from '../../utilities/ajax-helpers';
    import { convertToFriendlyDate } from '../../utilities/datetime-helpers';

    export default {
        data() {
            return {
                purchaseOrderList: []
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getPurhaseOrderList(pageNumber){
                let url = `/api/purchase_orders`;
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

        }
    }
</script>
