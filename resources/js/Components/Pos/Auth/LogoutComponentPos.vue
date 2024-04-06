<template>
    <button class="w-full flex items-center flex-col  rounded-lg px-6 py-12 text-black" @click="logoutBtnClicked">
        <i class="fas fa-sign-out-alt"></i>
        Logout
    </button>
</template>

<script>
import { postApiData } from '../../../utilities/ajax-helpers';
import { mapMutations, mapGetters } from 'vuex';

export default {
    name: "LogoutComponentPos",
    data() {
        return {

        }
    },

    methods: {
        ...mapMutations(['setUser', 'setToken', 'setCsrfToken', 'setDepartment', 'setRoles']),
        ...mapGetters(['getToken']),

        async logoutBtnClicked() {
            let response = await postApiData({ url: `/api/logout`, token: this.getToken() });
            if (response.success) {
                this.setUser(null);
                this.setToken(null);
                this.setDepartment(null);
                this.setRoles([]);
                window.location.replace(`/pos/login`);
            }
        }
    },

    mounted() {

    }
}
</script>
