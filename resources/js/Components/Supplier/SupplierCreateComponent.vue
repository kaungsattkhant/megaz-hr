<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                Add Staff
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6">

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Supplier Name
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Supplier Shop Name
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Supplier Ph Number
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>

            <div class="mb-4 col-span-6 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Address
                </label>
                <textarea name="" v-model="address"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="10"></textarea>
            </div>
            <div class="col-span-6"></div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Credit Limit
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Items
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class=" col-span-6 ">
                <button class="pl-1 py-2" data-te-toggle="modal" data-te-target="#add_department_modal">
                    <i class="far fa-plus"></i>
                </button>
            </div>

            <div class=" col-span-12">
                <table class="min-w-[40%] text-sm font-light ">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                Item
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                            
                        <tr class="">
                            <td class=" px-6 py-4 font-medium ">
                                1
                            </td>
                            <td class=" px-6 py-4 font-medium ">
                                    <button @click="removePurchaseOrderItemBtnClicked(purchaseOrderItemsIndex)">
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                            </td>
                        </tr>
                        <tr class="">
                            <td class=" py-2 "></td>
                        </tr>
                    </tbody>
                </table>
            </div>












        </div>
        <div>
            <button class="add-btn">
                Create Supplier
            </button>
        </div>




        
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {};
    },

    methods: {},

    mounted() {}
}
</script>
