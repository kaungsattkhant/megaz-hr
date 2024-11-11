<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add Menu
            </p>
        </div>

        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Menu Name
                </label>
                <input type="text" v-model="name" class="input-ui mb-2">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Selling Price
                </label>
                <input type="text" v-model="name" class="input-ui mb-2">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Cooking Areas
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedAreas" class="input-ui">
                        <option :value="menuArea.id" v-for="(menuArea, menuAreaIndex) in areaList"
                            :key="menuAreaIndex"> {{ menuArea.name }} </option>
                    </select>
                </div>
            </div><div class="col-span-3"></div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Level
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedAreas" class="input-ui">
                        <option :value="menuArea.id" v-for="(menuArea, menuAreaIndex) in areaList"
                            :key="menuAreaIndex"> {{ menuArea.name }} </option>
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
                        data-te-select-filter="true" name="" id="" v-model="selectedAreas" class="input-ui">
                        <option :value="menuArea.id" v-for="(menuArea, menuAreaIndex) in areaList"
                            :key="menuAreaIndex"> {{ menuArea.name }} </option>
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
                        data-te-select-filter="true" name="" id="" v-model="selectedAreas" class="input-ui">
                        <option :value="menuArea.id" v-for="(menuArea, menuAreaIndex) in areaList"
                            :key="menuAreaIndex"> {{ menuArea.name }} </option>
                    </select>
                </div>
            </div><div class="col-span-3"></div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Position
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedAreas" class="input-ui">
                        <option :value="menuArea.id" v-for="(menuArea, menuAreaIndex) in areaList"
                            :key="menuAreaIndex"> {{ menuArea.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Duration
                </label>
                <input type="text" v-model="name" class="input-ui mb-2">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Order Time
                </label>
                <input type="text" v-model="name" class="input-ui mb-2">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Expected Quantity
                </label>
                <input type="text" v-model="name" class="input-ui mb-2">
            </div>

            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Item
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedAreas" class="input-ui">
                        <option :value="menuArea.id" v-for="(menuArea, menuAreaIndex) in areaList"
                            :key="menuAreaIndex"> {{ menuArea.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Amount
                </label>
                <input type="text" v-model="name" class="input-ui mb-2">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    UOM
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedAreas" class="input-ui">
                        <option :value="menuArea.id" v-for="(menuArea, menuAreaIndex) in areaList"
                            :key="menuAreaIndex"> {{ menuArea.name }} </option>
                    </select>
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
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="">
                                Menu
                            </th>
                            <th scope="col" class="">
                                Qty
                            </th>
                            <th scope="col" class="">
                                Amount
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(ingredient, ingredientIndex) in ingredientItems"
                            :key="ingredientIndex">
                            <td class="">
                                {{ ingredient.name }}
                            </td>
                            <td class="">
                                {{ (ingredient.price).toLocaleString() }}
                            </td>
                            <td class="">
                                {{ ingredient.weight }}
                            </td>
                            <td class="">
                                {{ ingredient.uom_name }}
                            </td>
                            <td class="">
                                {{ ingredient.is_make_pack }}
                            </td>
                            <td class="">
                                <button @click="removeIngredientBtnClicked(ingredientIndex)">
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                        <tr class="" v-for="(ingredient, ingredientIndex) in ingredientItems"
                            :key="ingredientIndex">
                            <td class="">
                                {{ ingredient.name }}
                            </td>
                            <td class="">
                                {{ (ingredient.price).toLocaleString() }}
                            </td>
                            <td class="">
                                {{ ingredient.weight }}
                            </td>
                            <td class="">
                                {{ ingredient.uom_name }}
                            </td>
                            <td class="">
                                {{ ingredient.is_make_pack }}
                            </td>
                            <td class="">
                                <button @click="removeIngredientBtnClicked(ingredientIndex)">
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
            menuCategoryList: [],
            itemCategoryList: [],
            itemList: [],

            uomList: [],
            itemUoms: [],
            selectedUom: null,

            profitPercentage: 0,

            menuCategoryId: null,
            name: null,
            price: null,
            isFeatured: false,

            selectedItemCategory: null,
            selectedItem: null,

            weight: null,
            isMakePack: false,
            ingredientItems: [],
            ingredientItemPriceTotal: 0,

            selectedImage: null,

            areaList: [],
            selectedAreas: [],

            departmentId: null,
            code:null,
            description:null,

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

        async getCookingAreaList(departmentId) {
            let response = await getApiData({ url: `/api/areas?department_id=${departmentId}`, token: this.getToken() });
            if (response.data) {
                this.areaList = response.data;
            }
        },

        updateItemPriceTotal(items){
            this.ingredientItemPriceTotal = 0;
            items.forEach((item)=>{
                this.ingredientItemPriceTotal += item.price;
            });
        },

        calculateProfitPercentage(){
            if(this.price > 0 && this.ingredientItemPriceTotal > 0){
                this.profitPercentage = ((this.price - this.ingredientItemPriceTotal) / this.ingredientItemPriceTotal) * 100
            }
        },

        isFeaturedCheckChanged() {
            this.isFeatured = this.$refs.is_featured.checked;
        },

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

        async addItemBtnClicked() {
            if(!this.selectedItem){
                this.alertValidationMessage('an item');
                return 1;
            }
            if (!this.weight) {
                this.alertValiationMessage('weight');
                return 1;
            }
            if(!this.selectedUom){
                this.alertValiationMessage('UOM');
                return 1;
            }

            let url = `/api/get_uom_conversion_by_uom?po_uom_id=${this.selectedUom.id}&item_uom_id=${this.selectedItem.item_prices.uom_id}&item_price=${this.selectedItem.item_prices.price}&base_uom_id=${this.selectedItem.base_uom_id}`;
            let response = await getApiData({url: url, token: this.getToken()});
            let uomConversion = null;
            let amount = 0;
            let price = 0;
            if(response.data){
                uomConversion = response.data;
                amount = parseInt(response.data.price);
                price = this.weight * amount;

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

            this.ingredientItems.push({
                id: this.selectedItem.id,
                price: price,
                name: this.selectedItem.name,
                weight: this.weight,
                is_make_pack: this.isMakePack,
                uom_id: this.selectedUom.id,
                uom_name: this.selectedUom.name
            });

            this.updateItemPriceTotal(this.ingredientItems);

            this.weight = null;
            this.isMakePack = false;
            this.$refs.is_make_pack.checked = false;
        },

        removeIngredientBtnClicked(ingredientIndex) {
            this.ingredientItems.splice(ingredientIndex, 1);
            this.updateItemPriceTotal(this.ingredientItems);
        },

        async createMenuBtnClicked() {
            if (!this.name) {
                this.alertValiationMessage(`menu name`);
                return 1;
            }
            else if(!this.price){
                this.alertValiationMessage(`menu price`);
                return 1;
            }
            else if(!this.menuCategoryId){
                this.alertValiationMessage(`menu category`);
                return 1;
            }
            else if(this.ingredientItems.length < 1){
                this.alertValiationMessage(`menu items`);
                return 1;
            }
            else if(!this.selectedImage){
                this.alertValiationMessage(`menu image`);
                return 1;
            }
            else if(this.selectedAreas.length < 1){
                this.alertValiationMessage(`cooking areas`);
                return 1;
            }
            else {
                let areaIds = [];
                this.selectedAreas.forEach((area)=>{
                    areaIds.push(area.id);
                });
                let menuItems = JSON.stringify({ items: this.ingredientItems });
                let formData = new FormData();
                formData.append('menu_category_id', this.menuCategoryId);
                formData.append('name', this.name);
                formData.append('is_feature', (this.isFeatured)?1:0);
                formData.append('price', this.price);
                formData.append('items', menuItems);
                formData.append('image',this.selectedImage);
                formData.append('areas',JSON.stringify(areaIds));
                formData.append('code',this.code);
                formData.append('description',this.description);

                let response = await postApiData({ url: `/api/menus`, form_data: formData, token: this.getToken() });

                if (response.success) {
                    window.location.replace(`/menus`);
                }
            }
        }
    },

    watch: {
        price: function () {
            this.calculateProfitPercentage();
        },

        ingredientItemPriceTotal: function(){
            this.calculateProfitPercentage();
        }
    },

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
        this.getCookingAreaList(this.departmentId);
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
