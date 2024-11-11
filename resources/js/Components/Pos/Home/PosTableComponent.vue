<template>
    <div class="">
        <div class="mb-6">
            <div class="opacity-100 transition-opacity duration-150 ease-linear" :style="isShowSidebar == true ? 'width:calc(100% - 410px)' : 'width:100%' ">
                <div class="flex flex-wrap gap-x-4 gap-y-4">
                    <div v-for="(room, roomIndex) in roomList" :key="roomIndex"
                        :class="room.is_active == 0 ? 'bg-[#55EFC4]' : 'bg-[#FF7675]'"
                        class=" flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
                        <button @click="btnClickedIsOpenRoom(room, roomIndex)"
                            class="relative flex flex-col justify-between h-full w-full">
                            <div v-if="room.is_active == 1" class=" flex justify-between flex-col h-full">
                                <!-- <div>
                                    <p class="text-sm text-white">Start Time : {{ room.room_sessions[0].start_date ? room.room_sessions[0].start_date.slice(11,16) : '' }} </p>
                                    <p class="text-sm text-white">Start Time : {{ room.room_sessions[0].end_date ? room.room_sessions[0].end_date.slice(11,16) : '' }} </p>
                                </div> -->
                                <p class="text-base text-left text-white">
                                    {{ room.price_per_hour }}
                                </p>
                            </div>
                            <div class="absolute bottom-0 w-full flex justify-end">
                                <p class="text-base text-white font-semibold">
                                    {{ room.name }}
                                </p>
                            </div>
                        </button>
                    </div>                            
                </div>
            </div>

            <div class="fixed right-0 top-0 bottom-0 bg-white shadow-md ease-in-out duration-300 transition delay-100 pt-12 right-sidebar-2" :class="isShowSidebar == true ? 'translate-x-0 opacity-100 w-[400px]' : 'translate-x-full opacity-0 w-0' ">
                <div class="relative h-full w-full">
                    <div class="fixed right-4 top-4 z-40" :class="isShowSidebar == true ? 'block' : 'hidden' ">
                        <button @click="isShowSidebar = false"><i class="far fa-times"></i></button>
                    </div>
                    <div class="relative h-full " v-if="isOpenRoom.open_1 == true"  id="open_room_1">
                        <div class="w-full h-full flex justify-center flex-col">
                            <div class="w-2/3 mx-auto">
                                <div class="text-center">
                                    <p class="mb-2 text-black font-semibold">
                                        <!-- {{ selectedRoom ? (selectedRoom.start_time).slice(0,5) : '' }} -->
                                          <!-- no start time in selected Room -->
                                    </p>
                                    <p class="mb-2 text-black font-semibold">
                                        {{ selectedRoom ? selectedRoom.name : '' }}
                                    </p>
                                    <p class="mb-4 text-black font-semibold">
                                        Price : {{ selectedRoom ? selectedRoom.price_per_hour.toLocaleString() : '' }} MMKs
                                    </p>
                                </div>
                                <img class="w-[60%] mx-auto mb-6" src="../../../../../public/img/Video_light.png" alt="">
                                <button @click="btnClickedOpenRoom"
                                    class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
                                    Open Room
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="relative h-full" v-if="isOpenRoom.open_2 == true" id="open_room_2">
                        <div class="small-scrollbar overflow-y-auto h-[100vh] pt-8">
                            <div class="padding-section w-2/3 mx-auto ">

                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Customer Name
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="selectedCustomer"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                            <option :value="customer" v-for="(customer, customerIndex) in customerList"
                                                :key="customerIndex"> {{ customer.name }} </option>
                                        </select>
                                        <button
                                            class="absolute -right-6 transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                            data-te-toggle="modal" data-te-target="#create_customer_modal">
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Type
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="type"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                            <option value="null"> Free </option>
                                            <option value="package"> Package </option>
                                            <option value="endless_time"> Endless Time </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4" v-show="this.type == 'package'">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Packages
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="selectedPackage" @change="getSelectedPackage()"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                            <option v-for="pack in packageList" :value="pack" :key="pack">
                                                {{ pack.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Deposit
                                    </label>
                                    <input type="text" placeholder="Deposit" v-model="deposit"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Male
                                    </label>
                                    <input type="number" placeholder="Male" v-model="male"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Female
                                    </label>
                                    <input type="number" placeholder="Female" v-model="female"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-8">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Child
                                    </label>
                                    <input type="number" placeholder="Child" v-model="child"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <button @click="confirmRoomBtnClicked()"
                                    class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
                                    Open Room
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="isOpenRoom.detail == true" class="relative h-full">
                        <div class="flex justify-between padding-section border-b">
                            <div>
                                <p class="text-black text-xl" v-if="selectedRoom">
                                    {{ selectedRoom.room_sessions ? selectedRoom.room_sessions.name : '' }} 
                                </p>
                            </div>
                            <div class="flex gap-x-3">
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    @click="btnClickedGetChangeableRoomList()" data-te-toggle="modal"
                                    data-te-target="#change_table_modal">
                                    <i class="far fa-random"></i>
                                </button>
                                <button @click="btnClickAddMenu()"
                                    class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_menu_table_modal">
                                    <i class="far fa-cocktail"></i>
                                </button>
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_accessory_modal">
                                    <i class="far fa-plus-circle"></i>
                                </button>
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_service_modal">
                                    <i class="far fa-user-music"></i>
                                </button>
                                <!-- <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_hour_modal">
                                    <i class="far fa-hourglass-half"></i>
                                </button> -->
                            </div>
                        </div>
                        <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 195px)">
                            <div class="padding-section border-b    " v-if="selectedRoom">
                                <div class="flex justify-between font-semibold mb-2">
                                    <p class="text-sm text-black" v-if="selectedRoom.room_sessions">
                                        Invoice Id
                                        {{ selectedRoom.room_sessions ? (selectedRoom.room_sessions.latest_invoice ?
                                            selectedRoom.room_sessions.latest_invoice.invoice_id : '')
                                        : '' }}

                                    </p>
                                    <p class="text-sm text-black font-semibold" v-if="selectedRoom">
                                        {{ selectedRoom.room_sessions ?
                                            (selectedRoom.room_sessions.latest_invoice.total_session_price ?
                                                selectedRoom.room_sessions.latest_invoice.total_session_price : '' )
                                        : '' }}

                                        MMKs
                                    </p>
                                </div>
                                <div class="mb-2">
                                    <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit" v-if="selectedRoom">
                                        
                                        {{ selectedRoom.room_sessions ? selectedRoom.room_sessions.name : ''}}
                                    </p>
                                </div>
                                <div class="">
                                    <!-- <p class="text-sm text-black mb-2">
                                        Start Time : {{ selectedRoom.room_sessions.length > 0 ?
                                            selectedRoom.room_sessions[0].start_date : '' }}
                                    </p>
                                    <p class="text-sm text-black">
                                        End Time : {{ selectedRoom.room_sessions.length > 0 ?
                                            selectedRoom.room_sessions[0].end_date : '' }}
                                    </p> -->
                                </div>
                            </div>

                            <div class="padding-section border-b    ">
                                <div class="flex justify-between font-semibold mb-3">
                                    <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                        Menu Total
                                    </p>
                                    <p class="text-sm text-black font-semibold">
                                        {{ purchaseMenuList.length > 0 ? purchaseMenuList[0].total.toLocaleString() : '0' }}
                                        MMks
                                    </p>
                                </div>
                                <div class=" grid grid-cols-10 gap-x-2 gap-y-3">
                                    <div v-for="(menu, index) in purchaseMenuList" class="contents" :key="index">
                                        <div v-for="menu2 in menu.order_items" class="contents" :key="menu2">
                                            <p class=" col-span-4 text-sm">
                                                {{ menu2.menu.name }}
                                            </p>
                                            <p class=" col-span-1 text-center text-sm">
                                                {{ menu2.quantity }}
                                            </p>

                                            <!-- <select name="" id="" class="w-12  col-span-2">
                                                <option value="test">
                                                    {{ menu2.status }}
                                                </option>
                                            </select> -->
                                            <p :class="menu2.status == 'done' ? 'text-green-600 font-semibold' : 'text-gray-500'"
                                                class=" col-span-2 text-center text-xs pt-0.5">
                                                {{ menu2.status }}
                                            </p>
                                            <p class=" col-span-3 text-sm text-right">
                                                {{ menu2.price.toLocaleString() }} MMKs
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <!-- services -->
                            <div class="padding-section border-b" v-if="serviceList.length > 0">
                                <div class="flex justify-between font-semibold mb-3">
                                    <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                            Services
                                    </p>
                                    <p class="text-sm text-black font-semibold">
                                            {{ selectedRoom.total_service_value.toLocaleString() }} MMks
                                    </p>
                                </div>
                                <div class=" grid grid-cols-10 gap-x-2 gap-y-3">
                                    <div class="contents" v-for="(service,index) in serviceList" :key="index" :class="service.is_active == 1 ? 'text-black' : 'text-gray-400'">
                                        <div class=" col-span-3 text-sm">
                                            <button  data-te-toggle="modal" data-te-target="#service_end_modal" @click="btnClickedEndService(service)">
                                                {{ service.service.name ? service.service.name : service.service.staff.name }}
                                            </button>
                                        </div>
                                            <!-- <p class=" col-span-3 text-sm" v-else>
                                                {{ service.service.staff.name }}
                                            </p> -->
                                        <p class=" col-span-3 text-center text-sm">
                                            {{ service.minutes }}
                                        </p>
                                            <!-- <p :class="menu2.status == 'done' ? 'text-green-600 font-semibold' : 'text-gray-500'"
                                                class=" col-span-2 text-center text-xs pt-0.5">
                                                {{ menu2.status }}
                                            </p> -->
                                        <p class=" col-span-4 text-sm text-right">
                                            {{ service.service_value.toLocaleString() }} MMKs
                                        </p>
                                    </div>
                                </div>
                            </div>
    
                                    <!-- accessories -->
                            <div class="padding-section border-b    " v-if="accessoryListSidebar.length > 0">
                                <div class="flex justify-between font-semibold mb-3">
                                    <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                        Accessories
                                    </p>
                                    <p class="text-sm text-black font-semibold">
                                        {{ selectedRoom.total_accessory_value.toLocaleString() }} MMks
                                    </p>
                                </div>
                                <div class=" grid grid-cols-10 gap-x-2 gap-y-3">
                                    <div class="contents" v-for="(accessory,index) in accessoryListSidebar" :key="index">
                                        <div class=" col-span-3 text-sm">
                                            {{ accessory.accessory.name }}
                                        </div>
                                        <p class=" col-span-3 text-center text-sm">
                                            {{ accessory.quantity }}
                                        </p>
                                        <p class=" col-span-4 text-sm text-right">
                                            {{ accessory.accessory.accessory_price.price.toLocaleString() }} MMKs
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section">
                            <div class=" text-right pr-3 mb-3">
                                <p class="" v-if="selectedRoom && purchaseMenuList.length > 0">
                                    Total
                                    <!-- {{
                                        (selectedRoom ?
                                            (purchaseMenuList.length > 0 ?
                                                (
                                                    (selectedRoom.room_sessions.latest_invoice.total_session_price ?
                                                        selectedRoom.room_sessions.latest_invoice.total_session_price : 0)
                                                    +
                                                    (purchaseMenuList.length > 0 ? purchaseMenuList[0].total : 0)
                                                    - (selectedRoom.room_sessions.latest_invoice.package ? selectedRoom.room_sessions[0].latest_invoice.package.package_discount : 0)
                                                ).toLocaleString()
                                                :
                                                (
                                                    (selectedRoom.room_sessions.latest_invoice.total_session_price ?
                                                        selectedRoom.room_sessions.latest_invoice.total_session_price : 0)
                                                    - (selectedRoom.room_sessions.latest_invoice.package ? selectedRoom.room_sessions[0].latest_invoice.package.package_discount : 0)
                                                ).toLocaleString()
                                            )
                                            : 0
                                        )


                                    }} -->
                                    {{
                                        (selectedRoom ?
                                            (purchaseMenuList.length > 0 ?
                                                (
                                                    purchaseMenuList[0].total - (selectedRoom.room_sessions.latest_invoice.package ? selectedRoom.room_sessions[0].latest_invoice.package.package_discount : 0)
                                                ).toLocaleString()
                                                :
                                                (
                                                    selectedRoom.room_sessions.latest_invoice.package ? selectedRoom.room_sessions[0].latest_invoice.package.package_discount : 0
                                                ).toLocaleString()
                                            )
                                            : 0
                                        )


                                    }}
                                    MMKs
                                </p>
                            </div>
                            <div class="" >
                                <button @click="btnClickedDoneSession()"
                                    class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
                                    Done Session
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- invoice right sidebar -->
                    <div v-if="isOpenRoom.invoice == true" class="relative h-full">
                        <div class="flex justify-between padding-section border-b">
                            <button @click="btnBackToDetail()">
                                <i class="far fa-chevron-left"></i>
                            </button>
                            <div>
                                <p class="text-black text-lg">
                                    Print Invoice
                                </p>
                            </div>
                            <div></div>
                        </div>
                        <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 329px)">
                            <div class="padding-section w-2/3 mx-auto ">
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Discount Type
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="discount_type" @change="getRoomDiscount"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                            <option value="fix_amount"> Fix Ammount </option>
                                            <option value="percentage"> Percentage </option>
                                            <option value="customer_level" :disabled="roomSessionData.customer_level == 'no customer level'"> Customer Level </option>
                                            <option value="birthday_discount"
                                                v-if="roomSessionData ? roomSessionData.is_birthday : ''"> Birthday
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4" v-show="discount_type == 'birthday_discount'">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Birthday Disount
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="birthday_discount"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                            @change="birthdayDiscountSelectChanged">
                                            <option v-for="bd in birthdayDiscountList" :value="bd" :key="bd"> {{ bd.name }} </option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-4"
                                    v-show="discount_type != 'customer_level' && discount_type != 'birthday_discount'">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Discount
                                    </label>
                                    <input type="number" placeholder="Discount" v-model="printInvoiceData.discount"
                                        
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <!-- <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Payment Method
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="selectedPaymentMethod"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                            <option value="bank"> Bank </option>
                                            <option value="cash" selected> Cash </option>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="mb-4">
                                    <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                                        <input class="input-check-pos" type="checkbox" v-model="printInvoiceData.isTax" @click="btnClickedTax()"
                                            value="" id="tax" />
                                        <label class="inline-block pl-[0.15rem] hover:cursor-pointer" for="tax">
                                            Tax
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                                        <input class="input-check-pos" type="checkbox" @click="btnClickedServiceCharge()"
                                            v-model="printInvoiceData.service_charge" value="" id="service" />
                                        <label class="inline-block pl-[0.15rem] hover:cursor-pointer" for="service">
                                            Service Charges
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section !pt-3">
                            <div v-if="isPackage" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Package Price
                                </p>
                                <p class=" w-28">
                                    {{ selectedRoom.room_sessions[0].invoice.package.name }}
                                </p>
                            </div>
                            <div v-if="!isPackage" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Room
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.room ? printInvoiceData.room.toLocaleString() : 0 }} MMKs
                                </p>
                            </div>
                            <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Food
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.food ? printInvoiceData.food.toLocaleString() : 0 }} MMks
                                </p>
                            </div>
                            <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Service Charge
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.service_charge == true ? (printInvoiceData.service_tax ?
                                        printInvoiceData.service_tax.toLocaleString() :
                                    0) : 0 }} MMKs
                                </p>
                            </div>
                            <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Tax
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.isTax == true ? (printInvoiceData.tax ?
                                        printInvoiceData.tax.toLocaleString() :
                                    0) : 0 }} MMKs
                                </p>
                            </div>
                            <div class="text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Food Discount
                                </p>
                                <p class="w-28">
                                    {{ foodDiscount > 0 ? '- ' : '' }} {{ foodDiscount }}
                                </p>
                            </div>
                            <div v-if="isPackage" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Package Discount
                                </p>
                                <p class=" w-28">
                                    {{ selectedRoom.room_sessions[0].invoice.package.package_discount > 0 ? '- ' : '' }}  {{ selectedRoom.room_sessions[0].invoice.package.package_discount }}
                                </p>
                            </div>
                            <div v-show="discount_type != 'customer_level'" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Discount
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.discount > 0 ? '- ' : '' }} {{ printInvoiceData.discount }} {{
                                        this.discount_type == 'percentage' ? '%' : 'MMKs' }}
                                </p>
                            </div>
                            <div v-show="discount_type == 'customer_level'" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Customer Discount
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.customer_discount > 0 ? '- ' : '' }} {{ printInvoiceData.customer_discount }} MMKs
                                </p>
                            </div>
                            <div class=" text-right pr-3 mb-3">
                                <p class="font-semibold">
                                    Total &nbsp;
                                    {{    (printInvoiceData.total ? printInvoiceData.total : 0)
                                        + (printInvoiceData.service_charge == true ? (printInvoiceData.service_tax ?
                                            printInvoiceData.service_tax : 0) : 0)
                                        + (printInvoiceData.isTax == true ? (printInvoiceData.tax ? printInvoiceData.tax : 0) :
                                    0) }} MMKs
                                </p>
                            </div>
                            <div class="">
                                <button @click="btnClickedEndRoom()"
                                    class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
                                    Print Invoice
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- <div v-if="isOpenRoom.is_package == true" class="relative h-full">
                        <div class="flex justify-center padding-section ">

                            <div>
                                <p class="text-black text-lg">
                                    Confirm Menu
                                </p>
                            </div>
                        </div>
                        <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 329px)">
                            <div class="padding-section ">
                                <div class="table-container px-2">
                                    <table class=" w-full">
                                        <thead class="">
                                            <tr class="border-b">
                                                <th scope="col" class="text-left   py-4">
                                                    Menu
                                                </th>
                                                <th scope="col" class="text-left   py-4">
                                                    Area
                                                </th>
                                                <th scope="col" class="text-left   py-4">
                                                    Qty
                                                </th>
                                                <th scope="col" class="text-left   py-4">
                                                    Price
                                                </th>
                                                <th scope="col" class="text-left   py-4">

                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="" v-for="(pm,index) in packageMenuList" :key=index>
                                                <td class=" py-4 text-sm  ">
                                                    {{ pm.name }}
                                                </td>                                                
                                                <td class=" py-4 text-sm  ">
                                                    <select name="" :class="pm.is_package == 0 ? 'hidden' : ''"
                                                        class="text-xs pl-0 border-0 focus:shadow-none focus:outline-none focus:ring-0 select-box area-select-box !pr-6"
                                                        placeholder="Select Area" @change="packageCookingAreaChange(area,index)" v-model="pm.area_id">
                                                        <option disabled selected>Select Area</option>
                                                        <option v-for="(area, index) in pm.areas" :key="index"
                                                            :value="area.id" class="">
                                                            {{ area.name }}
                                                        </option>
                                                    </select>
                                                </td>
                                                <td class=" py-4 text-sm  ">
                                                    {{ pm.quantity }}
                                                </td>
                                                <td class=" py-4 text-sm  ">
                                                    {{ (pm.original_price - pm.discount_value) * pm.quantity  }}
                                                </td>
                                                <td class=" py-4 text-sm  text-center">
                                                    <button
                                                        @click="removePackageMenu(index)">
                                                        <i class="fal fa-times  pr-3"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section !pt-3">
                            <div>
                                <button class="w-full text-center mb-3 font-semibold text-sm"
                                data-te-toggle="modal" data-te-target="#add_package_menu_modal">
                                    Add More Menu
                                </button>
                            </div>
                            <div class="">
                                <button @click="createRoomForPackage()"
                                    class="bg-[#55EFC4] text-gray-700 text-center text-sm font-semibold w-full py-3">
                                    Confirm Menu
                                </button>
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="relative block h-full">
                        <div class="w-full h-full flex justify-center flex-col">
                            <div class="w-2/3 mx-auto">
                                <div class="text-center">
                                    <p class="mb-4 text-black font-semibold">
                                        Select A Room
                                    </p>
                                </div>
                                <img class="w-[60%] mx-auto mb-6" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRnjfjQ0p-BZt5Vb6KhcdHPeC4hBxiKEYXxMw&s" alt="">

                            </div>
                        </div>
                    </div> -->
                </div>
            </div>



        </div>


        <!-- add Menu modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_menu_table_modal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add Menu
                        </p>
                        <button type="button" id="closeAddMenuTableModal"
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
                            <multiselect v-model="selectedMenu" :options="menuList" :close-on-select="true" @select="selectedMenuChange()"
                                class=" h-10"
                                :clear-on-select="false" :preserve-search="true" placeholder="Select Menu" label="name"
                                track-by="id" :preselect-first="false"></multiselect>


                            <!-- <select name="" id="" placeholder="Menu" v-model="selectedMenu" @change="selectedMenuChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="menu" v-for="(menu, index) in menuList" :key="index">{{ menu.name }}</option>
                            </select> -->
                            
                        </div>
                        <div class="mb-4">
                            <select name="" id="" placeholder="Menu" v-model="selectedMenuArea"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="area" v-for="(area, index) in menuAreaList" :key="index">{{ area.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <!-- <label for="" class="block text-sm text-black mb-3">
                                Hour
                            </label> -->
                            <input type="text" placeholder="Qty" v-model="menuQuantity"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div>
                            <textarea v-model="remark"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                name="" id="" cols="30" rows="10" placeholder="Remark"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="btnConfirmAddMenu" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Add Menu
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- add Package Menu modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_package_menu_table_modal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add Package Menu
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
                            <select name="" id="" placeholder="Menu" v-model="selectedMenuForPackage" @change="selectedPackageMenuChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="menu" v-for="(menu, index) in menuList" :key="index">{{ menu.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <select name="" id="" placeholder="Menu" v-model="selectedMenuAreaForPackage"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="area" v-for="(area, index) in menuAreaListForPackage" :key="index">{{ area.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <input type="text" placeholder="Qty" v-model="menuQuantityForPackage"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="btnConfirmAddPackageMenu()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Add Menu
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- change room modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="change_table_modal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Change Room
                        </p>
                        <button type="button" id="closeChangeTableModal"
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
                            <select name="" id="" placeholder="Room" v-model="change_room"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="changeableRoom" :key="index"
                                    v-for="(changeableRoom, index) in changeableRoomList">{{ changeableRoom.name }}
                                </option>
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="btnClickedChangeRoom()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Change
                        </button>
                    </div>
                </div>
            </div>
        </div>


            <!-- add Service modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_service_modal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add Service
                        </p>
                        <button type="button" id="closeServiceModal"
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
                            <label for="" class="label-form mb-3">
                                Service Category
                            </label>
                            <select name="" id="" v-model="selectedServiceCategory" class="input-ui" @change="serviceCategoryChange()">
                                <option :value="service" v-for="(service, index) in serviceCategoryList"
                                    :key="index">{{
                                        service.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4" v-if="selectedServiceCategory ? selectedServiceCategory.name == 'Lady' : ''">
                            <label for="" class="label-form mb-3">
                                Lady
                            </label>
                            <select name="" id="" v-model="selectedLady" class="input-ui">
                                <option :value="lady" v-for="(lady, index) in ladyList"
                                    :key="index">{{
                                        lady.staff.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4" v-if="selectedServiceCategory ? selectedServiceCategory.name == 'DJ' : ''">
                            <label for="" class="label-form mb-3">
                                DJ
                            </label>
                            <select name="" id="" v-model="selectedDj" class="input-ui">
                                <option :value="dj" v-for="(dj, index) in djList"
                                    :key="index">{{
                                        dj.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Start Date
                            </label>
                            <input type="datetime-local" placeholder="Qty" v-model="selectedServiceStartTime"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Remark
                            </label>
                            <textarea v-model="serviceRemark"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                name="" id="" cols="30" rows="10" placeholder="Remark"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="btnConfirmAddService()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Add Service
                        </button>
                    </div>
                </div>
            </div>
        </div>
            <!-- end Service modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="service_end_modal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            End Service
                        </p>
                        <button type="button" id="closeEndServiceModal"
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
                            <label for="" class="label-form mb-3">
                                End Date
                            </label>
                            <input type="datetime-local" placeholder="End Date" v-model="selectedServiceEndTime"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="btnConfirmEndService()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            End Service
                        </button>
                    </div>
                </div>
            </div>
        </div>


            <!-- add accessory modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_accessory_modal" tabindex="-1" aria-labelledby="addAccessoryModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add Accessory
                        </p>
                        <button type="button" id="closeAccessoryModal"
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
                            <label for="" class="label-form mb-3">
                                Accessory Category
                            </label>
                            <select name="" id="" v-model="selectedAccessoryCategory" class="input-ui" @change="accessoryCategoryChange()">
                                <option :value="accessory" v-for="(accessory, index) in accessoryCategoryList"
                                    :key="index">{{
                                        accessory.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Accessory
                            </label>
                            <select name="" id="" v-model="selectedAccessory" class="input-ui">
                                <option :value="accessory" v-for="(accessory, index) in accessoryList"
                                    :key="index">
                                    {{ accessory.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Quantity
                            </label>
                            <input type="number" v-model="selectedAccessoryQuantity" class="input-ui mb-2">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="btnConfirmAddAccessory()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Add Service
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
        name:'PosTableComponent',
        props:{
            tableAreaId:{
                type: Number,
                required: true
            }
        },
        components:{
            Multiselect
        },
        // props: ['tableAreaId'],
        data() {
            return {
                roomList:[],
                area_Id :null,
                isOpenRoom:{
                    open_1: false,
                    open_2: false,
                    detail: false,
                    invoice: false,
                    is_package:false,
                },
                selectedRoom: null,
                selectedRoomId: null,

                //customer data
                name: null,
                ph_number: null,
                email: null,
                genderList: [],
                date: null,
                duration: null,
                selectedDivision: null,
                divisionList: null,
                selectedTownship: null,
                townshipList: null,
                address_name: null,
                address: null,
                selectedGender: null,


                // open_2's data
                customerList:[],
                packageList:[],

                selectedCustomer:null,
                invoice_date:null,
                type:'session',
                selectedPackage:null,
                deposit:null,
                duration:null,
                male:null,
                female:null,
                child:null,

                purchaseMenuList: [],
                foodDiscount: 0,
                selectedRoomIndex: null,
                printInvoiceData: {
                    room: 0,
                    food: 0,
                    service_tax: 0,
                    service_charge: false,
                    tax: 0,
                    isTax: false,
                    discount: 0,
                    tatalPrice: 0,
                    package_discount:0,
                    customer_discount:0,
                    percent_discount_amount:0,
                },
                room_discount: null,
                birthday_discount: null,
                orderList: [],
                orderItemsPrice: null,
                selectedPaymentMethod: 'cash',
                change: null,
                paid_amount: null,
                isActive: true,

                packageMenuList:[],
                menuAreaListForPackage:[],
                selectedMenuForPackage:null,
                selectedMenuAreaForPackage:null,
                menuQuantityForPackage:null,
                is_menu_discount:0,
                food_total_package:0,
                total_package_menu_price:0,
                package_total:0,

                // create menu , add hour , change room
                menuList: [],
                invoiceId: null,
                selectedMenu: null,
                menuAreaList:[],
                selectedMenuArea:null,
                menuQuantity: null,
                remark: null,
                menuPrice: null,
                testroom: null,
                sessionDuration: null,
                changeableRoomList: [],
                change_room: null,

                // doneSession
                roomDiscountList: null,
                birthdayDiscountList: null,
                isPackage: false,
                packagePrice: null,
                discount_type: null,
                isShowDiscount: true,

                // rooftop
                tableList: [],

                // currentTime: getCurretDateTime(),
                roomSessionData: null,

                roomEndTime:null,
                authUser:null,
                isCashier :false,

                // add service
                serviceCategoryList:[],
                ladyList:[],
                djList:[],

                selectedServiceCategory:null,
                selectedLady:null,
                selectedDj:null,
                selectedServiceQuantity:null,
                serviceRemark:null,
                selectedServiceStartTime:null,
                serviceEnd :null,
                selectedServiceEndTime:null,
                serviceList:[], // use in right sidebar

                serviceEndDate:null,


                // add accessory
                accessoryListSidebar:[],
                accessoryCategoryList:[],
                accessoryList:[],

                selectedAccessoryCategory:null,
                selectedAccessory:null,
                selectedAccessoryQuantity:null,


                currentTime: getCurretDateTime(),
                isShowSidebar:false,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getTableList(){
                const response = await getApiData({ url: '/api/areas/' + this.tableAreaId + '/entities' , token: this.getToken()});
                if(response.data){
                    this.roomList = response.data;
                    console.log('get table list')
                }
            },
            async btnClickedIsOpenRoom(room , roomIndex) {
                this.isShowSidebar = true;
                this.selectedRoom = null;
                this.selectedRoomId = room.id;
                // this.getSelectedRoom();
                if (this.roomList[roomIndex].is_active == 1) {
                    this.isOpenRoomStep('detail');
                    // this.getPurchaseMenuList();
                    const response = await getApiData({ url: '/api/entities/'+ this.selectedRoomId, token: this.getToken() });
                    if (response.data) {
                        this.selectedRoom = response.data;
                        this.purchaseMenuList = response.data.room_sessions.latest_invoice.orders
                        // this.serviceList = response.data.services;
                        // this.accessoryListSidebar = response.data.invoice_accessories;
                    }
                }
                else {
                    this.selectedRoom = this.roomList[roomIndex];
                    this.isOpenRoomStep('open_1');
                };
            },

            btnClickedOpenRoom() {
                this.isOpenRoomStep('open_2');
                // this.clearOpenRoomForm();
                this.isPackage = false;

            },

            // step 2's methods
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
            getSelectedPackage(){
                // this.packageMenuList = this.selectedPackage;
            },
            confirmRoomBtnClicked() {
                this.getPurchaseMenuList();
                if(this.type == 'package' && this.selectedPackage){
                    this.isOpenRoomStep('is_package');
                    let menuOfselectedPackage = this.selectedPackage.menu_packages;
                    menuOfselectedPackage.forEach((packageMenu)=>{
                        let is_dis_menu_price = 0;
                        this.food_total_package += (packageMenu.menu.prices[0].price - is_dis_menu_price) * packageMenu.quantity;
                        this.packageMenuList.push({
                            quantity : packageMenu.quantity,
                            name : packageMenu.menu.name,
                            original_price : packageMenu.menu.prices[0].price,
                            discount_value: is_dis_menu_price,
                            menu_id : packageMenu.menu_id,
                            is_package : 1,
                            area_id: null,
                            areas : packageMenu.menu.areas
                        });
                    });
                    console.log('package')
                }
                else{
                    this.createRoom();
                }
            },
            async selectedPackageMenuChange() {
                const response = await getApiData({ url: '/api/menus/' + this.selectedMenuForPackage.id + '/areas', token: this.getToken() });
                if (response.data) {
                    this.menuAreaListForPackage = response.data.areas;
                }
            },

            btnConfirmAddPackageMenu() {
                this.addPackageMenu();
            },
            packageCookingAreaChange(area,index){
                // this.packageMenuList[index].area_id = area.id;
                console.log(area)
            },
            async addPackageMenu() {
                if(this.selectedMenuForPackage.menu_service_discounts.length > 0){
                    this.is_menu_discount = this.selectedMenuForPackage.menu_service_discounts[0].discount_price
                }
                else{
                    this.is_menu_discount = 0
                }

                this.packageMenuList.push({
                    quantity: this.menuQuantityForPackage,
                    name: this.selectedMenuForPackage.name,
                    original_price:this.selectedMenuForPackage.prices[0].price,
                    menu_id : this.selectedMenuForPackage.prices[0].menu_id,
                    discount_value: this.is_menu_discount,
                    is_package : 0,
                    area_id: this.selectedMenuAreaForPackage.id
                });
                this.food_total_package += (this.selectedMenuForPackage.prices[0].price - this.is_menu_discount) * this.menuQuantityForPackage;
                this.selectedMenu = null
                this.menuAreaListForPackage = []
                this.selectedMenuAreaForPackage = null;
                this.menuQuantityForPackage = null;
            },
            removePackageMenu(index){

                this.food_total_package -= (this.packageMenuList[index].original_price - this.packageMenuList[index].discount_value) * this.packageMenuList[index].quantity;
                this.packageMenuList.splice(index, 1);
            },

            createRoomForPackage(){

                // this.packageMenuList.forEach((packageMenu)=>{
                //     this.total_package_menu_price += (packageMenu.original_price - packageMenu.discount_value ) * packageMenu.quantity;
                // });
                let packageActualTotal = this.food_total_package + (this.selectedPackage.session_price * this.selectedPackage.pay_session) - this.selectedPackage.package_discount;
                if(packageActualTotal < this.selectedPackage.price){
                    this.$notify({
                        title: `Not valid`,
                        text: 'Your Total is Lower than Package Price',
                        type: "warn"
                    });
                }
                else{
                    this.filterMenuForData();
                    this.createRoom();
                }
            },

            async createRoom() {
                let formData = new FormData();
                // formData.append('entity_id', this.selectedRoom.id);
                formData.append('entity_id', this.selectedRoom.id);
                formData.append('customer_id', this.selectedCustomer.id);
                if (this.type == 'package') {
                    formData.append('package_id', this.selectedPackage.id);
                    formData.append('orders', JSON.stringify(this.packageMenuList));
                }
                formData.append('type', this.type);
                formData.append('deposit', this.deposit);
                if (this.female > 0) {
                    formData.append('female', +this.female);
                }
                if (this.male > 0) {
                    formData.append('male', +this.male);
                }
                if (this.child > 0) {
                    formData.append('child', +this.child);
                }
                let response = await postApiData({ url: '/api/entities/start', form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.getTableList();
                    this.getSelectedRoom();
                    this.isOpenRoomStep('detail');//for right side bar ui

                    if (this.selectedRoom.room_sessions) {
                        if (this.selectedRoom.room_sessions.latest_invoice.orders.length > 0) {
                            this.getPurchaseMenuList();
                        }
                    
                        else if (this.selectedRoom.room_sessions.latest_invoice.orders.length < 1) {
                            this.purchaseMenuList = [];
                        }
                    }
                    this.food_total_package = 0 //total price of package menu and add more menu reset
                    console.log("success")
                    window.location.reload()
                }
                else {
                    console.log('some errors occur')
                }
            },
            async getPurchaseMenuList() {
                const response = await getApiData({ url: '/api/entities/' + this.selectedRoomId, token: this.getToken() });
                if (response.data) {
                    if (response.data.room_sessions.length > 0) {
                        this.purchaseMenuList = response.data.room_sessions.latest_invoice.orders;
                        if (response.data.room_sessions.latest_invoice.orders) {
                            if (response.data.room_sessions.latest_invoice.orders[0]) {
                                this.foodDiscount = response.data.room_sessions.latest_invoice.orders[0].total_discount_price
                            }
                        }
                    }

                }
            },
            async getSelectedRoom() {
                const response = await getApiData({ url: '/api/entities/' + this.selectedRoomId, token: this.getToken() });
                if (response.data) {
                    this.selectedRoom = response.data;
                    this.purchaseMenuList = response.data.room_sessions.invoice.orders
                }
            },



            //invoice or done
            btnBackToDetail() {
                this.isOpenRoomStep('detail');
            },
            btnClickedDoneSession() {
                this.doneSession();
                this.getPurchaseMenuList();
                this.discount_type = null;
            },

            filterMenuForData(){
                this.packageMenuList.forEach(om => {
                    delete om.name;
                    delete om.areas;
                    console.log('hello = ' + om.name);
                });

            },
            async doneSession() {
                let formData = new FormData();
                let roomSessions = [];
                formData.append('invoice_id', this.selectedRoom.room_sessions.latest_invoice.invoice_id);
                let response = await postApiData({ url: '/api/room_done', form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.roomSessionData = response.data;
                    roomSessions = response.data;
                    console.log("success")
                    this.isOpenRoomStep('invoice')
                }
                else {
                    this.$notify({
                        title: `Not valid`,
                        text: response.message,
                        type: "warn"
                    });
                }
                let roomChargeTotal = 0;
                // roomSessions.forEach(roomSession => {
                if(this.selectedRoom.room_sessions.latest_invoice.invoice_type == 'endless_time'){
                    roomChargeTotal = this.roomSessionData.total_session_price;
                }
                else{
                    // roomChargeTotal = roomSessions.total_session_price;
                    roomChargeTotal = 0
                }
                // roomChargeTotal += roomSession.price; // or roomSession.session_duration * roomSession.entity.price_per_hour;
                // });
                this.printInvoiceData.room = roomChargeTotal; // <== or that


                if (this.purchaseMenuList.length > 0) {
                    this.printInvoiceData.food = this.purchaseMenuList[0].total
                    this.purchaseMenuList[0].order_items.forEach(element => {
                        this.orderList.push({
                            'menu_category_id': element.menu.menu_category_id,
                            'price': element.price,
                        })
                    });
                }
                if (this.selectedRoom.room_sessions.latest_invoice.invoice_type == 'package') {
                    this.printInvoiceData.room = this.selectedRoom.room_sessions.latest_invoice.total_session_price;
                    this.printInvoiceData.package_discount = this.selectedRoom.room_sessions.latest_invoice.package.package_discount;
                    this.isPackage = true;
                    this.packagePrice = this.selectedRoom.room_sessions.latest_invoice.paid_amount
                    this.printInvoiceData.total = (this.printInvoiceData.room + this.printInvoiceData.food) - this.printInvoiceData.package_discount - this.foodDiscount;
                }
                else {
                    this.isPackage = false;
                    this.printInvoiceData.total = (roomChargeTotal + this.printInvoiceData.food) - this.foodDiscount;
                }


                // room price = this.printInvoiceData.room
                if (this.service_charge = true) {
                    this.printInvoiceData.service_tax = this.printInvoiceData.total * 0.05
                }
                if (this.isTax = true) {
                    this.printInvoiceData.tax = this.printInvoiceData.food * 0.05
                }

                // this.printInvoiceData.total = this.printInvoiceData.room + this.printInvoiceData.food + this.printInvoiceData.tax +this.printInvoiceData.service_tax

                // this.selectedPaymentMethod = null
                this.change = null
                this.paid_amount = null
                this.printInvoiceData.discount = 0

            },

            btnClickedServiceCharge(){
                if (this.service_charge = true) {
                    this.printInvoiceData.service_tax = this.printInvoiceData.total * 0.05
                }
            },
            btnClickedTax(){
                if (this.isTax = true) {
                    this.printInvoiceData.tax = (this.printInvoiceData.food - this.foodDiscount) * 0.05
                }
            },
            
            birthdayDiscountSelectChanged() {
                this.printInvoiceData.discount = this.birthday_discount.discount_value
                if (this.selectedRoom.room_sessions[0].invoice.invoice_type == 'package') {
                    this.printInvoiceData.room = (this.roomSessionData.total_session_price) - this.selectedRoom.room_sessions[0].invoice.package.package_discount;
                }
                else {
                    this.printInvoiceData.room = this.roomSessionData.total_session_price;
                }
                console.log(this.printInvoiceData.room,this.printInvoiceData.food,this.printInvoiceData.discount,this.foodDiscount);
                this.printInvoiceData.total = (this.printInvoiceData.room + this.printInvoiceData.food) - this.printInvoiceData.discount - this.foodDiscount
            },

            
            discountChanged() {
                // let roomTotalAmount = (this.roomSessionData.total_session_price + this.printInvoiceData.food) - this.printInvoiceData.package_discount - this.foodDiscount
                let roomTotalAmount = this.printInvoiceData.food - this.printInvoiceData.package_discount - this.foodDiscount
                console.log('room total = ' + this.printInvoiceData.room)
                if (this.discount_type == 'percentage') {
                    this.printInvoiceData.total = roomTotalAmount - (roomTotalAmount * (this.printInvoiceData.discount / 100));
                    this.printInvoiceData.percent_discount_amount = roomTotalAmount * (this.printInvoiceData.discount / 100);
                }
                else {
                    // this.printInvoiceData.total = roomTotalAmount - this.printInvoiceData.discount;
                    if(this.printInvoiceData.discount){
                        this.printInvoiceData.total = roomTotalAmount - this.printInvoiceData.discount;
                        console.log(this.printInvoiceData.discount)
                    }
                    else{
                        this.printInvoiceData.total = roomTotalAmount - this.printInvoiceData.discount;
                        console.log(this.printInvoiceData.discount)
                    }
                }
                console.log('room total amounttt = ' + roomTotalAmount);


            },

            btnClickedEndRoom() {
                this.EndRoom();
                // if(this.selectedPaymentMethod){
                //     this.EndRoom();
                // }
                // else{
                //     this.$notify({
                //         title: `Not valid`,
                //         text: 'Please Select Payment Method',
                //         type: "warn"
                //     });
                // }

            },
            async EndRoom() {
                let totalAmount = this.printInvoiceData.total;
                let allTotalDiscounts = 0;
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.room_sessions.latest_invoice.id);
                // formData.append('payment_type', this.selectedPaymentMethod);
                formData.append('discount_type', this.discount_type);

                if (this.discount_type == 'fix_amount') {
                    formData.append('discount_value', this.printInvoiceData.discount);
                    // totalAmount = totalAmount - this.printInvoiceData.discount;
                }
                if (this.discount_type == 'percentage') {
                    formData.append('discount_value', this.printInvoiceData.percent_discount_amount);
                }
                if (this.discount_type == 'birthday_discount') {
                    formData.append('birthday_promotion_id', this.birthday_discount.id);
                    formData.append('birthday_discount', this.birthday_discount.discount_value);
                }
                else{
                    formData.append('birthday_discount', 0);
                }
                if(this.discount_type == 'customer_level'){
                    formData.append('customer_level_discount',this.roomSessionData.customer_level_discount_value);
                }
                else{
                    formData.append('customer_level_discount', 0);
                }
                formData.append('order_categories', JSON.stringify(this.orderList));
                if (this.printInvoiceData.service_charge) {
                    formData.append('service_charge', this.printInvoiceData.service_tax);
                    totalAmount = totalAmount + this.printInvoiceData.service_tax
                }
                else{
                    formData.append('service_charge', 0);
                }
                if (this.printInvoiceData.isTax) {
                    formData.append('tax', this.printInvoiceData.tax);
                    totalAmount = totalAmount + this.printInvoiceData.tax
                }
                else{
                    formData.append('tax', 0);
                }
                // formData.append('total', this.printInvoiceData.total);
                if (this.room_discount) {
                    formData.append('room_discount_amount', this.printInvoiceData.roomDiscountAmount);
                    formData.append('discount_session', this.printInvoiceData.discountSession);
                }
                if (this.selectedRoom.room_sessions.latest_invoice.invoice_type == 'package') {
                    if(this.discount_type == 'percentage'){
                        allTotalDiscounts = this.foodDiscount + this.printInvoiceData.package_discount + this.printInvoiceData.percent_discount_amount
                    }
                    else if(this.discount_type == 'customer_level'){
                        allTotalDiscounts = this.foodDiscount + this.printInvoiceData.package_discount + this.roomSessionData.customer_level_discount_value
                    }
                    else if(this.discount_type == 'birthday_discount'){
                        allTotalDiscounts = this.foodDiscount + this.printInvoiceData.package_discount + this.birthday_discount.discount_value
                    }
                    else{
                        allTotalDiscounts = this.foodDiscount + this.printInvoiceData.discount + this.printInvoiceData.package_discount
                    }
                }
                else{
                    if(this.discount_type == 'percentage'){
                        allTotalDiscounts = this.foodDiscount + this.printInvoiceData.percent_discount_amount
                    }
                    else if(this.discount_type == 'customer_level'){
                        allTotalDiscounts = this.foodDiscount + this.roomSessionData.customer_level_discount_value
                    }
                    else if(this.discount_type == 'birthday_discount'){
                        allTotalDiscounts = this.foodDiscount + this.birthday_discount.discount_value
                    }
                    else{
                        allTotalDiscounts = this.foodDiscount + this.printInvoiceData.discount
                    }
                }
                formData.append('total', totalAmount);
                formData.append('order_discount', this.foodDiscount);
                formData.append('discount_total', allTotalDiscounts);


                console.log(formData)

                let response = await postApiData({ url: '/api/entities/done', form_data: formData, token: this.getToken() });
                if (response.success) {
                    await this.getTableList();
                    // this.selectedRoom = await this.roomList[this.selectedRoomIndex];
                    this.isOpenRoom.step_1 = true;
                    this.isOpenRoom.step_2 = false;
                    this.isOpenRoom.step_detail = false;
                    this.isOpenRoom.step_invoice = false;

                    console.log("success")
                    window.location.reload()
                }
                else {
                    console.log('some errors occur');
                }
            },




            // async getSelectedRoom() {
            //     const response = await getApiData({ url: '/api/entities/' + this.selectedRoomId, token: this.getToken() });
            //     if (response.data) {
            //         this.selectedRoom = response.data;
            //     }
            // },


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
                this.createCustomer();
            },
            async createCustomer() {
                console.log(this.email);
                // return 1;
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
                console.log(this.selectedGender + ',' + this.name + ',' + this.email + ',' + this.ph_number + ',' + this.address + ',' + this.date)
                if (response.success) {
                    this.customerList.push(response.data);
                    this.selectedCustomer = response.data;
                    console.log("success customer")
                    this.closeModal('closeCustomerModal');
                    this.clearCustomerForm();
                }
                else {
                    console.log('some errors occur');
                }
            },


            // add menu
            async getMenuList() {
                const response = await getApiData({ url: '/api/menus', token: this.getToken() });
                if (response.data) {
                    this.menuList = response.data;
                }
            },
            async selectedMenuChange() {
                const response = await getApiData({ url: '/api/menus/' + this.selectedMenu.id + '/areas', token: this.getToken() });
                if (response.data) {
                    this.menuAreaList = response.data.areas;
                }
            },
            btnClickAddMenu() {
                this.invoiceId = this.selectedRoom.room_sessions.latest_invoice.id;
                console.log('invoice id ' + this.invoiceId)
            },
            btnConfirmAddMenu() {
                this.addMenu();
            },
            async addMenu() {
                let formData = new FormData();
                formData.append('invoice_id', this.invoiceId);
                formData.append('menu_id', this.selectedMenu.id);
                formData.append('area_id', this.selectedMenuArea.id);
                formData.append('quantity', this.menuQuantity);
                formData.append('original_price', this.selectedMenu.prices[0].price);
                formData.append('remark', this.remark);
                let response = await postApiData({ url: '/api/entities/orders', form_data: formData, token: this.getToken() });
                // console.log(this.invoiceId+','+this.selectedMenu.id + ','+ this.menuQuantity +','+this.selectedMenu.prices[0].price)
                if (response.success) {
                    this.closeModal('closeAddMenuTableModal');
                    this.clearMenuForm();
                    // this.getPurchaseMenuList();
                    this.getSelectedRoom();
                }
                else {
                    console.log('some errors occur');
                }
            },
            btnAddHour() {
                this.addHour();
            },
            async addHour() {
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.room_sessions[0].invoice.invoice_id);
                formData.append('session_duration', this.sessionDuration);
                let response = await postApiData({ url: '/api/entities/add_more_sessions', form_data: formData, token: this.getToken() });
                if (response.success) {
                    console.log("success")
                    await this.getTableList();
                    // this.selectedRoom = await this.roomList[this.selectedRoomIndex];
                    this.getSelectedRoom();
                    this.closeModal();
                    this.clearAddHourForm();
                }
                else {
                    this.$notify({
                        title: `Not valid`,
                        text: response.message,
                        type: "warn"
                    });
                }
            },
            btnClickedChangeRoom() {
                this.changeRoom();
            },
            async changeRoom() {
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.room_sessions.invoice.id);
                formData.append('entity_id', this.change_room.id);
                let response = await postApiData({ url: '/api/entities/change', form_data: formData, token: this.getToken() });
                console.log('change room ' + this.selectedRoom.room_sessions.invoice.id + ',' + this.change_room.id)
                if (response.success) {
                    console.log("success");
                    await this.getTableList();
                    // this.selectedRoom = this.roomList.find(x => x.id === this.change_room.id);
                    this.selectedRoomId = this.change_room.id;
                    this.getSelectedRoom();
                    this.closeModal('close_change_room_modal');
                    this.clearChangeRoomForm();
                }
                else {
                    console.log('some errors occur');
                }
            },
            btnClickedGetChangeableRoomList() {
                this.getChangeableRoomList();
            },
            async getChangeableRoomList() {
                const response = await getApiData({ url: '/api/areas/' + this.tableAreaId + '/inactive_entities?type=table', token: this.getToken() });
                if (response.data) {
                    this.changeableRoomList = response.data;
                }
            },


            // add service
            // api/get_service?service_category_id=1&area_id=3 dj list
            async getServiceCategoryList() {
                const response = await getApiData({ url: '/api/service_categories', token: this.getToken() });
                if (response.data) {
                    this.serviceCategoryList = response.data;
                }
            },
            async getLadyList(){
                const response = await getApiData({ url: '/api/staff_by_department_slug/entertainment', token: this.getToken() });
                if (response.data) {
                    this.ladyList = response.data;
                }
            },
            async serviceCategoryChange(){
                const response = await getApiData({ url: '/api/get_service?service_category_id=' + this.selectedServiceCategory.id + '&area_id=' + this.tableAreaId, token: this.getToken() });
                if (response.data) {
                    if(this.selectedServiceCategory.name == 'DJ'){
                        this.djList = response.data;
                    }
                    else{
                        this.ladyList = response.data;
                    }
                    
                }
            },
            btnConfirmAddService() {
                console.log('add services')
                this.addService();
            },
            async addService() {   // invoice pay yan
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.room_sessions.latest_invoice.id);
                // formData.append('invoice_id', this.selectedServiceCategory);
                if(this.selectedServiceCategory.name == 'Lady'){
                    formData.append('service_id', this.selectedLady.id);
                }
                if(this.selectedServiceCategory.name == 'DJ'){
                    formData.append('service_id', this.selectedDj.id);
                }
                formData.append('start_date', this.selectedServiceStartTime);
                formData.append('remark', this.serviceRemark);
                let response = await postApiData({ url: '/api/entities/add_service', form_data: formData, token: this.getToken() });
                // console.log(this.invoiceId+','+this.selectedMenu.id + ','+ this.menuQuantity +','+this.selectedMenu.prices[0].price)
                if (response.success) {
                    this.closeModal('closeServiceModal');
                    this.clearServiceForm();
                    this.getSelectedRoom();
                }
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            },
            btnClickedEndService(service){
                this.serviceEnd = service;
            },  
            async btnConfirmEndService(){
                let formData = new FormData();
                formData.append('invoice_service_id', this.serviceEnd.id);
                formData.append('invoice_id', this.serviceEnd.invoice_id);
                formData.append('end_date', this.selectedServiceEndTime);
                let response = await postApiData({ url: '/api/entities/end_service', form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.closeModal('closeEndServiceModal');
                    this.selectedServiceEndTime = null;
                    this.getSelectedRoom();
                }
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            },


            // add accessory
            async getAccessoryCategoryList() {
                const response = await getApiData({ url: '/api/pos/get_accessory_category', token: this.getToken() });
                if (response.data) {
                    this.accessoryCategoryList = response.data;
                }
            },
            async accessoryCategoryChange(){ //get accessory list
                const response = await getApiData({ url: '/api/pos/accessory_by_category/'+ this.selectedAccessoryCategory.id, token: this.getToken() });
                if (response.data) {
                    this.accessoryList = response.data;
                }
            },
            btnConfirmAddAccessory() {
                this.addAccessory();
            },
            async addAccessory() {   // invoice pay yan
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.room_sessions[0].invoice.id);
                formData.append('accessory_id', this.selectedAccessory.id);
                formData.append('quantity', this.selectedAccessoryQuantity);
                let response = await postApiData({ url: '/api/pos/add_accessory', form_data: formData, token: this.getToken() });
                // console.log(this.invoiceId+','+this.selectedMenu.id + ','+ this.menuQuantity +','+this.selectedMenu.prices[0].price)
                if (response.success) {
                    this.closeModal('closeAccessoryModal');
                    this.clearAccessoryForm();
                    this.getSelectedRoom();
                }
                else {
                    this.$notify({
                        title: `Input validation`,
                        text: response.message,
                        type: "warn"
                    });
                }
            },


            closeModal(modalId) {
                document.getElementById(modalId).click();
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
            clearMenuForm() {
                this.invoiceId = null
                this.menuQuantity = null
                this.selectedMenu = null
            },
            clearAddHourForm() {
                this.sessionDuration = null
            },
            clearChangeRoomForm() {
                this.change_room = null
            },
            clearServiceForm() {
                this.selectedServiceCategory = null
                this.selectedLady = null
                this.selectedDj = null
                this.selectedServiceQuantity = null
            },
            clearAccessoryForm() {
                this.selectedAccessoryCategory = null
                this.selectedAccessory = null
                this.selectedAccessoryQuantity = null
            },
            clearOpenRoomForm() {
                this.selectedCustomer = null
                this.type = 'session'
                this.selectedPackage = null
                this.deposit = null
                this.invoice_date = null
                this.duration = null
                this.male = null
                this.female = null
                this.child = null
            },
            showSidebar(){
                if(this.isShowSidebar){

                    this.isOpenRoom.open_1 = false;
                    this.isOpenRoom.open_2 = false;
                    this.isOpenRoom.detail = true;
                    this.isOpenRoom.invoice = false;
                    this.isShowSidebar = false;

                }
                else{
                    this.isShowSidebar = true
                }
            },
            isOpenRoomStep(selectedKey) {
                for (let key in this.isOpenRoom) {
                    if (key === selectedKey) {
                        this.isOpenRoom[key] = true;
                    }
                    else {
                        this.isOpenRoom[key] = false;
                    }
                }
            },

        },
        
        watch: {
            selectedRoom(val, oldVal) {
                console.log(`new: ${val}, old: ${oldVal}`)
            },
        
            tableAreaId(newId) {
            // Call your function once areaId is updated
            this.getTableList(newId);
            }
        },
        created(){
            this.getCustomerList();
            // this.getGendersList();
            this.getMenuList();
            // this.getDivisionList();
            this.getPackageList(this.currentTime);

            this.getServiceCategoryList();
            this.getLadyList();

            this.getAccessoryCategoryList();
        },
        mounted()
        {
            // if (this.tableAreaId) {
            //     this.getTableList(this.tableAreaId);
            // }
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>