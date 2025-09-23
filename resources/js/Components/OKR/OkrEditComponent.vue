<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Edit Objective Key Results
            </p>
        </div>

        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Department
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black">
                        <option :value="department" v-for="(department, index) in departmentList"
                            :key="index"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Role
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" @change="roleChanged()"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div><div class="col-span-6"></div>
            <div class="mb-6 col-span-6">
                <label for="" class="label-form mb-3">
                    SOP
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select SOP"
                        data-te-select-filter="true" name="" id="" v-model="selectedSop" class="input-ui !text-black">
                        <option :value="sop" v-for="(sop, index) in sopList"
                            :key="index"> {{ sop.sop }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-6"></div>


            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Objective
                </label>
                <input type="text" v-model="objName" class="input-ui ">
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    OKR Point
                </label>
                <input type="number" v-model="selectedOkrPoint" class="input-ui ">
            </div>
            <div class="mb-6 col-span-3">
                <label for="" class="label-form mb-3">
                    Type
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type"
                        data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui !text-black">
                        <option :value="type" v-for="(type, index) in typeList"
                            :key="index"> {{ type.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-3" v-if="selectedType?.value === 'daily'">
                <label for="" class="label-form mb-3">
                    Repetition
                </label>
                <input type="number" v-model="selectedRepition" class="input-ui ">
            </div>
            <div class="col-span-3" v-else></div>

            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Key Result
                </label>
                <input type="text" v-model="key_result" class="input-ui ">
            </div>
            
            <div class="">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="addObj()">
                    Add Obj
                </button>
            </div>
        </div>

        <div class=" bg-white py-4 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="text-left">
                                Key Results
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(keyResult, keyResultIndex) in key_result_list"
                            :key="keyResultIndex">
                            
                            <td class="text-left">
                                {{ keyResult.name }}
                            </td>
                            
                            <td class="">
                                <button @click="deleteKey(keyResultIndex)">
                                    <i class="fal fa-trash  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
        
        <div>
            <button class="add-btn" @click="btnclickedCreateOkr()">
                Edit OKR
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
    props: ["okrId"],
    data() {
        return {
            departmentList:[],
            roleList:[],
            dateList: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
            objName:null,
            selectedDepartment:null,
            selectedRole:null,
            selectedDate:[],
            key_result:null,
            selectedOkrPoint:null,
            duration:null,

            key_result_list:[],
            selected_obj_keys:[],

            okrDetail:null,
            test:[],

            sopList: [],
            selectedSop: null,
            typeList: [
                { name: 'Daily', value: 'daily' },
                { name: 'Occasionally', value: 'occasionally' },
            ],
            selectedType: null,
            selectedRepition: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getOkrDetail() {
            let response = await getApiData({ url: `/api/objectives/${this.okrId}`, token: this.getToken() });
            if (response.data) {
                this.okrDetail = response.data[0];
                if(response.data[0]){
                    this.addDetail(response.data[0])
                }
            }
        },
        async addDetail(detail){
            this.selectedDepartment = this.departmentList.find(dep => dep.id === detail.role.department_id);
            if(this.selectedDepartment){
                this.roleList = this.selectedDepartment.roles;
            }
            this.selectedRole = this.roleList.find(role => role.id === detail.role_id);

            let response = await getApiData({url: `/api/sops?role_id=` + detail.role_id, token: this.getToken()});
            if(response.data){
                // this.sopList = response.data;
                response.data.forEach(sops => {
                    sops.sops.forEach(sop => {
                        this.sopList.push(sop)
                    })
                });
                this.selectedSop = this.sopList.find(sop => sop.id === detail.sop_id);
            }
            this.objName = detail.objective_name;
            this.selectedOkrPoint = detail.okr_point;
            this.selectedType = this.typeList.find(type => type.value === detail.type)
            if(detail.type === 'daily'){
                this.selectedRepition = detail.repetition;
            }
            this.key_result_list = detail.objective_keys;
        },

        async getDepartment(){
            let response = await getApiData({url: `/api/departments`, token: this.getToken()});
            if(response.data){
                this.departmentList = response.data;
            }
        },
        selectedDepartmentChange(){
            this.getRoleList();
        },
        async getRoleList(){
            let response = await getApiData({url: '/api/roles_department/' + this.selectedDepartment , token: this.getToken()});
            if(response.data){
                this.roleList = response.data;
            }
        },
        roleChanged(){
            this.getSopList();
        },
        async getSopList(){
            let response = await getApiData({url: `/api/sops?role_id=` + this.selectedRole.id, token: this.getToken()});
            if(response.data){
                response.data.forEach(sops => {
                    sops.sops.forEach(sop => {
                        this.sopList.push(sop)
                    })
                });
            }
        },
        
        
        
        async getMenuCategoryList(){
            let response = await getApiData({ url: '/api/menu_categories', token: this.getToken() });
            if (response.data) {
                this.menuCategoryList = response.data;
            }
        },
        async getMenuList(){
            let response = await getApiData({ url: '/api/menu_categories/'+ this.selectedMenuCategory.id +'/menus', token: this.getToken() });
            if (response.data) {
                this.menuList = response.data;
            }
        },
        addObj() {
            if (!this.key_result) {
                this.alertValidationMessage(`Key Result`);
                return 1;
            }
            else{
                this.key_result_list.push({ 
                    name: this.key_result,
                    // department_name: this.departmentList.find(depart => depart.id === this.selectedDepartment).name,
                    // role_id:this.selectedRole,
                    // role_name:this.roleList.find(role => role.id === this.selectedRole).name,
                    // okr_point: this.selectedOkrPoint,
                    // assigned_days: this.selectedDate, 
                    // duration: this.duration 
                });
                this.key_result = null;
                // this.selectedOkrPoint = null;
                // this.selectedDate = null;
                // this.duration = null;
            }
        },
        deleteKey(index) {
            this.key_result_list.splice(index, 1);
        },
        

        btnclickedCreateOkr(){
            if(!this.objName){
                this.alertValidationMessage(`Objective Name`);
                return 1;
            }
            else if(!this.selectedRole){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            else if(!this.selectedSop){
                this.alertValidationMessage(`SOP`);
                return 1;
            }
            else if(!this.selectedOkrPoint){
                this.alertValidationMessage(`OKR Point`);
                return 1;
            }
            else if(!this.selectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }

            else if(this.selectedType.value === 'daily' && !this.selectedRepition){
                this.alertValidationMessage(`Repitition`);
                return 1;
            }
            else if(this.key_result_list < 1){
                this.alertValidationMessage(`Objective Key`);
                return 1;
            }
            else{
                this.createOkr()
            }
        },
        async createOkr(){
            let formData = new FormData();
            formData.append('objective_name', this.objName);
            formData.append('okr_point', this.selectedOkrPoint);
            formData.append('type', this.selectedType.value);
            if(this.selectedType.value === 'daily'){
                formData.append('repetition', this.selectedRepition);
            }
            formData.append('role_id', this.selectedRole.id);
            formData.append('sop_id', this.selectedSop.id);
            formData.append('objective_key', JSON.stringify(this.key_result_list));
            formData.append('id', +this.okrId);
            let response = await postApiData({ url: '/api/objectives', form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace('/OKR');
                console.log('success')
            }
            // if (response.message == "Objective stored successfully!") {
            //     window.location.replace('/OKR');
            //     console.log('success')
            // }
            else {
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
        this.getDepartment();
        this.getOkrDetail();
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>
<style scoped>
    .multiselect__placeholder{
        margin-bottom: 6px!important;
    }
</style>

<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
