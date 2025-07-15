<template>
    <div class="px-6 pt-4 pb-8 mt-3 card-shadow" v-show="!isSupplier">
        <div class="mb-0 ">
            <p class="text-lg font-semibold font-inter">
                Create Item
            </p>
        </div>

        <div class="grid !grid-cols-12 gap-x-8 gap-y-4 bg-white py-6 mb-0">
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Item Name
                </label>
                <input type="text" placeholder="Item Name" v-model="name" class="input-ui">
            </div>
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Item Code
                </label>
                <input type="text" placeholder="Code" v-model="selectedCode" class="input-ui">
            </div>
            <div class="col-span-6"></div>
            <div class="mb-6 relative col-span-3">
                <label for="" class="label-form mb-3">
                    Category
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select name="" id="" v-model="selectedCategory" class="input-ui"
                            data-te-select-init data-te-select-placeholder="Select Category" data-te-select-filter="true">
                            <option :value="category" v-for="(category, categoryIndex) in itemCategoryList"
                                :key="categoryIndex"> {{ category.name }} </option>
                        </select>
                    </div>
                    <button  class="px-2 pt-2"  data-te-toggle="modal" data-te-target="#add_category_modal" @click="addCategoryModalClicked"><i class="fal fa-plus"></i></button>
                </div>
            </div>
            <div class="mb-6 relative col-span-3">
                <label for="" class="label-form mb-3">
                    Item Type
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select name="" id="" v-model="selectedItemType" class="input-ui"
                            data-te-select-init data-te-select-placeholder="Select Type" data-te-select-filter="true">
                            <option :value="item_type" v-for="(item_type, index) in itemTypeList" :key="index"> {{ item_type.name }}
                            </option>
                        </select>
                    </div>
                    <button  class="px-2 pt-2"  data-te-toggle="modal" data-te-target="#add_type_modal" @click="addTypeModalClicked"><i class="fal fa-plus"></i></button>
                </div>
            </div>
            <div class="mb-6 relative col-span-3">
                <label for="" class="label-form mb-3">
                    Tag
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select name="" id="" v-model="selectedTag" class="input-ui"
                            data-te-select-init data-te-select-placeholder="Select Tag" data-te-select-filter="true">
                            <option :value="tag" v-for="(tag, index) in tagList" :key="index"> {{ tag.name }}
                            </option>
                        </select>
                    </div>
                    <button  class="px-2 pt-2"  data-te-toggle="modal" data-te-target="#add_tag_modal" @click="addTagModalClicked"><i class="fal fa-plus"></i></button>
                </div>
            </div>
            <div class="col-span-3"></div>

            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Base UOM (အကြီး)
                </label>
                <select name="" id="" v-model="selectedBaseUom" class="input-ui" @change="updateSelectedUoms"
                    data-te-select-init data-te-select-placeholder="Select Base UOM" data-te-select-filter="true">
                    <option :value="uom" v-for="(uom, uomIndex) in uomList" :key="uomIndex"> {{ uom.name }}
                    </option>
                </select>
            </div>
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Uom အသေး (Inventory သိမ်းဆည်း unit)
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select name="" id="" v-model="selectedUOM" class="input-ui" @change="updateSelectedUoms"
                            data-te-select-init data-te-select-placeholder="Select UOM" data-te-select-filter="true">
                            <option :value="uom" v-for="(uom, uomIndex) in uomList" :key="uomIndex"> {{ uom.name }}
                            </option>
                            
                        </select>
                    </div>
                    <button  class="px-2 pt-2"  data-te-toggle="modal" data-te-target="#add_uom_modal" @click="addUomModalClicked"><i class="fal fa-plus"></i></button>
                </div>
            </div>
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Conversion
                </label>
                <input type="number" step="0.01" placeholder="Conversion" v-model="conversion" class="input-ui">
            </div><div class="col-span-12"></div>

            <div class="mb-6 relative col-span-3">
                <label for="" class="label-form mb-3">
                    PO Limit Type
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" data-te-select-filter="true"
                        name="" id="" v-model="selectedLimitType" class="input-ui !text-black">
                        <option :value="type.value" v-for="(type, index) in limitTypeList" class="!uppercase"
                            :key="index"> {{ type.name }} </option>
                    </select>
                </div>
            </div>
            <div v-show="selectedLimitType === 'uom'" class="contents">
                <!-- <div class="mb-6 col-span-3">
                    <label for="" class="label-form mb-3">
                        Maximum Limit ( Base UOM - အကြီး )
                    </label>
                    <input type="number" placeholder="Max Base Uom" min="0" v-model="maxBaseUomLimit" class="input-ui">
                </div>
                <div class="mb-6 col-span-3">
                    <label for="" class="label-form mb-3">
                        Maximum Limit ( UOM - အသေး )
                    </label>
                    <input type="number" placeholder="Max Uom" min="0" v-model="maxUomLimit" class="input-ui">
                </div> -->
                <div class="mb-6 col-span-3">
                    <label for="" class="label-form mb-3">
                        Maximum Limit
                    </label>
                    <input type="number" placeholder="Maximum Limit Amount" min="0" v-model="max_limit" class="input-ui">
                </div>
                <div class="mb-6 relative col-span-3">
                    <label for="" class="label-form mb-3">
                        Maximum Limit UOM
                    </label>
                    <div class="flex gap-x-2">
                        <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                            data-te-select-wrapper-ref>
                            <select name="" id="" v-model="selectedMaxUom" class="input-ui"
                                data-te-select-init data-te-select-placeholder="Select UOM" data-te-select-filter="true">
                                <option :value="uom" v-for="(uom, uomIndex) in selectedUomList"
                                    :key="uomIndex"> {{ uom.uom_name }} </option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mb-6 col-span-3" v-show="selectedLimitType === 'finance'">
                <label for="" class="label-form mb-3">
                    Amount
                </label>
                <input type="number" placeholder="Amount " min="0" v-model="limit_amount" class="input-ui">
            </div>
            <div v-if="selectedLimitType" class="col-span-12"></div>




            
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
            
            <!-- <div class="mb-4">
                <label for="" class="label-form mb-3">
                    Lead Time
                </label>
                <input type="text" placeholder="Lead Time" v-model="lead_time" class="input-ui">
            </div> -->
            <!-- <div class="mb-4 relative col-span-3">
                <label for="" class="label-form mb-3">
                    Brand
                </label>
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
            </div> -->
            <!-- <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Minimum Holding Amount ( Base UOM - အကြီး )
                </label>
                <input type="number" placeholder="Minimum Holding Amount" min="0" v-model="base_min_amount" class="input-ui">
            </div>
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Minimum Holding Amount ( UOM - အသေး )
                </label>
                <input type="number" placeholder="Minimum Holding Amount" min="0" v-model="min_amount" class="input-ui">
            </div> -->
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Minimum Holding Amount 
                </label>
                <input type="number" placeholder="Minimum Holding Amount" min="0" v-model="min_amount" class="input-ui">
            </div>
            <div class="mb-6 relative col-span-3">
                <label for="" class="label-form mb-3">
                    Minimum Holding UOM
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select name="" id="" v-model="selectedMinUom" class="input-ui"
                            data-te-select-init data-te-select-placeholder="Select UOM" data-te-select-filter="true">
                            <option :value="uom" v-for="(uom, uomIndex) in selectedUomList"
                                :key="uomIndex"> {{ uom.uom_name }} </option>
                        </select>
                    </div>
                </div>
            </div>



            <div class="col-span-12"></div>
            
            <div class="mb-6 relative col-span-3">
                <label for="" class="label-form mb-3">
                    Brand
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select name="" id="" v-model="selectedBrand" class="input-ui"
                            data-te-select-init data-te-select-placeholder="Select Brand" data-te-select-filter="true">
                            <option :value="brand" v-for="(brand, brandIndex) in brandList"
                                :key="brandIndex"> {{ brand.name }} </option>
                        </select>
                    </div>
                    <button  class="px-2 pt-2"  data-te-toggle="modal" data-te-target="#add_brand_modal" @click="addBrandModalClicked"><i class="fal fa-plus"></i></button>
                </div>
            </div>
            <div class="mb-6 relative col-span-3">
                <label for="" class="label-form mb-3">
                    Supplier
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select name="" id="" v-model="selectedSupplier" class="input-ui"
                            data-te-select-init data-te-select-placeholder="Select Supplier" data-te-select-filter="true">
                            <option :value="supplier" v-for="(supplier, supplierIndex) in supplierList"
                                :key="supplierIndex"> {{ supplier.name }} </option>
                        </select>
                    </div>
                    <button  class="px-2 pt-2" @click="addSupplierClicked"><i class="fal fa-plus"></i></button>
                </div>
            </div>
            <div class="mb-6 relative col-span-3">
                <label for="" class="label-form mb-3">
                    UOM
                </label>
                <div class="flex gap-x-2">
                    <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                        data-te-select-wrapper-ref>
                        <select name="" id="" v-model="uomForBrandSupplier" class="input-ui"
                            data-te-select-init data-te-select-placeholder="Select UOM" data-te-select-filter="true">
                            <option :value="uom" v-for="(uom, uomIndex) in selectedUomList"
                                :key="uomIndex"> {{ uom.uom_name }} </option>
                        </select>
                    </div>
                    
                </div>
            </div>
            <div class="mb-6 col-span-2">
                <label for="" class="label-form mb-3">
                    Price
                </label>
                <input type="number" placeholder="Price" min="0" v-model="price" class="input-ui">
            </div>


            <div>
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn" @click="btnClickedAddPrice()">
                    Add
                </button>
            </div>

            
            
        </div>

        <div class=" bg-white py-8 px-0  mb-8" v-show="selectedItemPriceList.length > 0">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="!text-left">
                        <tr>
                            <th scope="col" class="">
                                Brand
                            </th>
                            <th scope="col" class="">
                                Supplier
                            </th>
                            <th scope="col" class="">
                                Price
                            </th>
                            <th scope="col" class="">
                                UOM
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="!text-left">
                        <tr class="" v-for="(price, priceIndex) in selectedItemPriceList"
                            :key="priceIndex">
                            <td class="">
                                {{ price.brand_name }}
                            </td>
                            <td class="">
                                {{ price.supplier_name }}
                            </td>
                            <td class="">
                                {{ price.uom_price }}
                            </td>
                            <td class="">
                                {{ price.uom_name }}
                            </td>
                            <td class="text-center">
                                <button @click="removePrice(priceIndex)">
                                    <i class="fas fa-times  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class=" !text-center" v-if="selectedItemPriceList.length < 1">
                            <td class="" colspan="3">
                                No Data Here
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>



        <div>
            <button class="add-btn" @click="btnClickedCreateItem()">
                Create Item
            </button>
        </div>







            <!-- category modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_category_modal" tabindex="-1" aria-labelledby="add_duty_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_tag_label">
                            Create Category
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_category_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="grid grid-cols-11 gap-x-4">
                            
                            <div class="mb-4 col-span-11">
                                <label for="" class="block text-sm text-black mb-3">
                                    Name
                                </label>
                                <input type="text" v-model="categoryName"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4 col-span-11">
                                <label for="" class="block text-sm text-black mb-3">
                                    Code
                                </label>
                                <input type="text" v-model="categoryCode"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                        </div>
                        
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                        </button>
                        <button type="button" @click="btnClickedCreateCategory()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
             <!-- type modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_type_modal" tabindex="-1" aria-labelledby="add_duty_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_tag_label">
                            Create Type
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_type_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="grid grid-cols-11 gap-x-4">
                            
                            <div class="mb-4 col-span-11">
                                <label for="" class="block text-sm text-black mb-3">
                                    Name
                                </label>
                                <input type="text" v-model="typeName"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                        </div>
                        
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                        </button>
                        <button type="button" @click="btnClickedCreateType()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>



            <!-- tag Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_tag_modal" tabindex="-1" aria-labelledby="add_duty_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_tag_label">
                            Create Tag
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_tag_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="grid grid-cols-11 gap-x-4">
                            
                            <div class="mb-4 col-span-11">
                                <label for="" class="block text-sm text-black mb-3">
                                    Name
                                </label>
                                <input type="text" v-model="tagName"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                        </div>
                        
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                        </button>
                        <button type="button" @click="btnClickedCreateTag()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>



            <!-- brand modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_brand_modal" tabindex="-1" aria-labelledby="add_duty_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_tag_label">
                            Create Brand
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_brand_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="grid grid-cols-11 gap-x-4">
                            
                            <div class="mb-4 col-span-11">
                                <label for="" class="block text-sm text-black mb-3">
                                    Name
                                </label>
                                <input type="text" v-model="brandName"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                        </div>
                        
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                        </button>
                        <button type="button" @click="btnClickedCreateBrand()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
            <!-- uom modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="add_uom_modal"
            tabindex="-1" aria-labelledby="uomLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create UOM
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss id="close_create_uom"
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
                                Uom Name
                            </label>
                            <input type="text" placeholder="Uom" v-model="create_uom_name" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Uom Code
                            </label>
                            <input type="text" placeholder="eg.uom-11" pattern="^uom-\d+$" required v-model="create_uom_code" class="input-ui">
                        </div>

                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 " @click="createUomBtnClicked">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div v-show="isSupplier">
        <supplier-create-component route-location="/items/create" @finish="handleSupplier" @cancel="isSupplier = false" />
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Tab, Select } from "tw-elements";
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { each } from "lodash";
import SupplierCreateComponent from '../Supplier/SupplierCreateComponent.vue'


