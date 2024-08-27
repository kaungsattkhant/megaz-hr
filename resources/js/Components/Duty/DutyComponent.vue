<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Duties
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <!-- <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">

                    <i class="fal fa-search"></i>
                </label> -->
                <input type="date" class="input-ui  mr-2 h-8" v-model="selectedDate" @change="dateChange()">
            </div>
            <div class="flex justify-end flex-col">

                <a href="/duty/create"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 ">
                    Add New
                </a>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    Id
                                </th>
                                <th scope="col" class="">
                                    From
                                </th>
                                <th scope="col" class="">
                                    Staff Name
                                </th>
                                <th scope="col" class="">
                                    Cooking Place
                                </th>
                                <th scope="col" class="">
                                    Tasks
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(duty, dutyIndex) in dutyList" :key="index">
                                <tr class="">
                                    <td class="">
                                        {{ dutyIndex+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ duty.date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ duty.staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ duty.cooking_place.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-for="(task) in duty.tasks" class="group">
                                            {{ task.name }} 
                                            <span class="group-last:hidden">, </span>
                                        </span>
                                        <!-- <ul>
                                            <li v-for="(task) in duty.tasks">
                                                {{ task.name }}
                                            </li>
                                        </ul> -->
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <a :href="'/duty/' + duty.id + '/edit'">
                                            <i class="far fa-pen cursor-pointer mr-3"></i>
                                        </a>
                                        <button @click="deleteDuty(duty.id)">
                                            <i class="far fa-trash-alt cursor-pointer" ></i>
                                        </button>
                                        
                                    </td>
                                </tr>
                                <!-- <tr class="" v-for="acc in journal.ledgers">
                                    <td class=" align-middle" rowspan="2" v-if="acc.action == 'credit'">
                                        {{ journalIndex+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ acc.account.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ journal.description }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ acc.action == 'debit' ? acc.value : '0' }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ acc.action == 'credit' ? acc.value : '0' }}
                                    </td>
                                </tr> -->

                            </div>
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
    data() {
        return {
            dutyList: [],
            selectedDate:getCurrentDate(),
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getDutyList() {
            const response = await getApiData({ url: '/api/duties?date=' + this.selectedDate, token: this.getToken() });
            if (response.data) {
                this.dutyList = response.data.data;
            }
        },
        dateChange(){
            this.getDutyList();
        }

        // async deleteCookingPlace(id) {
        //     let response = await deleteApiData({ url: `/api/cooking_places/` + id, token: this.getToken() });
        //     if (response.success) {
        //         this.getCookingPlaces();
        //     }
        //     else {
        //         this.$notify({
        //             title: `Input validation`,
        //             text: response.message,
        //             type: "warn"
        //         });
        //     }
        // },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getDutyList();
    }
}
</script>
