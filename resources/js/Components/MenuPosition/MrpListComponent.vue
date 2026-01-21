<template>
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class="page-title">Selling Menus</p>
            </div>
            <div class="btn-container">
                <div class="flex gap-x-4">
                    <label for="search" class="search-input">
                        <input
                            type="text"
                            class="input-search"
                            placeholder="Search"
                            v-model="searchInput"
                        />
                        <i class="fal fa-search"></i>
                    </label>

                    <div
                        class="bg-white mb-0 w-[40%] text-xs h-8 border-b border-black rounded-bl-[4px] rounded-br-[4px] overflow-hidden inline-block"
                        data-te-select-wrapper-ref
                    >
                        <select
                            data-te-select-init
                            data-te-select-placeholder="Filter by category"
                            data-te-select-filter="true"
                            v-model="searchCategory"
                            @change="searchCategoryChanged()"
                        >
                            <option
                                :value="category"
                                v-for="category in menuCategoryList"
                                :key="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <button class="add-btn" @click="searchBtnClicked">
                        Search
                    </button>
                    <button class="add-btn" @click="clearSearchBtnClicked">
                        Clear
                    </button>
                </div>

                <div class="flex justify-end flex-col">
                    <a href="/mrp/create" class="add-btn"> Add New </a>
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
                                <th scope="col" class="  ">#</th>
                                <th scope="col" class="  ">Menu</th>
                                <th scope="col" class="  ">Description</th>
                                <th scope="col" class="  ">Price</th>
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
                                <th scope="col" class=""></th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div
                                class="contents"
                                v-for="(menu, index) in menuList"
                                :key="index"
                            >
                                <tr class="">
                                    <td class="  ">
                                        {{
                                            perPage * (currentPage - 1) +
                                            (index + 1)
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ menu.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ menu.description }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ menu.price?.price.toLocaleString() }}
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
                                    <td class="whitespace-nowrap">
                                        <input
                                            :checked="menu.is_active == 1"
                                            @change="isActiveToggled(menu.id)"
                                            class="mt-[0.1rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-white relative mx-3 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:-mt-[0.2875rem] after:h-5 after:-left-1 after:w-5 after:rounded-full after:border-none after:bg-black after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-black checked:after:absolute checked:after:z-[2] checked:after:-mt-[4px] checked:after:ms-[1.3625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-black checked:after:shadow-switch-1 checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100 focus:ring-0 focus:shadow-none focus:before:opacity-[0.12] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:bg-black checked:hover:bg-black focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:before:ms-[1.3625rem] checked:focus:before:scale-100 checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s]"
                                            type="checkbox"
                                            role="switch"
                                        />
                                        <a
                                            :href="`/mrp/${menu.id}/edit`"
                                            id="edit-btn"
                                            class="pr-1 ml-2"
                                        >
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button
                                            class="pr-1 ml-2"
                                            type="button"
                                            data-te-toggle="modal"
                                            data-te-target="#update_price_modal"
                                            @click="
                                                updatePriceBtnClicked(
                                                    menu,
                                                    index
                                                )
                                            "
                                        >
                                            <i class="fas fa-dollar-sign"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="menuList.length < 1 && !loading">
                                <td class="" colspan="5">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <div class="flex justify-center">
                    <div
                        v-if="totalData != 0"
                        class="bg-white flex justify-center mt-5 py-3"
                    >
                        <button
                            class="rounded px-6 py-1 border hover:bg-slate-200"
                            :disabled="currentPage === 1"
                            @click="getMenuList(currentPage - 1)"
                        >
                            «
                        </button>

                        <button class="text-sm px-5 border">
                            Page
                            <span @dblclick="showInput">{{ currentPage }}</span>
                            / <span class="text-gray-400">{{ lastPage }}</span>
                        </button>

                        <button
                            class="rounded px-6 py-1 border hover:bg-slate-200"
                            :disabled="currentPage === lastPage"
                            @click="getMenuList(currentPage + 1)"
                        >
                            »
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div
            data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="priceUpdateModal"
            tabindex="-1"
            aria-labelledby="create_modalLabel"
            aria-hidden="true"
        >
            <div
                data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
            >
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none"
                >
                    <div
                        class="relative flex justify-between py-2 px-6 border-b"
                    >
                        <h5
                            class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel"
                        >
                            Update Item Price
                        </h5>
                        <button
                            type="button"
                            class="text-xs focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss
                            aria-label="Close"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-4 w-4"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                    <!-- <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Price
                            </label>
                            <input type="number" placeholder="Price" v-model="price"
                            class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Uom
                            </label>
                            <select name="" id="" v-model="selectedUom" class="input-ui">
                                <option :value="uom" v-for="(uom, index) in uomList" :key="index"> {{ uom.uom_name }}
                                </option>
                            </select>
                        </div>
                    </div> -->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button
                            type="button"
                            class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss
                            aria-label="Close"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            class="add-btn focus:outline-none focus:ring-0"
                            @click="confirmUpdatePriceBtnClicked"
                        >
                            Update Price
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Modal -->
    <div
        data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="update_price_modal"
        tabindex="-1"
        aria-labelledby="create_modalLabel"
        aria-modal="true"
        role="dialog"
    >
        <div
            data-te-modal-dialog-ref
            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]"
        >
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none"
            >
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5
                        class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="update_price_modalLabel"
                    >
                        Update Price
                    </h5>
                    <button
                        type="button"
                        class="text-xs focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss
                        id="close_create_bank_modal"
                        aria-label="Close"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <div class="relative px-12 py-4" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            New Price
                        </label>
                        <input
                            type="number"
                            v-model="newPrice"
                            placeholder="Price"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                        />
                    </div>
                </div>

                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button
                        type="button"
                        class="cancel-btn focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss
                        aria-label="Close"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        class="add-btn focus:outline-none focus:ring-0"
                        data-te-modal-dismiss
                        @click="confirmUpdatePriceBtnClicked"
                    >
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->
    <button
        type="button"
        hidden
        disabled
        class=""
        data-te-toggle="modal"
        data-te-target="#exampleModal"
        data-te-ripple-init
        data-te-ripple-color="light"
    >
        Launch demo modal
    </button>

    <!-- Modal -->
    <div
        data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div
            data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
        >
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none dark:bg-surface-dark"
            >
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4 dark:border-white/10"
                >
                    <h5
                        class="text-xl font-medium leading-normal text-surface dark:text-white"
                        id="exampleModalLabel"
                    >
                        Modal title
                    </h5>
                    <button
                        type="button"
                        class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-neutral-300"
                        data-te-modal-dismiss
                        aria-label="Close"
                    >
                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    Modal body text goes here.
                </div>

                <!-- Modal footer -->
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 p-4 dark:border-white/10"
                >
                    <button
                        type="button"
                        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-primary-700 transition duration-150 ease-in-out hover:bg-primary-accent-200 focus:bg-primary-accent-200 focus:outline-none focus:ring-0 active:bg-primary-accent-200 dark:bg-primary-300 dark:hover:bg-primary-400 dark:focus:bg-primary-400 dark:active:bg-primary-400"
                        data-te-modal-dismiss
                        data-te-ripple-init
                        data-te-ripple-color="light"
                    >
                        Close
                    </button>
                    <button
                        type="button"
                        class="ms-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                        data-te-ripple-init
                        data-te-ripple-color="light"
                    >
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
    <button
        id="price-edit-btn"
        class="hidden"
        data-te-toggle="modal"
        data-te-target="#priceUpdateModal"
    ></button>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import {
    getApiData,
    postApiData,
    deleteApiData,
} from "../../utilities/ajax-helpers";
import { mapGetters } from "vuex";
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
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
            totalData: 0,

            editId: null,
            editIndex: null,
            newPrice: null,

            loading: false,
        };
    },

    methods: {
        ...mapGetters(["getToken"]),

        async getMenuCategoryList() {
            let url = `/api/menu_categories`;
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.menuCategoryList = response.data;
            }
        },

        async getMenuList(pageNumber) {
            this.loading = true;
            let url = `/api/mrp?page=${pageNumber}`;
            if (this.searchInput && this.searchCategory) {
                url = `/api/mrp?search=${this.searchInput}&menu_category_id=${this.searchCategory.id}&page=${pageNumber}`;
            }
            if (this.searchInput && !this.searchCategory) {
                url = `/api/mrp?search=${this.searchInput}&page=${pageNumber}`;
            }
            if (!this.searchInput && this.searchCategory) {
                url = `/api/mrp?menu_category_id=${this.searchCategory.id}&page=${pageNumber}`;
            }

            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.loading = false;
                this.menuList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },
        searchCategoryChanged(){
            this.getMenuList(1);
        },
        isActiveToggled(id) {
            let index = this.menuList.findIndex((menu) => menu.id == id);
            if (index != -1) {
                let url = `/api/menu/` + id + `/toggle`;
                let formData = new FormData();
                if (this.menuList[index].is_active == 1) {
                    formData.append("is_active", 0);
                } else {
                    formData.append("is_active", 1);
                }
                formData.append("type", "menu");
                let response = postApiData({
                    url: url,
                    form_data: formData,
                    token: this.getToken(),
                });
                if (response.success) {
                    if (this.menuList[index].is_active == 1) {
                        this.menuList[index].is_active = 0;
                    } else {
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
            let response = await deleteApiData({
                url: url,
                token: this.getToken(),
            });
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

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn",
            });
        },

        updatePriceBtnClicked(menu, index) {
            if (menu.price) {
                this.newPrice = menu.price.price;
            }
            this.editId = menu.id;
            this.editIndex = index;
        },

        confirmUpdatePriceBtnClicked() {
            if (!this.newPrice) {
                this.alertvalidationMessage("new price");
                return;
            }

            let formData = new FormData();
            formData.append("price", this.newPrice);
            postApiData({
                url: `/api/menus/${this.editId}/prices`,
                form_data: formData,
                token: this.getToken(),
            }).then((response) => {
                if (response.data) {
                    this.menuList[this.editIndex].price = response.data;
                    // menu.price = response.data;
                    // console.log(response.data);
                    this.editId = null;
                    this.newPrice = null;
                    this.editIndex = null;
                }
            });
        },
    },

    created() {
        this.getMenuCategoryList();
        this.getMenuList(1);
    },

    mounted() {
        initTE({ Modal, Ripple, Input, Select, Dropdown });
    },
};
</script>
