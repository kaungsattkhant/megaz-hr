<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
        </div>
        <div class="flex justify-end flex-col">
            <a href="/suppliers/create" class="add-btn ">
                Add New
            </a>

        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Supplier Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Shop Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Ph number
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Address
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- looping start -->
                        <div class="contents" v-for="(supplier, supplierIndex) in supplierList">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++supplierIndex }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ supplier.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ supplier.shop_name }}
                                </td>
                                <td class=" px-6 py-4 ">
                                    {{ supplier.phone_number }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ supplier.address }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <a class="pr-2" :href="'/suppliers/' + supplier.id + '/edit'">
                                        <i class="fal fa-pen"></i>
                                    </a>

                                    <button data-te-toggle="modal"
                                        data-te-target="#deleteModal" id="edit-btn" class="pl-2">
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
import { Modal, Ripple, initTE, Input } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, deleteApiData } from '../../utilities/ajax-helpers';

export default {
    data() {
        return {
            supplierList: [],
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getSupplierList(){
            let url = `/api/suppliers`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.supplierList = response.data;
            }
        },
    },

    created(){
        this.getSupplierList();
    },

    mounted() {

    }
}
</script>
