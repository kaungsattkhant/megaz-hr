<template>

    <div>
        <button
            class="bg-red-600 focus:outline-none focus:ring-0 hidden" id="open_noti_modal"
            data-te-toggle="modal" data-te-target="#noti_modal">
            +
        </button>



        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="noti_modal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-baseline opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 pt-16 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-fit">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Notifications
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none
                        " id="closeCustomerModal" data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-6 pb-8" data-te-modal-body-ref>
                        <div>
                            <table class="min-w-full text-left text-sm font-light border-l border-t">
                                <thead class="border-b font-medium">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 border-r">Room Name</th>
                                        <th scope="col" class="px-6 py-4 border-r">Session duration </th>
                                        <th scope="col" class="px-6 py-4 border-r">Start time</th>
                                        <th scope="col" class="px-6 py-4 border-r">End time </th>
                                        <th scope="col" class="px-6 py-4 border-r">Customer name</th>
                                        <th scope="col" class="px-6 py-4 border-r text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b" v-for="(sessionRequest) in sessionRequests">

                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            {{ sessionRequest.room_name }}
                                        </td>
                                        <td class="whitespace-nowrap border-r px-6 py-4 text-center">
                                            {{ sessionRequest.session_duration }}
                                        </td>
                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            {{ sessionRequest.start_time }}
                                        </td>
                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            {{ sessionRequest.end_time }}
                                        </td>
                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            {{ sessionRequest.customer_name }}
                                        </td>
                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            <button :disabled="sessionRequest.status != 'received'" @click="btnRejectSession(sessionRequest.invoice_id)"
                                                class="px-3 py-2 bg-red-600 text-white rounded-md mr-2">
                                                Reject
                                            </button>
                                            <button :disabled="sessionRequest.status != 'received'" @click="btnConfirmSession(sessionRequest.invoice_id)"
                                                class="px-3 py-2 bg-green-600 text-white rounded-md">
                                                Confirm
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button class="pos-add-btn focus:outline-none focus:ring-0 px-8" data-te-modal-dismiss aria-label="Close">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>






        <!-- 2nd -->
        <button
            class="bg-red-600 focus:outline-none focus:ring-0 hidden" id="open_noti_modal2"
            data-te-toggle="modal" data-te-target="#noti_modal2">
            +
        </button>



        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="noti_modal2" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-baseline opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 pt-16 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-fit">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            End Room Notifications
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none
                        " id="closeNotimodal2" data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-6 pb-8" data-te-modal-body-ref>
                        <div>
                            <table class="min-w-full text-left text-sm font-light border-l border-t">
                                <thead class="border-b font-medium">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 border-r">Room Name</th>
                                        <!-- <th scope="col" class="px-6 py-4 border-r">Session duration </th> -->
                                        <!-- <th scope="col" class="px-6 py-4 border-r">Start time</th> -->
                                        <!-- <th scope="col" class="px-6 py-4 border-r">End time </th> -->
                                        <!-- <th scope="col" class="px-6 py-4 border-r">Customer name</th> -->
                                        <th scope="col" class="px-6 py-4 border-r text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b" v-for="(sessionRequest2) in sessionRequests2" :key="sessionRequest2">

                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            {{ sessionRequest2.room_name }}
                                        </td>
                                        <!-- <td class="whitespace-nowrap border-r px-6 py-4 text-center">
                                            {{ sessionRequest.session_duration }}
                                        </td>
                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            {{ sessionRequest.start_time }}
                                        </td>
                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            {{ sessionRequest.end_time }}
                                        </td>
                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            {{ sessionRequest.customer_name }}
                                        </td> -->
                                        <td class="whitespace-nowrap border-r px-6 py-4">
                                            <button :disabled="sessionRequest2.status != 'received'" @click="btnRejectSession2(sessionRequest2.invoice_id)"
                                                class="px-3 py-2 bg-red-600 text-white rounded-md mr-2">
                                                Reject
                                            </button>
                                            <button :disabled="sessionRequest2.status != 'received'" @click="btnConfirmSession2(sessionRequest2.invoice_id)"
                                                class="px-3 py-2 bg-green-600 text-white rounded-md">
                                                Confirm
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button class="pos-add-btn focus:outline-none focus:ring-0 px-8" data-te-modal-dismiss aria-label="Close">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    import firebase from 'firebase/compat/app';
    import 'firebase/messaging';
    import { Dropdown ,Modal, Ripple, Select, initTE } from "tw-elements";
    import { mapGetters } from "vuex";

    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';

    import { getApiData, postApiData } from '../../../utilities/ajax-helpers';
    import { convertToFriendlyDateTime } from '../../../utilities/datetime-helpers';

    export default{
        data() {
            return {
                firebaseMessaging: null,
                fcmToken: null,
                user: null,
                department: null,
                role:null,
                notifications: [],
                newNofiCount: 0,
                intervalId: null,

                sessionRequests: [],
                sessionRequests2: [],
            };
        },

        methods: {
            ...mapGetters(['getUser', 'getDepartment', 'getToken', 'getRoles']),

            // for open from waiter tablet
            notiModalOpen(){
                document.getElementById("open_noti_modal").click();
            },

            listenBroadCastNotifications(channel, event){
                window.Echo.channel(channel)
                .listen(event,(response)=>{
                    let newSessionRequest = {
                        invoice_id: response.invoice_id,
                        customer_name: response.customer_name,
                        room_name: response.room_name,
                        session_duration: response.room_session.session_duration,
                        start_time: convertToFriendlyDateTime(response.room_session.start_date),
                        end_time: convertToFriendlyDateTime(response.room_session.end_date),
                        status: 'received'
                    };

                    let index = this.sessionRequests.findIndex(sessionRequest => sessionRequest.invoice_id == newSessionRequest.invoice_id);
                    if(index != -1){
                        console.log('already requested');
                    }
                    else{
                        this.sessionRequests.push(newSessionRequest);
                    }

                    console.log(response);
                    console.log('session request received');
                    this.notiModalOpen();
                });

            },


            async btnConfirmSession(id){
                let formData = new FormData();
                formData.append("invoice_id", id);
                formData.append("is_confirm", 1);
                let response = await postApiData({ url: `/api/entities/confirm`, form_data: formData, token: this.getToken() });
                if (response.data) {
                    console.log('confirm success')
                }
                let index = this.sessionRequests.findIndex(sessionRequest => sessionRequest.invoice_id == id);
                if (index != -1) {
                    this.sessionRequests[index].status = 'confirmed';
                    this.sessionRequests.splice(index, 1);
                }
                window.location.reload();

                this.notiModalOpen();
            },
            async btnRejectSession(id){
                let formData = new FormData();
                formData.append("invoice_id", id);
                formData.append("is_confirm", 0);
                let response = await postApiData({ url: `/api/entities/confirm`, form_data: formData, token: this.getToken() });
                if (response.data) {
                    console.log('reject success')
                }
                let index = this.sessionRequests.findIndex(sessionRequest => sessionRequest.invoice_id == id);
                if (index != -1) {
                    this.sessionRequests[index].status = 'rejected';
                    this.sessionRequests.splice(index, 1);
                }
                window.location.reload();

                this.notiModalOpen();
            },





            // for closing from waiter tablet
            notiModalOpen2(){
                document.getElementById("open_noti_modal2").click();
            },

            listenBroadCastNotificationsDone(channel, event){
                window.Echo.channel(channel)
                .listen(event,(response)=>{
                    let newSessionRequest = {
                        room_name: response.entity.name,
                        invoice_id: response.invoice_id,
                        status: 'received'
                    };
                    let index = this.sessionRequests2.findIndex(sessionRequest => sessionRequest.id == newSessionRequest.id);
                    if(index != -1){
                        console.log('already requested');
                    }
                    else{
                        this.sessionRequests2.push(newSessionRequest);
                    }
                    this.notiModalOpen2();
                });
            },


            async btnConfirmSession2(id){

                let formData = new FormData();
                formData.append("invoice_id", id);
                let response = await postApiData({ url: `/api/entities/done?is_confirm=1`, form_data: formData, token: this.getToken() });
                if (response.data) {
                    console.log('confirm success')
                }
                let index = this.sessionRequests2.findIndex(sessionRequest => sessionRequest.invoice_id == id);
                if (index != -1) {
                    this.sessionRequests2[index].status = 'confirmed';
                    this.sessionRequests2.splice(index, 1);
                }
                window.location.reload();
                this.notiModalOpen2();
            },
            async btnRejectSession2(id){

                let formData = new FormData();
                formData.append("invoice_id", id);
                let response = await postApiData({ url: `/api/entities/done?is_confirm=0`, form_data: formData, token: this.getToken() });
                if (response.data) {
                    console.log('reject success')
                }
                let index = this.sessionRequests2.findIndex(sessionRequest => sessionRequest.invoice_id == id);
                if (index != -1) {
                    this.sessionRequests2[index].status = 'rejected';
                    this.sessionRequests2.splice(index, 1);
                }
                window.location.reload();
                this.notiModalOpen2();

            },
        },

        created(){
            this.user = this.getUser();
            this.department = this.getDepartment();
            this.role = this.getRoles();
            // this.requestPermission();
            // this.getNotifications();
        },

        mounted(){
            initTE({ Dropdown, Modal, Select, Ripple });


            let channelName = `room-notification-request.${this.department.id}`;
            let eventName = `RoomNotificationRequest`;
            this.listenBroadCastNotifications(channelName, eventName);

            let doneChannelName = `pos-roomdone-notification-request.${this.role[0].id}`;
            let doneEventName = `PosRoomDoneNotification`;
            this.listenBroadCastNotificationsDone(doneChannelName, doneEventName);
        },

        beforeDestroy() {
            clearInterval(this.intervalId);
        }
    }
</script>
