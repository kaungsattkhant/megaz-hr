<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add Cooking Place
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Cooking Place Name
                </label>
                <input type="text" v-model="name" class="input-ui ">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Area
                </label>
                
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Category"
                        data-te-select-filter="true" name="" id="" v-model="selectedArea" class="input-ui !text-black">
                        <option :value="area" v-for="(area, index) in areaList"
                            :key="index"> {{ area.name }} </option>
                    </select>
                </div>
            </div><div class="col-span-6"></div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Skill / Menu
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" data-te-select-filter="true" 
                        name="" id="" v-model="selectedType" class="input-ui !text-black" @change="typeChange()">
                        <option :value="type.value" v-for="(type, index) in typeList" class="!uppercase"
                            :key="index"> {{ type.name }} </option>
                    </select>
                </div>
            </div>
            <div v-if="selectedType == 'menu'" class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Menu Category 
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Menu Category" @change="menuCategoryChange()"
                        data-te-select-filter="true" name="" id="" v-model="selectedMenuCategory" class="input-ui">
                        <option :value="menuCategory" v-for="(menuCategory, index) in menuCategoryList"
                            :key="index"> {{ menuCategory.name }} </option>
                    </select>
                </div>
            </div>
            <div v-if="selectedType == 'menu'" class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Menu Name 
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Menu"
                        data-te-select-filter="true" name="" id="" v-model="selectedMenu" class="input-ui">
                        <option :value="menu" v-for="(menu, index) in menuList"
                            :key="index"> {{ menu.name }} </option>
                    </select>
                </div>
            </div>
            <div v-else class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Skill Name
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Skill"
                        data-te-select-filter="true" name="" id="" v-model="selectedSkill" class="input-ui">
                        <option :value="skill" v-for="(skill, index) in skillList"
                            :key="index"> {{ skill.skill }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="addCookingItem()">
                    Add Item
                </button>
            </div>
            <div v-if="selectedType != 'menu'" class="col-span-3"></div>











            

        </div>

        <div>
            {{ cookingPlaceMenuList }}
        </div>
        <div class=" bg-white py-4 px-8 rounded-md shadow-md mb-8">
            <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                <li role="presentation">
                    <a href="#tabs-menu"
                        class="my-2 block border-x-0 px-7 pb-3.5 pt-3 rounded-md font-inter text-xs font-medium  leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:text-[#845adf] data-[te-nav-active]:bg-[#845adf1a]"
                        data-te-toggle="pill" data-te-target="#tabs-menu" data-te-nav-active role="tab"
                        aria-controls="tabs-menu" aria-selected="true">Menu</a>
                </li>
                <li role="presentation">
                    <a href="#tabs-skill"
                        class="my-2 block border-x-0 px-7 pb-3.5 pt-3 rounded-md font-inter text-xs font-medium  leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent  data-[te-nav-active]:text-[#845adf] data-[te-nav-active]:bg-[#845adf1a]"
                        data-te-toggle="pill" data-te-target="#tabs-skill" role="tab" aria-controls="tabs-skill"
                        aria-selected="false">Skill</a>
                </li>
            </ul>

            <div class="mb-6">
                <div class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                    id="tabs-menu" role="tabpanel" aria-labelledby="tabs-menu-tab" data-te-tab-active>
                    <div class="table-container">
                        <table class="primary-table">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">
                                        Name
                                    </th>
                                    <th scope="col" class="">
                                        Type
                                    </th>
                                    
                                    <th scope="col" class="">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(menu, index) in cookingPlaceMenuList"
                                    :key="index">
                                    <td class="">
                                        {{ menu.name }}
                                    </td>
                                    
                                    <td class="">
                                        {{ menu.type }}
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
                <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                    id="tabs-skill" role="tabpanel" aria-labelledby="tabs-skill-tab">
                    
                    <div class="table-container">
                        <table class="primary-table">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">
                                        Name
                                    </th>
                                    <th scope="col" class="">
                                        Type
                                    </th>
                                    
                                    <th scope="col" class="">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(skill, index) in cookingPlaceSkillList"
                                    :key="index">
                                    <td class="">
                                        {{ skill.name }}
                                    </td>
                                    
                                    <td class="">
                                        {{ skill.type }}
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
            </div>

        </div>


        <div>
            <button class="add-btn" @click="btnclickedCreateCookingPlace()">
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










            areaList:[],
            typeList:[
                {'name': 'Skill', 'value': 'skill'},
                {'name': 'Menu', 'value': 'menu'}
            ],
            menuList:[],
            skillList:[],
            cookingItemList:[],

            name:null,
            selectedArea:null,
            selectedType:null,
            selectedMenuCategory:null,
            selectedMenu:null,
            selectedSkill:null,

            cookingPlaceMenuList:[],
            cookingPlaceSkillList:[],
            testlist:[]
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getAreaList(departmentId){
            // let response = await getApiData({ url: '/api/areas', token: this.getToken() });
            let response = await getApiData({ url: `/api/areas?department_id=${departmentId}`, token: this.getToken() });
            if (response.data) {
                this.areaList = response.data;
            }
        },
        typeChange(){
            console.log(this.selectedType)
            if(this.selectedType == 'menu'){
                this.getMenuCategoryList();
                this.selectedSkill = null
            }
            else{
                this.getSkillList();
                this.selectedMenuCategory = null;
                this.selectedMenu = null;
            }
        },
        menuCategoryChange(){
            this.getMenuList();
        },  
        async getMenuCategoryList(){
            let response = await getApiData({ url: '/api/menu_categories', token: this.getToken() });
            if (response.data) {
                this.menuCategoryList = response.data;
            }
        },
        async getMenuList(){
            let response = await getApiData({ url: '/api/menu_categories/'+ this.selectedMenuCategory.id +'/menus', token: this.getToken() });
            if (response.data) {
                this.menuList = response.data;
            }
        },
        async getSkillList(){
            let response = await getApiData({ url: '/api/skills', token: this.getToken() });
            if (response.data) {
                this.skillList = response.data.data;
            }
        },

        addCookingItem(){
            if(this.selectedType == 'menu'){
                this.cookingPlaceMenuList.push({
                    name: this.selectedMenu.name,
                    id: this.selectedMenu.id,
                    type: this.selectedType,
                });
            }
            else if(this.selectedType == 'skill'){
                this.cookingPlaceSkillList.push({
                    name: this.selectedSkill.skill,
                    id: this.selectedSkill.id,
                    type: this.selectedType,
                });
                console.log(this.selectedType + ' type')
            }
            
        },

        btnclickedCreateCookingPlace(){
            let availableCookingPlaces = [];
            this.cookingPlaceMenuList.forEach(cpm =>{
                availableCookingPlaces.push({
                    id:cpm.id,
                    type:cpm.type
                })
            })
            this.cookingPlaceSkillList.forEach(cps =>{
                availableCookingPlaces.push({
                    id:cps.id,
                    type:cps.type
                })
            })
            this.createCookingPlace(availableCookingPlaces)
        },
        async createCookingPlace(availableCookingPlaces){
                let formData = new FormData();
                formData.append('availableCookingPlaces', JSON.stringify(availableCookingPlaces));
                formData.append('name', this.name);
                formData.append('area_id', this.selectedArea.id);
                let response = await postApiData({ url: '/api/cooking_places', form_data: formData, token: this.getToken() });
                if (response.success) {
                    window.location.replace('/cooking_places');
                    console.log('success')
                }
                
            },
        


        // async getCookingAreaList(departmentId) {
        //     let response = await getApiData({ url: `/api/areas?department_id=${departmentId}`, token: this.getToken() });
        //     if (response.data) {
        //         this.areaList = response.data;
        //     }
        // },

        
    },

    watch: {
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
        this.getAreaList(this.departmentId);
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
