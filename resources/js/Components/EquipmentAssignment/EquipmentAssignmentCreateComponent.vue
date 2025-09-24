<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Assign Equipment
            </p>
        </div>


        <div class="grid !grid-cols-10 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <!-- <div class="mb-6 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date
                </label>
                <input type="date" v-model="selectedDate" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div><div class="col-span-7"></div> -->
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Department
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="changeDepartment()"
                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black text-sm">
                        <option :value="department" v-for="(department, index) in departmentList"
                            :key="index"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Role
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" @change="roleChange()"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Staff
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Staff"
                        data-te-select-filter="true" name="" id="" v-model="selectedStaff" class="input-ui !text-black text-sm">
                        <option :value="staff" v-for="(staff, index) in staffList"
                            :key="index"> {{ staff.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Equipment
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Equipment"
                        data-te-select-filter="true" name="" id="" v-model="selectedEquipment" class="input-ui !text-black text-sm">
                        <option :value="equipment" v-for="(equipment, index) in equipmentList"
                            :key="index"> {{ equipment.name }} </option>
                    </select>
                </div>
            </div>
            <!-- <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Uom Type
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Uom Type"
                        data-te-select-filter="true" name="" id="" v-model="selectedUomType" class="input-ui !text-black text-sm">
                        <option value="base_uom">Base Uom</option>
                        <option value="uom">Uom</option>
                    </select>
                </div>
            </div> -->
            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Amount
                </label>
                <input type="number" placeholder="Amount" v-model="amount" class="input-ui">
            </div>
            
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="btnClickedAddEquipment()">
                    Add
                </button>
            </div>
        </div>

        <div class=" bg-white py-8 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="!text-left">
                        <tr>
                            <th scope="col" class="">
                                Equipment
                            </th>
                            <th scope="col" class="">
                                Amount
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="!text-left">
                        <tr class="" v-for="(item, itemIndex) in selectedEquipmentList"
                            :key="itemIndex">
                            <td class="">
                                {{ item.item_name }}
                            </td>
                            <td class="">
                                {{ item.quantity }}
                            </td>
                            <td class="text-center">
                                <button @click="removeItem(itemIndex)">
                                    <i class="fas fa-times  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class=" !text-center" v-if="selectedEquipmentList.length < 1">
                            <td class="" colspan="3">
                                No Data Here
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <button class="add-btn" @click="btnClickedCreateEquipmentAssing()">
                Create
            </button>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Tab, Select } from "tw-elements";
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { each } from "lodash";

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            departmentList: [],
            roleList: [],
            staffList: [],
            equipmentList: [],
            areaList: [],

            selectedDepartment: null,
            selectedRole: null,
            selectedStaff: null,
            selectedEquipment: null,
            amount: null,
            selectedUomType: null,
            
            selectedEquipmentList: [],
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        async getEquipmentList(){
            let response = await getApiData({ url: '/api/equipment-items', token: this.getToken() });
            if (response.data) {
                this.equipmentList = response.data;
            }
        },
        changeDepartment(){
            this.selectedRole = null;
            this.roleList = this.selectedDepartment.roles;
            this.selectedStaff = null;
            this.staffList = null;
        },
        async roleChange(){
            let response = await getApiData({ url: '/api/hr/staff_lists_by_role/' + this.selectedRole.id + '/department/' + this.selectedDepartment.id, token: this.getToken() });
            if (response.data) {
                if(response.data.data){
                    this.staffList = response.data.data;
                }
                else{
                    this.staffList = response.data;
                }
            }
        },

        btnClickedAddEquipment(){
            if(!this.selectedEquipment){
                this.alertValidationMessage(`Equipment`);
                return 1;
            }
            else if(!this.amount){
                this.alertValidationMessage(`Amount`);
                return 1;
            }
            else if(!this.selectedUomType){
                this.alertValidationMessage(`Uom Type`);
                return 1;
            }
            else{
                this.addEquipment();
            }
        },
        async addEquipment(){
            
            this.selectedEquipmentList.push({
                item_id: this.selectedEquipment.id,
                item_name: this.selectedEquipment.name,
                uom_id: this.selectedEquipment.uom_id,
                uom_name: this.selectedEquipment.item_uom,
                quantity: this.amount,
                uom_conversion: this.selectedEquipment.uom_conversion,
                uom_type: 'uom',
            })
            this.selectedEquipment = null;
            this.amount = null;
            this.selectedUomType = null;
        },
        removeItem(index){
            this.selectedEquipmentList.splice(index, 1);
        },


        btnClickedCreateEquipmentAssing(){
            if(!this.selectedStaff){
                this.alertValidationMessage(`Staff`);
                return 1;
            } 
            else if(this.selectedEquipmentList.length < 1){
                this.alertValidationMessage(`Equipment`);
                return 1;
            }
            else{
                this.createEquipmentAssign();
            }
        },
        async createEquipmentAssign(){
            let formData = new FormData();
            formData.append('staff_id',this.selectedStaff.id);
            formData.append('equipments',JSON.stringify(this.selectedEquipmentList));
            let response = await postApiData({url:`/api/hr/equipment-assignments`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/equipment_assignment`);
            }else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
    },

    watch: {
    },

    async created() {

    },

    mounted() {
        this.getDepartmentList();
        this.getEquipmentList();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
