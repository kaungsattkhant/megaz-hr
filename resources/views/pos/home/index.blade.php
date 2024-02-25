@extends('pos.layouts.main')

@section('page_title', 'Home')
@section('home', 'pos-active-link')
@section('content')
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
                    <li role="presentation">
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
                            <div class="bg-[#FF7675] flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
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
                            </div>
                            @for($i = 0;$i < 12;$i++)
                            <div class="bg-[#55EFC4] flex-shrink-0 flex-grow p-6 w-40 max-w-44 h-40">
                                <div class="flex flex-col justify-end h-full">
                                    <div class=" w-full flex justify-end">
                                        <p class="text-2xl text-white">
                                            {{ $i+2}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                    <div
                        class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-profile"
                        role="tabpanel"
                        aria-labelledby="tabs-profile-tab">
                        Tab 2 content
                    </div>                    
                </div>
            </div>
            <div class="right-sidebar shadow-lg border-l border-gray-200">
                    <!-- secssion right sidebar -->
                <div class="relative h-full hidden">
                    <div class="flex justify-between padding-section border-b">
                        <div>
                                <p class="text-black text-xl">
                                    Table Details
                                </p>
                        </div>
                        <div class="flex gap-x-3">
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                     data-te-toggle="modal" data-te-target="#change_modal">
                                    a
                                </button>
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                     data-te-toggle="modal" data-te-target="#add_menu_modal">
                                    a
                                </button>
                                <button class="transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                     data-te-toggle="modal" data-te-target="#add_hour_modal">
                                    a
                                </button>
                        </div>
                    </div>
                    <div class="small-scrollbar overflow-y-auto" style="height:calc(100% - 195px)">
                        <div class="padding-section border-b    ">
                            <div class="flex justify-between font-semibold mb-2">
                                <p class="text-sm text-black">
                                    Order Id 1001
                                </p>
                                <p class="text-sm text-black font-semibold">
                                    35,000 MMks
                                </p>
                            </div>
                            <div class="mb-2">
                                <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                    Room 301
                                </p>
                            </div>
                            <div class="">
                                <p class="text-sm text-black mb-2">
                                    Start Time : 9:00 AM Feb 5 2024
                                </p>
                                <p class="text-sm text-black">
                                    Start Time : 9:00 AM Feb 5 2024
                                </p>
                            </div>
                        </div>

                        <div class="padding-section border-b    ">
                            <div class="flex justify-between font-semibold mb-3">
                                <p class="px-2 py-0.5 bg-[#F19E51] text-white text-xs w-fit">
                                    Room 301
                                </p>
                                <p class="text-sm text-black font-semibold">
                                    35,000 MMks
                                </p>
                            </div>
                            <div class=" grid grid-cols-10 gap-x-2 gap-y-3">
                                @for($i = 0;$i < 2;$i++)
                                <div class="contents">
                                    <p class=" col-span-4 text-sm">
                                        Tiger Bottle
                                    </p>
                                    <p class=" col-span-1 text-center text-sm">
                                        1
                                    </p>
                                    <p class=" col-span-2 text-center text-xs">
                                        FOC
                                    </p>
                                    <p class=" col-span-3 text-sm text-right">
                                        12,000 MMKs
                                    </p>
                                </div>
                                <div class="contents">
                                    <p class=" col-span-4 text-sm">
                                        Fried Chicken Rice
                                    </p>
                                    <p class=" col-span-1 text-center text-sm">
                                        1
                                    </p>
                                    <p class=" col-span-2 text-center text-xs">
                                        FOC
                                    </p>
                                    <p class=" col-span-3 text-sm text-right">
                                        112,000 MMKs
                                    </p>
                                </div>
                                @endfor
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
                            <p>
                                Total 10,000 MMKs
                            </p>
                        </div>
                        <div class="">
                            <button class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3"> 
                                Done Session
                            </button>
                        </div>
                    </div>
                </div>
                    <!-- invoice right sidebar -->
                <div class="relative hidden h-full">
                    <div class="flex justify-between padding-section border-b">
                        <button>   
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
                                    Customer Name
                                </label>
                                <div class="relative">
                                    <input type="text" placeholder="Customer Name"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                    <button class="absolute -right-6 transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                        data-te-toggle="modal" data-te-target="#create_customer_modal">
                                        +
                                    </button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                    Discount
                                </label>
                                <input type="text" placeholder="Discount"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4">
                                <label for="" class="block text-sm text-black mb-3">
                                Paid Amount
                                </label>
                                <input type="text" placeholder="Paid Amount"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
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
                                1,000,000 MMKs
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Food
                            </p>
                            <p class=" w-28">
                                1,000,000 MMKs
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Service Tax
                            </p>
                            <p class=" w-28">
                                1,000,000 MMKs
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Tax
                            </p>
                            <p class=" w-28">
                                1,000,000 MMKs
                            </p>
                        </div>
                        <div class=" text-sm text-right flex gap-x-2 justify-end pr-2 mb-2">
                            <p>
                                Discount
                            </p>
                            <p class=" w-28">
                                1,000,000 MMKs
                            </p>
                        </div>
                        <div class=" text-right pr-3 mb-3">
                            <p>
                                Total 10,000 MMKs
                            </p>
                        </div>
                        <div class="">
                            <button class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3"> 
                                Print Invoice
                            </button>
                        </div>
                    </div>
                </div>

                    <!-- open session right sidebar -->
                <div class="relative block h-full">
                    <div class="w-full h-full flex justify-center flex-col">
                        <div class="w-2/3 mx-auto">
                            <div class="text-center">
                                <p class="mb-2 text-black font-semibold">
                                    Open Room
                                </p>
                                <p class="mb-4 text-black font-semibold">
                                    Price : 35,000 MMKs
                                </p>
                            </div>
                            <img class="w-[60%] mx-auto mb-6" src="{{ asset('img/Video_light.png') }}" alt="">
                            <button class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3"> 
                                Open Room
                            </button>
                        </div>
                    </div>
                    <div class="hidden">
                        <div class="small-scrollbar overflow-y-auto h-[100vh] pt-8">
                            <div class="padding-section w-2/3 mx-auto ">
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Customer Name
                                    </label>
                                    <div class="relative">
                                        <input type="text" placeholder="Customer Name"
                                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                        <button class="absolute -right-6 transition duration-150 ease-in-out focus:outline-none focus:ring-0"
                                            data-te-toggle="modal" data-te-target="#create_customer_modal">
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Diposit
                                    </label>
                                    <input type="text" placeholder="Diposit"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Time
                                    </label>
                                    <input type="text" placeholder="Time"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Male
                                    </label>
                                    <input type="text" placeholder="Male"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Female
                                    </label>
                                    <input type="text" placeholder="Female"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <div class="mb-8">
                                    <label for="" class="block text-sm text-black mb-3">
                                        Child
                                    </label>
                                    <input type="text" placeholder="Child"
                                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                </div>
                                <button class="bg-[#55EFC4] text-black text-center text-sm font-semibold w-full py-3"> 
                                    Open Room
                                </button>
                            </div>
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
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
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
                            <input type="text" placeholder="Hour"
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
                        <button class="pos-add-btn focus:outline-none focus:ring-0 ">
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
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-16 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <select name="" id="" placeholder="Room"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="1"> Room</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <!-- <label for="" class="block text-sm text-black mb-3">
                                Hour
                            </label> -->
                            <input type="text" placeholder="Qty"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button class="pos-add-btn focus:outline-none focus:ring-0 ">
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
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-16 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <select name="" id="" placeholder="Room"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="1"> Room</option>
                            </select>
                        </div>
                        
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Change
                        </button>
                    </div>
                </div>
            </div>
        </div>

            <!-- Create Customer modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_customer_modal" tabindex="-1" aria-labelledby="addHourModalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Create Customer
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
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
                            <input type="text" placeholder="Hour"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Phone Number
                            </label>
                            <input type="text" placeholder="Hour"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Gender
                            </label>
                            <input type="text" placeholder="Hour"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Birthdate
                            </label>
                            <input type="text" placeholder="Hour"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Address
                            </label>
                            <textarea class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                 name="" id="" cols="30" rows="10"></textarea>
                        </div>
                        
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
