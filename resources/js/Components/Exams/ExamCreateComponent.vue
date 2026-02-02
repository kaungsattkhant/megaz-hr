<template>
    <div class="px-0">
        <div class="mb-4">
            <p class="text-lg font-semibold font-inter">
                Add New Exam
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white p-8 rounded-md">
            <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Exam Name
                </label>
                <input type="text" v-model="name"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
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
            </div><div class="col-span-3"></div>
            
                

            <div class="mb-6 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Type
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" v-model="selectedType" class="input-ui !text-black"
                    data-te-select-filter="true" >
                        <option :value="type.value" v-for="(type, typeIndex) in typeList" :key="typeIndex">
                            {{ type.name }}
                        </option>
                    </select>
                </div>
            </div><div class="col-span-9"></div>

            <!-- skill set for exam -->
            <div class="contents" v-if="selectedType == 'exam'">
                <div class="mb-4 col-span-3 pb-0 rounded-md">
                    <label class="label-form mb-3">Skillset</label>
                    <multiselect
                    v-model="selectedSkillset"
                    :options="skillsetList"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="true"
                    placeholder="Select Skillset"
                    label="skill" class=" capitalize"
                    track-by="id"
                    :preselect-first="false">
                    </multiselect>
                </div>

                <div class="col-span-6" :class="selectedSkillsetList.length < 1 ? 'mb-10' : ''">
                    <button type="button" class="mt-8 add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10" @click="addSkillsetBtnClicked()" >
                        Add
                    </button>
                </div><div class="col-span-3"></div>

                <div class=" col-span-12 mb-10" v-show="selectedSkillsetList.length > 0">
                    <table class="min-w-[50%] text-sm font-light ml-2">
                        <thead class="font-medium text-left ">
                            <tr>
                                <th scope="col" class=" pr-6 pl-2 py-3 ">
                                    Skillset
                                </th>
                                <th scope="col" class=" px-6 py-3">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="" v-for="(skillset, skillsetIndex) in selectedSkillsetList" :key="skillsetIndex">
                                <td class=" pr-6 pl-2 py-3 font-medium ">
                                    {{ skillset.name }}
                                </td>
                                <td class=" px-6 py-3 font-medium text-left">
                                    <button>
                                        <i class="fal fa-times  pr-3" @click="deleteSeleted(skillsetIndex,selectedSkillsetList)" ></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- grade list -->
            <div class="contents">
                <div class="mb-4 col-span-3 pb-0 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Mark
                    </label>
                    <input type="number" v-model="mark" autocomplete="off" min="0" max="100"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                </div>
                <div class="mb-4 col-span-3 pb-0 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Grade
                    </label>
                    <input type="text" v-model="grade" autocomplete="off"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                </div>
                
                <div class="col-span-3" :class="selectedGradeList.length < 1 ? 'mb-10' : ''">
                    <label class="label-form mb-3">&nbsp;</label>
                    <button type="button" class=" add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10" @click="btnclickedAddGrade()" >
                        Add
                    </button>
                </div><div class="col-span-3"></div>
                <div class=" col-span-12 mb-8" v-show="selectedGradeList.length > 0">
                    <table class="min-w-[50%] text-sm font-light ml-2">
                        <thead class="font-medium text-left ">
                            <tr>
                                <th scope="col" class=" pr-6 pl-2 py-4 ">
                                    Mark
                                </th>
                                <th scope="col" class=" px-6 py-4 ">
                                    Grade
                                </th>
                                <th scope="col" class=" px-6 py-4">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(grade,gradeIndex) in selectedGradeList" >
                                <td class=" pr-6 pl-2 py-3 font-medium ">
                                    {{ grade.mark }}
                                </td>
                                <td class=" px-6 py-3 font-medium capitalize">
                                    {{ grade.grade }}
                                </td>
                                <td class=" px-6 py-3 font-medium">
                                    <button>
                                        <i class="fal fa-times" data-te-toggle="modal"
                                        data-te-target="#deleteModal" @click="gradeDeleteBtnClicked(gradeIndex)" ></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="contents">
                <div class="mb-4 col-span-3 pb-0 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Question Name
                    </label>
                    <input type="text" v-model="question" autocomplete="off"
                        class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                </div>
                <div class="mb-6 col-span-3 pb-6 rounded-md">
                    <label for="" class="block text-sm text-black mb-3">
                        Type
                    </label>
                    <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type" v-model="selectedQuestionType" class="input-ui !text-black"
                        data-te-select-filter="true" @change="typeChange">
                            <option :value="type" v-for="(type, typeIndex) in questionTypeList" :key="typeIndex">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-3">
                    <label class="label-form mb-3">&nbsp;</label>
                    <button type="button" class=" add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 " @click="btnclickedAddQuestion()" >
                        Add
                    </button>
                </div><div class="col-span-3"></div>
                <div class=" col-span-12 mb-6" v-show="selectedQuestionList.length > 0">
                    <table class="min-w-[50%] text-sm font-light ml-2">
                        <thead class="font-medium text-left ">
                            <tr>
                                <th scope="col" class=" pr-6 pl-2 py-4 ">
                                    Question Name
                                </th>
                                <th scope="col" class=" pr-6 pl-2 py-4 whitespace-nowrap">
                                    Question Type
                                </th>
                                <th scope="col" class=" px-6 py-4 whitespace-nowrap">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(question,questionIndex) in selectedQuestionList" >
                                <td class=" pr-6 pl-2 py-3 font-medium ">
                                    {{ question.question }}
                                </td>
                                <td class=" pr-6 pl-2 py-3 font-medium whitespace-nowrap">
                                    {{ question.type }}
                                </td>
                                <td class=" px-6 py-3 font-medium relative whitespace-nowrap">
                                    <input :checked="question.is_active == 1" @change="isActiveToggled(question)"
                                            class="mt-[0.1rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-white before:pointer-events-none before:absolute before:h-3.5 relative mx-3
                                            before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:-mt-[0.2875rem] after:h-5 after:-left-1
                                            after:w-5 after:rounded-full after:border-none after:bg-black after:transition-[background-color_0.2s,transform_0.2s]
                                            after:content-[''] checked:bg-black checked:after:absolute checked:after:z-[2] checked:after:-mt-[4px] checked:after:ms-[1.3625rem]
                                            checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-black checked:after:shadow-switch-1
                                            checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100 focus:ring-0 focus:shadow-none
                                            focus:before:opacity-[0.12]  focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:bg-black checked:hover:bg-black
                                            focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                             checked:focus:before:ms-[1.3625rem] checked:focus:before:scale-100
                                            checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] "
                                            type="checkbox" role="switch" />
                                    <button data-te-toggle="modal" data-te-target="#add_question_modal" class="mx-4"
                                        @click="addAnswerBtnClicked(question,questionIndex)">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <button>
                                        <i class="fal fa-times" data-te-toggle="modal"
                                        data-te-target="#deleteModal" @click="questionDeleteBtnClikced(questionIndex)" ></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <button class="add-btn" @click="createBtnClicked">
                Create 
            </button>
        </div>
    </div>


    <button data-te-toggle="modal" data-te-target="#add_question_modal" class="mr-4 hidden">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Modal -->
    <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="add_question_modal" tabindex="-1" aria-labelledby="add_question_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_question_modalLabel">
                            Question
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close" id="close_add_answer_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="grid grid-cols-5 gap-x-5 gap-y-4 text-sm">
                            <div class="mb-4 col-span-3 pb-0 rounded-md">
                                <label for="" class="block text-sm text-black mb-3">
                                    Answer
                                </label>
                                <input type="text" v-model="answer" autocomplete="off"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            <div class="mb-4 col-span-1 pb-0 rounded-md">
                                <label for="" class="block text-sm text-black mb-3">
                                    Mark
                                </label>
                                <input type="number" v-model="answerMark" autocomplete="off"
                                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                            </div>
                            
                            <div class="col-span-1 mb-4">
                                <label class="label-form mb-3">&nbsp;</label>
                                <button type="button" class=" add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 h-10" @click="btnClickedAddAnswer()" >
                                    Add
                                </button>
                            </div>


                            <div class="col-span-3">
                                <p>
                                    Answer
                                </p>
                            </div>
                            <div class="col-span-1">
                                <p>
                                    Mark
                                </p>
                            </div>
                            <div class="col-span-1">
                                <p>
                                    Answer
                                </p>
                            </div>
                            <div class="contents" v-if="selectedAnswerList.length > 0" v-for="(q,index) in selectedAnswerList">
                                <div class="col-span-3">
                                    <p>
                                        {{ q.answer }}
                                    </p>
                                </div>
                                <div class="col-span-1">
                                    <p>
                                        {{ q.mark }}
                                    </p>
                                </div>
                                <div class="col-span-1">
                                    <button>
                                        <i class="fal fa-times" @click="deleteSeleted(index,selectedAnswerList)" ></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="addAnswerToQuestion()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
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
                        Delete {{ isQuestion ? 'Question' : 'Grade' }} ?
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
            departmentList: [],
            roleList: [],
            skillsetList:[],
            typeList: [
                {value: 'exam', name: 'Exam'},
                {value: 'interview', name: 'Interview'}
            ],
            questionTypeList: [
                {value: 'EQ', name: 'EQ'},
                {value: 'IT', name: 'IT'}
            ],

            selectedDepartment: null,
            selectedRoles: null,
            selectedType: null,
            selectedSkillset: null,
            mark: null,
            grade: null,
            question: null,
            selectedQuestionType: null,

            selectedSkillsetList: [],
            selectedGradeList: [],
            selectedQuestionList: [],

            answer: null,
            answerMark: null,
            selectedQuestionIndex: null,
            selectedQuestionDetail: null,
            selectedAnswerList: [],


            
            name: null,

            deleteIndex: null,
            isQuestion: false,

            test:null,
            
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        

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
            this.selectedSkillsetList = [];
            // let rolesResponse = await getApiData({ url: `/api/roles?department_id=${this.selectedDepartment.id}`, token: this.getToken() });
            // if (rolesResponse.data) {
            //     this.roleList = rolesResponse.data;
            // }
        },
        async roleChange(){
            const response = await getApiData({ url: '/api/hr/departments/' + this.selectedDepartment.id + '/roles/' + this.selectedRoles.id + '/skills', token: this.getToken() });
            if(response.data){
                this.skillsetList = response.data.data;
            }
            this.selectedSkillsetList = [];
        },
        typeChange(){
            this.selectedSkillsetList = [];
            // if(this.selectedType === 'exam'){

            // }
        },

        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        addSkillsetBtnClicked(){
            if(!this.selectedSkillset){
                this.alertValidationMessage(`Skillset`);
                return;
            }
            else{
                this.selectedSkillsetList.push({
                    name: this.selectedSkillset.skill,
                    id: this.selectedSkillset.id,
                    role_id: this.selectedSkillset.role_id,
                });
                this.selectedSkillset = null;
                // this.selectedItemBrands = [];
            }
        },

        btnclickedAddGrade(){
            if(!this.mark){
                this.alertValidationMessage(`Mark`);
                return;
            }
            else if(!this.grade){
                this.alertValidationMessage(`Grade`);
                return;
            }
            else{
                this.selectedGradeList.push({
                    mark: this.mark,
                    grade: this.grade,
                });
                this.mark = null;
                this.grade = null;
            }
        },
        btnclickedAddQuestion(){
            if(!this.question){
                this.alertValidationMessage(`Question Name`);
                return 1;
            }

            if(!this.selectedQuestionType){
                this.alertValidationMessage(`Question Type`);
                return 1;
            }
            else{
                this.selectedQuestionList.push({
                    question: this.question,
                    type: this.selectedQuestionType.value,
                    answers: [],
                    is_active: 1,
                });
                this.question = null;
                this.selectedQuestionType = null;
            }
        },
        addAnswerBtnClicked(question,index){
            this.selectedQuestionIndex = index;
            this.selectedQuestionDetail = question;
            this.answer = null;
            this.answerMark = null;
            if(this.selectedQuestionList[index].answers){
                this.selectedAnswerList = this.selectedQuestionList[index].answers
            }
            else{
                this.selectedAnswerList = [];
            }
        },
        btnClickedAddAnswer(){
            if(!this.answer){
                this.alertValidationMessage(`Answer`);
                return 1;
            }
            if(!this.answerMark){
                this.alertValidationMessage(`Mark`);
                return 1;
            }
            this.selectedAnswerList.push({
                answer: this.answer,
                mark: this.answerMark,
            })
            this.answer = null;
            this.answerMark = null;
        },
        addAnswerToQuestion(){
            if(this.selectedAnswerList.length < 1){
                this.alertValidationMessage(`Answers`);
                return 1;
            }
            this.selectedQuestionList[this.selectedQuestionIndex].answers = this.selectedAnswerList
            document.getElementById("close_add_answer_modal").click();
        },
        isActiveToggled(question) {
            let index = this.selectedQuestionList.findIndex(item => item == question);
            if (index != -1) {
                if (this.selectedQuestionList[index].is_active == 1) {
                    this.selectedQuestionList[index].is_active = 0;
                }
                else {
                    this.selectedQuestionList[index].is_active = 1;
                }

                // let url = `/api/is_active`;
                // let formData = new FormData();
                // formData.append('id', id);
                // formData.append('type', 'staff');
                // let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
            }
        },
        async createBtnClicked(){
            if(!this.name){
                this.alertValidationMessage(`Name`);
                return 1;
            }
            if(!this.selectedRoles){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            if(!this.selectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            if(this.selectedType == 'exam' && this.selectedSkillsetList.length < 1){
                this.alertValidationMessage(`Skillset`);
                return 1;
            }
            if(this.selectedGradeList.length < 1){
                this.alertValidationMessage(`Grade`);
                return 1;
            }
            if(this.selectedQuestionList.length < 1){
                this.alertValidationMessage(`Question`);
                return 1;
            }
            const allAnswered = this.selectedQuestionList.every(q => Array.isArray(q.answers) && q.answers.length > 0);;
            if (!allAnswered) {
                this.alertValidationMessage(`Answer`);
                return 1;
            }
            let exam_skills = [];
            this.selectedSkillsetList.forEach(skill => {
                exam_skills.push(skill.id)
            });
            let formData = new FormData();
            formData.append("name", this.name);
            formData.append("role_id", this.selectedRoles.id);
            // formData.append("phone_number", this.phoneNumber);
            formData.append("type", this.selectedType);
            formData.append("exam_skills", JSON.stringify(exam_skills));
            formData.append("grades", JSON.stringify(this.selectedGradeList));
            formData.append("exam_questions", JSON.stringify(this.selectedQuestionList));
            
            let url = `/api/hr/exams`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                window.location.replace("/exam");
            }else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },

        gradeDeleteBtnClicked(index){
            this.deleteIndex = index;
            this.isQuestion = false;
        },
        questionDeleteBtnClikced(index){
            this.deleteIndex = index;
            this.isQuestion = true;
        },

        deleteItem(){
            if(this.isQuestion){
                this.selectedQuestionList.splice(this.deleteIndex, 1);
            }
            else{
                this.selectedGradeList.splice(this.deleteIndex, 1);
            }
        },
        deleteSeleted(index,list){
            if(index != -1){
                list.splice(index, 1);
            }
        },
    },

    created(){
        this.getDepartmentList();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
