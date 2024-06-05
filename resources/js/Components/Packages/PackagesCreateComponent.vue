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
                <input type="date" class="input-ui" :min="today" v-model="startDate">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    To
                </label>
                <input type="date" class="input-ui" :min="today" v-model="endDate" >
            </div>

            <div class="mb-3 col-span-12 rounded-md">
                <!--Default checkbox-->
                <div class="mb-[0.125rem] block min-h-[1.5rem] ps-[1.5rem]">
                    <input
                        class="relative float-left -ms-[1.5rem] me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-secondary-500 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-checkbox before:shadow-transparent before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ms-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-black/60 focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-checkbox checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ms-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent rtl:float-right dark:border-neutral-400 dark:checked:border-primary dark:checked:bg-primary"
                        type="checkbox"
                        v-model="isKTVPackage"
                        id="checkboxDefault" />
                    <label
                        class="inline-block ps-[0.15rem] hover:cursor-pointer"
                        for="checkboxDefault">
                        Package for KTV
                    </label>
                </div>
            </div>

            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Sessions
                </label>
                <input type="text" class="input-ui" :disabled="!isKTVPackage" v-model="sessionDuration" placeholder="Discount Sessions" >
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Rooms
                </label>
                <multiselect v-model="selectedRooms" :options="roomList" :multiple="true"
                :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                placeholder="Select Rooms" label="name" track-by="id" :preselect-first="true">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">
                            {{ values.length }} rooms selected
                        </span>
                    </template>
                </multiselect>
                <div class="flex gap-x-2 flex-wrap mt-1">
                    <span class="font-inter after-coma" v-for="selectedRoom in selectedRooms">{{ selectedRoom.name }}</span>
                </div>
            </div>

            <div class="col-span-6"> </div>

            <div class="mb-0 col-span-3 rounded-md">
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
                <input type="text" class="input-ui" v-model="menuQty" placeholder="Menu Package Qty">
            </div>

            <div class="col-span-12 flex justify-end pt-8">
                <button class="add-btn" @click="addMenuBtnClicked">
                    Add Menu
                </button>
            </div>

        </div>

        <div class=" bg-white py-4 px-4 rounded-md shadow-md mb-8">
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
                        <div class="contents" v-for="(promotionMenu, menuIndex) in selectedMenus" :key="menuIndex" >
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

        <div>
            <button class="add-btn" @click="createBtnClicked" >
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

                isKTVPackage: false,

                menuCategoryList: [],
                selectedMenuCategory: null,
                menuList: [],
                selectedMenu: null,
                selectedMenus: [],

                roomList: [],
                sessionDuration: null,
                selectedRooms: [],

                name: null,
                price: null,
                startDate: null,
                endDate: null,

                menuQty: null,
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

            async getMenuCategoryList(){
                let url = `/api/menu_categories`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.menuCategoryList = response.data;
                }
            },

            async menuCategorySelectChanged(){
                let url = `/api/menu_categories/${this.selectedMenuCategory.id}/menus`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.menuList = response.data;
                }
            },

            async getRoomList(){
                let url = `/api/entities?type=room`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.roomList = response.data;
                }
            },

            addMenuBtnClicked(){
                if(!this.selectedMenu){
                    this.alertValiationMessage(`package menu`);
                    return 1;
                }
                if(!this.menuQty){
                    this.alertValiationMessage(`package menu quantity`);
                    return 1;
                }
                this.selectedMenus.push({
                        id: this.selectedMenu.id,
                        name: this.selectedMenu.name,
                        quantity: this.menuQty
                    });
                this.selectedMenu = null;
                this.menuQty = null;
                this.menuPrice = null;
            },

            removePackageMenuBtnClicked(menuIndex){
                this.selectedMenus.splice(menuIndex, 1);
            },

            async createBtnClicked(){
                let formData = new FormData();
                if(!this.name){
                    this.alertValiationMessage(`package name`);
                    return 1;
                }
                if(!this.startDate){
                    this.alertValiationMessage(`from date`);
                    return 1;
                }
                if(!this.endDate){
                    this.alertValiationMessage(`to date`);
                    return 1;
                }
                if(!this.price){
                    this.alertValiationMessage(`package price`);
                    return 1;
                }
                if(this.selectedMenus.length < 1){
                    this.alertValiationMessage(`package menus`);
                    return 1;
                }

                formData.append('name', this.name);
                formData.append('from_date', this.startDate);
                formData.append('to_date', this.endDate);
                formData.append('price', this.price);
                formData.append('is_ktv', (this.isKTVPackage)? 1: 0);
                if(this.isKTVPackage){
                    if(!this.sessionDuration){
                        this.alertValiationMessage(`session`);
                        return 1;
                    }
                    if(this.selectedRooms.length < 1){
                        this.alertValiationMessage(`rooms`);
                        return 1;
                    }
                    let roomIds = [];
                    this.selectedRooms.forEach((room)=>{
                        roomIds.push(room.id);
                    });
                    formData.append('session', this.sessionDuration);
                    formData.append('roomIds', JSON.stringify(roomIds));
                }
                let menuIds = [];
                this.selectedMenus.forEach((menu)=>{
                    menuIds.push({menu_id: menu.id, quantity: menu.quantity});
                });

                formData.append('menuIds', JSON.stringify(menuIds));

                let url = `/api/packages`;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    this.$notify({
                        text: `Package created successfully`,
                        type: "info"
                    });

                    window.location.replace('/packages');
                }
                else{
                    this.$notify({
                        text: `Package create failed`,
                        type: "error"
                    });
                }
            },
        },

        watch: {
            isKTVPackage: function(){
                if(this.isKTVPackage){
                    this.getRoomList();
                }
                else{
                    this.roomList = [];
                    this.selectedRooms = [];
                    this.sessionDuration = null;
                }
            }
        },

        created(){
            this.getMenuCategoryList();
        },

        mounted(){
            initTE({ Modal, Select, Tab, Ripple, Input });
        }
    }
</script>
