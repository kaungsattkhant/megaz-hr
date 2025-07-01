<template>
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Selling Menus
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>

                    <div class="bg-white mb-0 w-[40%] text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Filter by category"
                            data-te-select-filter="true" v-model="searchCategory">
                            <option :value="category" v-for="category in menuCategoryList" :key="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <button class="add-btn " @click="searchBtnClicked">Search</button>
                    <button class="add-btn " @click="clearSearchBtnClicked">Clear</button>
                </div>

                <div class="flex justify-end flex-col">
                    <a href="/mrp/create" class="add-btn ">
                        Add New
                    </a>

                </div>
            </div>
        </div>
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
                                    Menu Name
                                </th>
                                <!-- <th scope="col" class="  ">
                                    Ingredients
                                </th>
                                <th scope="col" class="  ">
                                    Image
                                </th>
                                <th scope="col" class="  ">
                                    Price
                                </th>
                                <th scope="col" class="  ">
                                    Is featured?
                                </th> -->
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <div class="contents" v-for="(menu, index) in menuList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ menu.name }}
                                    </td>
                                    <!-- <td class="whitespace-nowrap  ">
                                        <p v-for="(ingredient, ingredientIndex) in menu.items" :key="ingredientIndex">
                                            {{ ingredient.name }}
                                        </p>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div
                                            class="relative flex border rounded text-center shrink-0 overflow-hidden rounded-md h-18 w-18">
                                            <img width="80" height="100" style="aspect-ratio: 4/3; object-fit: cover;"
                                                :src="menu.image_url" alt="Menu image">
                                        </div>
                                    </td>
                                    <td class="  ">
                                        {{ (menu.prices[0].price).toLocaleString() }}
                                    </td>
                                    <td class="  ">
                                        {{ (menu.is_feature == 1) ? 'Yes' : 'No' }}
                                    </td> -->
                                    <td class="whitespace-nowrap ">
                                        
                                        <input :checked="menu.is_active == 1" @change="isActiveToggled(menu.id)"
                                            class="mt-[0.1rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-white relative mx-3 
                                            before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] 
                                            after:absolute after:-mt-[0.2875rem] after:h-5 after:-left-1 after:w-5 after:rounded-full after:border-none after:bg-black after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] 
                                            checked:bg-black checked:after:absolute checked:after:z-[2] checked:after:-mt-[4px] checked:after:ms-[1.3625rem] checked:after:h-5 
                                            checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-black checked:after:shadow-switch-1 checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] 
                                            hover:cursor-pointer focus:outline-none focus:before:scale-100 focus:ring-0 focus:shadow-none focus:before:opacity-[0.12] focus:before:transition-[box-shadow_0.2s,transform_0.2s] 
                                            checked:focus:bg-black checked:hover:bg-black focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] 
                                            checked:focus:before:ms-[1.3625rem] checked:focus:before:scale-100 checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s]"
                                                    type="checkbox" role="switch" />
                                        <a :href="`/mrp/${menu.id}/edit`" id="edit-btn" class="pr-1 ml-2">
                                            <i class="fas fa-pen"></i>
                                        </a>
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
                            @click="getMenuList(currentPage - 1)">«</button>

                        <button class=" text-sm px-5 border">
                            Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                lastPage }}</span>
                        </button>

                        <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                            :disabled="currentPage === lastPage" @click="getMenuList(currentPage + 1)"> »</button>
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
            menuCategoryList: [],
            menuList: [],
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

        async getMenuCategoryList() {
            let url = `/api/menu_categories`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.menuCategoryList = response.data;
            }
        },

        async getMenuList(pageNumber) {

            let url = `/api/mrp?page=${pageNumber}`;
            if (this.searchInput && this.searchCategory) {
                url = `/api/mrp?search=${this.searchInput}&menu_category_id=${this.searchCategory.id}&page=${pageNumber}`;
            }
            if (this.searchInput && !this.searchCategory) {
                url = `/api/mrp?search=${this.searchInput}&page=${pageNumber}`;
            }
            if ((!this.searchInput) && this.searchCategory) {
                url = `/api/mrp?menu_category_id=${this.searchCategory.id}&page=${pageNumber}`;
            }

            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.menuList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        isActiveToggled(id) {
            let index = this.menuList.findIndex(menu => menu.id == id);
            if (index != -1) {
                let url = `/api/menu/` + id + `/toggle`;
                let formData = new FormData();
                if (this.menuList[index].is_active == 1) {
                    formData.append('is_active', 0);
                }
                else {
                    formData.append('is_active', 1);
                }
                formData.append('type', 'menu');
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
                if(response.success){
                    if (this.menuList[index].is_active == 1) {
                        this.menuList[index].is_active = 0;
                    }
                    else {
                        this.menuList[index].is_active = 1;
                    }
                }
            }
        },

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

            this.getMenuList(1);
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.searchCategory = null;
            this.getMenuList(1);
        },



    },

    created() {
        this.getMenuCategoryList();
        this.getMenuList(1);
    },

    mounted() {
        initTE({ Modal, Ripple, Input, Select, Dropdown });
    }
}
</script>
