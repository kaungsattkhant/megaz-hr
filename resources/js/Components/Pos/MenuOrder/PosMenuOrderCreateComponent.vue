<template>
    <div>
        <notifications position="top center" />
        <div class="">
            <div class="w-full pt-9 px-6">

                <div class="bg-white rounded w-full mx-auto mb-6">
                    <div class="relative  pt-6 px-8 mb-4">
                        <p class="text-xl w-full text-left">
                            Create Booking
                        </p>
                    </div>
                    <div class="grid grid-cols-12 gap-x-8 relative px-8 py-4">
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Date
                            </label>
                            <input type="datetime-local" placeholder="Time" v-model="slectedDate"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Customer Name
                            </label>
                            <select name="" id="" v-model="selectedCustomer"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(customer,index) in customerList" :value="customer" :key="index"> {{ customer.name }} </option>
                            </select>
                        </div>
                        <div class="col-span-6">

                        </div>
                        
                       
                        <!-- <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Area
                            </label>
                            <select name="" id="" v-model="selectedArea"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(area,index) in areaList" :value="area" :key="index">{{ area.name }} </option>
                            </select>
                        </div> -->
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Menu Category
                            </label>
                            <select name="" id="" v-model="selectedMenuCategory" @change="menuCategorySelectChanged()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(menuCategory,index) in menuCategoryList" :value="menuCategory" :key="index">{{ menuCategory.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Menu
                            </label>
                            <select name="" id="" v-model="selectedMenu"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(menu,index) in menuList" :value="menu" :key="index">{{ menu.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Quantity
                            </label>
                            <input type="text" placeholder="Quantity" v-model="selectedQuantity"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                &nbsp;
                            </label>
                            <button @click="btnClickedAddMenu()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                                Add
                            </button>
                        </div>
                    </div>
                    
                </div>
                <div class="bg-white rounded w-full mx-auto">
                    <div class="relative px-8 py-4">
                        <div class="table-container pr-16">
                            <table class=" w-full">
                                <thead class="">
                                    <tr class="border-b">
                                        <th scope="col" class=" text-left py-4">
                                            Menu Category
                                        </th>
                                        <th scope="col" class="text-left   py-4">
                                            Menu
                                        </th>
                                        <th scope="col" class="text-left   py-4">
                                            Count
                                        </th>
                                        <th scope="col" class="text-left   py-4">
                                            Price
                                        </th>
                                        <th scope="col" class="text-left   py-4">
            
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="" v-for="(om,index) in orderMenuList">
                                        <td class="text-left py-4 text-sm">
                                            {{ om.menu_category_name }}
                                        </td>
                                        <td class=" py-4 text-sm  ">
                                            {{ om.name }}
                                        </td>
                                        <td class=" py-4 text-sm  ">
                                            {{ om.quantity }}
                                        </td>
                                        <td class=" py-4 text-sm  ">
                                            {{ (om.price - om.discount_value ) * om.quantity }}

                                        </td>
                                        <td class=" py-4 text-sm  text-center">
                                            <!-- <button :disabled="!om.is_changeable" :title="!om.is_changeable ? 'Can not Change Selected Package Menu' : '' "
                                                @click="removeMenuBtnClicked(index)">
                                                <i class="fal fa-times  pr-3"></i>
                                            </button> -->
                                            <button @click="removeMenuBtnClicked(index)">
                                                <i class="fal fa-times  pr-3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="border-t">
                                        <td colspan="2"></td>
                                        <td class="py-4 border-b">
                                            Food
                                        </td>
                                        <td colspan="2" class="py-4 border-b">
                                            {{ food_total }}
                                        </td>
                                    </tr>
                                    <tr class="" v-if="type == 'package'">
                                        <td colspan="2"></td>
                                        <td class="py-4 border-b">
                                            Package Discount
                                        </td>
                                        <td colspan="2" class="py-4 border-b">
                                            {{  package_discount }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td colspan="2"></td>
                                        <td class="py-4 border-b">
                                            Total
                                        </td>
                                        <td colspan="2" class="py-4 border-b">
                                            {{  total }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-center px-12 pb-8">
                        <button @click="btnClickedCreateBooking()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Create
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
    import { getCurrentTime, getCurretDateTime } from '../../../utilities/datetime-helpers';
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                customerList:[],
                areaList:[],
                menuCategoryList:[],
                menuList:[],


                selectedCustomer:null,
                selectedDate:null,
                selectedArea:null,
                selectedMenuCategory:null,
                selectedMenu:null,
                selectedQuantity:null,
                is_menu_discount:null,


                orderMenuList:[],

                currentTime: getCurretDateTime(),
            };
        },

        methods: {
            ...mapGetters(['getToken']),
            async getCustomerList() {
                const response = await getApiData({ url: '/api/customers', token: this.getToken() });
                if (response.data) {
                    this.customerList = response.data;
                }
            },


            async getMenuCategoryList(){
                let url = `/api/menu_categories`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.menuCategoryList = response.data;
                }
            },
            async menuCategorySelectChanged(){
                console.log('menu category selected');
                let url = `/api/menu_categories_bookings/${this.selectedMenuCategory.id}/menus?date=${this.startTime}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.menuList = response.data.data;
                }
            },



            btnClickedAddMenu(){
                this.addMenu();
                
            },
            addMenu(){
                if(this.selectedMenu.menu_service_discounts.length > 0){
                    this.is_menu_discount = this.selectedMenu.menu_service_discounts[0].discount_price
                }
                else{
                    this.is_menu_discount = 0
                }
                this.orderMenuList.push({
                    quantity: this.selectedQuantity,
                    name: this.selectedMenu.name,
                    menu_category_name: this.selectedMenuCategory.name,
                    price:this.selectedMenu.prices[0].price,
                    menu_id : this.selectedMenu.prices[0].menu_id,
                    discount_value: this.is_menu_discount, //0 if package menu
                    is_package : 0,
                });
                this.food_total += (this.selectedMenu.prices[0].price - this.is_menu_discount) * this.menuCount;
                console.log(this.food_total)
                this.total += (this.selectedMenu.prices[0].price - this.is_menu_discount) * this.menuCount;
                this.selectedQuantity = null;
                this.selectedMenu = null;
                this.selectedMenuCategory = null;
                this.menuList = []
                
            },
            removeMenuBtnClicked(index){
                this.orderMenuList.splice(index, 1);
            },


            async btnClickedCreateOrder(){
                this.filterMenuForData();
                let formData = new FormData();
                formData.append('customer_id', this.selectedCustomer.id);
                formData.append('total_price', this.total);
                formData.append('total_discount_price', this.total_discount);
                formData.append('food_order_items', JSON.stringify(this.orderMenuList));

                let response = await postApiData({ url: '/api/order', form_data: formData, token: this.getToken() });
                if (response.success) {
                    window.location.replace('/menuorder');
                }
                else {
                    console.log('some errors occur');
                    this.$notify({
                        title: `Not valid`,
                        text: response.message,
                        type: "warn"
                    });
                }
            },
            filterMenuForData(){
                this.orderMenuList.forEach(om => {
                    delete om.name;
                    delete om.menu_category_name;
                });
            },
        },
        created(){
            this.getCustomerList();
            this.getMenuCategoryList();
        },
        mounted()
        {   
            
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
