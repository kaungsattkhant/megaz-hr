<template>
    <div class="px-0">
        <div class="mb-4">
            <p class="text-lg font-semibold font-inter">
                Meeting : Operation Meeting
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white p-8 rounded-md">
            <div class="mb-4 col-span-12">
                <p class="text-lg font-normal font-inter">
                    Meeting : Operation Meeting
                </p>
            </div>
            <div class="col-span-3 rounded-md mb-8">
                <label for="" class="label-form mb-3">
                    Old Meeting
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <multiselect
                    v-model="selectedOldMeeting"
                    :options="oldMeetings"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="false"
                    placeholder="Old Meeting"
                    label="title"
                    track-by="id"
                    :preselect-first="false"
                    @select="oldMeetingSelected()">
                    </multiselect>                    
                </div>
            </div>

            <div class="col-span-9 rounded-md mb-8"></div>

            <div class="col-span-3 rounded-md mb-8">
                <label for="" class="label-form mb-3">
                    Department
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <multiselect
                    v-model="selectedDepartment"
                    :options="departmentList"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="false"
                    placeholder="Department"
                    label="name"
                    track-by="id"
                    :preselect-first="false"
                    @select="departmentSelectChanged">
                    </multiselect>                    
                </div>
            </div>

            <div class="col-span-3 rounded-md mb-8">
                <label class="label-form mb-3">Roles</label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <multiselect
                    v-model="selectedRole"
                    :options="roleList"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="false"
                    placeholder="Role"
                    label="name"
                    track-by="id"
                    :preselect-first="false"
                    @select="roleSelectChanged">
                    </multiselect>                    
                </div>
            </div>
            <div class="col-span-3 rounded-md mb-8">
                <label class="label-form mb-3">Staff</label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <multiselect
                    v-model="selectedStaff"
                    :options="staffList"
                    :multiple="true"
                    :close-on-select="false"
                    :clear-on-select="false"
                    :preserve-search="false"
                    placeholder="Staff"
                    label="name"
                    track-by="id"
                    :preselect-first="false"
                    >
                    </multiselect>                    
                </div>
            </div>
            <div class="col-span-3">
                <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10"
                :disabled="selectedStaff.length < 1"
                @click="addSelectedStaffToMeeting">
                    Add
                </button>
            </div>

            <div class=" col-span-12 mb-10">
                <table class="min-w-[50%] text-sm font-light ml-2">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Department
                            </th>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Role
                            </th>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Staff
                            </th>
                            <th scope="col" class=" px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(staff, index) in meetingStaff" >
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                {{ staff.department.name }}
                            </td>
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                {{ staff.role.name }}
                            </td>
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                {{ staff.staff.name }}
                            </td>
                            <td class=" px-6 py-3 font-medium text-left">
                                <button @click="removeMeetingStaffBtnClicked(index)">
                                    <i class="fal fa-times  pr-3" ></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-0 col-span-7 rounded-md">
                <label for="" class="label-form mb-3">
                    Meeting Minutes
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <textarea type='text' v-model="meetingMinutesText" class="input-ui w-full !p-1 text-xs" rows="8" placeholder="Description" ></textarea>
                </div>

            </div>

            <hr class="col-span-12 my-6">

            <div class="mb-0 col-span-12 rounded-md">
                <!-- Tabs navigation (Vue-driven) -->
                <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 ps-0" role="tablist">
                  <li v-for="tab in tabs" :key="tab.key" role="presentation">
                    <a
                      href="#"
                      role="tab"
                      :aria-selected="activeTab === tab.key"
                      :class="tabNavClass(tab.key)"
                      @click.prevent="setActiveTab(tab.key)"
                    >{{ tab.label }}</a>
                  </li>
                </ul>

                <!-- Tabs content (Vue-driven) -->
                <div class="mb-6">
                  <div
                    v-show="activeTab === 'kpi'"
                    class="opacity-100 grid grid-cols-12 gap-x-4 transition-opacity duration-150 ease-linear"
                    role="tabpanel">

                    <div class="mb-0 col-span-4 rounded-md">
                        <!-- <label for="" class="label-form mb-3">
                            Metric
                        </label>
                        <input type="text" v-model="kpiMetric" placeholder="Metric" autocomplete="off"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">           -->              
                        <label for="" class="block text-sm text-black mb-3">
                            Metric
                        </label>
                        <multiselect
                            v-model="selectedKpiSnapshot"
                            :options="kpiSnapshots"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="KPI"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>
                    </div>

                    <div class="mb-4 col-span-1 pb-0 rounded-md">
                        <label for="" class="label-form mb-5">
                            &nbsp;
                        </label>
                        <button data-te-toggle="modal" data-te-target="#addKpiSnapshotModal" > <i class="fas fa-plus"></i> </button>
                    </div>

                    <div class="mb-0 col-span-3 rounded-md">
                        <label for="" class="label-form mb-3">
                            Amount
                        </label>
                        <input type="number" v-model="kpiAmount" placeholder="Amount" autocomplete="off"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">                        
                    </div>

                    <div class="mb-0 col-span-2 rounded-md">                        
                        <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10"
                        @click="addKpiButtonClicked()">
                            Add
                        </button>                        
                    </div>

                    <div class="mb-0 col-span-2 rounded-md"></div>

                    <div class="mb-0 col-span-9 rounded-md">
                        <label for="" class="label-form mb-3 text-lg mt-4">
                            Current Meeting
                        </label>
                        <table class="min-w-[50%] text-sm font-light ml-2">
                            <thead class="font-medium text-left ">
                                <tr>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Metric
                                    </th>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Amount
                                    </th>                                    
                                    <th scope="col" class=" px-6 py-3">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(kpiObj, index) in kpiContainer" >
                                    <td class=" pr-6 pl-2 py-3 font-medium ">
                                        {{ kpiObj.metric }}
                                    </td>
                                    <td class=" pr-6 pl-2 py-3 font-medium ">
                                        {{ kpiObj.amount }}
                                    </td>                                    
                                    <td class=" px-6 py-3 font-medium text-left">
                                        <button @click="removeKpiObjBtnClicked(index)">
                                            <i class="fal fa-times  pr-3" ></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-0 col-span-3 rounded-md"></div>
                  </div>

                  <div
                    v-show="activeTab === 'alignment'"
                    class="opacity-100 grid grid-cols-12 gap-x-4 transition-opacity duration-150 ease-linear"
                    role="tabpanel">
                    <div class="mb-4 col-span-12">
                        <p class="text-lg font-normal font-inter">
                            7S Alignment
                        </p>
                    </div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            7S
                        </label>
                        <multiselect
                            v-model="selectedAlignment"
                            :options="sevenSAlignments"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="7S"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>
                    </div>

                    <div class="mb-4 col-span-4 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Remark
                        </label>                
                        <input type="text" v-model="alignmentRemark" placeholder="Remark" autocomplete="off"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="col-span-3">
                        <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10"
                        @click="addAlignmentBtnClicked()">
                            Add New
                        </button>
                    </div>

                    <div class="col-span-2"></div>

                    <div class="mb-0 col-span-9 rounded-md">
                        <label for="" class="label-form mb-3 text-lg mt-4">
                            Current Meeting
                        </label>
                        <table class="min-w-[50%] text-sm font-light ml-2">
                            <thead class="font-medium text-left ">
                                <tr>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Alignment
                                    </th>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Remark
                                    </th>                                    
                                    <th scope="col" class=" px-6 py-3">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(alignmentObj, index) in alignmentContainer" >
                                    <td class=" pr-6 pl-2 py-3 font-medium ">
                                        {{ alignmentObj.alignment.name }}
                                    </td>
                                    <td class=" pr-6 pl-2 py-3 font-medium ">
                                        {{ alignmentObj.remark }}
                                    </td>                                    
                                    <td class=" px-6 py-3 font-medium text-left">
                                        <button @click="removeAlignmentObjBtnClicked(index)">
                                            <i class="fal fa-times  pr-3" ></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-0 col-span-3 rounded-md"></div>
                  </div>

                  <div
                    v-show="activeTab === 'instructions'"
                    class="opacity-100 grid grid-cols-12 gap-x-4 transition-opacity duration-150 ease-linear"
                    role="tabpanel">
                    <div class="mb-4 col-span-12">
                        <p class="text-lg font-normal font-inter">
                            Add Instruction
                        </p>
                    </div>
                    <div class="mb-4 col-span-4 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Objective Name
                        </label>                
                        <input type="text" v-model="objectiveName" placeholder="Objective Name" autocomplete="off"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="mb-4 col-span-4 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            OKR Point
                        </label>
                        <input type="number" v-model="okrPoint" autocomplete="off"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="col-span-4"></div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Priority
                        </label>
                        <multiselect
                            v-model="selectedPriority"
                            :options="priorities"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Priority"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>
                    </div>

                    <div class="mb-4 col-span-2 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Stage
                        </label>
                        <multiselect
                            v-model="selectedTag"
                            :options="tagList"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Stage"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>                        
                    </div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Project
                        </label>
                        <multiselect
                            v-model="selectedProject"
                            :options="projectList"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Project"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>
                        <!-- <input type="number" autocomplete="off"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
                    </div>

                    <div class="mb-4 col-span-1 pb-0 rounded-md">
                        <label for="" class="label-form mb-5">
                            &nbsp;
                        </label>
                        <button data-te-toggle="modal" data-te-target="#addProjectModal" > <i class="fas fa-plus"></i> </button>
                    </div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            SOP
                        </label>
                        <multiselect
                            v-model="selectedSop"
                            :options="sopList"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="SOP"
                            label="sop"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>
                    </div>

                    <!-- <div class="col-span-3"></div> -->

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Start Date
                        </label>
                        <input type="date" v-model="startDate" autocomplete="off"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Due Date
                        </label>
                        <input type="date" v-model="dueDate" autocomplete="off"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Remark
                        </label>
                        <input type="text" v-model="remark" autocomplete="off"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="col-span-3"></div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Responsible
                        </label>
                        <multiselect
                            v-model="responsibleStaff"
                            :options="allStaffList"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Responsible"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>
                    </div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Accountable
                        </label>
                        <multiselect
                            v-model="accountableStaff"
                            :options="allStaffList"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Accountable"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>                        
                    </div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Consulted
                        </label>
                        <multiselect
                            v-model="consultedStaff"
                            :options="allStaffList"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Consulted"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>                        
                    </div>

                    <div class="mb-4 col-span-3 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Informed
                        </label>
                        <multiselect
                            v-model="informedStaff"
                            :options="allStaffList"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Informed"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect>                        
                    </div>

                    <div class="mb-4 col-span-6 pb-0 rounded-md">
                        <label for="" class="block text-sm text-black mb-3">
                            Key Result
                        </label>
                        <!-- <multiselect
                            v-model="selectedObjectiveKeys"
                            :options="objectiveKeys"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Objective"
                            label="name"
                            track-by="id"
                            :preselect-first="false"
                            >
                        </multiselect> -->
                        <input type="text" v-model="keyResultName" placeholder="Key Result" autocomplete="off"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>

                    <div class="col-span-3">
                        <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10"
                        @click="addKeyResult">
                            Add
                        </button>
                    </div>

                    <div class=" col-span-12 mb-10">
                        <table class="min-w-[50%] text-sm font-light ml-2">
                            <thead class="font-medium text-left ">
                                <tr>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Key Results
                                    </th>
                                    <th scope="col" class=" px-6 py-3">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(keyResult, index) in keyResults">
                                    <td class=" pr-6 pl-2 py-3 font-medium ">
                                        {{ keyResult.name }}
                                    </td>
                                    <td class=" px-6 py-3 font-medium text-left">
                                        <button @click="removeKeyResult(index)">
                                            <i class="fal fa-times  pr-3" ></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-span-12">
                        <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10"
                        @click="addInstructionBtnClicked">
                            Add
                        </button>
                    </div>
                  </div>                    
                </div>
            </div>

            <hr class="col-span-12 my-6">
        </div>
        <div>
            <button class="add-btn" @click="createBtnClicked">
                Create
            </button>
        </div>
    </div>


        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="addProjectModal" tabindex="-1" aria-labelledby="add_category_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_category_modalLabel">
                            Create Project
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="btn-close-create-project-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4 pb-0 rounded-md">
                            <label for="" class="block text-sm text-black mb-3">
                                Name
                            </label>
                            <input type="text" v-model="projectName" autocomplete="off"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <LoadingButton
                            :loading="projectCreateLoading"
                            text="Create"
                            loadingText="Loading..."
                            @click="createProject"
                        />
                        <!-- <button type="button" @click="createCategory()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button> -->
                    </div>
                </div>
            </div>
        </div>

        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="addKpiSnapshotModal" tabindex="-1" aria-labelledby="add_category_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_category_modalLabel">
                            Create KPI Snapshot
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="btn-close-create-kpi-snapshot-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4 pb-0 rounded-md">
                            <label for="" class="block text-sm text-black mb-3">
                                Name
                            </label>
                            <input type="text" v-model="kpiSnapshotName" autocomplete="off"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <LoadingButton
                            :loading="kpiSnapshotCreateLoading"
                            text="Create"
                            loadingText="Loading..."
                            @click="createKpiSnapshot"
                        />
                        <!-- <button type="button" @click="createCategory()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button> -->
                    </div>
                </div>
            </div>
        </div>

    <!--Delete Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Delete
                    </h5>
                    <button type="button"
                        class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p>
                        Are you sure ?
                    </p>
                </div>
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button"
                        class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                        data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="deleteItem()" type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                        class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown, Tab } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import LoadingButton from "../Common/LoadingButton.vue";

