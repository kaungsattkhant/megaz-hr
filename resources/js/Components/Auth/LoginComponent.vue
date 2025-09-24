<template>
    <notifications position="top center" />


    <div class="w-4/5 relative flex h-[100vh] bg-white mx-auto items-center gap-x-8">
        <div class="w-1/2">
            <img src="../../../../public/img/login_bg.svg" alt="">
        </div>
        <div class="w-10/12 lg:w-1/2 ">
            <div class="mb-6 w-4/5">
                <img src="../../../../public/img/logo.png" class="w-1/3 mx-auto" alt="">
                <div class="px-8" @keyup.enter="login">
                    <div>
                        <p class=" text-4xl font-black text-[#153063] mb-4">
                            Login
                        </p>
                    </div>
                    <div class=" mb-4">
                        <label for="phoneNumber" class="text-sm text-black mb-2 block">
                            Phone Number
                        </label>
                        <input type="text" id="phoneNumber" autocomplete="off" v-model="phoneNumber"
                            class=" border border-gray-400 bg-white w-full rounded" placeholder="Enter phone number">
                    </div>
                    <div class=" mb-12">
                        <label for="password" class="text-sm text-black mb-2 block">
                            Password
                        </label>
                        <input type="password" id="password" autocomplete="off" v-model="password"
                            class=" border border-gray-400 bg-white w-full rounded" placeholder="Enter password">
                    </div>
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" v-model="remember" checked class="form-checkbox mr-2" >
                            <span class="text-sm">Remember me next time</span>
                        </label>
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
                    <input type="hidden" v-model="remember" name="remember">
                    <input type="hidden" v-model="fcmToken" name="fcm_token">
                </form>

            </div>
        </div>
    </div>


















    <!-- <main class="w-full block relative ">
        <div class="w-[100vw] h-[100vh] overflow-hidden">
            <div class=" mx-auto rounded-md" style="width:30vw;left:calc(50% - 15vw);top:24%;position:absolute;padding:3rem">
                <div class="mb-3">
                    <p class=" text-2xl text-black primary-font relative dash-under">Login</p>
                </div>
                <div class="bg-white" @keyup.enter="login">
                    <div class="mb-3 w-full">
                        <label for="phone_number" class="block mb-2 text-sm">
                            Phone Number
                        </label>
                        <input type="text" id="phone_number" v-model="phoneNumber" class="block w-full py-1 border text-sm bg-white focus:ring-0 focus:shadow-none">
                    </div>
                    <div class="mb-3 w-full">
                        <label for="password" class="block mb-2 text-sm">
                            Password
                        </label>
                        <input type="password" id="password" v-model="password" class="block w-full py-1 border text-sm bg-white focus:ring-0 focus:shadow-none">
                        <button class="text-xs pt-2 border-0 bg-transparent">
                            Forget Password?
                        </button>
                    </div>
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" v-model="remember" checked class="form-checkbox mr-2" >
                            <span class="text-sm">Remember me next time</span>
                        </label>
                    </div>
                    <div class="mb-0 flex justify-center">
                        <button @click="login" class="bg-[#FF4300] px-6 py-2 rounded-full text-sm text-white">Login</button>
                    </div>
                </div>
            </div>

            <form method="POST" id="signin-form" ref="signinForm" action="/login">
                <input type="hidden" v-model="csrfToken" name="_token">
                <input type="hidden" v-model="phoneNumber" name="phone_number">
                <input type="hidden" v-model="password" name="password">
                <input type="hidden" v-model="remember" name="remember">
                <input type="hidden" v-model="fcmToken" name="fcm_token">
            </form>
        </div>
    </main> -->
</template>

<script>
    import { mapMutations } from 'vuex';
    import { postApiData } from '../../utilities/ajax-helpers';

    import firebase from 'firebase/compat/app';
    import 'firebase/messaging';
    import { notify } from '../../utilities/vue-toastification-helper';

    export default {
        name: "LoginComponent",
        data(){
            return {
                token: null,
                csrfToken: null,
                phoneNumber: null,
                password: null,
                fcmToken: null,
                remember: true,
            }
        },

        methods: {
            ...mapMutations(['setUser', 'setToken', 'setCsrfToken', 'setDepartment', 'setRoles', 'setFeature']),

            async login(){
                let url = '/api/login';
                let formData = new FormData();
                formData.append('phone_number', this.phoneNumber);
                formData.append('password', this.password);
                if(this.fcmToken){
                    formData.append('fcm_token', this.fcmToken);
                }

                let response = await postApiData({url: url, form_data: formData});
                if(response.data){
                    this.token = response.data.token;
                    this.setToken(this.token);
                    let user = JSON.parse(JSON.stringify(response.data.user));
                    delete user.department;
                    delete user.roles;
                    this.setUser(user);
                    this.setDepartment(response.data.user.department);
                    let roles = [];
                    response.data.user.roles.forEach(role => {
                        roles.push({name: role.name, id: role.id});
                    });
                    this.setRoles(roles);

                    let features = [];
                    features = JSON.parse(JSON.stringify(response.data.features));
                    this.setFeature(features);
                    notify('Login successful','success');
                    this.$refs.signinForm.submit();

                    return true;
                }
                else{
                    notify(response.message,'warning');
                    return false;
                }
            }
        },

        created(){
            this.csrfToken = $('meta[name="csrf-token"]').attr('content');
            this.setCsrfToken(this.csrfToken);
        },

        async mounted(){
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
                    text: 'Firebase notification unavailable, using only WS notification',
                    type: "warn"
                });
            }
        }
    }
</script>
