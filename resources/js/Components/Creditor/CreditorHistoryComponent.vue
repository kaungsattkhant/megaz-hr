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
    <div class=" px-4 py-4 margin-bg rounded">
        <div class="overflow-x-auto">
            <div class="table-container">
                <table class=" primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="">
                                #
                            </th>
                            <th scope="col" class="">
                                Date
                            </th>
                            <th scope="col" class="">
                                Supplier Name
                            </th>
                            <th scope="col" class="">
                                Account
                            </th>
                            <th scope="col" class="">
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(creditor, index) in creditorTransactionList" :key="index">
                            <tr class="">
                                <td class="  font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap  ">
                                    {{ creditor.date }}
                                </td>
                                <td class="whitespace-nowrap  ">
                                    {{ creditor.supplier_name }}
                                </td>
                                <td class="whitespace-nowrap  ">
                                    {{ creditor.account ? creditor.account.name : '' }} ({{ creditor.account ? creditor.account.account_code : '' }})
                                </td>
                                <td class="whitespace-nowrap  ">
                                    {{ (creditor.amount).toLocaleString() }}
                                </td>
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
        props: ['creditorId'],
        data() {
            return {
                creditorTransactionList: [],

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

            async getCreditorTransactions(pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }

                let url = `/api/get_creditor_transaction_by_supplier?supplier_id=${this.creditorId}&page=${this.currentPage}&per_page=${this.per_page}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.creditorTransactionList = response.data;
                }
            },
        },

        created(){
            this.getCreditorTransactions();
        },

        mounted() {
            initTE({Modal, Ripple, Input, Select, Dropdown});
        },
    }
</script>

