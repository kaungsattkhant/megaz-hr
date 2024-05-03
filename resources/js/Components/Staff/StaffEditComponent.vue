<template>
    <div class="px-8">
        <div class="mb-6">
            <p class="text-xl  text-black font-normal">
                Add Staff
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6">

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Name
                </label>
                <input type="text" v-model="name" placeholder="Name (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    Date of Birth
                </label>
                <input type="date" v-model="dob"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Gender
                </label>
                <select name="" id="" v-model="selectedGender"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    <option :value="gender" v-for="(gender, genderIndex) in genderList" :key="genderIndex">
                        {{ gender.name }}
                    </option>
                </select>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    NRC Number
                </label>
                <input type="text" v-model="nrcNumber" placeholder="NRC (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Father's Name
                </label>
                <input type="text" v-model="fathterName" placeholder="Father's Name"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Mother's Name
                </label>
                <input type="text" v-model="motherName" placeholder="Mother's Name"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Email
                </label>
                <input type="email" v-model="email" placeholder="Email"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="col-span-3"></div>

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Ph Number
                </label>
                <input type="tel" v-model="phoneNumber" placeholder="Phone (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Alt Ph Number
                </label>
                <input type="tel" v-model="altPhoneNumber" placeholder="Alt Phone"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Password
                </label>
                <input type="password" v-model="password"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Department
                </label>
                <select name="" id="" v-model="selectedDepartment"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                    @change="departmentSelectChanged">
                    <option :value="department" v-for="(department, departmentIndex) in departmentList"
                        :key="departmentIndex"> {{ department.name }} </option>
                    <!-- <option value="2">Table</option>
                    <option value="3">Security</option> -->
                </select>
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Roles
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Roles" data-te-select-filter="true"
                        name="" id="" multiple v-model="selectedRoles"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        <option :value="role" v-for="(role, roleIndex) in roleList" :key="roleIndex">
                            {{ role.name }}
                        </option>
                        <!-- <option value="2">Manager</option>
                        <option value="3">Waiter</option> -->
                    </select>
                </div>
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Joined Date
                </label>
                <input type="date" v-model="joinedDate"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Inventories
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block" data-te-select-wrapper-ref>
                    <select :disabled="inventories.length < 1" data-te-select-init data-te-select-placeholder="Select Inventories" data-te-select-filter="true"
                        name="" id="" multiple v-model="selectedInventories"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        <option :value="inventory" v-for="(inventory, inventoryIndex) in inventories" :key="inventoryIndex">
                            {{ inventory.name }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- <div class="col-span-3"></div> -->

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    State
                </label>
                <!-- <input type="input" v-model="state" placeholder="State (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
                <select name="" id="" v-model="selectedState" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                @change="stateSelectChanged">
                    <option :value="state" v-for="(state, stateIndex) in stateList"> {{ state.name }} </option>
                </select>
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    City
                </label>
                <!-- <input type="input" v-model="city" placeholder="City (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
                <select name="" id="" v-model="selectedCity" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                @change="citySelectChanged">
                    <!-- <option value="City 1"> City 1 </option> -->
                    <option :value="city" v-for="(city, cityIndex) in cityList"> {{ city.name }} </option>
                </select>
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Zip Code
                </label>
                <input type="text" v-model="zipCode" placeholder="Zip Code"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>

            <div class="col-span-3"></div>

            <div class="mb-4 col-span-6 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
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
                <label for="" class="block text-sm text-black mb-3">
                    Primary Contact
                </label>
                <input type="text" v-model="primaryName" placeholder="Primary Contact (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Phone Number
                </label>
                <input type="text" v-model="primaryPhone" placeholder="Phone Number (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Relationship
                </label>
                <input type="text" v-model="primaryRelationship" placeholder="Relationship (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Secondary Contact
                </label>
                <input type="text" v-model="secondaryName" placeholder="Secondary Contact (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Phone Number
                </label>
                <input type="text" v-model="secondaryPhone" placeholder="Phone Number (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="block text-sm text-black mb-3">
                    Relationship
                </label>
                <input type="text" v-model="secondaryRelationship" placeholder="Relationship (Required)"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>


        </div>
        <div>
            <button class="add-btn" @click="createStaffBtnClicked">
                Create Staff
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
                            <label for="" class="block text-sm text-black mb-3">
                                Department
                            </label>
                            <input type="text" placeholder="Department"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
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
                            <label for="" class="block text-sm text-black mb-3">
                                Role
                            </label>
                            <input type="text" placeholder="Role"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Department
                            </label>
                            <input type="text" placeholder="Department"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
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
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import { getCurrentDate } from "../../utilities/datetime-helpers";

    export default {
        props: ["staffId"],
        data() {
            return {
                staffList: [],
                genderList: [],
                departmentList: [],
                roleList: [],
                stateList: [],
                selectedState: null,
                cityList: [],
                selectedCity: null,

                name: null,
                dob: null,
                phoneNumber: null,
                altPhoneNumber: null,
                email: null,
                fathterName: null,
                motherName: null,
                joinedDate: getCurrentDate(),
                password: null,
                selectedGender: null,
                nrcNumber: null,
                selectedDepartment: null,
                selectedRoles: [],
                roleIds: [],
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

                inventories: [],
                selectedInventories: [],
                inventoryIds: [],
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getStaffDetail(){
                let url = `/api/staffs/${this.staffId}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){

                }
            },

            async getStateList(){
                let response = await getApiData({url: `/api/mmrc/regions`});
                if(response.data){
                    this.stateList = response.data;
                }
            },

            async stateSelectChanged(){
                this.state = this.selectedState.name;
                let response = await getApiData({url: `/api/mmrc/regions/${this.selectedState.id}`});
                if(response.data){
                    this.cityList = response.data.cities;
                }
            },

            citySelectChanged(){
                this.city = this.selectedCity.name;
            },

            async getGenderList(){
                const response = await getApiData({ url: '/api/genders', token: this.getToken() });
                if(response.data){
                    this.genderList = response.data;
                }
            },

            async getDepartmentList(){
                const response = await getApiData({ url: '/api/departments' , token: this.getToken()});
                if(response.data){
                    this.departmentList = response.data;
                }
            },

            async departmentSelectChanged(){
                this.inventories = [];
                let rolesResponse = await getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() });
                if(rolesResponse.data){
                    this.roleList = rolesResponse.data;
                }
                if(this.selectedDepartment.name == 'Inventory'){
                    let inventoriesResponse = await getApiData({url: `/api/inventories`, token: this.getToken()});
                    if(inventoriesResponse.data){
                        this.inventories = inventoriesResponse.data;
                    }
                }
            },

            async getRoleList(){
                const response = await getApiData({ url: '/api/roles', token: this.getToken() });
                if(response.data){
                    this.roleList = response.data;
                }
            },

            alertValiationMessage(field)
            {
                this.$notify({
                    title: `Input validation`,
                    text: `You forgot to privide ${field}, please try again`,
                    type: "warn"
                });
            },

            createStaffBtnClicked(){
                this.roleIds = [];
                this.inventoryIds = [];
                if(this.selectedDepartment.name == 'Inventory' && this.selectedInventories.length < 1){
                    this.alertValiationMessage('inventories');
                    return 1;
                }
                if(this.selectedInventories.length > 0){
                    this.selectedInventories.forEach((inventory)=>{
                        this.inventoryIds.push(inventory.id);
                    });
                }
                this.selectedRoles.forEach((role)=>{
                    this.roleIds.push(role.id);
                });

                if(!this.name){
                    this.alertValiationMessage('name');
                    return 1;
                }

                if(!this.dob){
                    this.alertValiationMessage('Date of Birth');
                    return 1;
                }

                if(!this.selectedGender){
                    this.alertValiationMessage('gender');
                    return 1;
                }

                if(!this.nrcNumber){
                    this.alertValiationMessage('Nrc');
                    return 1;
                }

                if(!this.phoneNumber){
                    this.alertValiationMessage('phone number');
                    return 1;
                }

                if(!this.password){
                    this.alertValiationMessage('password');
                    return 1;
                }


                if(!this.joinedDate){
                    this.alertValiationMessage('joined date');
                    return 1;
                }
                if(!this.selectedDepartment)
                {
                    this.alertValiationMessage('department');
                    return 1;
                }

                if(this.roleIds.length < 1){
                    this.alertValiationMessage('role');
                    return 1;
                }

                if(!this.state){
                    this.alertValiationMessage('state');
                    return 1;
                }

                if(!this.city){
                    this.alertValiationMessage('city');
                    return 1;
                }

                if(!this.address){
                    this.alertValiationMessage('address');
                    return 1;
                }

                if(!this.primaryName){
                    this.alertValiationMessage('primary name');
                    return 1;
                }

                if(!this.primaryPhone){
                    this.alertValiationMessage('primary phone');
                    return 1;
                }

                if(!this.primaryRelationship){
                    this.alertValiationMessage('primary relationship');
                    return 1;
                }

                if(!this.secondaryName){
                    this.alertValiationMessage('secondary name');
                    return 1;
                }

                if(!this.secondaryPhone){
                    this.alertValiationMessage('secondary phone');
                    return 1;
                }

                if(!this.secondaryRelationship){
                    this.alertValiationMessage('secondary relationship');
                    return 1;
                }


                // console.log(this.roleIds);
                // alert(`name = ${this.name}`);
                // alert(`phone num = ${this.phoneNumber}`);
                // alert(`password = ${this.password}`);
                // alert(`nrc = ${this.nrcNumber}`);
                // alert(`department = ${this.selectedDepartment.name}`);
                // alert(`role = ${this.selectedRole.name}`);
                // alert(`gender = ${this.selectedGender.name}`);
                // alert(`address = ${this.address}`);
                this.createStaff();
            },

            async createStaff()
            {
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('phone_number', this.phoneNumber);
                if(this.nrcNumber){
                    formData.append('nrc_number', this.nrcNumber);
                }


                if(this.fathterName)
                {
                    formData.append('father_name',this.fathterName);
                }

                if(this.motherName)
                {
                    formData.append('mother_name',this.motherName);
                }

                if(this.email)
                {
                    formData.append('email',this.email);
                }

                if(this.altPhoneNumber)
                {
                    formData.append('alt_phone_number',this.altPhoneNumber);
                }

                if(this.zipCode)
                {
                    formData.append('zip_code',this.zipCode);
                }
                formData.append('birthdate',this.dob);
                formData.append('state',this.state);
                formData.append('address',this.address);
                formData.append('city',this.city);
                formData.append('gender_id', this.selectedGender.id);
                formData.append('department_id', this.selectedDepartment.id);
                if(this.inventoryIds.length > 0){
                    formData.append('inventoryIds', JSON.stringify(this.inventoryIds));
                }
                formData.append('password', this.password);
                formData.append('roles', this.roleIds);
                formData.append('joined_date',this.joinedDate);

                // for emegercy

                formData.append('primary_name',this.primaryName);
                formData.append('primary_phone',this.primaryPhone);
                formData.append('primary_relationship',this.primaryRelationship);
                formData.append('secondary_name',this.secondaryName);
                formData.append('secondary_phone',this.secondaryPhone);
                formData.append('secondary_relationship',this.secondaryRelationship);

                let response = await postApiData({url: '/api/staffs', form_data: formData, token: this.getToken()});
                if(response.success){
                    window.location.replace('/staff');
                }
                else{
                    let message = `Some errors occured`;
                    if(response.message){
                        message = response.message;
                    }
                    this.$notify({
                        text: message,
                        type: "error"
                    });
                }
            },
        },

        created(){
            this.getGenderList();
            this.getDepartmentList();
            // this.getRoleList();
            this.getStateList();
        },

        mounted(){
            initTE({ Modal,Select, Ripple });
        }
    }
</script>
