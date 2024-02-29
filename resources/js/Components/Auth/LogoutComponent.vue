<template>
    <button class="w-full text-left pl-12" @click="logoutBtnClicked">
        <i class="fal fa-sign-out pr-3"></i>Logout
    </button>
</template>

<script>
    import { postApiData } from '../../utilities/ajax-helpers';
    import { mapMutations, mapGetters } from 'vuex';

    export default {
        name: "LogoutComponent",
        data(){
            return {

            }
        },

        methods: {
            ...mapMutations(['setUser', 'setToken', 'setCsrfToken', 'setDepartment', 'setRoles']),
            ...mapGetters(['getToken']),

            async logoutBtnClicked(){
                let response = await postApiData({url: `/api/logout`, token: this.getToken()});
                if(response.success){
                    this.setUser(null);
                    this.setToken(null);
                    this.setDepartment(null);
                    this.setRoles([]);
                    window.location.replace(`/login`);
                }
            }
        },

        mounted(){

        }
    }
</script>
