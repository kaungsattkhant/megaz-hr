<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
            <button hidden class="add-btn mt-0.5" data-te-toggle="modal" data-te-target="#create_modal">
                <i class="fal fa-plus"></i>
            </button>
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
                                Supplier Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Account
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Amount
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(ap, index) in apTransactionList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ ap.date }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ ap.supplier_name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ ap.account_id }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ (ap.amount).toLocaleString() }}
                                </td>

                                <td class=" px-6 py-4 ">
                                    &nbsp;
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
    import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";

    import { mapGetters } from 'vuex';
    import { getApiData, postApiData } from '../../utilities/ajax-helpers';

    export default {
        data() {
            return {
                apTransactionList: [],

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

            alertValiationMessage(field) {
                this.$notify({
                    title: `Input validation`,
                    text: `You forgot to provide ${field}, please try again`,
                    type: "warn"
                });
            },

            async getAccountPayableTransactions(pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }

                let url = `/api/account_payable_transaction_list?page=${this.currentPage}&per_page=${this.per_page}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.apTransactionList = response.data;
                }
            },
        },

        created(){
            this.getAccountPayableTransactions();
        },

        mounted() {
            initTE({Modal, Ripple, Input, Select, Dropdown});
        },
    }
</script>

