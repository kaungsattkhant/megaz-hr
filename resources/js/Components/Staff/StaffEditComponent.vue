<template>
    <div class=" container-card pb-4" v-show="!isFeature">
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
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]" data-te-select-wrapper-ref>
                    <multiselect
                    v-model="selectedDepartment" :options="departmentList" :multiple="false" :close-on-select="true" :clear-on-select="false"
                    :preserve-search="true" placeholder="Select Department" label="name" track-by="id" :preselect-first="false"
                    @select="departmentSelectChanged"></multiselect>
                </div>
                <div class="mt-1" v-if="departmentInputError">
                    <span class="px-1 text-red-600 text-sm">{{ departmentInputError }} *</span>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">Role</label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]" data-te-select-wrapper-ref>
                        <multiselect
                        v-model="selectedRole" :options="roleList" :multiple="false" :close-on-select="true" :clear-on-select="false"
                        :preserve-search="true" placeholder="Select Role" label="name" track-by="id" :preselect-first="false"
                        @select="getSkillByRole(selectedRole.id)"></multiselect>
                    </div>
                    <div class="mt-1" v-if="rolesInputError">
                        <span class="px-1 text-red-600 text-sm">{{ rolesInputError }} *</span>
                    </div>
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <div>
                    <label class="label-form mb-3">Authorized Features</label>
                    <div class="input-ui flex justify-between">
                        <span class="">{{ selectedFeatures ? selectedFeatures.length : '' }} features selected</span>
                        <button class="px-3 border-l" @click="btnClickedChangeFeature()">
                            <i class="fal fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- <div>
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
                </div> -->
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
                        <span class="font-inter after-coma text-sm" v-for="selectedInventory in selectedInventories">{{ selectedInventory.name }}</span>
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

            <div class="contents">
                <div class="contents">
                    <div class="col-span-3 rounded-md mb-4 pb-6">
                        <label for="nrc-front" class="label-form mb-3">
                            Profile Picture
                        </label>
                        <input type="file" accept="image/png, image/gif, image/jpeg" id="nrc-front" ref="nrc_front_image"
                        class="flex flex-col items-center justify-center h-12 w-full bg-gray-100 rounded-md border-2 border-gray-300
                        border-dashed transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700"
                        @change="onProfilePicFileChange">
                        <img v-if="profilePicPreview" :src="profilePicPreview" alt="NRC Front Preview" class="mt-2 h-24" />
                        <div class="mt-1" v-if="profilePicFileError">
                            <span class="px-1 text-red-600 text-sm">{{ profilePicFileError }} *</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-span-3"></div>

            <div v-if="staff" class="contents">
                <div class="contents">
                    <div class="col-span-3 rounded-md mb-4 pb-6">
                        <label for="" class="label-form mb-3">
                            Profile Image
                        </label>
                        <img v-if="staff.profile_image_url" :src="staff.profile_image_url" alt="Profile Image" class="mx-4 mt-2 h-24" />
                    </div>

                    <div class="col-span-3 rounded-md mb-4 pb-6">
                        <label for="" class="label-form mb-3">
                            NRC Front
                        </label>
                        <img v-if="staff.nrc_front_url" :src="staff.nrc_front_url" alt="NRC Front Image" class="mx-4 mt-2 h-24" />
                    </div>

                    <div class="col-span-3 rounded-md mb-4 pb-6">
                        <label for="" class="label-form mb-3">
                            NRC Back
                        </label>
                        <img v-if="staff.nrc_back_url" :src="staff.nrc_back_url" alt="NRC Back Image" class="mx-4 mt-2 h-24" />
                    </div>

                    <div class="col-span-3 rounded-md mb-4 pb-6">
                        <label for="" class="label-form mb-3">
                            Household Registration
                        </label>
                        <img v-if="staff.household_registration_url" :src="staff.household_registration_url" alt="Household Registration Image" class="mx-4 mt-2 h-24" />
                    </div>
                </div>
            </div>

            <div v-if="staff" class="contents">
                <div class="contents" v-if="staff.staff_certifications.length > 0">
                    <div class="col-span-12 rounded-md mb-4 pb-6">
                        <label for="" class="label-form mb-3">
                            Certification Images
                        </label>
                        <div class="flex">
                            <div v-for="(certificate) in staff.staff_certifications">
                                <img :src="certificate.certificate_file_url" alt="Certification Preview" class="mx-4 mt-2 h-24" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12"></div>
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

            <div class="col-span-3  rounded-md mb-4 pb-6">
                <label for="household-registration" class="label-form mb-3">
                    Certifications
                </label>
                <input type="file" multiple accept="image/png, image/gif, image/jpeg" id="household-registration" ref="certificateInput"
                class="flex flex-col items-center justify-center h-12 w-full bg-gray-100 rounded-md border-2 border-gray-300
                border-dashed transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700"
                @change="certificationFilesChange">
                <div v-for="(preview, index) in certificateFilePreviews">
                    <div class="flex items-center">
                        <div class="contents">
                            <img v-if="preview" :src="preview" alt="Certification Preview" class="mt-2 h-24" />
                            <button @click="removeCertificateBtnClicked(index)"> <i class="fas fa-times"></i> </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-span-3 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-3">
                    Bank Account Number
                </label>
                <input type="text" v-model="bankAccountNumber" placeholder="Bank Account Number" class="input-ui">
            </div>

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

            <div class="col-span-1 rounded-md mb-4 pb-6">
                <label for="" class="label-form mb-5">
                    &nbsp;
                </label>
                <button data-te-toggle="modal" data-te-target="#add_bank_modal" > <i class="fas fa-plus"></i> </button>
            </div>



            <div class="col-span-5"></div>

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
        <!-- Bank Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_bank_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-modal="true" role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create Bank
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            id="close_create_bank_modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Name
                            </label>
                            <input type="text" v-model="bankName" placeholder="Bank Name" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss
                        @click="createBankBtnClicked">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <transition
        enter-active-class="fade-out duration-[200ms]"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="fade-in duration-[300ms]"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
        >
    <div v-show="isFeature" class="fixed top-0 left-0 right-0 bottom-0 w-[100vw] h-[100vh] z-40 overflow-y-auto bg-[#0008]" @click="btnClickedChangeFeature">
        <div class="container-card pb-4 px-8 m-16 z-50 overflow-hidden" @click.stop>
            <div class="mb-6 flex justify-between">
                <p class="text-lg font-semibold font-inter">
                    Feature
                </p>
                <div>
                    <button type="button" class="add-btn focus:shadow-none focus:outline-none" @click="btnClickedChangeFeature">
                        Done
                    </button>
                </div>
            </div>
            <div>
                <div v-for="(module,index) in featureList" class="mb-4 pb-6 px-2 border-b border-gray-200 flex">
                    <p class=" capitalize mb-4 font-semibold w-[20%] cursor-pointer" @click="toggleGroup(module)">
                        {{ module.module }}
                    </p>
                    <div class="w-[80%] grid grid-cols-4 text-sm text-gray-600 flex-wrap gap-x-4 gap-y-6">
                        <label v-show="module.features.length > 1" class="block items-center space-x-2 cursor-pointer" @click="toggleGroup(module)">
                            <span class="text-black break-all capitalize block mb-2"> All </span>
                            <input
                            type="checkbox" :checked="isAllSelected(module)"
                            class="form-checkbox !ml-0.5 h-4 w-4 text-[#845adf] rounded focus:shadow-none focus:ring-0 cursor-pointer"
                            />

                        </label>
                        <div v-for="feature in module.features" class="">

                            <label class="block items-center space-x-2 cursor-pointer">
                                <span class="text-black break-all capitalize block mb-2">{{ feature.slug }}</span>
                                <input
                                type="checkbox" :value="feature.id" v-model="selectedFeatures"
                                class="form-checkbox h-4 w-4 text-[#845adf] rounded focus:shadow-none focus:ring-0 cursor-pointer !ml-0.5"
                                />

                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" class="add-btn focus:shadow-none focus:outline-none" @click="btnClickedChangeFeature">
                    Done
                </button>
            </div>
        </div>
    </div>
    </transition>

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
            originalSelectedDepartment: null,

            roleList: [],
            selectedRole: null,
            originalSelectedRole: null,
            roleIds: [],

            originalSelectedFeatures: [],
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

            profilePicFile: null,
            profilePicPreview: null,
            profilePicFileError: null,

            nrcFrontFileError: null,
            nrcBackFileError: null,
            houseHoldRegistrationFileError: null,

            certificateFiles: [],
            certificateFilePreviews: [],

            primaryName: null,
            primaryPhone: null,
            primaryRelationship: null,
            secondaryName: null,
            secondaryPhone: null,
            secondaryRelationship: null,

            skillList:[],
            skillIds:[],
            selectedSkills:[],

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

            bankName: null,
            bankSelectError: null,
            bankAccountNumberError: null,
            creatable: false,

            isFeature: false,
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
        onProfilePicFileChange(e) {
            const file = e.target.files[0];
            this.profilePicFile = file;
            this.profilePicPreview = file ? URL.createObjectURL(file) : null;
        },

        certificationFilesChange(e){
            const files = Array.from(e.target.files);
            // Clear previous selections if you want to replace, or remove this line to allow accumulating
            this.certificateFiles = [];
            this.certificateFilePreviews = [];
            files.forEach(file => {
                this.certificateFiles.push(file);
                this.certificateFilePreviews.push(URL.createObjectURL(file));
            });
        },

        removeCertificateBtnClicked(index){
            this.certificateFiles.splice(index,1);
            this.certificateFilePreviews.splice(index,1);
            // Reset the file input so user can re-select files
            this.$refs.certificateInput.value = '';
        },

        async getStaffDetail() {
            let url = `/api/staffs/${this.staffId}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.staff = response.data;
                this.name = this.staff.name;
                // const separator1 = this.staff.birthdate.includes('-') ? '-' : '/';
                this.dob = this.formatDate(this.staff.birthdate, '-', 'dd/mm/yyyy', '/', ['year','month','day']);
                this.selectedGender = this.staff.gender;
                this.nrcNumber = this.staff.nrc_number;
                this.fatherName = this.staff.father_name;
                this.motherName = this.staff.mother_name;
                this.email = this.staff.email;
                this.phoneNumber = this.staff.phone_number;
                this.altPhoneNumber = this.staff.alt_phone_number;
                this.joinedDate = this.formatDate(this.staff.joined_date, '-', 'dd/mm/yyyy', '/', ['year','month','day']);
                this.zipCode = this.staff.zip_code;
                this.address = this.staff.address;
                this.bankAccountNumber = this.staff.bank_account_number;
                this.password = null;
                setTimeout(() => {
                    this.reconstructStaffData();
                }, 200);
            }
        },

        async reconstructStaffData() {
            if (this.staff) {
                this.selectedDepartment = this.staff.department;
                this.originalSelectedDepartment = this.selectedDepartment;
                this.getFeatureList();
                this.selectedRole = this.staff.roles[0];
                this.originalSelectedRole = this.selectedRole;
                this.selectedInventories = this.staff.inventories;
                // this.originalSelectedFeatures = this.staff.features;
                // this.selectedFeatures = this.staff.features;
                // this.selectedFeatures = this.staff.features;
                this.staff.features.forEach((fea) => {
                    this.selectedFeatures.push(fea.id)
                })
                this.originalSelectedFeatures = this.selectedFeatures;
                this.selectedSkills = this.staff.skills;

                this.departmentList.forEach((department) => {
                    if (this.staff.department_id == department.id) {
                        this.roleList = department.roles;
                        this.featureList = department.features;
                        this.inventories.push(department.inventory.inventory);
                    }
                });
                getApiData({url:`/api/roles/${this.staff.roles[0].id}/skills`, token: this.getToken()}).then(response =>{
                    if(response.data){
                        this.skillList = response.data;
                    }
                });

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

                if (this.staff.emergency_contacts.length > 0) {
                    this.primaryName = this.staff.emergency_contacts[0].primary_name;
                    this.primaryPhone = this.staff.emergency_contacts[0].primary_phone;
                    this.primaryRelationship = this.staff.emergency_contacts[0].primary_relationship
                    this.secondaryName = this.staff.emergency_contacts[0].secondary_name;
                    this.secondaryPhone = this.staff.emergency_contacts[0].secondary_phone;
                    this.secondaryRelationship = this.staff.emergency_contacts[0].secondary_relationship;
                }
            }
        },

        async departmentSelectChanged() {
            this.featureList = [];
            this.selectedRole = null;
            this.selectedFeatures = [];
            this.featureList = [];
            this.selectedInventories = [];
            this.inventories = [];
            this.skillList = [];
            this.selectedSkills = [];
            getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() }).then(response=>{
                if(response.data){
                    this.roleList = response.data;
                }
            });
            // let rolesResponse = await getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() });
            // if (rolesResponse.data) {
            //     this.roleList = rolesResponse.data;
            // }

            // if (this.selectedDepartment.features.length > 0) {
            //     this.featureList = this.selectedDepartment.features;
            // }
            // if (this.selectedDepartment.features.length > 0) {
            //     this.featureList = this.selectedDepartment.features;
            // }
            this.getFeatureList();

            if(this.selectedDepartment.inventory){
                this.inventories.push(this.selectedDepartment.inventory.inventory);
            }

            if(this.selectedDepartment.id == this.originalSelectedDepartment.id){
                this.selectedRole = this.originalSelectedRole;
                this.selectedFeatures = this.originalSelectedFeatures;
            }
        },
        async getFeatureList() {
            let response = await getApiData({ url: `/api/feature_by_department/${this.selectedDepartment.id}`, token: this.getToken() });
            if (response.data) {
                this.featureList = response.data;
            }
        },
        btnClickedChangeFeature(){
            if(!this.selectedDepartment){
                this.$notify({
                    title: `Input validation`,
                    text: `Please Select Department First`,
                    type: "warn"
                });
                return
            }
            if(this.isFeature){
                this.isFeature = false;
            }
            else{
                this.isFeature = true;
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

        async getSkillByRole(id) {
            this.selectedSkills = [];
            // this.selectedFeatures = [];
            let response = await getApiData({ url: `/api/roles/${id}/skills`, token: this.getToken() });
            if (response.data) {
                this.skillList = response.data;
            }
        },

        updateStaffBtnClicked() {
            this.featureIds = [];
            this.inventoryIds = [];
            this.skillIds = [];
            this.creatable = true;

            if (!this.name) {
                this.alertValiationMessage('name');
                this.nameInputError = "Name must be filled";
                // return 1;
                this.creatable = false;
            }

            if (!this.dob) {
                this.alertValiationMessage('Date of Birth');
                this.dateOfBirthInputError = "Date of birth must be filled";
                // return 1;
                this.creatable = false;
            }

            if (!this.selectedGender) {
                this.alertValiationMessage('gender');
                this.genderInputError = "Gender must be selected";
                // return 1;
                this.creatable = false;
            }

            if(!this.selectedNrcCode){
                this.alertValiationMessage('NRC Code');
                this.nrcCodeError = "NRC Code must be selected";
                // return 1;
                this.creatable = false;
            }

            if(!this.selectedNrcTownship){
                this.alertValiationMessage('NRC Township');
                this.nrcTownshipError = "NRC Township must be selected";
                // return 1;
                this.creatable = false;
            }

            if(!this.selectedNrcType){
                this.alertValiationMessage('NRC Type');
                this.nrcTypeError = "NRC Type must be selected";
                // return 1;
                this.creatable = false;
            }

            // if (!this.nrcNumber || !this.nrcNumber.match(/^\d{6,8}$/)) {
            //     this.alertValiationMessage('NRC Number');
            //     this.nrcInputError = "NRC Number must be filled and valid (6 to 8 digits)";
            //     // return 1;
            //     this.creatable = false;
            // }

            if (!this.nrcNumber) {
                this.alertValiationMessage('NRC Number');
                this.nrcInputError = "NRC Number must be filled and valid (6 to 8 digits)";
                // return 1;
                this.creatable = false;
            }

            if (!this.joinedDate) {
                this.alertValiationMessage('joined date');
                this.joinedDateInputError = "Joined date must be filled";
                // return 1;
                this.creatable = false;
            }

            // phone number format = !this.phoneNumber.match(/^09\d{7}(\d{2})?$/)
            if (!this.phoneNumber) {
                this.alertValiationMessage('phone number');
                this.phoneNumberInputError = "Phone number must be filled";
                // return 1;
                this.creatable = false;
            }

            if(!this.selectedDepartment){
                this.alertValiationMessage('department');
                this.departmentInputError = "Department must be selected";
                // return 1;
                this.creatable = false;
            }
            if(!this.selectedRole){
                this.alertValiationMessage('role');
                this.rolesInputError = "Staff role must be selected";
                // return 1;
                this.creatable = false;
            }
            // if (this.selectedFeatures.length < 1) {
            //     this.alertValiationMessage('authorized features');
            //     this.featuresInputError = "At least one feature must be authroized";
            //     // return 1;
            //     this.creatable = false;
            // }
            if (this.selectedDepartment.name == 'Inventory' && this.selectedInventories.length < 1) {
                this.alertValiationMessage('inventories');
                this.inventoryInputError = "Inventory staff must select at least one inventory";
                // return 1;
                this.creatable = false;
            }

            if (this.selectedInventories.length > 0) {
                this.selectedInventories.forEach((inventory) => {
                    this.inventoryIds.push(inventory.id);
                });
            }

            this.selectedSkills.forEach((skill) => {
                this.skillIds.push(skill.id);
            });

            // this.selectedFeatures.forEach((feature) => {
            //     this.featureIds.push(feature.id);
            // });

            if (!this.state) {
                this.alertValiationMessage('state');
                this.stateInputError = "State/Division must be selected";
                // return 1;
                this.creatable = false;
            }

            if (!this.city) {
                this.alertValiationMessage('city');
                this.cityInputError = "City must be selected";
                // return 1;
                this.creatable = false;
            }

            if (!this.address) {
                this.alertValiationMessage('address');
                this.addressInputError = "Address must be filled";
                // return 1;
                this.creatable = false;
            }

            if (!this.primaryName) {
                this.alertValiationMessage('primary name');
                this.primaryNameInputError = "Primary contact name must be filled";
                // return 1;
                this.creatable = false;
            }

            if (!this.primaryPhone) {
                this.alertValiationMessage('primary phone');
                this.primaryPhoneInputError = "Primary contatct phone number must be filled";
                // return 1;
                this.creatable = false;
            }

            if (!this.primaryRelationship) {
                this.alertValiationMessage('primary relationship');
                this.primaryRelationshipInputError = "Relationship with the primary contact must be filled";
                // return 1;
                this.creatable = false;
            }

            if (!this.secondaryName) {
                this.alertValiationMessage('secondary name');
                this.secondaryNameInputError = "Secondary contact name must be filled";
                // return 1;
                this.creatable = false;
            }

            if (!this.secondaryPhone) {
                this.alertValiationMessage('secondary phone');
                this.secondaryPhoneInputError = "Secondary contatct phone number must be filled";
                // return 1;
                this.creatable = false;
            }

            if (!this.secondaryRelationship) {
                this.alertValiationMessage('secondary relationship');
                this.secondaryRelationshipInputError = "Relationship with the secondary contact must be filled";
                // return 1;
                this.creatable = false;
            }

            if(this.creatable){
                this.updateStaff();
            }
        },

        async updateStaff() {
            let formData = new FormData();
            formData.append('department_id', this.selectedDepartment.id);
            formData.append('role_id', this.selectedRole.id);
            formData.append('skill_ids',JSON.stringify(this.skillIds));
            // formData.append('feature_ids', JSON.stringify(this.featureIds));
            if(this.selectedFeatures.length > 0){
                formData.append('feature_ids', JSON.stringify(this.selectedFeatures));
            }
            if (this.inventoryIds.length > 0) {
                formData.append('inventory_ids', JSON.stringify(this.inventoryIds));
            }

            formData.append('name', this.name);
            formData.append('phone_number', this.phoneNumber);
            formData.append('nrc_code', this.selectedNrcCode);
            formData.append('nrc_township_code', this.selectedNrcTownship.name_en);
            formData.append('nrc_type', this.selectedNrcType);
            formData.append('nrc_number', this.nrcNumber);

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

            formData.append('birthdate', this.formatDate(this.dob, '/', 'yyyy-mm-dd', '-', ['day','month','year']));
            formData.append('state', this.state);
            formData.append('address', this.address);
            formData.append('city', this.city);
            formData.append('gender_id', this.selectedGender.id);

            if(this.password){
                formData.append('password', this.password);
            }

            formData.append('joined_date', this.formatDate(this.joinedDate, '/', 'yyyy-mm-dd', '-', ['day','month','year']));

            if(this.profilePicFile){
                formData.append('profile_image', this.profilePicFile);
            }
            if (this.nrcFrontFile) {
                formData.append('nrc_front_image', this.nrcFrontFile);
            }
            if (this.nrcBackFile) {
                formData.append('nrc_back_image', this.nrcBackFile);
            }
            if (this.houseHoldRegistrationFile) {
                formData.append('household_registration_image', this.houseHoldRegistrationFile);
            }
            // Append each certification file
            if (this.certificateFiles.length > 0) {
                this.certificateFiles.forEach(file => {
                    formData.append('certificate_images[]', file);
                });
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

        formatDate(date, dateSplitter='-', format='dd/mm/yyyy', separator='/', ordering = ['year', 'month', 'day']){
            return formatDate(date, dateSplitter, format, separator, ordering);
        },

        createBankBtnClicked(){
            if(!this.bankName){
                this.alertValiationMessage('bank name');
                return;
            }
            let formData = new FormData();
            formData.append('name', this.bankName);
            postApiData({url: `/api/banks`, form_data: formData, token: this.getToken()}).then(response=>{
                if(response.data){
                    this.banksList.push(response.data);
                    this.selectedBank = response.data;
                    this.bankName = null;
                }
            });
        },
        toggleGroup(items) {
            const allSelectedd = items.features.every(i => this.selectedFeatures.includes(i.id));
            console.log('All fruit items selected?', allSelectedd); // true

            items.features.forEach(item => {
                // const allSelected = items.features.every(i => this.selectedFeatures.includes(i.id));
                // console.log(`All items in  selected?`, allSelected);
                // const exists = this.selectedFeatures.some(i => i === item.id);


                if(allSelectedd){
                    this.selectedFeatures = this.selectedFeatures.filter(i => i !== item.id);
                }
                else{
                    this.selectedFeatures = this.selectedFeatures.filter(i => i !== item.id);
                    this.selectedFeatures.push(item.id);
                }

            });

        },
        handleEscape(event) {
            if (event.key === "Escape") {
                this.isFeature = false
            }
        },
        isAllSelected(module) {
                const featureIds = module.features.map(f => f.id)
                return featureIds.every(id => this.selectedFeatures.includes(id))
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
        window.addEventListener("keydown", this.handleEscape);
        initTE({ Modal, Select, Ripple });
    }
}
</script>
