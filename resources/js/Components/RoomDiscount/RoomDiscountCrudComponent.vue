<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Department
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">
                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
                    Add New
                </button>
            </div>
        </div>
        <div class="block mx-4 mt-4 pb-4">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class=" ">
                                    Room
                                </th>
                                <th scope="col" class="">
                                    From To
                                </th>
                                <th scope="col" class="">
                                    Paid Session
                                </th>
                                <th scope="col" class="">
                                    Free Session
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents">
                                <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                    <td class=" px-6 py-4 font-medium ">
                                        1
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        Room
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        Form to
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        Paid
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        Free
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <button data-te-toggle="modal"
                                            data-te-target="#editModal" id="edit-btn" class="pr-1">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <!-- <button @
                                    data-te-toggle="modal" data-te-target="#deleteModal"
                                    class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> -->
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
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
                            Room Discounts 
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
                                Rooms
                            </label>
                            <input type="text" placeholder="Rooms" v-model="name" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                From
                            </label>
                            <input type="text" placeholder="From" v-model="name" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                To
                            </label>
                            <input type="text" placeholder="To" v-model="name" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Used Session
                            </label>
                            <input type="text" placeholder="Used Session" v-model="name" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Free Session
                            </label>
                            <input type="text" placeholder="Free Session" v-model="name" class="input-ui">
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button"
                            class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</template>

<script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect
        },
        data() {
            return {}
                
        },
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
