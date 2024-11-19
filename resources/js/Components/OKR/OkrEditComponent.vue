<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add Objective Key Results
            </p>
        </div>

        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Department
                </label>

                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="selectedDepartmentChange()"
                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black">
                        <option :value="department.id" v-for="(department, index) in departmentList"
                            :key="index"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Role
                </label>

                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>


            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Date Assigned
                </label>
                <!-- <input type="date" v-model="selectedDate" class="input-ui "> -->
                <multiselect v-model="selectedDate" :options="dateList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                :preserve-search="false" placeholder="Select Date" label="name" track-by="value" :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single"
                            v-if="values.length"
                            v-show="!isOpen">{{ values.length }} Date selected</span>
                    </template>
                </multiselect>
                <!-- <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role"
                        data-te-select-filter="true" name="" id="" v-model="selectedDate" class="input-ui !text-black">
                        <option :value="date" v-for="(date, index) in dateList"
                            :key="index"> {{ date }} </option>
                    </select>
                </div> -->
            </div>
            <div class="mb-4 col-span-3">
                <!-- <label for="" class="label-form mb-3">
                    Duration
                </label>
                <input type="number" v-model="duration" class="input-ui "> -->
            </div>
            
            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Objective Name
                </label>
                <input type="text" v-model="objName" class="input-ui ">
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="addObj()">
                    Add Obj
                </button>
            </div>
            <div class="col-span-6"></div>

            <div class="contents" v-for="(obj,index) in objective_key" :key="index">
                <div class="mb-4 col-span-3">
                    <label for="" class="label-form mb-3">
                        Key Result
                    </label>
                    <input type="text" v-model="obj.name" class="input-ui ">
                </div>
                <div class="col-span-3">
                    <label for="" class="label-form mb-3">
                        QKR Point
                    </label>
                    <input type="number" v-model="obj.okr_point" class="input-ui ">
                </div>
                <div class="pl-3">
                    <label for="" class="label-form mb-3">
                        &nbsp;
                    </label>
                    <button class="py-2" @click="deleteObj(index)">
                        <i class="fal fa-trash"></i>
                    </button>
                </div>
                <div class="col-span-5"></div>
            </div>













        </div>
        
        <div>
            <button class="add-btn" @click="btnclickedCreateOkr()">
                Create QKR
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
            dateList: [
                { name: 'Monday', value: 'Monday' },
                { name: 'Tuesday', value: 'Tuesday' },
                { name: 'Wednesday', value: 'Wednesday' },
                { name: 'Thursday', value: 'Thursday' },
                { name: 'Friday', value: 'Friday' },
                { name: 'Saturday', value: 'Saturday' },
                { name: 'Sunday', value: 'Sunday' }
            ],
            selectedDepartment:null,
            selectedRole:null,
            selectedDate:null,
            duration:null,
            objName:null,

            objective_key: [{ name: "" ,okr_point: "" }],  // Start with one input box

            okrDetail:null,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getOkrDetail() {
            this.okrDetail = await getApiData({ url: `/api/objectives/${this.okrId}`, token: this.getToken() });
            
        },
        addDetail(detail){
            this.selectedDepartment = detail.role.department_id;
            this.selectedRole = detail.role;
            this.selectedDate = detail.assigned_days;
            // this.duration = detail.code;
            this.objName = detail.objective_name;
            this.objName = this.menuCategoryList.find(category => category.id === detail.menu_category_id );
            this.objective_key = objective_keys;

            // this.menuLevel.level = this.selectedLevel.id;
            // this.menuLevel.type = this.selectedType.id;
            // this.menuLevel.position = this.selectedPosition;
            // this.menuLevel.role_id = this.selectedRole.id;        
            // this.menuLevel.role_name = this.selectedRole.name;
            // this.menuLevel.duration = this.duration;
            // this.menuLevel.order_time = this.orderTime;
            // this.menuLevel.expected_quantity = this.expectedQuantity;
            


            // this.menuLevel.item_menu.push({
            //     item_id: this.selectedItem.id,
            //     price: price,
            //     name: this.selectedItem.name,
            //     weight: this.amount,
            //     is_make_pack: this.isMakePack,
            //     uom_id: this.selectedUom.id,
            //     uom_name: this.selectedUom.name
            // });

            let sampleMenuLevel = {
                level : null,
                type : null,
                position : null,
                role_name:null,
                role_id:null,
                duration : null,
                order_time : null,
                expected_quantity : null,
                menu_id : null,
                item_menu:[],
            };
            detail.menu_steps.forEach(step => {
                sampleMenuLevel.level = step.level
                sampleMenuLevel.type = step.type
                sampleMenuLevel.role_name = this.roleList.find(role => role.id === step.role_id ).name;
                sampleMenuLevel.role_id = step.role_id
                sampleMenuLevel.duration = step.duration
                sampleMenuLevel.order_time = step.order_time
                sampleMenuLevel.expected_quantity = step.expected_quantity
                sampleMenuLevel.menu_id = step.menu_id
                step.menu_step_item.forEach(item => {
                    sampleMenuLevel.item_menu.push({
                        item_id: item.item_id,
                        menu_step_id: item.menu_step_id,
                        price: item.item.item_prices.price,
                        name: item.item.name,
                        weight: item.weight,
                        uom_id: item.uom_id,
                        uom_name: item.uom.name
                    });
                })
                this.levelTable.push(sampleMenuLevel)
            })    
            this.getMenuList();
            
            // this.subMenu = detail.sub_menus
            detail.sub_menus.forEach(menu => {
                // let menuName = this.menuList.find(item => item.id === menu).name
                this.subMenuList.push({
                    name: menu.name,
                    id: menu.id,
                });
                this.subMenu.push(menu.id);
            })
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
            // console.log(department)
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
        
        

        btnclickedCreateOkr(){
            if (!this.selectedDepartment) {
                this.alertValidationMessage(`Department`);
                return 1;
            }
            else if(!this.selectedRole){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            else if(this.selectedDate.length < 1){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            // else if(!this.duration){
            //     this.alertValidationMessage(`Duration`);
            //     return 1;
            // }
            else if(!this.objName){
                this.alertValidationMessage(`Objective Name`);
                return 1;
            }
            else if(this.objective_key < 1){
                this.alertValidationMessage(`Objective Key`);
                return 1;
            }
            else{
                this.createOkr()
            }
        },
        async createOkr(){
            let selectedDate = [];
            this.selectedDate.forEach(date => {
                selectedDate.push(date.value)
            });
            console.log(selectedDate)
            let formData = new FormData();
            formData.append('objective_name', this.objName);
            formData.append('role_id', this.selectedRole.id);
            formData.append('assigned_days', JSON.stringify(selectedDate));
            formData.append('objective_key', JSON.stringify(this.objective_key));
            let response = await postApiData({ url: '/api/objectives', form_data: formData, token: this.getToken() });
            // if (response.success) {
            //     window.location.replace('/OKR');
            //     console.log('success')
            // }
            if (response.message == "Objective stored successfully!") {
                window.location.replace('/OKR');
                console.log('success')
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }

        },

        addObj() {
            this.objective_key.push({ name: "",okr_point:"" });  // Add a new input box
        },
        deleteObj(index) {
            this.objective_key.splice(index, 1);
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
<style>
    .multiselect__placeholder{
        margin-bottom: 6px!important;
    }
</style>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
