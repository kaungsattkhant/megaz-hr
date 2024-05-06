<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
        </div>
        <div class="flex justify-end flex-col">
            <button type="button" class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                data-te-toggle="modal" data-te-target="#create_modal">
                Add New
            </button>
        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Date
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Fixed Asset Id
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Name
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Description
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Total Price
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Depreciation Amount
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Status
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Manager Checked
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                MD Check
                            </th>

                            <th scope="col" class="px-6 py-4 col-span-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(fixedAsset, fixedAssetIndex) in fixedAssetPurchases" :key="fixedAssetIndex">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++fixedAssetIndex }}
                                </td>

                                <td class=" px-6 py-4 font-medium ">
                                    {{ fixedAsset.date }}
                                </td>

                                <td class=" px-6 py-4 font-medium ">
                                    {{ fixedAsset.fixed_asset_id }}
                                </td>

                                <td class=" px-6 py-4 font-medium ">
                                    {{ fixedAsset.name }}
                                </td>

                                <td class=" px-6 py-4 font-medium ">
                                    {{ fixedAsset.description }}
                                </td>

                                <td class=" px-6 py-4 font-medium ">
                                    {{ (fixedAsset.total_price).toLocaleString() }}
                                </td>

                                <td class=" px-6 py-4 font-medium ">
                                    {{ (fixedAsset.depreciation_amount).toLocaleString() }}
                                </td>

                                <td class=" px-6 py-4 font-medium ">
                                    {{ fixedAsset.status }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ fixedAsset.manager_check_id == null ? 'No' : 'Yes' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ fixedAsset.is_md_checked == 0 ? 'No' : 'Yes' }}
                                </td>

                                <td class=" px-6 py-4 font-medium ">
                                    <button data-te-toggle="modal" data-te-target="#checkModal" @click="checkBtnClicked(fixedAsset.id)">
                                        <i class="fal fa-check  pr-3"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>
                    </tbody>
                </table>
            </div>

            <div class="mt-2 ml-2">
                <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                    <!-- <li>
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="firstPaginationGroupBtnClicked">
                            First
                        </button>
                    </li> -->
                    <li v-if="!isFirstGroup">
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="previousPaginationGroupBtnClicked"
                        :disabled="isFirstGroup">
                            Previous
                        </button>
                    </li>

                    <!-- loop link 1 , 2 ,3  ... replace normal-pagination with active-pagination for active pagination page-->
                    <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
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
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="nextPaginationGroupBtnClicked"
                        :disabled="isLastGroup">
                            Next
                        </button>
                    </li>
                    <!-- <li>
                        <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                        hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="lastPaginationGroupBtnClicked">
                            Last
                        </button>
                    </li> -->
                </ul>

                <ul v-else class="list-style-none flex">
                    <li v-for="(pageNumber, pageNumberIndex) in pageNumbers" :key="pageNumberIndex"
                        :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                        <button v-if="pageNumber == currentPage"
                            class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                            :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                            {{ pageNumber }}
                            <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
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

                <div class="relative  p-4">
                    <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                        Purchase Fixed Asset
                    </h5>
                    <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-12 py-4" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Fixed Asset Name
                        </label>
                        <input type="text" placeholder="Name" v-model="name"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Description
                        </label>
                        <input type="text" placeholder="Description" v-model="description"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Register Date
                        </label>
                        <input type="date" placeholder="Register date" v-model="date"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Total Price
                        </label>
                        <input type="number" placeholder="Total price" v-model="total_price"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Depreciation Amount
                        </label>
                        <input type="number" placeholder="Depreciation amount" v-model="depreciation_amount"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Depreciation Start Date
                        </label>
                        <input type="date" placeholder="Depreciation start date" v-model="start_date"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Total Duration
                        </label>
                        <input type="number" placeholder="Total duration" v-model="total_duration"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                </div>
                <div class="flex justify-center px-12 mb-6">
                    <button data-te-modal-dismiss type="button" class="add-btn focus:outline-none focus:ring-0 "
                        @click="createBtnClicked">
                        Add New
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--Check Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="checkModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
            min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Check Fixed Asset Purchase
                    </h5>
                    <!--Close button-->
                    <button type="button" class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p>
                        Are you sure ?
                    </p>
                </div>

                <!--Modal footer-->
                <div class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmCheckBtnClicked" type="button" data-te-toggle="modal" data-te-target="#checkModal"
                    class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, initTE } from "tw-elements";

import { mapGetters } from 'vuex';
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { convertToFriendlyDate } from '../../utilities/datetime-helpers';


export default {
    data() {
        return {
            fixedAssetPurchases: [],

            name: null,
            date: null,
            description: null,
            total_price: null,
            depreciation_amount: null,
            total_duration: null,
            start_date: null,

            per_page: 20,
            currentPage: 1,
            pageNumbers: [],
            paginationGroupsCount: 1,
            groupedPageNumbers: [],
            currentGroup: 0,
            isFirstGroup: true,
            isLastGroup: false,

            isManager: false,
            isStaff: false,
            isMD: false,
            checkId: null,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getUser', 'getRoles', 'getDepartment']),

        async getFixedAssetPurchases() {
            let response = await getApiData({ url: `/api/fixed_asset_purchases?page=1`, token: this.getToken() });
            if (response.data) {
                this.fixedAssetPurchases = response.data.fixedAssetPurchase;
            }
        },

        async createBtnClicked() {
            if (!this.name || !this.date || !this.description || !this.total_price
                || !this.depreciation_amount || !this.start_date || !this.total_duration) {
                this.$notify({
                    title: `Input validation`,
                    text: `Please fill all requrired fields`,
                    type: 'warn'
                });
            }
            else {
                let formData = new FormData();
                formData.append('date', this.date);
                formData.append('name', this.name);
                formData.append('description', this.description);
                formData.append('total_price', this.total_price);
                formData.append('depreciation_amount', this.depreciation_amount);
                formData.append('start_date', this.start_date);
                formData.append('total_duration', this.total_duration);
                let response = await postApiData({ url: `/api/fixed_asset_purchases`, form_data: formData, token: this.getToken() });
                if (response.data) {
                    this.fixedAssetPurchases.unshift(response.data);
                }
            }
        },

        checkBtnClicked(fixedAssetId){
            this.checkId = fixedAssetId;
        },

        async confirmCheckBtnClicked(){
            let url = `/api/fixed_asset_purchases/is_update_checked`;
            let formData = new FormData();
            formData.append('id', this.checkId);
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.$notify({
                    text: 'Fixed asset checked',
                    type: 'info'
                });

                setTimeout(()=>{
                    window.location.reload();
                }, 3000);
            }
            else{
                this.$notify({
                    text: response.message,
                    type: 'error'
                });
            }
            // console.log(response);
        },
    },

    created() {
        this.getRoles().forEach((role)=>{
            if(role.name == 'Manager'){
                this.isManager = true;
            }
            if(role.name == 'MD'){
                this.isMD = true;
            }
            if(role.name == 'Staff'){
                this.isStaff = true;
            }
        });
        this.getFixedAssetPurchases();
    },

    mounted() {
        initTE({ Modal });
    }
}
</script>
