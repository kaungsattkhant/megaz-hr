<template>

    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Project Instructions
                </p>
            </div>
            <div class="btn-container">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
                </div>
                <div class="flex pr-0 gap-x-4">
                    <!-- <div class=" !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Status" @change="typeFilter"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type" v-for="(type, typeIndex) in typeList" :key="typeIndex"> {{
                                type.name }} </option>
                        </select>
                    </div>
                    <div class=" !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Department"
                            @change="departmentFilter" data-te-select-filter="true" name="" id=""
                            v-model="selectedDepartment" class="input-ui">
                            <option :value="department" v-for="(department, departmentIndex) in departmentList"
                                :key="departmentIndex"> {{ department.name }} </option>
                        </select>
                    </div>
                    <div class=" !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Role" @change="roleFilter"
                            data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui">
                            <option :value="role" v-for="(role, roleIndex) in roleList" :key="roleIndex"> {{ role.name }}
                            </option>
                        </select>
                    </div> -->
                    <!-- <button data-te-toggle="modal" data-te-target="#addProjectModal" class="add-btn  h-8 whitespace-nowrap">
                        Add New
                    </button> -->
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div v-if="plans.length > 0"> Plans </div>
                <div class="table-container" v-if="plans.length > 0">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Objective
                                </th>
                                <th scope="col" class="">
                                    Keys
                                </th>
                                <th scope="col" class="">
                                    Priority
                                </th>
                                <th scope="col" class="">
                                    Start
                                </th>
                                <th scope="col" class="">
                                    Due
                                </th>
                                <th scope="col" class="">
                                    Remark
                                </th>
                                <th scope="col" class="">
                                    OKR Points
                                </th>
                                <th scope="col" class="">
                                    Assigned To
                                </th>                                                       
                                <th scope="col" class="">
                                    Responsible By
                                </th>
                                <th scope="col" class="">
                                    Accountable By
                                </th>
                                <th scope="col" class="">
                                    Consulted To
                                </th>
                                <th scope="col" class="">
                                    Informed To
                                </th>

                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <div class="contents" v-for="(item, index) in plans" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">                                        
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.objective.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-for="(key, index) in item.objective_keys">
                                            {{ key.name }}
                                        </span>                                        
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.priority }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.start_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.due_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.remark }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.okr_points }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.assigned" > {{ item.assigned.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.responsible" > {{ item.responsible.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.accountable" > {{ item.accountable.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.consulted" > {{ item.consulted.name }} </span>                                        
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.informed" > {{ item.informed.name }} </span>                                        
                                    </td>        
                                </tr>
                            </div>
                        </tbody>
                    </table>                    
                </div>

                <div v-if="dos.length > 0"> Dos </div>
                <div class="table-container" v-if="dos.length > 0">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Objective
                                </th>
                                <th scope="col" class="">
                                    Keys
                                </th>
                                <th scope="col" class="">
                                    Priority
                                </th>
                                <th scope="col" class="">
                                    Start
                                </th>
                                <th scope="col" class="">
                                    Due
                                </th>
                                <th scope="col" class="">
                                    Remark
                                </th>
                                <th scope="col" class="">
                                    OKR Points
                                </th>
                                <th scope="col" class="">
                                    Assigned To
                                </th>                                                       
                                <th scope="col" class="">
                                    Responsible By
                                </th>
                                <th scope="col" class="">
                                    Accountable By
                                </th>
                                <th scope="col" class="">
                                    Consulted To
                                </th>
                                <th scope="col" class="">
                                    Informed To
                                </th>

                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <div class="contents" v-for="(item, index) in dos" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">                                        
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.objective.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-for="(key, index) in item.objective_keys">
                                            {{ key.name }}
                                        </span>                                        
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.priority }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.start_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.due_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.remark }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.okr_points }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.assigned" > {{ item.assigned.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.responsible" > {{ item.responsible.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.accountable" > {{ item.accountable.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.consulted" > {{ item.consulted.name }} </span>                                        
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.informed" > {{ item.informed.name }} </span>                                        
                                    </td>        
                                </tr>
                            </div>
                        </tbody>
                    </table>                    
                </div>

                <div v-if="checks.length > 0"> Checks </div>
                <div class="table-container" v-if="checks.length > 0">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Objective
                                </th>
                                <th scope="col" class="">
                                    Keys
                                </th>
                                <th scope="col" class="">
                                    Priority
                                </th>
                                <th scope="col" class="">
                                    Start
                                </th>
                                <th scope="col" class="">
                                    Due
                                </th>
                                <th scope="col" class="">
                                    Remark
                                </th>
                                <th scope="col" class="">
                                    OKR Points
                                </th>
                                <th scope="col" class="">
                                    Assigned To
                                </th>                                                       
                                <th scope="col" class="">
                                    Responsible By
                                </th>
                                <th scope="col" class="">
                                    Accountable By
                                </th>
                                <th scope="col" class="">
                                    Consulted To
                                </th>
                                <th scope="col" class="">
                                    Informed To
                                </th>

                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <div class="contents" v-for="(item, index) in checks" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">                                        
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.objective.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-for="(key, index) in item.objective_keys">
                                            {{ key.name }}
                                        </span>                                        
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.priority }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.start_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.due_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.remark }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.okr_points }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.assigned" > {{ item.assigned.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.responsible" > {{ item.responsible.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.accountable" > {{ item.accountable.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.consulted" > {{ item.consulted.name }} </span>                                        
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.informed" > {{ item.informed.name }} </span>                                        
                                    </td>        
                                </tr>
                            </div>
                        </tbody>
                    </table>                    
                </div>

                <div v-if="acts.length > 0"> Acts </div>
                <div class="table-container" v-if="acts.length > 0">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Objective
                                </th>
                                <th scope="col" class="">
                                    Keys
                                </th>
                                <th scope="col" class="">
                                    Priority
                                </th>
                                <th scope="col" class="">
                                    Start
                                </th>
                                <th scope="col" class="">
                                    Due
                                </th>
                                <th scope="col" class="">
                                    Remark
                                </th>
                                <th scope="col" class="">
                                    OKR Points
                                </th>
                                <th scope="col" class="">
                                    Assigned To
                                </th>                                                       
                                <th scope="col" class="">
                                    Responsible By
                                </th>
                                <th scope="col" class="">
                                    Accountable By
                                </th>
                                <th scope="col" class="">
                                    Consulted To
                                </th>
                                <th scope="col" class="">
                                    Informed To
                                </th>

                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <div class="contents" v-for="(item, index) in acts" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">                                        
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.objective.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-for="(key, index) in item.objective_keys">
                                            {{ key.name }}
                                        </span>                                        
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.priority }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.start_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.due_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.remark }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ item.okr_points }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.assigned" > {{ item.assigned.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.responsible" > {{ item.responsible.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.accountable" > {{ item.accountable.name }} </span>                                        
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <span v-if="item.consulted" > {{ item.consulted.name }} </span>                                        
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="item.informed" > {{ item.informed.name }} </span>                                        
                                    </td>        
                                </tr>
                            </div>
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>
        
</template>

<script>
import Multiselect from 'vue-multiselect';
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import { getCurrentTime, getCurretDateTime } from "../../utilities/datetime-helpers";
import LoadingButton from '../Common/LoadingButton.vue';

export default {
    props: ["projectId"],

    components: {
        Multiselect,
        LoadingButton
    },
    data() {
        return {
            plans: [],
            dos: [],
            checks: [],
            acts: [],
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment', 'getToken']),

        getProjectDetail(){
            getApiData({url: `/api/projects/${this.projectId}/instruction_by_project`, token: this.getToken()})
            .then((response)=>{
                if(response.data){
                    if(response.data.plan){
                        this.plans = response.data.plan;
                    }
                    if(response.data.do){
                        this.dos = response.data.do;
                    }
                    if(response.data.check){
                        this.checks = response.data.check;
                    }
                    if(response.data.act){
                        this.acts = response.data.act;
                    }
                }
            });
        },

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });
        },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });

    },
    created() {        
        this.getProjectDetail();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
