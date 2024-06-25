<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Packages
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>

                <button class="add-btn h-8 text-[13px] font-inter">Search</button>
            </div>
            <div class="flex justify-end flex-col">
                <a href="/packages/create" class="add-btn text-[13px] font-inter">
                    Add New
                </a>

            </div>
        </div>
        <div class="box-container-table">
            <div class=" overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class=" table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class=" text-left">
                                    Package Name
                                </th>
                                <th scope="col" class="text-center  ">
                                    Image
                                </th>
                                <th scope="col" class=" text-left">
                                    Price
                                </th>
                                <th scope="col" class=" ">
                                    Item
                                </th>
                                <th scope="col" class="">

                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(promotionPackage, index) in promotionPackageList" :key="index">
                                <tr class="">
                                    <td class=" ">
                                        {{ ++index }}
                                    </td>
                                    <td class="whitespace-nowrap text-left  ">
                                        {{ promotionPackage.name }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <div class="relative flex border rounded text-center shrink-0 overflow-hidden rounded-md h-12 w-12">
                                            <img width="80" height="100" style="aspect-ratio: 4/3; object-fit: cover;" :src="promotionPackage.image_url" alt="Menu image">
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap text-left  ">
                                        {{ (promotionPackage.price).toLocaleString() }}
                                    </td>
                                    <td class="   ">
                                        <div>
                                            Menus: <span v-for="menuPackage in promotionPackage.menu_packages"> {{ menuPackage.menu.name }},  </span>
                                        </div>
                                        <hr>
                                        <div>
                                            Rooms: <span v-for="room in promotionPackage.rooms"> {{ room.name }},  </span>
                                        </div>

                                    </td>
                                    <td class="whitespace-nowrap   relative">
                                        <a href="#" class="pr-2 ">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    </td>
                                </tr>
                            </div>


                            <!-- looping end -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!--Delete Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                        <div
                            class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                            <!--Modal title-->
                            <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                                Delete ?
                            </h5>
                            <!--Close button-->
                            <button type="button"
                                class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
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
                        <div
                            class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                            <button type="button"
                                class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                                data-te-modal-dismiss>
                                Close
                            </button>
                            <button @click="confirmDeleteBtnClicked" type="button" data-te-toggle="modal"
                                data-te-target="#deleteModal"
                                class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
    import { mapGetters } from "vuex";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';

    export default {
        data() {
            return {
                promotionPackageList: [],

                per_page: 20,
                pageNumbers: [],
                currentPage: 1,
                paginationGroupsCount: 1,
                per_group: 10,
                groupedPageNumbers: [],
                currentGroup: 0,
                isFirstGroup: true,
                isLastGroup: false,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getPromotionPackageList(pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }

                let url = `/api/packages?page=${this.currentPage}&per_page=${this.per_page}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.promotionPackageList = response.data.data;
                }
            },
        },

        created(){
            this.getPromotionPackageList();
        },

        mounted(){
            initTE({ Modal, Ripple, Input, Select, Dropdown })
        }
    }
</script>
