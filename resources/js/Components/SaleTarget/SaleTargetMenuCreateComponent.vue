<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Create Sale Target (Menu)
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 gap-y-4 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Month
                </label>
                <input type="date" class="input-ui" v-model="selectedDate" placeholder="Package Name">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Area
                </label>
                <div class="w-full select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Area" v-model="selectedArea"
                    data-te-select-filter="true" @change="getMenuList()" class="input-ui w-full">
                        <option :value="area" v-for="(area, index) in areaList"> {{ area.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-3 col-span-6 rounded-md"></div>


            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Menu
                </label>
                <div class="w-full select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Menu" v-model="selectedMenu"
                    data-te-select-filter="true" class="input-ui w-full">
                        <option :value="menu" v-for="(menu, index) in menuList"> {{ menu.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Quantity
                </label>
                <input type="text" placeholder="Quantity" class="input-ui" v-model="selectedQuantity">
            </div>

            

            <div class="col-span-3 flex justify-end flex-col mb-3">
                <!-- <label for="" class="label-form mb-3"> &nbsp;</label> -->
                <button class="add-btn h-8 w-fit mb-1" @click="btnClickedAddMenu()">
                    Add Menu
                </button>
            </div>
            <div class="col-span-3"></div>

        </div>

        <div class=" bg-white py-8 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class=" text-left ">
                                Position
                            </th>
                            <th scope="col" class="  ">
                                Amount
                            </th>
                            <th scope="col" class="  ">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(menu,index) in addedMenuList" :key="index" >
                            <tr class="">
                                <td class="text-left">
                                    {{ menu.name }}
                                </td>
                                <td class="  ">
                                    {{ menu.quantity }}
                                </td>
                                <td class="  ">
                                    <button @click="removeAddedMenu(index)">
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
            <button class="add-btn" @click="createBtnClicked()" >
                Create
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
                menuList:[],
                areaList:[],
                addedMenuList:[],

                selectedDate:null,
                
                selectedArea:null,
                selectedMenu:null,
                selectedQuantity:null,

                
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getAreaList(){
                const response = await getApiData({ url: '/api/sellings_areas' , token: this.getToken() });
                if(response.data){
                    this.areaList = response.data;
                }
            },
            async getMenuList()
            {   
                const response = await getApiData({ url: '/api/menus?selling_area_id=' + this.selectedArea.id, token: this.getToken() });
                if(response.data){
                    this.menuList = response.data;
                }
            },


            btnClickedAddMenu() {
                if(!this.selectedMenu || !this.selectedQuantity){
                    this.$notify({
                        text: `Failed`,
                        type: "error"
                    });
                }
                else{
                     this.addMenu();
                }
               
            },
            addMenu(){
                this.addedMenuList.push({
                    name: this.selectedMenu.name,
                    menu_id: this.selectedMenu.id,
                    quantity: this.selectedQuantity,
                    area_id:this.selectedArea.id
                });
                this.selectedMenu = null;
                this.selectedQuantity = null;
                // this.selectedArea = null;
            },

            removeAddedMenu(index) {
                this.addedMenuList.splice(index, 1);
            },


            async createBtnClicked() {
                let selectedMonth = getFirstDate(this.selectedDate)
                let menuListForForm = [];
                this.addedMenuList.forEach((ap) => {
                    menuListForForm.push({ menu_id: ap.menu_id,area_id: ap.area_id, quantity: ap.quantity });
                });
                let formData = new FormData();
                formData.append('month', selectedMonth);
                // formData.append('area_id', this.selectedArea.id);
                formData.append('target_menus', JSON.stringify(menuListForForm));

                let url = `/api/sale_target_menus`;
                let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.$notify({
                        text: `Menu created successfully`,
                        type: "info"
                    });

                    window.location.replace('/sale_target_menu');
                }
                else {
                    this.$notify({
                        text: `Menu create failed`,
                        type: "error"
                    });
                }
            },

            
        },

        created(){
            this.getAreaList();
            this.getMenuList();
        },

        mounted(){
            initTE({ Modal, Select, Tab, Ripple, Input });
        }
    }
</script>
