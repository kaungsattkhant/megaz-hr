<template>
    <div class="px-0">
        <div class="mb-6">
            <p class="text-lg font-semibold font-inter">
                Edit Staff
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white mt-4 pt-4  px-4">

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Name
                </label>
                <input type="text" v-model="name" placeholder="Name (Required)" class="input-ui">
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    Date of Birth
                </label>
                <input type="date" v-model="dob" class="input-ui">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Gender
                </label>
                <select name="" id="" v-model="selectedGender" class="input-ui">
                    <option :value="gender" v-for="(gender, genderIndex) in genderList" :key="genderIndex">
                        {{ gender.name }}
                    </option>
                </select>
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

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Joined Date
                </label>
                <input type="date" v-model="joinedDate"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <!-- <div class="col-span-3"></div> -->

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
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Password
                </label>
                <input type="password" v-model="password" placeholder="Password" autocomplete="new-password" class="input-ui">
            </div>
            <div class="col-span-3"></div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Department
                </label>
                <select name="" id="" v-model="selectedDepartment" class="input-ui"
                    @change="departmentSelectChanged(selectedDepartment)">
                    <option :value="department" v-for="(department, departmentIndex) in departmentList"
                        :key="departmentIndex">
                        {{ department.name }}
                    </option>
                </select>
            </div>

            <!-- <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">Roles</label>
                    <multiselect v-model="selectedRoles" :options="roleList" :multiple="true" :close-on-select="false"
                        :clear-on-select="false" :preserve-search="true" placeholder="Select Roles" label="name"
                        track-by="id" :preselect-first="true">
                        <template #selection="{ values, search, isOpen }">
                            <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                                roles selected</span>
                        </template>
                    </multiselect>
                    <div class="flex gap-x-2 flex-wrap mt-1">
                        <span class="font-inter after-coma" v-for="selectedRole in selectedRoles">{{ selectedRole.name }}</span>
                    </div>
                </div>
            </div> -->
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">Roles {{ selectedRole }}</label>
                    <!-- <multiselect v-model="selectedRoles" :options="roleList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                    :preserve-search="true" placeholder="Select Roles" label="name" track-by="id" :preselect-first="true">
                        <template #selection="{ values, search, isOpen }">
                            <span class="multiselect__single"
                                v-if="values.length"
                                v-show="!isOpen">{{ values.length }} roles selected</span>
                        </template>
                    </multiselect>
                    <div class="flex gap-x-2 flex-wrap mt-1">
                        <span class="font-inter after-coma" v-for="selectedRole in selectedRoles">{{ selectedRole.name }}</span>
                    </div> -->
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]" data-te-select-wrapper-ref>
                    <!-- <select data-te-select-init data-te-select-placeholder="Select Rolsdfdafe" data-te-select-filter="true"
                        name="" id="" v-model="selectedRoles" @change="getSkillByRole(selectedRoles.id)" class="input-ui h-[34px]">
                        <option :value="role" v-for="(role, roleIndex) in roleList"
                        :key="roleIndex"> {{ role.name }} </option>
                    </select> -->

                        <select name="" id="" v-model="selectedRole" class="input-ui" @change="getSkillByRole(selectedRole)">
                            <option :value="role.id" v-for="(role, roleIndex) in roleList"
                                :key="roleIndex">
                                {{ role.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">Authorized Features</label>
                    <multiselect class="text-xs" v-model="selectedFeatures" :options="featureList" :multiple="true"
                        :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                        placeholder="Select Features" label="name" track-by="id" :preselect-first="true">
                        <template #selection="{ values, search, isOpen }">
                            <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                                features selected</span>
                        </template>
                    </multiselect>
                    <div class="flex gap-x-2 flex-wrap mt-1">
                    <span class="font-inter after-coma text-sm" v-for="selectedFeature in selectedFeatures">{{ selectedFeature.name }}</span>
                    </div>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">Inventories</label>
                    <multiselect v-model="selectedInventories" :options="inventories" :multiple="true"
                        :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                        placeholder="Select Inventories" label="name" track-by="id" :preselect-first="true">
                        <template #selection="{ values, search, isOpen }">
                            <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                                inventories selected</span>
                        </template>
                    </multiselect>
                    <div class="flex gap-x-2 flex-wrap mt-1">
                        <span class="font-inter after-coma text-sm" v-for="selectedInventorie in selectedInventories">{{ selectedInventorie.name }}</span>
                    </div>
                </div>

            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">State</label>
                    <multiselect v-model="selectedState" :options="stateList" :close-on-select="true"
                        :clear-on-select="false" :preserve-search="true" placeholder="Select State" label="name"
                        track-by="id" :preselect-first="true" @select="stateSelectChanged(selectedState)"></multiselect>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">State</label>
                    <multiselect v-model="selectedCity" :options="cityList" :close-on-select="true"
                        :clear-on-select="false" :preserve-search="true" placeholder="Select City" label="name"
                        track-by="id" :preselect-first="true" @select="citySelectChanged(selectedCity)"></multiselect>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Zip Code
                </label>
                <input type="text" v-model="zipCode" placeholder="Zip Code" class="input-ui">
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
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
                        <span class="font-inter after-coma" v-for="skill in selectedSkills" :key=skill.id>{{ skill.skill }}</span>
                    </div>

                <!-- <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Skill" data-te-select-filter="true"
                        name="" id="" v-model="selectedSkill" class="input-ui h-[34px]"
                        >
                        <option :value="skill" v-for="(skill, skillIndex) in skillList"
                        :key="skillIndex"> {{ skill.skill }} </option>
                    </select>
                </div> -->
            </div>


            <div class="mb-4 col-span-6 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Address
                </label>
                <textarea name="" v-model="address"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="10"></textarea>
            </div>
            <div class="col-span-6"></div>

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

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Bank Account Number
                </label>
                <input type="text" v-model="bankAccountNumber" placeholder="Bank Account Number" class="input-ui">
            </div>

            <hr class="col-span-12 mb-4">

            <div class=" col-span-12 mb-6">
                <p class=" text-lg text-black">
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


        </div>
        <div>
            <button class="add-btn" @click="updateStaffBtnClicked">
                Update Staff
            </button>
        </div>


        <!-- Department Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_department_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-12 py-4" data-te-modal-body-ref>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Department
                            </label>
                            <input type="text" placeholder="Department" class="input-ui">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 ">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Role Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_role_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Role
                            </label>
                            <input type="text" placeholder="Role" class="input-ui">
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Department
                            </label>
                            <input type="text" placeholder="Department" class="input-ui">
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 ">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select } from "tw-elements";
import { getApiData, postApiData, deleteApiData, putApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { getCurrentDate } from "../../utilities/datetime-helpers";

export default {
    props: ["staffId"],
    components: {
        Multiselect
    },
    data() {
        return {
            staff: null,

            genderList: [],
            selectedGender: null,

            departmentList: [],
            selectedDepartment: null,

            roleList: [],
            selectedRoles: [],
            roleIds: [],

            featureList: [],
            selectedFeatures: [],
            featureIds: [],

            inventories: [],
            selectedInventories: [],
            inventoryIds: [],

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
            joinedDate: getCurrentDate(),
            password: null,
            selectedGender: null,
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
            selectedRole:null
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getSkillByRole(id)
            {
                let response = await getApiData({ url: `/api/roles/${id}/skills`, token: this.getToken() });
                if (response.data) {
                    this.skillList = response.data;
                    this.selectedSkills =[]
                }
            },


        async getStaffDetail() {
            let url = `/api/staffs/${this.staffId}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success == true) {
                this.staff = response.data;
                this.name = this.staff.name;
                this.dob = this.staff.birthdate;
                this.selectedGender = this.staff.gender;
                this.nrcNumber = this.staff.nrc_number
                this.fatherName = this.staff.father_name;
                this.motherName = this.staff.mother_name;
                this.email = this.staff.email;
                this.phoneNumber = this.staff.phone_number;
                this.altPhoneNumber = this.staff.alt_phone_number;
                this.joinedDate = this.staff.joined_date;
                this.zipCode = this.staff.zip_code;
                this.address = this.staff.address;
                this.bankAccountNumber = this.staff.bank_account_number;
                this.password = null;

                if (this.staff.emergency_contacts.length > 0) {
                    this.primaryName = this.staff.emergency_contacts[0].primary_name;
                    this.primaryPhone = this.staff.emergency_contacts[0].primary_phone;
                    this.primaryRelationship = this.staff.emergency_contacts[0].primary_relationship
                    this.secondaryName = this.staff.emergency_contacts[0].secondary_name;
                    this.secondaryPhone = this.staff.emergency_contacts[0].secondary_phone;
                    this.secondaryRelationship = this.staff.emergency_contacts[0].secondary_relationship;
                }

                setTimeout(() => {
                    this.reconstructStaffData();
                }, 1300);
            }
        },

        async reconstructStaffData() {
            if (this.staff) {

                this.departmentList.forEach((department) => {
                    if (this.staff.department_id == department.id) {
                        this.selectedDepartment = department;
                    }
                });
                let response = await getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() });
                if (response.data) {
                    this.roleList = response.data;
                }

                this.selectedRole = this.staff.roles[0].id;

                let responseSkill = await getApiData({url:`/api/roles/${this.staff.roles[0].id}/skills`, token: this.getToken()});
                if(response.data){
                    this.skillList = responseSkill.data;
                }
                this.selectedSkills = this.staff.skills;

                if (this.selectedDepartment.features.length > 0) {
                    this.featureList = this.selectedDepartment.features;
                }
                this.selectedFeatures = this.staff.features;

                if(this.selectedDepartment.inventory){
                    this.inventories.push(this.selectedDepartment.inventory.inventory);
                }

                if (this.staff.inventories.length > 0) {
                    this.selectedInventories = this.staff.inventories;
                }

                this.state = this.staff.state;
                this.city = this.staff.city;
                if (this.stateList.length > 0) {
                    let index = this.stateList.findIndex(state => state.name == this.state);
                    if (index != -1) {
                        this.selectedState = this.stateList[index];
                        let response = await getApiData({ url: `/api/mmrc/regions/${this.selectedState.id}` });
                        if (response.data) {
                            this.cityList = response.data.cities;
                            if (this.city) {
                                let index = this.cityList.findIndex(city => city.name === this.city);
                                if (index != -1) {
                                    this.selectedCity = this.cityList[index];
                                }
                            }
                        }
                    }
                }
            }
        },

        async departmentSelectChanged() {
            this.featureList = [];
            this.inventories = [];
            this.selectedRoles = null;
            this.selectedFeatures = [];
            // this.selectedInventories = [];
            this.areaList = [];
            this.selectedArea = null;
            let rolesResponse = await getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() });
            if (rolesResponse.data) {
                this.roleList = rolesResponse.data;
            }
            if (this.selectedDepartment.features.length > 0) {
                this.featureList = this.selectedDepartment.features;
            }

            this.areaList = this.selectedDepartment.areas;

            // if(this.selectedDepartment.inventory){
            //     this.inventories.push(this.selectedDepartment.inventory.inventory);
            // }
        },

        async getStateList() {
            let response = await getApiData({ url: `/api/mmrc/regions` });
            if (response.data) {
                this.stateList = response.data;
                if (this.state) {
                    let index = this.stateList.findIndex(state => state.name === this.state);
                    if (index != -1) {
                        this.selectedState = this.stateList[index];
                        this.stateSelectChanged(this.selectedState);
                    }
                }
            }
        },

        async stateSelectChanged(selectedState) {
            this.state = selectedState.name;
            this.city = null;
            this.selectedCity = null;
            this.cityList = [];
            let response = await getApiData({ url: `/api/mmrc/regions/${this.selectedState.id}` });
            if (response.data) {
                this.cityList = response.data.cities;
                if (this.city) {
                    let index = this.cityList.findIndex(city => city.name === this.city);
                    if (index != -1) {
                        this.selectedCity = this.cityList[index];
                    }
                }
            }
        },

        citySelectChanged(city) {
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

        async getInventoryList() {
            let url = `/api/inventory/all`;
            // if (departmentId) {
            //     url = `${url}?department_id=${departmentId}`;
            // }
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.inventories = response.data;
            }
        },

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        updateStaffBtnClicked() {
            this.roleIds = [];
            this.featureIds = [];
            this.inventoryIds = [];

            

            // if ((this.selectedDepartment.name == 'Catering' || this.selectedDepartment.name == 'Kitchent') && !this.selectedArea) {
            //     this.alertValiationMessage('area');
            //     return 1;
            // }

            if (this.selectedInventories.length > 0) {
                this.selectedInventories.forEach((inventory) => {
                    this.inventoryIds.push(inventory.id);
                });
            }

            this.selectedSkills.forEach((skill) => {
                this.skillIds.push(skill.id);
            });

            console.log(this.skillIds);


            // if (this.selectedRoles.length > 0) {
            //     this.selectedRoles.forEach((item) => {
            //         this.roleIds.push(item.id);
            //     });
            // }
            this.roleIds = [this.selectedRoles.id];
            // this.roleIds = [this.selectedRole];

            console.log(this.selectedRoles);

            if (this.selectedFeatures.length > 0) {
                this.selectedFeatures.forEach((feature) => {
                    this.featureIds.push(feature.id);
                });
            }

            if(this.$refs.nrc_front_image.files[0]){
                this.nrcFrontFile = this.$refs.nrc_front_image.files[0];
            }

            if(this.$refs.nrc_back_image.files[0]){
                this.nrcBackFile = this.$refs.nrc_back_image.files[0];
            }

            if(this.$refs.household_registration_image.files[0]){
                this.houseHoldRegistrationFile = this.$refs.household_registration_image.files[0];
            }

            if (!this.name) {
                this.alertValiationMessage('name');
                return 1;
            }

            // if (!this.dob) {
            //     this.alertValiationMessage('Date of Birth');
            //     return 1;
            // }

            if (!this.selectedGender) {
                this.alertValiationMessage('gender');
                return 1;
            }

            // if (!this.nrcNumber) {
            //     this.alertValiationMessage('Nrc');
            //     return 1;
            // }

            if (!this.phoneNumber) {
                this.alertValiationMessage('phone number');
                return 1;
            }


            if (!this.joinedDate) {
                this.alertValiationMessage('joined date');
                return 1;
            }

            if (!this.selectedDepartment) {
                this.alertValiationMessage('department');
                return 1;
            }

            if (!this.state) {
                this.alertValiationMessage('state');
                return 1;
            }

            if (!this.city) {
                this.alertValiationMessage('city');
                return 1;
            }

            if (!this.address) {
                this.alertValiationMessage('address');
                return 1;
            }

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

            this.updateStaff();
        },

        async updateStaff() {
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('phone_number', this.phoneNumber);
            if (this.nrcNumber) {
                formData.append('nrc_number', this.nrcNumber);
            }

            if (this.fatherName) {
                formData.append('father_name', this.fatherName);
            }

            if (this.motherName) {
                formData.append('mother_name', this.motherName);
            }

            if (this.email) {
                formData.append('email', this.email);
            }

            if (this.altPhoneNumber) {
                formData.append('alt_phone_number', this.altPhoneNumber);
            }

            if (this.zipCode) {
                formData.append('zip_code', this.zipCode);
            }

            if (this.bankAccountNumber) {
                formData.append('bank_account_number', this.bankAccountNumber);
            }

            if(this.password){
                formData.append('password', this.password);
            }

            formData.append('birthdate', this.dob);
            formData.append('state', this.state);
            formData.append('address', this.address);
            formData.append('city', this.city);
            formData.append('gender_id', this.selectedGender.id);
            formData.append('department_id', this.selectedDepartment.id);

            if (this.inventoryIds.length > 0) {
                formData.append('inventoryIds', JSON.stringify(this.inventoryIds));
            }
            if (this.featureIds.length > 0) {
                formData.append('featureIds', JSON.stringify(this.featureIds));
            }
            // if (this.roleIds.length > 0) {
            //     this.roleIds.forEach(roleId => {
            //         formData.append('roles[]', roleId);
            //     });
            // }

            formData.append('roles', this.roleIds);

            formData.append('skills',JSON.stringify(this.skillIds));

            if (this.password) {
                formData.append('password', this.password);
            }
            formData.append('joined_date', this.joinedDate);

            if(this.nrcFrontFile){
                formData.append('nrc_front_image', this.nrcFrontFile);
            }

            if(this.nrcBackFile){
                formData.append('nrc_back_image', this.nrcBackFile);
            }

            if(this.houseHoldRegistrationFile){
                formData.append('household_registration_image', this.houseHoldRegistrationFile);
            }

            // for emegercy

            formData.append('primary_name', this.primaryName);
            formData.append('primary_phone', this.primaryPhone);
            formData.append('primary_relationship', this.primaryRelationship);
            formData.append('secondary_name', this.secondaryName);
            formData.append('secondary_phone', this.secondaryPhone);
            formData.append('secondary_relationship', this.secondaryRelationship);

            let response = await postApiData({ url: `/api/staffs/${this.staffId}`, form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace('/staff');
            }
            else {
                let message = `Some errors occured`;
                if (response.message) {
                    message = response.message;
                }
                this.$notify({
                    text: message,
                    type: "error"
                });
            }
        },
    },

    created() {
        this.getGenderList();
        this.getDepartmentList();
        this.getStateList();
        this.getStaffDetail();
        this.getInventoryList();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
