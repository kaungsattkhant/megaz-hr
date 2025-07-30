<template>
    <div class="">
        <div class="mb-6" v-if="!isExtra">
            asdf
            <div class="w-full pt-9 px-6 ">
                <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                    <li v-for="(category, index) in menuCategoryList" :key="index" role="presentation" @click="menuCategoryChange(category)">
                        <a href="#tabs-profile" class="my-2 mr-3 text-white block  px-7 pb-2.5 rounded-full
                            pt-3 text-xs  hover:isolate bg-[#F0C094]
                            hover:bg-[#f7a559] focus:isolate data-[te-nav-active]:bg-[#F19E51]">
                            {{ category.name }}
                        </a>
                    </li>
                </ul>
                <div class="opacity-100 transition-opacity duration-150 ease-linear"
                    style="width:calc(100% - 410px)">
                    <div class="flex flex-wrap gap-x-4 gap-y-4">
                        <div v-for="(menu, menuIndex) in menuList" :key="menuIndex"
                            class=" flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
                            <button @click="btnClickedAddOrder(menu, menuIndex)"
                                class="relative flex flex-col justify-between h-full w-full">
                                <img src="../../../../../public/img/order1.png" alt="Menu Image" />
                                <div class="absolute bottom-0 w-full flex justify-end">
                                    <p class="text-base text-black font-semibold">
                                        {{ menu.name }}
                                    </p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

               
            </div>

            <div class="fixed right-0 top-0 bottom-0 bg-white shadow-md ease-in-out duration-300 transition delay-100 pt-12 right-sidebar-2"
                :class="isExtra === false ? 'translate-x-0 opacity-100 w-[400px]' : 'translate-x-full opacity-0 w-0' ">
                <div class="relative h-full w-full">
                    <div class="relative h-full">
                        <div class="flex justify-start padding-section border-b">
                            <div>
                                <p class="text-black text-xl">
                                    Table 1
                                </p>
                            </div>
                            <!-- <div class="flex gap-x-3">
                                
                                
                            </div> -->
                        </div>
                        <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 195px)">

                            <div class="padding-section border-b text-sm">
                                <div class=" grid grid-cols-10 gap-x-6 gap-y-5">
                                    <p class="text-black font-semibold col-span-4">
                                        Menu 
                                    </p>
                                    <div class="col-span-2"></div>
                                    <p class="text-black font-semibold col-span-3 text-right">
                                        Price
                                    </p>
                                    <div>
                                        
                                    </div>
                                    <div v-for="(menu,index) in selectedOrderList" class="contents" :key="menu">
                                        <button class=" col-span-4 text-sm text-right" @click="toggleStep">
                                            {{ menu.name }}
                                        </button>
                                        <div class=" col-span-2 text-center text-sm flex justify-between items-center">
                                            <button @click="minusOrder(menu,index)">
                                                <i class="fal fa-minus text-xs"></i>
                                            </button>
                                            <p>
                                                {{ menu.quantity }}
                                            </p>
                                            <button @click="plusOrder(menu,index)">
                                                <i class="fal fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                        <p class=" col-span-3 text-sm text-right">
                                            {{ (menu.price).toLocaleString() }}
                                        </p>
                                        <button @click="deleteOrder(index)">
                                            <i class="fal fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section">
                            <div class=" text-right pr-3 mb-3">
                                <p class="">
                                    Total
                                    
                                    {{ total }}
                                    MMKs
                                </p>
                            </div>
                            <div class="">
                                <button @click="btnClickedDoneSession()"
                                    class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
                                    Done Session
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            



        </div>
        <div v-else>

            <div class="w-full pt-9 px-6 ">
                <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                    <li v-for="(category, index) in menuCategoryList" :key="index" role="presentation" @click="menuCategoryChange(category)">
                        <a href="#tabs-profile" class="my-2 mr-3 text-white block  px-7 pb-2.5 rounded-full
                            pt-3 text-xs  hover:isolate bg-[#F0C094]
                            hover:bg-[#f7a559] focus:isolate data-[te-nav-active]:bg-[#F19E51]">
                            {{ category.name }}
                        </a>
                    </li>
                </ul>
                <div class="opacity-100 transition-opacity duration-150 ease-linear"
                    style="width:calc(100% - 410px)">
                    <div class="flex flex-wrap gap-x-4 gap-y-4">
                        <div v-for="(menu, menuIndex) in menuList" :key="menuIndex"
                            class=" flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
                            <button @click="btnClickedAddOrder(menu, menuIndex)"
                                class="relative flex flex-col justify-between h-full w-full">
                                <img src="../../../../../public/img/order1.png" alt="Menu Image" />
                                <div class="absolute bottom-0 w-full flex justify-end">
                                    <p class="text-base text-black font-semibold">
                                        {{ menu.name }}
                                    </p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

               
            </div>

            <div class="fixed right-0 top-0 bottom-0 bg-white shadow-md ease-in-out duration-300 transition delay-100 pt-12 right-sidebar-2"
                :class="isExtra === true ? 'translate-x-0 opacity-100 w-[400px]' : 'translate-x-full opacity-0 w-0' ">
                <div class="relative h-full w-full">
                    <div class="relative h-full">
                        <div class="flex justify-start padding-section border-b">
                            <div>
                                <p class="text-black text-xl">
                                    is extra
                                </p>
                            </div>
                        </div>
                        <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 195px)">

                            <div class="padding-section border-b text-sm">
                                <div class=" grid grid-cols-10 gap-x-6 gap-y-5">
                                    
                                </div>
                            </div>
                            
                        </div>

                        <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section">
                            <div class=" text-right pr-3 mb-3">
                                <p class="">
                                    Total
                                    
                                    MMKs
                                </p>
                            </div>
                            <div class="">
                                <button @click="btnClickedDoneSession()"
                                    class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
                                    Done Session
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        



    </div>

    
</template>
<script>
    import { Modal, Ripple, Select, Datepicker, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import { getCurrentTime, getCurretDateTime } from "../../../utilities/datetime-helpers";
    import Multiselect from 'vue-multiselect';

    export default {
        name:'PosOrderComponent',
        // props:{
        //     tableAreaId:{
        //         type: Number,
        //         required: true
        //     },
        // },
        // emits: ['callParent'],
        components:{
            Multiselect
        },
        data() {
            return {
                isExtra: false,

                menuCategoryList: [],
                menuList: [],
                selectedOrderList: [],

                selectedMenuCategory: null,
                selectedMenu: null,

                total: 0,

                extraCategoryList: [],

                selectedExtraCategory: null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getMenuCategoryList() {
                const response = await getApiData({ url: '/api/menu_categories', token: this.getToken() });
                if (response.data) {
                    this.menuCategoryList = response.data;
                    this.selectedMenuCategory = response.data[0].id;
                    this.getInitMenuList(response.data[0].id)
                }
            },
            async getInitMenuList(id) {
                console.log(id)
                const response = await getApiData({ url: '/api/menu_categories/' + id + '/menus', token: this.getToken() });
                if (response.data) {
                    this.menuList = response.data;
                }
            },
            menuCategoryChange(category){
                this.selectedMenuCategory = category
                this.getMenuList(category);
            },
            async getMenuList(category) {
                const response = await getApiData({ url: '/api/menu_categories/' + category.id + '/menus', token: this.getToken() });
                if (response.data) {
                    this.menuList = response.data;
                }
            },
            btnClickedAddOrder(menu){
                this.selectedMenu = menu
                let index = this.selectedOrderList.findIndex(item => item.id === menu.id);
                if (index != -1) {
                    this.selectedOrderList[index].quantity += 1;
                    this.selectedOrderList[index].price += menu.prices[0].price;
                }
                else{
                    this.selectedOrderList.push({
                        id: menu.id,
                        name: menu.name,
                        code: menu.code,
                        quantity: 1,
                        price: menu.prices[0].price,
                        unit_price: menu.prices[0].price
                    })
                }
                this.getTotalAmount();
            },
            plusOrder(menu,index){
                this.selectedOrderList[index].quantity += 1;
                this.selectedOrderList[index].price += menu.unit_price;
                this.getTotalAmount();
            },
            minusOrder(menu,index){
                if(this.selectedOrderList[index].quantity === 1){
                    this.selectedOrderList.splice(index, 1);
                }
                else{
                    this.selectedOrderList[index].quantity -= 1;
                    this.selectedOrderList[index].price -= menu.unit_price;
                    this.getTotalAmount();
                }
            },
            deleteOrder(index){
                this.selectedOrderList.splice(index, 1);
                this.getTotalAmount();
            },
            getTotalAmount() {
                this.total = this.selectedOrderList.reduce((total, item) => {
                    const amount = Number(item.price) || 0;
                    return total + amount;
                }, 0);
            },


            toggleStep(){
                this.isExtra = !this.isExtra;
            },

            // extra
            async getExtraCategoryList() {
                const response = await getApiData({ url: '/api/pos/selling_extra_categories', token: this.getToken() });
                if (response.data) {
                    this.extraCategoryList = response.data;
                    // this.selectedExtraCategory = response.data[0].id;
                    // this.getInitMenuList(response.data[0].id)
                }
            },

            showToastMessage(message, type="warn", title="Input Validation") {
                this.$notify({
                    title: `${title}`,
                    text: `${message}`,
                    type: `${type}`
                });
            },

        },

        watch: {
            
        },
        computed: {
            
        },
        created(){
            this.getMenuCategoryList();
            this.getExtraCategoryList();

        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
