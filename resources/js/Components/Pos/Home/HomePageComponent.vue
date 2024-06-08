<template>
    <div>

        <div class="">
            <div class="w-[67%] pt-9 px-6">
                <p>{{ testsession }}</p>
                <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                    <li v-for="(area,index) in areaList" role="presentation" @click="btnGetAreaItemList(area.id)">
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
                        <div class="flex flex-wrap gap-x-4 gap-y-4">
                            <div v-for="(room,index) in roomList"
                                :class="room.is_active == 0 ? 'bg-[#55EFC4]' : 'bg-[#FF7675]'"
                                class=" flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
                                <button @click="btnClickedIsOpenRoom(room,index)"
                                    class="relative flex flex-col justify-between h-full w-full">
                                    <div v-if="room.is_active == 1 " class=" flex justify-between flex-col h-full">
                                        <div>
                                            <p class="text-sm text-white">Start Time : 9:00 </p>
                                            <p class="text-sm text-white">Start Time : 9:00 </p>
                                        </div>
                                        <p class="text-base text-left text-white">
                                            {{ room.price_per_hour }}
                                        </p>
                                    </div>
                                    <div class="absolute bottom-0 w-full flex justify-end">
                                        <p class="text-xl text-white">
                                            {{ room.name }}
                                        </p>
                                    </div>
                                </button>
                            </div>
                            <!-- <div class="bg-[#55EFC4] flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
                                <div class="flex flex-col justify-end h-full">
                                    <div class=" w-full flex justify-end">
                                        <p class="text-2xl text-white">
                                            1
                                        </p>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <!-- <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-profile" role="tabpanel" aria-labelledby="tabs-profile-tab">
                        <div class="flex flex-wrap gap-x-4 gap-y-4">
                            <div v-for="(table,index) in tableList"
                                :class="table.is_active == 0 ? 'bg-[#55EFC4]' : 'bg-[#FF7675]'"
                                class=" flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">

                                <button class="relative flex flex-col justify-between h-full w-full">

                                    <div v-if="table.is_active == 1 " class=" flex justify-between flex-col h-full">
                                        <div>
                                            <p class="text-sm text-white">Start Time : 9:00 </p>
                                            <p class="text-sm text-white">Start Time : 9:00 </p>
                                        </div>

                                        <p class="text-base text-left text-white">
                                            {{ table.price_per_hour }}
                                        </p>
                                    </div>
                                    <div class="absolute bottom-0 w-full flex justify-end">

                                        <p class="text-xl text-white">
                                            {{ table.name }}
                                        </p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="right-sidebar shadow-lg border-l border-gray-200">
                <!-- secssion right sidebar -->
                <div v-if="isOpenRoom.step_detail == true" class="relative h-full">
                    <div class="flex justify-between padding-section border-b">
                        <div>
                            <p class="text-black text-xl">
                                Table Details
                            </p>
                        </div>
                        <div class="flex gap-x-3">
                            <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                @click="btnClickedGetChangeableRoomList()" data-te-toggle="modal"
                                data-te-target="#change_modal">
                                <i class="far fa-random"></i>
                            </button>
                            <button @click="btnClickAddMenu"
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
                        <div class="padding-section border-b    ">
                            <div class="flex justify-between font-semibold mb-2">
                                <p class="text-sm text-black">
                                    Invoice Id
                                    <!-- {{ selectedRoom.room_sessions[0].invoice.invoice_id ? selectedRoom.room_sessions[0].invoice.invoice_id
                                    : '' }} -->

                                    {{ selectedRoom.room_sessions ? (selectedRoom.room_sessions[0].invoice ? selectedRoom.room_sessions[0].invoice.invoice_id : '' )
                                        : '' }}

                                </p>
                                <p class="text-sm text-black font-semibold">
                                    <!-- 35,000 MMks -->

                                    <!-- {{ (selectedRoom.price_per_hour * (selectedRoom.invoices.length > 0 ?
                                    selectedRoom.invoices[0].sessions[0].session_duration : 1)).toLocaleString() }} -->

                                    <!-- {{ selectedRoom.room_sessions.length > 0 ? selectedRoom.room_sessions[0].invoice.total_session_price :
                                    0 }} -->

                                    {{ selectedRoom.room_sessions[0].length > 0 ? (selectedRoom.room_sessions[0].invoice.total_session_price ? selectedRoom.room_sessions[0].invoice.total_session_price : '' )
                                        : '' }}

                                    MMKs
                                </p>
                            </div>
                            <div class="mb-2">
                                <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                    Room {{ selectedRoom.name }}
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
                                <div v-for="(menu,index) in purchaseMenuList" class="contents">
                                    <div v-for="menu2 in menu.order_items" class="contents">
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
                                (selectedRoom.room_sessions[0].length > 0  ?
                                    (purchaseMenuList.length > 0 ?
                                        (
                                            (selectedRoom.room_sessions[0].invoice.total_session_price ? selectedRoom.room_sessions[0].invoice.total_session_price : 0)
                                             +
                                            (purchaseMenuList[0].length > 0 ? purchaseMenuList[0].total : 0 )
                                        ).toLocaleString()
                                        :
                                        (
                                        selectedRoom.room_sessions[0].invoice.total_session_price ? selectedRoom.room_sessions[0].invoice.total_session_price : 0
                                        ).toLocaleString()
                                    )
                                : 0
                                )

                                }}
                                MMKs

                                <!-- <span v-if="purchaseMenuList.length > 1" >
                                    {{ ((selectedRoom.price_per_hour * selectedRoom.invoices[0].sessions[0].session_duration) + purchaseMenuList[0].total).toLocaleString() }} MMKs
                                </span>
                                <span v-else>
                                    0 MMKs
                                </span> -->
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
                <!-- invoice right sidebar -->
                <div v-if="isOpenRoom.step_invoice == true" class="relative h-full">
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
                                        <option value="room_discount">  Room Discount </option>
                                        <option value="fix_amount"> Fix Ammount  </option>
                                        <option value="percentage"> Percentage </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4" v-show="discount_type == 'room_discount'">
                                <label for="" class="block text-sm text-black mb-3">
                                    Room Disount
                                </label>
                                <div class="relative">
                                    <select name="" id="" v-model="room_discount"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                        <option v-for="rd in roomDiscountList" :value="rd">  {{ rd.name }} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4" v-show="discount_type != 'room_discount'">
                                <label for="" class="block text-sm text-black mb-3">
                                    Discount
                                </label>
                                <input type="number" placeholder="Discount" v-model="printInvoiceData.discount"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <!-- <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                Paid Amount
                                </label>
                                <input type="text" placeholder="Paid Amount" v-model="paid_amount"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Change
                                </label>
                                <div class="relative">
                                    <input type="text" placeholder="Change" v-model="change"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                            </div> -->
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Payment Method
                                </label>
                                <div class="relative">
                                    <select name="" id="" v-model="selectedPaymentMethod"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                        <option value="bank"> Bank </option>
                                        <option value="cash"> Cash </option>
                                    </select>
                                </div>
                            </div>


                            <div class="mb-4">
                                <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                                    <input class="input-check-pos" type="checkbox" v-model="printInvoiceData.isTax"
                                        value="" id="tax" />
                                    <label class="inline-block pl-[0.15rem] hover:cursor-pointer" for="tax">
                                        Tax
                                    </label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                                    <input class="input-check-pos" type="checkbox"
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
                                {{ packagePrice.toLocaleString() }} MMKs
                            </p>
                        </div>
                        <div v-if="!isPackage" class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Room
                            </p>
                            <p class=" w-28">
                                {{ printInvoiceData.room ? printInvoiceData.room.toLocaleString() : 0 }} MMKs
                                <!-- {{ (selectedRoom.price_per_hour * (selectedRoom.invoices.length > 0 ? selectedRoom.invoices[0].sessions[0].session_duration : 1)).toLocaleString() }} MMKs -->
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Food
                            </p>
                            <p class=" w-28">
                                {{ printInvoiceData.food ? printInvoiceData.food.toLocaleString() : 0 }} MMks
                                <!-- {{ printInvoiceData.isTax ? (printInvoiceData.food ? printInvoiceData.food.toLocaleString() : 0) : 0 }} MMKs -->
                                <!-- {{ purchaseMenuList.length > 0 ? purchaseMenuList[0].total.toLocaleString() : '0' }} MMks -->
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Service Charge
                            </p>
                            <p class=" w-28">
                                {{ printInvoiceData.service_charge == true? (printInvoiceData.service_tax ? printInvoiceData.service_tax.toLocaleString() :
                                0) : 0 }} MMKs
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Tax
                            </p>
                            <p class=" w-28">
                                {{ printInvoiceData.isTax == true? (printInvoiceData.tax ? printInvoiceData.tax.toLocaleString() :
                                    0) : 0 }} MMKs
                                <!-- {{ printInvoiceData.tax ? printInvoiceData.tax.toLocaleString() : 0 }} MMKs -->
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Discount
                            </p>
                            <p class=" w-28">
                                {{ printInvoiceData.discount }} MMKs
                            </p>
                        </div>
                        <div class=" text-right pr-3 mb-3">
                            <p class="font-semibold">
                                Total &nbsp;
                                <!-- {{  (printInvoiceData.room + printInvoiceData.food + printInvoiceData.service_tax + printInvoiceData.tax ) }} -->
                                <!-- {{ printInvoiceData.total ? printInvoiceData.total.toLocaleString() : 0 }} MMKs -->
                                {{ (printInvoiceData.total ? printInvoiceData.total : 0) 
                                    + (printInvoiceData.service_charge == true? (printInvoiceData.service_tax ? printInvoiceData.service_tax :0) : 0) 
                                    + (printInvoiceData.isTax == true? (printInvoiceData.tax ? printInvoiceData.tax : 0) : 0) }} MMKs
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

                <!-- open session right sidebar -->
                <div class="relative block h-full" v-if="isOpenRoom.step_1 == true" id="open_room_1">
                    <div class="w-full h-full flex justify-center flex-col">
                        <div class="w-2/3 mx-auto">
                            <div class="text-center">
                                <p class="mb-2 text-black font-semibold">
                                    Open {{ selectedRoom ? selectedRoom.name : ''}}
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
                <div class="" v-show="isOpenRoom.step_2 == true" id="open_room_2">
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
                                    <select name="" id="" v-model="type" @change="getPackageList"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                        <option value="session">  Session </option>
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
                                    <select name="" id="" v-model="selectedPackage"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                        <option v-for="pack in packageList" :value="pack.id">
                                            {{ pack.name }}
                                        </option>
                                        <!-- <option v-for="(package,index) in packageList" :value="index"> {{ package }}</option> -->
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
                                    Time
                                </label>
                                <input type="datetime-local" placeholder="Time" v-model="invoice_date"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4" v-show="this.type == 'session'">
                                <label for="" class="block text-sm text-black mb-3">
                                    Duration
                                </label>
                                <input type="text" placeholder="" v-model="duration" :disabled="this.type == 'package' || this.type == 'endless_time'"
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
                            <input type="text" placeholder="Hour" v-model="sessionDuration"
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
                            <select name="" id="" placeholder="Menu" v-model="selectedMenu"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="menu" v-for="(menu,index) in menuList">{{ menu.name }}</option>
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
                            <select name="" id="" placeholder="Room" v-model="change_room"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="changeableRoom" :key="index"
                                    v-for="(changeableRoom,index) in changeableRoomList">{{ changeableRoom.name }}
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
                            <input type="text" placeholder="Customer Name" v-model="name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Phone Number
                            </label>
                            <input type="text" placeholder="Phone Number" v-model="ph_number"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Email
                            </label>
                            <input type="text" placeholder="Email" v-model="email"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Gender
                            </label>
                            <select name="" id="" v-model="selectedGender"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="gender.id" v-for="(gender, genderIndex) in genderList"
                                    :key="genderIndex"> {{ gender.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Birthdate
                            </label>
                            <input type="date" placeholder="Birthdate" v-model="date"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Address
                            </label>
                            <textarea v-model="address"
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
    import { Modal, Ripple, Select, initTE, Tab } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../../utilities/ajax-helpers';
    import { getCurrentTime, getCurretDateTime } from '../../../utilities/datetime-helpers';
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                areaList:[],
                selectedAreaId:null,
                roomList:[],
                selectedRoom:null,
                selectedRoomId:null,
                isOpenRoom: {
                    step_1 : false,
                    step_2 : false,
                    step_detail : false,
                    step_invoice : false
                },
                value: 49650,
                name: null,
                ph_number:null,
                email:null,
                genderList: [],
                date:null,
                duration:null,
                address:null,
                selectedGender:null,
                customerList:null,
                roomName:null,
                selectedCustomer:null,
                type:'session',
                packageList:null,
                selectedPackage:null,
                invoice_date:null,
                deposit:null,
                male:null,
                female:null,
                child:null,
                purchaseMenuList: [],
                selectedRoomIndex:null,
                printInvoiceData:{
                    room:0,
                    food:0,
                    service_tax:0,
                    service_charge:false,
                    tax:0,
                    isTax:false,
                    discount:0,
                    tatalPrice:0,
                    foodList:[]
                },
                room_discount:null,
                orderList:[],
                orderItemsPrice:null,
                selectedPaymentMethod:null,
                change:null,
                paid_amount:null,
                isActive:true,

                //create menu , add hour , change room
                menuList: [],
                invoiceId:null,
                selectedMenu:null,
                menuQuantity:null,
                remark:null,
                menuPrice:null,
                testroom:null,
                sessionDuration:null,
                changeableRoomList:[],
                change_room:null,

                // doneSession
                roomDiscountList:null,
                isPackage:false,
                packagePrice:null,
                discount_type: null,
                isShowDiscount:true,

                //rooftop
                tableList:[],

                currentTime: getCurretDateTime(),
                testsession:null
            };
        },

        methods: {
            ...mapGetters(['getToken']),
            async getAreaList(){
                let url = '/api/areas'
                const response = await getApiData({ url: url, token: this.getToken() });
                if(response.data){
                    this.areaList = response.data;
                    this.selectedAreaId = this.areaList[0].id;
                    let firstAreaId = response.data[0].id;
                    if (firstAreaId) {
                        this.initialGetRoomList(firstAreaId);
                    }
                }
            },
            async initialGetRoomList(firstAreaId) {
                const response = await getApiData({ url: '/api/areas/' + firstAreaId + '/entities', token: this.getToken() });
                if (response.data) {
                    this.roomList = response.data;
                    this.selectedRoomId = response.data[0].id
                    this.getSelectedRoom();
                    if (this.roomList[0]?.room_sessions.length > 0) {
                        this.isOpenRoom.step_1 = false;
                        this.isOpenRoom.step_2 = false;
                        this.isOpenRoom.step_detail = true;
                    }
                    if (this.roomList[0]?.room_sessions.length < 1) {
                        this.isOpenRoom.step_1 = true;
                        this.isOpenRoom.step_2 = false;
                        this.isOpenRoom.step_detail = false;
                    }
                }
            },
            async btnGetAreaItemList(id){
                this.selectedAreaId = id;
                const response = await getApiData({ url: '/api/areas/'+ id +'/entities', token: this.getToken() });
                if (response.data) {
                    this.roomList = response.data;
                    this.selectedRoomId = response.data[0].id;
                    this.getSelectedRoom();
                    if(this.roomList[0]?.room_sessions.is_active == 1){
                        this.isOpenRoom.step_1 = false;
                        this.isOpenRoom.step_2 = false;
                        this.isOpenRoom.step_detail = true;
                        this.isOpenRoom.step_invoice = false;
                    }
                    else{
                        this.isOpenRoom.step_1 = true;
                        this.isOpenRoom.step_2 = false;
                        this.isOpenRoom.step_detail = false;
                        this.isOpenRoom.step_invoice = false;

                    }

                }
            },

            async getGendersList(){
                const response = await getApiData({ url: '/api/genders', token: this.getToken() });
                if(response.data){
                    this.genderList = response.data;
                }
            },
            async getMenuList(){
                const response = await getApiData({ url: '/api/menus', token: this.getToken() });
                if(response.data){
                    this.menuList = response.data;
                }
            },
            async getPurchaseMenuList(){
                const response = await getApiData({ url: '/api/entities/' + this.selectedRoomId, token: this.getToken() });
                if(response.data){
                    if(response.data.room_sessions.length > 0){
                        this.purchaseMenuList = response.data.room_sessions[0].invoice.orders;
                    }

                }
            },
            async getRoomList(){
                const response = await getApiData({ url: '/api/areas/' + this.selectedAreaId + '/entities', token: this.getToken() });
                if(response.data){
                    this.roomList = response.data;
                    // console.log( this.roomList[0] );
                }
                console.log(this.selectedAreaId)
            },

            async getCustomerList(){
                const response = await getApiData({ url: '/api/customers', token: this.getToken() });
                if(response.data){
                    this.customerList = response.data;
                }
            },
            async getPackageList(){
                if(this.type == 'package'){
                    const response = await getApiData({ url: '/api/packages', token: this.getToken() });
                    if(response.data){
                        this.packageList = response.data.data;
                    }
                }
                else{
                    this.packageList = null;
                }

            },

            async btnClickedIsOpenRoom(room,index){

                this.selectedRoomId = room.id;
                this.selectedRoomIndex = index;
                this.getSelectedRoom();
                if(this.roomList[index].is_active == 1){
                    this.isOpenRoom.step_1 = false;
                    this.isOpenRoom.step_2 = false;
                    this.isOpenRoom.step_invoice = false;
                    this.isOpenRoom.step_detail = true;
                    this.getPurchaseMenuList();
                };
                if(this.roomList[index].is_active == 0){
                    // alert(this.roomList[index].room_sessions.length + ' = 0 ')
                    this.isOpenRoom.step_1=true;
                    this.isOpenRoom.step_2 = false;
                    this.isOpenRoom.step_invoice = false;
                    this.isOpenRoom.step_detail = false;
                    this.getPurchaseMenuList();
                };
            },
            async getSelectedRoom(){
                const response = await getApiData({ url: '/api/entities/' + this.selectedRoomId, token: this.getToken() });
                    if(response.data){
                        this.selectedRoom = response.data;
                }
            },
            btnClickedOpenRoom(){
                this.isOpenRoom.step_1=false;
                this.isOpenRoom.step_2=true;
                this.isOpenRoom.step_detail=false;
                this.isOpenRoom.step_invoice=false;
                this.clearOpenRoomForm();

            },

            createCustomerBtnClicked(){
                this.createCustomer();
            },
            async createCustomer()
            {
                console.log(this.email);
                // return 1;
                let formData = new FormData();
                formData.append('gender_id', this.selectedGender);
                formData.append('name', this.name);
                if(this.email != null){
                    formData.append('email', this.email);
                }
                formData.append('phone_number', this.ph_number);
                formData.append('address', this.address);
                formData.append('birthdate', this.date);
                let response = await postApiData({url: '/api/customers', form_data: formData, token: this.getToken()});
                console.log(this.selectedGender+','+this.name+','+this.email+','+this.ph_number+','+this.address+','+this.date)
                if(response.success){
                    this.customerList.push(response.data);
                    this.selectedCustomer = response.data;
                    // this.getRoomList(null);
                    console.log("success")
                    this.closeCustomerModal();
                    this.clearCustomerForm();
                }
                else{
                    console.log('some errors occur');
                }
            },

            confirmRoomBtnClicked(){
                this.createRoom();
            },
            async createRoom()
            {
                let formData = new FormData();
                // formData.append('invoice_id', this.invoice_id);
                formData.append('entity_id', this.selectedRoom.id);
                formData.append('customer_id', this.selectedCustomer.id);
                formData.append('invoice_date', this.invoice_date);
                if(this.type == 'session'){
                    formData.append('session_duration', this.duration);
                }
                if(this.type == 'package'){
                    formData.append('package_id', this.selectedPackage);

                }
                formData.append('type', this.type);
                formData.append('deposit', this.deposit);
                if(this.female > 0){
                    formData.append('female', +this.female);
                }
                if(this.male > 0){
                    formData.append('male', +this.male);
                }
                if(this.child > 0){
                    formData.append('child', +this.child);
                }
                let response = await postApiData({ url: '/api/entities/start', form_data: formData, token: this.getToken()});
                if(response.success){
                    await this.getRoomList();
                    await this.getSelectedRoom();
                    this.isOpenRoom.step_1 = false;
                    this.isOpenRoom.step_2 = false;
                    this.isOpenRoom.step_detail = true;
                    this.isOpenRoom.step_invoice = false;

                    if(this.selectedRoom.room_sessions.length > 0){
                        if(this.selectedRoom.room_sessions[0].invoice.orders.length>0){
                            this.getPurchaseMenuList();
                        }
                    }
                    if(this.selectedRoom.room_sessions.length < 1){
                        if(this.selectedRoom.room_sessions[0].invoice.orders.length<1){
                            this.purchaseMenuList = [];
                        }
                    }

                    console.log("success")
                }
                else{
                    console.log('some errors occur')
                }
            },
            btnClickAddMenu(){
                this.invoiceId = this.selectedRoom.room_sessions[0].invoice.invoice_id;
                console.log('invoice id ' + this.invoiceId)
            },

            btnConfirmAddMenu(){
                this.addMenu();
            },
            async addMenu()
            {
                console.log('test add menu')
                let formData = new FormData();
                formData.append('invoice_id', this.invoiceId);
                formData.append('menu_id', this.selectedMenu.id);
                formData.append('quantity', this.menuQuantity);
                formData.append('original_price', this.selectedMenu.prices[0].price);
                formData.append('remark', this.remark);
                let response = await postApiData({ url: '/api/entities/orders', form_data: formData, token: this.getToken()});
                // console.log(this.invoiceId+','+this.selectedMenu.id + ','+ this.menuQuantity +','+this.selectedMenu.prices[0].price)
                if(response.success){
                    console.log("success")
                    this.closeMenuModal();
                    this.clearMenuForm();
                    this.getPurchaseMenuList();
                }
                else{
                    console.log('some errors occur');
                }
            },
            btnAddHour(){
                this.addHour();
            },
            async addHour()
            {
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.room_sessions[0].invoice.invoice_id);
                formData.append('session_duration', this.sessionDuration);
                let response = await postApiData({ url: '/api/entities/add_more_sessions', form_data: formData, token: this.getToken()});
                if(response.success){
                    console.log("success")
                    await this.getRoomList();
                    this.selectedRoom = await this.roomList[this.selectedRoomIndex];
                    this.closeModal();
                    this.clearAddHourForm();
                }
                else{
                    console.log('some errors occur');
                }
            },
            btnClickedChangeRoom(){
                this.changeRoom();
            },
            async changeRoom()
            {
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.room_sessions[0].invoice.invoice_id);
                formData.append('entity_id', this.change_room.id);
                let response = await postApiData({ url: '/api/entities/change', form_data: formData, token: this.getToken()});
                console.log('change room ' + this.selectedRoom.room_sessions[0].invoice.invoice_id+','+this.change_room.id)
                if(response.success){
                    console.log("success");
                    await this.getRoomList();
                    // this.selectedRoom = this.roomList.find(x => x.id === this.change_room.id);
                    this.selectedRoomId = this.change_room.id;
                    this.getSelectedRoom();
                    this.closeChangeRoomModal();
                    this.clearChangeRoomForm();
                }
                else{
                    console.log('some errors occur');
                }
            },
            btnClickedGetChangeableRoomList(){
                this.getChangeableRoomList();
            },
            async getChangeableRoomList(){
                const response = await getApiData({ url: '/api/areas/' + this.selectedAreaId + '/inactive_entities', token: this.getToken() });
                if(response.data){
                    this.changeableRoomList = response.data;
                }
            },



            btnBackToDetail(){
                this.isOpenRoom.step_1 = false;
                this.isOpenRoom.step_2 = false;
                this.isOpenRoom.step_detail = true;
                this.isOpenRoom.step_invoice = false;
            },
            btnClickedDoneSession(){
                this.doneSession();

            },
            async doneSession(){
                this.isOpenRoom.step_1=false;
                this.isOpenRoom.step_2 = false;
                this.isOpenRoom.step_detail = false;
                this.isOpenRoom.step_invoice = true;

                let formData = new FormData();
                let roomSessions = [];
                formData.append('invoice_id', this.selectedRoom.room_sessions[0].invoice.invoice_id);
                let response = await postApiData({ url: '/api/room_done', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.testsession = response.data
                    roomSessions = response.data;
                    console.log("success")
                }
                let roomChargeTotal = 0;
                if(this.room_discount){
                    roomSessions.forEach(roomSession => {
                        if(roomSession.session_duration < 1){
                            roomChargeTotal += roomSession.price; // or roomSession.session_duration * roomSession.entity.price_per_hour;
                        }
                        else{
                            let perGroup = this.room_discount.session + this.room_discount.free_session;
                            let completeGroups = roomSession.session_duration % perGroup; // need to consider fractional session durations
                            let factor = (this.room_discount.free_session * completeGroups);
                            let remainings = roomSession.session_duration - (perGroup * completeGroups);
                            let priceForCompleteGroups = completeGroups * factor * roomSession.entity.price_per_hour;
                            let priceForRemainings = remainings * roomSession.entity.price_per_hour;
                            roomChargeTotal += (priceForCompleteGroups + priceForRemainings);
                        }
                    });
                }
                else{
                    roomSessions.forEach(roomSession => {
                        roomChargeTotal += roomSession.price; // or roomSession.session_duration * roomSession.entity.price_per_hour;
                    });
                }
                this.printInvoiceData.room = this.selectedRoom.price_per_hour * this.selectedRoom.room_sessions[0].session_duration; // <== this
                this.printInvoiceData.room = roomChargeTotal; // <== or that

                if(this.purchaseMenuList.length > 0){
                    this.printInvoiceData.food = this.purchaseMenuList[0].total
                    this.purchaseMenuList[0].order_items.forEach(element => {
                        this.orderList.push({
                            'menu_category_id' : element.menu.menu_category_id ,
                            'price' : element.price ,
                        })
                    });

                }
                if(this.selectedRoom.room_sessions[0].invoice.invoice_type == 'package'){
                    this.isPackage = true;
                    this.packagePrice = this.selectedRoom.room_sessions[0].invoice.paid_amount
                }
                
                
                // room price = this.printInvoiceData.room
                if(this.service_charge = true){
                    this.printInvoiceData.service_tax = (this.printInvoiceData.room + this.printInvoiceData.food) * 0.05
                }
                if(this.isTax = true){
                    this.printInvoiceData.tax = this.printInvoiceData.food * 0.05
                }
                
                
                this.printInvoiceData.total = this.printInvoiceData.room + this.printInvoiceData.food
                // this.printInvoiceData.total = this.printInvoiceData.room + this.printInvoiceData.food + this.printInvoiceData.tax +this.printInvoiceData.service_tax

                this.selectedPaymentMethod = null
                this.change = null
                this.paid_amount = null
                this.printInvoiceData.discount = 0

            },
            async getRoomDiscount(){
                const response = await getApiData({ url: '/api/room_discounts', token: this.getToken() });
                if(response.data){
                    this.roomDiscountList = response.data.data;
                }
            },
            btnClickedEndRoom(){
                this.EndRoom();
            },
            async EndRoom()
            {
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.room_sessions[0].invoice.invoice_id);
                // formData.append('change', this.change);
                // formData.append('paid_amount', this.paid_amount);
                formData.append('payment_type', this.selectedPaymentMethod);
                formData.append('discount_type', this.discount_type);

                if(this.discount_type == 'fix_amount'){
                    formData.append('discount_value', this.printInvoiceData.discount);
                }
                if(this.discount_type == 'percentage'){
                    formData.append('discount_percentage', this.printInvoiceData.discount);
                }
                if(this.discount_type == 'room_discount'){
                    formData.append('room_discount_id', this.room_discount.id);
                }
                formData.append('order_categories', JSON.stringify(this.orderList));
                // formData.append('total_session_price', this.printInvoiceData.room);
                // formData.append('food_charge', this.printInvoiceData.food);
                formData.append('service_charge', this.printInvoiceData.service_charge);
                formData.append('tax', this.printInvoiceData.isTax);
                // formData.append('total', this.printInvoiceData.total);
                console.log(formData)
                let response = await postApiData({ url: '/api/entities/done', form_data: formData, token: this.getToken()});
                if(response.success){
                    await this.getRoomList();
                    // this.selectedRoom = await this.roomList[this.selectedRoomIndex];
                    this.isOpenRoom.step_1 = true;
                    this.isOpenRoom.step_2 = false;
                    this.isOpenRoom.step_detail = false;
                    this.isOpenRoom.step_invoice = false;

                    console.log("success")
                }
                else{
                    console.log('some errors occur');
                }
            },


            closeModal() {
                document.getElementById("closeModal").click();
            },
            closeMenuModal() {
                document.getElementById("closeMenuModal").click();
            },
            closeCustomerModal() {
                document.getElementById("closeCustomerModal").click();
            },
            closeChangeRoomModal() {
                document.getElementById("close_change_room_modal").click();
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

            clearOpenRoomForm(){
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

            //root top
            btnGetTableListTab(){
                this.getTableListTab()
            },
            async getTableListTab(){
                const response = await getApiData({ url: '/api/tables', token: this.getToken() });
                if(response.data){
                    this.tableList = response.data;
                }
            }

            // async initialSidebarShow(){
            //     alert(this.roomList[0].invoices.length)
            //     if(this.roomList[0].invoices.length>0){
            //         this.isOpenRoom.step_1 = false;
            //         this.isOpenRoom.step_2 = false;
            //         this.isOpenRoom.step_detail = true;
            //         alert('test2')
            //     }
            //     if(this.roomList[0].invoices.length<1){
            //         this.isOpenRoom.step_1=true;
            //         this.isOpenRoom.step_2 = false;
            //         this.isOpenRoom.step_detail = false;
            //         alert('test')
            //     }
            // }
        },
        watch :{
            selectedRoom(val, oldVal) {
                console.log(`new: ${val}, old: ${oldVal}`)
            },
        },
        mounted()
        {
            this.getAreaList();
            this.getGendersList();
            // this.getRoomList();
            this.getCustomerList();
            this.getMenuList();
            // this.getChangeableRoomList();
            // this.getSelectedRoom();
            // this.initialSidebarShow();

            // this.initialGetRoomList();
            this.getPackageList();
            initTE({ Modal, Select, Ripple, Tab });

        }
    }
</script>
