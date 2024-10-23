<template>
    <div class="">
        <div class=" bg-gray-100 w-max min-h-screen flex overflow-x-auto hidden-scrollbar" :style="isShowSidebar == true ? 'width:calc(100% - 410px)' : 'width:100%' ">
            <div class="w-fit pt-9 px-6 ">
                <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                    <li v-for="(area, index) in areaList" :key="index" role="presentation" @click="btnClickedArea(area.id)">
                        <a href="#tabs-profile" class="my-2 mr-3 text-white block  px-7 pb-2.5 rounded-full
                            pt-3 text-xs  hover:isolate bg-[#F0C094]
                            hover:bg-[#f7a559] focus:isolate data-[te-nav-active]:bg-[#F19E51]"
                            :class="area.id == selectedAreaId ? 'bg-[#F19E51]' : 'bg-[#F0C094]'">
                            {{ area.name }}
                        </a>
                    </li>
                </ul>

                <div class="mb-6">
                    <div class="opacity-100 transition-opacity duration-150 ease-linear">
                        <div v-for="(room, index) in roomList" :key="index"
                            :class="room.is_active == 0 ? 'bg-[#55EFC4]' : 'bg-[#FF7675]'"
                            class=" flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
                            <button @click="btnClickedIsOpenRoom(room, index)"
                                class="relative flex flex-col justify-between h-full w-full">
                                <div v-if="room.is_active == 1" class=" flex justify-between flex-col h-full">
                                    <div>
                                        <p class="text-sm text-white">Start Time : {{ room.room_sessions[0].start_date ? room.room_sessions[0].start_date.slice(11,16) : '' }} </p>
                                        <p class="text-sm text-white">Start Time : {{ room.room_sessions[0].end_date ? room.room_sessions[0].end_date.slice(11,16) : '' }} </p>
                                    </div>
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
            </div>




            
            <!-- <div class="fixed right-0 top-0 bottom-0 bg-white shadow-md ease-in-out duration-300 transition delay-100 pt-12 right-sidebar-2" :class="isShowSidebar == true ? 'translate-x-0 opacity-100 w-[400px]' : 'translate-x-full opacity-0 w-0' ">
                <div class="relative h-full w-full">
                    <div class="fixed right-4 top-4 z-40" :class="isShowSidebar == true ? 'block' : 'hidden' ">
                        <button @click="isShowSidebar = false"><i class="far fa-times"></i></button>
                    </div>
                    
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
                                        Time
                                    </label>
                                    <input type="datetime-local" placeholder="Time" v-model="invoice_date" @change="getPackageList(invoice_date)"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
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
                                        Deposit
                                    </label>
                                    <input type="text" placeholder="Deposit" v-model="deposit"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-4" v-show="this.type == 'session'">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Duration
                                    </label>
                                    <input type="text" placeholder="" v-model="duration"
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
                            <div>
                                <p class="text-black text-xl">
                                    test
                                </p>
                            </div>
                            <div class="flex gap-x-3">
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    @click="btnClickedGetChangeableRoomList()" data-te-toggle="modal"
                                    data-te-target="#change_modal">
                                    <i class="far fa-random"></i>
                                </button>
                                <button @click="btnClickAddMenu()"
                                    class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_menu_modal">
                                    <i class="far fa-cocktail"></i>
                                </button>
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                    data-te-toggle="modal" data-te-target="#add_hour_modal">
                                    <i class="far fa-hourglass-half"></i>
                                </button>
                            </div>
                        </div>
                        <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 195px)">
                            <div class="padding-section border-b    " v-if="selectedRoom">
                                <div class="flex justify-between font-semibold mb-2">
                                    <p class="text-sm text-black" v-if="selectedRoom.room_sessions.length > 0">
                                        Invoice Id
                                        {{ selectedRoom.room_sessions[0] ? (selectedRoom.room_sessions[0].invoice ?
                                            selectedRoom.room_sessions[0].invoice.invoice_id : '')
                                        : '' }}

                                    </p>
                                    <p class="text-sm text-black font-semibold" v-if="selectedRoom">
                                        {{ selectedRoom.room_sessions[0] ?
                                            (selectedRoom.room_sessions[0].invoice.total_session_price ?
                                                selectedRoom.room_sessions[0].invoice.total_session_price : '' )
                                        : '' }}

                                        MMKs
                                    </p>
                                </div>
                                <div class="mb-2">
                                    <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                        Room Err0r
                                    </p>
                                </div>
                                <div class="">
                                    <p class="text-sm text-black mb-2">
                                        Start Time : {{ selectedRoom.room_sessions.length > 0 ?
                                            selectedRoom.room_sessions[0].start_date : '' }}
                                    </p>
                                    <p class="text-sm text-black">
                                        End Time : {{ selectedRoom.room_sessions.length > 0 ?
                                            selectedRoom.room_sessions[0].end_date : '' }}
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
                                        <div v-for="menu2 in menu.order_items" class="contents" :key="menu2">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="padding-section border-b    ">
                                <div class="flex justify-between font-semibold mb-3">
                                    <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                        Services
                                    </p>
                                    <p class="text-sm text-black font-semibold">
                                        35,000 MMks
                                    </p>
                                </div>
                                <div class=" flex justify-between">
                                    <div class="block">
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section">
                            <div class=" text-right pr-3 mb-3">
                                <p class="">
                                    Total
                                    {{
                                        (selectedRoom ?
                                            (purchaseMenuList.length > 0 ?
                                                (
                                                    (selectedRoom.room_sessions[0].invoice.total_session_price ?
                                                        selectedRoom.room_sessions[0].invoice.total_session_price : 0)
                                                    +
                                                    (purchaseMenuList.length > 0 ? purchaseMenuList[0].total : 0)
                                                    - (selectedRoom.room_sessions[0].invoice.package ? selectedRoom.room_sessions[0].invoice.package.package_discount : 0)
                                                ).toLocaleString()
                                                :
                                                (
                                                    (selectedRoom.room_sessions[0].invoice.total_session_price ?
                                                        selectedRoom.room_sessions[0].invoice.total_session_price : 0)
                                                    - (selectedRoom.room_sessions[0].invoice.package ? selectedRoom.room_sessions[0].invoice.package.package_discount : 0)
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
                                    </label><div class="fixed right-0 top-0 bottom-0 bg-white shadow-md ease-in-out duration-300 transition delay-100 pt-12 right-sidebar-2" :class="isShowSidebar == true ? 'translate-x-0 opacity-100 w-[400px]' : 'translate-x-full opacity-0 w-0' ">
                                        <div class="relative h-full w-full">
                                            <div class="fixed right-4 top-4 z-40" :class="isShowSidebar == true ? 'block' : 'hidden' ">
                                                <button @click="isShowSidebar = false"><i class="far fa-times"></i></button>
                                            </div>
                                            
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
                                                                Time
                                                            </label>
                                                            <input type="datetime-local" placeholder="Time" v-model="invoice_date" @change="getPackageList(invoice_date)"
                                                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
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
                                                                Deposit
                                                            </label>
                                                            <input type="text" placeholder="Deposit" v-model="deposit"
                                                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                                        </div>
                                                        <div class="mb-4" v-show="this.type == 'session'">
                                                            <label for="" class="block text-sm text-black mb-3">
                                                                Duration
                                                            </label>
                                                            <input type="text" placeholder="" v-model="duration"
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
                                                    <div>
                                                        <p class="text-black text-xl">
                                                            test
                                                        </p>
                                                    </div>
                                                    <div class="flex gap-x-3">
                                                        <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                                            @click="btnClickedGetChangeableRoomList()" data-te-toggle="modal"
                                                            data-te-target="#change_modal">
                                                            <i class="far fa-random"></i>
                                                        </button>
                                                        <button @click="btnClickAddMenu()"
                                                            class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                                            data-te-toggle="modal" data-te-target="#add_menu_modal">
                                                            <i class="far fa-cocktail"></i>
                                                        </button>
                                                        <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                                            data-te-toggle="modal" data-te-target="#add_hour_modal">
                                                            <i class="far fa-hourglass-half"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 195px)">
                                                    <div class="padding-section border-b    " v-if="selectedRoom">
                                                        <div class="flex justify-between font-semibold mb-2">
                                                            <p class="text-sm text-black" v-if="selectedRoom.room_sessions.length > 0">
                                                                Invoice Id
                                                                {{ selectedRoom.room_sessions[0] ? (selectedRoom.room_sessions[0].invoice ?
                                                                    selectedRoom.room_sessions[0].invoice.invoice_id : '')
                                                                : '' }}
                        
                                                            </p>
                                                            <p class="text-sm text-black font-semibold" v-if="selectedRoom">
                                                                {{ selectedRoom.room_sessions[0] ?
                                                                    (selectedRoom.room_sessions[0].invoice.total_session_price ?
                                                                        selectedRoom.room_sessions[0].invoice.total_session_price : '' )
                                                                : '' }}
                        
                                                                MMKs
                                                            </p>
                                                        </div>
                                                        <div class="mb-2">
                                                            <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                                                Room Err0r
                                                            </p>
                                                        </div>
                                                        <div class="">
                                                            <p class="text-sm text-black mb-2">
                                                                Start Time : {{ selectedRoom.room_sessions.length > 0 ?
                                                                    selectedRoom.room_sessions[0].start_date : '' }}
                                                            </p>
                                                            <p class="text-sm text-black">
                                                                End Time : {{ selectedRoom.room_sessions.length > 0 ?
                                                                    selectedRoom.room_sessions[0].end_date : '' }}
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
                                                                <div v-for="menu2 in menu.order_items" class="contents" :key="menu2">
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
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="padding-section border-b    ">
                                                        <div class="flex justify-between font-semibold mb-3">
                                                            <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                                                Services
                                                            </p>
                                                            <p class="text-sm text-black font-semibold">
                                                                35,000 MMks
                                                            </p>
                                                        </div>
                                                        <div class=" flex justify-between">
                                                            <div class="block">
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        
                                                <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section">
                                                    <div class=" text-right pr-3 mb-3">
                                                        <p class="">
                                                            Total
                                                            {{
                                                                (selectedRoom ?
                                                                    (purchaseMenuList.length > 0 ?
                                                                        (
                                                                            (selectedRoom.room_sessions[0].invoice.total_session_price ?
                                                                                selectedRoom.room_sessions[0].invoice.total_session_price : 0)
                                                                            +
                                                                            (purchaseMenuList.length > 0 ? purchaseMenuList[0].total : 0)
                                                                            - (selectedRoom.room_sessions[0].invoice.package ? selectedRoom.room_sessions[0].invoice.package.package_discount : 0)
                                                                        ).toLocaleString()
                                                                        :
                                                                        (
                                                                            (selectedRoom.room_sessions[0].invoice.total_session_price ?
                                                                                selectedRoom.room_sessions[0].invoice.total_session_price : 0)
                                                                            - (selectedRoom.room_sessions[0].invoice.package ? selectedRoom.room_sessions[0].invoice.package.package_discount : 0)
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
                                                        <div class="mb-4">
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
                        
                        
                                            <div v-if="isOpenRoom.is_package == true" class="relative h-full">
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
                                            </div>
                                        </div>
                                    </div>
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
                                <div class="mb-4">
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


                    <div v-if="isOpenRoom.is_package == true" class="relative h-full">
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
                    </div>
                </div>
            </div> -->
        </div>




        
        
        




    </div>

</template>
<script>
    import { Modal, Ripple, Select, Datepicker, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
import { getCurrentTime } from "../../../utilities/datetime-helpers";

    export default {
        data() {
            return {
                

            };
        },

        methods: {
            
        },
        created(){

        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