export default {
    components: {
        Multiselect,
        SupplierCreateComponent
    },
    data() {
        return {
            itemCategoryList: [],
            itemTypeList: [],
            tagList: [],
            limitTypeList: [
                {'name': 'UOM', 'value': 'uom'},
                {'name': 'Finanace', 'value': 'finance'}
            ],
            uomList: [],

            name: null,
            selectedCategory: null,
            selectedItemType: null,
            selectedTag: null,
            selectedCode: null,
            selectedLimitType: null,

            // maxBaseUomLimit: null,
            // maxUomLimit: null,
            max_limit: null,
            selectedMaxUom: null,

            limit_amount: null,
            selectedBaseUom: null,
            selectedUOM: null,
            conversion: null,

            // base_min_amount: null,
            min_amount: null,
            selectedMinUom: null,

            tagName: null,
            categoryName: null,
            categoryCode: null,
            typeName: null,

            brandList: [],
            supplierList: [],
            selectedBrand: null,
            selectedSupplier: null,
            uomForBrandSupplier: null,
            selectedUomList: [],

            brandName:null,
            price: null,
            selectedItemPriceList: [],

            isSupplier: false,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getItemCategoryList() {
            let url = `/api/categories`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.itemCategoryList = response.data;
            }
        },
        async getTagList() {
            let url = `/api/tags`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.tagList = response.data;
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
        async getUomList() {
            let url = `/api/uoms`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.uomList = response.data;
            }
        },
        async getBrandList()
        {
            let url = `/api/brands`;
            let response = await getApiData({url:url, token: this.getToken()});
            if(response.data){
                this.brandList = response.data;
            }
        },
        async getSupplierList()
        {
            let url = `/api/suppliers`;
            let response = await getApiData({url:url, token: this.getToken()});
            if(response.data){
                this.supplierList = response.data;
            }
        },
        addCategoryModalClicked(){
            this.categoryName = null;
            this.categoryCode = null;
        },
        btnClickedCreateCategory(){
            if(!this.categoryName){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else if(!this.categoryCode){
                this.alertValidationMessage(`Code`);
                return 1;
            }
            else{
                this.createCategory();
            }
        },
        async createCategory(){
            let formData = new FormData();
            formData.append('name', this.categoryName);   
            formData.append('category_code', this.categoryCode);           
            let response = await postApiData({url:`/api/categories`, form_data:formData, token:this.getToken()})
            if(response.success){
                document.getElementById('close_category_modal').click();
                this.getItemCategoryList();
                // this.selectedCategory = this.itemCategoryList.find(cat => cat.name === this.categoryName)
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        addTypeModalClicked(){
            this.typeName = null;
        },
        btnClickedCreateType(){
            if(!this.typeName){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else{
                this.createType();
            }
        },
        async createType(){
            let formData = new FormData();
            formData.append('name', this.typeName);            
            let response = await postApiData({url:`/api/item_types`, form_data:formData, token:this.getToken()})
            if(response.success){
                document.getElementById('close_type_modal').click();
                this.getItemTypeList();
                // this.selectedItemType = this.itemTypeList.find(type => type.name === this.typeName);
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        addTagModalClicked(){
            this.tagName = null;
        },
        btnClickedCreateTag(){
            if(!this.tagName){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            else{
                this.createTag();
            }
        },
        async createTag(){
            let formData = new FormData();
            formData.append('name', this.tagName);
            formData.append('floors', JSON.stringify(this.selectedFloorList));
            
            let response = await postApiData({url:`/api/tags`, form_data:formData, token:this.getToken()})
            if(response.success){
                document.getElementById('close_tag_modal').click();
                this.getTagList();
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },

        addBrandModalClicked(){
            this.brandName = null;
        },
        btnClickedCreateBrand(){
            if(!this.brandName){
                this.alertValidationMessage(`Brand Name`);
                return 1;
            }
            else{
                this.createBrandName();
            }
        },
        async createBrandName(){
            let formData = new FormData();
            formData.append('name', this.brandName);
            let response = await postApiData({url:`/api/brands`, form_data:formData, token:this.getToken()})
            if(response.success){
                document.getElementById('close_brand_modal').click();
                this.getBrandList();
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        addUomModalClicked(){
            this.create_uom_name = null;
            this.create_uom_code = null;
        },
        createUomBtnClicked(){
            const pattern = /^uom-\d*$/;
            if(!this.create_uom_name){
                this.alertValidationMessage(`UOM Name`);
                return 1;
            }
            else if(!this.create_uom_code){
                this.alertValidationMessage(`UOM Code`);
                return 1;
            }
            else if (!pattern.test(this.create_uom_code)) {
                this.$notify({
                    title: `Input validation`,
                    text: `Format must be like uom-11`,
                    type: "warn"
                });
                return 1;
            }
            else{
                this.createUom();
            }
        },
        async createUom(){
            let url = `/api/uoms`
            let formData = new FormData();
            formData.append('name', this.create_uom_name);
            formData.append('uom_code', this.create_uom_code);

            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success == true) {
                document.getElementById('close_create_uom').click();
                this.getUomList();
            }
            else{
                this.$notify({
                    title: 'Error',
                    text: response.message,
                    type: 'error'
                });

                return 1;
            }
        },
        
        addSupplierClicked(){
            this.isSupplier = true;
        },
        btnClickedAddPrice(){
            if(!this.selectedBrand){
                this.alertValidationMessage(`Brand`);
                return 1;
            }if(!this.selectedSupplier){
                this.alertValidationMessage(`Supplier`);
                return 1;
            }if(!this.uomForBrandSupplier){
                this.alertValidationMessage(`Uom`);
                return 1;
            }if(!this.price){
                this.alertValidationMessage(`Price`);
                return 1;
            }
            let isBrand = this.selectedItemPriceList.some(item => item.brand_id === this.selectedBrand.id);
            let isSupplier = this.selectedItemPriceList.some(item => item.supplier_id === this.selectedSupplier.id);
            console.log('isbrand = ' + isBrand + ' issupplier = ' + isSupplier)
            if(isBrand && isSupplier){
                this.$notify({
                    title: `Input validation`,
                    text: `You Can't add Same Brand and Supplier again`,
                    type: "warn"
                });
                return 1;
            }
            this.addPrice();
        },
        addPrice(){
            this.selectedItemPriceList.push({
                brand_id: this.selectedBrand.id,
                brand_name: this.selectedBrand.name,
                supplier_id: this.selectedSupplier.id,
                supplier_name: this.selectedSupplier.name,
                uom_price: this.price,
                uom_id: this.uomForBrandSupplier.uom_id,
                uom_name: this.uomForBrandSupplier.uom_name,
                uom_type: this.uomForBrandSupplier.base_uom,
            })
            this.selectedBrand = null;
            this.selectedSupplier = null;
            this.uomForBrandSupplier = null;
            this.price = null;

        },
        removePrice(index){
            this.selectedItemPriceList.splice(index, 1);
        },



        updateSelectedUoms() {
            this.selectedUomList = []; // clear first
            const base = this.selectedBaseUom;
            const uom = this.selectedUOM;
            // Skip if nothing is selected
            if (!base && !uom) return;
            // If both selected and different -> push both
            if (base && uom && base.id !== uom.id) {
                this.selectedUomList.push({
                    uom_id: base.id,
                    uom_name: base.name,
                    uom_type: 'base_uom'
                });
                this.selectedUomList.push({
                    uom_id: uom.id,
                    uom_name: uom.name,
                    uom_type: 'uom'
                });
            }
            // If only one selected or both same → push only one
            else if (base) {
                this.selectedUomList.push({
                    uom_id: base.id,
                    uom_name: base.name,
                    uom_type: 'base_uom'
                });
            } 
            else if (uom) {
                this.selectedUomList.push({
                    uom_id: uom.id,
                    uom_name: uom.name,
                    uom_type: 'uom'
                });
            }
        },
        // baseUomChange(){
        //     const alreadyExists = this.selectedUomList.some(uom => uom.uom_id === this.selectedBaseUom.id);
        //     const isBaseUom = this.selectedUomList.some(uom => uom.uom_type === 'base_uom');
        //     if(isBaseUom){
        //         console.log('hello')
        //         let index = this.selectedUomList.findIndex(uom => uom.uom_type === 'base_uom');
        //         if (index != -1) {
        //             this.selectedUomList.splice(index, 1);
        //         }
        //     }
        //     this.selectedUomList.push({
        //         uom_name: this.selectedBaseUom.name,
        //         uom_id: this.selectedBaseUom.id,
        //         uom_type: 'base_uom'
        //     })
            
        //     if(alreadyExists){
        //         console.log('exit')
        //         let index = this.selectedUomList.findIndex(uom => uom.uom_type === 'base_uom');
        //         if (index != -1) {
        //             this.selectedUomList.splice(index, 1);
        //         }
        //     }
            
        // },
        // uomChange(){
        //     const alreadyExists = this.selectedUomList.some(uom => uom.uom_id === this.selectedUOM.id);
        //     const isUom = this.selectedUomList.some(uom => uom.uom_type === 'uom');
        //     if(isUom){
        //         console.log('hello')
        //         let index = this.selectedUomList.findIndex(uom => uom.uom_type === 'uom');
        //         if (index != -1) {
        //             this.selectedUomList.splice(index, 1);
        //         }
        //     }
        //     this.selectedUomList.push({
        //         uom_name: this.selectedUOM.name,
        //         uom_id: this.selectedUOM.id,
        //         uom_type: 'uom'
        //     })
            
        //     if(alreadyExists){
        //         console.log('exit')
        //         let index = this.selectedUomList.findIndex(uom => uom.uom_type === 'uom');
        //         if (index != -1) {
        //             this.selectedUomList.splice(index, 1);
        //         }
        //     }
        // },  

        btnClickedCreateItem() {
            if(!this.name){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            if(!this.selectedCategory){
                this.alertValidationMessage(`Category`);
                return 1;
            }
            if(!this.selectedItemType){
                this.alertValidationMessage(`Item Type`);
                return 1;
            }
            if(!this.selectedTag){
                this.alertValidationMessage(`Tag`);
                return 1;
            }
            if(!this.selectedCode){
                this.alertValidationMessage(`Code`);
                return 1;
            }
            if(!this.selectedLimitType){
                this.alertValidationMessage(`PO Limit Type`);
                return 1;
            }
            
            if(this.selectedLimitType && this.selectedLimitType === 'uom'){
                // if(!this.maxBaseUomLimit){
                //     this.alertValidationMessage(`Maximum Base Uom Limit`);
                //     return 1;
                // }
                // if(!this.maxUomLimit){
                //     this.alertValidationMessage(`Maximum Uom Limit`);
                //     return 1;
                // }
                if(!this.max_limit){
                    this.alertValidationMessage(`Maximum Limit`);
                    return 1;
                }
                if(!this.selectedMaxUom){
                    this.alertValidationMessage(`Maximum Limit UOM`);
                    return 1;
                }
            }
            if(this.selectedLimitType && this.selectedLimitType === 'finance'){
                if(!this.limit_amount){
                    this.alertValidationMessage(`Limit Amount`);
                    return 1;
                }
            }

            if(!this.selectedBaseUom){
                this.alertValidationMessage(`Base Uom`);
                return 1;
            }
            if(!this.selectedUOM){
                this.alertValidationMessage(`Uom`);
                return 1;
            }
            if(!this.conversion){
                this.alertValidationMessage(`Conversion`);
                return 1;
            }
            
            // if(this.base_min_amount < 1 && this.min_amount < 1){
            //     this.alertValidationMessage('Amount');
            //     return 1;
            // }
            if(!this.min_amount){
                this.alertValidationMessage('Minimum Holding Amount');
                return 1;
            }
            if(!this.selectedMinUom){
                this.alertValidationMessage('Minimum Holding UOM');
                return 1;
            }
            if(this.selectedItemPriceList.length < 1){
                this.alertValidationMessage(`Price List`);
                return 1;
            }
            this.createItem();
        },
        async createItem() {
            // if (!this.selectedUOM || !this.name || !this.selectedCategory ||!this.selectedBaseUom || !this.code || !this.selectedItemType || !this.selectedBrand ) {
            //     this.alertValidationMessage('required data');
            //     return false;
            // }
            // if (this.isValid(this.name, 'Name')) return;
            // if (this.isValid(this.selectedCategory, 'Category')) return;
            // if (this.isValid(this.selectedItemType,'Item Type')) return;

            // if (this.isValid(this.selectedTag, 'Tag')) return;
            // if (this.isValid(this.selectedCode, 'Code')) return;
            // if (this.isValid(this.selectedLimitType, 'PO Limit Type')) return;
            // if(this.selectedLimitType && this.selectedLimitType === 'uom'){
            //     if (this.isValid(this.maxBaseUomLimit, 'Maximum Base Uom Limit')) return;
            //     if (this.isValid(this.maxUomLimit, 'Maximum Base Uom Limit')) return;
            // }
            // if(this.selectedLimitType && this.selectedLimitType === 'finance'){
            //     if (this.isValid(this.limit_amount, 'Limit Amount')) return;
            // }

            // if (this.isValid(this.selectedBaseUom, 'Base Uom')) return;
            // if (this.isValid(this.selectedUOM, 'Uom')) return;
            // if (this.isValid(this.conversion, 'Conversion')) return;
            // if(this.base_min_amount < 1 && this.min_amount < 1){
            //     this.alertValidationMessage('Amount');
            //     return 1;
            // }
            // if (this.isLengthValid(this.selectedItemPriceList,'Price List')) return;


            let url = `/api/items`;
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('code',this.selectedCode);
            formData.append('category_id', this.selectedCategory.id);
            formData.append('base_uom_id',this.selectedBaseUom.id);
            formData.append('uom_id', this.selectedUOM.id);
            formData.append('item_type_id',this.selectedItemType.id);
            formData.append('tag_id',this.selectedTag.id);

            // formData.append('min_holding_base_uom_quantity',this.base_min_amount);
            // formData.append('min_holding_uom_quantity',this.min_amount);
            
            formData.append('min_uom_id',this.selectedMinUom.id);
            formData.append('min_holding_quantity',this.min_amount);


            formData.append('conversion',this.conversion);
            formData.append('limitation_type',this.selectedLimitType);
            if(this.selectedLimitType === 'uom'){
                // formData.append('max_limit_base_uom_quantity',this.maxBaseUomLimit);
                // formData.append('max_limit_uom_quantity',this.maxUomLimit);
                formData.append('max_uom_id',this.selectedMaxUom.id);
                formData.append('max_limit_quantity',this.max_limit);
            }
            else{
                formData.append('amount',this.limit_amount);
            }
            formData.append('brand_suppliers',JSON.stringify(this.selectedItemPriceList));
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace("/items");
            }
            else {
                this.$notify({
                    title: `Error Message`,
                    text: response.message.code,
                    type: "warn"
                });
            }
        },





        handleSupplier(){
            this.isSupplier = false;
            this.getSupplierList();
        },
        isLengthValid(selectedClass,selectedName){
            if(selectedClass.length < 1){
                this.$notify({
                    title: `Input validation`,
                    text: `You forgot to provide ${selectedName}, please try again`,
                    type: "warn"
                });
                return 1;
            }
        },
        isValid(selectedClass,selectedName){
            if(!selectedClass){
                this.$notify({
                    title: `Input validation`,
                    text: `You forgot to provide ${selectedName}, please try again`,
                    type: "warn"
                });
                return 1;
            }
        },
        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
    },

    watch: {
        
    },

    async created() {

    },

    mounted() {
        this.getItemCategoryList();
        this.getItemTypeList();
        this.getTagList();
        this.getUomList();
        this.getBrandList();
        this.getSupplierList();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