export default {
    props: ["meetingId"],
    components: {
        Multiselect,
        LoadingButton,
    },
    data() {
        return {
            activeTab: 'kpi',
            tabs: [
                { key: 'kpi', label: 'KPI Snapshot' },
                { key: 'alignment', label: '7S Alignment' },
                { key: 'instructions', label: 'Instructions' },
                // { key: 'contact', label: 'Contact' },
            ],
            departmentList: [],
            selectedDepartment: null,
            roleList: [],
            selectedRole: null,
            staffList: [],
            selectedStaff: [],

            meetingStaff: [],

            meetingMinutesText: null,

            allStaffList: [],
            accountableStaff: null,
            consultedStaff: null,
            informedStaff: null,
            assignedStaff: null,
            responsibleStaff: null,

            startDate: null,
            dueDate: null,
            remark: null,

            objectiveList: [],

            selectedObjective: null,
            okrPoint: 0,
            objectiveKeys: [],
            selectedObjectiveKeys: [],

            projectList: [],
            selectedProject: null,
            tagList: [
                {value: 'not_yet', name: 'Not Yet', id: 1},
                {value: 'plan', name: 'Plan', id: 2},
                {value: 'do', name: 'Do', id: 3},
                {value: 'done', name: 'Done', id: 4},
                {value: 'check', name: 'Check', id: 5},
                {value: 'completed', name: 'Completed', id: 6},
            ],
            selectedTag: null,
            priority: null,

            meetingInstructions: [],

            projectName: null,
            projectCreateLoading: false,

            sopList: [],
            selectedSop: null,

            objectiveName: null,

            keyResults: [],
            keyResultName: null,

            oldMeetings: [],
            selectedOldMeeting: null,

            kpiSnapshots: [],
            selectedKpiSnapshot: null,
            kpiContainer: [],
            kpiMetric: null,
            kpiAmount: 0,

            sevenSAlignments: [],
            selectedAlignment: null,
            alignmentRemark: null,
            alignmentContainer: [],

            priorities: [
                {
                    id: 1,
                    name: 'Urgent & Important',
                    value: 'urgent_important'
                },
                {
                    id: 2,
                    name: 'Not Urgent But Important',
                    value: 'not_urgent_important'
                },
                {
                    id: 3,
                    name: 'Urgent But Not Important',
                    value: 'urgent_not_important'
                },
                {
                    id: 4,
                    name: 'Not Urgent & Not Important',
                    value: 'not_urgent_not_important'
                },                
            ],
            selectedPriority: null,

            kpiSnapshotName: null,
            kpiSnapshotCreateLoading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        setActiveTab(tabKey) {
            this.activeTab = tabKey;
        },

        tabNavClass(tabKey) {
            const base =
                "my-2 block border-x-0 border-b-2 border-t-0 px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate dark:hover:bg-neutral-700/60";

            if (this.activeTab === tabKey) {
                return `${base} border-primary text-primary focus:border-primary dark:text-primary`;
            }

            return `${base} border-transparent text-neutral-500 focus:border-transparent dark:text-white/50`;
        },

        addKeyResult(){
            if(!this.keyResultName){
                this.showToastMessage("Key result name must be present");
                return;
            }
            this.keyResults.push({
                "name": this.keyResultName
            });
            this.keyResultName = null;
        },

        removeKeyResult(index){
            this.keyResults.splice(index, 1);
        },

        createProject(){
            this.projectCreateLoading = true;
            if(!this.projectName){
                this.projectCreateLoading = false;
                this.showToastMessage("Name of the project is required");
                return;
            }
            let formData = new FormData();
            formData.append('name', this.projectName);
            postApiData({url: `/api/projects`, form_data: formData, token: this.getToken()})
            .then((response)=>{
                this.projectCreateLoading = false;
                if(response.data){
                    this.projectList.push(response.data);
                    this.selectedProject = response.data;
                }
            });
            document.getElementById('btn-close-create-project-modal').click();
        },

        getObjectiveList(){
            getApiData({url: `/api/objectives`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.objectiveList = response.data;
                }
            });
        },

        getProjectList(){
            getApiData({url: `/api/projects`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.projectList = response.data;
                }
            });
        },

        getAllStaffList(){
            getApiData({url: `/api/staffs`, token: this.getToken()})
            .then((response)=>{
                if(response.data){
                    this.allStaffList = response.data;
                }
            });
        },

        getDepartmentList(){
            getApiData({url: `/api/departments`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.roleList = [];
                    this.selectedRole = null;
                    this.selectedDepartment = null;
                    this.departmentList = response.data;
                }
            });
        },

        departmentSelectChanged(){
            getApiData({url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.roleList = response.data;
                    this.selectedRole = null;
                }
            });
        },

        roleSelectChanged(){
            getApiData({url: `/api/staffs?department_id[]=${this.selectedDepartment.id}&role_id[]=${this.selectedRole.id}`, token: this.getToken()})
            .then((response)=>{
                if(response.success){
                    this.staffList = response.data;
                    this.selectedStaff = [];
                }
            });
        },

        addSelectedStaffToMeeting(){
            if(!this.selectedStaff.length > 0){
                this.showToastMessage("Please select staff first");
                return;
            }
            this.selectedStaff.forEach(staff => {
                this.meetingStaff.push({
                    'staff': staff,
                    'department': this.selectedDepartment,
                    'role': this.selectedRole
                });
            });

            this.selectedStaff = [];
        },

        removeMeetingStaffBtnClicked(index){
            this.meetingStaff.splice(index, 1);
        },

        objectiveSelectChanged(){
            this.objectiveKeys = this.selectedObjective.objective_keys;
            this.okrPoint = this.selectedObjective.okr_point;
        },

        removeSelectedObjectiveKey(id, index){
            this.selectedObjectiveKeys.splice(index, 1);
        },

        addInstructionBtnClicked(){
            // if(!this.selectedObjective){
            //     this.showToastMessage("Objective must be selected");
            //     return;
            // }
            if(!this.okrPoint){
                this.showToastMessage("OKR point must be assigned");
                return;
            }
            if(!this.selectedProject){
                this.showToastMessage("Project must be selected");
                return;
            }
            if(!this.selectedTag){
                this.showToastMessage("Stage must be selected");
                return;
            }
            if(!this.accountableStaff || !this.consultedStaff || !this.informedStaff || !this.responsibleStaff){
                this.showToastMessage("Assigned, accountable, consulted, informed and responsible staff must be selected");
                return;
            }
            if(!this.selectedPriority){
                this.showToastMessage("Priority must be selected");
                return;
            }
            if(this.keyResults.length < 1){
                this.showToastMessage("At least one objective key result must be entered");
                return;
            }
            if(!this.startDate || !this.dueDate){
                this.showToastMessage("Start date and due date are required");
                return;
            }
            if(!this.remark){
                this.showToastMessage("Remark must be entered");
                return;
            }

            if(!this.selectedSop){
                this.showToastMessage("SOP must be selected");
                return;
            }

            let instructionObjectiveKeys = [];
            this.keyResults.forEach(element => {
                instructionObjectiveKeys.push(element);
            });
            this.showToastMessage("Instruction can now be entered", "success", "OK");
            this.meetingInstructions.push({
                // "objective_id": this.selectedObjective.id, n
                "objective_name": this.objectiveName,
                "okr_point": this.okrPoint,
                "project_id": this.selectedProject.id,
                'stage': this.selectedTag.value,                
                "priority": this.selectedPriority.value,
                "sop_id": this.selectedSop.id,
                "role_id": this.selectedSop.role_id,
                "start_date": this.startDate,
                "due_date": this.dueDate,
                "remark": this.remark,
                "accountable": this.accountableStaff.id,
                "responsible_id": this.responsibleStaff.id,
                "consulted_id": this.consultedStaff.id,
                "informed_id": this.informedStaff.id,
                "instruction_objective_key": instructionObjectiveKeys,
                // "project": this.selectedProject,                
            });

            this.resetInputs();
        },

        removeMeetingInstructionBtnClicked(index){
            this.meetingInstructions.splice(index, 1);
        },

        async createBtnClicked(){
            if(this.meetingInstructions.length < 1){
                this.showToastMessage("At least one meeting instruction must be present");
                return;
            }
            if(!this.meetingMinutesText){
                this.showToastMessage("Meeting minute must be present");
                return;
            }
            if(this.meetingStaff.length < 2){
                this.showToastMessage("Meeting instruction cannot be created with less than 2 attendee");
                return;
            }
            if(this.kpiContainer.length < 1){
                this.showToastMessage("KPI Snapshots must be provided");
                return;
            }
            if(this.alignmentContainer.length < 1){
                this.showToastMessage("7S Alignments must be provided");
                return;
            }

            let attendances = [];
            this.meetingStaff.forEach(element => {
                attendances.push(element.staff.id);
            });
            let payload = {
                meeting_id: this.meetingId,
                meeting_minute: this.meetingMinutesText,
                attendances: attendances,
                kpi_snapshots: this.kpiContainer,
                instructions: this.meetingInstructions,
                alignments: this.alignmentContainer,
            };
            if(this.selectedOldMeeting){
                payload.old_meeting_id = this.selectedOldMeeting.id;
            }
            const response = await axios.post(
                "/api/meeting_minutes",
                // {
                //     meeting_id: this.meetingId,
                //     meeting_minute: this.meetingMinutesText,
                //     attendances: attendances,
                //     instructions: this.meetingInstructions
                // },
                payload,
                {
                    headers: {
                        Authorization: `Bearer ${this.getToken()}`,
                        "Content-Type": "application/json",
                    },
                }
            );
            if(response.status > 199 && response.status < 300){
                this.showToastMessage("OK", "success", "Success");
                return;
                window.location.replace("/meeting");
            }
        },

        resetInputs(){
            this.objectiveName = null;
            this.keyResults = [];
            this.selectedSop = null;
            this.selectedObjective = null;
            this.selectedObjectiveKeys = [];
            this.okrPoint = null;
            this.selectedProject = null;
            this.selectedTag = null;
            this.assignedStaff = null;
            this.priority = null;
            this.startDate = null;
            this.dueDate = null;
            this.remark = null;
            this.accountableStaff = null;
            this.responsibleStaff = null;
            this.consultedStaff = null;
            this.informedStaff = null;
        },

        showToastMessage(message, type = "warn", title = "Input Validation") {
            this.$notify({
                title: `${title}`,
                text: `${message}`,
                type: `${type}`
            });
        },

        async getSopList(){
            getApiData({url: `/api/get_sop`, token: this.getToken()})
            .then((response)=>{
                this.sopList = response.data;
            });
        },

        getOldMeetingsList(){
            getApiData({url: `/api/meetings`, token: this.getToken()})
            .then((response)=>{
                this.oldMeetings = response.data;                
            });
        },

        oldMeetingSelected(){
            getApiData({url: `/api/staff_by_meeting/${this.selectedOldMeeting.id}`, token: this.getToken()})
            .then((response)=>{
                // let meetingStaff = response.data;
                response.data.forEach(staff => {
                    this.meetingStaff.push({
                        'staff': {id: staff.id, name: staff.name},
                        'department': staff.department,
                        'role': staff.roles[0]
                    });
                });
                console.log(response.data);
            });

            // let participants = this.selectedOldMeeting.participants;

            // participants.forEach(participant => {                
            //     if(participant.staff){
            //         // console.log('staff exists');
            //         let staff = {
            //             'staff': {
            //                 id: participant.staff.id,
            //                 name: participant.staff.name,
            //             },
            //             'department': participant.staff.department,
            //             'role': participant.staff.roles[0]
            //         };                    
            //         this.meetingStaff.push(staff);
            //     }else{
            //         // console.log('no staff');
            //     }
            // });
        },

        getKpiSnapshots(){
            getApiData({url: `/api/kpi_snapshots`, token: this.getToken()})
            .then((response)=>{                
                this.kpiSnapshots = response.data;                
            });
        },

        createKpiSnapshot(){
            this.kpiSnapshotCreateLoading = true;
            if(!this.kpiSnapshotName){
                this.kpiSnapshotCreateLoading = false;
                this.showToastMessage("Name of the KPI Snapshot is required");
                return;
            }
            let formData = new FormData();
            formData.append('name', this.kpiSnapshotName);
            postApiData({url: `/api/kpi_snapshots`, form_data: formData, token: this.getToken()})
            .then((response)=>{
                this.kpiSnapshotCreateLoading = false;
                if(response.data){
                    this.kpiSnapshots.push(response.data);
                    this.selectedKpiSnapshot = response.data;
                }
            });
            document.getElementById('btn-close-create-kpi-snapshot-modal').click();
        },

        addKpiButtonClicked(){
            if(!this.selectedKpiSnapshot){
                this.showToastMessage("KPI metric cannot be empty");
                return;
            }
            if(this.kpiAmount < 1){
                this.showToastMessage("KPI amount must be greater than 0");
                return;
            }
            this.kpiContainer.push({
                'metric': this.selectedKpiSnapshot.name,
                'kpi_snapshot_id': this.selectedKpiSnapshot.id,
                'amount': this.kpiAmount,
                'value': this.kpiAmount
            });
            this.selectedKpiSnapshot = null;
            this.kpiMetric = null;
            this.kpiAmount = 0;
        },

        removeKpiObjBtnClicked(index){
            this.kpiContainer.splice(index, 1);
        },

        addAlignmentBtnClicked(){     
            // Check if a user with the name 'Bob' exists
            const exists = this.alignmentContainer
            .some(alignmentObj => alignmentObj.alignment.id === this.selectedAlignment.id);       
            if(exists){
                this.showToastMessage("This alignment already added");
                return;
            }
            if(!this.selectedAlignment){
                this.showToastMessage("Select a 7S alignment first");
                return;
            }
            if(!this.alignmentRemark){
                this.showToastMessage("Remark for 7S alignment cannot be empty");
                return;
            }
            this.alignmentContainer.push({
                'alignment': this.selectedAlignment,
                'alignment_id': this.selectedAlignment.id,
                'remark': this.alignmentRemark
            });
            this.selectedAlignment = null;
            this.alignmentRemark = null;
        },

        removeAlignmentObjBtnClicked(index){
            this.alignmentContainer.splice(index, 1);
        },

        getSevenSAlignments(){
            getApiData({url: `/api/alignments`, token: this.getToken()})
            .then((response)=>{                
                this.sevenSAlignments = response.data;                
            });
        },
    },
    created(){
        this.getDepartmentList();
        this.getAllStaffList();
        this.getObjectiveList();
        this.getProjectList();
        this.getSopList();
        this.getOldMeetingsList();
        this.getKpiSnapshots();
        this.getSevenSAlignments();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown, Tab });
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
