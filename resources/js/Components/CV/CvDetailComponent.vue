<template>
    <div class="px-0">
        <div class="mb-4 flex justify-between">
            <p class="text-lg font-semibold font-inter text-left">
                CV Detail
            </p>
            <div class="flex justify-end gap-x-4 px-6">
                <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                    @click="btnClickedCancelledCV">
                        Cancel
                </button>
                <button type="button" @click="btnClickedConfirmCV()"
                    class="add-btn focus:outline-none focus:ring-0 ">
                    Confirm
                </button>
            </div>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-4 container-card">

            <div class=" col-span-12 mb-6">
                <p class=" text-xl text-black">
                    Proposed Position
                </p>
            </div>
            
            <div class="col-span-3 rounded-md mb-8">
                <label for="" class="label-form mb-3">
                    Department
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" data-te-select-filter="true"
                        name="" id="" v-model="selectedDepartment" class="input-ui"
                        @change="departmentSelectChanged">
                        <option :value="department" v-for="(department, departmentIndex) in departmentList"
                        :key="departmentIndex"> {{ department.name }} </option>
                    </select>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-8">
                <label class="label-form mb-3">Roles</label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" data-te-select-filter="true"
                        name="" id="" v-model="selectedRoles" @change="roleChange()" class="input-ui">
                        <option :value="role" v-for="(role, roleIndex) in roleList"
                            :key="roleIndex"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-3 rounded-md mb-8">
                <label class="label-form mb-3">Exp</label>
                <input type="text" v-model="selectedExp" placeholder="Exp" class="input-ui">
            </div>
            <div class="col-span-3"></div>
        </div>
        
        <div class="grid !grid-cols-12 gap-x-4 mb-6 container-card !pt-0 !mt-0">
            <div class=" col-span-12 mb-6 mt-4">
                <p class=" text-xl text-black">
                    Personal Information
                </p>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Name
                </label>
                <input type="text" v-model="name" placeholder="Name (Required)" class="input-ui">
            </div>
            <div class="col-span-3 relative">
                <label for="" class="label-form mb-3">
                    Date of Birth
                </label>
                <input type="date" v-model="dob" class="input-ui">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Gender
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Gender" data-te-select-filter="true"
                        name="" id="" v-model="selectedGender" class="input-ui h-[34px]">
                        <option :value="gender" v-for="(gender, genderIndex) in genderList" :key="genderIndex">
                            {{ gender.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    NRC Number
                </label>
                <input type="text" v-model="nrcNumber" placeholder="NRC (Required)" class="input-ui">
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Father's Name
                </label>
                <input type="text" v-model="fatherName" placeholder="Father's Name" class="input-ui">
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Mother's Name
                </label>
                <input type="text" v-model="motherName" placeholder="Mother's Name" class="input-ui">
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Email
                </label>
                <input type="email" v-model="email" placeholder="Email" class="input-ui">
            </div>
            <div class="col-span-3"></div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    State
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select State" data-te-select-filter="true"
                        name="" id="" v-model="selectedState" class="input-ui h-[34px]"
                        @change="stateSelectChanged">
                        <option :value="state" v-for="(state, stateIndex) in stateList"> {{ state.name }} </option>
                    </select>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    City
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select City" data-te-select-filter="true"
                        name="" id="" v-model="selectedCity" class="input-ui h-[34px]"
                        @change="citySelectChanged">
                        <option :value="city" v-for="(city, cityIndex) in cityList"> {{ city.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md row-span-2">
                <label for="" class="label-form mb-3">
                    Address
                </label>
                <textarea name="" v-model="address" class="input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="7"></textarea>
            </div>
            <div class="col-span-3 row-span-2"></div>

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Ph Number
                </label>
                <input type="tel" v-model="phoneNumber" placeholder="Phone (Required)" class="input-ui">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Alt Ph Number
                </label>
                <input type="tel" v-model="altPhoneNumber" placeholder="Alt Phone" class="input-ui">
            </div>
            <!-- <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Skills
                </label>

                <multiselect v-model="selectedSkills" :options="skillList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                    :preserve-search="true" placeholder="Select Skill" label="skill" track-by="id" :preselect-first="true">
                        <template #selection="{ values, search, isOpen }">
                            <span class="multiselect__single"
                                v-if="values.length"
                                v-show="!isOpen">{{ values.length }} skills selected</span>
                        </template>
                    </multiselect>
                    <div class="flex gap-x-2 flex-wrap mt-1">
                        <span class="font-inter after-coma" v-for="skill in selectedSkills">{{ skill.skill }}</span>
                    </div>
            </div> -->

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="nrc-front" class="label-form mb-3">
                    NRC Front
                </label>
                <input type="file" accept="image/png, image/gif, image/jpeg" id="nrc-front" ref="nrc_front_image"
                class="flex flex-col items-center justify-center h-12 w-full bg-gray-100 rounded-md border-2 border-gray-300
                border-dashed transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700"/>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="nrc-back" class="label-form mb-3">
                    NRC Back
                </label>
                <input type="file" accept="image/png, image/gif, image/jpeg" id="nrc-back" ref="nrc_back_image"
                class="flex flex-col items-center justify-center h-12 w-full bg-gray-100 rounded-md border-2 border-gray-300
                border-dashed transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700"/>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="household-registration" class="label-form mb-3">
                    Household Registration
                </label>
                <input type="file" accept="image/png, image/gif, image/jpeg" id="household-registration" ref="household_registration_image"
                class="flex flex-col items-center justify-center h-12 w-full bg-gray-100 rounded-md border-2 border-gray-300
                border-dashed transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700"/>
            </div>

            <div class="col-span-3"></div>

            <hr class="col-span-12 mb-4">

            <div class=" col-span-12 mb-6">
                <p class=" text-xl text-black">
                    Emergency Information
                </p>
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Primary Contact
                </label>
                <input type="text" v-model="primaryName" placeholder="Primary Contact (Required)" class="input-ui">
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Phone Number
                </label>
                <input type="text" v-model="primaryPhone" placeholder="Phone Number (Required)" class="input-ui">
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Relationship
                </label>
                <input type="text" v-model="primaryRelationship" placeholder="Relationship (Required)" class="input-ui">
            </div>
            <div class="col-span-3"></div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Secondary Contact
                </label>
                <input type="text" v-model="secondaryName" placeholder="Secondary Contact (Required)" class="input-ui">
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Phone Number
                </label>
                <input type="text" v-model="secondaryPhone" placeholder="Phone Number (Required)" class="input-ui">
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Relationship
                </label>
                <input type="text" v-model="secondaryRelationship" placeholder="Relationship (Required)"
                    class="input-ui">
            </div>
            <div class="col-span-3"></div>
            <hr class="col-span-12 mb-4">

            <div class=" col-span-12 mb-6">
                <p class=" text-xl text-black">
                    Skillset
                </p>
            </div>
            <div v-if="skillList.length < 1" class="col-span-12">
                <div class="text-left text-gray-500 py-1">
                    <p class="text-base font-semibold">No Skillset to show</p>
                    <p class="text-sm">Choose a department and role to get started.</p>
                  </div>
            </div>
            <div class="col-span-12 flex gap-x-8 mb-8">
                <label class="inline-flex items-center space-x-2" v-for="skill in skillList">
                    <input
                      type="checkbox" :value="skill" v-model="selectedSkills"
                      class="form-checkbox h-4 w-4 text-[#845adf] rounded focus:shadow-none focus:ring-0"
                    />
                    <span class="text-gray-600">{{ skill.skill }}</span>
                </label>
            </div>

        </div>
        
        <div class="px-4 mb-8">
            <button class="add-btn" @click="btnClickedCreateCVForm">
                Edit CV
            </button>
        </div>





        
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { getCurrentDate } from "../../utilities/datetime-helpers";

export default {
    components: {
        Multiselect
    },
    props: ["cvId"],
    data() {
        return {
            genderList: [],
            selectedGender: null,

            departmentList: [],
            selectedDepartment: null,

            roleList: [],
            selectedRoles: [],
            roleIds: [],

            expList: [],
            selectedExp: null,

            stateList: [],
            selectedState: null,
            cityList: [],
            selectedCity: null,

            name: null,
            dob: null,
            phoneNumber: null,
            altPhoneNumber: null,
            email: null,
            fatherName: null,
            motherName: null,
            nrcNumber: null,
            state: null,
            city: null,
            address: null,
            zipCode: null,

            nrcFrontFile: null,
            nrcBackFile: null,
            houseHoldRegistrationFile: null,
            bankAccountNumber: null,

            primaryName: null,
            primaryPhone: null,
            primaryRelationship: null,
            secondaryName: null,
            secondaryPhone: null,
            secondaryRelationship: null,

            skillList:[],
            skillIds:[],
            selectedSkills:[],

            detail: null,
            
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken']),
        async getDetail() {
            let response = await getApiData({ url: `/api/hr/cvs/${this.cvId}`, token: this.getToken() });
            if (response.data) {
                this.detail = response.data;
                this.addDetail(response.data);
            }
        },
        async addDetail(detail){
            this.selectedDepartment = this.departmentList.find(department => department.id == detail.department_id);
            if(this.selectedDepartment){
                this.roleList = this.selectedDepartment.roles;
            }
            if(this.roleList.length > 0){
                this.selectedRoles = this.roleList.find(role => role.id == detail.roles[0].id);
            }
            this.selectedExp = detail.experience;

            this.name = detail.name;
            this.dob = detail.birthdate;
            this.selectedGender = this.genderList.find(gender => gender.id == detail.gender_id);
            this.nrcNumber = detail.nrc_number;
            this.phoneNumber = detail.phone_number;
            this.altPhoneNumber = detail.alt_phone_number;
            this.email = detail.email;
            this.fatherName = detail.father_name;
            this.motherName = detail.mother_name;
            this.selectedState = this.stateList.find(state => state.name == detail.state);
            if(this.selectedState){
                this.state = this.selectedState.name;
                let responsePromise = getApiData({ url: `/api/mmrc/regions/${this.selectedState.id}` });
                responsePromise.then(response => {
                    if (response.data) {
                        this.cityList = response.data.cities;
                        this.selectedCity = this.cityList.find(city => city.name == detail.city);
                        this.city = this.selectedCity.name;
                    }
                })
                // let response = await getApiData({ url: `/api/mmrc/regions/${this.selectedState.id}` });
                // if (response.data) {
                //     this.cityList = response.data.cities;
                //     this.selectedCity = this.cityList.find(city => city.name == detail.city);
                //     this.city = this.selectedCity.name;
                // }
            }
            
            this.address = detail.address;

            this.primaryName = detail.emergency_contacts[0].primary_name;
            this.primaryPhone = detail.emergency_contacts[0].primary_phone;
            this.primaryRelationship = detail.emergency_contacts[0].primary_relationship;
            this.secondaryName = detail.emergency_contacts[0].secondary_name;
            this.secondaryPhone = detail.emergency_contacts[0].secondary_phone;
            this.secondaryRelationship = detail.emergency_contacts[0].secondary_relationship;
            
            if(this.selectedRoles){
                let responsePromise = getApiData({ url: '/api/hr/departments/' + this.selectedDepartment.id + '/roles/' + this.selectedRoles.id + '/skills', token: this.getToken() });
                responsePromise.then(response => {
                    if(response.data){
                        this.skillList = response.data.data;
                        detail.skills.forEach(skill => {
                            let selectSk = this.skillList.find(sk => sk.id == skill.id);
                            this.selectedSkills.push(selectSk)
                        });
                    }
                })
                // const response = await getApiData({ url: '/api/hr/departments/' + this.selectedDepartment.id + '/roles/' + this.selectedRoles.id + '/skills', token: this.getToken() });
                // if(response.data){
                //     this.skillList = response.data.data;
                //     detail.skills.forEach(skill => {
                //         let selectSk = this.skillList.find(sk => sk.id == skill.id);
                //         this.selectedSkills.push(selectSk)
                //     });
                // }
            };

        },


        async getStateList() {
            let response = await getApiData({ url: `/api/mmrc/regions` });
            if (response.data) {
                this.stateList = response.data;
            }
        },
        async stateSelectChanged() {
            this.state = this.selectedState.name;
            let response = await getApiData({ url: `/api/mmrc/regions/${this.selectedState.id}` });
            if (response.data) {
                this.cityList = response.data.cities;
            }
        },
        citySelectChanged() {
            this.city = this.selectedCity.name;
        },

        async getGenderList() {
            const response = await getApiData({ url: '/api/genders', token: this.getToken() });
            if (response.data) {
                this.genderList = response.data;
            }
        },

        async getDepartmentList() {
            const response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },

        async departmentSelectChanged() {
            this.selectedRoles = null;
            this.roleList = [];

            if(this.selectedDepartment){
                this.roleList = this.selectedDepartment.roles;
            }
            // let rolesResponse = await getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() });
            // if (rolesResponse.data) {
            //     this.roleList = rolesResponse.data;
            // }
        },
        async roleChange(){
            const response = await getApiData({ url: '/api/hr/departments/' + this.selectedDepartment.id + '/roles/' + this.selectedRoles.id + '/skills', token: this.getToken() });
            if(response.data){
                this.skillList = response.data.data;
            }
        },
        async btnClickedConfirmCV(item){
            let formData = new FormData();
            formData.append('status', 'confirmed');
            formData.append('confirmed_by', this.getUser().id);
            formData.append('confirmed_at', this.currentTime);
            let response = await postApiData({url:`/api/hr/cvs/` + this.cvId + '/status', form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/cv`);
                // this.getPrimaryList();
            }else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        async btnClickedCancelledCV(item){
            let formData = new FormData();
            formData.append('status', 'cancelled');
            formData.append('cancelled_by', this.getUser().id);
            formData.append('cancelled_at', this.currentTime);
            let response = await postApiData({url:`/api/hr/cvs/` + this.cvId + '/status', form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/cv`);
            }else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },



        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
        btnClickedCreateCVForm() {
            // this.roleIds = [];

            if(this.$refs.nrc_front_image.files[0]){
                this.nrcFrontFile = this.$refs.nrc_front_image.files[0];
            }
            if(this.$refs.nrc_back_image.files[0]){
                this.nrcBackFile = this.$refs.nrc_back_image.files[0];
            }
            if(this.$refs.household_registration_image.files[0]){
                this.houseHoldRegistrationFile = this.$refs.household_registration_image.files[0];
            }
            
            // this.selectedSkills.forEach((skill) => {
            //     this.skillIds.push(skill.id);
            // });
            // this.roleIds = [this.selectedRoles.id];

            
            if (!this.name) {
                this.alertValiationMessage('name');
                return 1;
            }
            if (!this.dob) {
                this.alertValiationMessage('Date of Birth');
                return 1;
            }
            if (!this.selectedGender) {
                this.alertValiationMessage('gender');
                return 1;
            }
            if (!this.nrcNumber) {
                this.alertValiationMessage('Nrc');
                return 1;
            }
            if (!this.phoneNumber) {
                this.alertValiationMessage('phone number');
                return 1;
            }
            if (!this.selectedDepartment) {
                this.alertValiationMessage('department');
                return 1;
            }
            // if (this.roleIds.length < 1) {
            //     this.alertValiationMessage('roles');
            //     return 1;
            // }
            // if (!this.state) {
            //     this.alertValiationMessage('state');
            //     return 1;
            // }
            // if (!this.city) {
            //     this.alertValiationMessage('city');
            //     return 1;
            // }
            // if (!this.address) {
            //     this.alertValiationMessage('address');
            //     return 1;
            // }
            // if (!this.primaryName) {
            //     this.alertValiationMessage('primary name');
            //     return 1;
            // }
            // if (!this.primaryPhone) {
            //     this.alertValiationMessage('primary phone');
            //     return 1;
            // }
            // if (!this.primaryRelationship) {
            //     this.alertValiationMessage('primary relationship');
            //     return 1;
            // }
            // if (!this.secondaryName) {
            //     this.alertValiationMessage('secondary name');
            //     return 1;
            // }
            // if (!this.secondaryPhone) {
            //     this.alertValiationMessage('secondary phone');
            //     return 1;
            // }
            // if (!this.secondaryRelationship) {
            //     this.alertValiationMessage('secondary relationship');
            //     return 1;
            // }
            this.createCVForm();
        },

        async createCVForm() {
            let formData = new FormData();
            formData.append('department_id', this.selectedDepartment.id);
            formData.append('experience', this.selectedExp);
            formData.append('name', this.name);
            formData.append('birthdate', this.dob);
            formData.append('gender_id', this.selectedGender.id);
            formData.append('phone_number', this.phoneNumber);
            formData.append('alt_phone_number', this.altPhoneNumber);
            formData.append('email', this.email);
            formData.append('nrc_number', this.nrcNumber);
            formData.append('father_name', this.fatherName);
            formData.append('mother_name', this.motherName);
            formData.append('state', this.selectedState.name);
            formData.append('city', this.city);
            formData.append('address', this.address);
            // formData.append('password', this.password);

            formData.append('roles', this.roleIds);
            // formData.append('skills', JSON.stringify(this.selectedSkills));
            if(this.selectedSkills.length > 0){
                this.selectedSkills.forEach((skill)=>{
                    formData.append('skills[]', skill.id);
                });
            }

            formData.append('nrc_front_image', this.nrcFrontFile);
            formData.append('nrc_back_image', this.nrcBackFile);
            formData.append('household_registration_image', this.houseHoldRegistrationFile);

            formData.append('primary_name', this.primaryName);
            formData.append('primary_phone', this.primaryPhone);
            formData.append('primary_relationship', this.primaryRelationship);
            formData.append('secondary_name', this.secondaryName);
            formData.append('secondary_phone', this.secondaryPhone);
            formData.append('secondary_relationship', this.secondaryRelationship);
            // formData.append('status', 'received');
            formData.append('roles[]', this.selectedRoles.id);

            let response = await postApiData({ url: '/api/hr/cvs/' + this.cvId, form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace('/cv');
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
    },

    created() {
        this.getGenderList();
        this.getDepartmentList();
        this.getStateList();
        this.getDetail();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
