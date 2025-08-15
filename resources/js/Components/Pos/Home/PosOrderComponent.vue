<template>
    <div class="">
        <!-- {{ selectedOrderList }} -->
        <div class="mb-6" v-show="!isExtra">
            <div class="w-full pt-9 px-6 ">
                <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                    <li v-for="(category, index) in menuCategoryList" :key="index" role="presentation">
                        <button class="my-2 mr-3 text-white block  px-7 pb-2.5 rounded-full
                            pt-3 text-xs  hover:isolate  focus:isolate" @click="menuCategoryChange(category)"
                            :class="category?.id === this.selectedMenuCategory ? 'bg-[#D45E5E]' : 'bg-[#c4c4c4]'">
                            {{ category.name }}
                        </button>
                    </li>
                </ul>
                <div class="opacity-100 transition-opacity duration-150 ease-linear"
                    style="width:calc(100% - 410px)">
                    <div class="flex flex-wrap gap-x-8 gap-y-8">
                        <div v-for="(menu, menuIndex) in menuList" :key="menuIndex"
                            class=" flex-shrink-0 flex-grow w-40 max-w-44 h-fit bg-white rounded-2xl">
                            <button @click="btnClickedAddOrder(menu, menuIndex)"
                                class="relative h-full w-full ">
                                <img :src="menu.image_url" alt="Menu Image" />
                                <div class="text-left px-4 py-3 rounded-2xl">
                                    <p class="text-base text-black font-semibold">
                                        {{ menu.name }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ menu.prices[0].price }}
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
                                    {{ detail?.name }}
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
                                        <button class=" col-span-4 text-sm text-left" @click="toggleStep(menu,index)">
                                            {{ menu.name }}
                                        </button>
                                        <div class=" col-span-2 text-center text-sm flex justify-between items-center">
                                            <!-- <button @click="minusOrder(menu,index)">
                                                <i class="fal fa-minus text-xs"></i>
                                            </button> -->
                                            <p>
                                                {{ menu.quantity }}
                                            </p>
                                            <!-- <button @click="plusOrder(menu,index)">
                                                <i class="fal fa-plus text-xs"></i>
                                            </button> -->
                                        </div>
                                        <p class=" col-span-3 text-sm text-right">
                                            {{ (menu.price).toLocaleString() }} 
                                            <span v-if="getOrderTotalExtraPrice(menu) > 0">
                                                ({{ getOrderTotalExtraPrice(menu).toLocaleString() }})
                                            </span>
                                            
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
                                    Extras: 
                                    
                                    {{ extraTotal }}
                                    MMKs
                                </p>
                            </div>
                            <div class=" text-right pr-3 mb-3">
                                <p class="">
                                    Total:
                                    
                                    {{ total + extraTotal }}
                                    MMKs
                                </p>
                            </div>
                            <div class="">
                                <button @click="btnClickedAddMenuOrder()"
                                    class="bg-[#D45E5E] text-white text-center text-sm font-semibold w-full py-3">
                                    Order 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="isExtra">
            <div class="w-full pt-9 px-6 h-[100vh] pb-16">
                <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                    <li v-for="(category, index) in extraCategoryList" :key="index" role="presentation" @click="extraCategoryChange(category)">
                        <a href="#tabs-profile" class="my-2 mr-3 text-white block  px-7 pb-2.5 rounded-full
                            pt-3 text-xs  hover:isolate
                            hover:bg-[#D45E5E] focus:isolate data-[te-nav-active]:bg-[#D45E5E]"
                            :class="category?.id === this.selectedExtraCategory ? 'bg-[#D45E5E]' : 'bg-[#c4c4c4]'">
                            {{ category.name }}
                        </a>
                    </li>
                </ul>
                <div class="opacity-100 transition-opacity duration-150 ease-linear"
                    style="width:calc(100% - 10px)">
                    
                    <div class="flex flex-wrap gap-x-4 gap-y-4">

                        <label v-for="(extra, extraIndex) in extraList"
                            :key="extra.id" class="block w-64 h-max">
                            <input type="checkbox" :id="'extra' + extraIndex" :checked="selectedExtras.some(e => e.id === extra.id)"
                                :value="extra" v-model="selectedExtras" class="peer hidden"/>

                            <div class="bg-white  transition-colors rounded-2xl shadow p-4 flex flex-col justify-between peer-checked:bg-blue-200"
                                >
                                <div class="text-lg font-medium text-gray-800 text-left">
                                    {{ extra.item.name }}
                                </div>
                                <div class="text-3xl font-semibold text-black text-right">
                                    {{ extra.price }}
                                </div>
                            </div>
                        </label>
                        
                    </div>
                </div>
                <div class=" fixed bottom-0 pb-4 right-0 w-[410px] bg-white px-6 pt-6 rounded">
                    <div class="flex items-center justify-center mb-4 bg-white">
                        <!-- <div class="w-auto flex-grow">
                            <multiselect v-model="selectedRemark" :options="remarkList"
                                :close-on-select="true" class=" h-10" :clear-on-select="false"
                                :preserve-search="true" placeholder="Remark" label="name" track-by="id"
                                :preselect-first="false"></multiselect>
                        </div> -->
                        <div class=" !text-sm w-auto flex-grow" data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Remark"
                                data-te-select-filter="true" name="" id="" v-model="selectedRemark" class="input-ui !rounded-tr-none !rounded-br-none">
                                <option :value="remark" v-for="(remark, remarkIndex) in remarkList"
                                    :key="remarkIndex"> {{ remark.name }} </option>
                            </select>
                        </div>
                        <div class="w-12 text-center h-full text-lg">
                            <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0 py-1"
                                data-te-toggle="modal" data-te-target="#add_remark_modal" @click="addNewRemarkBtnClicked">
                                <i class="far fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button @click="btnClikcedAddExtra()"
                        class="bg-[#91D45E] text-black text-center text-sm font-semibold w-full py-3 px-4">
                        Proceed
                    </button>
                </div>

               
            </div>

            <!-- <div class="fixed right-0 top-0 bottom-0 bg-white shadow-md ease-in-out duration-300 transition delay-100 pt-12 right-sidebar-2"
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
            </div> -->


        </div>

        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_remark_modal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            New Remark
                        </p>
                        <button type="button" id="closeAddPackageMenuTableModal"
                            class="absolute top-4 right-4 focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-16 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <input type="text" placeholder="Remark" v-model="newRemarkText" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="addNewRemark()" class="pos-add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                            Add Remark
                        </button>
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
        props: ['orderId'],
        data() {
            return {
                isExtra: false,

                menuCategoryList: [],
                menuList: [],
                selectedOrderList: [],

                selectedMenuCategory: null,
                selectedMenu: null,

                total: 0,
                extraTotal: 0,

                extraCategoryList: [],
                extraList: [],

                selectedExtraCategory: null,
                selectedExtras: [],
                remarkList: [],

                selectedMenuForExtra: null,
                selectedMenuForExtraIndex: null,
                selectedRemark: null,
                newRemarkText: null,

                detail: null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),
            async getOrderDetail() {
                const response = await getApiData({ url: '/api/entities/' + this.orderId, token: this.getToken() });
                if (response.data) {
                    this.detail = response.data;
                    this.invoice_id = response.data.invoice.invoice_id;
                    this.area_id = response.data.area_id;
                    this.getMenuCategoryList();
                }
            },
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
                console.log(this.detail.area_id)
                if(this.detail.area_id){
                    const response = await getApiData({ url: '/api/menu_categories/' + id + '/menus?selling_area_id=' + this.detail.area_id, token: this.getToken() });
                    if (response.data) {
                        this.menuList = response.data;
                    }
                }
                
            },
            menuCategoryChange(category){
                this.selectedMenuCategory = category.id
                this.getMenuList(category);
            },
            async getMenuList(category) {
                const response = await getApiData({ url: '/api/menu_categories/' + category.id + '/menus?selling_area_id=' + this.detail.area_id, token: this.getToken() });
                if (response.data) {
                    this.menuList = response.data;
                }
            },
            btnClickedAddOrder(menu){
                this.selectedMenu = menu
                // let index = this.selectedOrderList.findIndex(item => item.menu_id === menu.id);
                // if (index != -1) {
                //     this.selectedOrderList[index].quantity += 1;
                //     this.selectedOrderList[index].price += menu.prices[0].price;
                // }
                // else{
                //     this.selectedOrderList.push({
                //         menu_id: menu.id,
                //         name: menu.name,
                //         code: menu.code,
                //         quantity: 1,
                //         price: menu.prices[0].price,
                //         unit_price: menu.prices[0].price,
                //         original_price: menu.prices[0].price,
                //         menu_category_id: this.selectedMenuCategory,
                //         cooking_area_id: menu.cooking_area_id,
                //         is_package: 0,
                //     })
                // }
                this.selectedOrderList.push({
                    menu_id: menu.id,
                    name: menu.name,
                    code: menu.code,
                    quantity: 1,
                    price: menu.prices[0].price,
                    unit_price: menu.prices[0].price,
                    original_price: menu.prices[0].price,
                    menu_category_id: this.selectedMenuCategory,
                    cooking_area_id: menu.cooking_area_id,
                    is_package: 0,
                })
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
                    this.getTotalAmount();
                    this.getTotalExtraPrice();
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
                this.getTotalExtraPrice();
            },
            getTotalAmount() {
                this.total = this.selectedOrderList.reduce((total, item) => {
                    const amount = Number(item.price) || 0;
                    return total + amount;
                }, 0);
            },


            toggleStep(menu,index){
                this.isExtra = !this.isExtra;
                // if(this.isExtra){
                //     this.getExtraCategoryList();
                // }
                this.selectedMenuForExtra = menu;
                this.selectedMenuForExtraIndex = index;
                if(this.selectedOrderList[index].extras){
                    // this.selectedOrderList[index].extras.forEach(item => {
                    //     this.selectedExtras.push(item.id)
                    // })
                    this.selectedExtras = this.selectedOrderList[index].extras
                }
                else{
                    this.selectedExtras = [];
                }
            },

            // extra
            getRemarks(){
                getApiData({url: `/api/remarks`, token: this.getToken()})
                .then((response)=>{
                    if(response.success){
                        this.remarkList = response.data;
                    }
                });
            },
            addNewRemarkBtnClicked(){
                this.newRemarkText = null;
            },
            addNewRemark(){
                if(!this.newRemarkText){
                    this.showToastMessage('Please provide remark');
                    return;
                }
                let formData = new FormData();
                formData.append('name',this.newRemarkText);
                postApiData({url: `/api/remarks`, form_data: formData, token: this.getToken()})
                .then((response)=>{
                    if(response.success){
                        this.showToastMessage('New remark created', 'success', 'Success');
                        // this.selectedRemark = response.data;
                        this.newRemarkText = null;
                        this.remarkList.push(response.data);
                        // this.getRemarks();
                    }
                });
            },
            async getExtraCategoryList() {
                const response = await getApiData({ url: '/api/pos/selling_extra_categories', token: this.getToken() });
                if (response.data) {
                    this.extraCategoryList = response.data;
                    if(response.data[0].id){
                        this.selectedExtraCategory = response.data[0].id;
                    }
                    if(response.data[0].extras){
                        this.extraList = response.data[0].extras;
                    }
                    // this.getInitMenuList(response.data[0].id)
                }
            },
            extraCategoryChange(category){
                this.extraList = category.extras;
            },
            btnClikcedAddExtra(){
                let selling_extra_id = [];
                let extras = [];
                let extra_price = 0;
                let extra_origin_price = 0;
                let allTotal = this.total;

                this.selectedExtras.forEach(item => {
                    extra_price += item.price;
                    extra_origin_price += item.price;
                    selling_extra_id.push(item.id);
                    extras.push(item);
                })
                this.selectedOrderList[this.selectedMenuForExtraIndex].selling_extra_id = selling_extra_id;
                this.selectedOrderList[this.selectedMenuForExtraIndex].extras = extras;
                this.selectedOrderList[this.selectedMenuForExtraIndex].extra_price = extra_price;
                this.selectedOrderList[this.selectedMenuForExtraIndex].extra_origin_price = extra_origin_price;
                if(this.selectedRemark){
                    this.selectedOrderList[this.selectedMenuForExtraIndex].remark_id = this.selectedRemark.id;
                }
                // this.selectedOrderList[this.selectedMenuForExtraIndex].push({
                //     extra_price: this.selectedExtras.price,
                //     extra_origin_price: this.selectedExtras.price
                // })
                // this.isExtra = !this.isExtra;

                // let allTotal = this.total - extra_origin_price + extra_price


                this.isExtra = !this.isExtra;
                this.getTotalExtraPrice();
            },
            getTotalExtraPrice() {
                this.extraTotal = this.selectedOrderList.reduce((extraTotal, item) => {
                    const amount = Number(item.extra_price) || 0;
                    return extraTotal + amount;
                }, 0);
            },
            getOrderTotalExtraPrice(order) {
                if (!order.extras || !Array.isArray(order.extras)) return 0;

                    return order.extras.reduce((total, extra) => {
                        return total + (extra.price || 0);
                }, 0);
            },

            btnClickedAddMenuOrder(){
                this.addMenu();
            },
            async addMenu(){
            let formData = new FormData();
            // if(this.selectedOrderList.length > 0){
            //    this.selectedOrderList.forEach(item => {
            //        delete item.extras
            //    });
            // }
            formData.append('menuArray', JSON.stringify(this.selectedOrderList))
            formData.append('invoice_id', this.invoice_id);
            formData.append('selling_area_id', this.area_id);
            let response = await postApiData({ url: '/api/entities/orders', form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace('/pos/home');
            }
            else {
                let message = `Some errors occured`;
                if (response.message) {
                    message = response.message;
                }
                this.$notify({
                    text: message,
                    type: "error"
                });
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
            this.getOrderDetail();
            // this.getMenuCategoryList();
            this.getExtraCategoryList();
            this.getRemarks();
        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
