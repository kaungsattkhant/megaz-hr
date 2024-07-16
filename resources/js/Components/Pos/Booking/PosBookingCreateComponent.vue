<template>
    <div>

        <div class="">
            <div class="w-full pt-9 px-6">

                <div class="bg-white rounded w-full mx-auto">
                    <div class="relative  pt-6 px-8 mb-4">
                        <p class="text-xl w-full text-left">
                            Create Booking
                        </p>
                    </div>

                    <div class="grid grid-cols-12 gap-x-8 relative px-8 py-4">
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Customer Name
                            </label>
                            <input type="text" placeholder="Customer Name" v-model="name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4 col-span-3">
                            <label for="" class="block text-sm text-black mb-3">
                                Type
                            </label>
                            <select name="" id="" v-model="type"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="package"> Package </option>
                                <option value="session"> Session </option>
                            </select>
                        </div>
                        <div class="mb-4 col-span-3" v-if="type == 'package'">
                            <label for="" class="block text-sm text-black mb-3">
                                Gender
                            </label>
                            <select name="" id=""
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option v-for="package in packageList" > {{ package }} </option>
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 pb-8">
                        <button class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>

        </div>







    </div>
</template>
<script>
    import { Modal, Ripple, Select, Datepicker, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                name:null,
                type:null,
                packageList:[],

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getPackageList() {
                const response = await getApiData({ url: '/api/packages', token: this.getToken() });
                if (response.data) {
                    this.packageList = response.data.data;
                }
            
            // if (this.type == 'package') {
            //     const response = await getApiData({ url: '/api/packages', token: this.getToken() });
            //     if (response.data) {
            //         this.packageList = response.data.data;
            //     }
            // }
            // else {
            //     this.packageList = null;
            // }

        },
        
        },
        mounted()
        {   
            this.getPackageList();
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
