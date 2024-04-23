<template>
    <notifications position="top center" />
    <div class="w-full flex justify-between pr-4">
        <p> {{ user.name }} ({{ department.name }}) </p>
    </div>
    <div>
        <div class="relative" data-te-dropdown-ref>
            <button
                class="flex items-center rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal
                text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2
                focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600
                active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong
                dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                type="button" id="dropdownMenuButton1" data-te-dropdown-toggle-ref aria-expanded="false"
                data-te-ripple-init data-te-ripple-color="light" @click="sidebarNotificationTrayExpanded">
                <i class="fas fa-bell"></i>
                <div>
                    <p v-if="newNofiCount > 0" class="ml-1 bg-red-600 w-fit h-fit px-1 rounded-full py-1 text-sm leading-3 text-white">
                        {{ newNofiCount }}
                    </p>
                </div>
            </button>
            <ul class="absolute z-[1000] float-left m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-base shadow-lg data-[te-dropdown-show]:block dark:bg-surface-dark"
                aria-labelledby="dropdownMenuButton1" data-te-dropdown-menu-ref>
                <li v-for="(notification, notificationIndex) in notifications" :key="notificationIndex">
                    <div class="bg-gray-100 rounded-lg shadow-sm p-4 my-2">
                        <h2 class="text-lg font-semibold text-gray-800"> {{ notification.title }} <span v-if="notification.is_new" class="inline-block w-2 h-2 mr-2 bg-red-600 rounded-full"></span> </h2>
                        <p class="text-sm text-gray-600 mt-2"> {{ notification.preview }} ( {{ notification.elapsed_moment }} ) </p>
                        <button class="px-2 py-1 rounded-md bg-blue-300 mt-2" @click="markNotificationReadBtnClicked(notification.id, notificationIndex)">
                            <i class="fas fa-check mr-1"></i>
                            Mark as Read
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import firebase from 'firebase/compat/app';
    import 'firebase/messaging';
    import { Dropdown ,Modal, Ripple, Select, initTE } from "tw-elements";
    import { mapGetters } from "vuex";

    import { getApiData, postApiData } from '../../utilities/ajax-helpers';
    import { getElapsedMoments } from '../../utilities/datetime-helpers';

    export default{
        data() {
            return {
                firebaseMessaging: null,
                fcmToken: null,
                user: null,
                department: null,
                notifications: [],
                newNofiCount: 0,
            };
        },

        methods: {
            ...mapGetters(['getUser', 'getDepartment', 'getToken']),

            async getNotifications(){
                let url = `/api/notifications`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.notifications = response.data;
                    this.newNofiCount = 0;
                    this.notifications.forEach(notification => {
                        notification.notification_users.forEach((userNoti)=>{
                            if((userNoti.staff_id == this.getUser().id) && userNoti.is_read == 0){
                                notification.is_new = true;
                            }
                            if((userNoti.staff_id == this.getUser().id) && userNoti.is_read_count == 0){
                                this.newNofiCount++;
                            }
                        });
                    });
                }
            },

            async sidebarNotificationTrayExpanded(){
                let notificationIds = [];
                this.notifications.forEach((notification)=>{
                    notification.elapsed_moment = getElapsedMoments(notification.date_time);
                    notificationIds.push(notification.id);
                });
                let formData = new FormData();
                formData.append('notification_ids', JSON.stringify(notificationIds));
                let url = `/api/notifications/set_seen`;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    this.newNofiCount = 0;
                }
            },

            async markNotificationReadBtnClicked(notificationId, notificationIndex){
                let url = `/api/notifications/${notificationId}/mark_read`;
                let response = await postApiData({url: url, token: this.getToken()});
                if(response.success){
                    this.notifications[notificationIndex].is_new = false;
                }
            },

            async startOnMessageListener() {
                console.log(`im running`);
                try {
                    await this.firebaseMessaging.onMessage((payload) => {
                        console.log('message received: ', payload);
                        let title = payload.notification.title;
                        let body = payload.notification.body;
                        let notiOptions = { body: body };
                        new Notification(title, notiOptions);

                        this.$notify({
                            title: payload.notification.title,
                            text: payload.notification.body,
                            type: "info"
                        });
                        this.getNotifications();
                    });
                }
                catch (error) {
                    console.log('error', error);
                }
            },

            async requestPermission() {
                try {
                    const permission = await Notification.requestPermission();
                    if (permission == 'denied') {
                        this.$notify({
                            text: `Notification permission ${permission}`,
                            type: 'warn'
                        });
                    }
                    if (permission == 'granted') {
                        console.log(`permission granted`);
                        this.firebaseMessaging = firebase.messaging();
                        this.fcmToken = await this.firebaseMessaging.getToken();
                        console.log(this.fcmToken);
                        this.startOnMessageListener();
                    }
                }
                catch (error) {
                    this.$notify({
                        text: 'Firebase error',
                        type: "error"
                    });
                }
            },

        },

        created(){
            this.user = this.getUser();
            this.department = this.getDepartment();
            this.requestPermission();
            this.getNotifications();
        },

        mounted(){
            initTE({ Dropdown, Modal, Select, Ripple });
        },
    }
</script>
