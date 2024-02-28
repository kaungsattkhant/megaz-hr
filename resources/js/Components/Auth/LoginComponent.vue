<template>
    <main class="w-full block relative ">
        <div class="w-[100vw] h-[100vh] overflow-hidden">
            <!-- <img class="h-auto w-full" src="../../../public/img/loginbackground.jpg" alt=""> -->
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
                        <button @click="login" class="bg-[#000000] px-6 py-2 rounded-full text-sm text-black">Login</button>
                    </div>
                </div>
            </div>

            <form method="POST" id="signin-form" ref="signinForm" action="/login">
                <input type="hidden" v-model="csrfToken" name="_token">
                <input type="hidden" v-model="phoneNumber" name="phone_number">
                <input type="hidden" v-model="password" name="password">
                <input type="hidden" v-model="remember" name="remember">
            </form>
        </div>
    </main>
</template>

<script>
    import { mapMutations } from 'vuex';
    import { postApiData } from '../../utilities/ajax-helpers';

    // import firebase from 'firebase/compat/app';
    // import 'firebase/messaging';

    export default {
        name: "LoginComponent",
        data(){
            return {
                token: null,
                csrfToken: null,
                // firebaseMessaging: null,

                phoneNumber: null,
                password: null,
                fcmToken: null,
                remember: true,
            }
        },

        methods: {
            ...mapMutations(['setUser', 'setToken', 'setCsrfToken', 'setDepartment', 'setRoles']),

            async login(){
                let url = '/api/login';
                let formData = new FormData();
                formData.append('phone_number', this.phoneNumber);
                formData.append('password', this.password);
                formData.append('fcm_token', this.fcmToken);

                let response = await postApiData({url: url, form_data: formData});
                console.log(response);
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
                    this.$refs.signinForm.submit();

                    return true;
                }
                else{
                    return false;
                }
            }
        },

        created(){
            this.csrfToken = $('meta[name="csrf-token"]').attr('content');
            // console.log(this.csrfToken);
            this.setCsrfToken(this.csrfToken);
        },

        async mounted(){
            // try {
            //     const permission = await Notification.requestPermission();
            //     console.log('notification permission', permission);
            //     let notiType = 'warn';
            //     if(permission == 'denied'){
            //         notiType = 'error';
            //     }
            //     if(permission == 'granted'){
            //         notiType = 'info';
            //         this.firebaseMessaging = firebase.messaging();
            //         this.fcmToken = await this.firebaseMessaging.getToken();
            //     }

            //     this.$notify({
            //         text: `Notification permission ${permission}`,
            //         type: notiType
            //     });
            // }
            // catch (error) {
            //     this.$notify({
            //         text: 'Notification permission error',
            //         type: "error"
            //     });
            // }
        }
    }
</script>
