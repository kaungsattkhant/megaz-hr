<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add Product Tree
            </p>
        </div>



        

        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Room
                </label>
                <input type="text" v-model="name" class="input-ui mb-2">
            </div>
        </div>

        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Objective Name
                </label>
                <input type="text" v-model="name" class="input-ui mb-2">
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]">
                    Add
                </button>
            </div>
            <div class="col-span-12">
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    Objective
                                </th>
                                <th scope="col" class="">
                                    Duration
                                </th>
                                <th scope="col" class="">
                                    Role
                                </th>
                                <th scope="col" class="">
    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="">
                                <td class="">
                                    1
                                </td>
                                <td class="">
                                    2
                                </td>
                                <td class="">
                                    3
                                </td>
                                <td class="">
                                    <button>
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Item Name
                </label>
                <input type="text" v-model="name" class="input-ui mb-2">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Amount
                </label>
                <input type="number" v-model="name" class="input-ui mb-2">
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]">
                    Add
                </button>
            </div>
            <div class="col-span-12">
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    Objective
                                </th>
                                <th scope="col" class="">
                                    Duration
                                </th>
                                <th scope="col" class="">
                                    Role
                                </th>
                                <th scope="col" class="">
    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="">
                                <td class="">
                                    1
                                </td>
                                <td class="">
                                    2
                                </td>
                                <td class="">
                                    3
                                </td>
                                <td class="">
                                    <button>
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div>
            <button class="add-btn">
                Create Menu
            </button>
        </div>
        
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Tab, Select } from "tw-elements";
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            inputs: [{ value: "",name: "" }],  // Start with one input box

        };
    },

    methods: {
        ...mapGetters(['getToken']),

        addInput() {
            this.inputs.push({ value: "",name:"" });  // Add a new input box
        },
        // alertValiationMessage(field) {
        //     this.$notify({
        //         title: `Input validation`,
        //         text: `You forgot to provide ${field}, please try again`,
        //         type: "warn"
        //     });
        // },

        

        
    },



    async created() {
        
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
