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
                <div class="mt-1" v-if="nameInputError">
                    <span class="px-1 text-red-600 text-sm">{{ nameInputError }} *</span>
                </div>
            </div>
            <div class="col-span-3">
                <label for="" class="label-form mb-3">
                    Date of Birth
                </label>
                <!-- <input type="date" v-model="dob" class="input-ui"> -->
                 <flat-pickr
                    v-model="dob"
                    :config="{ dateFormat: 'd/m/Y' }"
                    class="input-ui"
                    placeholder="dd/mm/yyyy"
                />
                <div class="mt-1" v-if="dateOfBirthInputError">
                    <span class="px-1 text-red-600 text-sm">{{ dateOfBirthInputError }} *</span>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Gender
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Gender" data-te-select-filter="true"
                        name="" id="" v-model="selectedGender" class="input-ui">
                        <option :value="gender" v-for="(gender, genderIndex) in genderList" :key="genderIndex">
                            {{ gender.name }}
                        </option>
                    </select>
                </div>
                <div class="mt-1" v-if="genderInputError">
                    <span class="px-1 text-red-600 text-sm">{{ genderInputError }} *</span>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                &nbsp;
                <!-- <label for="" class="label-form mb-3">
                    NRC Number
                </label>
                <input type="text" v-model="nrcNumber" placeholder="NRC (Required)" class="input-ui"> -->
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    NRC Code
                </label>
                <div class="flex">
                    <multiselect v-model="selectedNrcCode"
                    :options="[1,2,3,4,5,6,7,8,9,10,11,12,13,14]"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="false"
                    placeholder="Select NRC Code"
                    :preselect-first="false"
                    @select="getNrcTownships">
                    </multiselect>
                    <div class="mx-2 text-lg text-4xl text-gray-500">
                        /
                    </div>
                </div>
                <div class="mt-1" v-if="nrcCodeError">
                    <span class="px-1 text-red-600 text-sm">{{ nrcCodeError }} *</span>
                </div>
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    NRC Townships
                </label>
                <multiselect v-model="selectedNrcTownship"
                :options="nrcTownships"
                :multiple="false"
                :close-on-select="true"
                :clear-on-select="false"
                :preserve-search="false"
                placeholder="Select NRC Township"
                track-by="id"
                label="name_en"
                :preselect-first="false">
                </multiselect>
                <div class="mt-1" v-if="nrcTownshipError">
                    <span class="px-1 text-red-600 text-sm">{{ nrcTownshipError }} *</span>
                </div>
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    NRC Type
                </label>
                <multiselect v-model="selectedNrcType"
                :options="['N','E','P']"
                :multiple="false"
                :close-on-select="true"
                :clear-on-select="false"
                :preserve-search="false"
                placeholder="Select NRC Type"
                :preselect-first="false">
                </multiselect>
                <div class="mt-1" v-if="nrcTypeError">
                    <span class="px-1 text-red-600 text-sm">{{ nrcTypeError }} *</span>
                </div>
            </div>

            <div class="col-span-3 mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    NRC Number
                </label>
                <input type="text" v-model="nrcNumber" placeholder="NRC (Required)" class="input-ui">
                <div class="mt-1" v-if="nrcInputError">
                    <span class="px-1 text-red-600 text-sm">{{ nrcInputError }} *</span>
                </div>
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
                <!-- <input type="date" v-model="joinedDate" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
                 <flat-pickr
                    v-model="joinedDate"
                    :config="{ dateFormat: 'd/m/Y' }"
                    class="input-ui"
                    placeholder="dd/mm/yyyy"
                />
                <div class="mt-1" v-if="joinedDateInputError">
                    <span class="px-1 text-red-600 text-sm">{{ joinedDateInputError }} *</span>
                </div>
            </div>

            <!-- <div class="col-span-3"></div> -->

            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Ph Number
                </label>
                <input type="tel" v-model="phoneNumber" placeholder="Phone (Required)" class="input-ui">
                <div class="mt-1" v-if="phoneNumberInputError">
                    <span class="px-1 text-red-600 text-sm">{{ phoneNumberInputError }} *</span>
                </div>
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
                <div class="mt-1" v-if="departmentInputError">
                    <span class="px-1 text-red-600 text-sm">{{ departmentInputError }} *</span>
                </div>
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
                        <select name="" id="" v-model="selectedRole" class="input-ui" @change="getSkillByRole(selectedRole)">
                            <option :value="role.id" v-for="(role, roleIndex) in roleList"
                                :key="roleIndex">
                                {{ role.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mt-1" v-if="rolesInputError">
                        <span class="px-1 text-red-600 text-sm">{{ rolesInputError }} *</span>
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
                    <div class="mt-1" v-if="featuresInputError">
                        <span class="px-1 text-red-600 text-sm">{{ featuresInputError }} *</span>
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
                    <div class="mt-1" v-if="stateInputError">
                        <span class="px-1 text-red-600 text-sm">{{ stateInputError }} *</span>
                    </div>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">City</label>
                    <multiselect v-model="selectedCity" :options="cityList" :close-on-select="true"
                        :clear-on-select="false" :preserve-search="true" placeholder="Select City" label="name"
                        track-by="id" :preselect-first="true" @select="citySelectChanged(selectedCity)"></multiselect>
                    <div class="mt-1" v-if="cityInputError">
                        <span class="px-1 text-red-600 text-sm">{{ cityInputError }} *</span>
                    </div>
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
                <div class="mt-1" v-if="addressInputError">
                    <span class="px-1 text-red-600 text-sm">{{ addressInputError }} *</span>
                </div>
            </div>
            <div class="col-span-6"></div>

            <div v-if="staff" class="contents">
                <div class="contents" v-if="staff.staff_certifications.length > 0">
                    <div class="col-span-3 rounded-md mb-4 pb-6">
                        <label for="nrc-front" class="label-form mb-3">
                            Certification 1
                        </label>
                        <img v-if="staff.staff_certifications[0]" :src="staff.staff_certifications[0].certificate_file_url" alt="Old Certification 1 Preview" class="mt-2 h-24" />
                    </div>

                    <div class="col-span-3 rounded-md mb-4 pb-6">
                        <label for="nrc-front" class="label-form mb-3">
                            Certification 2
                        </label>
                        <img v-if="staff.staff_certifications[1]" :src="staff.staff_certifications[1].certificate_file_url" alt="Old Certification 2 Preview" class="mt-2 h-24" />
                    </div>

                    <div class="col-span-3 rounded-md mb-4 pb-6">
                        <label for="nrc-front" class="label-form mb-3">
                            Certification 3
                        </label>
                        <img v-if="staff.staff_certifications[2]" :src="staff.staff_certifications[2].certificate_file_url" alt="Old Certification 3 Preview" class="mt-2 h-24" />
                    </div>
                </div>

                <div class="col-span-3"></div>
            </div>


            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="nrc-front" class="label-form mb-3">
                    NRC Front
                </label>
                <input type="file" accept="image/png, image/gif, image/jpeg" id="nrc-front" ref="nrc_front_image"
                class="flex flex-col items-center justify-center h-12 w-full bg-gray-100 rounded-md border-2 border-gray-300
                border-dashed transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700"
                @change="onNrcFrontChange"/>
                <img v-if="nrcFrontPreview" :src="nrcFrontPreview" alt="NRC Front Preview" class="mt-2 h-24" />
                <div class="mt-1" v-if="nrcFrontFileError">
                    <span class="px-1 text-red-600 text-sm">{{ nrcFrontFileError }} *</span>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="nrc-back" class="label-form mb-3">
                    NRC Back
                </label>
                <input type="file" accept="image/png, image/gif, image/jpeg" id="nrc-back" ref="nrc_back_image"
                class="flex flex-col items-center justify-center h-12 w-full bg-gray-100 rounded-md border-2 border-gray-300
                border-dashed transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700"
                @change="onNrcBackChange"/>
                <img v-if="nrcBackPreview" :src="nrcBackPreview" alt="NRC Back Preview" class="mt-2 h-24"/>
                <div class="mt-1" v-if="nrcBackFileError">
                    <span class="px-1 text-red-600 text-sm">{{ nrcBackFileError }} *</span>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="household-registration" class="label-form mb-3">
                    Household Registration
                </label>
                <input type="file" accept="image/png, image/gif, image/jpeg" id="household-registration" ref="household_registration_image"
                class="flex flex-col items-center justify-center h-12 w-full bg-gray-100 rounded-md border-2 border-gray-300
                border-dashed transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700"
                @change="onHouseHoldRegistrationChange"/>
                <img v-if="houseHoldRegistrationPreview" :src="houseHoldRegistrationPreview" alt="Household Registration Preview" class="mt-2 h-24"/>
                <div class="mt-1" v-if="houseHoldRegistrationFileError">
                    <span class="px-1 text-red-600 text-sm">{{ houseHoldRegistrationFileError }} *</span>
                </div>
            </div>

            <div class="col-span-3"></div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Bank Name
                </label>
                <multiselect v-model="selectedBank"
                :options="banksList"
                :multiple="false"
                :close-on-select="true"
                :clear-on-select="false"
                :preserve-search="false"
                placeholder="Select Bank"
                track-by="id"
                label="name"
                :preselect-first="false">
                </multiselect>
                <div class="mt-1" v-if="bankSelectError">
                    <span class="px-1 text-red-600 text-sm">{{ bankSelectError }} *</span>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Bank Account Number
                </label>
                <input type="text" v-model="bankAccountNumber" placeholder="Bank Account Number" class="input-ui">
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
                <div class="mt-1" v-if="primaryNameInputError">
                    <span class="px-1 text-red-600 text-sm">{{ primaryNameInputError }} *</span>
                </div>
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Phone Number
                </label>
                <input type="text" v-model="primaryPhone" placeholder="Phone Number (Required)" class="input-ui">
                <div class="mt-1" v-if="primaryPhoneInputError">
                    <span class="px-1 text-red-600 text-sm">{{ primaryPhoneInputError }} *</span>
                </div>
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Relationship
                </label>
                <input type="text" v-model="primaryRelationship" placeholder="Relationship (Required)" class="input-ui">
                <div class="mt-1" v-if="primaryRelationshipInputError">
                    <span class="px-1 text-red-600 text-sm">{{ primaryRelationshipInputError }} *</span>
                </div>
            </div>
            <div class="col-span-3"></div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Secondary Contact
                </label>
                <input type="text" v-model="secondaryName" placeholder="Secondary Contact (Required)" class="input-ui">
                <div class="mt-1" v-if="secondaryNameInputError">
                    <span class="px-1 text-red-600 text-sm">{{ secondaryNameInputError }} *</span>
                </div>
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Phone Number
                </label>
                <input type="text" v-model="secondaryPhone" placeholder="Phone Number (Required)" class="input-ui">
                <div class="mt-1" v-if="secondaryPhoneInputError">
                    <span class="px-1 text-red-600 text-sm">{{ secondaryPhoneInputError }} *</span>
                </div>
            </div>
            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Relationship
                </label>
                <input type="text" v-model="secondaryRelationship" placeholder="Relationship (Required)"
                    class="input-ui">
                <div class="mt-1" v-if="secondaryRelationshipInputError">
                    <span class="px-1 text-red-600 text-sm">{{ secondaryRelationshipInputError }} *</span>
                </div>
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
import { getCurrentDate, formatDate } from "../../utilities/datetime-helpers";
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

export default {
    props: ["staffId"],
    components: {
        Multiselect,
        FlatPickr
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
            nrcFrontPreview: null,
            nrcBackFile: null,
            nrcBackPreview: null,
            houseHoldRegistrationFile: null,
            houseHoldRegistrationPreview: null,

            nrcFrontFileError: null,
            nrcBackFileError: null,
            houseHoldRegistrationFileError: null,

            primaryName: null,
            primaryPhone: null,
            primaryRelationship: null,
            secondaryName: null,
            secondaryPhone: null,
            secondaryRelationship: null,

            skillList:[],
            skillIds:[],
            selectedSkills:[],
            selectedRole:null,

            inventoryInputError: null,
            nameInputError: null,
            dateOfBirthInputError: null,
            genderInputError: null,
            nrcInputError: null,
            phoneNumberInputError: null,
            passwordInputError: null,
            joinedDateInputError: null,
            departmentInputError: null,
            rolesInputError: null,
            featuresInputError: null,
            stateInputError: null,
            cityInputError: null,
            addressInputError: null,
            primaryNameInputError: null,
            primaryPhoneInputError: null,
            primaryRelationshipInputError: null,
            secondaryNameInputError: null,
            secondaryPhoneInputError: null,
            secondaryRelationshipInputError: null,

            selectedNrcCode: null,
            nrcTownships: [],
            selectedNrcTownship: null,
            selectedNrcType: 'N',
            nrcNumber: null,

            nrcCodeError: null,
            nrcTownshipError: null,
            nrcTypeError: null,
            nrcInputError: null,

            banksList: [],
            selectedBank: null,
            bankAccountNumber: null,

            bankSelectError: null,
            bankAccountNumberError: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        onNrcFrontChange(e) {
            const file = e.target.files[0];
            this.nrcFrontFile = file;
            this.nrcFrontPreview = file ? URL.createObjectURL(file) : null;
        },
        onNrcBackChange(e) {
            const file = e.target.files[0];
            this.nrcBackFile = file;
            this.nrcBackPreview = file ? URL.createObjectURL(file) : null;
        },
        onHouseHoldRegistrationChange(e) {
            const file = e.target.files[0];
            this.houseHoldRegistrationFile = file;
            this.houseHoldRegistrationPreview = file ? URL.createObjectURL(file) : null;
        },

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
                this.dob = this.formatDate(this.staff.birthdate);
                this.selectedGender = this.staff.gender;
                this.nrcNumber = this.staff.nrc_number
                this.fatherName = this.staff.father_name;
                this.motherName = this.staff.mother_name;
                this.email = this.staff.email;
                this.phoneNumber = this.staff.phone_number;
                this.altPhoneNumber = this.staff.alt_phone_number;
                this.joinedDate = this.formatDate(this.staff.joined_date);
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
                }, 200);
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
                this.selectedNrcCode = this.staff.nrc_code;
                this.selectedNrcType = this.staff.nrc_type;
                this.getNrcTownships();

                this.selectedBank = this.staff.bank;
                this.getBanksList();
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
            this.roleList = [];
            this.inventories = [];
            let rolesResponsePromise = getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() });
            rolesResponsePromise.then(response=>{
                if(response.data){
                    this.roleList = response.data;
                }
            });
            // let rolesResponse = await getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() });
            // if (rolesResponse.data) {
            //     this.roleList = rolesResponse.data;
            // }
            if (this.selectedDepartment.features.length > 0) {
                this.featureList = this.selectedDepartment.features;
            }

            this.areaList = this.selectedDepartment.areas;

            if(this.selectedDepartment.inventory){
                this.inventories.push(this.selectedDepartment.inventory.inventory);
            }
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

            // if(this.$refs.nrc_front_image.files[0]){
            //     this.nrcFrontFile = this.$refs.nrc_front_image.files[0];
            // }

            // if(this.$refs.nrc_back_image.files[0]){
            //     this.nrcBackFile = this.$refs.nrc_back_image.files[0];
            // }

            // if(this.$refs.household_registration_image.files[0]){
            //     this.houseHoldRegistrationFile = this.$refs.household_registration_image.files[0];
            // }

            if(!this.selectedDepartment){
                this.alertValiationMessage('department');
                this.departmentInputError = "Department must be selected";
                return 1;
            }
            if (this.selectedDepartment.name == 'Inventory' && this.selectedInventories.length < 1) {
                this.alertValiationMessage('inventories');
                this.inventoryInputError = "Inventory staff must select at least one inventory";
                return 1;
            }
            if(!this.selectedRole || this.selectedRoles.length < 1){
                this.alertValiationMessage('role');
                this.rolesInputError = "At least one role must be selected";
                return 1;
            }
            // if ((this.selectedDepartment.name == 'Catering' || this.selectedDepartment.name == 'Kitchen') && !this.selectedArea) {
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

            this.roleIds = [this.selectedRoles.id];
            // this.roleIds = [this.selectedRole];

            if (this.selectedFeatures.length > 0) {
                this.selectedFeatures.forEach((feature) => {
                    this.featureIds.push(feature.id);
                });
            }

            if (!this.name) {
                this.alertValiationMessage('name');
                this.nameInputError = "Name must be filled";
                return 1;
            }

            if (!this.dob) {
                this.alertValiationMessage('Date of Birth');
                this.dateOfBirthInputError = "Date of birth must be filled";
                return 1;
            }

            if (!this.selectedGender) {
                this.alertValiationMessage('gender');
                this.genderInputError = "Gender must be selected";
                return 1;
            }

            if(!this.selectedNrcCode){
                this.alertValiationMessage('NRC Code');
                this.nrcCodeError = "NRC Code must be selected";
                return 1;
            }

            if(!this.selectedNrcTownship){
                this.alertValiationMessage('NRC Township');
                this.nrcTownshipError = "NRC Township must be selected";
                return 1;
            }

            if(!this.selectedNrcType){
                this.alertValiationMessage('NRC Type');
                this.nrcTypeError = "NRC Type must be selected";
                return 1;
            }

            if (!this.nrcNumber || !this.nrcNumber.match(/^\d{6,8}$/)) {
                this.alertValiationMessage('NRC Number');
                this.nrcInputError = "NRC Number must be filled and valid (6 to 8 digits)";
                return 1;
            }

            if (!this.phoneNumber || !this.phoneNumber.match(/^09\d{7}(\d{2})?$/)) {
                this.alertValiationMessage('phone number');
                this.phoneNumberInputError = "Phone number must be filled and valid (09XXXXXXXX or 09XXXXXXXXXX)";
                return 1;
            }

            if (!this.joinedDate) {
                this.alertValiationMessage('joined date');
                this.joinedDateInputError = "Joined date must be filled";
                return 1;
            }

            if (!this.selectedDepartment) {
                this.alertValiationMessage('department');
                this.departmentInputError = "Department must be selected";
                return 1;
            }

            if (this.roleIds.length < 1) {
                this.alertValiationMessage('roles');
                this.rolesInputError = "At least one role must be selected";
                return 1;
            }

            if (this.featureIds.length < 1) {
                this.alertValiationMessage('authorized features');
                this.featuresInputError = "At least one feature must be authroized";
                return 1;
            }

            if (!this.state) {
                this.alertValiationMessage('state');
                this.stateInputError = "State/Division must be selected";
                return 1;
            }

            if (!this.city) {
                this.alertValiationMessage('city');
                this.cityInputError = "City must be selected";
                return 1;
            }

            if (!this.address) {
                this.alertValiationMessage('address');
                this.addressInputError = "Address must be filled";
                return 1;
            }

            if (!this.primaryName) {
                this.alertValiationMessage('primary name');
                this.primaryNameInputError = "Primary contact name must be filled";
                return 1;
            }

            if (!this.primaryPhone) {
                this.alertValiationMessage('primary phone');
                this.primaryPhoneInputError = "Primary contatct phone number must be filled";
                return 1;
            }

            if (!this.primaryRelationship) {
                this.alertValiationMessage('primary relationship');
                this.primaryRelationshipInputError = "Relationship with the primary contact must be filled";
                return 1;
            }

            if (!this.secondaryName) {
                this.alertValiationMessage('secondary name');
                this.secondaryNameInputError = "Secondary contact name must be filled";
                return 1;
            }

            if (!this.secondaryPhone) {
                this.alertValiationMessage('secondary phone');
                this.secondaryPhoneInputError = "Secondary contatct phone number must be filled";
                return 1;
            }

            if (!this.secondaryRelationship) {
                this.alertValiationMessage('secondary relationship');
                this.secondaryRelationshipInputError = "Relationship with the secondary contact must be filled";
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

            formData.append('bank_id', this.selectedBank.id);
            formData.append('bank_account_number', this.bankAccountNumber);

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

            formData.append('roles', this.selectedRole);

            formData.append('skills',JSON.stringify(this.skillIds));

            if (this.password) {
                formData.append('password', this.password);
            }
            formData.append('joined_date', this.joinedDate);

            if (this.nrcFrontFile) {
                formData.append('certificate_images[]', this.nrcFrontFile);
            }
            if (this.nrcBackFile) {
                formData.append('certificate_images[]', this.nrcBackFile);
            }
            if (this.houseHoldRegistrationFile) {
                formData.append('certificate_images[]', this.houseHoldRegistrationFile);
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

        getNrcTownships(){
            if(!this.selectedNrcCode){
                this.alertValiationMessage('NRC Code');
                this.nrcTownships = [];
                return;
            }
            this.selectedNrcTownship = null;
            this.nrcTownships = [];
            getApiData({ url: `/api/nrcs?nrc_code=${this.selectedNrcCode}`, token: this.getToken() }).then(response => {
                if (response.data) {
                    this.nrcTownships = response.data;
                    if(this.staff){
                        this.selectedNrcTownship = this.nrcTownships.find(nrcTownship => nrcTownship.name_en == this.staff.nrc_township_code);
                    }
                }
            });
        },

        getBanksList() {
            getApiData({ url: `/api/banks`, token: this.getToken() }).then(response => {
                if (response.data) {
                    this.banksList = response.data;
                }
            });
        },

        formatDate(date){
            return formatDate(date, 'dd/mm/yyyy', '/');
        },
    },

    created() {
        this.getGenderList();
        this.getDepartmentList();
        this.getStateList();
        this.getStaffDetail();
        // this.getInventoryList();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
