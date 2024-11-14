<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Add Objective Key Results
            </p>
        </div>

        <div v-for="(input, index) in inputs" :key="index">
            <input v-model="input.value" placeholder="Enter value" />
            <input v-model="input.name" placeholder="Enter name" />
        </div>
      
        <button @click="addInput">Add Input</button>

        <hr>
        <hr class="my-4">
        <div v-for="(t,index) in inputs" class="border-b mb-12 pb-8 flex gap-x-4">
            <p> {{ t.value}} </p> |
            <p> {{ t.name}} </p>
            <button @click="deleteInput(index)"> delete </button>
        </div>




        <div class="grid !grid-cols-12 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Department
                </label>

                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department"
                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black">
                        <option :value="department" v-for="(department, index) in departmentList"
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
                <input type="date" v-model="selectedDate" class="input-ui ">
            </div>
            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Duration
                </label>
                <input type="number" v-model="duration" class="input-ui ">
            </div>
            
            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Objective Name
                </label>
                <input type="text" v-model="test" class="input-ui ">
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

            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Key Result
                </label>
                <input type="text" v-model="test" class="input-ui ">
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    QKR Point
                </label>
                <input type="text" v-model="test" class="input-ui ">
            </div>
            <div class="col-span-6"></div>













        </div>
        
        <div>
            <button class="add-btn" @click="btnclickedCreateCookingPlace()">
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
    data() {
        return {
            departmentList:[],
            roleList:[],

            selectedDepartment:null,
            selectedRole:null,
            selectedDate:null,
            duration:null,

            inputs: [{ value: "",name: "" }],  // Start with one input box

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        addInput() {
            this.inputs.push({ value: "",name:"" });  // Add a new input box
        },
        deleteInput(index) {
            this.inputs.splice(index, 1);
        },
        async getAreaList(departmentId){
            // let response = await getApiData({ url: '/api/areas', token: this.getToken() });
            let response = await getApiData({ url: `/api/areas?department_id=${departmentId}`, token: this.getToken() });
            if (response.data) {
                this.areaList = response.data;
            }
        },
        typeChange(){
            console.log(this.selectedType)
            if(this.selectedType == 'menu'){
                this.getMenuCategoryList();
                this.selectedSkill = null
            }
            else{
                this.getSkillList();
                this.selectedMenuCategory = null;
                this.selectedMenu = null;
            }
        },
        menuCategoryChange(){
            this.getMenuList();
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
        async getSkillList(){
            let response = await getApiData({ url: '/api/skills', token: this.getToken() });
            if (response.data) {
                this.skillList = response.data.data;
            }
        },

        addCookingItem(){
            if(this.selectedType == 'menu'){
                this.cookingPlaceMenuList.push({
                    name: this.selectedMenu.name,
                    id: this.selectedMenu.id,
                    type: this.selectedType,
                });
            }
            else if(this.selectedType == 'skill'){
                this.cookingPlaceSkillList.push({
                    name: this.selectedSkill.skill,
                    id: this.selectedSkill.id,
                    type: this.selectedType,
                });
                console.log(this.selectedType + ' type')
            }

        },
        removeCookingMenuItem(index){
            this.cookingPlaceMenuList.splice(index, 1);
        },
        removeCookingSkillItem(index){
            this.cookingPlaceSkillList.splice(index, 1);
        },

        btnclickedCreateCookingPlace(){
            let availableCookingPlaces = [];
            this.cookingPlaceMenuList.forEach(cpm =>{
                availableCookingPlaces.push({
                    id:cpm.id,
                    type:cpm.type
                })
            })
            this.cookingPlaceSkillList.forEach(cps =>{
                availableCookingPlaces.push({
                    id:cps.id,
                    type:cps.type
                })
            })
            this.createCookingPlace(availableCookingPlaces)
        },
        async createCookingPlace(availableCookingPlaces){
                let formData = new FormData();
                formData.append('availableCookingPlaces', JSON.stringify(availableCookingPlaces));
                formData.append('name', this.name);
                formData.append('area_id', this.selectedArea.id);
                let response = await postApiData({ url: '/api/cooking_places', form_data: formData, token: this.getToken() });
                if (response.success) {
                    window.location.replace('/cooking_places');
                    console.log('success')
                }

            },



        // async getCookingAreaList(departmentId) {
        //     let response = await getApiData({ url: `/api/areas?department_id=${departmentId}`, token: this.getToken() });
        //     if (response.data) {
        //         this.areaList = response.data;
        //     }
        // },


    },

    watch: {
    },

    async created() {
        let response = await getApiData({url: `/api/departments`, token: this.getToken()});
        if(response.data){
            response.data.forEach((department)=>{
                if(department.name == `Kitchen`){
                    this.departmentId = department.id;
                }
            });
        }
        this.getAreaList(this.departmentId);
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
