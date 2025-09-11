<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Loan Details ({{ this.creditorName }})
                </p>
            </div>
            <div class="btn-container">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="date" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Date
                                </th>
                                <th scope="col" class="">
                                    Type
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">
                                    Balance
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, index) in primaryList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        {{ perPage * (currentPage - 1) + (index + 1) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ convertToFriendlyDate(item.date) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.type }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ parseFloat(item.amount).toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ parseFloat(item.balance).toLocaleString() }}
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getPrimaryList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getPrimaryList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { convertToFriendlyDate } from "../../utilities/datetime-helpers";

export default {
    components: {
        Multiselect
    },
    props: ["accountId"],
    data() {
        return {
            primaryList: [],
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            creditorName: null,

        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        getPrimaryList(pageNumber){
            getApiData({url: `/api/loans/${this.accountId}`, token: this.getToken()}).then((response)=>{
                if(response.success){
                    this.primaryList = response.data.data;
                    this.creditorName = this.primaryList[0].account_name;
                    this.lastPage = response.data.last_page;
                    this.currentPage = pageNumber;
                    this.perPage = response.data.per_page;
                }
            });
        },

        convertToFriendlyDate(datetimestr){
            return convertToFriendlyDate(datetimestr);
        },
    },

    created() {
        this.getPrimaryList(1);
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    },
}
</script>
