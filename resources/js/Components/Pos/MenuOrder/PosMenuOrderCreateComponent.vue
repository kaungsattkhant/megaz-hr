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
                            <input type="datetime-local" placeholder="Time" v-model="slectedDate" ref="date"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Customer Name
                            </label>
                            <select name="" id="" v-model="selectedCustomer" ref="customer"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(customer,index) in customerList" :value="customer" :key="index"> {{ customer.name }} </option>
                            </select>
                        </div>
                        <div class="col-span-6">
                            <label for="" class="block text-sm text-black mb-3">
                                &nbsp;
                            </label>
                            <button
                                class=" transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                data-te-toggle="modal" data-te-target="#create_customer_modal">
                                <i class="fal fa-plus"></i>
                            </button>
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
                            <select name="" id="" v-model="selectedMenuCategory" @change="menuCategorySelectChanged()" ref="menuCategory"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(menuCategory,index) in menuCategoryList" :value="menuCategory" :key="index">{{ menuCategory.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Menu
                            </label>
                            <select name="" id="" v-model="selectedMenu" @change="menuSelectChanged()" ref="menu"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(menu,index) in menuList" :value="menu" :key="index">{{ menu.name }} </option>
                            </select>
                        </div><div class="col-span-6"></div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Area
                            </label>
                            <select name="" id="" v-model="selectedArea" ref="area"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="(area,index) in areaList" :value="area" :key="index">{{ area.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Quantity
                            </label>
                            <input type="text" placeholder="Quantity" v-model="selectedQuantity" ref="quantity"
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

                <div class="bg-white rounded w-full mx-auto" ref="menuListInUi">
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
                                            Discount
                                        </th>
                                        <th scope="col" class="text-left   py-4">
                                            Price
                                        </th>
                                        <th scope="col" class="text-left   py-4">
                                            Count
                                        </th>
                                        <th scope="col" class="text-left   py-4">
                                            Total Price
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
                                            {{ om.discount_price }}

                                        </td>
                                        <td class=" py-4 text-sm  ">
                                            {{ om.original_price }}

                                        </td>
                                        <td class=" py-4 text-sm  ">
                                            {{ om.quantity }}
                                        </td>
                                        <td class=" py-4 text-sm  ">
                                            {{ ( om.original_price - om.discount_price ) * om.quantity }}
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
                                        <td colspan="4"></td>
                                        <td class="py-4 border-b">
                                            Food
                                        </td>
                                        <td colspan="2" class="py-4 border-b">
                                            {{ food_total }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td colspan="4"></td>
                                        <td class="py-4 border-b">
                                            Total
                                        </td>
                                        <td colspan="2" class="py-4 border-b">
                                            {{  food_total - discount_total }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-center px-12 pb-8">
                        <button @click="btnClickedCreateOrder()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>

        </div>


        


        <!-- Create Customer modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_customer_modal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Create Customer
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none
                        " id="closeCustomerModal" data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-16 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Customer Name
                            </label>
                            <input type="text" placeholder="Customer Name" v-model="name" ref="name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Phone Number
                            </label>
                            <input type="text" placeholder="Phone Number" v-model="ph_number" ref="phone"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Email
                            </label>
                            <input type="text" placeholder="Email" v-model="email" ref="email"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Gender
                            </label>
                            <select name="" id="" v-model="selectedGender" ref="gender"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="gender.id" v-for="(gender, genderIndex) in genderList"
                                    :key="genderIndex"> {{ gender.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Birthdate
                            </label>
                            <input type="date" placeholder="Birthdate" v-model="date" ref="date"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class=" mb-4">
                            <label for="" class="text-sm text-black mb-2 block">
                                Division
                            </label>
                            <select
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                v-model="selectedDivision" @change="divisionSelectChanged"  ref="division">
                                <option class="text-sm" :value="division"
                                    v-for="(division, divisionIndex) in divisionList" :key="divisionIndex">
                                    {{ division.name }}
                                </option>

                            </select>
                        </div>

                        <div class=" mb-4">
                            <label for="" class="text-sm text-black mb-2 block">
                                Township
                            </label>
                            <select
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                v-model="selectedTownship" @change="townshipSelectChanged"  ref="township">
                                <option class="text-sm" :value="township" v-for="(township, index) in townshipList"
                                    :key="index">
                                    {{ township.name }}
                                </option>

                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Address Name
                            </label>
                            <input type="text" placeholder="Address Name" v-model="address_name" ref="address_name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Address
                            </label>
                            <textarea v-model="address" ref="address"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                name="" id="" cols="30" rows="10"></textarea>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="createCustomerBtnClicked" class="pos-add-btn focus:outline-none focus:ring-0 ">
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
                areaList:[],


                selectedCustomer:null,
                selectedDate:null,
                selectedArea:null,
                selectedMenuCategory:null,
                selectedMenu:null,
                selectedArea:null,
                selectedQuantity:null,
                is_menu_discount:null,


                orderMenuList:[],

                total:0,
                discount_total:0,
                food_total:0,
                sub_total:0,

                orderMenuListForCreate:[],

                genderList:[],
                divisionList:[],
                townshipList:[],
                name:null,
                ph_number:null,
                email:null,
                selectedGender:null,
                date:null,
                selectedDivision:null,
                selectedTownship:null,
                address_name:null,
                address:null,

                currentTime: getCurretDateTime(),


                addMenuFields: [
                    { label: 'Menu Category',ref: 'menuCategory' },
                    { label: 'Menu',ref: 'menu' },
                    { label: 'Area',ref: 'area' },
                    { label: 'Quantity',ref: 'quantity' },
                ],
                createOrderFields: [
                    { label: 'Date',ref: 'date' },
                    { label: 'Customer',ref: 'customer' },
                ],
                createCustomerFields: [
                    { label: 'Customer Name',ref: 'name' },
                    { label: 'Phone Number',ref: 'phone' },
                    { label: 'Email',ref: 'email' },
                    { label: 'Gender',ref: 'gender' },
                    { label: 'Date',ref: 'date' },
                    { label: 'Division',ref: 'division' },
                    { label: 'Township',ref: 'township' },
                    { label: 'Address Name',ref: 'address_name' },
                    { label: 'Address',ref: 'address' },
                ],
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
            menuCategorySelectChanged(){
                console.log('menu category selected');
                this.getMenuList();
            },
            async getMenuList(){
                let url = `/api/menu_categories/${this.selectedMenuCategory.id}/menus`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.menuList = response.data;
                    console.log(this.menuList)
                }
            },
            menuSelectChanged(){
                this.getAreaList();
            },
            async getAreaList(){
                let url = `/api/menus/${this.selectedMenu.id}/areas`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.areaList = response.data.areas;
                }
            },



            btnClickedAddMenu(){
                this.clearErrors();
                let isValid = true;
                const errors = [];
                this.addMenuFields.forEach((field) => {
                    const value = this.$refs[field.ref].value; 
                    if (!value) {
                        errors.push({
                            text: `${field.label} is required.`,
                            isActive: false,
                        });
                        isValid = false;
                    }
                    else{
                        errors.push({
                            text: `${field.label} is required.`,
                            isActive: true,
                        });
                        isValid = true;
                    }
                });
                if (!isValid) {
                    this.displayErrors(errors,this.addMenuFields);
                } else {
                    this.addMenu();
                }

                
                
            },
            addMenu(){
                let is_menu_dis = 0
                let is_menu_dis_id = null;
                console.log(this.sub_total)
                if(this.selectedMenu.menu_service_discounts.length > 0){
                    is_menu_dis = this.selectedMenu.menu_service_discounts[0].discount_price
                    is_menu_dis_id = this.selectedMenu.menu_service_discounts[0].discountable_id
                }
                else{
                    is_menu_dis = 0
                    is_menu_dis_id = null
                }
                this.food_total += this.selectedMenu.prices[0].price * this.selectedQuantity;
                this.total += this.selectedMenu.prices[0].price * this.selectedQuantity;
                this.discount_total += is_menu_dis * this.selectedQuantity;
                this.sub_total += (this.selectedMenu.prices[0].price - is_menu_dis) * this.selectedQuantity

                this.orderMenuList.push({
                    menu_id : this.selectedMenu.id,
                    quantity: this.selectedQuantity,
                    discount_price: is_menu_dis,
                    original_price:this.selectedMenu.prices[0].price,
                    menu_service_discount_id : is_menu_dis_id,
                    area_id: this.selectedArea.id,
                    name: this.selectedMenu.name,
                    menu_category_name: this.selectedMenuCategory.name,
                });
                this.selectedQuantity = null;
                this.selectedMenu = null;
                this.selectedMenuCategory = null;
                this.selectedArea = null;
                this.menuList = []
                
            },
            removeMenuBtnClicked(index){
                this.food_total -= this.orderMenuList[index].original_price * this.orderMenuList[index].quantity;
                this.total -= this.orderMenuList[index].original_price * this.orderMenuList[index].quantity;
                this.discount_total -= this.orderMenuList[index].discount_price * this.orderMenuList[index].quantity;
                this.sub_total -= (this.orderMenuList[index].original_price - this.orderMenuList[index].discount_price) * this.orderMenuList[index].quantity

                this.orderMenuList.splice(index, 1);
            },

            btnClickedCreateOrder(){
                this.clearErrors();
                let isValid = true;
                const errors = [];
                this.createOrderFields.forEach((field) => {
                    const value = this.$refs[field.ref].value; 
                    if (!value) {
                        errors.push({
                            text: `${field.label} is required.`,
                            isActive: false,
                        });
                        isValid = false;
                    }
                    else{
                        errors.push({
                            text: `${field.label} is required.`,
                            isActive: true,
                        });
                        isValid = true;
                    }
                });
                if (!isValid) {
                    this.displayErrors(errors,this.createOrderFields);
                }
                else if(this.orderMenuList.length < 1){
                    const errorElement = document.createElement('small');
                    errorElement.textContent = 'Menu List is empty';
                    errorElement.classList.add('error-message','text-red-600','block','w-full', 'text-center', 'mt-4' ,'text-lg');
                    const fieldRef = this.$refs.menuListInUi;
                    fieldRef.after(errorElement);
                }
                else {
                    this.createOrder()
                }
                
            },
            async createOrder(){
                this.filterMenuForData();
                let formData = new FormData();
                if(this.selectedCustomer){
                    formData.append('customer_id', this.selectedCustomer.id);
                    formData.append('customer_address_id', this.selectedCustomer.addresses[0].id);
                }
                
                // formData.append('delivery_charge', this.selectedCustomer.addresses[0].township.latest_delivery_charge);
                formData.append('delivery_charge', 0);
                formData.append('sub_total', this.sub_total);
                formData.append('total_discount_price', this.discount_total);
                formData.append('total_price', this.total);
                formData.append('food_order_items', JSON.stringify(this.orderMenuList));

                let response = await postApiData({ url: '/api/create_food_orders', form_data: formData, token: this.getToken() });
                if (response.success) {
                    window.location.replace('/food_orders');
                }
                else {
                    console.log('some errors occur');
                    this.$notify({
                        title: `Not valid`,
                        text: 'Invalid Data',
                        type: "warn"
                    });
                }
            },
            filterMenuForData(){
                this.orderMenuList.forEach( om => {
                    this.orderMenuListForCreate.push({
                        menu_id : om.menu_id,
                        quantity: om.quantity,
                        discount_price: om.discount_price,
                        original_price:om.original_price,
                        menu_service_discount_id : om.menu_service_discount_id,
                        area_id: om.area_id,
                    });
                })
                
                // this.orderMenuList.forEach(om => {
                //     delete om.name;
                //     delete om.menu_category_name;
                // });
            },




            // create customer
            async getGendersList() {
                const response = await getApiData({ url: '/api/genders', token: this.getToken() });
                if (response.data) {
                    this.genderList = response.data;
                }
            },
            async getDivisionList() {
                const response = await getApiData({ url: '/api/divisions', token: this.getToken() });
                if (response.data) {
                    this.divisionList = response.data;
                }
            },
            divisionSelectChanged() {
                this.getTownShipList();
            },
            async getTownShipList() {
                this.townshipList = this.divisionList.find(x => x.id === this.selectedDivision.id).townships;
            },

            createCustomerBtnClicked() {
                this.clearErrors();
                let isValid = true;
                const errors = [];
                this.createCustomerFields.forEach((field) => {
                    const value = this.$refs[field.ref].value; 
                    if (!value) {
                        errors.push({
                            text: `${field.label} is required.`,
                            isActive: false,
                        });
                        isValid = false;
                    }
                    else{
                        errors.push({
                            text: `${field.label} is required.`,
                            isActive: true,
                        });
                        isValid = true;
                    }
                });
                if (!isValid) {
                    this.displayErrors(errors,this.createCustomerFields);
                }
                else {
                    this.createCustomer();
                }
                
            },
            async createCustomer() {
                let formData = new FormData();
                formData.append('gender_id', this.selectedGender);
                formData.append('name', this.name);
                if (this.email != null) {
                    formData.append('email', this.email);
                }
                formData.append('phone_number', this.ph_number);
                formData.append('address_name', this.address_name);
                formData.append('address', this.address);
                formData.append('birthdate', this.date);
                formData.append('township_id', this.selectedTownship.id);
                let response = await postApiData({ url: '/api/customers', form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.customerList.push(response.data);
                    this.selectedCustomer = response.data;
                    console.log("success customer")
                    this.closeCustomerModal();
                    this.clearCustomerForm();
                }
                else {
                    console.log('some errors occur');
                }
            },
            clearCustomerForm() {
                this.name = null
                this.ph_number = null
                this.email = null
                this.selectedGender = null
                this.date = null
                this.selectedDivision = null
                this.selectedTownship = null
                this.address_name = null
                this.address = null
            },

            closeCustomerModal() {
                document.getElementById("closeCustomerModal").click();
            },







            validationMessage(text){
                this.$notify({
                    title: `Not valid`,
                    text: text,
                    type: "warn"
                });

            },


            // use in add menu
            displayErrors(errors,list) {
                errors.forEach((errorMessage, index) => {
                    const errorElement = document.createElement('small');
                    errorElement.textContent = errorMessage.text;
                    errorElement.classList.add('error-message','text-red-600');
                    const fieldRef = this.$refs[list[index].ref];
                    if (fieldRef && !errorMessage.isActive) {
                        fieldRef.after(errorElement);
                    }
                });
            },
            clearErrors() {
                const errorMessages = document.querySelectorAll('.error-message');
                errorMessages.forEach((el) => el.remove()); // Remove all error messages
            },
        },
        created(){
            this.getCustomerList();
            this.getMenuCategoryList();
            this.getGendersList();
            this.getDivisionList();
        },
        mounted()
        {   
            
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
