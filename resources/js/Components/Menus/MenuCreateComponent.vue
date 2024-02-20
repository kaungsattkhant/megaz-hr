<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                Add Staff
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Name
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Price
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Menu Category
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Category
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Ingredients
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Weight
                </label>
                <input type="text"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    &nbsp;
                </label>
                <button class="add-btn">
                    Add New
                </button>
            </div>

        </div>

        <div class=" bg-white p-8 rounded-md shadow-md mb-8">
            <ul
            class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0"
            role="tablist"
            data-te-nav-ref>
            <li role="presentation">
                <a
                href="#tabs-home"
                class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"
                data-te-toggle="pill"
                data-te-target="#tabs-home"
                data-te-nav-active
                role="tab"
                aria-controls="tabs-home"
                aria-selected="true"
                >Home</a
                >
            </li>
            <li role="presentation">
                <a
                href="#tabs-profile"
                class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"
                data-te-toggle="pill"
                data-te-target="#tabs-profile"
                role="tab"
                aria-controls="tabs-profile"
                aria-selected="false"
                >Profile</a
                >
            </li>
            <li role="presentation">
                <a
                href="#tabs-messages"
                class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"
                data-te-toggle="pill"
                data-te-target="#tabs-messages"
                role="tab"
                aria-controls="tabs-messages"
                aria-selected="false"
                >Messages</a
                >
            </li>
            <li role="presentation">
                <a
                href="#tabs-contact"
                class="disabled pointer-events-none my-2 block border-x-0 border-b-2 border-t-0 border-transparent bg-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-400 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent dark:text-neutral-600"
                data-te-toggle="pill"
                data-te-target="#tabs-contact"
                role="tab"
                aria-controls="tabs-contact"
                aria-selected="false"
                >Contact</a
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
                Tab 1 content
            </div>
            <div
                class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                id="tabs-profile"
                role="tabpanel"
                aria-labelledby="tabs-profile-tab">
                Tab 2 content
            </div>
            <div
                class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                id="tabs-messages"
                role="tabpanel"
                aria-labelledby="tabs-profile-tab">
                Tab 3 content
            </div>
            <div
                class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                id="tabs-contact"
                role="tabpanel"
                aria-labelledby="tabs-contact-tab">
                Tab 4 content
            </div>
            </div>

        </div>


        
        <div>
            <button class="add-btn">
                Create Staff
            </button>
        </div>




        <!-- Department Modal -->
        <!-- <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_department_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
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
                                Department
                            </label>
                            <input type="text" placeholder="Department"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 ">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div> -->



        <!-- Role Modal -->
        <!-- <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_role_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
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
                                Role
                            </label>
                            <input type="text" placeholder="Role"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Department
                            </label>
                            <input type="text" placeholder="Department"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 ">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
    import { Modal, Ripple, initTE, Input, Tab, Select } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';

    export default {
        data() {
            return {
                staffList: [],
                genderList: [],
                departmentList: [],
                roleList: [],

                
            };
        },

        methods: {

            

        },
        mounted()
        {
            initTE({ Modal,Select, Tab, Ripple });
        }
    }
</script>
