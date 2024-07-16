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
                        <button class="pos-add-btn" data-te-toggle="modal" data-te-target="#create_cashbook_modal">
                            Add
                            </button>
                    </div>
                </div>

                <div>
                    <div class="bg-white px-4 pb-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Id</th>
                                    <th scope="col" class="px-6 py-4">Date & Time</th>
                                    <th scope="col" class="px-6 py-4">Customer Name</th>
                                    <th scope="col" class="px-6 py-4">Menu</th>
                                    <th scope="col" class="px-6 py-4">Address</th>
                                    <th scope="col" class="px-6 py-4">Ph Number</th>
                                    <th scope="col" class="px-6 py-4">Total</th>
                                    <th scope="col" class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        1
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        Jul 15 3 PM
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        Khant
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        Fried Chicken Rice            
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        22nd 87 Corner Mandalay
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        0920123454
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        15,000 MMks
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <button type="button" class="bg-green-600 px-6 py-2 text-white rounded-full"> 
                                            Confirm
                                        </button>
                                    </td>
                                </tr>
                                <div class="contents" v-for="num in 3">
                                    <tr class="">
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3 font-medium">
                                            2
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            Jul 15 3 PM
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            Khant
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            {{ 'Fried Chicken Rice' }} {{ num }}            
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            22nd 87 Corner Mandalay
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            0920123454
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            15,000 MMks
                                        </td>
                                        <td class="whitespace-nowrap px-6  text-center">
                                            <button type="button" class="bg-[#fb923c] px-6 py-2 text-white rounded-full"> 
                                                Pending
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="" v-for="(num,index) in 3">
                                        <td colspan="3" class="whitespace-nowrap px-6 py-3 font-medium"></td>
                                        <td class="whitespace-nowrap px-6 py-3">
                                            Fried Chicken Rice   {{ num }}         
                                        </td>
                                        <td colspan="3" class="whitespace-nowrap px-6 py-3"></td>
                                        <td class="whitespace-nowrap px-6 py-3 text-center">
                                            <button>
                                                <i class="fal fa-check text-sm mr-2"></i>
                                            </button>
                                            <button>
                                                <i class="fal fa-times text-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </div>
                                
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
                

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            
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
