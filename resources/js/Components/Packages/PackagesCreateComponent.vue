<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Create Item Usage Forecast
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 gap-y-4 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Package Name
                </label>
                <input type="text"
                    class="input-ui">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Price
                </label>
                <input type="number"
                    class="input-ui">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    From
                </label>
                <input type="text"
                    class="input-ui">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    To
                </label>
                <input type="text"
                    class="input-ui">
            </div>

            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Sessions
                </label>
                <input type="text"
                    class="input-ui">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Rooms
                </label>
                <input type="text"
                    class="input-ui">
            </div><div class="col-span-6"></div>

            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Type
                </label>
                <div class="text-sm w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" data-te-select-filter="true"
                        name="" id=""
                        class="input-ui">
                        <option> test</option>
                    </select>
                </div>
            </div>
            <div class="mb-0 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Menu
                </label>
                <div class="text-sm w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Menu" data-te-select-filter="true"
                        name="" id=""
                        class="input-ui">
                        <option> test</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Menu Name
                </label>
                <input type="text"
                    class="input-ui">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Qty
                </label>
                <input type="text"
                    class="input-ui">
            </div>
            <div class="col-span-12 flex justify-end pt-8">
                <button class="add-btn" @click="addItemBtnClicked">
                    Add Item
                </button>
            </div>

        </div>

        <div class=" bg-white py-4 px-4 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class=" text-left ">
                                Menu Name
                            </th>
                            <th scope="col" class="  ">
                                Qty
                            </th>
                            <th scope="col" class="  ">
                                Price
                            </th>
                            <th scope="col" class="  ">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" >
                            <tr class="">
                                <td class="text-left">
                                    menu name
                                </td>
                                <td class="  ">
                                    qty
                                </td>
                                <td class="  ">
                                    price
                                </td>
                                <td class="  ">
                                    <button>
                                        <i class="fal fa-trash  pr-3"></i>
                                    </button>
                                </td>
                            </tr>
                        </div>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <button class="add-btn">
                Create Forecast
            </button>
        </div>
    </div>
</template>

<script>
    import { Modal, Ripple, initTE, Input, Tab, Select } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { getCurrentDate, getFirstDate } from "../../utilities/datetime-helpers";
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {

            };
        },

        methods: {

        },

        mounted(){
            initTE({ Modal, Select, Tab, Ripple });
        }
    }
</script>
