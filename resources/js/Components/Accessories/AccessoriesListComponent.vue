<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Accessories list
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>

                <div class="bg-white mb-0 w-[40%] text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Filter by category" class="text-xs"
                        data-te-select-filter="true" v-model="searchCategory">
                        <option :value="category" v-for="category in accessoriesCategoryList" :key="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <button class="add-btn " @click="searchBtnClicked">Search</button>
                <button class="add-btn " @click="clearSearchBtnClicked">Clear</button>
            </div>

            <div class="flex justify-end flex-col">
                <a href="/accessories/create" class="add-btn ">
                    Add New
                </a>

            </div>
        </div>
        <!-- {{ accessoriesList }} -->
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Accessories Name
                                </th>
                                <th scope="col" class="  ">
                                    Item
                                </th>
                                <th scope="col" class="  ">
                                    Image
                                </th>
                                <th scope="col" class="  ">
                                    Price
                                </th>
                                <!-- <th scope="col" class="  ">
                                    Is featured?
                                </th> -->
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <div class="contents" v-for="(accessories, index) in accessoriesList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap align-middle">
                                        {{ accessories.name }}
                                    </td>
                                    <td class="whitespace-nowrap align-middle">
                                        <p v-for="(accessory, accessoryIndex) in accessories.accessory_items" :key="accessoryIndex">
                                            {{ accessory.item.name }}
                                        </p>
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        <div
                                            class="w-full flex justify-center">
                                            <img width="80" height="100" style="aspect-ratio: 4/3; object-fit: cover;"
                                                :src="accessories.image_url" alt="Menu image">
                                        </div>
                                    </td>
                                    <td class=" align-middle ">
                                        {{ (accessories.accessory_price.price).toLocaleString() }}
                                    </td>
                                    <!-- <td class="  ">
                                        {{ (accessories.is_feature == 1) ? 'Yes' : 'No' }}
                                    </td> -->
                                    <td class="whitespace-nowrap align-middle">
                                        <a :href="`/accessories/${accessories.id}/edit`" id="edit-btn" class="pr-1">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <!-- <input :checked="menu.is_active == 1" @change="isActiveToggled(menu.id)"
                                            class="me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                    before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5
                                    after:w-5 after:rounded-full after:border-none after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                    after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                    checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                    checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                    focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                    focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                    checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                    checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-white/25 dark:after:bg-surface-dark dark:checked:bg-primary dark:checked:after:bg-primary"
                                            type="checkbox" role="switch" /> -->
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <div class="flex justify-center">

                    <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                        <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                            @click="getAccessoriesList(currentPage - 1)">«</button>

                        <button class=" text-sm px-5 border">
                            Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                lastPage }}</span>
                        </button>

                        <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                            :disabled="currentPage === lastPage" @click="getAccessoriesList(currentPage + 1)"> »</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            accessoriesCategoryList: [],
            accessoriesList: [],
            deleteId: null,

            searchInput: null,
            searchCategory: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getAccessoriesCategoryList() {
            let url = `/api/get_accessory_category`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.accessoriesCategoryList = response.data;
            }
        },

        async getAccessoriesList(pageNumber) {

            let url = `/api/accessories?page=${pageNumber}`;
            if (this.searchInput && this.searchCategory) {
                url = `/api/accessories?search_input=${this.searchInput}&accessories_id=${this.searchCategory.id}&page=${pageNumber}`;
            }
            if (this.searchInput && !this.searchCategory) {
                url = `/api/accessories?search_input=${this.searchInput}&page=${pageNumber}`;
            }
            if ((!this.searchInput) && this.searchCategory) {
                url = `/api/accessories?accessory_category_id=${this.searchCategory.id}&page=${pageNumber}`;
            }

            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.accessoriesList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        // isActiveToggled(id) {
        //     let index = this.accessoriesList.findIndex(menu => menu.id == id);
        //     if (index != -1) {
        //         if (this.accessoriesList[index].is_active == 1) {
        //             this.accessoriesList[index].is_active = 0;
        //         }
        //         else {
        //             this.accessoriesList[index].is_active = 1;
        //         }

        //         let url = `/api/is_active`;
        //         let formData = new FormData();
        //         formData.append('id', id);
        //         formData.append('type', 'menu');
        //         let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
        //     }
        // },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        async confirmDeleteBtnClicked() {
            // let url = `/api/staff/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                alert(`deleted`);
            }
        },

        async searchBtnClicked() {

            this.getAccessoriesList(1);
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.searchCategory = null;
            this.getAccessoriesList(1);
        },



    },

    created() {
        this.getAccessoriesCategoryList();
        this.getAccessoriesList(1);
    },

    mounted() {
        initTE({ Modal, Ripple, Input, Select, Dropdown });
    }
}
</script>
