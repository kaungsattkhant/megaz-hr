<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Items
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex gap-x-4 ">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>

                <div class="bg-white mb-0 w-[40%] text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Filter by category"
                        data-te-select-filter="true" v-model="searchCategory">
                        <option :value="category" v-for="category in itemCategoryList">
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <button class="add-btn " @click="searchBtnClicked">Search</button>
                <button class="add-btn " @click="clearSearchBtnClicked">Clear</button>

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
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Item Name
                                </th>
                                <th scope="col" class="  ">
                                    Price
                                </th>
                                <th scope="col" class="  ">
                                    Category
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                                <tr class="">
                                    <td class="">
                                        {{ per_page * (currentPage - 1) + (++itemIndex) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.item_prices.price }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.category.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- <button id="edit-btn" class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> -->
                                        <input :checked="item.is_active == 1" @change="isActiveToggled(item.id)"
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
                                            type="checkbox" role="switch" />
                                    </td>
                                </tr>
                            </div>

                            <!-- looping end -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 ml-2">
                    <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                        <li v-if="!isFirstGroup">
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                @click="previousPaginationGroupBtnClicked" :disabled="isFirstGroup">
                                Previous
                            </button>
                        </li>

                        <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]"
                            :key="pageNumberIndex" :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                            <button v-if="pageNumber == currentPage"
                                class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                                <span
                                    class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
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
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                @click="nextPaginationGroupBtnClicked" :disabled="isLastGroup">
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
                                <span
                                    class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
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


            <!-- Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <!--Modal title-->
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Create Item
                            </h5>
                            <!--Close button-->
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!--Modal body-->
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>

                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Item Name
                                </label>
                                <input type="text" placeholder="Item Name" v-model="name" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Price
                                </label>
                                <input type="number" placeholder="Item Price" v-model="price" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    UOM
                                </label>
                                <select name="" id="" v-model="selectedUOM" class="input-ui">
                                    <option :value="uom" v-for="(uom, uomIndex) in uomList" :key="uomList"> {{ uom.name
                                        }}
                                    </option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Category
                                </label>
                                <select name="" id="" v-model="selectedCategory" class="input-ui">
                                    <option :value="category" v-for="(category, categoryIndex) in itemCategoryList"
                                        :key="categoryIndex"> {{ category.name }} </option>
                                </select>
                            </div>

                        </div>

                        <!--Modal footer-->
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                                @click="createBtnClicked" data-te-modal-dismiss>
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Modal, Ripple, initTE, Select, Dropdown } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

    export default{
        data() {
            return {
                itemCategoryList: [],
                itemList: [],
                uomList: [],
                name: '',
                price: null,
                selectedUOM: null,
                selectedCategory: null,
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

        methods:{
            ...mapGetters(['getToken']),

            async getItemCategoryList(){
                let url = `/api/categories`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.itemCategoryList = response.data;
                }
            },

            async getUomList(){
                let url = `/api/uoms`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.uomList = response.data;
                }
            },

            async getItemList(pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }
                let url = `/api/items?page=${this.currentPage}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.itemList = response.data.data;
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

            async createBtnClicked(){
                let url = `/api/items`;
                let formData = new FormData();
                let seletedUOMs = [this.selectedUOM.id];
                formData.append('uoms', JSON.stringify(seletedUOMs));
                formData.append('name', this.name);
                formData.append('price', this.price);
                formData.append('category_id', this.selectedCategory.id);
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    // this.getItemList(this.currentPage);
                    window.location.reload();
                }
            },

            isActiveToggled(id){
                let index = this.itemList.findIndex(table => table.id == id);
                if(index != -1){
                    if(this.itemList[index].is_active == 1){
                        this.itemList[index].is_active = 0;
                    }
                    else{
                        this.itemList[index].is_active = 1;
                    }

                    let url = `/api/is_active`;
                    let formData = new FormData();
                    formData.append('id', id);
                    formData.append('type', 'item');
                    let response = postApiData({url: url, form_data: formData, token: this.getToken()});
                }
            },

            async searchBtnClicked(){
                let url = null;
                if(this.searchInput && this.searchCategory){
                    url = `/api/items?search_input=${this.searchInput}&category_id=${this.searchCategory.id}&page=1`;
                }
                if(this.searchInput && !this.searchCategory){
                    url = `/api/items?search_input=${this.searchInput}&page=1`;
                }
                if((!this.searchInput) && this.searchCategory){
                    url = `/api/items?category_id=${this.searchCategory.id}&page=1`;
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
                this.getItemList(this.currentPage);
            },

            nextPaginationGroupBtnClicked(){
                this.currentGroup += 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getItemList(this.currentPage);
            },

            previousPaginationGroupBtnClicked(){
                this.currentGroup -= 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getItemList(this.currentPage);
            },

            firstPaginationGroupBtnClicked(){
                this.currentGroup = 0;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getItemList(this.currentPage);
            },

            lastPaginationGroupBtnClicked(){
                this.currentGroup = this.paginationGroupsCount - 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getItemList(this.currentPage);
            }
        },

        created(){
            this.getItemCategoryList();
            this.getUomList();
            this.getItemList(null);
        },

        mounted(){
            initTE({ Modal, Ripple, Select, Dropdown });
        }
    }
</script>
