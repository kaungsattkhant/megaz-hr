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
                    <option v-for="category in itemCategoryList">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
            <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button>

        </div>
        <div class="flex justify-end flex-col">

            <button type="button" class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                data-te-toggle="modal" data-te-target="#create_modal">
                Add New
            </button>
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
                                Item Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- looping start -->
                        <div class="contents" v-for="(item, itemIndex) in itemList" :key="itemList">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++itemIndex }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ item.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ item.category_id }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button id="edit-btn" class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>

                        <!-- looping end -->
                    </tbody>
                </table>

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

                    <div class="relative  p-4">
                        <!--Modal title-->
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black"
                            id="create_modalLabel">
                            Create Item
                        </h5>
                        <!--Close button-->
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!--Modal body-->
                    <div class="relative px-12 py-4" data-te-modal-body-ref>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Item Name
                            </label>
                            <input type="text" placeholder="Item Name" v-model="name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                UOM
                            </label>
                            <select name="" id="" v-model="selectedUOM"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="uom" v-for="(uom, uomIndex) in uomList" :key="uomList"> {{ uom.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Category
                            </label>
                            <select name="" id="" v-model="selectedCategory"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="category" v-for="(category, categoryIndex) in itemCategoryList" :key="categoryIndex" > {{ category.name }} </option>
                            </select>
                        </div>

                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 " @click="createBtnClicked"
                        data-te-modal-dismiss>
                            Create
                        </button>
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
                selectedUOM: null,
                selectedCategory: null,
                searchInput: null,
                searchCategory: null,
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

            async getItemList(){
                let url = `/api/items`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.itemList = response.data;
                }
            },

            async createBtnClicked(){
                let url = `/api/items`;
                let formData = new FormData();
                let seletedUOMs = [this.selectedUOM.id];
                formData.append('uoms', JSON.stringify(seletedUOMs));
                formData.append('name', this.name);
                formData.append('category_id', this.selectedCategory.id);
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    this.itemList.unshift(response.data);
                }
            },

            async searchBtnClicked(){
                let url = null;
                console.log(this.searchCategory);
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
                this.getItemList();
            }
        },

        created(){
            this.getItemCategoryList();
            this.getUomList();
            this.getItemList();
        },

        mounted(){
            initTE({ Modal, Ripple, Select, Dropdown });
        }
    }
</script>
