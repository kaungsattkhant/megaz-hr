<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add MRP
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

            <div class="mb-0 col-span-3 row-span-2 rounded-md">
                <label for="" class="label-form mb-3">
                    Description
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <textarea type='text' v-model='description' class="input-ui w-full !px-1 !py-1.5 text-xs" rows="6" placeholder="Description" ></textarea>
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
                <label for="" class="label-form mb-3">
                    MRP Category
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select MRP Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedMenuCategory" class="input-ui">
                        <option :value="menuCategory" v-for="(menuCategory, menuCategoryIndex) in menuCategoryList"
                            :key="menuCategoryIndex"> {{ menuCategory.name }} </option>
                    </select>
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
                            data-te-select-filter="true" name="" id="" v-model="categoryMenu" class="input-ui">
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
                            <option :value="menu" v-for="(menu, menuIndex) in menuList"
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
                    <div class="mb-0 w-full text-sm inline-block h-max" :class="is_disable_custom ? 'bg-gray-200 rounded' : ''"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                        :disabled="is_disable_custom" :class="is_disable_custom ? 'cursor-not-allowed' : ''"
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
                    <div class=" mb-0 w-full text-sm inline-block h-max" :class="is_disable_custom ? 'bg-gray-200 rounded' : ''"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category" @change="typeChange()"
                        :disabled="is_disable_custom" :class="is_disable_custom ? 'cursor-not-allowed' : ''"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type" v-for="(type, typeIndex) in typeList"
                                :key="typeIndex"> {{ type.name }} </option>
                        </select>
                    </div>
                </div>
                <!-- <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Position
                    </label>
                    <div class="mb-0 w-full text-sm inline-block h-max" :class="is_disable_custom ? 'bg-gray-200 rounded' : ''"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            :disabled="is_disable_custom" :class="is_disable_custom ? 'cursor-not-allowed' : ''"
                            data-te-select-filter="true" name="" id="" v-model="selectedPosition" class="input-ui">
                            <option :value="position" v-for="(position, positionIndex) in positionList"
                                :key="positionIndex"> {{ position.name }} </option>
                        </select>
                    </div>
                </div> -->
                <div class="mb-4 col-span-3">
                    <label for="" class="label-form mb-3">
                        Department
                    </label>
                    <div class="mb-0 w-full text-sm inline-block h-max" :class="is_disable_custom ? 'bg-gray-200 rounded' : ''"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
                            :disabled="is_disable_custom" :class="is_disable_custom ? 'cursor-not-allowed' : ''"
                            data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui">
                            <option :value="department" v-for="(department, index) in departmentList"
                                :key="index"> {{ department.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Role
                    </label>
                    <div class="mb-0 w-full text-sm inline-block h-max" :class="is_disable_custom ? 'bg-gray-200 rounded' : ''"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Role"
                            :disabled="is_disable_custom" :class="is_disable_custom ? 'cursor-not-allowed' : ''"
                            data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui">
                            <option :value="role" v-for="(role, roleIndex) in roleList"
                                :key="roleIndex"> {{ role.name }} </option>
                        </select>
                    </div>
                </div>
                <!-- <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Position Quantity
                    </label>
                    <input type="number" v-model="positionQuantity" class="input-ui" :disabled="is_disable_custom" :class="is_disable_custom ? 'cursor-not-allowed !bg-gray-200 rounded' : ''">
                </div> -->
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Duration
                    </label>
                    <input type="number" v-model="duration" class="input-ui" :disabled="is_disable_custom" :class="is_disable_custom ? 'cursor-not-allowed !bg-gray-200 rounded' : ''">
                </div>
                <div class="contents" v-show="is_show">
                    <div class="mb-4 col-span-3 rounded-md">
                        <label for="" class="label-form mb-3">
                            Order Time
                        </label>
                        <input type="number" v-model="orderTime" class="input-ui" :disabled="is_disable_custom" :class="is_disable_custom ? 'cursor-not-allowed !bg-gray-200 rounded' : ''">
                    </div>
                    <div class="mb-4 col-span-3 rounded-md">
                        <label for="" class="label-form mb-3">
                            Expected Quantity
                        </label>
                        <input type="number" v-model="expectedQuantity" class="input-ui" :disabled="is_disable_custom" :class="is_disable_custom ? 'cursor-not-allowed !bg-gray-200 rounded' : ''">
                    </div><div class="col-span-3"></div>
                </div>
                <div  v-show="!is_show" class="col-span-9"></div>
                <div class="mb-0 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Item Category
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
                                :key="uomIndex"> {{ uom.uom_name }} </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-span-12"></div>
            
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn" @click="btnClickedAddMenuLevel()">
                    Add Item
                </button>
            </div>


        </div>
        

        <div class=" bg-white py-4 px-8 rounded-md shadow-md mb-8">

            <div v-if="menuLevel.item_menu.length > 0">
                <div class="flex mb-2">
                    <p v-if="menuLevel.level">
                        {{ menuLevel.level }} : 
                    </p>
                    <p>
                        &nbsp;{{ menuLevel.type }}
                    </p>
                </div>
                <div class="flex gap-x-8 mb-4">
                    <div class="flex mb-2">
                        <p>
                            Position : 
                        </p>
                        <p>
                            &nbsp;{{ menuLevel.role_name }}
                        </p>
                    </div>
                    <div class="flex mb-2">
                        <p>
                            Duration : 
                        </p>
                        <p>
                            &nbsp;{{ menuLevel.duration }} min
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
                                <button @click="removeMenuLevel(menuIndex)">
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex gap-x-4">
                <button class="add-btn" @click="clearMenuLevel()">
                    Clear 
                </button>
                <button class="add-btn" @click="addLevelBtnClicked()">
                    Add 
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
                                {{ level.level }}
                            </td>
                            <td class="">
                                {{ level.type }}
                            </td>
                            <td class="">
                                {{ level.role_name }}
                            </td>
                            <td class="">
                                {{ level.duration }}
                            </td>
                            <td class="">
                                <button @click="removeLevel(levelIndex)">
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="" v-for="(submenu, submenuIndex) in subMenuList"
                            :key="submenuIndex">    
                            <td class="" colspan="4">
                                {{ submenu.name }}
                            </td>
                            <td class="">
                                <button @click="removeSubMenu(submenuIndex)">
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
            departmentList:[],
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
            roleList:[],
            itemCategoryList:[],
            itemList:[],
            itemUoms:[],
            uomList:[],

            selectedDepartment:null,
            menuName:null,
            sellingPrice:null,
            selectedCookingArea:null,
            code:null,
            selectedMenuType:null,
            selectedMenuCategory:null,
            categoryMenu:null,
            selectedMenu:null,
            selectedLevel:null,
            selectedType:null,
            selectedPosition:null,
            positionQuantity:null,
            selectedRole:null,
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
                // staff_id:null,
                // staff_quantity:null,
                role_name:null,
                role_id:null,
                duration : null,
                order_time : null,
                expected_quantity : null,
                item_menu:[],
            },
            levelTable:[],
            subMenu:[], // selected type = menu
            subMenuList:[], // selected type = menu

            departmentId:null,

            is_disable_custom:false,
            is_show:false,

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

        async getDepartment(){
            let response = await getApiData({url: `/api/departments`, token: this.getToken()});
            if(response.data){
                this.departmentList = response.data;
            }
        },
        selectedDepartmentChange(){
            this.getRoleList();
        },
        async getRoleList(){
            let response = await getApiData({url: '/api/roles_department/' + this.selectedDepartment.id , token: this.getToken()});
            if(response.data){
                this.roleList = response.data;
            }
        },
        typeChange(){
            if(this.selectedType.name == 'Portion'){
                this.is_show = true;
            }
            else{
                this.is_show = false;
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
            let response = await getApiData({ url: `/api/menu_categories/${this.categoryMenu.id}/menus`, token: this.getToken() });
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
            console.log(this.selectedItem)
            this.itemUoms.push(
                {
                    id:this.selectedItem.base_uom_id,
                    base_uom_id : this.selectedItem.base_uom_id,
                    uom_name : this.selectedItem.base_uom_name,
                    uom_conversion : this.selectedItem.uom_conversion
                },
                {
                    id:this.selectedItem.uom_id,
                    uom_id : this.selectedItem.uom_id,
                    uom_name : this.selectedItem.item_uom,
                    uom_conversion : this.selectedItem.uom_conversion
                }
            )
            // let index = this.uomList.findIndex(uom => uom.id == this.selectedItem.base_uom_id);
            // if (index != -1) {
            //     let baseUom = this.uomList[index];
            //     this.itemUoms.push(baseUom);
            // }
            // index = this.uomList.findIndex(uom => uom.id == this.selectedItem.uom_id);
            // if (index != -1) {
            //     let itemUom = this.uomList[index];
            //     this.itemUoms.push(itemUom);
            // }
            // console.log('uom test')
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
        btnClickedAddMenuLevel(){
            if(this.selectedMenuType == 'menu'){
                this.subMenuList.push({
                    name: this.selectedMenu.name,
                    id: this.selectedMenu.id,
                });
                // this.subMenu.push(this.selectedMenu.id);
                this.selectedMenu = null;
            }
            else{
                this.addCustom();
            }
            console.log(this.selectedMenuType)
        },
        async addCustom() {
            if(!this.selectedLevel){
                this.alertValidationMessage('Level');
                return 1;
            }
            else if (!this.selectedType) {
                this.alertValidationMessage('Type');
                return 1;                
            }
            else if(!this.selectedRole){
                this.alertValidationMessage('Role');
                return 1;
            }
            else if(!this.duration){
                this.alertValidationMessage('Duration');
                return 1;
            }
            else if(this.selectedType.name == 'Portion' && !this.orderTime){
                this.alertValidationMessage('Order Time');
                return 1;
            }
            else if(this.selectedType.name == 'Portion' && !this.expectedQuantity){
                this.alertValidationMessage('Expected Quantity');
                return 1;
            }
            else if(!this.selectedItemCategory){
                this.alertValidationMessage('Item Category');
                return 1;
            }
            else if(!this.selectedItem){
                this.alertValidationMessage('Item');
                return 1;
            }
            else if(!this.amount){
                this.alertValidationMessage('Amount');
                return 1;
            }
            else if(!this.selectedUom){
                this.alertValidationMessage('UOM');
                return 1;
            }
            else{
                let item_price = parseFloat(this.selectedItem.average_price)
                let url = `/api/get_uom_conversion_by_uom?po_uom_id=${this.selectedUom.id}&item_uom_id=${this.selectedItem.uom_id}&item_price=${item_price}&base_uom_id=${this.selectedItem.base_uom_id}`;
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

                let uom_type = null;
                if(this.selectedUom.base_uom_id){
                    uom_type = 'base_uom'
                }
                if(this.selectedUom.uom_id){
                    uom_type = 'uom'
                }
                if(this.menuLevel.item_menu.length < 1){
                    this.menuLevel.level = this.selectedLevel.id;
                    this.menuLevel.type = this.selectedType.id;
                    this.menuLevel.position = this.selectedPosition;
                    // this.menuLevel.staff_id = this.selectedPosition.id;
                    // this.menuLevel.staff_quantity = this.positionQuantity;
                    this.menuLevel.role_id = this.selectedRole.id;
                    this.menuLevel.role_name = this.selectedRole.name;
                    this.menuLevel.duration = this.duration;
                    if(this.selectedType.name == 'Portion'){
                        this.menuLevel.order_time = this.orderTime;
                        this.menuLevel.expected_quantity = this.expectedQuantity;
                    }
                    // else{
                    //     this.menuLevel.order_time = 0;
                    //     this.menuLevel.expected_quantity = 0;
                    // }
                    
                    this.menuLevel.item_menu.push({
                        item_id: this.selectedItem.id,
                        price: price,
                        name: this.selectedItem.name,
                        weight: this.amount,
                        is_make_pack: this.isMakePack,
                        uom_id: this.selectedUom.id,
                        uom_name: this.selectedUom.name,
                        uom_type: uom_type,
                        uom_conversion: this.selectedUom.uom_conversion
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
                        uom_name: this.selectedUom.name,
                        uom_type: uom_type,
                        uom_conversion: this.selectedUom.uom_conversion
                    });
                }
                this.selectedItemCategory = null;
                this.selectedItem = null;
                this.selectedUom = null;
                this.amount = null;
                this.selectedDepartment = null;
                this.is_disable_custom = true;
            }
            // this.updateItemPriceTotal(this.ingredientItems);

            // this.weight = null;
            // this.isMakePack = false;
            // this.$refs.is_make_pack.checked = false;
        },

        removeMenuLevel(index) {
            this.menuLevel.item_menu.splice(index, 1);
            // this.updateItemPriceTotal(this.ingredientItems);
        },

        clearMenuLevel(){
            this.resetMenuLevel();
            this.selectedLevel = null;
            this.selectedType = null;
            this.selectedPosition = null;
            this.positionQuantity = null;
            this.duration = null;
            this.orderTime = null;
            this.expectedQuantity = null;
            this.is_disable_custom = false;
        },
        addLevelBtnClicked(){
            this.levelTable.push(
                JSON.parse(JSON.stringify(this.menuLevel))
            );
            this.is_disable_custom = false
            this.clearMenuLevel();
        },
        removeLevel(index) {
            this.levelTable.splice(index, 1);
            // this.updateItemPriceTotal(this.ingredientItems);
        },
        removeSubMenu(index) {
            this.subMenuList.splice(index, 1);
            // this.submenu.splice(index, 1);
            // this.updateItemPriceTotal(this.ingredientItems);
        },
        async createMenuBtnClicked() {
            if (!this.menuName) {
                this.alertValidationMessage(`menu name`);
                return 1;
            }
            else if(!this.sellingPrice){
                this.alertValidationMessage(`menu price`);
                return 1;
            }
            else if(!this.selectedMenuCategory){
                this.alertValidationMessage(`menu category`);
                return 1;
            }
            else if(!this.code){
                this.alertValidationMessage(`Code`);
                return 1;
            }
            else if(!this.selectedImage){
                this.alertValidationMessage(`menu image`);
                return 1;
            }
            else if(!this.selectedCookingArea){
                this.alertValidationMessage(`cooking areas`);
                return 1;
            }
            else if(!this.selectedMenuType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            else {
                let cookingPlaceId = [];
                this.selectedCookingArea.forEach((item) => {
                    cookingPlaceId.push(item.id)
                })
                let sub_menu_id = [];
                this.subMenuList.forEach((submenu) => {
                    sub_menu_id.push(submenu.id)
                    console.log('sub menu ' ,sub_menu_id)
                })
                console.log('array ' ,sub_menu_id)
                let formData = new FormData();
                formData.append('name', this.menuName);
                formData.append('menu_category_id', this.selectedMenuCategory.id);
                formData.append('code', this.code);
                formData.append('image',this.selectedImage);
                // formData.append('description',this.description);
                formData.append('price', this.sellingPrice);
                formData.append('Menu_type', this.selectedMenuType);
                formData.append('cooking_place_id',JSON.stringify(cookingPlaceId));

                formData.append('menu_steps',JSON.stringify(this.levelTable));
                if(sub_menu_id.length > 0){
                    formData.append('sub_menu_id',JSON.stringify(sub_menu_id));
                }
                
                let response = await postApiData({ url: `/api/mrp`, form_data: formData, token: this.getToken() });
                if (response.success) {
                    window.location.replace(`/mrp`);
                }
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            }
        },
        resetMenuLevel(){
            this.menuLevel.level = null;
            this.menuLevel.type = null;
            this.menuLevel.position = null;
            this.menuLevel.staff_id=null;
            this.menuLevel.staff_quantity=null;
            this.menuLevel.duration = null;
            this.menuLevel.order_time = null;
            this.menuLevel.expected_quantity = null;
            this.menuLevel.item_menu=[];
        },

        
        alertValidationMessage(field) {
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
        this.getDepartment();
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
