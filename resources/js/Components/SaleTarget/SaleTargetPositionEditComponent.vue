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
                    data-te-select-filter="true" @change="getRoleByDepartment()" class="input-ui w-full">
                        <option :value="department.id" v-for="(department, index) in departmentList"> {{ department.name }} </option>
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
        props:['positionId'],
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

                saleTargetPositionDetail:null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getSaleTargetPositionDetail(){
                console.log(this.positionId)
                const response = await getApiData({ url: '/api/sale_target_positions/'+this.positionId , token: this.getToken() });
                if(response.data){
                    this.selectedDepartment = response.data.department.id
                    this.getRoleByDepartment();
                    this.saleTargetPositionDetail = response.data;
                    let month = response.data.month.slice(0,7)
                    this.selectedDate = month;
                    this.selectedHeadCount = response.data.head_count
                    this.getRoleForDetail();
                }
            },
            async getRoleForDetail()
            {   
                const response = await getApiData({ url: `/api/role_by_department/` + this.selectedDepartment, token: this.getToken() });
                if(response.data){
                    this.roleList = response.data;
                    this.saleTargetPositionDetail.target_positions.forEach((position)=>{
                        let role_name = this.roleList.find(role => role.id == position.role_id ).name;
                        console.log('role'+role_name)
                        this.addedPositionList.push({
                            name: role_name,
                            role_id: position.role_id,
                            amount: position.amount,
                        });
                    });
                }
            },
            async getDepartmentList(){
                const response = await getApiData({ url: '/api/departments' , token: this.getToken() });
                if(response.data){
                    this.departmentList = response.data;
                }
            },
            async getRoleByDepartment(departmentId)
            {   
                const response = await getApiData({ url: `/api/role_by_department/` + this.selectedDepartment, token: this.getToken() });
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
                formData.append('department_id', this.selectedDepartment);
                formData.append('head_count', this.selectedHeadCount);
                formData.append('target_positions', JSON.stringify(positionListForForm));

                let url = `/api/sale_target_positions/`+this.positionId;
                let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
                if (response.success) {
                    this.$notify({
                        text: `Sale Target Position created successfully`,
                        type: "info"
                    });

                    // window.location.replace('/sale_target_position');
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
            this.getSaleTargetPositionDetail();
        },

        mounted(){
            initTE({ Modal, Select, Tab, Ripple, Input });
        }
    }
</script>
