<template>
    <notifications position="top center" />
    <div class="w-4/5 relative flex h-[100vh] bg-white mx-auto items-center gap-x-8">
        <div class="w-1/2">
            <img src="../../../../../public/img/login_bg.svg" alt="">
        </div>
        <div class="w-10/12 lg:w-1/2 ">
            <div class="mb-6 w-4/5">
                <img src="../../../../../public/img/logo.png" class="w-1/3 mx-auto" alt="">
                <div class="px-8" @keyup.enter="login">
                    <div>
                        <p class=" text-4xl font-black text-[#153063] mb-4">
                            Sign Up
                        </p>
                    </div>
                    <div class=" mb-4">
                        <label for="phoneNumber" class="text-sm text-black mb-2 block">
                            Phone Number
                        </label>
                        <input type="text" id="phoneNumber" autocomplete="off" v-model="phoneNumber"
                            class=" border border-gray-400 bg-white w-full rounded">
                    </div>
                    <div class=" mb-12">
                        <label for="password" class="text-sm text-black mb-2 block">
                            Password
                        </label>
                        <input type="password" id="password" autocomplete="off" v-model="password"
                            class=" border border-gray-400 bg-white w-full rounded">
                    </div>
                    <div class="w-full text-center">
                        <button @click="login"
                            class="bg-[#5d7fff] w-full mx-auto text-white text-sm rounded-md px-8 py-3 block mb-2.5">
                            Login
                        </button>
                    </div>
                </div>

                <form method="POST" id="signin-form" ref="signinForm" action="/login">
                    <input type="hidden" v-model="csrfToken" name="_token">
                    <input type="hidden" v-model="phoneNumber" name="phone_number">
                    <input type="hidden" v-model="password" name="password">
                    <input type="hidden" v-model="fcmToken" name="fcm_token">
                </form>

            </div>
        </div>
    </div>
</template>
<script>
    import { mapMutations } from 'vuex';
    import { postApiData } from '../../../utilities/ajax-helpers';

    import firebase from 'firebase/compat/app';
    import 'firebase/messaging';

    export default {
        name: "LoginComponent",
        data() {
            return {
                token: null,
                csrfToken: null,

                phoneNumber: null,
                password: null,
                fcmToken: null,
            }
        },

        methods: {
            ...mapMutations(['setUser', 'setToken', 'setCsrfToken', 'setDepartment', 'setRoles']),

            async login() {
                let url = '/api/login';
                let formData = new FormData();
                formData.append('phone_number', this.phoneNumber);
                formData.append('password', this.password);
                formData.append('fcm_token', this.fcmToken);

                let response = await postApiData({ url: url, form_data: formData });
                console.log(response);
                if (response.data) {
                    this.token = response.data.token;
                    this.setToken(this.token);
                    let user = JSON.parse(JSON.stringify(response.data.user));
                    // delete user.department;
                    // delete user.roles;
                    this.setUser(user);
                    this.setDepartment(response.data.user.department);
                    let roles = [];
                    response.data.user.roles.forEach(role => {
                        roles.push({ name: role.name, id: role.id });
                    });

                    this.setRoles(roles);
                    this.$refs.signinForm.submit();

                    return true;
                }
                else {
                    return false;
                }
            }
        },

        created() {
            this.csrfToken = $('meta[name="csrf-token"]').attr('content');
            this.setCsrfToken(this.csrfToken);
        },

        async mounted() {
            try {
                const permission = await Notification.requestPermission();
                console.log('notification permission', permission);
                let notiType = 'warn';
                if(permission == 'denied'){
                    notiType = 'warn';
                }
                if(permission == 'granted'){
                    notiType = 'info';
                    this.firebaseMessaging = firebase.messaging();
                    this.fcmToken = await this.firebaseMessaging.getToken();
                    console.log('fcm token',this.fcmToken);
                }

                this.$notify({
                    text: `Notification permission ${permission}`,
                    type: notiType
                });
            }
            catch (error) {
                console.log(error);
                this.$notify({
                    text: 'Firebase error',
                    type: "error"
                });
            }
        }
    }

</script>
