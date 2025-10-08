<template>
    <div class="">
        <notifications position="top center" />
        <div class="mb-6">
            <div class="opacity-100 transition-opacity duration-150 ease-linear overflow-x-auto hidden-scrollbar" :style="isShowSidebar == true ? 'width:calc(100% - 375px)' : 'width:100%' ">
                <div class="flex flex-wrap gap-x-4 gap-y-4">
                    <div class="flex flex-col mb-4" v-for="(room, roomIndex) in roomList" :key="roomIndex">
                        <div class="flex flex-row gap-x-4 pr-6">
                            <div :class="room.is_active == 0 ? 'bg-[#4fe0b7]' : 'bg-[#FF7675]'"
                                class=" flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
                                <button
                                    class="relative flex flex-col justify-between h-full w-full">
                                    <div class=" flex justify-between flex-col h-full">

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
                            <!-- <div :class="[time.is_active == 0 ? 'bg-[#4fe0b7]' : 'bg-[#FF7675]', parseInt(time.start_time.split(':')) < currentTime ? 'opacity-70' : 'opacity-100' ]" v-for="(time,timeIndex) in room.entity_sessions" :key="index"
                                class=" flex-shrink-0 flex-grow w-40 max-w-44 h-40"> -->
                            <!-- <div
                                :class="isTimeActive(time)"
                                v-for="(time,timeIndex) in room.entity_sessions" :key="timeIndex"
                                class=" flex-shrink-0 flex-grow w-40 max-w-44 h-40"> -->
                            <div :class="time.is_active == 0 ? 'bg-[#4fe0b7]' : 'bg-[#FF7675]'" v-for="(time,timeIndex) in room.entity_sessions" :key="timeIndex"
                                class=" flex-shrink-0 flex-grow w-44 max-w-48 h-40 rounded">
                                <!-- <button @click="btnClickedSession(time, timeIndex, room, roomIndex)" :disabled="parseInt(time.start_time.split(':')) < currentTime" :class="parseInt(time.start_time.split(':')) < currentTime ? ' cursor-not-allowed' : ''"
                                    class="relative flex flex-col justify-between h-full w-full p-6"> -->
                                <button @click="btnClickedSession(time, timeIndex, room, roomIndex)"
                                    class="relative flex flex-col justify-center h-full w-full py-5 px-1 items-center">
                                    <div  class=" flex justify-center flex-col h-full gap-y-4">
                                        <p class="text-sm text-white">Start Time : <span class="font-semibold">{{ time.start_time ? time.start_time.slice(0,5) : '' }} </span></p>
                                        <p class="text-sm text-white">End Time : <span class="font-semibold">{{ time.end_time ? time.end_time.slice(0,5) : '' }}</span> </p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="fixed right-0 top-0 bottom-0 bg-white drop-shadow-xl ease-in-out duration-300 transition delay-100 pt-12 right-sidebar-2" :class="isShowSidebar == true ? 'translate-x-0 opacity-100 w-[400px]' : 'translate-x-full opacity-0 w-0' ">
                <div class="relative h-full w-full">
                    <div class="fixed right-4 top-4 z-40" :class="isShowSidebar == true ? 'block' : 'hidden' ">
                        <button @click="isShowSidebar = false"><i class="far fa-times"></i></button>
                    </div>
                    <!-- aa
                        </div>  :class="isShowSidebar == true ? 'w-[400px] block opacity-100' : 'w-0 hidden opacity-0'"
                        <div class="right-sidebar shadow-lg border-l border-gray-200"> -->
                    <div class="relative h-full " v-if="isOpenRoom.open_1 == true"  id="open_room_1">
                        <div class="w-full h-full flex justify-center flex-col">
                            <div class="w-2/3 mx-auto">
                                <div class="text-center">
                                    <p class="mb-2 text-black font-semibold">
                                        {{ selectedTime ? (selectedTime.start_time).slice(0,5) : '' }}
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
                        <div class="small-scrollbar overflow-y-auto h-[100vh] pt-8 pb-16">
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
                                            data-te-toggle="modal" data-te-target="#create_customer_modal"
                                            @click="handleClick">
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Time
                                    </label>
                                    <!-- <input ref="datetimeInput" type="text" class="form-control input-ui"  v-model="invoice_date" @change="getPackageList(invoice_date)" /> -->
                                    <input type="datetime-local" placeholder="Time" v-model="invoice_date" @change="getPackageList(invoice_date)"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Discount Type
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="selectedDiscountType"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                            <option v-for="discount in discountTypeList" :value="discount">{{ discount.name }} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Type
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="type"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                            <option value="session"> Session </option>
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
                                        Pre Deposit?
                                    </label>
                                    <input type="checkbox" v-model="isPreDeposit" class="rounded" >
                                </div>
                                <div class="mb-4" v-if="isPreDeposit">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Deposit
                                    </label>
                                    <input type="number" placeholder="Deposit Amount" v-model="deposit" :disabled="!isPreDeposit"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-4" v-if="isPreDeposit">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Cash Account
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="selectedCashAccount" :disabled="!isPreDeposit"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                            <option disabled selected> Select Cash Account </option>
                                            <option v-for="cashAccount in cashAccounts" :value="cashAccount" > {{ cashAccount.name }} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4" v-show="this.type == 'session'">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Duration
                                    </label>
                                    <input type="number" placeholder="" v-model="duration"
                                        :disabled="this.type == 'package' || this.type == 'endless_time'"
                                        :class="this.type == 'package' || this.type == 'endless_time' ? ' cursor-not-allowed ' : ''"
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
                            <div v-if="selectedRoom">
                                <p v-if="selectedRoom.entity" class="text-black text-xl">
                                    {{ selectedRoom.entity.name }}
                                </p>
                            </div>
                            <div class="flex gap-x-3">
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    @click="btnClickedGetChangeableRoomList()" data-te-toggle="modal"
                                    data-te-target="#change_modal">
                                    <i class="far fa-random"></i>
                                </button>
                                <!-- <button @click="btnClickAddMenu()"
                                    class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_menu_modal">
                                    <i class="far fa-cocktail"></i>
                                </button> -->
                                <a :href="'/pos/pos_order/'+selectedRoom?.id"><i class="far fa-cocktail"></i></a>
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_hour_modal">
                                    <i class="far fa-hourglass-half"></i>
                                </button>
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_accessory_modal_room" @click="getAccessoryCategoryList()">
                                    <i class="far fa-plus-circle"></i>
                                </button>
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_service_modal_room" @click="getServiceCategoryList()">
                                    <i class="far fa-user-music"></i>
                                </button>
                            </div>
                        </div>
                        <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 300px)">
                            <div class="padding-section border-b    " v-if="selectedRoom">
                                <div class="flex justify-between font-semibold mb-2">
                                    <p class="text-sm text-black" v-if="selectedRoom">
                                        Invoice Id
                                        {{ selectedRoom.invoice ? (selectedRoom.invoice.invoice_id ?
                                            selectedRoom.invoice.invoice_id : '')
                                        : '' }}

                                    </p>
                                    <p class="text-sm text-black font-semibold" v-if="selectedRoom">
                                        {{ selectedRoom.total_session_price ? selectedRoom.total_session_price : '' }}
                                        <!-- {{ selectedRoom.room_sessions[0] ?
                                            (selectedRoom.room_sessions[0].invoice.total_session_price ?
                                                selectedRoom.room_sessions[0].invoice.total_session_price : '' )
                                        : '' }} -->

                                        MMKs
                                    </p>
                                </div>
                                <div class="mb-2">
                                    <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                        Room Err0r
                                        <!-- {{ selectedRoom.name }} -->
                                    </p>
                                </div>
                                <div class="">
                                    <p class="text-sm text-black mb-2">
                                        Start Time : {{ selectedRoom.start_date_time ?
                                            selectedRoom.start_date_time : '' }}
                                    </p>
                                    <p class="text-sm text-black">
                                        End Time : {{ selectedRoom.end_date_time ?
                                            selectedRoom.end_date_time : '' }}
                                    </p>
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
                                        <div v-for="menu2 in combinedMenuList" class="contents" :key="menu2">
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


                                        <!-- <div v-for="menu2 in menu.order_items" class="contents" :key="menu2">
                                            <p class=" col-span-4 text-sm">
                                                {{ menu2.menu.name }}
                                            </p>
                                            <p class=" col-span-1 text-center text-sm">
                                                {{ menu2.quantity }}
                                            </p>
                                            <p :class="menu2.status == 'done' ? 'text-green-600 font-semibold' : 'text-gray-500'"
                                                class=" col-span-2 text-center text-xs pt-0.5">
                                                {{ menu2.status }}
                                            </p>
                                            <p class=" col-span-3 text-sm text-right">
                                                {{ menu2.price.toLocaleString() }} MMKs
                                            </p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                                <!-- services -->
                            <div class="padding-section border-b    " v-if="serviceList.length > 0">
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
                                            <button  data-te-toggle="modal" data-te-target="#service_end_modal_room" @click="btnClickedEndService(service)">
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
                                    <!-- <div class="block">
                                        <p class="text-sm">
                                            Service 1
                                        </p>
                                        <p class="text-sm text-right">
                                            12,000 MMKs
                                        </p>
                                    </div>
                                    <div class="block">
                                        <p class="text-sm">
                                            Service 1
                                        </p>
                                        <p class="text-sm text-right">
                                            12,000 MMKs
                                        </p>
                                    </div> -->
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
                                        <!-- <p class=" col-span-3 text-sm" v-else>
                                            {{ service.service.staff.name }}
                                        </p> -->
                                        <p class=" col-span-3 text-center text-sm">
                                            {{ accessory.quantity }}
                                        </p>
                                        <!-- <p :class="menu2.status == 'done' ? 'text-green-600 font-semibold' : 'text-gray-500'"
                                            class=" col-span-2 text-center text-xs pt-0.5">
                                            {{ menu2.status }}
                                        </p> -->
                                        <p class=" col-span-4 text-sm text-right">
                                            {{ (accessory.accessory_price * accessory.quantity).toLocaleString() }} MMKs
                                        </p>
                                    </div>
                                </div>
                            </div>



                        </div>

                        <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section bg-white">
                            <!-- <div v-if="isPackage" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Package Price
                                </p>
                                <p class=" w-28">
                                    {{ selectedRoom.room_sessions[0].invoice.package.name }}
                                </p>
                            </div> -->
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
                            <div class="text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Food Discount
                                </p>
                                <p class="w-28">
                                    {{ foodDiscount > 0 ? '- ' : '' }} {{ foodDiscount }}
                                </p>
                            </div>
                            <!-- <div v-if="isPackage" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Package Discount
                                </p>
                                <p class=" w-28">
                                    {{ selectedRoom.room_sessions[0].invoice.package.package_discount > 0 ? '- ' : '' }}  {{ selectedRoom.room_sessions[0].invoice.package.package_discount }}
                                </p>
                            </div> -->
                            <div v-show="discount_type != 'customer_level'" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Discount
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.discount > 0 ? '- ' : '' }} {{ printInvoiceData.discount }} {{
                                        this.discount_type == 'percentage' ? '%' : 'MMKs' }}
                                </p>
                            </div>
                            <!-- <div v-show="discount_type == 'customer_level'" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Customer Discount
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.customer_discount > 0 ? '- ' : '' }} {{ printInvoiceData.customer_discount }} MMKs
                                </p>
                            </div> -->
                            <!-- <div v-show="roomSessionData.is_service == 1" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Service Price
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.service_total_value.toLocaleString() }} MMKs
                                </p>
                            </div> -->
                            <!-- <div v-show="roomSessionData.total_accessory_value > 0" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Accessories Price
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.accessory_total_value.toLocaleString() }} MMKs
                                </p>
                            </div> -->
                            <div class=" text-right pr-3 mb-3">
                                <p class="font-semibold">
                                    Total &nbsp;
                                    {{  (printInvoiceData.total ? printInvoiceData.total : 0).toLocaleString() }} MMKs
                                </p>
                            </div>
                            <!-- <div class="">
                                <button data-te-toggle="modal" data-te-target="#end_room_modal"
                                    class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
                                    Print Invoice
                                </button>
                            </div> -->

                            <!-- <div class=" text-right pr-3 mb-3">
                                <p class="">
                                    Total
                                    {{
                                        (selectedRoom ?
                                            ((purchaseMenuList.length > 0 ?
                                                (
                                                    (selectedRoom.total_session_price ?
                                                        selectedRoom.total_session_price : 0)
                                                    +
                                                    (purchaseMenuList.length > 0 ? purchaseMenuList[0].total : 0)
                                                    - (selectedRoom.invoice.package ? selectedRoom.invoice.package.package_discount : 0)
                                                )
                                                :
                                                (
                                                    (selectedRoom.total_session_price ?
                                                        selectedRoom.invoice.total_session_price : 0)
                                                    - (selectedRoom.invoice.package ? selectedRoom.invoice.package.package_discount : 0)
                                                )
                                            ) + selectedRoom.total_service_value + selectedRoom.total_accessory_value).toLocaleString()
                                            : 0
                                        )


                                    }}
                                        {{ (total_room_price + (selectedRoom ? selectedRoom.total_service_value : 0) + (selectedRoom ? selectedRoom.total_accessory_value : 0)).toLocaleString() }}
                                        MMKs
                                </p>
                            </div> -->
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
                                            <!-- <option value="room_discount"
                                                :disabled="selectedRoom.room_sessions[0].invoice.invoice_type == 'package'">
                                                Room Discount </option> -->
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
                                <div class="mb-4" v-show="discount_type == 'room_discount'">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Room Disount
                                    </label>
                                    <div class="relative">
                                        <select name="" id="" v-model="room_discount"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                            @change="roomDiscountSelectChanged">
                                            <option v-for="rd in roomDiscountList" :value="rd" :key="rd"> {{ rd.name }} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4"
                                    v-show="discount_type != 'room_discount' && discount_type != 'customer_level' && discount_type != 'birthday_discount'">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Discount
                                    </label>
                                    <input type="number" placeholder="Discount" v-model="printInvoiceData.discount"
                                        @input="discountChanged"
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
                                <div class="mb-4" v-if="roomSessionData.is_service == 1">
                                    <label for="" class="label-form mb-3">
                                        Service End Date
                                    </label>
                                    <input type="datetime-local" placeholder="End Date" v-model="serviceEndDate"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
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

                        <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section !pt-3 bg-white">
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
                            <div v-show="roomSessionData.is_service == 1" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Service Price
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.service_total_value.toLocaleString() }} MMKs
                                </p>
                            </div>
                            <div v-show="roomSessionData.total_accessory_value > 0" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                                <p>
                                    Accessories Price
                                </p>
                                <p class=" w-28">
                                    {{ printInvoiceData.accessory_total_value.toLocaleString() }} MMKs
                                </p>
                            </div>
                            <div class=" text-right pr-3 mb-3">
                                <p class="font-semibold">
                                    Total &nbsp;
                                    {{  (  (printInvoiceData.total ? printInvoiceData.total : 0)
                                        + (printInvoiceData.service_charge == true ? (printInvoiceData.service_tax ?
                                            printInvoiceData.service_tax : 0) : 0)
                                        + (printInvoiceData.isTax == true ? (printInvoiceData.tax ? printInvoiceData.tax : 0) :
                                    0) ).toLocaleString() }} MMKs
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


                    <div v-if="isOpenRoom.is_package == true" class="relative h-full pb-[75px]">
                        <div class="relative h-full overflow-y-auto small-scrollbar">
                            <div>
                                <div class="flex justify-center padding-section !pt-0 !pb-2">
                                    <p class="text-black text-lg">
                                        Menus
                                    </p>
                                </div>
                                <div class="relative pb-20 overflow-y-auto min-h-[40vh]" style="height:calc(100% - 329px)">
                                    <div class="padding-section !pt-0">
                                        <div class="table-container px-2">
                                            <table class=" w-full">
                                                <thead class="">
                                                    <tr class="border-b">
                                                        <th scope="col" class="text-left   py-4">
                                                            Menu
                                                        </th>
                                                        <!-- <th scope="col" class="text-left   py-4">
                                                            Area
                                                        </th> -->
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
                                                    <div class="contents" v-for="(pm,index) in packageMenuList" :key="index">
                                                        <tr class="" v-if="pm.is_package != -1">
                                                            <td class=" py-4 text-sm  ">
                                                                {{ pm.name }}
                                                            </td>
                                                            <!-- <td class=" py-4 text-sm  ">
                                                                <select name="" :class="pm.is_package == 0 ? 'hidden' : ''"
                                                                    class="text-xs pl-0 border-0 focus:shadow-none focus:outline-none focus:ring-0 select-box area-select-box !pr-6"
                                                                    placeholder="Select Area" @change="packageCookingAreaChange(area,index)" v-model="pm.area_id">
                                                                    <option disabled selected>Select Area</option>
                                                                    <option v-for="(area, index) in pm.areas" :key="index"
                                                                        :value="area.id" class="">
                                                                        {{ area.name }}
                                                                    </option>
                                                                </select>
                                                            </td> -->
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
                                                    </div>
                                                </tbody>
                                            </table>
                                        </div>
        
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 border-b border-gray-200 mb-10">
                                        <button class="w-full text-center mb-4 font-semibold text-sm focus:outline-none focus:ring-0" 
                                        data-te-toggle="modal" data-te-target="#add_package_menu_modal" @click="btnClickAddPackageMenuModal">
                                            Add More Menu
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="flex justify-center padding-section !pt-0 !pb-2">
                                    <p class="text-black text-lg">
                                        Accessories
                                    </p>
                                </div>
                                <div class="small-scrollbar overflow-y-auto min-h-[40vh] relative pb-20" style="height:calc(100% - 329px)">
                                    <div class="padding-section !pt-0">
                                        <div class="table-container px-2">
                                            <table class=" w-full">
                                                <thead class="">
                                                    <tr class="border-b">
                                                        <th scope="col" class="text-left   py-4">
                                                            Accessories
                                                        </th>
                                                        <!-- <th scope="col" class="text-left   py-4">
                                                            Area
                                                        </th> -->
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
                                                    <div class="contents" v-for="(pm,index) in packageAccessoriesList" :key="index">
                                                        <tr class="" v-if="pm.is_package != -1">
                                                            <td class=" py-4 text-sm  ">
                                                                {{ pm.name }}
                                                            </td>
                                                            <!-- <td class=" py-4 text-sm  ">
                                                                <select name="" :class="pm.is_package == 0 ? 'hidden' : ''"
                                                                    class="text-xs pl-0 border-0 focus:shadow-none focus:outline-none focus:ring-0 select-box area-select-box !pr-6"
                                                                    placeholder="Select Area" @change="packageCookingAreaChange(area,index)" v-model="pm.area_id">
                                                                    <option disabled selected>Select Area</option>
                                                                    <option v-for="(area, index) in pm.areas" :key="index"
                                                                        :value="area.id" class="">
                                                                        {{ area.name }}
                                                                    </option>
                                                                </select>
                                                            </td> -->
                                                            <td class=" py-4 text-sm  ">
                                                                {{ pm.quantity }}
                                                            </td>
                                                            <td class=" py-4 text-sm  ">
                                                                {{ pm.unit_price * pm.quantity  }}
                                                            </td>
                                                            <td class=" py-4 text-sm  text-center">
                                                                <button
                                                                    @click="removePackageAccessory(index)">
                                                                    <i class="fal fa-times  pr-3"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </div>
        
                                                </tbody>
                                            </table>
                                        </div>
        
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 border-b border-gray-200 focus:outline-none focus:ring-0 ">
                                        <button class="w-full text-center mb-4 font-semibold text-sm"
                                        data-te-toggle="modal" data-te-target="#add_package_accessory_modal" @click="btnClickedAddAccessory">
                                            Add More Accessories
                                        </button>
                                    </div>
                                </div>
                            </div>
                            

                            <!-- <div class="flex justify-center padding-section ">

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
                            </div> -->

                            
                        </div>
                        <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section !pt-3 bg-white">
                            
                            <div class="">
                                <button @click="createRoomForPackage()"
                                    class="bg-[#55EFC4] text-gray-700 text-center text-sm font-semibold w-full py-3">
                                    Confirm Package
                                </button>
                            </div>
                        </div>
                    </div>

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


        <!-- add Hour modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_hour_modal" tabindex="-1" aria-labelledby="addHourModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add More Hour
                        </p>
                        <button type="button" id="closeModal"
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
                            <!-- <label for="" class="block text-sm text-black mb-3">
                                Hour
                            </label> -->
                            <input type="number" placeholder="Hour" v-model="sessionDuration"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                                <input class="input-check-pos" type="checkbox" value="" id="checkboxChecked" checked />
                                <label class="inline-block pl-[0.15rem] hover:cursor-pointer" for="checkboxChecked">
                                    Charge
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="btnAddHour()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Add Hours
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- add Menu modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_menu_modal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add Menu
                        </p>
                        <button type="button" id="closeAddMenuModal"
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
                                :clear-on-select="false" :preserve-search="true" placeholder="Select Menu" label="name" :custom-label="nameWithPrice"
                                track-by="id" :preselect-first="false">
                            </multiselect>

                            <!-- <select name="" id="" placeholder="Menu" v-model="selectedMenu" @change="selectedMenuChange()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="menu" v-for="(menu, index) in menuList" :key="index">{{ menu.name }}</option>
                            </select> -->
                        </div>
                        <!-- <div class="mb-4">
                            <select name="" id="" placeholder="Menu" v-model="selectedMenuArea"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="area" v-for="(area, index) in menuAreaList" :key="index">{{ area.name }}</option>
                            </select>
                        </div> -->
                        <div class="mb-4">
                            <!-- <label for="" class="block text-sm text-black mb-3">
                                Hour
                            </label> -->
                            <input type="number" placeholder="Qty" v-model="menuQuantity"
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
            id="add_package_menu_modal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add Package Menu
                        </p>
                        <button type="button" id="closeMenuModal"
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
                                <option :value="menu" v-for="(menu, index) in menuList" :key="index">{{ menu.name }} ( {{ menu.prices[0].price }} Ks 
                                )</option>
                            </select>
                        </div>
                        <!-- <div class="mb-4">
                            <select name="" id="" placeholder="Menu" v-model="selectedMenuAreaForPackage"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="area" v-for="(area, index) in menuAreaListForPackage" :key="index">{{ area.name }}</option>
                            </select>
                        </div> -->
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
            id="change_modal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Change Room
                        </p>
                        <button type="button" id="close_change_room_modal"
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
                            <select name="" id="" placeholder="Select Room" v-model="change_room"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="changeableRoom" :key="index"
                                    v-for="(changeableRoom, index) in changeableRoomList">{{ changeableRoom.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <input type="datetime-local" placeholder="Time" v-model="start_date_time" @change="getPackageList(invoice_date)"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
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
            id="add_service_modal_room" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add Service
                        </p>
                        <button type="button" id="closeServiceModalRoom"
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
                                        lady.staff ? lady.staff.name : '' }}</option>
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
            id="service_end_modal_room" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            End Service
                        </p>
                        <button type="button" id="closeEndServiceModalRoom"
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
            id="add_accessory_modal_room" tabindex="-1" aria-labelledby="addAccessoryModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add Accessory
                        </p>
                        <button type="button" id="closeAccessoryModalRoom"
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
                            Add Accessory
                        </button>
                    </div>
                </div>
            </div>
        </div>

            <!-- add package accessory modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_package_accessory_modal" tabindex="-1" aria-labelledby="addAccessoryModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Add Accessory
                        </p>
                        <button type="button" id="closeAccessoryModalRoom"
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
                        <button @click="btnConfirmAddPackageAccessory()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Add Accessory
                        </button>
                    </div>
                </div>
            </div>
        </div>


            <!--  modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="end_room_modal" tabindex="-1" aria-labelledby="addAccessoryModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none py-4">
                    <div class="relative  p-4 mb-4">
                        <p class="text-xl w-full text-center">
                            End Room ( {{ selectedRoom ? selectedRoom.name : ''}} )
                        </p>
                        <button type="button" id="closeEndRoomModal"
                            class="absolute top-4 right-4 focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>


                    <div class="flex justify-center gap-x-8 px-12 mb-6">
                        <button data-te-modal-dismiss>Cancel</button>
                        <button  @click="btnClickedEndRoom()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            End Room
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
    import moment from "moment";
    import flatpickr from "flatpickr";
    import "flatpickr/dist/flatpickr.min.css";

    export default {
        name:'PosRoomComponent',
        props:{
            roomAreaId:{
                type: Number,
                required: true
            },
            area:{
                type: Object,
                required: true
            }
        },
        components:{
            Multiselect
        },
        // props: ['roomAreaId'],
        data() {
            return {
                roomList:[],
                areaList: [],
                isOpenRoom:{
                    open_1: false,
                    open_2: false,
                    detail: false,
                    invoice: false,
                    is_package:false,
                },

                selectedAreaId:null,
                selectedTime:null,
                selectedRoom:null,

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
                discountTypeList:[],

                selectedDiscountType:null,
                selectedCustomer:null,
                invoice_date:null,
                type:'session',
                selectedPackage:null,
                isPreDeposit: false,
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
                    service_total_value:0,
                    accessory_total_value:0,
                    total:0,
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
                packageAccessoriesList:[],
                menuAreaListForPackage:[],
                selectedMenuForPackage:null,
                selectedMenuAreaForPackage:null,
                menuQuantityForPackage:null,
                is_menu_discount:0,
                food_total_package:0,
                accessory_total_package:0,
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


                currentTime:parseInt(getCurrentTime().split(':')),
                currentTimeForPackage: getCurretDateTime(),
                start_date_time:getCurretDateTime(),
                isShowSidebar:false,
                testbro:null,


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

                cashAccounts: [],
                selectedCashAccount: null,

                depositBalance: null,

                total_room_price:0,
                entityType: null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),
            handleClick() {
                this.$emit('callParent');
            },
            async getRoomList(area){
                if(this.area.area_type.type == 'ktv'){
                    const response = await getApiData({ url: '/api/areas/' + this.roomAreaId + '/entities' , token: this.getToken()});
                    if(response.data){
                        this.roomList = response.data;
                        console.log(response.data)
                        if(response.data[0]){
                            this.entityType = response.data[0].entity_type
                        }
                    }
                }
                
            },
            async btnClickedSession(time, timeIndex, room , roomIndex) {
                this.isShowSidebar = true;
                this.selectedTime = time;
                this.selectedRoom = null;

                this.selectedRoomId = room.id;
                // this.getSelectedRoom();
                if (this.roomList[roomIndex].entity_sessions[timeIndex].is_active == 1) {
                    this.getPurchaseMenuList(); // why is this called
                    const response = await getApiData({ url: '/api/entities_sessions/'+ this.selectedTime.id, token: this.getToken() });
                    if (response.data) {
                        if(response.data.deposit_balance > 0){
                            this.depositBalance = response.data.deposit_balance;
                        }
                        this.selectedRoom = response.data;
                        this.serviceList = response.data.services;
                        this.accessoryListSidebar = response.data.invoice_accessories;
                        this.getTotal(response.data);
                        this.isOpenRoomStep('detail');

                        this.printInvoiceData.room = response.data.total_session_price
                        this.printInvoiceData.food = response.data.total_order_value
                        this.printInvoiceData.total = response.data.invoice_total - response.data.total_order_discount_price

                    }
                }
                else {
                    this.selectedRoom = this.roomList[roomIndex];
                    this.isOpenRoomStep('open_1');
                    console.log('open 1')
                };
                this.getPackageList(this.currentTimeForPackage);                
            },

            btnClickedOpenRoom() {
                this.isOpenRoomStep('open_2');
                // this.clearOpenRoomForm();
                this.isPackage = false;
                this.getCustomerList();
                this.selectedCustomer = null;
                this.invoice_date = null;
                this.duration = null;
                this.selectedPackage = null;
                this.type = null;
                this.isPreDeposit = false;
                this.deposit = null;
                this.selectedCashAccount = null;
                this.female = null;
                this.male = null;
                this.child = null;
                this.selectedDiscountType = null;
                this.getDiscountTypeList();

                // this.$nextTick(() => {
                //     flatpickr(this.$refs.datetimeInput, {
                //         enableTime: true,
                //         dateFormat: "Y-m-d H:i",
                //         time_24hr: true,
                //         onChange: (selectedDates, dateStr) => {
                //             this.invoice_date = dateStr;
                //         }
                //     });
                // });
            },

            // step 2's methods
            async getCustomerList() {
                const response = await getApiData({ url: '/api/customers', token: this.getToken() });
                if (response.data) {
                    this.customerList = response.data;
                }
            },
            async getDiscountTypeList(id) {
                const response = await getApiData({ url: '/api/pos/get_room_discount_list?room_id=' + this.selectedRoom.id, token: this.getToken() });
                if (response.data) {
                    this.discountTypeList = response.data;
                }
            },
            async getPackageList(time) {
                const response = await getApiData({ url: '/api/packages?date='+ time + '&selling_area_id=' + this.area.id +  '&room_id=' + this.selectedRoom.id, token: this.getToken() });
                if (response.data) {
                    this.packageList = response.data.data;
                }
            },
            getSelectedPackage(){
                // this.packageMenuList = this.selectedPackage;



            },
            confirmRoomBtnClicked() {
                // this.getPurchaseMenuList();
                if(this.type == 'package' && this.selectedPackage){
                    this.packageMenuList = [];
                    this.packageAccessoriesList = [];
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
                            // area_id: null,
                            areas : packageMenu.menu.areas,
                            cooking_area_id: packageMenu.menu.cooking_area_id
                        });

                    });
                    let accessoriesOfselectedPackage = this.selectedPackage.accessories;
                    accessoriesOfselectedPackage.forEach((accessories)=>{
                        // let is_dis_menu_price = 0;
                        this.accessory_total_package +=  accessories.accessory.accessory_price.price * accessories.quantity;
                        this.packageAccessoriesList.push({
                            quantity : accessories.quantity,
                            name : accessories.accessory.name,
                            // original_price : accessories.menu.prices[0].price,
                            // discount_value: is_dis_menu_price,
                            accessory_id : accessories.accessory_id,
                            is_package : 1,
                            unit_price: accessories.accessory.accessory_price.price
                            // area_id: null,
                            // areas : accessories.menu.areas
                        });

                    });

                    console.log('package')
                }
                else{
                    this.createRoom();
                }
            },
            btnClickAddPackageMenuModal(){
                this.getMenuList();
                this.selectedMenuForPackage = null;
                this.menuQuantityForPackage = null;
            },
            async selectedPackageMenuChange() {
                // const response = await getApiData({ url: '/api/menus/' + this.selectedMenuForPackage.id + '/areas', token: this.getToken() });
                // if (response.data) {
                //     this.menuAreaListForPackage = response.data.areas;
                // }
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
                    cooking_area_id:this.selectedMenuForPackage.cooking_area_id
                    // area_id: this.selectedMenuAreaForPackage.id
                });
                this.food_total_package += (this.selectedMenuForPackage.prices[0].price - this.is_menu_discount) * this.menuQuantityForPackage;
                this.selectedMenu = null
                this.menuAreaListForPackage = []
                // this.selectedMenuAreaForPackage = null;
                this.menuQuantityForPackage = null;
                document.getElementById('add_package_menu_modal').click();
            },
            removePackageMenu(index){

                this.food_total_package -= (this.packageMenuList[index].original_price - this.packageMenuList[index].discount_value) * this.packageMenuList[index].quantity;
                if(this.packageMenuList[index].is_package == '0'){
                    this.packageMenuList.splice(index, 1);
                }
                else{
                    this.packageMenuList[index].is_package = -1
                }
            },
            
            btnConfirmAddPackageAccessory() {
                this.packageAccessoriesList.push({
                    quantity: this.selectedAccessoryQuantity,
                    name: this.selectedAccessory.name,
                    // original_price:this.selectedMenuForPackage.prices[0].price,
                    price:this.selectedAccessory.accessory_price.price * this.selectedAccessoryQuantity,
                    unit_price:this.selectedAccessory.accessory_price.price,
                    accessory_id : this.selectedAccessory.id,
                    is_package : 0,
                });
                this.accessory_total_package += (this.selectedAccessory.accessory_price.price) * this.selectedAccessoryQuantity;
                this.selectedAccessoryCategory = null
                this.selectedAccessory = null
                this.accessoryList = []
                this.selectedAccessoryQuantity = null;
                document.getElementById('add_package_accessory_modal').click();
            },
            removePackageAccessory(index){

                this.accessory_total_package -= this.packageAccessoriesList[index].price;
                if(this.packageAccessoriesList[index].is_package == '0'){
                    this.packageAccessoriesList.splice(index, 1);
                }
                else{
                    this.packageAccessoriesList[index].is_package = -1
                }
                // this.packageAccessoriesList.splice(index, 1);
            },

            createRoomForPackage(){

                // this.packageMenuList.forEach((packageMenu)=>{
                //     this.total_package_menu_price += (packageMenu.original_price - packageMenu.discount_value ) * packageMenu.quantity;
                // });
                let packageActualTotal = this.food_total_package + (this.selectedPackage.session_price * this.selectedPackage.pay_session) + this.accessory_total_package;
                console.log('pack total = ' + packageActualTotal)
                if(packageActualTotal < (this.selectedPackage.price - this.selectedPackage.package_discount) ){
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
                if(this.isPreDeposit && !this.deposit && this.selectedCashAccount){
                    this.alertValiationMessage(`deposit amount or cash account`);
                    return;
                }
                let formData = new FormData();
                // formData.append('entity_id', this.selectedRoom.id);
                formData.append('entity_session_id', this.selectedTime.id);
                formData.append('customer_id', this.selectedCustomer.id);
                formData.append('start_time', this.invoice_date);
                if(this.selectedDiscountType != null){
                    formData.append('room_discount_id', this.selectedDiscountType.id);
                }
                else{
                    formData.append('room_discount_id', '');
                }
                if (this.type == 'session') {
                    formData.append('session_duration', this.duration);
                }
                if (this.type == 'package') {
                    formData.append('package_id', this.selectedPackage.id);
                    formData.append('orders', JSON.stringify(this.packageMenuList));
                    formData.append('accessories', JSON.stringify(this.packageAccessoriesList));
                }
                formData.append('type', this.type);
                let isDeposit = (this.isPreDeposit)? 1: 0;
                formData.append('is_deposit', isDeposit);
                if(this.deposit){
                    formData.append('deposit', this.deposit);
                }
                if(this.selectedCashAccount){
                    formData.append('cash_account_id', this.selectedCashAccount.id);
                    formData.append('account_id', this.selectedCustomer.account_id);
                }

                if (this.female > 0) {
                    formData.append('female', +this.female);
                }
                if (this.male > 0) {
                    formData.append('male', +this.male);
                }
                if (this.child > 0) {
                    formData.append('child', +this.child);
                }
                formData.append('entity_type', this.entityType);
                formData.append('entity_id',this.selectedRoom.id);
                let response = await postApiData({ url: '/api/entities/start', form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.getRoomList();
                    this.getSelectedRoom();
                    this.isOpenRoomStep('detail');
                    
                    
                    // if (this.selectedRoom.room_sessions.length > 0) {
                    //     if (this.selectedRoom.room_sessions[0].invoice.orders.length > 0) {
                    //         this.getPurchaseMenuList();
                    //     }
                    // }
                    // if (this.selectedRoom.room_sessions.length < 1) {
                    //     if (this.selectedRoom.room_sessions[0].invoice.orders.length < 1) {
                    //         this.purchaseMenuList = [];
                    //     }
                    // }
                    this.food_total_package = 0
                    // this.uiInShow = 

                    // (selectedRoom ?
                    //     ((purchaseMenuList.length > 0 ?
                    //         (
                    //             (selectedRoom.total_session_price ? selectedRoom.total_session_price : 0)
                    //             + (purchaseMenuList.length > 0 ? purchaseMenuList[0].total : 0)
                    //             - (selectedRoom.invoice.package ? selectedRoom.invoice.package.package_discount : 0)
                    //         )
                    //         :
                    //         (
                    //             (selectedRoom.total_session_price ? selectedRoom.invoice.total_session_price : 0)
                    //             - (selectedRoom.invoice.package ? selectedRoom.invoice.package.package_discount : 0)
                    //         )
                    //     ) + selectedRoom.total_service_value + selectedRoom.total_accessory_value).toLocaleString()
                    //     : 0
                    // )
                    
                    
                    // console.log("success")
                    // window.location.reload()
                }
                else {
                    this.$notify({
                        title: `Not valid`,
                        text: response.message,
                        type: "warn"
                    });
                }
            },
            async getPurchaseMenuList() {
                const response = await getApiData({ url: '/api/entities_sessions/'+ this.selectedTime.id, token: this.getToken() }); // and why is this api also called
                if (response.data) {
                    if(response.data.deposit_balance > 0){
                        this.depositBalance = response.data.deposit_balance;
                    }
                    // if (response.data.invoice) {
                    //     this.purchaseMenuList = response.data.invoice.orders;
                    //     if (response.data.invoice.orders) {
                    //         if (response.data.invoice.orders[0].total_discount_price) {
                    //             this.foodDiscount = response.data.invoice.orders[0].total_discount_price
                    //         }
                    //     }
                    // }
                    if (response.data.invoice) {
                        this.purchaseMenuList = response.data.invoice.orders;
                        if (response.data.invoice.orders.length > 0) {
                            this.foodDiscount = response.data.invoice.orders[0].total_discount_price
                        }
                    }
                    this.getTotal(response.data)
                    console.log('get purchase menu')
                }
            },
            async getSelectedRoom(){
                const response = await getApiData({ url: '/api/entities_sessions/'+ this.selectedTime.id, token: this.getToken() });
                    if (response.data) {
                        if(response.data.deposit_balance > 0){
                            this.depositBalance = response.data.deposit_balance;
                            // alert(this.depositBalance);
                        }
                        this.selectedRoom = response.data;
                        this.serviceList = response.data.services;
                        this.purchaseMenuList = response.data.invoice.orders;
                        this.accessoryListSidebar = response.data.invoice_accessories;
                        console.log('get selected room')
                        this.getTotal(response.data);
                        this.printInvoiceData.room = this.selectedRoom.total_session_price
                        this.printInvoiceData.food = this.selectedRoom.total_order_value
                        this.printInvoiceData.total = this.selectedRoom.invoice_total - this.selectedRoom.total_order_discount_price
                        
                    }
            },
            getTotal(roomData){
                let totalSessionPrice = 0;
                let totalOrderPrice = 0 ;
                let packageDiscount = 0;
                if(roomData.total_session_price){
                    totalSessionPrice = roomData.total_session_price
                    console.log('totalSessionprice = ' + totalSessionPrice)
                };
                if(roomData.invoice.orders.length > 0){
                    totalOrderPrice = roomData.invoice.orders[0].total
                    console.log('totalOrderPrice = ' + totalOrderPrice)
                }
                if(roomData.invoice.package){
                    packageDiscount = roomData.invoice.package.package_discount
                    console.log('packageDiscount = ' + packageDiscount)
                }
                this.total_room_price = totalSessionPrice + totalOrderPrice - packageDiscount
            },
            //invoice or done
            btnBackToDetail() {
                this.isOpenRoomStep('detail');
            },
            btnClickedDoneSession() {
                this.doneSession();
                // this.getPurchaseMenuList();
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
                formData.append('invoice_id', this.selectedRoom.invoice.id);
                formData.append('entity_type', this.entityType);
                formData.append('entity_id',this.selectedRoom.entity_id);
                console.log('entity id ',this.selectedRoom.entity_id)
                // if(this.depositBalance > 0){
                //     formData.append('deposit_balance', this.depositBalance);
                // }
                let response = await postApiData({ url: '/api/room_done', form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.roomSessionData = response.data;
                    roomSessions = response.data;
                    console.log("success")
                    this.isOpenRoomStep('invoice')
                    this.printInvoiceData.service_total_value = response.data.total_service_value;
                    this.printInvoiceData.accessory_total_value = response.data.total_accessory_value;
                    this.printInvoiceData.food = response.data.total_order_value;
                    // this.depositBalance = null;
                    this.foodDiscount = response.data.total_order_discount_price;
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
                if(this.selectedRoom.invoice.invoice_type == 'endless_time'){
                    roomChargeTotal = this.roomSessionData.total_session_price;
                }
                else{
                    roomChargeTotal = roomSessions.total_session_price;
                }
                // roomChargeTotal += roomSession.price; // or roomSession.session_duration * roomSession.entity.price_per_hour;
                // });
                this.printInvoiceData.room = roomChargeTotal; // <== or that


                // if (this.purchaseMenuList.length > 0) {
                //     this.printInvoiceData.food = this.purchaseMenuList[0].total
                //     this.purchaseMenuList[0].order_items.forEach(element => {
                //         this.orderList.push({
                //             'menu_category_id': element.menu.menu_category_id,
                //             'price': element.price,
                //         })
                //     });
                // }
                if (this.selectedRoom.invoice.invoice_type == 'package') {
                    this.printInvoiceData.room = this.selectedRoom.invoice.total_session_price;
                    this.printInvoiceData.package_discount = this.selectedRoom.invoice.package.package_discount;
                    this.isPackage = true;
                    this.packagePrice = this.selectedRoom.invoice.paid_amount
                    this.printInvoiceData.total = (this.printInvoiceData.room + this.printInvoiceData.food + this.printInvoiceData.service_total_value + this.printInvoiceData.accessory_total_value) - this.printInvoiceData.package_discount - this.foodDiscount;
                }
                else {
                    this.isPackage = false;
                    this.printInvoiceData.total = (roomChargeTotal + this.printInvoiceData.food + this.printInvoiceData.service_total_value + this.printInvoiceData.accessory_total_value) - this.foodDiscount;
                }


                // room price = this.printInvoiceData.room
                if (this.service_charge = true) {
                    this.printInvoiceData.service_tax = this.printInvoiceData.total * 0.15 // 0.05(5%) is used before
                }
                if (this.isTax = true) {
                    // this.printInvoiceData.tax = this.printInvoiceData.food * 0.05 used before
                    this.printInvoiceData.tax = this.printInvoiceData.total * 0.05
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
            roomDiscountSelectChanged() {
                let roomChargeTotal = 0;
                let roomSessions = this.roomSessionData;
                let totalSession = 0;
                let originalSessions = this.room_discount.session;
                let discountSessions = this.room_discount.free_session;
                let totalDiscounts = originalSessions + discountSessions;
                let paidSession = 0;
                let pricePerHour = 0;
                roomSessions.rooms_sessions.forEach(roomSession => {
                    totalSession += parseFloat(roomSession.session_duration);
                    pricePerHour = roomSession.entity.price_per_hour;
                });

                if (totalSession <= totalDiscounts) {
                    if (totalSession > originalSessions) {
                        paidSession = originalSessions;
                    }
                    else {
                        paidSession = totalSession;
                    }
                }
                else {
                    let q = Math.floor(totalSession / totalDiscounts);
                    let qProdOrig = q * originalSessions;
                    let r = totalSession % totalDiscounts;
                    paidSession = (qProdOrig + r);
                }
                let test = this.roomSessionData.total_session_price - (discountSessions * pricePerHour)
                console.log('new total = ' + test)
                roomChargeTotal = paidSession * pricePerHour;
                if (this.selectedRoom.invoice.invoice_type == 'package') {
                    this.printInvoiceData.room = this.selectedRoom.total_session_price;
                }
                else {
                    this.printInvoiceData.room = roomChargeTotal;
                }
                this.printInvoiceData.roomDiscountAmount = roomChargeTotal;
                this.printInvoiceData.discountSession = totalSession - paidSession;
                this.printInvoiceData.total = this.printInvoiceData.room + this.printInvoiceData.food -this.foodDiscount;
            },
            birthdayDiscountSelectChanged() {
                this.printInvoiceData.discount = this.birthday_discount.discount_value
                if (this.selectedRoom.invoice.invoice_type == 'package') {
                    this.printInvoiceData.room = (this.roomSessionData.total_session_price) - this.selectedRoom.invoice.package.package_discount;
                }
                else {
                    this.printInvoiceData.room = this.roomSessionData.total_session_price;
                }
                console.log(this.printInvoiceData.room,this.printInvoiceData.food,this.printInvoiceData.discount,this.foodDiscount);
                this.printInvoiceData.total = (this.printInvoiceData.room + this.printInvoiceData.food) - this.printInvoiceData.discount - this.foodDiscount
            },

            async getRoomDiscount() {
                this.birthday_discount = null;
                this.room_discount = null;
                this.printInvoiceData.discount = 0;
                if (this.discount_type == 'room_discount') {
                    const response = await getApiData({ url: '/api/room_discounts', token: this.getToken() });
                    if (response.data) {
                        this.roomDiscountList = response.data.data;
                        console.log('get room dis list' + response.data.data)
                        // this.printInvoiceData.total = this.printInvoiceData.room + this.printInvoiceData.food;
                    }
                    this.printInvoiceData.total = ((this.printInvoiceData.room + this.printInvoiceData.food) - this.foodDiscount);

                }
                else if (this.discount_type == 'birthday_discount') {

                    const response = await getApiData({ url: '/api/birthday_promotions', token: this.getToken() });
                    if (response.data) {
                        this.birthdayDiscountList = response.data.data;
                    }
                    this.printInvoiceData.total = (this.printInvoiceData.room + this.printInvoiceData.food) - this.foodDiscount;
                }

                else if (this.discount_type == 'customer_level') {

                    if (this.selectedRoom.invoice.invoice_type == 'package') {
                        // this.printInvoiceData.room = this.selectedRoom.room_sessions[0].invoice.package.pay_session * this.selectedRoom.room_sessions[0].invoice.package.session_price;
                        // this.printInvoiceData.customer_discount = this.roomSessionData.customer_level_discount_value + this.selectedRoom.room_sessions[0].invoice.package.package_discount + this.selectedRoom.room_sessions[0].invoice.orders[0].total_discount_price;
                        this.printInvoiceData.customer_discount = this.roomSessionData.customer_level_discount_value;
                        this.printInvoiceData.total = (this.roomSessionData.total_session_price + this.printInvoiceData.food) - (this.printInvoiceData.package_discount + this.foodDiscount + this.printInvoiceData.customer_discount);
                    }
                    else {
                    this.printInvoiceData.customer_discount = this.roomSessionData.customer_level_discount_value;
                        this.printInvoiceData.total = ((this.roomSessionData.total_session_price + this.printInvoiceData.food) - ( this.foodDiscount + this.printInvoiceData.customer_discount));
                    }

                }
                else {
                    this.roomDiscountList = [];
                    this.room_discount = null;
                    let roomChargeTotal = 0;
                    let roomSessions = this.roomSessionData;
                    // roomSessions.forEach(roomSession => {
                    roomChargeTotal = roomSessions.total_session_price;
                    // });
                    if (this.selectedRoom.invoice.invoice_type == 'package') {
                        this.printInvoiceData.room = (this.roomSessionData.total_session_price - this.selectedRoom.invoice.package.package_discount);
                    }
                    else {
                        // this.printInvoiceData.room = roomChargeTotal;
                        this.printInvoiceData.room = this.roomSessionData.total_session_price;
                    }
                    this.printInvoiceData.roomDiscountAmount = null;
                    this.printInvoiceData.discountSession = null;
                    console.log(this.printInvoiceData.room, this.printInvoiceData.food);
                    this.printInvoiceData.total = ((this.printInvoiceData.room + this.printInvoiceData.food + this.printInvoiceData.service_total_value) - (this.foodDiscount ));
                    // this.printInvoiceData.total = this.printInvoiceData.room + this.printInvoiceData.food;
                }
            },
            discountChanged() {
                let roomTotalAmount = (this.roomSessionData.total_session_price + this.printInvoiceData.food + this.printInvoiceData.service_total_value) - this.printInvoiceData.package_discount - this.foodDiscount
                console.log('room total = ' + this.printInvoiceData.room)
                if (this.discount_type == 'percentage') {
                    this.printInvoiceData.total = roomTotalAmount - (roomTotalAmount * (this.printInvoiceData.discount / 100));
                    this.printInvoiceData.percent_discount_amount = roomTotalAmount * (this.printInvoiceData.discount / 100);
                }
                else {
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
                formData.append('invoice_id', this.selectedRoom.invoice.id);
                formData.append('entity_type', this.entityType);
                formData.append('entity_id',this.selectedRoom.entity_id);
                // formData.append('payment_type', this.selectedPaymentMethod);
                if(this.discount_type){
                    formData.append('discount_type', this.discount_type);
                }

                if(this.depositBalance > 0){
                    formData.append('deposit_balance', this.depositBalance);
                }
                if (this.discount_type == 'fix_amount') {
                    formData.append('discount_value', this.printInvoiceData.discount);
                    // totalAmount = totalAmount - this.printInvoiceData.discount;
                }
                if (this.discount_type == 'percentage') {
                    formData.append('discount_value', this.printInvoiceData.percent_discount_amount);
                }
                if (this.discount_type == 'room_discount') {
                    formData.append('room_discount_id', this.room_discount.id);
                }
                if (this.discount_type == 'birthday_discount') {
                    formData.append('birthday_promotion_id', this.birthday_discount.id);
                    formData.append('birthday_discount', this.birthday_discount.discount_value);
                }
                if(this.discount_type == 'customer_level'){
                    formData.append('customer_level_discount',this.roomSessionData.customer_level_discount_value);
                }
                // formData.append('order_categories', JSON.stringify(this.orderList));
                if (this.printInvoiceData.service_charge) {
                    formData.append('service_charge', this.printInvoiceData.service_tax);
                    totalAmount = totalAmount + this.printInvoiceData.service_tax
                }
                if (this.printInvoiceData.isTax) {
                    formData.append('tax', this.printInvoiceData.tax);
                    totalAmount = totalAmount + this.printInvoiceData.tax
                }
                // formData.append('total', this.printInvoiceData.total);
                if (this.room_discount) {
                    formData.append('room_discount_amount', this.printInvoiceData.roomDiscountAmount);
                    formData.append('discount_session', this.printInvoiceData.discountSession);
                }
                if (this.selectedRoom.invoice.invoice_type == 'package') {
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
                formData.append('end_date', this.serviceEndDate);
                console.log(formData)
                let response = await postApiData({ url: '/api/entities/done', form_data: formData, token: this.getToken() });
                if (response.success) {
                    await this.getRoomList();
                    // this.selectedRoom = await this.roomList[this.selectedRoomIndex];
                    this.isOpenRoom.step_1 = true;
                    this.isOpenRoom.step_2 = false;
                    this.isOpenRoom.detail = false;
                    this.isOpenRoom.invoice = false;
                    this.isShowSidebar = false;

                    console.log("success");
                    document.getElementById('closeEndRoomModal').click();
                }
                else {
                    this.$notify({
                        title: `Not valid`,
                        text: response.message,
                        type: "warn"
                    });
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
                const response = await getApiData({ url: '/api/menus?selling_area_id=' + this.area.id, token: this.getToken() });
                if (response.data) {
                    this.menuList = response.data;
                }
            },
            async selectedMenuChange() {
                // const response = await getApiData({ url: '/api/menus/' + this.selectedMenu.id + '/areas', token: this.getToken() });
                // if (response.data) {
                //     this.menuAreaList = response.data.areas;
                //     this.menuQuantity = 1;
                // }
                this.menuQuantity = 1;
            },
            btnClickAddMenu() {
                this.invoiceId = this.selectedRoom.invoice.id;
                console.log('invoice id ' + this.invoiceId)
                this.menuQuantity = null;
                this.selectedMenu = null;
                this.remark = null;
                this.getMenuList();
            },
            btnConfirmAddMenu() {
                this.addMenu();
            },
            async addMenu() {
                let formData = new FormData();
                formData.append('invoice_id', this.invoiceId);
                formData.append('menu_id', this.selectedMenu.id);
                // formData.append('area_id', this.selectedMenuArea.id);
                formData.append('quantity', this.menuQuantity);
                formData.append('original_price', this.selectedMenu.prices[0].price);
                formData.append('remark', this.remark);
                formData.append('selling_area_id', this.area.id);
                let response = await postApiData({ url: '/api/entities/orders', form_data: formData, token: this.getToken() });
                // console.log(this.invoiceId+','+this.selectedMenu.id + ','+ this.menuQuantity +','+this.selectedMenu.prices[0].price)
                if (response.success) {
                    this.closeModal('closeAddMenuModal');
                    this.clearMenuForm();
                    this.getPurchaseMenuList();
                    this.getSelectedRoom();
                }
                else {
                    this.$notify({
                        title: `Not valid`,
                        text: response.message,
                        type: "warn"
                    });
                    console.log('some errors occur');
                }
            },
            btnAddHour() {
                this.addHour();
            },
            async addHour() {
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.invoice.id);
                formData.append('session_duration', this.sessionDuration);
                let response = await postApiData({ url: '/api/entities/add_more_sessions', form_data: formData, token: this.getToken() });
                if (response.success) {
                    console.log("success")
                    await this.getRoomList();
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
                formData.append('invoice_id', this.selectedRoom.invoice.id);
                formData.append('entity_id', this.change_room.id);
                formData.append('start_date_time', this.start_date_time);
                formData.append('entity_type', this.entityType);
                formData.append('previous_entity_id',this.selectedRoom.entity_id);
                console.log('previous entity id ',this.selectedRoom.entity_id)
                let response = await postApiData({ url: '/api/entities/change', form_data: formData, token: this.getToken() });
                console.log('change room ' + this.selectedRoom.invoice.invoice_id + ',' + this.change_room.id)
                if (response.success) {
                    console.log("success");
                    await this.getRoomList();
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
                const response = await getApiData({ url: '/api/areas/' + this.roomAreaId + '/inactive_entities?type=room', token: this.getToken() });
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
                const response = await getApiData({ url: '/api/get_service?service_category_id=' + this.selectedServiceCategory.id + '&area_id=' + this.roomAreaId, token: this.getToken() });
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
                if(!this.selectedServiceCategory){
                    this.alertValiationMessage('Service Category')
                }
                else if(!this.selectedServiceStartTime){
                    this.alertValiationMessage('Start Time')
                    console.log('start hello')
                }
                else if(this.selectedServiceCategory.name == 'Lady' && !this.selectedLady){
                    this.alertValiationMessage('Lady')
                }
                else if(this.selectedServiceCategory.name == 'DJ' && !this.selectedDj){
                    this.alertValiationMessage('DJ')
                }
                else{
                    this.addService();
                }
            },
            async addService() {   // invoice pay yan
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.invoice.id);
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
                    this.closeModal('closeServiceModalRoom');
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
                    this.closeModal('closeEndServiceModalRoom');
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
            btnClickedAddAccessory(){
                this.selectedAccessoryCategory = null;
                this.accessoryList = [];
                this.selectedAccessoryQuantity = 0;
                this.getAccessoryCategoryList();
            },
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
            alertValiationMessage(field) {
                this.$notify({
                    title: `Input validation`,
                    text: `You forgot to provide ${field}, please try again`,
                    type: "warn"
                });
            },
            btnConfirmAddAccessory() {

                if(!this.selectedAccessoryCategory){
                    this.alertValiationMessage('Accessory Category');
                }
                if(!this.selectedAccessory){
                    this.alertValiationMessage('Accessory');
                }
                if(!this.selectedAccessoryQuantity){
                    this.alertValiationMessage('Quantity');
                }
                else{
                    this.addAccessory();
                }
            },
            async addAccessory() {   // invoice pay yan
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.invoice.id);
                formData.append('accessory_id', this.selectedAccessory.id);
                formData.append('quantity', this.selectedAccessoryQuantity);
                formData.append('accessory_price', this.selectedAccessory.accessory_price.price);
                let response = await postApiData({ url: '/api/pos/add_accessory', form_data: formData, token: this.getToken() });
                // console.log(this.invoiceId+','+this.selectedMenu.id + ','+ this.menuQuantity +','+this.selectedMenu.prices[0].price)
                if (response.success) {
                    this.closeModal('closeAccessoryModalRoom');
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
                this.remark = null
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
            clearAddHourForm() {
                this.sessionDuration = null
            },
            clearChangeRoomForm() {
                this.change_room = null
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
                        // console.log('is open room true')
                    }
                    else {
                        this.isOpenRoom[key] = false;
                        // console.log('is open room false')
                    }
                }
            },

            testtime(){
                let targetTime = '16:00'
                const currentTime = new Date();
                const [targetHour, targetMinute] = targetTime.split(':').map(Number);

                // Create a new Date object with the current date and target time
                const targetDateTime = new Date();
                targetDateTime.setHours(targetHour, targetMinute, 0, 0);

                // Check if current time is past the target time
                let test_time = currentTime > targetDateTime;
                console.log(test_time)

            },

            async getCashAccounts(){
                let response = await getApiData({url: `/api/get_cash_account`, token: this.getToken()});
                if(response.success){
                    let posCashAccount = response.data.find(account => account.account_code === '2-1011');
                    if (posCashAccount) {
                        this.cashAccounts.push(posCashAccount);
                    }
                    let posBankAccount = response.data.find(account => account.account_code === '2-1012');
                    if (posBankAccount) {
                        this.cashAccounts.push(posBankAccount);
                    }
                    // this.cashAccounts = response.data;
                }
            },
            isTimeActive(time){
                let currentTime = moment().format("HH:mm:ss");
                if(time.is_active == 1){
                    return 'bg-[#FF7675]';
                }
                else{
                    if(moment(time.end_time, "HH:mm:ss").isBefore(moment(), "second")){
                        return 'bg-[#eed202]';
                    }
                    else{
                        return 'bg-[#4fe0b7]';
                    }
                }
            },
            nameWithPrice ({name, prices}) {
                return `${name} (${prices[0].price}Ks)`
            }
        },

        watch: {
            selectedRoom(val, oldVal) {
                console.log(`new: ${val}, old: ${oldVal}`)
            },
            // roomAreaId(newId) {
            //     this.getRoomList(newId);
            // },
            area(area){
                this.getRoomList(area);
            },

            isPreDeposit(){
                if(!this.isPreDeposit){
                    this.deposit = null;
                    this.selectedCashAccount = null;
                }
            },
        },
        computed: {
            combinedMenuList() {
                const map = {};
                this.purchaseMenuList[0].order_items.forEach(item => {
                    const key = item.menu_id + '-' + item.status;
                    if (!map[key]) {
                        map[key] = { ...item };
                    } 
                    else {
                        map[key].quantity += item.quantity;
                        map[key].price += item.price;
                    }
                });
                return Object.values(map);
            },
        },
        created(){
            // this.getCustomerList();
            // this.getGendersList();
            // this.getMenuList();
            // this.getDivisionList();

            // this.getServiceCategoryList();
            // this.getLadyList();

            // this.getAccessoryCategoryList();
            // this.testtime();
            this.getCashAccounts();
        },
        mounted()
        {
            // Date picker
            
            // if (this.roomAreaId) {
            //     this.getRoomList(this.roomAreaId);
            // }
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
