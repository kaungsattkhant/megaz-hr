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
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-fit">
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

                    <div class="relative px-2 pb-4" data-te-modal-body-ref>
                        <div>
                            <table class="min-w-full text-left text-sm font-light">
                                <thead class="border-b font-medium">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">Room Name</th>
                                        <th scope="col" class="px-6 py-4">Session duration </th>
                                        <th scope="col" class="px-6 py-4">Start time</th>
                                        <th scope="col" class="px-6 py-4">End time </th>
                                        <th scope="col" class="px-6 py-4">Customer name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="">

                                        <td class="whitespace-nowrap px-6 py-4">
                                            Room C
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            1
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            12:00 Am
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            1:00 PM
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            Ko kYaw
                                        </td>
                                    </tr>



                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button class="pos-add-btn focus:outline-none focus:ring-0 ">
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
    import { getElapsedMoments } from '../../../utilities/datetime-helpers';

    export default{
        data() {
            return {
                firebaseMessaging: null,
                fcmToken: null,
                user: null,
                department: null,
                notifications: [],
                newNofiCount: 0,
                intervalId: null,
            };
        },

        methods: {
            ...mapGetters(['getUser', 'getDepartment', 'getToken', 'getRoles']),

            // async getNotifications(){
            //     let url = `/api/notifications`;
            //     let response = await getApiData({url: url, token: this.getToken()});
            //     if(response.data){
            //         this.notifications = response.data;
            //         this.newNofiCount = 0;
            //         this.notifications.forEach(notification => {
            //             notification.notification_users.forEach((userNoti)=>{
            //                 if((userNoti.staff_id == this.getUser().id) && userNoti.is_read == 0){
            //                     notification.is_new = true;
            //                 }
            //                 if((userNoti.staff_id == this.getUser().id) && userNoti.is_read_count == 0){
            //                     this.newNofiCount++;
            //                 }
            //             });
            //         });
            //     }
            // },
            notiModalOpen(){
                document.getElementById("open_noti_modal").click();
            },

            listenBroadCastNotifications(channel, event){
                // console.log(`listining notifications on ${channel} channel`);
                window.Echo.channel(channel)
                .listen(event,(response)=>{

                    console.log(response);
                    console.log('message received');
                    // let title = response.title;
                    // let body = response.body;
                    // let notiOptions = { body: body };
                    // new Notification(title, notiOptions);
                    // this.$notify({
                    //     title: title,
                    //     text: body,
                    //     type: "info"
                    // });
                    // this.getNotifications();
                    // this.notiModalOpen();
                });

            },
        },

        created(){
            // this.user = this.getUser();
            this.department = this.getDepartment();
            // this.requestPermission();
            // this.getNotifications();
        },

        mounted(){
            initTE({ Dropdown, Modal, Select, Ripple });


            let channelName = `room-notification-request.${this.department.id}`;
            let eventName = `SendNotification`;

            this.listenBroadCastNotifications(channelName, eventName);

            // window.Echo.channel('send-notification.' + 1)
            // .listen('SendNotification',(response)=>{
            //     console.log(response);
            // });
            // .notification((notification) => {
            //     console.log(notification.type);
            // });

        },

        beforeDestroy() {
            clearInterval(this.intervalId);
        }
    }
</script>
