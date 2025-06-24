<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Create Package
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 gap-y-4 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Package Name
                </label>
                <input type="text" class="input-ui" v-model="name" placeholder="Package Name">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Price
                </label>
                <input type="number" class="input-ui" v-model="price" placeholder="Package Price">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    From
                </label>
                <div class="relative">
                    <input type="date" class="input-ui" :min="today" v-model="startDate">
                </div>
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    To
                </label>
                <div class="relative">
                    <input type="date" class="input-ui" :min="today" v-model="endDate">
                </div>
            </div>

            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Image
                </label>
                <input type="file" class="input-ui" @change="handleFileChange" accept="image/png, image/gif, image/jpeg"
                    ref="image">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label class="label-form mb-3">Package Type </label>
                <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" @change="selectedPackageTypeChanged"
                        data-te-select-filter="true" name="" id="" v-model="selectedPackageType" class="input-ui">
                        <option value="ktv"> KTV </option>
                        <option value="restaurant"> Restaurant </option>
                        <option value="event"> Event </option>
                    </select>
                </div>
            </div>
            <div class="mb-3 col-span-3 rounded-md" v-if="selectedPackageType === 'event'">
                <label for="" class="label-form mb-3">
                    Event Date
                </label>
                <div class="relative">
                    <input type="date" class="input-ui" :min="today" v-model="eventdate">
                </div>
            </div>
            <div class="col-span-6" v-if="selectedPackageType != 'event'">
            </div>
            <div class="col-span-3" v-else></div>

            <!-- <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Sessions
                </label>
                <input type="number" class="input-ui" :disabled="!isKTVPackage" v-model="sessionDuration" placeholder="Discount Sessions" >
            </div> -->

            <div class="contents" v-show="selectedPackageType === 'ktv'">
                <div class="mb-3 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Paid Sessions
                    </label>
                    <input type="number" class="input-ui" :disabled="!isKTVPackage" v-model="paySession"
                        placeholder="Paid Sessions">
                </div>

                <div class="mb-3 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Free Sessions
                    </label>
                    <input type="number" class="input-ui" :disabled="!isKTVPackage" v-model="freeSession"
                        placeholder="Free Sessions">
                </div>
                <div class="col-span-6"></div>
            </div>
            
            <!-- <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Session Price
                </label>
                <input type="number" class="input-ui" :disabled="!isKTVPackage" v-model="sessionPrice"
                    placeholder="Session Price">
            </div> -->
            
            <div class="mb-0 col-span-3 rounded-md">
                <label class="label-form mb-3"> Room </label>
                <multiselect v-model="selectedRoom" :options="roomList" :close-on-select="false"
                    :multiple="true"
                    :clear-on-select="false" :preserve-search="true" placeholder="Select Room" label="name"
                    track-by="id" :preselect-first="true">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        Room selected</span>
                    </template>
                </multiselect>
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label class="label-form mb-3"> Type </label>
                <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" @change="selectedTypeChanged"
                        data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                        <option value="menu"> Menu </option>
                        <option value="accessory"> Accessory </option>
                    </select>
                </div>
            </div>
            <!-- <div class="mb-3 col-span-3 rounded-md">
                <div class="block ps-[1.5rem]">
                    <label for="" class="label-from mb-3 block relative">&nbsp;</label>
                    <input
                        class="relative float-left -ms-[1.5rem] me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem]
                        appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-secondary-500 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-checkbox before:shadow-transparent before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ms-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-black/60 focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-checkbox checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ms-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent rtl:float-right dark:border-neutral-400 dark:checked:border-primary dark:checked:bg-primary"
                        type="checkbox" v-model="isKTVPackage" id="checkboxDefault" />
                    <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="checkboxDefault">
                        Package for KTV
                    </label>
                </div>
            </div> -->

            <div class="mb-3 col-span-3 rounded-md">
                <div class="block ps-[1.5rem]">
                    <label for="" class="label-from mb-3 block relative">&nbsp;</label>
                    <input
                        class="relative float-left -ms-[1.5rem] me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem]
                        appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-secondary-500 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-checkbox before:shadow-transparent before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ms-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-black/60 focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-checkbox checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ms-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent rtl:float-right dark:border-neutral-400 dark:checked:border-primary dark:checked:bg-primary"
                        type="checkbox" v-model="isChangeable" id="checkboxDefault2" />
                    <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="checkboxDefault">
                        Can changeable?
                    </label>
                </div>
            </div><div class="col-span-3"></div>
            <div v-show="selectedType == 'menu'" class="contents">
                <div class="mb-0 col-span-3 rounded-md" v-show="selectedType == 'menu'">
                    <div>
                        <label class="label-form mb-3"> Menu Category </label>
                        <multiselect v-model="selectedMenuCategory" :options="menuCategoryList" :close-on-select="true"
                            :clear-on-select="false" :preserve-search="true" placeholder="Select Category" label="name"
                            track-by="id" :preselect-first="true" @select="menuCategorySelectChanged()"></multiselect>
                    </div>
                </div>

                <div class="mb-0 col-span-3 rounded-md">
                    <div>
                        <label class="label-form mb-3"> Menu </label>
                        <multiselect v-model="selectedMenu" :options="menuList" :close-on-select="true"
                            :clear-on-select="false" :preserve-search="true" placeholder="Select Category" label="name"
                            track-by="id" :preselect-first="true"></multiselect>
                    </div>
                </div>

                <div class="mb-3 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Package Quantity
                    </label>
                    <input type="number" class="input-ui" v-model="menuQty" placeholder="Menu Package Qty" min="0">
                </div>

                <div class="col-span-3">
                    <label for="" class="label-form mb-3">
                        &nbsp;
                    </label>
                    <button class="add-btn py-2.5" @click="addMenuBtnClicked">
                        Add Menu
                    </button>
                </div>
            </div>
            <div v-show="selectedType == 'accessory'" class="contents">
                <div class="mb-0 col-span-3 rounded-md">
                    <div>
                        <label class="label-form mb-3"> Accessory Category </label>
                        <multiselect v-model="selectedAccessoryCategory" :options="accessoryCategoryList" :close-on-select="true"
                            :clear-on-select="false" :preserve-search="true" placeholder="Select Category" label="name"
                            track-by="id" :preselect-first="true" @select="selectedAccessoryCategoryChange()"></multiselect>
                    </div>
                </div>

                <div class="mb-0 col-span-3 rounded-md">
                    <div>
                        <label class="label-form mb-3"> Accessory </label>
                        <multiselect v-model="selectedAccessory" :options="accessoryList" :close-on-select="true"
                            :clear-on-select="false" :preserve-search="true" placeholder="Select Accessory" label="name"
                            track-by="id" :preselect-first="true"></multiselect>
                    </div>
                </div>

                <div class="mb-3 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Accessory Quantity
                    </label>
                    <input type="number" class="input-ui" v-model="accessoryQuantity" placeholder="Accessory Qty" min="0">
                </div>

                <div class="col-span-12 flex justify-end pt-8">
                    <button class="add-btn" @click="addAccessoryBtnClicked">
                        Add Accessory
                    </button>
                </div>
            </div>

        </div>


        <div class=" ">
            <ul class="mb-0 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                <li role="presentation" class="group">
                    <button id="menu_tab_btn"
                        class="z-20 mt-2 block px-7 pb-3.5 pt-3 rounded-tr-md rounded-tl-md font-inter text-xs font-medium  leading-tight text-neutral-500  focus:isolate data-[te-nav-active]:text-[#fff] data-[te-nav-active]:bg-[#845adf]  bg-white custom-shadow-tab relative"
                        data-te-toggle="pill" data-te-target="#tabs-menu" role="tab" data-te-nav-active
                        aria-controls="tabs-menu" aria-selected="true">Menus</button>
                </li>
                <li role="presentation" class="-ml-0.5 group">
                    <!-- <a href="#tabs-material" @click="getRawMaterialList()"
                        class="z-[19] mt-2 block px-7 pb-3.5 pt-3 rounded-tr-md rounded-tl-md font-inter text-xs font-medium  leading-tight text-neutral-500  focus:isolate data-[te-nav-active]:text-[#fff] data-[te-nav-active]:bg-[#845adf] bg-white custom-shadow-tab relative"
                        data-te-toggle="pill" data-te-target="#tabs-material" role="tab"
                        aria-controls="tabs-material" aria-selected="true">Raw Material</a> -->
                    <button
                        class="z-20 mt-2 block px-7 pb-3.5 pt-3 rounded-tr-md rounded-tl-md font-inter text-xs font-medium  leading-tight text-neutral-500  focus:isolate data-[te-nav-active]:text-[#fff] data-[te-nav-active]:bg-[#845adf]  bg-white custom-shadow-tab relative"
                        data-te-toggle="pill" data-te-target="#tabs-accessory" role="tab" id="accessory_tab_btn"
                        aria-controls="tabs-accessory" aria-selected="true">Accessories</button>
                </li>
            </ul>

            <div class="bg-white py-4 px-8 rounded-tr-md rounded-bl-md rounded-br-md shadow-md mb-8 -mt-0.5 z-30 relative">
                <div class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                    id="tabs-menu" role="tabpanel" aria-labelledby="tabs-menu-tab" data-te-tab-active>
                    <div class="table-container">
                        <table class="primary-table">
                            <thead class="">
                                <tr>
                                    <th scope="col" class=" text-left ">
                                        Menu Name
                                    </th>
                                    <th scope="col" class="  ">
                                        Qty
                                    </th>
                                    <th scope="col" class="  ">
        
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="contents" v-for="(promotionMenu, menuIndex) in selectedMenus" :key="menuIndex">
                                    <tr class="">
                                        <td class="text-left">
                                            {{ promotionMenu.name }}
                                        </td>
                                        <td class="  ">
                                            {{ promotionMenu.quantity }}
                                        </td>
                                        <td class="  ">
                                            <button @click="removePackageMenuBtnClicked(menuIndex)">
                                                <i class="fal fa-trash  pr-3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                    id="tabs-accessory" role="tabpanel" aria-labelledby="tabs-accessory-tab">
                    <div class="table-container">
                        <table class="primary-table">
                            <thead class="">
                                <tr>
                                    <th scope="col" class=" text-left ">
                                        Accessory Name
                                    </th>
                                    <th scope="col" class="  ">
                                        Qty
                                    </th>
                                    <th scope="col" class="  ">
        
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="contents" v-for="(accessory, accessoryIndex) in selectedAccessoryList" :key="accessoryIndex">
                                    <tr class="">
                                        <td class="text-left">
                                            {{ accessory.name }}
                                        </td>
                                        <td class="  ">
                                            {{ accessory.quantity }}
                                        </td>
                                        <td class="  ">
                                            <button @click="removeAccessoryBtnClicked(accessoryIndex)">
                                                <i class="fal fa-trash  pr-3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>



        <!-- <div class=" bg-white py-4 px-4 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class=" text-left ">
                                Menu Name
                            </th>
                            <th scope="col" class="  ">
                                Qty
                            </th>
                            <th scope="col" class="  ">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(promotionMenu, menuIndex) in selectedMenus" :key="menuIndex">
                            <tr class="">
                                <td class="text-left">
                                    {{ promotionMenu.name }}
                                </td>
                                <td class="  ">
                                    {{ promotionMenu.quantity }}
                                </td>
                                <td class="  ">
                                    <button @click="removePackageMenuBtnClicked(menuIndex)">
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                        </div>
                    </tbody>
                </table>
            </div>
        </div> -->

        <div>
            <button class="add-btn" @click="createBtnClicked">
                Create Package
            </button>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Tab, Select } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { getCurrentDate, getFirstDate } from "../../utilities/datetime-helpers";
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            today: getCurrentDate(),

            // isKTVPackage: false,
            isChangeable: false,

            menuCategoryList: [],
            selectedMenuCategory: null,
            menuList: [],
            selectedMenu: null,
            selectedMenus: [],
            roomList:[],

            selectedPackageType: null,
            eventdate: null,
            sessionDuration: null,
            paySession: null,
            freeSession: null,
            sessionPrice: null,
            selectedRoom:null,

            name: null,
            price: null,
            startDate: null,
            endDate: null,

            menuQty: 0,
            selectedType:null,

            accessoryCategoryList:[],
            accessoryList:[],

            selectedAccessoryCategory:null,
            selectedAccessory:null,
            accessoryQuantity:0,

            selectedAccessoryList:[],

            selectedImage: null,
        };
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

        async getRoomList(){
            const response = await getApiData({ url: '/api/entities?type=room' , token: this.getToken()});
            if(response.data){
                this.roomList = response.data;
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

        handleFileChange(event) {
            const selectedFile = event.target.files[0];
            this.selectedImage = selectedFile;
        },

        addMenuBtnClicked() {
            if (!this.selectedMenu) {
                this.alertValiationMessage(`package menu`);
                return 1;
            }
            if (this.menuQty < 1) {
                this.alertValiationMessage(`package menu quantity`);
                return 1;
            }
            this.selectedMenus.push({
                id: this.selectedMenu.id,
                name: this.selectedMenu.name,
                quantity: this.menuQty
            });
            if(this.selectedMenuCategory && this.selectedMenu && this.menuQty > 0){
                document.getElementById('menu_tab_btn').click();
            }
            this.selectedMenuCategory = null;
            this.selectedMenu = null;
            this.menuQty = 0;
            this.menuPrice = null;
        },

        removePackageMenuBtnClicked(menuIndex) {
            this.selectedMenus.splice(menuIndex, 1);
        },

        async getAccessoryCategoryList() {
            const response = await getApiData({ url: '/api/get_accessory_category', token: this.getToken() });
            if (response.data) {
                this.accessoryCategoryList = response.data;
            }
        },
        async selectedAccessoryCategoryChange(){ //get accessory list
            const response = await getApiData({ url: `/api/get_accessory_by_category/${this.selectedAccessoryCategory.id}`, token: this.getToken() });
            if (response.data) {
                this.accessoryList = response.data;
            }
        },
        addAccessoryBtnClicked() {
            if (!this.selectedAccessory) {
                this.alertValiationMessage(`Accessory`);
                return 1;
            }
            if (this.accessoryQuantity < 1) {
                this.alertValiationMessage(`Accessory quantity`);
                return 1;
            }
            this.selectedAccessoryList.push({
                id: this.selectedAccessory.id,
                name: this.selectedAccessory.name,
                quantity: this.accessoryQuantity
            });
            if(this.selectedAccessoryCategory && this.selectedAccessory && this.accessoryQuantity > 0){
                document.getElementById('accessory_tab_btn').click();
            }
            this.selectedAccessoryCategory = null;
            this.selectedAccessory = null;
            this.accessoryQuantity = null;
        },

        removeAccessoryBtnClicked(accIndex) {
            this.selectedAccessoryList.splice(accIndex, 1);
        },


        async createBtnClicked() {
            let formData = new FormData();
            if (!this.name) {
                this.alertValiationMessage(`package name`);
                return 1;
            }
            if (!this.selectedPackageType) {
                this.alertValiationMessage(`package Type`);
                return 1;
            }
            if (!this.startDate) {
                this.alertValiationMessage(`from date`);
                return 1;
            }
            if (!this.endDate) {
                this.alertValiationMessage(`to date`);
                return 1;
            }
            if (this.selectedPackageType === 'event' && !this.eventdate) {
                this.alertValiationMessage(`Event date`);
                return 1;
            }
            if (!this.price) {
                this.alertValiationMessage(`package price`);
                return 1;
            }
            if (this.selectedMenus.length < 1 && this.selectedAccessoryList.length < 1) {
                this.alertValiationMessage(`package menus`);
                return 1;
            }
            if (!this.selectedImage) {
                this.alertValiationMessage(`package image`);
                return 1;
            }

            formData.append('name', this.name);
            formData.append('type', this.selectedPackageType);
            if (this.selectedPackageType === 'event') {
                formData.append('event_date', this.eventdate);
            }
            formData.append('from_date', this.startDate);
            formData.append('to_date', this.endDate);
            formData.append('price', this.price);
            // formData.append('is_ktv', (this.isKTVPackage) ? 1 : 0);
            formData.append('is_changeable', (this.isChangeable) ? 1 : 0);
            formData.append('image', this.selectedImage);
            if (this.selectedPackageType === 'ktv') {
                if (!this.paySession) {
                    this.alertValiationMessage(`paid session`);
                    return 1;
                }
                if (!this.freeSession) {
                    this.alertValiationMessage(`free sessions`);
                    return 1;
                }
                formData.append('pay_session', this.paySession);
                formData.append('free_session', this.freeSession);
            }

            let menuIds = [];
            this.selectedMenus.forEach((menu) => {
                menuIds.push({ menu_id: menu.id, quantity: menu.quantity });
            });
            formData.append('menuIds', JSON.stringify(menuIds));
            let accessoryIds = [];
            this.selectedAccessoryList.forEach((accessory) => {
                accessoryIds.push({ accessory_id: accessory.id, quantity: accessory.quantity });
            });

            formData.append('accessories', JSON.stringify(accessoryIds));
            let room_ids = [];
            this.selectedRoom.forEach((room) => {
                room_ids.push(room.id)
                formData.append('rooms[]', room.id);
            });
            // formData.append('rooms', room_ids);

            let url = `/api/packages`;
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Package created successfully`,
                    type: "info"
                });

                window.location.replace('/packages');
            }
            else {
                this.$notify({
                    title: `Package Create Failed`,
                    text: response.message,
                    type: "warn"
                });
            }
        },
    },

    created() {
        this.getRoomList();
        this.getMenuCategoryList();
        this.getAccessoryCategoryList();
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple, Input });
    }
}
</script>
