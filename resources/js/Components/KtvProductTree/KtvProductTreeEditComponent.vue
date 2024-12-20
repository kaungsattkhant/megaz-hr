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
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Room"
                        data-te-select-filter="true" name="" id="" v-model="selectedRoom" class="input-ui !text-black">
                        <option :value="room.id" v-for="(room, index) in roomList"
                            :key="index"> {{ room.name }} </option>
                    </select>
                </div>
            </div>
        </div>

        <div class=" bg-white p-8 rounded-md shadow-md mb-8">
            <div class="grid !grid-cols-12 gap-x-8 mb-5">
                <div class=" col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Department
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Objective" @change="changedDepartment()"
                            data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black">
                            <option :value="department" v-for="(department, index) in departmentList"
                                :key="index"> {{ department.name }} </option>
                        </select>
                    </div>
                </div>
                <div class=" col-span-3 rounded-md">
                    <label for="" class="label-form mb-3">
                        Objective Name
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                        data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Objective"
                            data-te-select-filter="true" name="" id="" v-model="selectedObjective" class="input-ui !text-black">
                            <option :value="objective" v-for="(objective, index) in objectiveList"
                                :key="index"> {{ objective.name }} </option>
                        </select>
                    </div>
                </div>
                <div class=" mb-4">
                    <label for="" class="label-form mb-3">
                        &nbsp;
                    </label>
                    <button class="add-btn py-[8px]" @click="addObj()">
                        Add
                    </button>
                </div><div class="col-span-5"></div>
            </div>
            <div class="">
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
                            <tr class="" v-for="(obj,objIndex) in obj_list">
                                <td class="">
                                    {{ obj.name }}
                                </td>
                                <td class="">
                                    {{ obj.duration }}
                                </td>
                                <td class="">
                                    {{ obj.role_name }}
                                </td>
                                <td class="">
                                    <button @click="deleteObj(objIndex)">
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
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Item"
                        data-te-select-filter="true" name="" id="" v-model="selectedItem" class="input-ui !text-black">
                        <option :value="item" v-for="(item, index) in itemList"
                            :key="index"> {{ item.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Amount
                </label>
                <input type="number" v-model="amount" class="input-ui mb-2">
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="addItem()">
                    Add
                </button>
            </div>
            <div class="col-span-12">
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    Item Name
                                </th>
                                <th scope="col" class="">
                                    Amount
                                </th>
                                <th scope="col" class="">
    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="" v-for="(item,itemIndex) in item_list">
                                <td class="">
                                    {{ item.item_name }}
                                </td>
                                <td class="">
                                    {{ item.quantity }}
                                </td>
                                <td class="">
                                    <button @click="deleteItem(itemIndex)">
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
            <button class="add-btn" @click="btnclickedCreate()">
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
    props: ["productTreeId"],
    data() {
        return {
            roomList:[],
            objectiveList:[], // get objective list
            obj_list:[],  // store obj
            itemList:[],
            item_list:[],

            selectedRoom:null,
            selectedObjective:null,
            selectedItem:null,
            amount:null,

            productTreeDetail:null,
            inputs: [{ value: "",name: "" }],  // Start with one input box

            departmentList:[],
            selectedDepartment:null,
            url_department:null,

        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getProductTreeDetail() {
            let response = await getApiData({ url: `/api/ktv/objective_trees/${this.productTreeId}`, token: this.getToken() });
            if (response.data) {
                this.productTreeDetail = response.data;
                if(response.data){
                    this.addDetail(response.data)
                }
            }
        },
        addDetail(detail){
            this.selectedRoom = detail.entity_id;
            detail.ktv_objectives.forEach(obj => {
                this.obj_list.push({ 
                    // id: obj.id,
                    name: obj.objective_key.name,
                    role_id: obj.objective_key.role_id,
                    role_name: obj.objective_key.role.name, 
                    duration: obj.objective_key.duration,
                    obj_id:obj.objective_key_id,
                    // id:this.testList.find(test => test.objective_name === this.selectedObjective.objective_name ).id 
                }); 
            });

            detail.ktv_items.forEach(item => {
                this.item_list.push({ 
                    // id:item.id,
                    item_name: item.item.name,
                    item_id: item.item_id,
                    quantity: item.quantity, 
                }); 
            });

            
            
        },
        async getRoomList(){
            let response = await getApiData({url: `/api/ktv/entity_room`, token: this.getToken()});
            if(response.data){
                this.roomList = response.data;
            }
        },

        async getDepartmentList(){
            const response = await getApiData({ url: '/api/departments' , token: this.getToken() });
            if(response.data){
                    this.departmentList = response.data;
                    console.log('department')
            }
        },
        changedDepartment(){
            this.url_department = '/' + this.selectedDepartment.id;
            this.getObjectiveList(this.url_department)
        },

        async getObjectiveList(department){
            let response = await getApiData({url: `/api/ktv/objectives` + department, token: this.getToken()});
            if(response.data){
                this.objectiveList = response.data;
            }
        },
        async test(){
            let response = await getApiData({url: `/api/objectives`, token: this.getToken()});
            if(response.data){
                this.testList = response.data.data;
            }
        },
        async getItemList(){
            let response = await getApiData({url: `/api/items`, token: this.getToken()});
            if(response.data){
                this.itemList = response.data;
            }
        },
        

        btnclickedCreate(){
            // if (!this.selectedRole) {
            //     this.alertValidationMessage(`Department`);
            //     return 1;
            // }
            // else if(!this.selectedRole){
            //     this.alertValidationMessage(`Role`);
            //     return 1;
            // }
            // else if(!this.objName){
            //     this.alertValidationMessage(`Objective Name`);
            //     return 1;
            // }
            // else if(this.objective_key < 1){
            //     this.alertValidationMessage(`Objective Key`);
            //     return 1;
            // }
            // else{
            //     this.createProductTree()
            // }
            this.createProductTree();
        },
        async createProductTree(){
            let obj_list = [];
            this.obj_list.forEach(objId => {
                obj_list.push(objId.obj_id)
            });
            let item_list = [];
            this.item_list.forEach(item => {
                item_list.push({
                    item_id:item.item_id,
                    quantity:item.quantity
                })
            });
            
            let formData = new FormData();
            formData.append('entity_id', this.selectedRoom);
            formData.append('objective_keys', JSON.stringify(obj_list));
            formData.append('items', JSON.stringify(item_list));
            let response = await postApiData({ url: '/api/ktv/objective_trees/' + this.productTreeId, form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace('/ktv_product_tree');
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
            if (!this.selectedObjective) {
                this.alertValidationMessage(`Objective`);
                return 1;
            }
            else{
                this.obj_list.push({ 
                    name: this.selectedObjective.name,
                    role_id: this.selectedObjective.role_id,
                    role_name: this.selectedObjective.role_name, 
                    duration: this.selectedObjective.duration,
                    obj_id:this.selectedObjective.id,
                    // id:this.testList.find(test => test.objective_name === this.selectedObjective.objective_name ).id
                }); 
                this.selectedObjective = null;
            }
        },
        deleteObj(index) {
            this.obj_list.splice(index, 1);
        },
        addItem() {
            if (!this.selectedItem) {
                this.alertValidationMessage(`Item`);
                return 1;
            }
            else if (!this.amount) {
                this.alertValidationMessage(`Amount`);
                return 1;
            }
            else{
                this.item_list.push({ 
                    item_name: this.selectedItem.name,
                    item_id: this.selectedItem.id,
                    quantity: this.amount, 
                }); 
                this.selectedItem = null;
                this.amount = null;
            }
        },
        deleteItem(index) {
            this.item_list.splice(index, 1);
        },




        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
    },



    async created() {
        this.getProductTreeDetail();
        this.getRoomList();
        this.getDepartmentList();
        this.getItemList();
        this.test();
    },

    mounted() {
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
