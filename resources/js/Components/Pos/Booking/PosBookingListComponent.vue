<template>
    <div>
        <div class="">
            <div class="w-full pt-9 px-6">
                <div class="flex justify-between mb-4">
                    <div class="flex gap-x-3">
                        <button class="pos-add-btn">
                            Date
                        </button>
                    </div>
                    <div class="flex gap-x-3">
                        <a href="/booking/create" class="pos-add-btn">
                            Add
                        </a>
                    </div>
                </div>

                <div>
                    <div class="bg-white px-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Id</th>
                                    <th scope="col" class="px-6 py-4">Date & Time</th>
                                    <th scope="col" class="px-6 py-4">Customer Name</th>
                                    <th scope="col" class="px-6 py-4">Type</th>
                                    <th scope="col" class="px-6 py-4">Start Time</th>
                                    <th scope="col" class="px-6 py-4">Room</th>
                                    <th scope="col" class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        one
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        two
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        three
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        four            
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        five
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        sive
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <button>
                                            <i class="fal fa-check text-sm mr-2"></i>
                                        </button>
                                        <button>
                                            <i class="fal fa-times text-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        one
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        two
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        three
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        four            
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        five
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        sive
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <button>
                                            <i class="fal fa-play-circle text-sm mr-2"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_cashbook_modal" tabindex="-1" aria-labelledby="createCashbookModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Create Booking
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none
                        " id="closeModal" data-te-modal-dismiss aria-label="Close">
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
                            <input type="text" placeholder="Customer Name" class="input-ui focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Room
                            </label>
                            <input type="text" placeholder="Room" class="input-ui focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Type
                            </label>
                            <select name="" id=""
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="session"> Session </option>
                                        <option value="package"> Package </option>
                                        <option value="endless_time"> Endless Time </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Start Time
                            </label>
                            <input type="datetime-local" placeholder="Time"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div>
                            <div class="flex justify-between border-b pb-2 mb-3">
                                <p>Menu</p>
                                <button>
                                    +
                                </button>
                            </div>
                            <div>
                                <div class="flex justify-between">
                                    <p class="text-sm">Beer</p>
                                    <button>
                                        <i class="fal fa-times text-sm"></i>
                                    </button>
                                </div>
                                <div class="flex justify-between">
                                    <p class="text-sm">Cola</p>
                                    <button>
                                        <i class="fal fa-times text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="createBtnClicked" class="pos-add-btn !px-16 focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>



    </div>

</template>
<script>
    import { Modal, Ripple, Select, Datepicker, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                bookingList:[],

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getBookingList() {
                const response = await getApiData({ url: '/api/bookings', token: this.getToken() });
                if (response.data) {
                    this.bookingList = response.data;
                }
            
            },
            
        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        },

        created(){
            // this.getTotalBookList();
        }
    }
</script>
