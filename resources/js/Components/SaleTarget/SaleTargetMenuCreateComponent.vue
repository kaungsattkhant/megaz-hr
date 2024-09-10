<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Create Sale Target (Menu)
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 gap-y-4 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Month
                </label>
                <input type="date" class="input-ui" v-model="selectedDate" placeholder="Package Name">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Department
                </label>
                <div class="w-full select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" v-model="selectedDepartment"
                    data-te-select-filter="true" @change="headAccountSelectChanged" class="input-ui w-full">
                        <option :value="department" v-for="(department, index) in departmentList"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Sale Target(Head Count)
                </label>
                <input type="text" class="input-ui" :min="today" v-model="selectedHeadCount">
            </div>
            <div class="mb-3 col-span-3 rounded-md"></div>


            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Role
                </label>
                <div class="w-full select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" v-model="selectedRole"
                    data-te-select-filter="true" @change="headAccountSelectChanged" class="input-ui w-full">
                        <option :value="role" v-for="(role, index) in roleList"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Sale Target(Amount)
                </label>
                <input type="text" class="input-ui" v-model="selectedAmount">
            </div>

            

            <div class="col-span-3">
                <label for="" class="label-form mb-3"> &nbsp;</label>
                <button class="add-btn h-10" >
                    Add Menu
                </button>
            </div>
            <div class="col-span-3"></div>

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

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(promotionMenu, menuIndex) in selectedMenus" :key="menuIndex" >
                            <tr class="">
                                <td class="text-left">
                                    {{ promotionMenu.name }}
                                </td>
                                <td class="  ">
                                    {{ promotionMenu.quantity }}
                                </td>
                                <td class="  ">
                                    <button @click="removePackageMenuBtnClicked(menuIndex)">
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
            <button class="add-btn" @click="createBtnClicked" >
                Create Package
            </button>
        </div>
    </div>
</template>

<script>
    import { Modal, Ripple, initTE, Input, Tab, Select } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { getCurrentDate, getFirstDate } from "../../utilities/datetime-helpers";
    import { mapGetters } from "vuex";
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect
        },
        data() {
            return {
                roleList:[],
                departmentList:[],


                selectedRole:null,
                selectedDepartment:null,

                
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getMenuCategoryList(){
                let url = `/api/menu_categories`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.menuCategoryList = response.data;
                }
            },

            
        },

        created(){
            this.getMenuCategoryList();
        },

        mounted(){
            initTE({ Modal, Select, Tab, Ripple, Input });
        }
    }
</script>
