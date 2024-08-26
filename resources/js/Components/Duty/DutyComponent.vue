<template>
    <div class="flex justify-between mb-3">
        <notifications position="top center" />

        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
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
            <div class="table-container">
                <table class="primary-table">
                    <thead>
                        <tr>
                            <th scope="col" class="">
                                #
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
                        </tr>
                    </thead>
                    <tbody>
                        <!-- looping start -->
                        <div class="contents" v-for="(duty, index) in dutyList" :key="index">
                            <tr class="">
                                <td class=" font-medium ">
                                    {{ index + 1 }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ duty.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ duty.area.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    <a :href="'/duty/' + duty.id + '/edit'">
                                        <i class="far fa-pen cursor-pointer mr-3"></i>
                                    </a>
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
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            dutyList: [],
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getDutyList() {
            const response = await getApiData({ url: '/api/duties', token: this.getToken() });
            if (response.data) {
                this.dutyList = response.data.data;
            }
        },

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
