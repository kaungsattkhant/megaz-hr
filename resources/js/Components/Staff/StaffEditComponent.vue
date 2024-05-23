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
                <input type="password" v-moel="password" placeholder="Password" class="input-ui">
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

            <div class="col-span-3 rounded-md mb-4 pb-6">
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
                    <pre class="language-json" v-for="selectedRole in selectedRoles"><code>{{ selectedRole.name }}</code></pre>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">Authorized Features</label>
                    <multiselect v-model="selectedFeatures" :options="featureList" :multiple="true"
                        :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                        placeholder="Select Features" label="name" track-by="id" :preselect-first="true">
                        <template #selection="{ values, search, isOpen }">
                            <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                                features selected</span>
                        </template>
                    </multiselect>
                    <pre class="language-json" v-for="selectedFeature in selectedFeatures"><code>{{ selectedFeature.name }}</code></pre>
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
                    <pre class="language-json" v-for="selectedInventorie in selectedInventories"><code>{{ selectedInventorie.name }}</code></pre>
                </div>

            </div>

            <!-- <div class="col-span-3"></div> -->

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

            <div class="col-span-3"></div>

            <div class="mb-4 col-span-6 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Address
                </label>
                <textarea name="" v-model="address"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="10"></textarea>
            </div>
            <div class="col-span-6"></div>

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

            primaryName: null,
            primaryPhone: null,
            primaryRelationship: null,
            secondaryName: null,
            secondaryPhone: null,
            secondaryRelationship: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),


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
                }, 900);
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
                this.selectedRoles = this.staff.roles;

                if (this.selectedDepartment.features.length > 0) {
                    this.featureList = this.selectedDepartment.features;
                }
                this.selectedFeatures = this.staff.features;

                if (this.selectedDepartment.inventories.length > 0) {
                    this.selectedDepartment.inventories.forEach((inventoryData) => {
                        this.inventories.push(inventoryData.inventory);
                    });
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
            this.selectedRoles = [];
            this.selectedFeatures = [];
            this.selectedInventories = [];
            let rolesResponse = await getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() });
            if (rolesResponse.data) {
                this.roleList = rolesResponse.data;
            }
            if (this.selectedDepartment.features.length > 0) {
                this.featureList = this.selectedDepartment.features;
            }
            this.selectedDepartment.inventories.forEach((inventoryData) => {
                this.inventories.push(inventoryData.inventory);
            });
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

        async getInventoryList(departmentId) {
            let url = `/api/get_inventory`;
            if (departmentId) {
                url = `${url}?department_id=${departmentId}`;
            }
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

            if (this.selectedInventories.length > 0) {
                this.selectedInventories.forEach((inventory) => {
                    this.inventoryIds.push(inventory.id);
                });
            }

            if (this.selectedRoles.length > 0) {
                this.selectedRoles.forEach((item) => {
                    this.roleIds.push(item.id);
                });
            }

            if (this.selectedFeatures.length > 0) {
                this.selectedFeatures.forEach((feature) => {
                    this.featureIds.push(feature.id);
                });
            }

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

            if (!this.primaryName) {
                this.alertValiationMessage('primary name');
                return 1;
            }

            if (!this.primaryPhone) {
                this.alertValiationMessage('primary phone');
                return 1;
            }

            if (!this.primaryRelationship) {
                this.alertValiationMessage('primary relationship');
                return 1;
            }

            if (!this.secondaryName) {
                this.alertValiationMessage('secondary name');
                return 1;
            }

            if (!this.secondaryPhone) {
                this.alertValiationMessage('secondary phone');
                return 1;
            }

            if (!this.secondaryRelationship) {
                this.alertValiationMessage('secondary relationship');
                return 1;
            }

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
            if (this.roleIds.length > 0) {
                this.roleIds.forEach(roleId => {
                    formData.append('roles[]', roleId);
                });
            }
            if (this.password) {
                formData.append('password', this.password);
            }
            formData.append('joined_date', this.joinedDate);
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
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
