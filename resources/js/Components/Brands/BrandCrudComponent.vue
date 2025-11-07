<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Brands
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex gap-x-4 ">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>

                    <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
                    <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button>

                </div>
                <div class="flex justify-end gap-x-4">
                    <label for="excel_import_supplier" class="add-btn h-8 cursor-pointer">
                        Import 
                        <input type="file" placeholder="Excel" id="excel_import_supplier" class="opacity-0 w-0 h-0 hidden"  @change="handleFileChange">
                    </label>
                    <button type="button" v-show="feature.includes('brand.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal" @click="createBtnClicked">
                        Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th v-show="feature.includes('brand.edit')"></th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <!-- looping start -->
                             <div class="contents" v-for="(brand, index) in brandsList" :key="index" >
                                <tr class="">
                                    <td class="">
                                        {{ perPage * (currentPage - 1) + (index + 1) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ brand.name }}
                                    </td>
                                    <td class="whitespace-nowrap" v-show="feature.includes('brand.edit')">
                                        <button id="edit-btn" class="pr-2" data-te-toggle="modal"
                                            data-te-target="#create_modal" @click="editBtnClicked(brand)">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                             </div>
                             <tr class=" !text-center" v-if="brandsList.length < 1 && !loading">
                                <td class="" colspan="3">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1"
                                @click="getBrandList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage"
                                @click="getBrandList(currentPage + 1)"> »</button>
                        </div>
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
                    <!--Modal title-->
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create / Update Brand
                    </h5>
                    <!--Close button-->
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close" id="closeModal">
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
                            Brand Name
                        </label>
                        <input type="text" placeholder="Brand Name" v-model="name" class="input-ui">
                    </div>
                    <div class="mb-4">
                        <label class="label-form mb-3">Items</label>
                        <multiselect
                        v-model="selectedItem"
                        :options="itemList"
                        :multiple="true"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select Items"
                        label="name"
                        track-by="id"
                        :preselect-first="false">
                            <template #selection="{ values, search, isOpen }">
                                <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                                    Item selected</span>
                            </template>
                        </multiselect>
                    </div>
                </div>
                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <LoadingButton
                        :loading="buttonLoading"
                        text="Confirm"
                        loadingText="Confirming..."
                        @click="createBrand"
                    />
                    <!-- <button type="button" class="add-btn focus:outline-none focus:ring-0 " @click="createBrand" >
                        Confirm
                    </button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="priceUpdateModal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Update Brand
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

                    <div class="mb-4" v-if="updatePriceItem">
                        <label for="" class="label-form mb-3">
                            Item Name
                        </label>
                        <input type="text" placeholder="Item Name" :value="updatePriceItem.name" disabled
                            class="input-ui">
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                        @click="confirmUpdatePriceBtnClicked">
                        Update Price
                    </button>
                </div>
            </div>
        </div>
    </div> -->
</template>

<script>
import { Modal, Ripple, initTE, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import TableSkeleton from "../Common/TableSkeleton.vue";
import LoadingButton from "../Common/LoadingButton.vue";

export default {
    components: {
        Multiselect,
        TableSkeleton,
        LoadingButton
    },
    data() {
        return {
            name: null,

            searchInput: null,
            searchCategory: null,

            isFirstGroup: true,
            isLastGroup: false,
            selectedBaseUom:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0,

            brandsList: [],
            selectedBrands: [],

            itemList:[],
            selectedItem:[],

            editId: null,

            selectedImportItem: null,
            feature: this.getFeature(),

            loading: true,
            buttonLoading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        async getBrandList(pageNumber) {
            this.loading = true;
            let url = `/api/brands?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.brandsList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
                this.loading = false;
            }
        },
        createBtnClicked(){
            this.name = null;
            this.editId = null;
            this.selectedItem = []
        },
        async createBrand() {
            this.buttonLoading = true;
            if (!this.name) {
                this.alertValiationMessage('required Brand Name');
                return false;
            }
            // if (!this.selectedItem) {
            //     this.alertValiationMessage('required Item');
            //     return false;
            // }
            let items = [];
            if(this.selectedItem.length > 0){
                this.selectedItem.forEach((item) => {
                    items.push(item.id)
                })
            }

            let url = `/api/brands`;
            let formData = new FormData();
            formData.append('name', this.name);
            if(this.selectedItem.length > 0){
                this.selectedItem.forEach((item)=>{
                    formData.append('items[]', item.id);
                });
            }
            if(this.editId){
                formData.append('id', this.editId);
            }
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                document.getElementById("closeModal").click();
                this.name = null;
                this.editId = null;
                this.getBrandList(1);
                setTimeout(() => {
                    this.buttonLoading = false
                }, 500)
            }
            else {
                this.buttonLoading = false;
                this.$notify({
                    text: message,
                    type: "error"
                });
            }
        },

        editBtnClicked(brand){
            this.name = brand.name;
            this.editId = brand.id;
            this.selectedItem = brand.items
        },

        isActiveToggled(id) {
            let index = this.itemList.findIndex(table => table.id == id);
            if (index != -1) {
                if (this.itemList[index].is_active == 1) {
                    this.itemList[index].is_active = 0;
                }
                else {
                    this.itemList[index].is_active = 1;
                }

                let url = `/api/is_active`;
                let formData = new FormData();
                formData.append('id', id);
                formData.append('type', 'brand');
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
                if(response.success){

                }
            }
        },

        async getItemList(){
            let url = `/api/items`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.itemList = response.data;
            }
        },

        handleFileChange(event) {
            console.log("Event object:", event);
            const selectedImportItem = event.target.files[0];
            this.selectedImportItem = selectedImportItem;
            if(this.selectedImportItem){
                this.importBrand();
            }
        },
        async importBrand() {
            let formData = new FormData();
            formData.append('sheet', this.selectedImportItem);
            let response = await postApiData({ url: '/api/brands/import', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Excel Imported successfully`,
                    type: "info"
                });
                this.selectedImportItem = null;
                this.getBrandList(1);
            }
            else {
                this.$notify({
                    text: `Excel Imported failed`,
                    type: "error"
                });
            }
        },
        async searchBtnClicked() {
            let url = null;
            if (this.searchInput && this.searchCategory) {
                url = `/api/items?search_input=${this.searchInput}&category_id=${this.searchCategory.id}&page=1`;
            }
            if (this.searchInput && !this.searchCategory) {
                url = `/api/items?search_input=${this.searchInput}&page=1`;
            }
            if ((!this.searchInput) && this.searchCategory) {
                url = `/api/items?category_id=${this.searchCategory.id}&page=1`;
            }
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.itemList = response.data.data;
            }
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.searchCategory = null;
            this.getBrandList(1);
        },
    },

    created() {
        this.getBrandList(1);
        this.getItemList();
    },

    mounted() {
        initTE({ Modal, Ripple, Select, Dropdown });
    }
}
</script>
