<template>
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Selling Extras
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex gap-x-4 ">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search">
                        <i class="fal fa-search"></i>
                    </label>
                </div>
                <div class="flex justify-end gap-x-4">
                    <button class="add-btn text-[13px] font-inter
                    transition duration-150 ease-in-out focus:outline-none focus:ring-0" data-te-toggle="modal"
                        data-te-target="#create_modal">
                        Add New
                    </button>

                    <button class="add-btn text-[13px] font-inter
                    transition duration-150 ease-in-out focus:outline-none focus:ring-0" data-te-toggle="modal"
                        data-te-target="#create_category_modal">
                        Add Category
                    </button>
                </div>
            </div>
            
        </div>
        <div class="box-container-table mb-4">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>UOM</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />

                        <tr class=" !text-center" v-else-if="sellingExtras.length < 1">
                            <td class="" colspan="5">
                                No Data Here
                            </td>
                        </tr>

                        <tbody v-else>
                            <div class="contents" v-for="(sellingExtra, index) in sellingExtras" :key="index">
                                <tr class="">
                                    <td class="">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="">
                                        {{ sellingExtra.item.name }}
                                    </td>
                                    <td class="">
                                        {{ sellingExtra.category.name }}
                                    </td>
                                    <td class="">
                                        {{ sellingExtra.uom.name }}
                                    </td>
                                    <td class="">
                                        {{ sellingExtra.price }}
                                    </td>
                                    <td class="">
                                        {{ sellingExtra.quantity }}
                                    </td>
                                    <td class="">
                                        <button type="button" class="pr-3"
                                            data-te-toggle="modal" data-te-target="#update_modal"
                                            @click="editBtnClicked(sellingExtra, index)">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="sellingExtras.length < 1 && !loading">
                                <td class="" colspan="7">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                        Create Selling Extra
                    </h5>
                    <!--Close button-->
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close" id="close_create_modal">
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
                            Category
                        </label>
                        <multiselect
                        v-model="selectedCategory"
                        :options="categories"
                        :close-on-select="true"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select Category"
                        label="name"
                        track-by="id"
                        :preselect-first="false" ></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Extra Item
                        </label>
                        <multiselect
                        v-model="selectedItem"
                        :options="itemList"
                        :close-on-select="true"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select Extra Item"
                        label="name"
                        track-by="id"
                        @select="itemSelectChanged"
                        :preselect-first="false" ></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            UOM
                        </label>
                        <multiselect
                        v-model="selectedUom"
                        :options="uomList"
                        :close-on-select="true"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select UOM"
                        label="name"
                        track-by="id"
                        :preselect-first="false" ></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Quantity
                        </label>
                        <input type="number" placeholder="Qty" v-model="quantity" class="input-ui">
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Price
                        </label>
                        <input type="number" placeholder="Price" v-model="price" class="input-ui">
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <!-- <button type="button" @click="createBtnClicked"
                        class="add-btn focus:outline-none focus:ring-0 " data-te-toggle="modal"
                        data-te-target="#create_modal">
                        Create
                    </button> -->
                    <LoadingButton
                        :loading="buttonLoading"
                        text="Create"
                        loadingText="Creating..."
                        @click="createBtnClicked"
                    />
                </div>
            </div>
        </div>
    </div>

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="update_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <!--Modal title-->
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Update Selling Extra
                    </h5>
                    <!--Close button-->
                    <button type="button" id="close_edit_modal" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
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
                            Category
                        </label>
                        <multiselect
                        v-model="selectedCategory"
                        :options="categories"
                        :close-on-select="true"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select Category"
                        label="name"
                        track-by="id"
                        :preselect-first="false" ></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Extra Item
                        </label>
                        <multiselect
                        v-model="selectedItem"
                        :options="itemList"
                        :close-on-select="true"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select Extra Item"
                        label="name"
                        track-by="id"
                        @select="itemSelectChanged"
                        :preselect-first="false" ></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            UOM
                        </label>
                        <multiselect
                        v-model="selectedUom"
                        :options="uomList"
                        :close-on-select="true"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select UOM"
                        label="name"
                        track-by="id"
                        :preselect-first="false" ></multiselect>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Quantity
                        </label>
                        <input type="number" placeholder="Qty" v-model="quantity" class="input-ui">
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Price
                        </label>
                        <input type="number" placeholder="Price" v-model="price" class="input-ui">
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="confirmEditBtnClicked"
                        class="add-btn focus:outline-none focus:ring-0 " data-te-toggle="modal"
                        data-te-target="#update_modal">
                        Update
                    </button>
                    <LoadingButton
                        :loading="buttonLoading"
                        text="Update"
                        loadingText="Updating..."
                        @click="confirmEditBtnClicked"
                    />
                </div>
            </div>
        </div>
    </div>

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="create_category_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <!--Modal title-->
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create Selling Extra Category
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
                            Name
                        </label>
                        <input type="text" placeholder="Category Name" v-model="categoryName" class="input-ui">
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="createCategoryBtnClicked"
                        class="add-btn focus:outline-none focus:ring-0 " data-te-toggle="modal"
                        data-te-target="#create_category_modal">
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>
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
        LoadingButton,
    },
    data() {
        return {
            categories: [],
            selectedCategory: null,

            sellingExtras: [],

            tagList: [],
            selectedTag: null,

            itemList: [],
            selectedItem: null,

            uomList: [],
            selectedUom: null,

            quantity: 0,
            price: 0,

            creatable: true,

            editId: null,
            editIndex: null,

            categoryName: null,

            loading: true,
            buttonLoading: false,
        }
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

        showToastMessage(message, type = "warn", title = "Input Validation") {
            this.$notify({
                title: `${title}`,
                text: `${message}`,
                type: `${type}`
            });
        },

        getCategories() {
            getApiData({ url: `/api/selling_extra_categories`, token: this.getToken() }).then((response) => {
                if (response.data) {
                    this.categories = response.data;
                }
            });
        },

        getExtraTaggedItems() {
            getApiData({ url: `/api/tags`, token: this.getToken() }).then((response) => {
                if (response.data) {
                    response.data.forEach(tag => {
                        if(tag.name == 'Extra'){
                            this.selectedTag = tag;
                            getApiData({url: `/api/items?tag_id=${this.selectedTag.id}`, token: this.getToken()}).then((response)=>{
                                if(response.data){
                                    this.itemList = response.data;
                                }
                            });
                        }
                    });
                }
            });
        },

        itemSelectChanged(){
            this.uomList = [];
            this.uomList.push(this.selectedItem.uom);
            this.uomList.push(this.selectedItem.base_uom);
        },

        createBtnClicked(){
            this.creatable = true;
            if(!this.selectedCategory){
                this.alertValiationMessage(`category`);
                this.creatable = false;
                return;
            }

            if(!this.selectedItem){
                this.alertValiationMessage(`extra item`);
                this.creatable = false;
                return;
            }

            if(!this.selectedUom){
                this.alertValiationMessage(`uom`);
                this.creatable = false;
                return;
            }

            if(!this.quantity){
                this.alertValiationMessage(`quanity`);
                this.creatable = false;
                return;
            }

            if(!this.price){
                this.alertValiationMessage(`price`);
                this.creatable = false;
                return;
            }

            if(this.creatable){
                this.buttonLoading = true;
                let formData = new FormData();
                formData.append('selling_extra_category_id',this.selectedCategory.id);
                formData.append('item_id',this.selectedItem.id);
                formData.append('uom_id',this.selectedUom.id);
                formData.append('quantity',this.quantity);
                formData.append('price',this.price);
                let url = `/api/selling_extras`;
                if(this.editId){
                    url = `${url}/${this.editId}`;
                }
                postApiData({url: url, form_data: formData, token: this.getToken()})
                .then((response)=>{
                    if(response.success){
                        this.selectedCategory = null;
                        this.selectedItem = null;
                        this.selectedUom = null;
                        this.quantity = 0;
                        this.price = 0;
                        this.uomList = [];
                        this.editId = null;
                        console.log('index', this.editIndex);
                        if(this.editIndex == 0 || this.editIndex > 0){
                            this.sellingExtras[this.editIndex] = response.data;
                            this.editIndex = null;
                        }else{
                            this.sellingExtras.push(response.data);
                            // this.getSellingExtras();
                        }
                        setTimeout(() => {
                            this.buttonLoading = false
                        }, 500)
                    }
                });
            }
        },

        editBtnClicked(sellingExtra, index){
            this.editId = sellingExtra.id;
            this.selectedCategory = sellingExtra.category;
            this.selectedItem = sellingExtra.item;
            this.uomList = [];
            this.uomList.push(this.selectedItem.uom);
            this.uomList.push(this.selectedItem.base_uom);
            this.selectedUom = sellingExtra.uom;
            this.quantity = sellingExtra.quantity;
            this.price = sellingExtra.price;
            this.editIndex = index;
        },

        confirmEditBtnClicked(){
            this.createBtnClicked();
        },

        createCategoryBtnClicked(){
            if(!this.categoryName){
                this.alertValiationMessage('category name');
                return;
            }
            let formData = new FormData();
            formData.append('name', this.categoryName);
            postApiData({url: '/api/selling_extra_categories', form_data: formData, token: this.getToken()})
            .then((response)=>{
                if(response.data){
                    this.categories.push(response.data);
                    this.selectedCategory = response.data;
                    this.showToastMessage(`${this.categoryName} category created`,'success','Success');
                    this.categoryName = null;
                }
            });
        },

        getSellingExtras(){
            this.loading = true;
            getApiData({url: `/api/selling_extras`, token: this.getToken()}).then((response)=>{
                if(response.data){
                    this.loading = false;
                    this.sellingExtras = response.data;
                }
            });
        },
    },

    created(){
        this.getSellingExtras();
        this.getExtraTaggedItems();
        this.getCategories();
    },

    mounted(){
        initTE({ Modal, Ripple, Select, Dropdown });
    },
}
</script>
