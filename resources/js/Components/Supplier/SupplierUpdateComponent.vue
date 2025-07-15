<template>
    <div class="px-0">
        <div class="mb-3">
            <p class="text-lg font-semibold font-inter">
                Edit Supplier
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white p-8 rounded-md">
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Supplier Name
                </label>
                <input type="text" v-model="name"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Supplier Shop Name
                </label>
                <input type="text" v-model="shopName"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <!-- <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Supplier Ph Number
                </label>
                <input type="tel" v-model="phoneNumber"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div> -->
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Credit Limit
                </label>
                <input type="number" v-model="maxCredit"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 relative">
                <label for="" class="block text-sm text-black mb-3 absolute -top-6 text-center w-full">
                    (Lead Time)
                </label>
                <div class="grid grid-cols-3 gap-x-4">
                    <div>
                        <label for="" class="block text-sm text-black mb-3">
                            Day
                        </label>
                        <input type="number" v-model="lead_time_day" min="0"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div>
                        <label for="" class="block text-sm text-black mb-3">
                            Hour
                        </label>
                        <input type="number" v-model="lead_time_hour" min="0" max="24"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div>
                        <label for="" class="block text-sm text-black mb-3">
                            Min 
                        </label>
                        <input type="number" v-model="lead_time_min" min="0" max="60"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                </div>
                
            </div>
            <!-- <div class="mb-4 col-span-3 pb-6 rounded-md row-span-2">
                <label for="" class="block text-sm text-black mb-3">
                    Credit Terms
                </label>
                <textarea name="" v-model="credit_terms"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="7"></textarea>
            </div> -->
            <div class="mb-4 col-span-3 pb-6 rounded-md row-span-2">
                <label for="" class="block text-sm text-black mb-3">
                    Address
                </label>
                <textarea name="" v-model="address"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="7"></textarea>
            </div>
            <div class="mb-1 col-span-3 rounded-md pl-4">
                <label for="" class="block text-sm text-black mb-3">
                    Amount
                </label>
                <input type="number" v-model="amount"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-1 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date
                </label>
                <input type="date" v-model="date"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-6 px-4">
                <div class="flex justify-between mb-2">
                    <label for="" class="block text-base text-black mb-0">
                        Account
                    </label>
                    <button class="add-btn mt-0.5" data-te-toggle="modal" data-te-target="#create_modal">
                        <i class="fal fa-plus"></i>
                    </button>
                </div>
                <div>
                    <p class="mb-3 text-sm">
                        <span class="text-gray-600 pr-2 w-32 inline-block">AP Account</span>  : <span class="pl-1">{{ selectedAccount ? selectedAccount.name : '' }}</span>
                    </p>
                    <p class="text-sm">
                        <span class="text-gray-600 pr-2 w-32 inline-block">Creditor Account</span> : <span class="pl-1">{{ selectedCreditAccount ? selectedCreditAccount.name : '' }}</span>
                    </p>
                </div>
            </div>

            <div class="col-span-3"></div>
            <div class="mb-12 col-span-3 pb-0 rounded-md">
                <label class="label-form mb-3">Credit Terms</label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Credit Term" v-model="selectedTermType" class="input-ui !text-black"
                    data-te-select-filter="false">
                        <option :value="type.value" v-for="type in termList">{{ type.name }}</option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 " v-if="selectedTermType === 'day'">
                <label for="" class="block text-sm text-black mb-3">
                    Day
                </label>
                <input type="number" v-model="term_day"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 " v-if="selectedTermType === 'exact_date'">
                <label for="" class="block text-sm text-black mb-3">
                    Exact Date
                </label>
                <input type="date" v-model="term_exact_date"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3" v-if="selectedTermType === 'amount_limitation'">
                <label for="" class="block text-sm text-black mb-3">
                    Amount Limitation
                </label>
                <input type="number" v-model="term_limitation_amount"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-12"></div>

            
            <!-- <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Items
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Items" v-model="selectedItem"  class="input-ui !text-black"
                    data-te-select-filter="true" @change="itemSelectChanged">
                        <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                            {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label class="label-form mb-3">Brands</label>
                <multiselect
                v-model="selectedItemBrands"
                :options="itemBrandsList"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Brands"
                label="name"
                track-by="id"
                :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        brands selected</span>
                    </template>
                </multiselect>
            </div>
            <div class="col-span-3" :class="selectedItemList.length < 1 ? 'mb-10' : ''">
                <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 " @click="addItemBtnClicked()" >
                    Add
                </button>
            </div><div class="col-span-3"></div>
            <div class=" col-span-12 mb-8" v-show="selectedItemList.length > 0">
                <table class="min-w-[50%] text-sm font-light ml-2">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" pr-6 py-4 ">
                                Item
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Brand
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(selectedItem,selectedItemIndex) in selectedItemList" >
                            <td class=" pr-6 py-3 font-medium ">
                                {{ selectedItem.item_name }}
                            </td>
                            <td class=" px-6 py-3 font-medium ">
                                {{ selectedItem.brand_name }}
                            </td>
                            <td class=" px-6 py-3 font-medium  text-right">
                                <input v-if="selectedItem.id" :checked="selectedItem.is_active == 1" @change="itemActiveToggled(selectedItem.id)"
                                            class="me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                    before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] 
                                    after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-400 after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                    after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                    checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                    checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                    focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                    focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                    checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                    checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] "
                                            type="checkbox" role="switch" />

                                <button v-if="!selectedItem.id" @click="deleteSelectedItemBtnClicked(selectedItemIndex)" >
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->

            <!-- supplier phone list -->
            <div class="contents">
                <div class="mb-4 col-span-3 pb-0 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Phone Numbers
                    </label>
                    <input type="number" v-model="ph_number" autocomplete="off"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                </div>
                <div class="mb-4 col-span-3 pb-0 rounded-md">
                    <label class="label-form mb-3">Type</label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type" v-model="selectedType" class="input-ui !text-black"
                        data-te-select-filter="false">
                            <option :value="type" v-for="type in typeList">{{ type.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-span-3" :class="phoneNumberList.length < 1 ? 'mb-10' : ''">
                    <label class="label-form mb-3">&nbsp;</label>
                    <button type="button" class=" add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 " @click="btnclickedAddPhone()" >
                        Add
                    </button>
                </div><div class="col-span-3"></div>
                <div class=" col-span-12 mb-8" v-show="phoneNumberList.length > 0">
                    <table class="min-w-[50%] text-sm font-light ml-2">
                        <thead class="font-medium text-left ">
                            <tr>
                                <th scope="col" class=" pr-6 py-4 ">
                                    Phone Number
                                </th>
                                <th scope="col" class=" px-6 py-4 ">
                                    Type
                                </th>
                                <th scope="col" class=" px-6 py-4 ">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(phone,phoneIndex) in phoneNumberList" >
                                <td class=" pr-6 py-3 font-medium ">
                                    {{ phone.phone_number }}
                                </td>
                                <td class=" px-6 py-3 font-medium capitalize">
                                    {{ phone.type }}
                                </td>
                                <td class=" px-6 py-3 font-medium text-right">
                                    <input v-if="phone.id" :checked="phone.is_active == 1" @change="phoneActiveToggled(phone.id)"
                                            class="me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                    before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] 
                                    after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-400 after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                    after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                    checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                    checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                    focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                    focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                    checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                    checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] "
                                            type="checkbox" role="switch" />
                                    <button v-if="!phone.id" @click="deleteSeleted(phoneIndex,phoneNumberList)" >
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="contents">
                <div class="mb-4 col-span-3 pb-0 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Account Name
                    </label>
                    <input type="text" v-model="account_name" autocomplete="off"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                </div>
                <div class="mb-4 col-span-3 pb-0 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Account Number
                    </label>
                    <input type="text" v-model="account_number" autocomplete="off"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                </div>
                <div class="col-span-3">
                    <label class="label-form mb-3">&nbsp;</label>
                    <button type="button" class=" add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 " @click="btnclickedAddBankAccount()" >
                        Add
                    </button>
                </div><div class="col-span-3"></div>
                <div class=" col-span-12 mb-6" v-show="bankAccountList.length > 0">
                    <table class="min-w-[50%] text-sm font-light ml-2">
                        <thead class="font-medium text-left ">
                            <tr>
                                <th scope="col" class=" pr-6 py-4 ">
                                    Account Name
                                </th>
                                <th scope="col" class=" px-6 py-4 ">
                                    Account Number
                                </th>
                                <th scope="col" class=" px-6 py-4 ">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(acc,accIndex) in bankAccountList" >
                                <td class=" pr-6 py-3 font-medium ">
                                    {{ acc.account_name }}
                                </td>
                                <td class=" px-6 py-3 font-medium capitalize">
                                    {{ acc.account_number }}
                                </td>
                                <td class=" px-6 py-3 font-medium text-right">
                                    <input v-if="acc.id" :checked="acc.is_active == 1" @change="accActiveToggled(acc.id)"
                                            class="me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                    before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] 
                                    after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-400 after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                    after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                    checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                    checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                    focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                    focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                    checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                    checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] "
                                            type="checkbox" role="switch" />
                                    <button v-if="!acc.id" @click="deleteSeleted(accIndex,bankAccountList)" >
                                        <i class="fal fa-trash  pr-3" @click="deleteSeleted(accIndex,bankAccountList)" ></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <button class="add-btn" @click="createBtnClicked">
                Update Supplier
            </button>
        </div>
    </div>

        <!-- Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create Supplier AP Account
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Name
                        </label>
                        <input type="text" v-model="accName" placeholder="AP Account Name"  class="input-ui">
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="createAccountBtnClicked()"
                        class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create Supplier AP Account
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" v-model="apAccountName" placeholder="AP Account Name"  class="input-ui">
                        </div>

                        <div class="mb-4">
                            <div>
                                <label class="label-form mb-3">AP Sub Account</label>
                                <multiselect v-model="selectedPayableSubAccount" :options="payableSubAccountList" :close-on-select="true"
                                :clear-on-select="false" :preserve-search="true" placeholder="Select Sub Account" label="name"
                                track-by="id" :preselect-first="true"></multiselect>
                            </div>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="createAPAccountBtnClicked"
                            class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- credit Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="create_credit_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create Credit Account
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Name
                        </label>
                        <input type="text" v-model="creditAccountName" placeholder="Credit Account Name"  class="input-ui">
                    </div>
                    <div class="mb-4">
                        <div>
                            <label class="label-form mb-3">Credit Sub Account</label>
                            <multiselect v-model="selectedCreditSubAcc" :options="creditSubAccList" :close-on-select="true"
                                :clear-on-select="false" :preserve-search="true" placeholder="Select Sub Account" label="name"
                                track-by="id" :preselect-first="true"></multiselect>
                        </div>
                    </div>
                </div>

                    <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="createCreditAccountBtnClicked"
                        class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    props: ["supplierId"],

    data() {
        return {
            itemList: [],
            apAccountList: [],
            creditAccList:[],
            selectedItemList: [],
            existingItems: [],
            phoneNumberList:[],
            bankAccountList:[],

            accName:null,
            selectedAccount: null,
            selectedCreditAccount:null,
            supplier: null,
            name: null,
            shopName: null,
            address: null,
            maxCredit: null,
            lead_time_day:0,
            lead_time_hour:0,
            lead_time_min:0,
            credit_terms:null,
            amount:null,
            date:null,

            ph_number: null,
            selectedType:null,

            account_name:null,
            account_number:null,

            payableSubAccountList: [],
            selectedPayableSubAccount: null,
            apAccountName: null,

            creditSubAccList:[],
            creditAccountName:null,
            selectedCreditSubAcc:null,

            itemBrandsList: [],
            selectedItem:null,
            selectedItemBrands: [],

            typeList:[
                {value:'phone',name:'Phone'},
                {value:'kpay',name:'Kpay'}
            ],
            test:null,

            termList:[
                {value:'day',name:'Day'},
                {value:'amount_limitation',name:'Amount Limitation'},
                {value:'exact_date',name:'Exact Date'}
            ],
            selectedTermType: null,
            term_day: null,
            term_exact_date: null,
            term_limitation_amount: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        deleteSelectedItemBtnClicked(index){
            // let index = this.selectedItemList.findIndex(item => item.id == id);
            if(index != -1){
                this.selectedItemList.splice(index, 1);
            }
        },

        deleteExistingItemBtnClicked(id){
            let index = this.existingItems.findIndex(item => item.id == id);
            if(index != -1){
                this.existingItems.splice(index, 1);
            }
        },

        async itemSelectChanged(){
            this.selectedItemBrands = [];
            let url = `/api/get_brand_by_item?item_ids[]=${this.selectedItem.id}`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.success){
                this.itemBrandsList = response.data;
            }
            // console.log(`Existing items`,this.existingItems);
            // console.log(`Selected items`,this.selectedItems);
            // this.existingItems.forEach((existingItem)=>{
            //     this.selectedItems.forEach((selectedItem)=>{
            //         if(existingItem.id == selectedItem.id){
            //             this.selectedItems.pop();
            //         }
            //     });
            // });
        },

        async getSupplierDetail(){
            let url = `/api/suppliers/${this.supplierId}`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.supplier = response.data;
                this.name = this.supplier.name;
                this.shopName = this.supplier.shop_name;
                this.phoneNumber = this.supplier.phone_number;
                this.maxCredit = this.supplier.credit_limit;
                this.address = this.supplier.address;
                this.lead_time_day = this.supplier.lead_time_day;
                this.lead_time_hour = this.supplier.lead_time_hour;
                this.lead_time_min = this.supplier.lead_time_minutes;
                this.selectedTermType = this.supplier.credit_term_type;
                if(this.supplier.credit_term_type === 'day'){
                    this.term_day = this.supplier.day;
                }
                if(this.supplier.credit_term_type === 'exact_date'){
                    this.term_exact_date = this.supplier.exact_date;
                }
                if(this.supplier.credit_term_type === 'amount_limitation'){
                    this.term_limitation_amount = this.supplier.amount_limitation;
                }
                // this.credit_terms = this.supplier.credit_terms;
                // this.existingItems = this.supplier.items;
                this.selectedAccount = this.apAccountList.find(ap => ap.id === this.supplier.account_id )
                this.selectedCreditAccount = this.creditAccList.find(crd => crd.id === this.supplier.creditor_account_id )
                this.supplier.supplier_items.forEach(item =>{
                    this.selectedItemList.push({
                        id:item.id,
                        is_active:item.is_active,
                        item_name:item.item.name,
                        item_id:item.item.id,
                        brand_id:item.brand.id,
                        brand_name:item.brand.name,
                        // brand_name:item.brands.find(brandId => brandId.id === item.pivot.brand_id).name
                    })
                    // this.selectedItem.push(this.itemList.find(itemid => itemid.id === item.id))
                })
                
                this.phoneNumberList = this.supplier.supplier_phone;
                this.bankAccountList = this.supplier.supplier_bank_account;
            }
        },
        itemActiveToggled(id) {
            let index = this.selectedItemList.findIndex(item => item.id == id);
            if (index != -1) {
                if (this.selectedItemList[index].is_active == 1) {
                    this.selectedItemList[index].is_active = 0;
                }
                else {
                    this.selectedItemList[index].is_active = 1;
                }
                let url = `/api/toggle_brand_item`;
                let formData = new FormData();
                formData.append('id', id);
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
                console.log('test')
                if(response.success == 'true'){
                    console.log(response)
                    
                }
                // this.getSupplierDetail();
            }
        },

        phoneActiveToggled(id) {
            let index = this.phoneNumberList.findIndex(phone => phone.id == id);
            if (index != -1) {
                if (this.phoneNumberList[index].is_active == 1) {
                    this.phoneNumberList[index].is_active = 0;
                }
                else {
                    this.phoneNumberList[index].is_active = 1;
                }
                let url = `/api/suppliers/toggle_phones`;
                let formData = new FormData();
                formData.append('id', id);
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
                console.log('test')
                if(response.success == 'true'){
                    console.log(response)
                    
                }
                // this.getSupplierDetail();
            }
        },
        accActiveToggled(id) {
            let index = this.bankAccountList.findIndex(acc => acc.id == id);
            if (index != -1) {
                if (this.bankAccountList[index].is_active == 1) {
                    this.bankAccountList[index].is_active = 0;
                }
                else {
                    this.bankAccountList[index].is_active = 1;
                }
                let url = `/api/suppliers/toggle_bank_accounts`;
                let formData = new FormData();
                formData.append('id', id);
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
                this.test = response
                if(response.success == 'true'){
                    console.log(response)
                    
                }
                // this.getSupplierDetail();
            }
        },

        addItemBtnClicked(){
            if(!this.selectedItem){
                this.alertValiationMessage(`Item`);
                return 1;
            }
            else if(this.selectedItemBrands.length < 1){
                this.alertValiationMessage(`Brands for item`);
                return 1;
            }
            else{
                this.selectedItemBrands.forEach(item =>{
                    this.selectedItemList.push({
                        item_name: this.selectedItem.name,
                        item_id: this.selectedItem.id,
                        brand_name: item.name,
                        brand_id: item.id,
                    })
                })

                this.selectedItem = null;
                this.selectedItemBrands = [];
                this.itemBrandsList = [];
            }
        },
        btnclickedAddPhone(){
            if(!this.ph_number){
                this.alertValiationMessage(`Phone Number`);
                return;
            }
            else if(!this.selectedType){
                this.alertValiationMessage(`Type`);
                return;
            }
            else{
                this.phoneNumberList.push({
                    phone_number: this.ph_number,
                    type: this.selectedType.value,
                });
                this.ph_number = null;
                this.selectedType = null;
            }
        },
        btnclickedAddBankAccount(){
            if(!this.account_name){
                this.alertValiationMessage(`Account Name`);
                return;
            }
            else if(!this.account_number){
                this.alertValiationMessage(`Account Number`);
                return;
            }
            else{
                this.bankAccountList.push({
                    account_name: this.account_name,
                    account_number: this.account_number,
                });
                this.account_name = null;
                this.account_number = null;
                
            }
        },
        async createBtnClicked(){
            // if(this.existingItems.length > 0){
            //     this.existingItems.forEach((item)=>{
            //         this.selectedItemList.push(item);
            //     });
            //     this.existingItems = [];
            //     console.log(this.existingItem)
            // }

            if(!this.name){
                this.alertValidationMessage(`supplier name`);
                return 1;
            }
            if(!this.shopName){
                this.alertValidationMessage(`supplier shop`);
                return 1;
            }
            // if(!this.phoneNumber){
            //     this.alertValidationMessage(`supplier phone number`);
            //     return 1;
            // }
            if(!this.address){
                this.alertValidationMessage(`supplier address`);
                return 1;
            }

            if(this.phoneNumberList.length < 1){
                this.alertValidationMessage(`supplier Phone Number`);
                return 1;
            }
            // if(this.bankAccountList.length < 1){
            //     this.alertValidationMessage(`supplier Bank Account`);
            //     return 1;
            // }
            // if(this.selectedItemList.length < 1 ){
            //     this.alertValidationMessage(`supplier selling items`);
            //     return 1;
            // }
            if(!this.maxCredit){
                this.alertValidationMessage(`supplier credit limit`);
                return 1;
            }
            if(!this.selectedAccount){
                this.alertValidationMessage(`Supplier Account Payable`);
                return 1;
            }
            if(this.lead_time_day < 1 && this.lead_time_hour < 1 && this.lead_time_min < 1){
                this.alertValidationMessage(`Lead Time`);
                return 1;
            }
            if(!this.selectedTermType){
                this.alertValidationMessage(`Credit Terms Type`);
                return 1;
            }
            if(!this.selectedCreditAccount){
                this.alertValidationMessage(`Credit account`);
                return 1;
            }

            let formData = new FormData();
            formData.append("id", this.supplierId);
            formData.append("name", this.name);
            formData.append("shop_name", this.shopName);
            // formData.append("phone_number", this.phoneNumber);
            formData.append("credit_limit", this.maxCredit);
            formData.append("lead_time_day", this.lead_time_day);
            formData.append("lead_time_hour", this.lead_time_hour);
            formData.append("lead_time_minutes", this.lead_time_min);
            formData.append("credit_term_type", this.selectedTermType);
            if(this.selectedTermType === 'day'){
                formData.append("day", this.term_day);
            }
            if(this.selectedTermType === 'exact_date'){
                formData.append("exact_date", this.term_exact_date);
            }
            if(this.selectedTermType === 'amount_limitation'){
                formData.append("amount_limitation", this.term_limitation_amount);
            }
            formData.append("address", this.address);
            formData.append("account_id", this.selectedAccount.id);
            formData.append("creditor_account_id", this.selectedCreditAccount.id);
            formData.append("credit_opening_amount", this.amount);
            formData.append("credit_opening_date", this.date);
            let itemBrandList = [];
            this.selectedItemList.forEach((item)=>{
                itemBrandList.push({
                    item_id:item.item_id,
                    brand_id:item.brand_id
                })
            });
            // formData.append("supplier_items", JSON.stringify(this.selectedItemList));
            formData.append("supplier_phones", JSON.stringify(this.phoneNumberList));
            if(this.bankAccountList.length > 0){
                formData.append("supplier_bank_accounts", JSON.stringify(this.bankAccountList));
            }
            
            let url = `/api/suppliers`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                window.location.replace("/suppliers");
            }
        },
        async getItemList(){
            let url = `/api/items`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.itemList = response.data;
            }
        },
        async getAPAccounts(){
            let url = `/api/get_payable_account`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.apAccountList = response.data;
            }
        },
        async getCreditAccList(){
            let url = `/api/get_creditor_account_list`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.creditAccList = response.data;
            }
        },

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
        async getPayableSubAccountList(){
            let url = `/api/sub_account_by_head_account/4`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.payableSubAccountList = response.data;
                this.creditSubAccList = response.data;
            }
        },

        async createAccountBtnClicked(){
            let url = '/api/create_supplier_account';
            let formData = new FormData();
            formData.append("name", this.accName);
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.selectedAccount = response.data.other_payable;
                this.selectedCreditAccount = response.data.creditor;
            }
        },
        async createAPAccountBtnClicked(){
            let url = `/api/create_payable_account`;
            let formData = new FormData();
            formData.append("name", this.apAccountName);
            formData.append("sub_account_id", this.selectedPayableSubAccount.id);
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.apAccountList.unshift(response.data);
                this.selectedAccount = response.data;
            }
        },
        async createCreditAccountBtnClicked(){
            let url = `/api/create_creditor_account`;
            let formData = new FormData();
            formData.append("name", this.creditAccountName);
            formData.append("sub_account_id", this.selectedCreditSubAcc.id);
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.apAccountList.unshift(response.data);
                this.selectedCreditAccount = response.data;
            }
        },
    },

    created(){
        
        this.getAPAccounts();
        this.getCreditAccList();
        this.getItemList();
        this.getSupplierDetail();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>
