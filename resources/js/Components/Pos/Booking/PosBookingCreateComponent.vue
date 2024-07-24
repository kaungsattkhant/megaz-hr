<template>
    <div>

        <div class="">
            <div class="w-full pt-9 px-6">

                <div class="bg-white rounded w-full mx-auto">
                    <div class="relative  pt-6 px-8 mb-4">
                        <p class="text-xl w-full text-left">
                            Create Booking
                        </p>
                    </div>
                    <div class="grid grid-cols-12 gap-x-8 relative px-8 py-4">
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Customer Name
                            </label>
                            <select name="" id="" v-model="selectedCustomer"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(customer,index) in customerList" :value="customer" :key="index"> {{ customer.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Time
                            </label>
                            <input type="datetime-local" placeholder="Time" v-model="startTime" @change="getPackageList(startTime)"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Type
                            </label>
                            <select name="" id="" v-model="type" @change="typeChange()" :class="startTime == null ? '!bg-[#0001]' : 'bg-transparent'" :disabled="startTime == null"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="package"> Package </option>
                                <option value="session"> Session </option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3" v-if="isPackage">
                            <label for="" class="block text-sm text-black mb-3">
                                Package
                            </label>
                            <select name="" id="" v-model="selectedPackage" @change="selectedPackageChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0" >
                                <option v-if="packageList.length > 0" v-for="(pack,index) in packageList" :value="pack" :key="index">{{ pack.name }} </option>
                                <option v-else>no Data Found</option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3" v-if="type == 'session'">
                            <label for="" class="block text-sm text-black mb-3">
                                Session Duration
                            </label>
                            <input type="text" placeholder="Duration" v-model="session_duration" @change="sessionDurationChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        
                        <div v-if="!isPackage && type != 'session'" class="col-span-3"></div>
                        
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Room
                            </label>
                            <select name="" id="" v-model="selectedRoom" @change="selectedRoomChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(room,index) in roomList" :value="room" :key="index">{{ room.name }} </option>
                            </select>
                        </div>
                        
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Deposit
                            </label>
                            <input type="number" placeholder="Deposit" v-model="deposit"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="col-span-6"></div>


                        <div class="mb-6 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Male
                            </label>
                            <input type="number" placeholder="Male" v-model="male"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-6 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Female
                            </label>
                            <input type="number" placeholder="Female" v-model="female"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-6 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Children
                            </label>
                            <input type="number" placeholder="Children" v-model="children"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div><div class="col-span-3"></div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Menu Category
                            </label>
                            <!-- <input type="text" placeholder="Menu Category"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
                            <select name="" id="" v-model="selectedMenuCategory" @change="menuCategorySelectChanged()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(menuCategory,index) in menuCategoryList" :value="menuCategory" :key="index">{{ menuCategory.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Menu
                            </label>
                            <!-- <input type="text" placeholder="Menu"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
                            <select name="" id="" v-model="selectedMenu"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(menu,index) in menuList" :value="menu" :key="index">{{ menu.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Count
                            </label>
                            <input type="number" placeholder="Count" v-model="menuCount"
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
                                            {{ om.price }}
                                        </td>
                                        <td class=" py-4 text-sm  text-center">
                                            <button :disabled="!om.is_changeable" :title="!om.is_changeable ? 'Can not Change Selected Package Menu' : '' "
                                                @click="removeMenuBtnClicked(index)">
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
                selectedCustomer:null,
                type:null,
                packageList:[],
                selectedPackage:null,
                session_duration:0,
                selectedRoomPrice:0,
                roomList:[],
                selectedRoom:null,
                startTime:null,
                deposit:null,
                male:null,
                female:null,
                children:null,

                isPackage:false,
                orderMenuList:[],
                is_menu_discount:0,

                customerList: [],
                selectedMenuCategory:null,
                menuCategoryList:[],
                selectedMenu:null,
                menuList:[],
                menuCount:null,

                testList:[],
                total:0,
                food_total:0,
                package_food_total:0,
                package_total:0,
                package_discount:0,

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

            async getPackageList(time) {
                const response = await getApiData({ url: '/api/packages?date='+time, token: this.getToken() });
                if (response.data) {
                    this.packageList = response.data.data;
                }
            
            },
            typeChange(){
                this.package_discount = 0;
                if(this.type == 'package'){
                    // this.getPackageList(this.startTime)
                    // const response = await getApiData({ url: '/api/packages?date=' + this.startTime, token: this.getToken() });
                    // if (response.data) {
                    //     this.packageList = response.data.data;
                    // }
                    this.selectedPackage = null;
                    this.isPackage = true;
                }
                else{
                    this.isPackage = false;
                }
                this.selectedRoom = null;
                    this.orderMenuList = [];
                    this.total = 0;
                    this.food_total = 0 ;
            },
            async getRoomList() {
                const response = await getApiData({ url: '/api/areas/3/entities', token: this.getToken() });
                if (response.data) {
                    this.roomList = response.data;
                }
            },
            selectedPackageChange(){
                if(this.selectedPackage){
                    this.package_discount = this.selectedPackage.package_discount;
                }
                this.food_total = 0 ;
                this.orderMenuList = [];
                // this.roomList = this.selectedPackage.rooms;
                let is_changeable = false;
                if(this.selectedPackage.is_changeable == 1){
                    is_changeable = true;
                }
                else{
                    is_changeable = false
                }
                
                let menuOfselectedPackage = this.selectedPackage.menu_packages;
                this.package_total = this.selectedPackage.price;
                menuOfselectedPackage.forEach((packageMenu)=>{
                    let menuCategorySelected = this.menuCategoryList.findIndex(menuCategory => menuCategory.id == packageMenu.menu.menu_category_id)
                    let is_dis_menu_price = 0;
                    if(packageMenu.menu.menu_service_discounts.length>0){
                        is_dis_menu_price = packageMenu.menu.menu_service_discounts[0].discount_price;
                    }
                    else{
                        is_dis_menu_price = 0;
                    }
                    this.orderMenuList.push({
                        quantity : packageMenu.quantity,
                        name : packageMenu.menu.name + ' (Package)',
                        menu_category_name : this.menuCategoryList[menuCategorySelected].name,
                        price : packageMenu.menu.prices[0].price,
                        menu_id : packageMenu.menu_id,
                        discount_value: is_dis_menu_price,
                    });
                    
                    this.food_total += packageMenu.menu.prices[0].price * packageMenu.quantity;
                    this.package_food_total += packageMenu.menu.prices[0].price * packageMenu.quantity;
                    // this.total = this.selectedPackage.price;
                    let room_price = this.selectedPackage.pay_session * this.selectedPackage.session_price;
                    this.total = ( this.food_total + room_price ) - this.selectedPackage.package_discount;
                    


                    
                });
            },
            filterMenuForData(){
                this.orderMenuList.forEach(om => {
                    delete om.name;
                    delete om.menu_category_name;
                });
            },
            sessionDurationChange(){
                let roomPrice = this.session_duration * this.selectedRoom.price_per_hour
                this.selectedRoomPrice = roomPrice
                this.total += roomPrice

            },
            selectedRoomChange(){
                if(this.type == 'session'){
                    this.total = this.food_total
                    let roomPrice = this.session_duration * this.selectedRoom.price_per_hour
                    this.selectedRoomPrice = roomPrice;
                    this.total += roomPrice
                }
                else{

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
                    quantity: this.menuCount,
                    name: this.selectedMenu.name,
                    menu_category_name: this.selectedMenuCategory.name,
                    price:this.selectedMenu.prices[0].price,
                    menu_id : this.selectedMenu.prices[0].menu_id,
                    discount_value: this.is_menu_discount,
                });
                this.food_total += this.selectedMenu.prices[0].price * this.menuCount;
                console.log(this.food_total)
                this.total += this.selectedMenu.prices[0].price * this.menuCount;
                this.menuCount = null;
                this.selectedMenu = null;
                this.selectedMenuCategory = null;
                this.menuList = []
                
            },
            removeMenuBtnClicked(index){
                this.food_total -= this.orderMenuList[index].menu_price;
                this.total -= this.orderMenuList[index].menu_price;
                if(this.orderMenuList[index].is_package == 1){
                    this.package_total -= this.orderMenuList[index].menu_price;
                }
                // if(this.type == 'package'){
                //     let checkPackagePrice = this.selectedPackage.price + this.food;
                //     console.log(checkPackagePrice < this.selectedPackage.price)
                //     if(checkPackagePrice < this.selectedPackage.price){
                //         alert('u cant')
                //     }
                // }

                this.orderMenuList.splice(index, 1);
                
            },


            async btnClickedCreateBooking(){
                this.filterMenuForData();
                let formData = new FormData();
                formData.append('customer_id', this.selectedCustomer.id);
                formData.append('male', this.male);
                formData.append('female', this.female);
                formData.append('child', this.children);
                formData.append('session_type', this.type);
                if(this.type == 'session'){
                    formData.append('session', this.session_duration);
                }
                else{
                    formData.append('session', this.selectedPackage.session);
                }
                formData.append('amount', this.total);
                formData.append('deposit', this.deposit);
                formData.append('start_date', this.startTime);
                formData.append('entity_id', this.selectedRoom.id);
                if(this.type == 'package'){
                    formData.append('package_id', this.selectedPackage.id);
                }
                formData.append('menus', JSON.stringify(this.orderMenuList));
                formData.append('package_price', this.package_total);

                if(this.type == 'package'){
                    if(this.total < this.selectedPackage.price){
                        alert('total is lower than package pricce')
                    }
                    else{
                        let response = await postApiData({ url: '/api/bookings', form_data: formData, token: this.getToken() });
                        if (response.success) {
                            window.location.replace('/booking');
                        }
                        else {
                            console.log('some errors occur');
                        }
                    }
                }
                else{
                    let response = await postApiData({ url: '/api/bookings', form_data: formData, token: this.getToken() });
                    if (response.success) {
                        window.location.replace('/booking');
                    }
                    else {
                        console.log('some errors occur');
                    }
                }

                
            }
        
        
        },
        created(){
            this.getCustomerList();
            this.getPackageList(this.currentTime);
            this.getRoomList();
            this.getMenuCategoryList();
        },
        mounted()
        {   
            
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
