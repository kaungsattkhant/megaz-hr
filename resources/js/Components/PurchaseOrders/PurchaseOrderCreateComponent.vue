<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                Add Purchase Order
            </p>
        </div>

        <div class="grid grid-rows-3 grid-cols-12 grid-flow-col gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 row-span-2 col-span-1 w-48 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date 1
                </label>
                <input type="date" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="mb-0 row-span-1 grid-cols-12 grid-rows-3 rounded-md flex w-60" style="background-color: aquamarine;">
                <div id="div-1" class="col-span-4 mr-10 w-full">
                    <label for="" class="block text-sm text-black mb-1">
                        Item Name
                    </label>
                    <div class="text-sm input-ui w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            data-te-select-filter="true" name="" id="" v-model="selectedItem"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                            >
                            <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex"> {{ item.name }} </option>
                        </select>
                    </div>
                </div>
                <div id="div-2" class="col-span-4 mr-20">
                    <label for="" class="block text-sm text-black mb-3">
                        Qty
                    </label>
                    <input type="number" class="text-sm border border-gray-300 input-ui bg-transparent rounded-lg focus:ring-0" placeholder="Qty">
                </div>

                <div id="div-3" class="col-span-4">
                    <!-- <label for="" class="block text-sm text-black mb-1">
                        Ingredients
                    </label>
                    <div class="text-sm input-ui w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Category"
                            data-te-select-filter="true" name="" id="" v-model="selectedItem"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                            >
                            <option :value="item" v-for="(item, itemIndex) in itemList" :key="itemIndex"> {{ item.name }} </option>
                        </select>
                    </div> -->
                    <button> Add </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import { getApiData, deleteApiData } from '../../utilities/ajax-helpers';

    export default {
        data() {
            return {
                itemList: [],
                selectedItem: null,
                deleteId: null,
            };
        },

        methods: {


        },

        created()
        {

        },

        mounted()
        {

        }
    }
</script>
