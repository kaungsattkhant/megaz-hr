<template>
    <div class="px-0">
        <div class="mb-4">
            <p class="text-lg font-semibold font-inter">
                Meeting Minutes Detail
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white p-8 rounded-md">
            <div class="mb-4 col-span-12">
                <p class="text-lg font-normal font-inter">
                    Meeting : {{ theMeeting?.title }}
                </p>
            </div>            
            <div class=" col-span-12 mb-10">
                <label class="label-form mb-3">Attendees</label>
                <table class="min-w-[50%] text-sm font-light ml-2">
                    <thead class="font-medium text-left ">
                        <tr>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Name
                            </th>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Department
                            </th>
                            <th scope="col" class=" pr-6 pl-2 py-3 ">
                                Role
                            </th>
                            <th scope="col" class=" px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="(staff, index) in meetingAttendees" >
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                {{ staff.name }}
                            </td>
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                <!-- {{ staff.role.name }} -->
                            </td>
                            <td class=" pr-6 pl-2 py-3 font-medium ">
                                <!-- {{ staff.staff.name }} -->
                            </td>
                            <td class=" px-6 py-3 font-medium text-left">
                                <!-- <button @click="removeMeetingStaffBtnClicked(index)">
                                    <i class="fal fa-times  pr-3" ></i>
                                </button> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- <div class="mb-0 col-span-7 rounded-md">
                <label for="" class="label-form mb-3">
                    Meeting Minutes
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <textarea type='text' v-model="meetingMinutesText" class="input-ui w-full !p-1 text-xs" rows="8" placeholder="Description" ></textarea>
                </div>

            </div> -->

            <hr class="col-span-12">

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

                    <div class="mb-4 col-span-12">
                        <p class="text-lg font-normal font-inter">
                            KPI Snapshots
                        </p>
                    </div>     

                    <div class="mb-0 col-span-9 rounded-md">
                        <table class="min-w-[50%] text-sm font-light ml-2">
                            <thead class="font-medium text-left ">
                                <tr>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Metric
                                    </th>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Amount
                                    </th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(kpiObj, index) in meetingMinutesKpiSnapshots" >
                                    <td class=" pr-6 pl-2 py-3 font-medium ">
                                        {{ kpiObj.name }}
                                    </td>
                                    <td class=" pr-6 pl-2 py-3 font-medium ">
                                        {{ kpiObj.value }}
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

                    <div class="mb-0 col-span-9 rounded-md">
                        <table class="min-w-[50%] text-sm font-light ml-2">
                            <thead class="font-medium text-left ">
                                <tr>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Alignment
                                    </th>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Remark
                                    </th>                                 
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(alignmentObj, index) in meetingMinutesAlignments" >
                                    <td class=" pr-6 pl-2 py-3 font-medium ">
                                        {{ alignmentObj.name }}
                                    </td>
                                    <td class=" pr-6 pl-2 py-3 font-medium ">
                                        {{ alignmentObj.remark }}
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
                            Instructions
                        </p>
                    </div>

                    <div class="mb-4 col-span-12" v-for="(instruction, index) in meetingMinutesInstructions">
                        <p class="text-lg font-normal font-inter">
                           Objective Name: {{ instruction.objective.objective_name }}
                        </p>

                        <label class="text-sm"> Objective Keys </label>
                        <table class="min-w-[50%] text-sm font-light ml-2">
                            <thead class="font-medium text-left ">
                                <tr v-for="objKey in instruction.instruction_objective_key">
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ objKey.name }}
                                    </th>
                                </tr>
                            </thead>
                        </table>

                        <p class="text-lg font-normal font-inter">
                            Project: {{ instruction.project.name }}
                        </p>

                        <label class="text-sm"> RACI </label>
                        <table class="min-w-[50%] text-sm font-light ml-2">
                            <thead class="font-medium text-left ">
                                <tr>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Responsible
                                    </th>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Accountable
                                    </th>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Consulted
                                    </th>
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Informed
                                    </th>  
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        OKR Points
                                    </th>          
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Start Date
                                    </th>      
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Due Date
                                    </th>  
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Stage
                                    </th>    
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        Remark
                                    </th>                                
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">
                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ instruction.objective.responsible.name }}
                                    </th>

                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ instruction.objective.accountable.name }}
                                    </th>

                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ instruction.objective.consulted.name }}
                                    </th>

                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ instruction.objective.informed.name }}
                                    </th>

                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ instruction.objective.okr_point }}
                                    </th>

                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ instruction.objective.start_date }}
                                    </th>

                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ instruction.objective.due_date }}
                                    </th>

                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ instruction.objective.stage }}
                                    </th>

                                    <th scope="col" class=" pr-6 pl-2 py-3 ">
                                        {{ instruction.objective.remark }}
                                    </th>                                                               
                                </tr>                               
                            </tbody>
                        </table>

                        <!-- <p class="text-lg font-normal font-inter">
                            {{ instruction.objective.objective_name }}
                        </p> -->
                    </div>

                    <!-- <hr class="col-span-12"> -->

                    </div>   
                </div>                       
            </div>

            <hr class="col-span-12 my-6">
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

            theMeeting: null,
            theOldMeeting: null,
            meetingAttendees: [],
            meetingMinutesAlignments: [],
            meetingMinutesInstructions: [],
            meetingMinutesKpiSnapshots: [],
            meetingMinutes: null,
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

        showToastMessage(message, type = "warn", title = "Input Validation") {
            this.$notify({
                title: `${title}`,
                text: `${message}`,
                type: `${type}`
            });
        },        

        getMeetingDetail(){
            getApiData({url: `/api/meeting_minutes/${this.meetingId}`, token: this.getToken()})
            .then((response)=>{
                if(response.data){                    
                    this.meetingAttendees = response.data.attendances;
                    this.meetingMinutesAlignments = response.data.alignments;
                    this.meetingMinutesInstructions = response.data.instructions;
                    this.meetingMinutesKpiSnapshots = response.data.kpi_snapshots;
                    this.theMeeting = response.data.meeting;
                    this.theOldMeeting = response.data.old_meeting;

                }                
            });
        }
    },

    created(){        
        this.getMeetingDetail();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown, Tab });
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
