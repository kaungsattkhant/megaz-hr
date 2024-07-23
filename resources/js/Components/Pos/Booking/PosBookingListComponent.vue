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
                                <tr class="" v-for="(booking,index) in bookingList">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ booking.date_time }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ booking.customer.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ booking.session_type }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ booking.start_date }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ booking.entity.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <button v-if="booking.status == 'confirm'"  @click="btnClickedPlayModal(booking)"
                                            data-te-toggle="modal" data-te-target="#play_modal">
                                            <i class="fal fa-play-circle text-sm mr-2"></i>
                                        </button>
                                        <div class="contents" v-if="booking.status == 'pending'">
                                            <button data-te-toggle="modal" @click="btnClickedConfirmModal(booking.booking_id)"
                                                data-te-target="#confirm_modal">
                                            <i class="fal fa-check text-sm mr-2"></i>
                                            </button>
                                            <button @click="btnClickedRejectModal(booking.booking_id)" 
                                                data-te-toggle="modal" data-te-target="#cancel_modal">
                                                <i class="fal fa-times text-sm"></i>
                                            </button>
                                        </div>
                                        
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
                                        <button>
                                            <i class="fal fa-check text-sm mr-2"></i>
                                        </button>
                                        <button>
                                            <i class="fal fa-times text-sm"></i>
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
            id="confirm_modal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-fit translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                    
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <button type="button"
                        class="absolute -top-2 -right-2 rounded-full bg-white border-black border p-1 text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="pt-8 pb-4 px-8">
                        <p class="text-lg text-center font-semibold">
                            Confirm {{ selectedRoom }} ?
                        </p>
                    </div>
                    <div class="flex justify-center px-24 py-6 gap-x-8 mb-2">
                        <button type="button" class="focus:shadow-none focus:outline-none !px-16 !py-3"
                         id="closeModal" data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button @click="btnClickedConfirm()" class="pos-add-btn !px-16 !py-3 focus:outline-none focus:ring-0 ">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- cancel modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="cancel_modal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-fit translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                    
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <button type="button" class="absolute -top-2 -right-2 rounded-full bg-white border-black border p-1 text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="pt-8 pb-4 px-8">
                        <p class="text-lg text-center font-semibold">
                            Reject {{ selectedRoom }} ?
                        </p>
                    </div>
                    <div class="flex justify-center px-24 py-6 gap-x-8 mb-2">
                        <button type="button" class="focus:shadow-none focus:outline-none !px-16 !py-3
                        " id="closeModal" data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button @click="btnClickedReject()" class="pos-add-btn !px-16 !py-3 focus:outline-none focus:ring-0 ">
                            Decline
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- play modal -->
        <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="play_modal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-modal="true"
        role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-fit translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                  
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <button type="button" class="absolute -top-2 -right-2 rounded-full bg-white border-black border p-1 text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    
                    <div class="pt-8 pb-4 px-8">
                        <p class="text-lg text-center font-semibold">
                            Start {{ selectedRoom }} ?
                        </p>
                    </div>
                    
                    
                    
                    <div class="flex justify-center px-24 py-6 gap-x-8 mb-2">
                        <button type="button" class="focus:shadow-none focus:outline-none !px-16 !py-3
                        " id="closeModal" data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button @click="bookingRoomStart()" class="pos-add-btn !px-16 !py-3 focus:outline-none focus:ring-0 ">
                            Play
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
                bookingId:null,
                booking_staus:null,
                selectedRoom:null,

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getBookingList() {
                const response = await getApiData({ url: '/api/bookings', token: this.getToken() });
                if (response.data) {
                    this.bookingList = response.data.data;
                }
            
            },

            btnClickedConfirmModal(bookingId){
                this.bookingId = bookingId;
            },
            btnClickedRejectModal(bookingId){
                this.bookingId = bookingId;
            },
            btnClickedConfirm(){
                this.bookingStatusChange(1);
            },
            btnClickedReject(){
                this.bookingStatusChange(0);
            },
            async bookingStatusChange(bookingStauts){
                let formData = new FormData();
                formData.append('is_confirm', bookingStauts);
                let response = await postApiData({ url: '/api/booking_status/' + this.bookingId, form_data: formData, token: this.getToken() });
                if (response.success) {
                    window.location.reload();
                }
                else {
                    console.log('some errors occur');
                }
                // console.log('status '  + bookingStauts + ' , id = ' + this.bookingId )

                
            },
            btnClickedPlayModal(selectedBooking){
                this.bookingId = selectedBooking.booking_id;
                this.selectedRoom = selectedBooking.entity.name
            },
            async bookingRoomStart(){
                let response = await postApiData({ url: '/api/booking_active/' + this.bookingId, token: this.getToken() });
                if (response.success) {
                    window.location.reload();
                }
                else {
                    console.log(response.error);
                }
            }
            
        },
        mounted()
        {

            initTE({ Modal, Select, Ripple, Datepicker });
        },

        created(){
            this.getBookingList();
        }
    }
</script>
