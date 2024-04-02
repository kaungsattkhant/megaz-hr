<template>
    <div>

        <div class="">
            <div class="w-[67%] pt-9 px-6">
                <ul
                    class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0"
                    role="tablist"
                    data-te-nav-ref>
                    <li role="presentation">
                        <a
                        href="#tabs-home"
                        class="my-2 mr-3 text-white block  px-7 pb-2.5 rounded-full
                            pt-3 text-xs  hover:isolate bg-[#F0C094]
                            hover:bg-[#f7a559] focus:isolate data-[te-nav-active]:bg-[#F19E51] "
                        data-te-toggle="pill"
                        data-te-target="#tabs-home"
                        data-te-nav-active
                        role="tab"
                        aria-controls="tabs-home"
                        aria-selected="true"
                        >KTV</a
                        >
                    </li>
                    <li role="presentation" @click="btnGetTableListTab()">
                        <a
                        href="#tabs-profile"
                        class="my-2 mr-3 text-white block  px-7 pb-2.5 rounded-full
                            pt-3 text-xs  hover:isolate bg-[#F0C094]
                            hover:bg-[#f7a559] focus:isolate data-[te-nav-active]:bg-[#F19E51]"
                        data-te-toggle="pill"
                        data-te-target="#tabs-profile"
                        role="tab"
                        aria-controls="tabs-profile"
                        aria-selected="false"
                        >Roof Top</a
                        >
                    </li>

                </ul>

                <div class="mb-6">
                    <div
                        class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-home"
                        role="tabpanel"
                        aria-labelledby="tabs-home-tab"
                        data-te-tab-active>
                        <div class="flex flex-wrap gap-x-4 gap-y-4">
                            <!-- <div class="bg-[#FF7675] flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
                                <div class="flex flex-col justify-between h-full">
                                    <div>
                                        <p class="text-sm text-white">Start Time : 9:00 </p>
                                        <p class="text-sm text-white">Start Time : 9:00 </p>
                                    </div>
                                    <div class=" w-full flex justify-between">
                                        <p class="text-base self-end text-white">
                                            1000MMks
                                        </p>
                                        <p class="text-2xl text-white">
                                            1
                                        </p>
                                    </div>
                                </div>
                            </div> -->
                            <div v-for="(room,index) in roomList" :class="room.is_active == 0 ? 'bg-[#55EFC4]' : 'bg-[#FF7675]'"
                                class=" flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">

                                <button @click="btnClickedIsOpenRoom(room,index)" class="relative flex flex-col justify-between h-full w-full">

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
                                            {{  room.name }}
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
                    <div
                        class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-profile"
                        role="tabpanel"
                        aria-labelledby="tabs-profile-tab">
                        <div class="flex flex-wrap gap-x-4 gap-y-4">
                            <div v-for="(table,index) in tableList" :class="table.is_active == 0 ? 'bg-[#55EFC4]' : 'bg-[#FF7675]'"
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
                                            {{  table.name }}
                                        </p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
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
                                @click="btnClickedGetChangeableRoomList()" data-te-toggle="modal" data-te-target="#change_modal">
                                <i class="far fa-random"></i>
                            </button>
                            <button @click="btnClickAddMenu" class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
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
                                    Invoice Id {{ selectedRoom.invoices.length > 0 ? selectedRoom.invoices[0].invoice_id : '' }}
                                </p>
                                <p class="text-sm text-black font-semibold">
                                    <!-- 35,000 MMks -->

                                    {{ (selectedRoom.price_per_hour * (selectedRoom.invoices.length > 0 ? selectedRoom.invoices[0].sessions[0].session_duration : 1)).toLocaleString() }}
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
                                    Start Time : {{ selectedRoom.invoices.length > 0 ? selectedRoom.invoices[0].sessions[0].start_date : '' }}
                                </p>
                                <p class="text-sm text-black">
                                    End Time : {{ selectedRoom.invoices.length > 0 ? selectedRoom.invoices[0].sessions[0].end_date : '' }}
                                </p>
                            </div>
                        </div>

                        <div class="padding-section border-b    ">
                            <div class="flex justify-between font-semibold mb-3">
                                <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                    Menu Total
                                </p>
                                <p class="text-sm text-black font-semibold">
                                    {{ purchaseMenuList.length > 0 ? purchaseMenuList[0].total.toLocaleString() : '0' }} MMks
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
                                        <p class=" col-span-2 text-center text-xs">
                                            FOC
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
                                    (
                                        selectedRoom ?
                                        (
                                            purchaseMenuList.length > 0 ?
                                            (
                                                (selectedRoom.price_per_hour * selectedRoom.invoices[0].sessions[0].session_duration) + purchaseMenuList[0].total
                                            ).toLocaleString()
                                            : (
                                                selectedRoom.price_per_hour * selectedRoom.invoices[0].sessions[0].session_duration
                                                ).toLocaleString()
                                        )
                                        : 0
                                    )

                                }} MMKs

                                <!-- <span v-if="purchaseMenuList.length > 1" >
                                    {{ ((selectedRoom.price_per_hour * selectedRoom.invoices[0].sessions[0].session_duration) + purchaseMenuList[0].total).toLocaleString() }} MMKs
                                </span>
                                <span v-else>
                                    0 MMKs
                                </span> -->
                            </p>
                        </div>
                        <div class="">
                            <button @click="btnClickedDoneSession()" class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
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
                        </div><div></div>
                    </div>
                    <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 329px)">
                        <div class="padding-section w-2/3 mx-auto ">

                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Discount
                                </label>
                                <input type="number" placeholder="Discount" v-model="printInvoiceData.discount"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4">
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
                            </div>
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
                                    <input
                                        class="input-check-pos"
                                        type="checkbox"
                                        value=""
                                        id="tax"
                                        checked />
                                    <label
                                        class="inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="tax">
                                        Tax
                                    </label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="input-check-pos"
                                        type="checkbox"
                                        value=""
                                        id="service"
                                        checked />
                                    <label
                                        class="inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="service">
                                        Service Charges
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute bottom-0 border-t-2 border-gray-200 w-full padding-section !pt-3">
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Room
                            </p>
                            <p class=" w-28">
                                {{  printInvoiceData.room ? printInvoiceData.room.toLocaleString() : 0 }} MMKs
                                <!-- {{ (selectedRoom.price_per_hour * (selectedRoom.invoices.length > 0 ? selectedRoom.invoices[0].sessions[0].session_duration : 1)).toLocaleString() }} MMKs -->
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Food
                            </p>
                            <p class=" w-28">
                                {{  printInvoiceData.food ? printInvoiceData.food.toLocaleString() : 0}} MMKs
                                <!-- {{ purchaseMenuList.length > 0 ? purchaseMenuList[0].total.toLocaleString() : '0' }} MMks -->
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Service Tax
                            </p>
                            <p class=" w-28">
                                {{  printInvoiceData.service_charge ? printInvoiceData.service_charge.toLocaleString() : 0 }} MMKs
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Tax
                            </p>
                            <p class=" w-28">
                                {{  printInvoiceData.tax ? printInvoiceData.tax.toLocaleString() : 0 }} MMKs
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Discount
                            </p>
                            <p class=" w-28">
                                {{  printInvoiceData.discount }} MMKs
                            </p>
                        </div>
                        <div class=" text-right pr-3 mb-3">
                            <p class="font-semibold">
                                Total &nbsp;
                                <!-- {{  (printInvoiceData.room + printInvoiceData.food + printInvoiceData.service_charge + printInvoiceData.tax ) }} -->
                                {{  printInvoiceData.total ? printInvoiceData.total.toLocaleString() : 0 }} MMKs
                            </p>
                        </div>
                        <div class="">
                            <button @click="btnClickedEndRoom()" class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
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
                            <button  @click="btnClickedOpenRoom" class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
                                Open Room
                            </button>
                        </div>
                    </div>
                </div>
                <div class=""  v-show="isOpenRoom.step_2 == true" id="open_room_2">
                    <div class="small-scrollbar overflow-y-auto h-[100vh] pt-8">
                        <div class="padding-section w-2/3 mx-auto ">
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Customer Name
                                </label>
                                <div class="relative">
                                    <select name="" id="" v-model="selectedCustomer"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                        <option :value="customer" v-for="(customer, customerIndex) in customerList" :key="customerIndex" > {{ customer.name }} </option>
                                    </select>
                                    <button class="absolute -right-6 transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                        data-te-toggle="modal" data-te-target="#create_customer_modal">
                                        +
                                    </button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Deposit
                                </label>
                                <input type="text" placeholder="Deposit"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Time
                                </label>
                                <input type="datetime-local" placeholder="Time" v-model="invoice_date"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Duration
                                </label>
                                <input type="text" placeholder="" v-model="duration"
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
                            <button @click="confirmRoomBtnClicked()" class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3">
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
                        <button type="button" id="closeModal" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
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
                                <input
                                    class="input-check-pos"
                                    type="checkbox"
                                    value=""
                                    id="checkboxChecked"
                                    checked />
                                <label
                                    class="inline-block pl-[0.15rem] hover:cursor-pointer"
                                    for="checkboxChecked">
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
                        <button type="button" id="closeMenuModal" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
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
                        <button type="button" id="close_change_room_modal" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
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
                                <option :value="changeableRoom" :key="index" v-for="(changeableRoom,index) in changeableRoomList">{{ changeableRoom.name }}</option>
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
            id="create_customer_modal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Create Customer
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none
                        " id="closeModal"
                            data-te-modal-dismiss aria-label="Close">
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
                                <option :value="gender.id" v-for="(gender, genderIndex) in genderList" :key="genderIndex" > {{ gender.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Birthdate
                            </label>
                            <input type="text" placeholder="Birthdate" v-model="date"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Address
                            </label>
                            <textarea v-model="address" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
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

    export default {
        data() {
            return {
                roomList:[],
                selectedRoom:null,
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
                invoice_date:null,
                male:null,
                female:null,
                child:null,
                purchaseMenuList: [],
                selectedRoomIndex:null,
                printInvoiceData:{
                    room:0,
                    food:0,
                    service_charge:0,
                    tax:0,
                    discount:0,
                    tatalPrice:0
                },
                selectedPaymentMethod:null,
                change:null,
                paid_amount:null,

                //create menu , add hour , change room
                menuList: [],
                invoiceId:null,
                selectedMenu:null,
                menuQuantity:null,
                menuPrice:null,
                testroom:null,
                sessionDuration:null,
                changeableRoomList:[],
                change_room:null,

                // doneSession


                //rooftop
                tableList:[],

                currentTime: getCurretDateTime(),
            };
        },

        methods: {
            async getGendersList(){
                const response = await getApiData({ url: '/api/genders' });
                if(response.data){
                    this.genderList = response.data;
                }
            },
            async getMenuList(){
                const response = await getApiData({ url: '/api/menus' });
                if(response.data){
                    this.menuList = response.data;
                }
            },
            async getPurchaseMenuList(){
                const response = await getApiData({ url: '/api/rooms/' + this.selectedRoom.id });
                if(response.data){
                    if(response.data.invoices.length > 0){
                        this.purchaseMenuList = response.data.invoices[0].orders;
                    }

                }
            },
            async getRoomList(){
                const response = await getApiData({ url: '/api/rooms' });
                if(response.data){
                    this.roomList = response.data;
                    // console.log( this.roomList[0] );
                }
            },
            async initialGetRoomList(){
                const response = await getApiData({ url: '/api/rooms' });
                if(response.data){
                    this.roomList = response.data;
                    this.selectedRoom = this.roomList[0]
                    if(this.roomList[0].invoices.length>0){
                        this.isOpenRoom.step_1 = false;
                        this.isOpenRoom.step_2 = false;
                        this.isOpenRoom.step_detail = true;
                    }
                    if(this.roomList[0].invoices.length<1){
                        this.isOpenRoom.step_1=true;
                        this.isOpenRoom.step_2 = false;
                        this.isOpenRoom.step_detail = false;
                    }
                    console.log( this.roomList[0] );
                }
            },
            async getCustomerList(){
                const response = await getApiData({ url: '/api/customers' });
                if(response.data){
                    this.customerList = response.data;
                }
            },

            btnClickedIsOpenRoom(room,index){
                this.selectedRoom = room;
                this.selectedRoomIndex = index;
                if(this.roomList[index].invoices.length>0){
                    // alert(this.roomList[index].invoices.length)
                    this.isOpenRoom.step_1 = false;
                    this.isOpenRoom.step_2 = false;
                    this.isOpenRoom.step_invoice = false;
                    this.isOpenRoom.step_detail = true;
                    this.getPurchaseMenuList();
                }
                if(this.roomList[index].invoices.length<1){
                    // alert(this.roomList[index].invoices.length)
                    this.isOpenRoom.step_1=true;
                    this.isOpenRoom.step_2 = false;
                    this.isOpenRoom.step_invoice = false;
                    this.isOpenRoom.step_detail = false;
                    this.getPurchaseMenuList();
                }
                console.log(this.selectedRoom)
            },
            btnClickedOpenRoom(){
                this.isOpenRoom.step_1=false;
                this.isOpenRoom.step_2=true;
                this.isOpenRoom.step_detail=false;

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
                let response = await postApiData({url: '/api/customers', form_data: formData});
                console.log(this.selectedGender+','+this.name+','+this.email+','+this.ph_number+','+this.address+','+this.date)
                if(response.success){
                    this.customerList.push(response.data);
                    this.selectedCustomer = response.data;
                    // this.getRoomList(null);
                    console.log("success")
                    this.closeModal();
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
                formData.append('session_duration', this.duration);
                if(this.female > 0){
                    formData.append('female', +this.female);
                }
                if(this.male > 0){
                    formData.append('male', +this.male);
                }
                if(this.child > 0){
                    formData.append('child', +this.child);
                }
                let response = await postApiData({url: '/api/rooms/start', form_data: formData});
                if(response.success){
                    await this.getRoomList();
                    this.selectedRoom = await this.roomList[this.selectedRoomIndex];
                    this.isOpenRoom.step_1 = false;
                    this.isOpenRoom.step_2 = false;
                    this.isOpenRoom.step_detail = true;
                    this.isOpenRoom.step_invoice = false;
                    if(this.selectedRoom.invoices.length>0){
                        this.getPurchaseMenuList();
                    }
                    if(this.selectedRoom.invoices.length<1){
                        this.purchaseMenuList = [];
                    }
                    console.log("success")
                }
                else{
                    console.log('some errors occur')
                }
            },
            btnClickAddMenu(){
                this.invoiceId = this.selectedRoom.invoices[0].invoice_id;
                console.log(this.invoiceId)
            },

            btnConfirmAddMenu(){
                this.addMenu();
            },
            async addMenu()
            {
                let formData = new FormData();
                formData.append('invoice_id', this.invoiceId);
                formData.append('menu_id', this.selectedMenu.id);
                formData.append('quantity', this.menuQuantity);
                formData.append('original_price', this.selectedMenu.prices[0].price);
                let response = await postApiData({url: '/api/rooms/orders', form_data: formData});
                console.log(this.invoiceId+','+this.selectedMenu.id + ','+ this.menuQuantity +','+this.selectedMenu.prices[0].price)
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
                formData.append('invoice_id', this.selectedRoom.invoices[0].invoice_id);
                formData.append('session_duration', this.sessionDuration);
                let response = await postApiData({url: '/api/rooms/add_more_sessions', form_data: formData});
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
                formData.append('invoice_id', this.selectedRoom.invoices[0].invoice_id);
                formData.append('entity_id', this.change_room.id);
                let response = await postApiData({url: '/api/rooms/change_rooms', form_data: formData});
                console.log('change room ' + this.selectedRoom.invoices[0].invoice_id+','+this.change_room.id)
                if(response.success){
                    console.log("success");
                    await this.getRoomList();
                    this.selectedRoom = this.roomList.find(x => x.id === this.change_room.id);
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
                const response = await getApiData({ url: '/api/rooms/lists/inactive' });
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
                this.printInvoiceData.room = this.selectedRoom.price_per_hour * this.selectedRoom.invoices[0].sessions[0].session_duration
                if(this.purchaseMenuList.length > 0){
                    this.printInvoiceData.food = this.purchaseMenuList[0].total
                }
                this.printInvoiceData.tax = this.printInvoiceData.food * 0.05
                this.printInvoiceData.service_charge = (this.printInvoiceData.room + this.printInvoiceData.food) * 0.05
                this.printInvoiceData.total = this.printInvoiceData.room + this.printInvoiceData.food + this.printInvoiceData.tax +this.printInvoiceData.service_charge

                this.selectedPaymentMethod = null
                this.change = null
                this.paid_amount = null
                this.printInvoiceData.discount = 0
            },
            btnClickedEndRoom(){
                this.EndRoom();
            },
            async EndRoom()
            {
                let formData = new FormData();
                formData.append('invoice_id', this.selectedRoom.invoices[0].invoice_id);
                formData.append('change', this.change);
                formData.append('paid_amount', this.paid_amount);
                formData.append('payment_type', this.selectedPaymentMethod);

                formData.append('discount_value', this.printInvoiceData.discount);
                formData.append('total_session_price', this.printInvoiceData.room);
                formData.append('food_charge', this.printInvoiceData.food);
                formData.append('service_charge', this.printInvoiceData.service_charge);
                formData.append('tax', this.printInvoiceData.tax);
                formData.append('total', this.printInvoiceData.total);

                let response = await postApiData({url: '/api/rooms/done', form_data: formData});
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


            //root top
            btnGetTableListTab(){
                this.getTableListTab()
            },
            async getTableListTab(){
                const response = await getApiData({ url: '/api/tables' });
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
            this.getGendersList();
            // this.getRoomList();
            this.initialGetRoomList();
            this.getCustomerList();
            this.getMenuList();
            // this.getChangeableRoomList();
            // this.getSelectedRoom();
            // this.initialSidebarShow();
            initTE({ Modal, Select, Ripple, Tab });

        }
    }
</script>
