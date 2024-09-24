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

            <a href="/cooking_places/create"
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
                                Cooking Place
                            </th>
                            <th scope="col" class="">
                                Cooking Area
                            </th>

                            <th scope="col" class="">
                                Menu
                            </th>

                            <th scope="col" class="">
                                Skill
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- looping start -->
                        <div class="contents" v-for="(cookingPlace, index) in cookingPlaces" :key="index">
                            <tr class="">
                                <td class=" font-medium ">
                                    {{ perPage * (currentPage - 1) + (++index) }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ cookingPlace.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ cookingPlace.area.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ cookingPlace.available_cooking_places.find(place =>
                                        place.cooking_placeable_type === 'menu')?.cooking_placeable.name }}
                                </td>

                                <td class="whitespace-nowrap">
                                    {{ cookingPlace.available_cooking_places.find(place =>
                                        place.cooking_placeable_type === 'skill')?.cooking_placeable.skill }}
                                </td>

                                <td class="whitespace-nowrap">
                                    <a :href="'/cooking_places/' + cookingPlace.id + '/edit'">
                                        <i class="far fa-pen cursor-pointer mr-3"></i>
                                    </a>
                                    <i class="far fa-trash-alt cursor-pointer"
                                        @click="deleteCookingPlace(cookingPlace.id)"></i>
                                </td>
                            </tr>
                        </div>
                    </tbody>
                </table>

                <!-- pagination -->
                <div class="flex justify-center">

                    <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                        <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                            @click="getCookingPlaces(currentPage - 1)">«</button>

                        <button class=" text-sm px-5 border">
                            Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                lastPage }}</span>
                        </button>

                        <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                            :disabled="currentPage === lastPage" @click="getCookingPlaces(currentPage + 1)">
                            »</button>
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

export default {
    data() {
        return {
            cookingPlaces: [],

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getCookingPlaces(pageNumber) {
            const response = await getApiData({ url: `/api/cooking_places?page=${pageNumber}`, token: this.getToken() });
            if (response.data) {
                this.cookingPlaces = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        async deleteCookingPlace(id) {
            let response = await deleteApiData({ url: `/api/cooking_places/` + id, token: this.getToken() });
            if (response.success) {
                this.getCookingPlaces(1);
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getCookingPlaces(1);
    }
}
</script>
