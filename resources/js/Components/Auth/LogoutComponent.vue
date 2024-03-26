<template>
    <button class="w-full text-left pl-12" @click="logoutBtnClicked">
        <i class="fal fa-sign-out pr-3"></i>Logout
    </button>

    <form action="/logout" method="POST" id="logout-form">
        <input type="hidden" v-model="csrfToken" name="_token">
    </form>
</template>

<script>
    import { postApiData } from '../../utilities/ajax-helpers';
    import { mapMutations, mapGetters } from 'vuex';

    export default {
        name: "LogoutComponent",
        data(){
            return {
                csrfToken: null,
            }
        },

        methods: {
            ...mapMutations(['setUser', 'setToken', 'setCsrfToken', 'setDepartment', 'setRoles']),
            ...mapGetters(['getToken', 'getCsrfToken']),

            async logoutBtnClicked(){
                let response = await postApiData({url: `/api/logout`, token: this.getToken()});
                if(response.success){
                    this.setUser(null);
                    this.setToken(null);
                    this.setDepartment(null);
                    this.setRoles([]);
                    $('#logout-form').submit();
                    // window.location.replace(`/login`);
                }
                $('#logout-form').submit();
                // window.location.replace(`/login`);
            }
        },

        mounted(){
            this.csrfToken = this.getCsrfToken();
        }
    }
</script>
