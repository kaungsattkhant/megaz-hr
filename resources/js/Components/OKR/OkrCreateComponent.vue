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
                    Objective Name
                </label>
                <input type="text" v-model="objName" class="input-ui ">
            </div><div class="col-span-9"></div>

            
            <div class="col-span-10 border-b mt-4 mb-6"></div><div class="col-span-2"></div>
            <div class="mb-4 col-span-3">
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
            <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Role
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <!-- <div class="mb-4 col-span-3"> -->
                <!-- <label for="" class="label-form mb-3">
                    Date Assigned
                </label>
                <multiselect v-model="selectedDate" :options="dateList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                    :preserve-search="false" placeholder="Select Date" label="" :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single"
                            v-if="values.length"
                            v-show="!isOpen">{{ values.length }} Date selected</span>
                    </template>
                </multiselect> -->
                <!-- <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] dark:bg-white !text-black"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role"
                        data-te-select-filter="true" name="" id="" v-model="selectedDate" class="input-ui !text-black">
                        <option :value="date" v-for="(date, index) in dateList"
                            :key="index"> {{ date }} </option>
                    </select>
                </div> -->
            <!-- </div> -->
            <div class="col-span-6"></div>


            
            <!-- <div class="mb-4 col-span-3">
                <label for="" class="label-form mb-3">
                    Duration
                </label>
                <input type="number" v-model="duration" class="input-ui ">
            </div> -->
            
            
            
            

            <div class="contents">
                <div class="mb-4 col-span-3">
                    <label for="" class="label-form mb-3">
                        Key Result
                    </label>
                    <input type="text" v-model="key_result" class="input-ui ">
                </div>
                <div class="col-span-3">
                    <label for="" class="label-form mb-3">
                        OKR Point
                    </label>
                    <input type="number" v-model="selectedOkrPoint" class="input-ui ">
                </div>
                
                <div class="col-span-3">
                    <label for="" class="label-form mb-3">
                        Lead Time ( minutes )
                    </label>
                    <input type="number" v-model="duration" class="input-ui ">
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
        </div>



        <div class=" bg-white py-4 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="">
                        <tr>
                            <th scope="col" class="">
                                Role
                            </th>
                            <th scope="col" class="">
                                Key Results
                            </th>
                            <th scope="col" class="">
                                OKR Points
                            </th>
                            <!-- <th scope="col" class="">
                                Date
                            </th> -->
                            <th scope="col" class="">
                                Lead Time ( minutes )
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(obj, objIndex) in objective_List"
                            :key="objIndex">
                            <td class="">
                                {{ obj.role_name }} ({{ obj.department_name }})
                            </td>
                            <td class="">
                                {{ obj.name }}
                            </td>
                            <td class="">
                                {{ obj.okr_point }}
                            </td>
                            <!-- <td class="">
                                <span class="after:content-[','] last:after:content-[''] pr-1" v-for="day in obj.assigned_days">
                                    {{ day }}
                                </span>
                            </td> -->
                            <td class="">
                                {{ obj.duration }}
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
        
        <div>
            <button class="add-btn" @click="btnclickedCreateOkr()">
                Create OKR
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
            dateList: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
            // dateList: [
            //     { name: 'Monday', value: 'Monday' },
            //     { name: 'Tuesday', value: 'Tuesday' },
            //     { name: 'Wednesday', value: 'Wednesday' },
            //     { name: 'Thursday', value: 'Thursday' },
            //     { name: 'Friday', value: 'Friday' },
            //     { name: 'Saturday', value: 'Saturday' },
            //     { name: 'Sunday', value: 'Sunday' }
            // ],
            selectedDepartment:null,
            selectedRole:null,
            key_result:null,
            selectedOkrPoint:null,
            selectedDate:null,
            duration:null,

            objective_List:[],
            selected_obj_keys:[],
            testKey:[],
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        addObj() {
            if (!this.key_result) {
                this.alertValidationMessage(`Key Result`);
                return 1;
            }
            else if (!this.selectedOkrPoint) {
                this.alertValidationMessage(`OKR Point`);
                return 1;
            }
            else if (!this.selectedRole) {
                this.alertValidationMessage(`Role`);
                return 1;
            }
            // else if (!this.selectedDate) {
            //     this.alertValidationMessage(`Date`);
            //     return 1;
            // }
            else if (!this.duration) {
                this.alertValidationMessage(`Duration`);
                return 1;
            }
            else{
                this.objective_List.push({ 
                    name: this.key_result,
                    department_name:this.selectedDepartment.name,
                    role_id:this.selectedRole.id,
                    role_name:this.selectedRole.name,
                    okr_point: this.selectedOkrPoint,
                    // assigned_days: this.selectedDate, 
                    duration: this.duration
                });  // Add a new input box
                this.selectedDepartment = null;
                this.selectedRole = null;
                this.key_result = null;
                this.selectedOkrPoint = null;
                // this.selectedDate = null;
                this.duration = null;
            }
        },
        deleteObj(index) {
            this.objective_List.splice(index, 1);
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
            let response = await getApiData({url: '/api/roles_department/' + this.selectedDepartment.id , token: this.getToken()});
            if(response.data){
                this.roleList = response.data;
            }
        },
        

        btnclickedCreateOkr(){
            if(!this.objName){
                this.alertValidationMessage(`Objective Name`);
                return 1;
            }
            else if(this.objective_list < 1){
                this.alertValidationMessage(`Objective Key`);
                return 1;
            }
            else{
                this.createOkr()
            }
        },
        async createOkr(){
            // let selectedDate = [];
            // this.selectedDate.forEach(date => {
            //     selectedDate.push(date.value)
            // });
            // console.log(selectedDate)
            
            let formData = new FormData();
            formData.append('objective_name', this.objName);
            // formData.append('role_id', this.selectedRole.id);
            // formData.append('assigned_days', JSON.stringify(selectedDate));
            formData.append('objective_key', JSON.stringify(this.objective_List));
            let response = await postApiData({ url: '/api/objectives', form_data: formData, token: this.getToken() });
            if (response.success) {
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
