<template>
    <div class="px-0">
        <div v-show="is_step == 1">
            
    
            <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
                <div class="mb-4 col-span-12">
                    <p class="text-lg font-semibold font-inter">
                        Add Menu Forecasting
                    </p>
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Month
                    </label>
                    <input type="date" v-model="selectedMonth" class="input-ui">
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Type
                    </label>
                    <div class="mb-0 w-full text-sm inline-block h-max select-ui"
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
                    <multiselect v-model="selectedMenu"
                    :options="menuList"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="false"
                    placeholder="Select Menu"
                    track-by="id"
                    label="name"
                    :preselect-first="false">
                    </multiselect>
                    <!-- <div class="mb-0 w-full text-sm inline-block h-max select-ui"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                         
                            data-te-select-filter="true" name="" id="" v-model="selectedMenu" class="input-ui">
                            <option :value="menu" v-for="(menu, menuIndex) in menuList"
                                :key="menuIndex"> {{ menu.name }} </option>
                        </select>
                    </div> -->
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Quantity
                    </label>
                    <input type="number" v-model="quantity" class="input-ui">
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
                            <tr class=" !text-center" v-if="menuTableList.length < 1">
                                <td class="" colspan="4">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
    
                    </table>
                </div>

                <div class="flex gap-x-4 mt-6">
                    <button class="add-btn" @click="clickedBtnCreate()">
                        Continue
                    </button>
                    <a href="/menu_forecasting" class="text-[13px] py-[8px] px-4 focus:shadow-none focus:outline-none bg-gray-200 border border-gray-200 rounded" 
                        >
                        Cancel
                    </a>
                </div>
            </div>
            
        </div>
        <div v-show="is_step == 2" class="mt-3">
            <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Month 
                    </label>
                    <input type="date" v-model="selectedMonth" class="input-ui"  :disabled="this.is_disable_step_2" :class="this.is_disable_step_2 ? 'cursor-not-allowed opacity-60 !bg-gray-300' : ''">
                </div>
                <div class="mb-4 col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Type
                    </label>
                    <div class="mb-0 w-full text-sm inline-block h-max select-ui"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type" :disabled="this.is_disable_step_2" :class="this.is_disable_step_2 ? 'cursor-not-allowed opacity-60 !bg-gray-300' : ''"
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
                        <button @click="getMenuTableList()"
                            class="z-20 mt-2 block px-7 pb-3.5 pt-3 rounded-tr-md rounded-tl-md font-inter text-xs font-medium  leading-tight text-neutral-500  focus:isolate data-[te-nav-active]:text-[#fff] data-[te-nav-active]:bg-[#845adf]  bg-white custom-shadow-tab relative"
                            data-te-toggle="pill" data-te-target="#tabs-menu" role="tab" data-te-nav-active
                            aria-controls="tabs-menu" aria-selected="true">Menu</button>
                    </li>
                    <li role="presentation" class="-ml-0.5 group">
                        <a href="#tabs-material" @click="getRawMaterialList()"
                            class="z-[19] mt-2 block px-7 pb-3.5 pt-3 rounded-tr-md rounded-tl-md font-inter text-xs font-medium  leading-tight text-neutral-500  focus:isolate data-[te-nav-active]:text-[#fff] data-[te-nav-active]:bg-[#845adf] bg-white custom-shadow-tab relative"
                            data-te-toggle="pill" data-te-target="#tabs-material" role="tab"
                            aria-controls="tabs-material" aria-selected="true">Raw Material</a>
                    </li>
                    <li role="presentation" class="-ml-1 group">
                        <a href="#tabs-hr" @click="getHrList()"
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
                                            <button data-te-toggle="modal" data-te-target="#change_quantity" @click="btnClickedEditQuantityModal(menu,menuIndex)">
                                                <i class="fal fa-plus  pr-3"></i>
                                            </button>
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
                                            <!-- {{ parseInt(parseInt(raw.total_uom_amt)/raw.uom_conversion) }} {{ raw.base_uom_name }}
                                            {{ ( (parseInt(raw.total_uom_amt)/raw.uom_conversion) - (parseInt(parseInt(raw.total_uom_amt)/raw.uom_conversion)))*raw.uom_conversion  }} {{ raw.uom_name }} -->
                                              {{ raw.total_uom_amt }}
                                        </td>
                                        <td class="">
                                            {{ raw.uom_name }}
                                        </td>
                                        <td class="">
                                            {{ raw.average_price * raw.total_uom_amt }}
                                        </td>
                                        <td class="">
                                            {{ parseInt(parseInt(raw.current_holdings)/raw.uom_conversion) }} {{ raw.base_uom_name }}
                                            {{ ( (parseInt(raw.current_holdings)/raw.uom_conversion) - (parseInt(parseInt(raw.current_holdings)/raw.uom_conversion)))*raw.uom_conversion  }} {{ raw.uom_name }}
                                        </td>
                                        <td class="">
                                            {{ raw.min_holding_base_uom_quantity }}{{ raw.base_uom_name }}
                                            {{ raw.min_holding_uom_quantity }}{{ raw.uom_name }}
                                        </td>
                                        <td class="">
                                            {{ raw.total_uom_amt > raw.current_holdings ? parseInt(parseInt(raw.total_uom_amt-raw.current_holdings)/raw.uom_conversion) : '' }} {{ raw.base_uom_name }}
                                            {{ raw.total_uom_amt > raw.current_holdings ? ( (parseInt(raw.total_uom_amt-raw.current_holdings)/raw.uom_conversion) - (parseInt(parseInt(raw.total_uom_amt-raw.current_holdings)/raw.uom_conversion))) *raw.uom_conversion : '' }} {{ raw.uom_name }}
                                        </td>
                                        <td class="">
                                            <button data-te-toggle="modal" data-te-target="#add_raw_material" @click="btnClickPoModal(raw)">
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
                                            Cost
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
                                            {{ convertMinutesToHoursMinutes(hr.total_working_hour) }}
                                        </td>
                                        <td class="">
                                            {{ (hr.hr_cost_cal).toLocaleString() }} Ks
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
                    Create 
                </button>
            </div>
        </div>


        <!-- Quantity Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="change_quantity" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Edit Quantity
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close" id="close_quantity">
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
                            <input type="text" placeholder="Quantity" v-model="menuQuantity" class="input-ui">
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnClickedEditQuantity()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_raw_material" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Purchase Order
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close" id="close_po">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="grid grid-cols-3 gap-x-4">
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Base Qty
                                </label>
                                <input type="number" placeholder="Quantity" v-model="po_base_quantity" class="input-ui">
                            </div>
                            <div class="mb-4 col-span-2">
                                <label for="" class="label-form mb-3">
                                    Base UOM
                                </label>
                                <div class="bg-white mb-0 w-full text-sm inline-block"
                                    data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select UOM" disabled
                                        data-te-select-filter="true" name="" id="" v-model="po_base_uom" class="input-ui">
                                        <option :value="uom" v-for="(uom, uomIndex) in itemUoms"
                                            :key="uomIndex"> {{ uom.name }} </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="grid grid-cols-3 gap-x-4">
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Qty
                                </label>
                                <input type="number" placeholder="Quantity" v-model="po_quantity" class="input-ui">
                            </div>
                            <div class="mb-4 col-span-2">
                                <label for="" class="label-form mb-3">
                                    UOM
                                </label>
                                <div class="bg-white mb-0 w-full text-sm inline-block"
                                    data-te-select-wrapper-ref>
                                    <select data-te-select-init data-te-select-placeholder="Select UOM" disabled
                                        data-te-select-filter="true" name="" id="" v-model="po_uom" class="input-ui">
                                        <option :value="uom" v-for="(uom, uomIndex) in itemUoms"
                                            :key="uomIndex"> {{ uom.name }} </option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Brand
                            </label>
                            <div class="bg-white mb-0 w-full text-sm inline-block"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Brand" @change="selectedBrandChange"
                                    data-te-select-filter="true" name="" id="" v-model="po_brand" class="input-ui">
                                    <option :value="brand" v-for="(brand, brandIndex) in itemBrands"
                                        :key="brandIndex"> {{ brand.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="label-form mb-3">PO Number</label>
                            <div class="bg-white mb-0 w-full text-sm inline-block"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select PO"
                                    data-te-select-filter="true" name="" id="" v-model="selectedPo" class="input-ui">
                                    <option :value="po" v-for="(po, poIndex) in poList"
                                        :key="poIndex"> {{ po.po_id }} </option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="new_po">
                                Create New
                                <input type="checkbox" id="new_po" v-model="isNewPo">
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="btnClickedCreatePo()"
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
import { getCurrentTime, convertMinutesToHoursMinutes } from "../../utilities/datetime-helpers";
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

            menuTableListSecond:[],
            rawMaterialList:[],
            hrList:[],
            
            selectedMenuIndex:null,
            selectedEditMenu:null,
            menuQuantity:null, // use in quantity change modal

            poList:[],
            uomList:[],
            itemUoms:[],
            itemBrands:[],
            po_base_quantity:0,
            po_base_uom:null,
            po_quantity:0,
            po_uom:null,
            po_brand:null,
            unit_price:0,
            selectedPo:null,

            selectedItem:null,
            isNewPo:false,

            is_disable_step_2:true,

            hrmin:convertMinutesToHoursMinutes(300)
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
                let mrp_forecastable_type = null;
                mrp_forecastable_type = "menu";
                // let index = this.menuTableList.findIndex(item => item.menu_id == response.data.id)
                // if(index != -1){
                //     let quantity_1 = Number(this.menuTableList[index].quantity)
                //     let quantity_2 = Number(response.data.quantity)
                //     this.menuTableList[index].quantity = quantity_1 + quantity_2
                // }
                // else{
                    this.menuTableList.push({
                        menuName: response.data.menu,
                        menu_id: response.data.id,
                        mrp_forecastable_id: response.data.id,
                        quantity: response.data.quantity,
                        amount: response.data.total_menu_forecast_amt,
                        mrp_forecastable_type : mrp_forecastable_type,
                        date: this.selectedMonth,
                    })
                // }
                // this.menuTableList[index].quantity += response.data.quantity;
                
                this.selectedMenuCategory = null;
                this.selectedMenu = null;
                this.menuList = [];
                this.quantity = null;
            }
        },
        btnClickedMenuForecastingCreate(){
            this.createMenuForcasting();
        },
        async createMenuForcasting(){
            let hrList = [];
            this.hrList.forEach(hr => {
                hrList.push({
                    role_id : hr.role_id,
                    total_duration : hr.total_working_hour
                })
            });
            let rawList = [];
            this.rawMaterialList.forEach(raw => {
                rawList.push({
                    uom_id : raw.uom_id,
                    quantity : raw.total_uom_amt,
                    amount : raw.average_price * raw.total_uom_amt,
                    item_id : raw.item_id
                })
            });
            
            let formData = new FormData();
            formData.append("type", this.selectedType.value);
            formData.append("date", this.selectedMonth);
            formData.append("target_mrp", JSON.stringify(this.menuTableList));
            formData.append("forecast_hr", JSON.stringify(hrList));
            formData.append("forecast_raw", JSON.stringify(rawList));
            let url = '/api/forecasts';
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                // this.rawMaterialList = response.data;
                window.location.replace('/menu_forecasting');
            }
        },

        async clickedBtnCreate(){
            if(this.menuTableList.length < 1){
                this.alertValidationMessage(`Menu`);
                return 1;
            }
            else {
                this.menuTableList.forEach(menu => menu.date = this.selectedMonth)
                this.is_step = 2;
                initTE({ Modal });
                this.getMenuTableList();
                this.getRawMaterialList();
                this.getHrList();
            }
        },
        async getMenuTableList(){
            let formData = new FormData();
            formData.append("forecast_datas", JSON.stringify(this.menuTableList));
            let url = '/api/forecast/menus';
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.menuTableListSecond = response.data;
                initTE({ Modal });
            }
        },
        btnClickedEditQuantityModal(menu,index){
            this.selectedEditMenu = menu;
            this.selectedMenuIndex = index;
        },
        async btnClickedEditQuantity(){
            if(!this.menuQuantity){
                this.alertValidationMessage(`Quantity`);
                return 1;
            }
            else{
                let formData = new FormData();
                formData.append("quantity", this.menuQuantity);
                let url = '/api/forecast/menus/' + this.selectedEditMenu.menu_id;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    this.menuTableList[this.selectedMenuIndex].quantity = response.data.quantity;
                    this.menuTableList[this.selectedMenuIndex].amount = response.data.total_menu_forecast_amt;
                    this.doneQuantityModal();
                }
            }
        },
        async getRawMaterialList(){
            let formData = new FormData();
            formData.append("forecast_datas", JSON.stringify(this.menuTableList));
            let url = '/api/forecast/raw_materials';
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.rawMaterialList = response.data;
                initTE({ Modal });
            }
        },
        // async getRawMaterial(){
        //     let formData = new FormData();
        //     formData.append("quantity", this.quantity);
        //     let url = '/api/forecast/raw_materials/' + this.selectedMenu.id;
        //     let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
        //     if(response.success){
        //         this.rawMaterialList = response.data;
        //     }
        // },
        async getHrList(){
            let formData = new FormData();
            formData.append("forecast_datas", JSON.stringify(this.menuTableList));
            let url = '/api/forecast/hr';
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.hrList = response.data;
                initTE({ Modal });
            }
        },
        // async getHr(){
        //     let formData = new FormData();
        //     formData.append("quantity", this.quantity);
        //     let url = '/api/forecast/hr/' + this.selectedMenu.id;
        //     let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
        //     if(response.success){
        //         this.hrList = response.data;
        //     }
        // },
        btnClickPoModal(raw){
            this.po_base_quantity = 0 ;
            this.po_brand = null;
            this.selectedItem = raw
            this.itemBrands = raw.brands;
            this.itemSelectChanged();
            this.po_base_uom = this.itemUoms.find(uom => uom.id === this.selectedItem.base_uom_id)
            this.po_uom = this.itemUoms.find(uom => uom.id === this.selectedItem.uom_id)
            this.getPoList();
        },
        async selectedBrandChange(){
            console.log('brand change')
            let response = await getApiData({url: '/api/items/' + this.selectedItem.item_id + '/brands/' + this.po_brand.id, token: this.getToken()});
            if(response.data){
                this.unit_price = response.data;
                console.log(response)
            }
        },
        btnClickedCreatePo(){
            if(this.po_base_quantity < 1){
                this.alertValidationMessage('Quantiy');
                return 1;
            }
            if(!this.po_base_uom){
                this.alertValidationMessage('Base Uom');
                return 1;
            }
            // if(!this.po_quantity){
            //     this.alertValidationMessage('Quantiy');
            //     return 1;
            // }
            if(!this.po_uom){
                this.alertValidationMessage('Uom');
                return 1;
            }
            if(!this.po_brand){
                this.alertValidationMessage('Brand');
                return 1;
            }
            this.createPo();
        },
        async createPo(){
            let quantity = (this.po_base_quantity * this.selectedItem.uom_conversion) + this.po_quantity
            let amount = quantity * (this.unit_price / this.selectedItem.uom_conversion);
            let unitPrice = this.unit_price / this.selectedItem.uom_conversion;
            
            let formData = new FormData();
            formData.append("base_uom_id", this.po_base_uom.id);
            formData.append("base_uom_quantity", this.po_base_quantity);
            formData.append("uom_quantity", this.po_quantity);
            formData.append("uom_id", this.po_uom.id);
            formData.append("item_id", this.selectedItem.item_id);
            formData.append("amount", amount);
            formData.append("quantity", quantity);
            formData.append("unit_price", unitPrice);
            formData.append("brand_id", this.po_brand.id);
            formData.append("uom_conversion_id", this.selectedItem.uom_conversion_id);
            if(!this.isNewPo){
                formData.append("purchase_order_id", this.selectedPo.id);
            }
            else{
                formData.append("status", 'created');
            }

            let url = '/api/forecasts/purchase_orders_item/' + this.selectedItem.item_id;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.donePoModal();
            }
        },

        removeMenuTableItem(index) {
            this.menuTableList.splice(index, 1);
            // this.updateItemPriceTotal(this.ingredientItems);
        },
        async getPoList(){
            let response = await getApiData({ url: `/api/forecasts/purchase_orders`, token: this.getToken() });
            if (response.data) {
                this.poList = response.data;
            }
        },
        itemSelectChanged() {
            this.itemUoms = [];
            let index = this.uomList.findIndex(uom => uom.id == this.selectedItem.base_uom_id);
            if (index != -1) {
                let baseUom = this.uomList[index];
                this.itemUoms.push(baseUom);
            }
            index = this.uomList.findIndex(uom => uom.id == this.selectedItem.uom_id);
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

        doneQuantityModal() {
            document.getElementById("close_quantity").click();
            this.menuQuantity = null;
        },
        donePoModal() {
            document.getElementById("close_po").click();
            this.po_uom = null;
            this.po_quantity = 0;
            this.selectedPo = null;
        },
        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
        convertMinutesToHoursMinutes(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return `${hours}h ${mins}m`;
        },
    },

    watch: {
    },
    
    async created() {
        this.getMenuCategoryList();
        this.getPoList();
        this.getUomList();
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
        const today = new Date();
        console.log(today)
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, "0"); 
        this.minDate = `${year}-${month}`; 
        // this.selectedMonth = `${year}-${month}`; 
    }
}
</script>
<style scoped>
    .multiselect__placeholder{
        margin-bottom: 6px!important;
    }
</style>

<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
