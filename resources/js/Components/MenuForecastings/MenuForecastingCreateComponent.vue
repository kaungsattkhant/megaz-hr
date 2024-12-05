<template>
    <div class="px-0">
        <div v-show="is_step == 1">
            <div class="mb-4 ">
                <p class="text-lg font-semibold font-inter">
                    Add Menu Forecasting
                </p>
            </div>
    
            <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Month
                    </label>
                    <input type="month" v-model="selectedMonth" class="input-ui" :min="minDate">
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Type
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type" v-for="(type, typeIndex) in typeList"
                                :key="typeIndex"> {{ type.name }} </option>
                        </select>
                    </div>
                </div><div class="col-span-6"></div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Menu Category
                    </label>
                    <div class="mb-0 w-full text-sm inline-block h-max select-ui"
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
                    <div class="mb-0 w-full text-sm inline-block h-max select-ui"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                         
                            data-te-select-filter="true" name="" id="" v-model="selectedMenu" class="input-ui">
                            <option :value="menu" v-for="(menu, menuIndex) in menuList"
                                :key="menuIndex"> {{ menu.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Quantity
                    </label>
                    <input type="text" v-model="quantity" class="input-ui">
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        &nbsp;
                    </label>
                    <button class="add-btn py-[9px]" @click="btnClickedAddMenu()">
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
                            <tr class="" v-for="(menu, menuIndex) in menuTableList"
                                :key="menuIndex">
                                <td class="">
                                    {{ menu.menuName }}
                                </td>
                                <td class="">
                                    {{ menu.quantity }}
                                </td>
                                <td class="">
                                    {{ menu.amount }}
                                </td>
                                <td class="">
                                    <button @click="removeMenuTableItem(menuIndex)">
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
    
                    </table>
                </div>
    
                
    
            </div>
            
            
            <div>
                <button class="add-btn" @click="clickedBtnCreate()">
                    Create
                </button>
            </div>
        </div>
        <div v-show="is_step == 2">
            <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Month 
                    </label>
                    <input type="month" v-model="selectedMonth" class="input-ui">
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Type
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type" v-for="(type, typeIndex) in typeList"
                                :key="typeIndex"> {{ type.name }} </option>
                        </select>
                    </div>
                </div><div class="col-span-6"></div>
            </div>

            <div class=" ">
                <ul class="mb-0 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                    <li role="presentation" class="group">
                        <button
                            class="z-20 mt-2 block px-7 pb-3.5 pt-3 rounded-tr-md rounded-tl-md font-inter text-xs font-medium  leading-tight text-neutral-500  focus:isolate data-[te-nav-active]:text-[#fff] data-[te-nav-active]:bg-[#845adf]  bg-white custom-shadow-tab relative"
                            data-te-toggle="pill" data-te-target="#tabs-menu" role="tab" data-te-nav-active
                            aria-controls="tabs-menu" aria-selected="true">Menu</button>
                    </li>
                    <li role="presentation" class="-ml-0.5 group">
                        <a href="#tabs-material"
                            class="z-[19] mt-2 block px-7 pb-3.5 pt-3 rounded-tr-md rounded-tl-md font-inter text-xs font-medium  leading-tight text-neutral-500  focus:isolate data-[te-nav-active]:text-[#fff] data-[te-nav-active]:bg-[#845adf] bg-white custom-shadow-tab relative"
                            data-te-toggle="pill" data-te-target="#tabs-material" role="tab"
                            aria-controls="tabs-material" aria-selected="true">Raw Material</a>
                    </li>
                    <li role="presentation" class="-ml-1 group">
                        <a href="#tabs-hr"
                            class="z-[18] mt-2 block px-7 pb-3.5 pt-3 rounded-tr-md rounded-tl-md font-inter text-xs font-medium  leading-tight text-neutral-500  focus:isolate data-[te-nav-active]:text-[#fff] data-[te-nav-active]:bg-[#845adf] bg-white custom-shadow-tab relative"
                            data-te-toggle="pill" data-te-target="#tabs-hr" role="tab"
                            aria-controls="tabs-hr" aria-selected="true">HR</a>
                    </li>
                    
                </ul>
    
                <div class="bg-white py-4 px-8 rounded-tr-md rounded-bl-md rounded-br-md shadow-md mb-8 -mt-0.5 z-30 relative">
                    <div class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-menu" role="tabpanel" aria-labelledby="tabs-menu-tab" data-te-tab-active>
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
                                    <tr class="" v-for="(menu, menuIndex) in menuTableList"
                                        :key="menuIndex">
                                        <td class="">
                                            {{ menu.menuName }}
                                        </td>
                                        <td class="">
                                            {{ menu.quantity }}
                                        </td>
                                        <td class="">
                                            {{ menu.amount }}
                                        </td>
                                        <td class="">
                                            <button @click="removeMenuTableItem(menuIndex)">
                                                <i class="fal fa-trash  pr-3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-material" role="tabpanel" aria-labelledby="tabs-material-tab">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead class="">
                                    <tr>
                                        <th scope="col" class="">
                                            Raw Material
                                        </th>
                                        <th scope="col" class="">
                                            Qty
                                        </th>
                                        <th scope="col" class="">
                                            UOM
                                        </th>
                                        <th scope="col" class="">
                                            Amount
                                        </th>
                                        <th scope="col" class="">
                                            Current Holding
                                        </th>
                                        <th scope="col" class="">
                                            Mininum Holding
                                        </th>
                                        <th scope="col" class="">
                                            Net Requirement
                                        </th>
                                        <th scope="col" class="">
    
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="" v-for="(raw, rawIndex) in rawMaterialList"
                                        :key="rawIndex">
                                        <td class="">
                                            {{ raw.name }}
                                        </td>
                                        <td class="">
                                            {{ raw.total_uom_amt }}
                                        </td>
                                        <td class="">
                                            {{ raw.uom_name }}
                                        </td>
                                        <td class="">
                                            {{ raw.average_price * raw.total_uom_amt }}
                                        </td>
                                        <td class="">
                                            {{ raw.current_holdings }}
                                        </td>
                                        <td class="">
                                            {{ raw.name }}
                                        </td>
                                        <td class="">
                                            {{ raw.name }}
                                        </td>
                                        <td class="">
                                            <button data-te-toggle="modal" data-te-target="#create_modal">
                                                <i class="fal fa-plus  pr-3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-hr" role="tabpanel" aria-labelledby="tabs-hr-tab">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead class="">
                                    <tr>
                                        <th scope="col" class="">
                                            Position
                                        </th>
                                        <th scope="col" class="">
                                            Working Hour
                                        </th>
                                        <th scope="col" class="">
    
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="" v-for="(hr, hrIndex) in hrList"
                                        :key="hrIndex">
                                        <td class="">
                                            {{ hr.position }}
                                        </td>
                                        <td class="">
                                            {{ hr.total_working_hour }}
                                        </td>
                                        <td class="">
                                            <button data-te-toggle="modal" data-te-target="#create_modal">
                                                <i class="fal fa-plus  pr-3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    
            </div>

            
            
            
            <div>
                <button class="add-btn" @click="btnClickedMenuForecastingCreate()">
                    Create QKR
                </button>
            </div>
        </div>


        <!-- Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create Raw Material
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close" id="close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Quantity
                            </label>
                            <input type="text" placeholder="Particular" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                UOM
                            </label>
                            <div class="bg-white mb-0 w-full text-sm inline-block"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Type"
                                    data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                                    <option :value="type" v-for="(type, typeIndex) in typeList"
                                        :key="typeIndex"> {{ type.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="label-form mb-3">PO Number</label>
                            <input type="text" placeholder="Amount" class="input-ui">
                        </div>

                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnClickedCreateJournal()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
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
            is_step : 1,

            typeList:[
                {name: 'KTV',value: 'ktv'},
                {name: 'Restaurant',value: 'restaurant'},
            ],
            menuCategoryList:[],
            menuList:[],

            selectedMonth:null,
            selectedType:null,
            selectedMenuCategory:null,
            selectedMenu:null,
            quantity:null,

            menuTableList:[],

            minDate: "",

            rawMaterialList:[],
            hrList:[],

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        
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
        btnClickedAddMenu(){
            // let menuTableListLength = this.menuTableList.length;
            // this.menuTableList.push({
            //     menuName:this.selectedMenu.name,
            //     menuId:this.selectedMenu.id,
            //     quantity:this.quantity,
            //     amount:this.amount
            // })
            // if(this.menuTableList.length > menuTableListLength){
            //     this.selectedMenu = null;
            //     this.quantity = null;
            //     this.amount = null;
            // }
            if(!this.selectedMonth){
                this.alertValidationMessage(`Month`);
                return 1;
            }
            else if(!this.selectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            else if(!this.selectedMenu){
                this.alertValidationMessage(`Menu`);
                return 1;
            }
            else if(!this.quantity){
                this.alertValidationMessage(`Quantity`);
                return 1;
            }
            else{
                this.addMenu();
            }
        },
        async addMenu(){
            let formData = new FormData();
            formData.append("quantity", this.quantity);
            formData.append("type", this.selectedType.value);
            formData.append("date", this.selectedMonth);
            let url = '/api/forecast/menus/' + this.selectedMenu.id;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.menuTableList.push({
                    menuName: response.data.menu,
                    menu_id: response.data.id,
                    quantity: response.data.quantity,
                    amount: response.data.total_menu_forecast_amt
                })
                // this.selectedMenuCategory = null;
                // this.menuList = [];
                // this.selectedMenu = null;
                // this.quantity = null;
                // this.selectedType = null;
                // this.selectedMonth = null;
            }
        },
        btnClickedMenuForecastingCreate(){
            this.createMenuForcasting();
        },
        async createMenuForcasting(){
            let formData = new FormData();
            formData.append("type", this.selectedType.value);
            formData.append("date", this.selectedMonth);
            formData.append("target_mrps", JSON.stringify(this.menuTableList));
            let url = '/api/forecasts';
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                // this.rawMaterialList = response.data;
            }
        },

        async clickedBtnCreate(){
            this.is_step = 2;
            this.getRawMaterial();
            this.getHr();
            
        },
        async getRawMaterial(){
            let formData = new FormData();
            formData.append("quantity", this.quantity);
            let url = '/api/forecast/raw_materials/' + this.selectedMenu.id;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.rawMaterialList = response.data;
            }
        },
        async getHr(){
            let formData = new FormData();
            formData.append("quantity", this.quantity);
            let url = '/api/forecast/hr/' + this.selectedMenu.id;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.hrList = response.data;
            }
        },

        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

    },

    watch: {
    },

    async created() {
        this.getMenuCategoryList();

    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, "0"); 
        this.minDate = `${year}-${month}`; 
        this.selectedMonth = `${year}-${month}`; 
    }
}
</script>
<style scoped>
    .multiselect__placeholder{
        margin-bottom: 6px!important;
    }
</style>

<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
