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
                                Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- <div class="contents" v-for="(menu, index) in menuList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ menu.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    <p v-for="(ingredient, ingredientIndex) in menu.items"> {{ ingredient.name }} </p>
                                </td>
                                <td class=" px-6 py-4 ">
                                    {{ menu.prices[0].price }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button
                                    data-te-toggle="modal" data-te-target="#deleteModal"
                                        id="edit-btn" class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import { getApiData, deleteApiData } from '../../utilities/ajax-helpers';

    export default {
        data() {
            return {
                purchaseOrderList: []
            };
        },

        methods: {
            async getPurhaseOrderList(){
                // let response = await getApiData({url: `/api/menus`});
                // if(response.data){
                //     this.purchaseOrderList = response.data;
                // }
            },
        },

        created()
        {
            this.getPurhaseOrderList();
        },

        mounted()
        {

        }
    }
</script>
