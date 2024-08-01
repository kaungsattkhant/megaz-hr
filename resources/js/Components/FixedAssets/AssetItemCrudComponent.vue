<template>

</template>

<script>
    import { Modal, initTE } from "tw-elements";

    import { mapGetters } from 'vuex';
    import { getApiData, postApiData } from '../../utilities/ajax-helpers';

    export default {
    data() {
        return {
            parentAssetAccounts: [],

            per_page: 20,
            currentPage: 1,
            pageNumbers: [],
            paginationGroupsCount: 1,
            groupedPageNumbers: [],
            currentGroup: 0,
            isFirstGroup: true,
            isLastGroup: false,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getUser']),

        async getFixedAssetAccounts(){
            let url = `/api/account_by_sub_account/1`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.success){
                console.log(response.data);
                this.parentAssetAccounts = response.data;
            }
        }
    },

    created() {
        this.getFixedAssetAccounts();
    },

    mounted() {
        initTE({ Modal });
    }
}
</script>
