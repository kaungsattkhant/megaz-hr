<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Staff Advanced
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <!-- <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label> -->
                <input type="month" class="input-ui  mr-2 h-8" v-model="selectedMonth" @change="monthChange()">
            </div>
            <div class="flex justify-end flex-col">

                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button>
            </div>
        </div>


        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Staff Name
                                </th>
                                <th scope="col" class="">
                                    Date
                                </th>
                                <th scope="col" class="">
                                    Opening
                                </th>
                                <th scope="col" class="">
                                    Addition
                                </th>
                                <th scope="col" class="">
                                    settlement
                                </th>
                                <th scope="col" class="">
                                    Balance
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="staffAdvance">
                            <tr class="">
                                <td class=" ">
                                    1
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ staffAdvance.name}}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ formatDate(currentdate).slice(0,3) }} 1
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ staffAdvance.staff_balance ? staffAdvance.staff_balance.opening_balance : '' }}
                                </td>
                                <td class="whitespace-nowrap" colspan="2">

                                </td>
                                <td class="whitespace-nowrap">
                                    {{ staffAdvance.staff_balance ? staffAdvance.staff_balance.closing_balance : '' }}
                                </td>
                            </tr>
                            <tr :key="adv.id" v-for="adv in staff_advance">
                                <td class=" "></td>
                                <td class="whitespace-nowrap"></td>
                                <td class="whitespace-nowrap">
                                    {{ formatDate(adv.date_time) }}
                                </td>
                                <td class="whitespace-nowrap"></td>
                                <td class="whitespace-nowrap">
                                    {{ adv.type == 'addition' ? adv.amount : ''}}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ adv.type == 'settlement' ? adv.amount : ''}}
                                </td>
                                <td class="whitespace-nowrap">

                                </td>
                            </tr>
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
    import { getCurrentDate } from "../../utilities/datetime-helpers";

    export default {
        props: ['staffId'],
        data() {
            return {
                staffAdvance:null,
                staff_advance:null,
                staff_balance:null,

                selectedNewMonth:null,
                currentMonth:null,
                currentdate : getCurrentDate(),
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            formatDate(dateTime) {
                const date = new Date(dateTime);
                const options = { month: 'short', day: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            },

            monthChange(){
                let selectedNewMonth = this.selectedMonth.slice(5,7);
                this.getStaffAdvance(selectedNewMonth);
            },
            async getStaffAdvance(selectedmonth){
                const response = await getApiData({ url: '/api/staff_balances/'+this.staffId + '?month=' + selectedmonth , token: this.getToken() });
                if(response.data){
                    this.staffAdvance = response.data;
                    this.staff_advance = response.data.staff_advances;
                    this.staff_balance = response.data.staff_balance;
                }
            },
        },
        mounted()
        {
            const date = new Date();
            this.currentMonth = date.getMonth() + 1;
            this.getStaffAdvance(this.currentMonth);
            initTE({ Modal,Select, Ripple });
        },
        created(){



        }

    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
