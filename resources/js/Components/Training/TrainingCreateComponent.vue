<template>
    <div class="px-0">
        <div class="mb-4">
            <p class="text-lg font-semibold font-inter">
                Create Training
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
                @close="departmentChange"
                group-values="data" 
                group-label="title"
                 :group-select="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                :custom-label="selectedDepartment.name"
                placeholder="Select Department"
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
                group-values="roles" 
                group-label='title'
                :group-select="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Role"
                label="name"
                track-by="id"
                :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        Role selected</span>
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
                    Trained By
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select" v-model="selectedTrainedBy" class="input-ui !text-black"
                    data-te-select-filter="true">
                        <option :value="item" v-for="(item, itemIndex) in staffList" :key="itemIndex">
                            {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Type
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select" v-model="selectedType" class="input-ui !text-black"
                    data-te-select-filter="true">
                        <option :value="type" v-for="(type, typeIndex) in typeList" :key="typeIndex">
                            {{ type.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div>
                <label for="" class="block text-sm text-black mb-3">
                    &nbsp;
                </label>
                <button data-te-toggle="modal" data-te-target="#add_type_modal" class=" py-2">
                    <i class="fal fa-plus  pr-3"></i>
                </button>
            </div>
            <div class="col-span-2"></div>



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
                Create Training
            </button>
        </div>
    </div>


    <!-- type Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="add_type_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Add Type
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close" id="close_type_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Type
                        </label>
                        <input type="text" placeholder="Session" v-model="newType" class="input-ui">
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" @click="addType()"
                        class="add-btn focus:outline-none focus:ring-0 ">
                        Create
                    </button>
                </div>
            </div>
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
            allDepartmentList:[],
            roleList:[],
            allRoleList:[],
            staffList:[],
            placeList:[],
            trainedByList:[],
            typeList:[],

            title:null,
            selectedDate:null,
            selectedFrom:null,
            selectedTo:null,
            selectedDepartment:[],
            selectedRole:[],
            selectedStaff:[],
            selectedPlace:null,
            selectedTrainedBy:null,
            selectedType:null,
            description:null,
            newType:null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        

        async getDepartmentList(){
            let url = `/api/departments`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.departmentList = [{
                    title: 'all',
                    data: response.data
                }] 
                this.getRoleList();
            }
        },
        departmentChange(){
            if(this.selectedDepartment.length > 0){
                this.roleList = [];
                this.selectedRole = [];
                this.selectedDepartment.forEach(department => {
                    this.roleList.push({
                        title: department.name,
                        roles:department.roles
                    })
                });
                
            }
            else{
                this.getRoleList();
            }
            // this.selectedStaff = this.staffList
            // this.selectedDepartment.forEach(department => {
                //     department.roles.forEach(role => {
                //         this.roleList.push(role)
                //         console.log(role)
                //     });
                // });
            
        },
        async getRoleList(){
            this.roleList = [];
            this.departmentList[0].data.forEach(department => {
                this.roleList.push({
                    title: department.name,
                    roles:department.roles
                })
            });
            // let url = `/api/roles`;
            // let response = await getApiData({url: url, token: this.getToken()});
            // if(response.data){
            //     this.roleList = response.data;
            // }
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
        async getTypeList(){
            let url = `/api/noti_types?type=training`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.typeList = response.data;
            }
        },
        async addType(){
            if(!this.newType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            let formData = new FormData();
            formData.append("name", this.newType);
            formData.append("typeable_type", 'training');
            let url = `/api/noti_types`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.getTypeList();
                document.getElementById('close_type_modal').click();
                this.newType = null;
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
            if(!this.selectedTrainedBy){
                this.alertValidationMessage(`Trained By`);
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
            formData.append("trained_by", this.selectedTrainedBy.id);
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
            
            let url = `/api/trainings`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                window.location.replace("/training");
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
        // this.getRoleList();
        this.getStaffList();
        this.getTypeList();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
