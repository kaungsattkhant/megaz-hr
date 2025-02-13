<template>
    <div class="px-0">
        <div class="mb-4">
            <p class="text-lg font-semibold font-inter">
                Create Meeting
            </p>
        </div>
        
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white p-8 rounded-md">
            <div class="mb-4 col-span-9 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Title
                </label>
                <input type="text" v-model="title"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date
                </label>
                <input type="datetime-local" v-model="selectedDate"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    From
                </label>
                <input type="datetime-local" v-model="selectedFrom"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    To
                </label>
                <input type="datetime-local" v-model="selectedTo"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Department
                </label>
                <multiselect
                v-model="selectedDepartment"
                :options="departmentList"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Staff"
                label="name"
                track-by="id"
                :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        Department selected</span>
                    </template>
                </multiselect>

                <!-- <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" v-model="selectedDepartment" class="input-ui !text-black"
                    data-te-select-filter="true" @change="departmentChange()" >
                        <option :value="department" v-for="(department, departmentIndex) in departmentList" :key="departmentIndex">
                            {{ department.name }}
                        </option>
                    </select>
                </div> -->
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Role
                </label>
                <multiselect
                v-model="selectedRole"
                :options="roleList"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Staff"
                label="name"
                track-by="id"
                :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        Department selected</span>
                    </template>
                </multiselect>

                <!-- <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" v-model="selectedRole" class="input-ui !text-black"
                    data-te-select-filter="true" @change="roleChange()" >
                        <option :value="role" v-for="(role, roleIndex) in roleList" :key="roleIndex">
                            {{ role.name }}
                        </option>
                    </select>
                </div> -->
            </div>

            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label class="label-form mb-3">Staff</label>
                <multiselect
                v-model="selectedStaff"
                :options="staffList"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Staff"
                label="name"
                track-by="id"
                :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        Staff selected</span>
                    </template>
                </multiselect>
            </div>
            <div class="col-span-3"></div>

            <div class="mb-6 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Place
                </label>
                <input type="text" v-model="selectedPlace"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Chaired By
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select" v-model="selectedChairedBy" class="input-ui !text-black"
                    data-te-select-filter="true">
                        <option :value="item" v-for="(item, itemIndex) in staffList" :key="itemIndex">
                            {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-span-6"></div>



            <div class="mb-4 col-span-6 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Description
                </label>
                <textarea name="" v-model="description"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="6"></textarea>
            </div>
        </div>
        <div>
            <button class="add-btn" @click="createBtnClicked">
                Create Meeting
            </button>
        </div>
    </div>
    
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
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
            staffList:[],
            placeList:[],
            chairedByList:[],

            title:null,
            selectedDate:null,
            selectedFrom:null,
            selectedTo:null,
            selectedDepartment:[],
            selectedRole:[],
            selectedStaff:[],
            selectedPlace:null,
            selectedChairedBy:null,
            description:null,

            typeList:[
                {value:'phone',name:'Phone'},
                {value:'kpay',name:'Kpay'}
            ]
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        

        async getDepartmentList(){
            let url = `/api/departments`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.departmentList = response.data;
            }
        },
        departmentChange(){
            this.getRoleList()
        },
        async getRoleList(){
            // let url = `/api/roles_department/`+this.selectedDepartment.id;
            let url = `/api/roles`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.roleList = response.data;
            }
        },
        roleChange(){
            this.getStaffList()
        },
        async getStaffList(){
            // let url = `/api/staff_by_department/`+this.selectedDepartment.id+`/role/`+this.selectedRole.id;
            let url = `/api/staffs`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.staffList = response.data;
            }
        },

        
        
        async createBtnClicked(){
            if(!this.title){
                this.alertValidationMessage(`MeetingTitle`);
                return 1;
            }
            if(!this.selectedDate){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            if(!this.selectedFrom){
                this.alertValidationMessage(`From`);
                return 1;
            }
            if(!this.selectedTo){
                this.alertValidationMessage(`To`);
                return 1;
            }
            if(!this.selectedPlace){
                this.alertValidationMessage(`place`);
                return 1;
            }
            if(this.selectedDepartment.length <1 && this.selectedRole.length <1 && this.selectedStaff.length <1){
                this.alertValidationMessage(`Particant`);
                console.log('d ',this.selectedDepartment.length);
                console.log('r ',this.selectedRole.length);
                console.log('s ',this.selectedDepartment.length);

                return 1;
            }
            if(!this.selectedChairedBy){
                this.alertValidationMessage(`Chaired By`);
                return 1;
            }
            let meetingType = null;
            if(this.selectedStaff.length > 0){
                meetingType = 'staff_type'
            }
            else {
                if(this.selectedRole.length > 0){
                    meetingType = 'role_type'
                }
                else{
                    meetingType = 'dep_type'
                }
            }
            let formData = new FormData();
            formData.append("title", this.title);
            formData.append("date_time", this.selectedDate);
            // formData.append("phone_number", this.phoneNumber);
            formData.append("from_date", this.selectedFrom);
            formData.append("to_date", this.selectedTo);
            formData.append("place", this.selectedPlace);
            formData.append("chaired_by", this.selectedChairedBy.id);
            formData.append("description", this.description);
            formData.append("meeting_type", meetingType);
            if(this.selectedDepartment.length > 0){
                this.selectedDepartment.forEach((item)=>{
                    formData.append('department[]', item.id);
                });
            }
            if(this.selectedRole.length > 0){
                this.selectedRole.forEach((item)=>{
                    formData.append('role[]', item.id);
                });
            }
            if(this.selectedStaff.length > 0){
                this.selectedStaff.forEach((item)=>{
                    formData.append('staff[]', item.id);
                });
            }
            
            let url = `/api/meetings`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                window.location.replace("/meeting");
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

    created(){
        this.getDepartmentList();
        this.getRoleList();
        this.getStaffList();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
