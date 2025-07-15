<template>
    <div class="card-shadow">
        <div class="btn-container py-4 mb-0">
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
        <div class="box-container-table mt-0">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table ">
                        <thead class="">
                            <tr>
                                <th scope="col" class=" ">
                                    #
                                </th>
                                <th scope="col" class=" ">
                                    Date
                                </th>
                                <th scope="col" class=" ">
                                    Supplier Name
                                </th>
                                <th scope="col" class=" ">
                                    Account
                                </th>
                                <th scope="col" class=" ">
                                    Amount
                                </th>
                                <th scope="col" class=" ">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(ap, index) in apTransactionList" :key="index">
                                <tr class="">
                                    <td class="  font-medium ">
                                        {{ ++index }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ ap.date }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ ap.supplier_name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ ap.account_name }} ({{ ap.account_code }})
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ (ap.amount).toLocaleString() }}
                                    </td>

                                    <td class="  ">
                                        &nbsp;
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";

    import { mapGetters } from 'vuex';
    import { getApiData, postApiData } from '../../utilities/ajax-helpers';

    export default {
        props: ['supplierId'],
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

                let url = `/api/account_payable_transaction_list?supplier_id=${this.supplierId}&page=${this.currentPage}&per_page=${this.per_page}`;
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

