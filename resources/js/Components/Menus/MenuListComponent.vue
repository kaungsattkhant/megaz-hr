<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                <i class="fal fa-search"></i>
            </label>

            <div class="bg-white mb-0 w-[40%] text-sm inline-block" data-te-select-wrapper-ref>
                <select data-te-select-init data-te-select-placeholder="Filter by category"
                data-te-select-filter="true" v-model="searchCategory">
                    <option :value="category" v-for="category in menuCategoryList">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
            <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button>
        </div>

        <div class="flex justify-end flex-col">
            <a href="/menus/create" class="add-btn ">
                Add New
            </a>

        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Menu Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Ingredients
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <div class="contents" v-for="(menu, index) in menuList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ per_page * (currentPage - 1) + (++index) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ menu.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    <p v-for="(ingredient, ingredientIndex) in menu.items"> {{ ingredient.name }} </p>
                                </td>
                                <td class=" px-6 py-4 ">
                                    {{ menu.prices[0].price }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button
                                    data-te-toggle="modal" data-te-target="#deleteModal"
                                        id="edit-btn" class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>
                    </tbody>
                </table>
            </div>

            <div class="mt-2 ml-2">
                <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                    <li v-if="!isFirstGroup">
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="previousPaginationGroupBtnClicked"
                        :disabled="isFirstGroup">
                            Previous
                        </button>
                    </li>

                    <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                (current)
                            </span>
                        </button>
                        <button v-else
                            class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                        </button>
                    </li>
                    <li v-if="!isLastGroup">
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="nextPaginationGroupBtnClicked"
                        :disabled="isLastGroup">
                            Next
                        </button>
                    </li>
                </ul>

                <ul v-else class="list-style-none flex">
                    <li v-for="(pageNumber, pageNumberIndex) in pageNumbers" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                (current)
                            </span>
                        </button>
                        <button v-else
                            class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!--Delete Modal -->
        <!-- <div
        data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div
            data-te-modal-dialog-ref
            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <h5
                    class="text-xl font-medium leading-normal text-neutral-800 "
                    id="exampleModalLabel">
                    Delete ?
                    </h5>
                    <button
                    type="button"
                    class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss
                    aria-label="Close">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p>Are you sure ?</p>
                </div>
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button
                    type="button"
                    class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                    data-te-modal-dismiss
                    >
                        Close
                    </button>
                    <button @click="confirmDeleteBtnClicked"
                    type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                    class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 "
                    >
                        Delete
                    </button>
                </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
    import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
    import { getApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                menuCategoryList: [],
                menuList: [],
                deleteId: null,

                searchInput: null,
                searchCategory: null,

                per_page: 20,
                currentPage: 1,
                pageNumbers: [],
                paginationGroupsCount: 1,
                groupedPageNumbers: [],
                currentGroup: 0,
                isFirstGroup: true,
                isLastGroup: false,

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getMenuCategoryList(){
                let url = `/api/menu_categories`;
                let response = await getApiData({ url: url, token: this.getToken() });
                if(response.data){
                    this.menuCategoryList = response.data;
                }
            },

            async getMenuList(pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }
                let url = `/api/menus?page=${this.currentPage}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.menuList = response.data.data;
                    this.per_page = response.data.per_page;

                    this.pageNumbers = [];
                    this.lastPageNumber = response.data.last_page;

                    for(let i=1; i<=response.data.last_page; i++){
                        this.pageNumbers.push(i);
                    }

                    if(this.pageNumbers.length > 10){
                        this.groupedPageNumbers = [];
                        this.paginationGroupsCount = this.pageNumbers.length % 10;
                        for(let i=0; i<this.pageNumbers.length; i+=10){
                            let chunk = this.pageNumbers.slice(i, i+10);
                            this.groupedPageNumbers.push(chunk);
                        }

                        let lastGroupIndex = this.groupedPageNumbers.length - 1;
                        this.isFirstGroup = (this.currentGroup === 0);
                        this.isLastGroup = (lastGroupIndex === this.currentGroup);
                    }
                }
            },

            deleteBtnClicked(id){
                this.deleteId = id;
            },

            async confirmDeleteBtnClicked(){
                // let url = `/api/staff/${this.deleteId}`;
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    alert(`deleted`);
                }
            },

            async searchBtnClicked(){
                let url = null;
                if(this.searchInput && this.searchCategory){
                    url = `/api/menus?search_input=${this.searchInput}&menu_category_id=${this.searchCategory.id}&page=1`;
                }
                if(this.searchInput && !this.searchCategory){
                    url = `/api/menus?search_input=${this.searchInput}&page=1`;
                }
                if((!this.searchInput) && this.searchCategory){
                    url = `/api/menus?menu_category_id=${this.searchCategory.id}&page=1`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.itemList = response.data.data;
                }
            },

            clearSearchBtnClicked(){
                this.searchInput = null;
                this.searchCategory = null;
                this.getItemList(null);
            },

            pageBtnClicked(pageNumber){
                this.currentPage = pageNumber;
                this.getMenuList(this.currentPage);
            },

            nextPaginationGroupBtnClicked(){
                this.currentGroup += 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getMenuList(this.currentPage);
            },

            previousPaginationGroupBtnClicked(){
                this.currentGroup -= 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getMenuList(this.currentPage);
            },

            firstPaginationGroupBtnClicked(){
                this.currentGroup = 0;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getMenuList(this.currentPage);
            },

            lastPaginationGroupBtnClicked(){
                this.currentGroup = this.paginationGroupsCount - 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getMenuList(this.currentPage);
            }

        },

        created()
        {
            this.getMenuCategoryList();
            this.getMenuList(null);
        },

        mounted()
        {
            initTE({Modal, Ripple, Input, Select, Dropdown});
        }
    }
</script>
