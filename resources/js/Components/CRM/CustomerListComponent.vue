<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Customers
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">

                <!-- <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button> -->
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table  ">
                        <thead class="  ">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Phone Number
                                </th>
                                <th scope="col" class="">
                                    Township
                                </th>
                                <th scope="col" class="">
                                    Address
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">
                                    Retention
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <!-- <div v-for="(complain,index) in complainList" :key="index" class="contents">
                                <tr class="">
                                    <td class="  ">
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <a href="#">
                                            {{ complain.title }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <a href="#">
                                            {{ complain.description }}
                                        </a>
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        <a href="#">
                                            {{ complain.status }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <a href="#">
                                            {{ complain.complaint_category.name }}
                                        </a>
                                    </td>
                                </tr>
                            </div> -->

                            <!-- looping end -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            customerList: [],

            per_page: 10,
            pageNumbers: [],
            currentPage: 1,
            paginationGroupsCount: 1,
            per_group: 10,
            groupedPageNumbers: [],
            currentGroup: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
    },

    mounted() {

    }
}
</script>
