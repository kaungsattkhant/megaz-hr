<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add Menu
            </p>
        </div>

        <div class="grid !grid-cols-12 gap-x-8 gap-y-2 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Menu Name
                </label>
                <input type="text" v-model="menuName" class="input-ui">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Selling Price
                </label>
                <input type="text" v-model="sellingPrice" class="input-ui">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Cooking Areas
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <multiselect v-model="selectedCookingArea" :options="cookingAreaList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                    :preserve-search="true" placeholder="Select Cooking Place" label="name" track-by="id" :preselect-first="true">
                        <template #selection="{ values, search, isOpen }">
                            <span class="multiselect__single"
                                v-if="values.length"
                                v-show="!isOpen">{{ values.length }} Cooking Place selected</span>
                        </template>
                    </multiselect>
                    <!-- <select data-te-select-init data-te-select-placeholder="Select Category" multiple
                        data-te-select-filter="true" name="" id="" v-model="selectedCookingArea" class="input-ui">
                        <option :value="menuArea.id" v-for="(menuArea, menuAreaIndex) in cookingAreaList"
                            :key="menuAreaIndex"> {{ menuArea.name }} </option>
                    </select> -->
                </div>
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Code
                </label>
                <div class="">
                    <input type='text' v-model='code' class="input-ui" placeholder="Code" />
                </div>

            </div>
            
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Type
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedMenuType" class="input-ui">
                        <option :value="menuType" v-for="(menuType, menuTypeIndex) in menuTypeList"
                            :key="menuTypeIndex"> {{ menuType }} </option>
                    </select>
                </div>
            </div>
            <div class="contents" v-if="selectedMenuType == 'menu'">
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Menu Category
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category" @change="menuCategoryChanged()"
                            data-te-select-filter="true" name="" id="" v-model="selectedMenuCategory" class="input-ui">
                            <option :value="menuCategory" v-for="(menuCategory, menuCategoryIndex) in menuCategoryList"
                                :key="menuCategoryIndex"> {{ menuCategory.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Menu
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            data-te-select-filter="true" name="" id="" v-model="selectedMenu" class="input-ui">
                            <option :value="menu.id" v-for="(menu, menuIndex) in menuList"
                                :key="menuIndex"> {{ menu.name }} </option>
                        </select>
                    </div>
                </div><div class="col-span-3"></div>
            </div>
            <div class="contents" v-if="selectedMenuType == 'custom'">
                <div class="col-span-9"></div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Level
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            data-te-select-filter="true" name="" id="" v-model="selectedLevel" class="input-ui">
                            <option :value="level" v-for="(level, levelIndex) in levelList"
                                :key="levelIndex"> {{ level.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Type
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type" v-for="(type, typeIndex) in typeList"
                                :key="typeIndex"> {{ type.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Position
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            data-te-select-filter="true" name="" id="" v-model="selectedPosition" class="input-ui">
                            <option :value="position" v-for="(position, positionIndex) in positionList"
                                :key="positionIndex"> {{ position.name }} </option>
                        </select>
                    </div>
                </div><div class="col-span-3"></div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Duration
                    </label>
                    <input type="number" v-model="duration" class="input-ui">
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Order Time
                    </label>
                    <input type="number" v-model="orderTime" class="input-ui">
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Expected Quantity
                    </label>
                    <input type="number" v-model="expectedQuantity" class="input-ui">
                </div><div class="col-span-3"></div>

                <div class="mb-0 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Category
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            data-te-select-filter="true" name="" id="" v-model="selectedItemCategory" class="input-ui"
                            @change="itemCategorySelectChanged">
                            <option :value="itemCategory" v-for="(itemCategory, itemCategoryIndex) in itemCategoryList"
                                :key="itemCategoryIndex"> {{ itemCategory.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Item
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category" @change="itemSelectChanged()"
                            data-te-select-filter="true" name="" id="" v-model="selectedItem" class="input-ui">
                            <option :value="itemList" v-for="(itemList, itemListIndex) in itemList"
                                :key="itemListIndex"> {{ itemList.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Amount
                    </label>
                    <input type="text" v-model="amount" class="input-ui">
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        UOM
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            data-te-select-filter="true" name="" id="" v-model="selectedUom" class="input-ui">
                            <option :value="uom" v-for="(uom, uomIndex) in itemUoms"
                                :key="uomIndex"> {{ uom.name }} </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Images
                </label>
                <div class="">
                    <input type='file' @change="handleFileChange" class="input-ui w-full !p-1 text-xs" />
                </div>

            </div>
            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Description
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <textarea type='text' v-model='description' class="input-ui w-full !p-1 text-xs" placeholder="Description" ></textarea>
                </div>

            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn" @click="addItemBtnClicked">
                    Add Item
                </button>
            </div>


        </div>
        

        <div class=" bg-white py-4 px-8 rounded-md shadow-md mb-8">

            <div v-if="menuLevel.item_menu.length > 0">
                <div class="flex mb-2">
                    <p v-if="menuLevel.level">
                        {{ menuLevel.level.name }} : 
                    </p>
                    <p>
                        &nbsp;{{ menuLevel.type.name }}
                    </p>
                </div>
                <div class="flex gap-x-8 mb-4">
                    <div class="flex mb-2">
                        <p>
                            Position : 
                        </p>
                        <p>
                            &nbsp;{{ menuLevel.position.name }}
                        </p>
                    </div>
                    <div class="flex mb-2">
                        <p>
                            Duration : 
                        </p>
                        <p>
                            &nbsp;{{ menuLevel.duration }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="table-container mb-4">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="">
                                Menu
                            </th>
                            <th scope="col" class="">
                                Amount
                            </th>
                            <th scope="col" class="">
                                Quantity
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody v-if="menuLevel">
                        <tr v-if="menuLevel.item_menu.length > 0" class="" v-for="(menu, menuIndex) in menuLevel.item_menu"
                            :key="menuIndex">
                            <td class="">
                                {{ menu.name }}
                            </td>
                            <td class="">
                                {{ (menu.price).toLocaleString() }}
                            </td>
                            <td class="">
                                {{ menu.weight }}
                            </td>
                            <td class="">
                                <button @click="removeIngredientBtnClicked(menuIndex)">
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <button class="add-btn" @click="addLevelBtnClicked()">
                    Add Level
                </button>
            </div>

            

        </div>
        <div class=" bg-white py-4 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="">
                                Lvl
                            </th>
                            <th scope="col" class="">
                                Type
                            </th>
                            <th scope="col" class="">
                                Position
                            </th>
                            <th scope="col" class="">
                                Duration
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(level, levelIndex) in levelTable"
                            :key="levelIndex">
                            <td class="">
                                {{ level.level.name }}
                            </td>
                            <td class="">
                                {{ level.type.name }}
                            </td>
                            <td class="">
                                {{ level.position.name }}
                            </td>
                            <td class="">
                                {{ level.duration }}
                            </td>
                            <td class="">
                                <button @click="removeLevelMenuBtnClicked(levelIndex)">
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

            

        </div>


        <div>
            <button class="add-btn" @click="createMenuBtnClicked">
                Create Menu
            </button>
        </div>
        
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Tab, Select } from "tw-elements";
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            cookingAreaList:[],
            levelList:[
                {"id": 'level_1',"name": "Level 1"},
                {"id": 'level_2',"name": "Level 2"},
                {"id": 'level_3',"name": "Level 3"},
                {"id": 'level_4',"name": "Level 4"},
                {"id": 'level_5',"name": "Level 5"},
            ],
            menuTypeList:[
                'menu',
                'custom'
            ],
            menuCategoryList:[],
            menuList:[],
            // levelList:[],
            typeList:[
                {"id": 'portion',"name": "Portion"},
                {"id": 'ready_to_sale',"name": "Ready To Sale"},
                {"id": 'cooking',"name": "Cooking"},
                {"id": 'plating',"name": "Plating"},
                {"id": 'hardcook',"name": "Hard Cook"},
            ],
            positionList:[ //staff list
                {"id": 1,"name": "Position 1"},
                {"id": 2,"name": "Position 2"},
            ],
            itemCategoryList:[],
            itemList:[],
            itemUoms:[],
            uomList:[],

            menuName:null,
            sellingPrice:null,
            selectedCookingArea:null,
            code:null,
            selectedMenuType:null,
            selectedMenuCategory:null,
            selectedMenu:null,
            selectedLevel:null,
            selectedType:null,
            selectedPosition:null,
            duration:null,
            orderTime:null,
            expectedQuantity:null,
            selectedItemCategory:null,
            selectedItem:null,
            amount:null,
            selectedUom:null,
            description:null,
            selectedImage:null,

            menuLevel:{
                level : null,
                type : null,
                position : null,
                staff_id:null,
                staff_quantity:null,
                duration : null,
                order_time : null,
                expected_quantity : null,
                item_menu:[],
            },
            levelTable:[],

            departmentId:null,


        };
    },

    methods: {
        ...mapGetters(['getToken']),

        

        async getCookingAreaList() {
            let response = await getApiData({ url: `/api/cooking_place`, token: this.getToken() });
            if (response.data) {
                this.cookingAreaList = response.data;
            }
        },

        async getStaffList(departmentId){
            let response = await getApiData({ url: '/api/departments/' + departmentId + '/staffs', token: this.getToken() });
            if (response.data) {
                this.positionList = response.data;
            }
        },
        async getMenuCategoryList() {
            let response = await getApiData({ url: `/api/menu_categories`, token: this.getToken() });
            if (response.data) {
                this.menuCategoryList = response.data;
            }
        },
        menuCategoryChanged(){
            this.getMenuList();
        },
        async getMenuList() {
            let response = await getApiData({ url: `/api/menu_categories/${this.selectedMenuCategory.id}/menus`, token: this.getToken() });
            if (response.data) {
                this.menuList = response.data;
            }
        },
        async getItemCategoryList() {
            let response = await getApiData({ url: `/api/categories`, token: this.getToken() });
            if (response.data) {
                this.itemCategoryList = response.data;
            }
        },

        async itemCategorySelectChanged() {
            this.itemUoms = [];
            let response = await getApiData({ url: `/api/items?category_id=${this.selectedItemCategory.id}`, token: this.getToken() });
            if (response.data) {
                this.itemList = response.data;
            }
        },

        itemSelectChanged() {
            this.itemUoms = [];
            let index = this.uomList.findIndex(uom => uom.id == this.selectedItem.base_uom_id);
            if (index != -1) {
                let baseUom = this.uomList[index];
                this.itemUoms.push(baseUom);
            }
            index = this.uomList.findIndex(uom => uom.id == this.selectedItem.item_prices.uom_id);
            if (index != -1) {
                let itemUom = this.uomList[index];
                this.itemUoms.push(itemUom);
            }
            console.log('uom test')
        },

        async getUomList() {
            let response = await getApiData({ url: `/api/uoms`, token: this.getToken() });
            if (response.data) {
                this.uomList = response.data;
            }
        },



        // updateItemPriceTotal(items){
        //     this.ingredientItemPriceTotal = 0;
        //     items.forEach((item)=>{
        //         this.ingredientItemPriceTotal += item.price;
        //     });
        // },
        calculateProfitPercentage(){
            if(this.price > 0 && this.ingredientItemPriceTotal > 0){
                this.profitPercentage = ((this.price - this.ingredientItemPriceTotal) / this.ingredientItemPriceTotal) * 100
            }
        },
        handleFileChange(event) {
            const selectedFile = event.target.files[0];
            this.selectedImage = selectedFile;
        },
        async addItemBtnClicked() {
            // if(!this.selectedItem){
            //     this.alertValidationMessage('an item');
            //     return 1;
            // }
            // if (!this.weight) {
            //     this.alertValiationMessage('weight');
            //     return 1;
            // }
            // if(!this.selectedUom){
            //     this.alertValiationMessage('UOM');
            //     return 1;
            // }

            let url = `/api/get_uom_conversion_by_uom?po_uom_id=${this.selectedUom.id}&item_uom_id=${this.selectedItem.item_prices.uom_id}&item_price=${this.selectedItem.item_prices.price}&base_uom_id=${this.selectedItem.base_uom_id}`;
            let response = await getApiData({url: url, token: this.getToken()});
            let uomConversion = null;
            let amount = 0;
            let price = 0;
            if(response.data){
                uomConversion = response.data;
                amount = parseInt(response.data.price);
                price = this.amount * amount;

                this.$notify({
                    text: `Uom conversion by uom value ${amount}`,
                    type: 'info'
                });
            }
            else{
                this.$notify({
                    title: 'Error',
                    text: response.message,
                    type: 'error'
                });

                return 1;
            }

            if(this.menuLevel.item_menu.length < 1){
                this.menuLevel.level = this.selectedLevel.id;
                this.menuLevel.type = this.selectedType.id;
                this.menuLevel.position = this.selectedPosition;
                this.menuLevel.staff_id = this.selectedPosition.id;
                this.menuLevel.staff_quantity = 2;
                this.menuLevel.duration = this.duration;
                this.menuLevel.order_time = this.orderTime;
                this.menuLevel.expected_quantity = this.expectedQuantity;
                this.menuLevel.item_menu.push({
                    item_id: this.selectedItem.id,
                    price: price,
                    name: this.selectedItem.name,
                    weight: this.amount,
                    is_make_pack: this.isMakePack,
                    uom_id: this.selectedUom.id,
                    uom_name: this.selectedUom.name
                });
            }
            else{
                this.menuLevel.item_menu.push({
                    item_id: this.selectedItem.id,
                    price: price,
                    name: this.selectedItem.name,
                    weight: this.amount,
                    is_make_pack: this.isMakePack,
                    uom_id: this.selectedUom.id,
                    uom_name: this.selectedUom.name
                });
            }

            // this.updateItemPriceTotal(this.ingredientItems);

            // this.weight = null;
            // this.isMakePack = false;
            // this.$refs.is_make_pack.checked = false;
        },

        removeIngredientBtnClicked(ingredientIndex) {
            this.ingredientItems.splice(ingredientIndex, 1);
            this.updateItemPriceTotal(this.ingredientItems);
        },

        addLevelBtnClicked(){
            this.levelTable.push(
                this.menuLevel
            );
        },

        async createMenuBtnClicked() {
            // if (!this.name) {
            //     this.alertValiationMessage(`menu name`);
            //     return 1;
            // }
            // else if(!this.price){
            //     this.alertValiationMessage(`menu price`);
            //     return 1;
            // }
            // else if(!this.menuCategoryId){
            //     this.alertValiationMessage(`menu category`);
            //     return 1;
            // }
            // else if(this.ingredientItems.length < 1){
            //     this.alertValiationMessage(`menu items`);
            //     return 1;
            // }
            // else if(!this.selectedImage){
            //     this.alertValiationMessage(`menu image`);
            //     return 1;
            // }
            // else if(this.selectedAreas.length < 1){
            //     this.alertValiationMessage(`cooking areas`);
            //     return 1;
            // }
            // else {
                let cookingPlaceId = [];
                this.selectedCookingArea.forEach((item) => {
                    cookingPlaceId.push(item.id)
                })
                
                console.log('array ' ,cookingPlaceId)
                let formData = new FormData();
                formData.append('name', this.menuName);
                formData.append('menu_category_id', 4);
                formData.append('code', this.code);
                formData.append('image',this.selectedImage);
                // formData.append('description',this.description);
                formData.append('price', this.sellingPrice);
                formData.append('Menu_type', this.selectedMenuType);
                // formData.append('areas',JSON.stringify(areaIds));
                formData.append('cooking_place_id',cookingPlaceId);
                formData.append('menu_steps',JSON.stringify(this.levelTable));

                let response = await postApiData({ url: `/api/mrp`, form_data: formData, token: this.getToken() });

                if (response.success) {
                    // window.location.replace(`/menus`);
                }
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            // }
        },

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
    },

    // watch: {
    //     price: function () {
    //         this.calculateProfitPercentage();
    //     },

    //     ingredientItemPriceTotal: function(){
    //         this.calculateProfitPercentage();
    //     }
    // },

    async created() {
        let response = await getApiData({url: `/api/departments`, token: this.getToken()});
        if(response.data){
            response.data.forEach((department)=>{
                if(department.name == `Kitchen`){
                    this.departmentId = department.id;
                }
            });
        }
        this.getMenuCategoryList();
        this.getItemCategoryList();
        this.getUomList();
        this.getCookingAreaList();
        this.getStaffList(this.departmentId);
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
