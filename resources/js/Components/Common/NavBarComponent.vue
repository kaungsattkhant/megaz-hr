<template>
    <div>
        <p> {{ user.name }} ({{ department.name }}) </p>
    </div>
    <div>
        <!-- <button>
            <i class="fas fa-bell"></i>
        </button> -->
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default{
        data() {
            return {
                user: null,
                department: null,
            };
        },

        methods: {
            ...mapGetters(['getUser', 'getDepartment']),
        },

        created(){
            this.user = this.getUser();
            this.department = this.getDepartment();
        },

        mounted(){

        },
    }
</script>
