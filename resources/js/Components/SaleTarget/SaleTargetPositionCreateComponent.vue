<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Create Sale Target (Position)
            </p>
        </div>


        <div class="grid !grid-cols-12 gap-x-8 gap-y-4 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Month
                </label>
                <input type="month" class="input-ui" v-model="selectedDate" placeholder="Package Name">
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Department
                </label>
                <div class="w-full select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" v-model="selectedDepartment"
                    data-te-select-filter="true" @change="changedDepartment()" class="input-ui w-full">
                        <option :value="department" v-for="(department, index) in departmentList"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-3 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Sale Target(Head Count)
                </label>
                <input type="text" class="input-ui" v-model="selectedHeadCount">
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



            <div class="col-span-3 flex justify-end flex-col mb-3">
                <!-- <label for="" class="label-form mb-3"> &nbsp;</label> -->
                <button class="add-btn h-8 w-fit mb-1" @click="btnClickedAddPosition()">
                    Add Menu
                </button>
            </div>
            <div class="col-span-3"></div>

        </div>

        <div class=" bg-white py-8 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class=" text-left ">
                                Position
                            </th>
                            <th scope="col" class="  ">
                                Amount
                            </th>
                            <th scope="col" class="  ">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(position,index) in addedPositionList" :key="index" >
                            <tr class="">
                                <td class="text-left">
                                    {{ position.name }}
                                </td>
                                <td class="  ">
                                    {{ position.amount }}
                                </td>
                                <td class="  ">
                                    <button @click="removeAddedPosition(index)">
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
            <button class="add-btn" @click="createBtnClicked()" >
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
                addedPositionList:[],

                selectedDate:null,
                selectedHeadCount:null,
                selectedAmount:null,
                selectedRole:null,
                selectedDepartment:null,


            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getDepartmentList(){
                const response = await getApiData({ url: '/api/departments' , token: this.getToken() });
                if(response.data){
                    this.departmentList = response.data;
                }
            },
            changedDepartment(){
                this.selectedRole = null;
                this.addedPositionList = [];
                this.getRoleByDepartment();
            },
            async getRoleByDepartment(departmentId)
            {
                const response = await getApiData({ url: `/api/role_by_department/` + this.selectedDepartment.id, token: this.getToken() });
                if(response.data){
                    this.roleList = response.data;
                }
            },


            btnClickedAddPosition() {
                if(!this.selectedRole || !this.selectedAmount){
                    this.$notify({
                        text: `Failed`,
                        type: "error"
                    });
                }
                else{
                     this.addPosition();
                }

            },
            addPosition(){
                this.addedPositionList.push({
                    name: this.selectedRole.name,
                    role_id: this.selectedRole.id,
                    amount: this.selectedAmount
                });
                this.selectedRole = null;
                this.selectedAmount = null;
            },

            removeAddedPosition(index) {
                this.addedPositionList.splice(index, 1);
            },


            async createBtnClicked() {
                let selectedMonth = getFirstDate(this.selectedDate)
                let positionListForForm = [];
                this.addedPositionList.forEach((ap) => {
                    positionListForForm.push({ role_id: ap.role_id, amount: ap.amount });
                });
                let formData = new FormData();
                formData.append('month', selectedMonth);
                formData.append('department_id', this.selectedDepartment.id);
                formData.append('head_count', this.selectedHeadCount);
                formData.append('target_positions', JSON.stringify(positionListForForm));

                let url = `/api/sale_target_positions`;
                let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.$notify({
                        text: `Sale Target Position created successfully`,
                        type: "info"
                    });
                    window.location.replace('/sale_target_position');
                }
                else {
                    this.$notify({
                        text: `Sale Target Position create failed`,
                        type: "error"
                    });
                }
            },


        },

        created(){
            this.getDepartmentList();
        },

        mounted(){
            initTE({ Modal, Select, Tab, Ripple, Input });
        }
    }
</script>
