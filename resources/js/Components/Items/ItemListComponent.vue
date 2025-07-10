<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Items
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex gap-x-4 ">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>

                    <div class="bg-white mb-0 w-[40%] text-sm inline-block" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Filter by category"
                            data-te-select-filter="true" v-model="searchCategory">
                            <option :value="category" v-for="category in itemCategoryList">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>



                    <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
                    <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button>

                </div>
                <div class="flex justify-end gap-x-4">
                    <label for="excel_import_item_type" class="add-btn h-8 cursor-pointer" v-if="feature.includes('item-type.import')">
                        Import Type
                        <input type="file" placeholder="Excel" id="excel_import_item_type" class="opacity-0 w-0 h-0 hidden"  @change="handleItemTypeFileChange">
                    </label>
                    <label for="excel_import_item_category" class="add-btn h-8 cursor-pointer" v-if="feature.includes('item-category.import')">
                        Import Category
                        <input type="file" placeholder="Excel" id="excel_import_item_category" class="opacity-0 w-0 h-0 hidden"  @change="handleItemCategoryFileChange">
                    </label>
                    <label for="excel_import" class="add-btn h-8 cursor-pointer" v-if="feature.includes('item.import')">
                        Import Item
                        <input type="file" placeholder="Excel" id="excel_import" class="opacity-0 w-0 h-0 hidden"  @change="handleFileChange">
                    </label>
                    <!-- <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#import_modal">
                        Excel Import
                    </button> -->
                    <!-- <button type="button" v-if="feature.includes('item.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-8"
                        data-te-toggle="modal" data-te-target="#create_modal" @click="step = 1">
                        Add New
                    </button> -->
                    <a href="/items/create"
                        class="add-btn  h-8 whitespace-nowrap">
                        Add New
                    </a>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Item Code</th>
                                <th>Price</th>
                                <th>Category</th>
                                <!-- <th></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(item, itemIndex) in itemList" :key="itemIndex">
                                <tr class="">
                                    <td class="">
                                        {{ perPage * (currentPage - 1) + (++itemIndex) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.name }}
                                        <a :href="`/items/${item.id}/suppliers`" class="text-blue-600 hover:underline" v-show="feature.includes('item.detail')"> [Detail] </a>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.code }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.average_price" > {{ item.average_price }} </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.category.name }}
                                    </td>
                                    <!-- <td class="whitespace-nowrap">
                                        <button id="price-edit-btn" class="pr-2" data-te-toggle="modal"
                                            data-te-target="#priceUpdateModal" @click="updatePriceBtnClicked(item.id)">
                                            <i class="fas fa-tag"></i>
                                        </button>
                                        <input :checked="item.is_active == 1" @change="isActiveToggled(item.id)"
                                            class="pr-2 me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                    before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5
                                    after:w-5 after:rounded-full after:border-none after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                    after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                    checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                    checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                    focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                    focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                    checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                    checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-white/25 dark:after:bg-surface-dark dark:checked:bg-primary dark:checked:after:bg-primary"
                                            type="checkbox" role="switch" />

                                    <a :href="`/items/${item.id}/pricing_history`" class="text-blue-600 hover:underline" > Pricing History </a>
                                    </td> -->
                                </tr>
                            </div>

                        </tbody>
                    </table>
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1"
                                @click="getItemList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage"
                                @click="getItemList(currentPage + 1)"> »</button>
                        </div>
                    </div>
                </div>

                <!-- <div class="mt-2 ml-2">
                    <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                        <li v-if="!isFirstGroup">
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                @click="previousPaginationGroupBtnClicked" :disabled="isFirstGroup">
                                Previous
                            </button>
                        </li>

                        <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]"
                            :key="pageNumberIndex" :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                            <button v-if="pageNumber == currentPage"
                                class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                                <span
                                    class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                    (current)
                                </span>
                            </button>
                            <button v-else
                                class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                            </button>
                        </li>
                        <li v-if="!isLastGroup">
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                @click="nextPaginationGroupBtnClicked" :disabled="isLastGroup">
                                Next
                            </button>
                        </li>
                    </ul>

                    <ul v-else class="list-style-none flex">
                        <li v-for="(pageNumber, pageNumberIndex) in pageNumbers" :key="pageNumberIndex"
                            :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                            <button v-if="pageNumber == currentPage"
                                class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                                <span
                                    class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                    (current)
                                </span>
                            </button>
                            <button v-else
                                class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                            </button>
                        </li>
                    </ul>
                </div> -->
            </div>

            <!-- <div class="mt-2 ml-2">
                <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                    <li v-if="!isFirstGroup">
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            @click="previousPaginationGroupBtnClicked" :disabled="isFirstGroup">
                            Previous
                        </button>
                    </li>

                    <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span
                                class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                (current)
                            </span>
                        </button>
                        <button v-else
                            class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                        </button>
                    </li>
                    <li v-if="!isLastGroup">
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            @click="nextPaginationGroupBtnClicked" :disabled="isLastGroup">
                            Next
                        </button>
                    </li>
                </ul>

                <ul v-else class="list-style-none flex">
                    <li v-for="(pageNumber, pageNumberIndex) in pageNumbers" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span
                                class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                (current)
                            </span>
                        </button>
                        <button v-else
                            class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                        </button>
                    </li>
                </ul>
            </div> -->
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
                <div v-show="step == '1'">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <!--Modal title-->
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create Item
                        </h5>
                        <!--Close button-->
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!--Modal body-->
                    <div class="relative px-12 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Item Name
                            </label>
                            <input type="text" placeholder="Item Name" v-model="name" class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Code
                            </label>
                            <input type="text" placeholder="Code" v-model="code" class="input-ui">
                        </div>

                        <div class="mb-4 relative">
                            <label for="" class="label-form mb-3">
                                Item Type
                            </label>
                            <select name="" id="" v-model="itemType" class="input-ui">
                                <option :value="item_type" v-for="(item_type, index) in itemTypeList" :key="index"> {{ item_type.name }}
                                </option>
                            </select>
                            <button type="button" class="text-xs absolute -right-6 top-1/2 pt-2" @click="[step = 'type', brandName = null]">
                                <i class="fal fa-plus"></i>
                            </button>
                        </div>

                        <div class="mb-4 relative">
                            <label for="" class="label-form mb-3">
                                Category
                            </label>
                            <select name="" id="" v-model="selectedCategory" class="input-ui">
                                <option :value="category" v-for="(category, categoryIndex) in itemCategoryList"
                                    :key="categoryIndex"> {{ category.name }} </option>
                            </select>
                            <button type="button" class="text-xs absolute -right-6 top-1/2 pt-2" @click="[step = 'category', brandName = null]">
                                <i class="fal fa-plus"></i>
                            </button>
                        </div>
                        <!-- <div class="mb-4">
                            <label class="label-form mb-3">Brands</label>
                            <multiselect
                            v-model="selectedBrands"
                            :options="brandsList"
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
                        </div> -->
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Base UOM (အကြီး)
                            </label>
                            <select name="" id="" v-model="selectedBaseUom" class="input-ui">
                                <option :value="uom" v-for="(uom, uomIndex) in uomList" :key="uomIndex"> {{ uom.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Uom အသေး (Inventory သိမ်းဆည်း unit)
                            </label>
                            <select name="" id="" v-model="selectedUOM" class="input-ui">
                                <option :value="uom" v-for="(uom, uomIndex) in uomList" :key="uomIndex"> {{ uom.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Conversion
                            </label>
                            <input type="number" step="0.01" placeholder="Conversion" v-model="conversion" class="input-ui">
                        </div>
                        <!-- <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Lead Time
                            </label>
                            <input type="text" placeholder="Lead Time" v-model="lead_time" class="input-ui">
                        </div> -->
                        <div class="mb-4 relative">
                            <label for="" class="label-form mb-3">
                                Brand
                            </label>
                            <!-- <select name="" id="" v-model="selectedBrand" class="input-ui">
                                <option :value="brand" v-for="(brand, brandIndex) in brandList" :key="brandIndex"> {{ brand.name }}
                                </option>
                            </select> -->
                            <multiselect
                                v-model="selectedBrand"
                                :options="brandList"
                                :multiple="true"
                                :close-on-select="false"
                                :clear-on-select="false"
                                :preserve-search="true"
                                :custom-label="selectedBrand.name"
                                placeholder="Select Brand"
                                label="name"
                                track-by="id"
                                :preselect-first="false">
                                <template #selection="{ values, search, isOpen }">
                                    <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                                    Brand selected</span>
                                </template>
                            </multiselect>
                            <button type="button" class="text-xs absolute -right-6 top-1/2 pt-2" @click="[step = 2, brandName = null]">
                                <i class="fal fa-plus"></i>
                            </button>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Minimum Holding Amount ( Base UOM - အကြီး )
                            </label>
                            <input type="number" placeholder="Minimum Holding Amount" min="0" v-model="base_min_amount" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Minimum Holding Amount ( UOM - အသေး )
                            </label>
                            <input type="number" placeholder="Minimum Holding Amount" min="0" v-model="min_amount" class="input-ui">
                        </div>

                        <div class="mb-4 relative">
                            <label for="" class="label-form mb-3">
                                PO Limit Type
                            </label>
                            <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                                data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Type" data-te-select-filter="true"
                                    name="" id="" v-model="selectedLimitType" class="input-ui !text-black" @change="limitTypeChange()">
                                    <option :value="type.value" v-for="(type, index) in limitTypeList" class="!uppercase"
                                        :key="index"> {{ type.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div v-show="selectedLimitType === 'uom'">
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Maximum Limit ( Base UOM - အကြီး )
                                </label>
                                <input type="number" placeholder="Max Base Uom" min="0" v-model="maxBaseUomLimit" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Maximum Limit ( UOM - အသေး )
                                </label>
                                <input type="number" placeholder="Max Uom" min="0" v-model="maxUomLimit" class="input-ui">
                            </div>
                        </div>

                        <div class="mb-4" v-show="selectedLimitType === 'finance'">
                            <label for="" class="label-form mb-3">
                                Amount
                            </label>
                            <input type="number" placeholder="Amount " min="0" v-model="limit_amount" class="input-ui">
                        </div>
                    </div>
                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 " @click="createBtnClicked">
                            Create
                        </button>
                    </div>
                </div>    
                <div v-show="step == '2'">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <button @click="[step = 1, brandName = null]">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter">
                            Create Brand
                        </h5>
                        <div>

                        </div>
                    </div>
                    <div class="relative px-12 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" placeholder="Name" v-model="brandName" class="input-ui">
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close" @click="stpe = 1">
                            Cancel
                        </button>
                        <button type="button" @click="createBrand()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
                <div v-show="step == 'type'">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <button @click="[step = 1, itemTypeName = null]">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter">
                            Create Item Type
                        </h5><div></div>
                    </div>
                    <div class="relative px-12 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" placeholder="Name" v-model="itemTypeName" class="input-ui">
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close" @click="stpe = 1">
                            Cancel
                        </button>
                        <button type="button" @click="createType()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
                <div v-show="step == 'category'">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <button @click="[step = 1, itemCategoryName = null]">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter">
                            Create Item Category
                        </h5><div></div>
                    </div>
                    <div class="relative px-12 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" placeholder="Name" v-model="itemCategoryName" class="input-ui">
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close" @click="stpe = 1">
                            Cancel
                        </button>
                        <button type="button" @click="createCategory()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- import Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="import_modal" tabindex="-1" aria-labelledby="import_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <!--Modal title-->
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="import_modalLabel">
                        Import Excel
                    </h5>
                    <!--Close button-->
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close" id="close_import_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Excel
                        </label>
                        <input type="file" placeholder="Excel" class="input-ui"  @change="handleFileChange">
                    </div>
                </div>
                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 " @click="importBtnClicked()">
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="priceUpdateModal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Update Item Price
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

                    <div class="mb-4" v-if="updatePriceItem">
                        <label for="" class="label-form mb-3">
                            Item Name
                        </label>
                        <input type="text" placeholder="Item Name" :value="updatePriceItem.name" disabled
                            class="input-ui">
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                        @click="confirmUpdatePriceBtnClicked" data-te-modal-dismiss>
                        Update Price
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { ref } from 'vue';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            itemCategoryList: [],
            itemList: [],
            uomList: [],
            itemTypeList:[],
            brandList: [],
            name: '',
            price: null,
            selectedUOM: null,
            // lead_time:null,
            selectedBrand:[],
            base_min_amount:0,
            min_amount:0,

            brandName:null,
            itemTypeName:null,
            itemCategoryName:null,
            step: 1,

            selectedCategory: null,
            searchInput: null,
            searchCategory: null,

            updatePriceItem: null,
            updatedPrice: null,
            code:null,
            itemType:null,


            isFirstGroup: true,
            isLastGroup: false,
            selectedBaseUom:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0,

            // brandsList: [],
            // selectedBrands: [],
            selectedFile:null,
            limitTypeList:[
                {'name': 'UOM', 'value': 'uom'},
                {'name': 'Finanace', 'value': 'finance'}
            ],

            selectedLimitType: null,
            maxBaseUomLimit: null,
            maxUomLimit: null,
            limit_amount: null,
            conversion: null,

            feature: this.getFeature(),
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        updatePriceBtnClicked(itemId){
            let index = this.itemList.findIndex(item => item.id == itemId);
            if(index != -1){
                this.updatePriceItem = this.itemList[index];
                this.updatedPrice = this.updatePriceItem.item_prices.price;
            }
        },

        async confirmUpdatePriceBtnClicked(){
            let formData = new FormData();
            formData.append('price', this.updatedPrice);
            let url = `/api/item_prices/${this.updatePriceItem.id}`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.data){
                this.getItemList(1);
            }
        },

        async getItemCategoryList() {
            let url = `/api/categories`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.itemCategoryList = response.data;
            }
        },


        async getItemTypeList()
        {
            let url = `/api/get_item_type`;
            let response = await getApiData({url:url, token: this.getToken()});
            if(response.data){
                this.itemTypeList = response.data;
            }
        },
        async getbrandList()
        {
            let url = `/api/brands`;
            let response = await getApiData({url:url, token: this.getToken()});
            if(response.data){
                this.brandList = response.data;
            }
        },


        async getUomList() {
            let url = `/api/uoms`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.uomList = response.data;
            }
        },
        async getItemList(pageNumber) {
            let url = `/api/items?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.itemList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },


        
        async createBrand(){
            if(!this.brandName){
                this.alertValiationMessage('Brand Name');
                return false;
            }
            let url = `/api/brands`;
            let formData = new FormData();
            formData.append('name', this.brandName);
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.brandName = null;
                await this.getbrandList();
                this.step = 1;
                this.selectedBrand.push(this.brandList.find(brand => brand.id == response.data.id))
            }
        },
        async createType(){
            if(!this.itemTypeName){
                this.alertValiationMessage('Brand Name');
                return false;
            }
            let url = `/api/item_types`;
            let formData = new FormData();
            formData.append('name', this.itemTypeName);
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.itemTypeName = null;
                await this.getItemTypeList();
                this.step = 1;
                this.itemType = this.itemTypeList.find(item => item.id == response.data.id)
            }
        },
        async createCategory(){
            if(!this.itemCategoryName){
                this.alertValiationMessage('Brand Name');
                return false;
            }
            let url = `/api/categories`;
            let formData = new FormData();
            formData.append('name', this.itemCategoryName);
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.itemCategoryName = null;
                await this.getItemCategoryList();
                this.step = 1;
                this.selectedCategory = this.itemCategoryList.find(category => category.id == response.data.id)
            }
        },
        async createBtnClicked() {
            if (!this.selectedUOM || !this.name || !this.selectedCategory ||!this.selectedBaseUom || !this.code || !this.itemType || !this.selectedBrand ) {
                this.alertValiationMessage('required data');
                return false;
            }
            if(this.base_min_amount < 1 && this.min_amount < 1){
                this.alertValiationMessage('Amount');
                return false;
            }
            let url = `/api/items`;
            let formData = new FormData();
            formData.append('uom_id', this.selectedUOM.id);
            formData.append('name', this.name);
            formData.append('code',this.code);
            formData.append('category_id', this.selectedCategory.id);
            formData.append('base_uom_id',this.selectedBaseUom.id);
            formData.append('item_type_id',this.itemType.id);
            // formData.append('brand_id',this.selectedBrand.id);
            formData.append('min_holding_base_uom_quantity',this.base_min_amount);
            formData.append('min_holding_uom_quantity',this.min_amount);
            formData.append('conversion',this.conversion);
            formData.append('limitation_type',this.selectedLimitType);
            if(this.selectedLimitType === 'uom'){
                formData.append('max_limit_base_uom_quantity',this.maxBaseUomLimit);
                formData.append('max_limit_uom_quantity',this.maxUomLimit);
            }
            else{
                formData.append('amount',this.limit_amount);
            }
            if(this.selectedBrand.length > 0){
                this.selectedBrand.forEach((brand)=>{
                    formData.append('brand_id[]', brand.id);
                });
            }
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                // this.getItemList(this.currentPage);
                // this.selectedBrands = [];
                window.location.reload();
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },

        isActiveToggled(id) {
            let index = this.itemList.findIndex(table => table.id == id);
            if (index != -1) {
                if (this.itemList[index].is_active == 1) {
                    this.itemList[index].is_active = 0;
                }
                else {
                    this.itemList[index].is_active = 1;
                }

                let url = `/api/is_active`;
                let formData = new FormData();
                formData.append('id', id);
                formData.append('type', 'item');
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
            }
        },

        async searchBtnClicked() {
            let url = null;
            if (this.searchInput && this.searchCategory) {
                url = `/api/items?search_input=${this.searchInput}&category_id=${this.searchCategory.id}&page=1`;
            }
            if (this.searchInput && !this.searchCategory) {
                url = `/api/items?search_input=${this.searchInput}&page=1`;
            }
            if ((!this.searchInput) && this.searchCategory) {
                url = `/api/items?category_id=${this.searchCategory.id}&page=1`;
            }
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.itemList = response.data.data;
            }
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.searchCategory = null;
            this.getItemList(1);
        },
        handleFileChange(event) {
            console.log("Event object:", event);
            const selectedFile = event.target.files[0];
            this.selectedFile = selectedFile;
            if(this.selectedFile){
                this.importBtnClicked();
            }
        },
        async importBtnClicked() {
            let formData = new FormData();
            formData.append('item_import', this.selectedFile);
            let response = await postApiData({ url: '/api/import/items', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Excel Imported successfully`,
                    type: "info"
                });
                this.selectedFile = null;
                this.getItemList(1);
            }
            else {
                this.$notify({
                    text: `Excel Imported failed`,
                    type: "error"
                });
            }
        },


        handleItemTypeFileChange(event) {
            console.log("Event object:", event);
            const selectedItemTypeFile = event.target.files[0];
            this.selectedItemTypeFile = selectedItemTypeFile;
            if(this.selectedItemTypeFile){
                this.importItemType();
            }
        },
        async importItemType() {
            let formData = new FormData();
            formData.append('item_type_import', this.selectedItemTypeFile);
            let response = await postApiData({ url: '/api/import/item_types', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Excel Imported successfully`,
                    type: "info"
                });
                this.selectedItemTypeFile = null;
                this.getItemTypeList();
            }
            else {
                this.$notify({
                    text: `Excel Imported failed`,
                    type: "error"
                });
            }
        },



        handleItemCategoryFileChange(event) {
            console.log("Event object:", event);
            const selectedItemCategoryFile = event.target.files[0];
            this.selectedItemCategoryFile = selectedItemCategoryFile;
            if(this.selectedItemCategoryFile){
                this.importItemCategory();
            }
        },
        async importItemCategory() {
            let formData = new FormData();
            formData.append('category_import', this.selectedItemCategoryFile);
            let response = await postApiData({ url: '/api/import/categories', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Excel Imported successfully`,
                    type: "info"
                });
                this.selectedItemCategoryFile = null;
                this.getItemCategoryList();
            }
            else {
                this.$notify({
                    text: `Excel Imported failed`,
                    type: "error"
                });
            }
        },


    },

    created() {
        this.getItemCategoryList();
        this.getUomList();
        // this.getBrandList();
        this.getItemList(1);
        this.getItemTypeList();
        this.getbrandList();
    },

    mounted() {
        initTE({ Modal, Ripple, Select, Dropdown });
    }
}
</script>