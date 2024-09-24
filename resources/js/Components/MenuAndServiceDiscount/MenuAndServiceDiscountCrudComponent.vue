<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Menu and Service Discounts
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">
                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
                    Add New
                </button>
            </div>
        </div>
        <div class="block mx-4 mt-4 pb-4">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class=" ">
                                    Discount Name
                                </th>
                                <th scope="col" class="">
                                    From Date
                                </th>
                                <th scope="col" class="">
                                    To Date
                                </th>
                                <th scope="col" class="">
                                    Original Price
                                </th>
                                <th scope="col" class="">
                                    Discount Price
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(discount, index) in discountList">
                                <tr class="">
                                    <td class=" px-6 py-4 font-medium ">
                                        {{ per_page * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ discount.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ discount.from_date }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ discount.to_date }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        <div v-if="discount.discountable_type == 'menu'">
                                            {{ discount.discountable.prices[0].price }}
                                        </div>
                                        <div v-else>
                                            {{ discount.discountable.price_per_hour }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ discount.discount_price }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <!-- <button data-te-toggle="modal"
                                            data-te-target="#editModal" id="edit-btn" class="pr-1">
                                            <i class="fas fa-pen"></i>
                                        </button> -->

                                        <button data-te-toggle="modal" data-te-target="#deleteModal" class="pr-1"
                                            @click="deleteBtnClicked(discount.id)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="lastPage != -1" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1" @click="getAreasList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getAreasList(currentPage + 1)"> »</button>
                        </div>
                    </div>
                </div>
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
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create Menu & Service Discount
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" v-model="name" placeholder="Discount Name" class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-black mb-3">Type</label>
                            <div class="text-sm w-full bg-transparent rounded-lg focus:ring-0"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Type"
                                    v-model="discountType" data-te-select-filter="true" name="" id="" class="input-ui"
                                    @change="discountTypeSelectChanged">
                                    <option value="menu"> Menu </option>
                                    <option value="service"> Service </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4" v-show="discountType == 'menu'">
                            <label class="block text-sm text-black mb-3">Menu Category</label>
                            <div class="text-sm w-full bg-transparent rounded-lg focus:ring-0"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Menu Category"
                                    v-model="selectedMenuCategory" data-te-select-filter="true" name="" id=""
                                    class="input-ui" @change="menuCategorySelectChanged">
                                    <option v-for="(menuCategory, menuCategoryIndex) in menuCategoryList"
                                        :value="menuCategory"> {{ menuCategory.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4" v-show="discountType == 'menu'">
                            <label for="" class="label-form mb-3"> Menu </label>
                            <div class="text-sm w-full bg-transparent rounded-lg focus:ring-0"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Menu"
                                    v-model="selectedMenu" data-te-select-filter="true" name="" id="" class="input-ui"
                                    @change="menuSelectChanged">
                                    <option v-for="(menu, menuIndex) in menuList" :value="menu"> {{ menu.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4" v-show="discountType == 'service'">
                            <label for="" class="label-form mb-3"> Service </label>
                            <div class="text-sm w-full bg-transparent rounded-lg focus:ring-0"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Service"
                                    v-model="selectedService" data-te-select-filter="true" name="" id=""
                                    class="input-ui" @change="serviceSelectChanged">
                                    <option v-for="(service, serviceIndex) in serviceList" :value="service"> {{
                                        service.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Original Price
                            </label>
                            <input type="number" disabled v-model="originalPrice" placeholder="Original Price"
                                class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Discount Price
                            </label>
                            <input type="number" v-model="discountedPrice" placeholder="Discount Price"
                                class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                From
                            </label>
                            <input type="date" placeholder="From" v-model="startDate" :min="today" class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                To
                            </label>
                            <input type="date" placeholder="To" v-model="endDate" :min="today" class="input-ui">
                        </div>

                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="createBtnClicked" class="add-btn focus:outline-none focus:ring-0 "
                            data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                    <div
                        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                        <!--Modal title-->
                        <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                            Delete ?
                        </h5>
                        <!--Close button-->
                        <button type="button"
                            class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!--Modal body-->
                    <div class="relative flex-auto p-4" data-te-modal-body-ref>
                        <p>
                            Are you sure ?
                        </p>
                    </div>

                    <!--Modal footer-->
                    <div
                        class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                        <button type="button"
                            class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                            data-te-modal-dismiss>
                            Close
                        </button>
                        <button @click="confirmDeleteBtnClicked" type="button" data-te-toggle="modal"
                            data-te-target="#deleteModal"
                            class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { getCurrentDate } from '../../utilities/datetime-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            today: getCurrentDate(),

            discountList: [],

            discountType: null,

            menuCategoryList: [],
            selectedMenuCategory: null,
            menuList: [],
            selectedMenu: null,

            serviceList: [],
            selectedService: null,

            name: null,
            originalPrice: null,
            discountedPrice: null,
            startDate: null,
            endDate: null,

            deleteId: null,

            per_page: 20,
            pageNumbers: [],
            currentPage: 1,
            paginationGroupsCount: 1,
            per_group: 10,
            groupedPageNumbers: [],
            currentGroup: 0,
            isFirstGroup: true,
            isLastGroup: false,
        }

    },

    methods: {
        ...mapGetters(['getToken']),

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        async getDiscountList(pageNumber) {
            if (pageNumber) {
                this.currentPage = pageNumber;
            }
            let url = `/api/menu_service_discounts?page=${this.currentPage}&per_page=${this.per_page}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.discountList = response.data.data;
                this.per_page = response.data.per_page;

                this.pageNumbers = [];
                this.lastPageNumber = response.data.last_page;

                for (let i = 1; i <= response.data.last_page; i++) {
                    this.pageNumbers.push(i);
                }

                if (this.pageNumbers.length > 10) {
                    this.groupedPageNumbers = [];
                    this.paginationGroupsCount = this.pageNumbers.length % 10;
                    for (let i = 0; i < this.pageNumbers.length; i += 10) {
                        let chunk = this.pageNumbers.slice(i, i + 10);
                        this.groupedPageNumbers.push(chunk);
                    }

                    let lastGroupIndex = this.groupedPageNumbers.length - 1;
                    this.isFirstGroup = (this.currentGroup === 0);
                    this.isLastGroup = (lastGroupIndex === this.currentGroup);
                }
            }
        },

        discountTypeSelectChanged() {
            this.serviceList = [];
            this.menuList = [];
            this.menuCategoryList = [];
            this.selectedMenu = null;
            this.selectedService = null;
            if (this.discountType == 'service') {
                this.getServiceList();
            }
            if (this.discountType == 'menu') {
                this.getMenuCategoryList();
            }
        },

        async getMenuCategoryList() {
            let url = `/api/menu_categories`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.menuCategoryList = response.data;
            }
        },

        async menuCategorySelectChanged() {
            let url = `/api/menu_categories/${this.selectedMenuCategory.id}/menus`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.menuList = response.data;
            }
        },

        menuSelectChanged() {
            this.originalPrice = this.selectedMenu.prices[0].price;
        },

        async getServiceList() {
            let url = `/api/entities?type=service`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.serviceList = response.data;
            }
        },

        serviceSelectChanged() {
            this.originalPrice = this.selectedService.price_per_hour;
        },

        async createBtnClicked() {
            if (!this.name) {
                this.alertValiationMessage(`discount name`);
                return 1;
            }
            if (!this.discountType) {
                this.alertValiationMessage(`discount type`);
                return 1;
            }
            if (this.discountType == 'service') {
                if (!this.selectedService) {
                    this.alertValiationMessage(`service`);
                    return 1;
                }
            }
            if (this.discountType == 'menu') {
                if (!this.selectedMenu) {
                    this.alertValiationMessage(`menu`);
                    return 1;
                }
            }
            if (!this.discountedPrice) {
                this.alertValiationMessage(`discount price`);
                return 1;
            }
            if (!this.startDate) {
                this.alertValiationMessage(`discount start date`);
                return 1;
            }
            if (!this.endDate) {
                this.alertValiationMessage(`discount end date`);
                return 1;
            }

            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('from_date', this.startDate);
            formData.append('to_date', this.endDate);
            formData.append('discount_price', this.discountedPrice);
            formData.append('type', this.discountType);
            if (this.discountType == 'menu') {
                formData.append('discountable_id', this.selectedMenu.id);
                formData.append('discountable_type', 'menu');
            }
            if (this.discountType == 'service') {
                formData.append('discountable_id', this.selectedService.id);
                formData.append('discountable_type', 'entity');
            }

            let url = `/api/menu_service_discounts`;
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Discount created successfully`,
                    type: "info"
                });
            }
            else {
                this.$notify({
                    text: `Discount create failed`,
                    type: "error"
                });
            }
            this.discountType = null;
            this.discountTypeSelectChanged();

            this.getDiscountList(this.currentPage);
        },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        async confirmDeleteBtnClicked() {
            let url = `/api/menu_service_discounts/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Discount deleted successfully`,
                    type: "info"
                });
            }
            else {
                this.$notify({
                    text: `Discount delete failed`,
                    type: "error"
                });
            }

            this.deleteId = null;
            this.getDiscountList(this.currentPage);
        },

        pageBtnClicked(pageNumber) {
            this.currentPage = pageNumber;
            this.getDiscountList(this.currentPage);
        },

        nextPaginationGroupBtnClicked() {
            this.currentGroup += 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getDiscountList(this.currentPage);
        },

        previousPaginationGroupBtnClicked() {
            this.currentGroup -= 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getDiscountList(this.currentPage);
        },

        firstPaginationGroupBtnClicked() {
            this.currentGroup = 0;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
            this.getDiscountList(this.currentPage);
        },

        lastPaginationGroupBtnClicked() {
            this.currentGroup = this.paginationGroupsCount - 1;
            let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
            this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
            this.getDiscountList(this.currentPage);
        }
    },

    created() {
        this.getDiscountList(null);
    },

    mounted() {

    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
