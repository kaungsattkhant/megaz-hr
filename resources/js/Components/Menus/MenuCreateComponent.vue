<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                Add Menu
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Name
                </label>
                <input type="text" v-model="name"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Price
                </label>
                <input type="text" v-model="price"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Menu Category
                </label>
                <div class="text-sm input-ui w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="menuCategoryId"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        <option :value="menuCategory.id" v-for="(menuCategory, menuCategoryIndex) in menuCategoryList"
                            :key="menuCategoryIndex"> {{ menuCategory.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Images
                </label>
                <div class="text-sm input-ui w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <input type='file' @change="handleFileChange" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0" />
                </div>

            </div>
            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Category
                </label>
                <div class="text-sm input-ui w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedItemCategory"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                        @change="itemCategorySelectChanged">
                        <option :value="itemCategory" v-for="(itemCategory, itemCategoryIndex) in itemCategoryList"
                            :key="itemCategoryIndex"> {{ itemCategory.name }} </option>
                    </select>
                </div>
            </div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Ingredients
                </label>
                <div class="text-sm input-ui w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Item"
                        data-te-select-filter="true" name="" id="" v-model="selectedItem"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex"> {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Weight
                </label>
                <input type="text" v-model="weight" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                    <input
                        class="relative float-left -ml-[1.5rem] mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                        type="checkbox" value="true" v-model="isMakePack" id="checkboxDefault" ref="is_make_pack"
                        @change="isMakePackCheckChanged" />
                    <label class="inline-block pl-[0.15rem] hover:cursor-pointer" for="checkboxDefault">
                        Is make pack?
                    </label>
                </div>
            </div>
            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    UOM
                </label>
                <div class="text-sm input-ui w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select UOM"
                        data-te-select-filter="true" name="" id="" v-model="selectedUom"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        <option :value="uom" v-for="(uom, uomIndex) in uomList" :key="uomIndex">
                            {{ uom.name }}
                        </option>
                    </select>
                </div>

            </div>

            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    &nbsp;
                </label>
                <button class="add-btn" @click="addItemBtnClicked">
                    Add Item
                </button>
            </div>

        </div>

        <div class=" bg-white p-8 rounded-md shadow-md mb-8">
            <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                <li role="presentation">
                    <a href="#tabs-ingredients"
                        class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"
                        data-te-toggle="pill" data-te-target="#tabs-home" data-te-nav-active role="tab"
                        aria-controls="tabs-home" aria-selected="true">Ingredients</a>
                </li>
                <li role="presentation">
                    <a href="#tabs-profile"
                        class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"
                        data-te-toggle="pill" data-te-target="#tabs-profile" role="tab" aria-controls="tabs-profile"
                        aria-selected="false">Profile</a>
                </li>
                <li role="presentation">
                    <a href="#tabs-messages"
                        class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"
                        data-te-toggle="pill" data-te-target="#tabs-messages" role="tab" aria-controls="tabs-messages"
                        aria-selected="false">Messages</a>
                </li>
                <li role="presentation">
                    <a href="#tabs-contact"
                        class="disabled pointer-events-none my-2 block border-x-0 border-b-2 border-t-0 border-transparent bg-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-400 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent dark:text-neutral-600"
                        data-te-toggle="pill" data-te-target="#tabs-contact" role="tab" aria-controls="tabs-contact"
                        aria-selected="false">Contact</a>
                </li>
            </ul>

            <div class="mb-6">
                <div class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                    id="tabs-ingredients" role="tabpanel" aria-labelledby="tabs-home-tab" data-te-tab-active>
                    <div>
                        <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                            <thead class="border-b font-medium ">
                                <tr>
                                    <th scope="col" class=" px-6 py-4 ">
                                        Item
                                    </th>
                                    <th scope="col" class=" px-6 py-4 ">
                                        Weight
                                    </th>
                                    <th scope="col" class=" px-6 py-4 ">
                                        UOM
                                    </th>
                                    <th scope="col" class=" px-6 py-4 ">
                                        Is packed?
                                    </th>
                                    <th scope="col" class=" px-6 py-4 ">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white rounded-lg overflow-hidden shadow-lg"
                                    v-for="(ingredient, ingredientIndex) in ingredientItems" :key="ingredientIndex">
                                    <td class=" px-6 py-4 font-medium ">
                                        {{ ingredient.name }}
                                    </td>
                                    <td class=" px-6 py-4 font-medium ">
                                        {{ ingredient.weight }}
                                    </td>
                                    <td class=" px-6 py-4 font-medium ">
                                        {{ ingredient.uom_name }}
                                    </td>
                                    <td class=" px-6 py-4 font-medium ">
                                        {{ ingredient.is_make_pack }}
                                    </td>
                                    <td class=" px-6 py-4 font-medium ">
                                        <button @click="removeIngredientBtnClicked(ingredientIndex)">
                                            <i class="fal fa-trash  pr-3"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                    id="tabs-profile" role="tabpanel" aria-labelledby="tabs-profile-tab">
                    Tab 2 content
                </div>
                <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                    id="tabs-messages" role="tabpanel" aria-labelledby="tabs-profile-tab">
                    Tab 3 content
                </div>
                <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                    id="tabs-contact" role="tabpanel" aria-labelledby="tabs-contact-tab">
                    Tab 4 content
                </div>
            </div>

        </div>


        <div>
            <button class="add-btn" @click="createMenuBtnClicked">
                Create Menu
            </button>
        </div>


        <!-- Department Modal -->
        <!-- <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_department_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-12 py-4" data-te-modal-body-ref>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Department
                            </label>
                            <input type="text" placeholder="Department"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 ">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div> -->



        <!-- Role Modal -->
        <!-- <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_role_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Role
                            </label>
                            <input type="text" placeholder="Role"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Department
                            </label>
                            <input type="text" placeholder="Department"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 ">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Tab, Select } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            menuCategoryList: [],
            itemCategoryList: [],
            itemList: [],

            uomList: [],
            selectedUom: null,

            menuCategoryId: null,
            name: null,
            price: null,

            selectedItemCategory: null,
            selectedItem: null,

            weight: null,
            isMakePack: false,
            ingredientItems: [],

            selectedImage: null

        };
    },

    methods: {
        ...mapGetters(['getToken']),


        handleFileChange(event) {
            const selectedFile = event.target.files[0];
            this.selectedImage = selectedFile;

        },

        async getMenuCategoryList() {
            let response = await getApiData({ url: `/api/menu_categories`, token: this.getToken() });
            if (response.data) {
                this.menuCategoryList = response.data;
            }
        },

        async getItemCategoryList() {
            let response = await getApiData({ url: `/api/categories`, token: this.getToken() });
            if (response.data) {
                this.itemCategoryList = response.data;
            }
        },

        async itemCategorySelectChanged() {
            let response = await getApiData({ url: `/api/items?category_id=${this.selectedItemCategory.id}`, token: this.getToken() });
            if (response.data) {
                this.itemList = response.data;
            }
        },

        async getUomList() {
            let response = await getApiData({ url: `/api/uoms`, token: this.getToken() });
            if (response.data) {
                this.uomList = response.data;
            }
        },

        isMakePackCheckChanged() {
            this.isMakePack = this.$refs.is_make_pack.checked;
        },

        addItemBtnClicked() {
            if (!this.weight) {
                alert('You forgot to specify weight');
            }
            else if(!this.selectedUom){
                alert('You forgot to specify UOM');
            }
            else {
                this.ingredientItems.push({
                    id: this.selectedItem.id,
                    name: this.selectedItem.name,
                    weight: this.weight,
                    is_make_pack: this.isMakePack,
                    uom_id: this.selectedUom.id,
                    uom_name: this.selectedUom.name
                });
            }

            this.weight = null;
            this.isMakePack = false;
            this.$refs.is_make_pack.checked = false;
        },

        removeIngredientBtnClicked(ingredientIndex) {
            this.ingredientItems.splice(ingredientIndex, 1);
        },

        async createMenuBtnClicked() {
            if (!this.menuCategoryId || !this.name || !this.price || this.ingredientItems.length < 1 ||!this.selectedImage) {
                alert('Required data must be filled');
            }
            else {
                let menuItems = JSON.stringify({ items: this.ingredientItems });
                let formData = new FormData();
                formData.append('menu_category_id', this.menuCategoryId);
                formData.append('name', this.name);
                formData.append('price', this.price);
                formData.append('items', menuItems);
                formData.append('image',this.selectedImage);

                let response = await postApiData({ url: `/api/menus`, form_data: formData, token: this.getToken() });

                if (response.success) {
                    window.location.replace(`/menus`);
                }
            }
        }
    },

    created() {
        this.getMenuCategoryList();
        this.getItemCategoryList();
        this.getUomList();
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>
