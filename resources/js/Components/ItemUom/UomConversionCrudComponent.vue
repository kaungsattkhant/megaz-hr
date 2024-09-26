<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            UOM Conversion
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>

                <!-- <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
            <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button> -->

            </div>
            <div class="flex justify-end flex-col">
                <div class="flex gap-3">
                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
                        Add New
                    </button>

                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#uom">
                        Create Uom
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Base Unit
                                </th>
                                <th scope="col" class="  ">
                                    Conversion Unit
                                </th>
                                <th scope="col" class="  ">
                                    Conversion
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(uom, itemIndex) in uomConversionList" :key="itemIndex">
                                <tr class="">
                                    <td class=" ">
                                        {{ perPage * (currentPage - 1) + (++itemIndex) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ uom.base_unit.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ uom.conversion_unit.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ uom.conversion }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        <button id="edit-btn" class="pr-1">
                                            <i class="fas fa-pencil-alt" @click="editBtnClicked(uom)"
                                                data-te-toggle="modal" data-te-target="#edit_modal"></i>
                                        </button>
                                        <!-- <input
                                    :checked="item.is_active == 1"
                                    @change="isActiveToggled(item.id)"
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
                                    type="checkbox"
                                    role="switch"/> -->
                                    </td>
                                </tr>
                            </div>

                            <!-- looping end -->
                        </tbody>
                    </table>
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1"
                                @click="getUomConversionList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage"
                                @click="getUomConversionList(currentPage + 1)"> »</button>
                        </div>
                    </div>
                </div>
                <!-- <div class="mt-2 ml-2">
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
                </div> -->
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
                    <!--Modal title-->
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create UOM
                    </h5>
                    <!--Close button-->
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Base Unit
                        </label>
                        <select type="text" placeholder="Unit" v-model="baseUnit" class="input-ui">
                            <option v-for='(uom, index) in uomList' :key=index :value=uom.id>{{ uom.name }}</option>
                        </select>

                    </div>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Conversion Unit
                        </label>
                        <select type="text" placeholder="Unit" v-model="conversionUnit" class="input-ui">
                            <option v-for='(uom, index) in uomList' :key=index :value=uom.id>{{ uom.name }}</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Conversion Rate
                        </label>
                        <input type="number" placeholder="Conversion rate" v-model="conversionRate" class="input-ui">
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                        @click="confirmCreateBtnClicked" data-te-modal-dismiss>
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="uom"
        tabindex="-1" aria-labelledby="uomLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <!--Modal title-->
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create UOM
                    </h5>
                    <!--Close button-->
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Uom Name
                        </label>
                        <input type="text" placeholder="Uom" v-model="uom_name" class="input-ui">
                    </div>

                </div>

                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 " @click="confirmUomCreate"
                        data-te-modal-dismiss>
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="edit_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative  p-4">
                    <!--Modal title-->
                    <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                        Edit UOM
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
                            Base Unit
                        </label>
                        <select type="text" placeholder="Unit" v-model="baseUnitedit"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            <option v-for='(uom, index) in uomList' :key=index :value=uom.id>{{ uom.name }}</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Conversion Unit
                        </label>
                        <select type="text" placeholder="Unit" v-model="conversionUnitedit"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            <option v-for='(uom, index) in uomList' :key=index :value=uom.id>{{ uom.name }}</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Conversion Rate
                        </label>
                        <input type="number" placeholder="Conversion rate" v-model="conversionRate"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-center px-12 mb-6">
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                        @click="confirmEditBtnClicked" data-te-modal-dismiss>
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, putApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            uomConversionList: [],

            baseUnit: null,
            conversionUnit: null,
            conversionRate: null,

            baseUnitId: null,
            conversionUnitId: null,

            uom_name: null,
            uomList: [],

            baseUnitedit: null,
            conversionUnitedit: null,
            editUomConversionId: null,


            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getUomConversionList(pageNumber) {
            let url = `/api/uom_conversions?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.uomConversionList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        async getUom() {
            console.log('it working');
            let url = '/api/uoms';
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success == true) {
                this.uomList = response.data;
            }
        },

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        async confirmUomCreate() {
            if (!this.uom_name) {
                this.alertValiationMessage('Uom name');
                return 1;
            }

            let url = `/api/uoms`
            let formData = new FormData();
            formData.append('name', this.uom_name);

            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success == true) {
                this.uom_name = "";
                this.getUom();
            }

        },

        async confirmCreateBtnClicked() {
            if (!this.baseUnit) {
                this.alertValiationMessage(`base unit`);
                return 1;
            }
            if (!this.conversionUnit) {
                this.alertValiationMessage(`conversion unit`);
                return 1;
            }
            if (!this.conversionRate) {
                this.alertValiationMessage(`conversion rate`);
                return 1;
            }
            if (this.baseUnit == this.conversionUnit) {
                this.$notify({
                    title: `Input validation`,
                    text: `Please choose different units for conversion. The base unit and conversion unit cannot be the same.`,
                    type: "warn"
                });
                return false;
            }
            let url = `/api/uom_conversions`;
            let formData = new FormData();
            formData.append('base_unit_id', this.baseUnit);
            formData.append('conversion_unit_id', this.conversionUnit);
            formData.append('conversion', this.conversionRate);

            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getUomConversionList(1);
                this.baseUnit = null;
                this.conversionUnit = null;
                this.conversionRate = null;
                // window.location.reload();
            }
        },

        editBtnClicked(uom) {
            this.editUomConversionId = uom.id;
            this.baseUnitedit = uom.base_unit_id;
            this.conversionUnitedit = uom.conversion_unit_id;
            this.conversionRate = uom.conversion;
        },

        async confirmEditBtnClicked() {


            let url = `/api/uom_conversions/${this.editUomConversionId}`;
            if (this.baseUnitedit == this.conversionUnitedit) {
                this.$notify({
                    title: `Input validation`,
                    text: `Please choose different units for conversion. The base unit and conversion unit cannot be the same.`,
                    type: "warn"
                });
                return false;
            }
            let formData = new FormData();
            formData.append('base_unit_id', this.baseUnitedit);
            formData.append('conversion_unit_id', this.conversionUnitedit);
            formData.append('conversion', this.conversionRate);

            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getUomConversionList(1);
                this.baseUnitedit = null;
                this.conversionUnitedit = null;
                this.conversionRate = null;

            }
        },


        clearSearchBtnClicked() {
            this.searchInput = null;
            this.searchCategory = null;
            this.getUomConversionList(1);
        },

    },

    created() {
        this.getUomConversionList(1);
        this.getUom();

    },

    mounted() {
        initTE({ Modal, Ripple, Select, Dropdown });
    }
}
</script>
