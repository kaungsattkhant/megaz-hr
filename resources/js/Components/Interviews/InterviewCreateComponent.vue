<template>
    <div class="px-0">
        <div class="mb-4 flex justify-between">
            <p class="text-lg font-semibold font-inter text-left">
                Create New Interview
            </p>
            <div class="flex justify-end gap-x-4 px-6">
                <p>
                    Total Marks : {{ total_mark + total_custom_mark }}
                </p>
            </div>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-4 container-card">

            <div class=" col-span-12 mb-6">
                <p class=" text-xl text-black">
                    Custom Question
                </p>
            </div>
            
            <div class="mb-4 col-span-3 pb-0 rounded-md row-span-2">
                <label for="" class="block text-sm text-black mb-3">
                    Question
                </label>
                <textarea name="" v-model="customQuestion" class="input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="1"></textarea>
                <!-- <input type="text" v-model="customQuestion" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"> -->
            </div>
            <div class="mb-6 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Type
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Type" v-model="selectedType" class="input-ui !text-black"
                    data-te-select-filter="true" @change="typeChange">
                        <option :value="type" v-for="(type, typeIndex) in typeList" :key="typeIndex">
                            {{ type.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-2 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Mark
                </label>
                <input type="number" v-model="customMark" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3">
                <label for="" class="block text-sm text-black mb-3">
                    &nbsp;
                </label>
                <button class="add-btn" @click="btnAddCustomQuestion">
                    Add
                </button>
            </div>
        </div>
        
        <div class=" mb-6 container-card pb-0.5 pt-8 px-7">
            <div class="mb-12" v-for="(question,questionIndex) in questionList">
                <div v-if="question.length > 0">
                    <div class="  mb-6">
                        <p class=" text-xl font-semibold text-black">
                            {{ question[0].type }}
                        </p>
                    </div>
                    <div class="contents" v-for="(q,qIndex) in question">
                        <div v-if="!q.is_custom" class="mb-8 pl-2">
                            <div class="  mb-3">
                                <p class=" text-base text-black">
                                    {{ q.question }}
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-y-4 gap-x-8 mb-6">
                                <label
                                class="inline-flex flex-grow-0 items-start space-x-2"
                                v-for="(answer, aIndex) in q.answers"
                                :key="aIndex"
                                >
                                <input
                                    type="radio"
                                    :name="'question-' + q.id"
                                    :value="answer"
                                    v-model="q.selected"
                                    :checked="answer.checked"
                                    @change="handleRadioChange(answer,q)"
                                    class="form-radio h-4 w-4 text-[#845adf] focus:ring-0 focus:shadow-none mt-0.5"
                                />
                                <p class="text-gray-600">{{ answer.answer }}</p>
                                </label>
                            </div>
                        </div>
                        <div class="flex gap-x-4 mb-5 pl-2" v-if="q.is_custom">
                            <div class=" min-w-[30%] max-w-[80%]"> 
                                <p  class="text-gray-800">{{ q.question }}</p>
                            </div>
                            <div class=" w-[10%] text-center">
                                <p>{{ q.mark }}</p>
                            </div>
                            <div class="w-[10%]">
                                <button  class="mx-4" @click="deleteItem(qIndex,question,q)">
                                    <i class="fal fa-times"></i>
                                </button>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="contents" v-else>
                    <div class="  mb-6">
                        <p class=" text-lg text-black">
                            {{ question.type_name }}
                        </p>
                    </div>
                    
                    <div class="flex gap-x-4 mb-5 pl-2" v-for="(q,cqIndex) in question.question">
                        <div class=" min-w-[30%] max-w-[80%]"> 
                            <p  class="text-gray-600">{{ q.question }}</p>
                        </div>
                        <div class=" w-[10%] text-center">
                            <p>{{ q.mark }}</p>
                        </div>
                        <div class="w-[10%]">
                            <button  class="mx-4" @click="deleteCustomQuestion(questionIndex,cqIndex,q)">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <table class="primary-table !border-0 text-left">
                <tbody>
                    <div class="contents" v-for="(item, index) in customQuestionList" :key="index">
                        <tr class=" !border-0">
                            <td class="whitespace-nowrap !border-0">
                                {{ item.question }}
                            </td>
                            <td class="whitespace-nowrap !border-0">
                                {{ item.type.name }}
                            </td>
                            <td class="whitespace-nowrap !border-0">
                                {{ item.mark }}
                            </td>
                            <td class="whitespace-nowrap !border-0">
                                <button  class="mx-4">
                                    <i class="fal fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </div>
                </tbody>
            </table> -->
            <!-- <div class="contents" v-for="(question,questionIndex) in customQuestionList">
                <div class="  mb-4">
                    <p class=" text-lg text-black">
                        {{ question.type }}
                    </p>
                </div>
                
                <div class="flex gap-x-4 mb-8">
                    <div class=" min-w-[30%] max-w-[80%]"> 
                        <p  class="text-gray-600">{{ question.question }}</p>
                    </div>
                    <div class=" w-[10%] text-center">
                        <p>{{ question.mark }}</p>
                    </div>
                    <div class="w-[10%]">
                        <button  class="mx-4">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>
                </div>
            </div> -->
        </div>
        <div class="px-4 mb-8">
            <button class="add-btn" @click="btnClickedCreateInterview">
                Done
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
    props: ["interviewId","cvId"],
    data() {
        return {
            typeList: [
                {value: 'EQ', name: 'EQ'},
                {value: 'IT', name: 'IT'}
            ],
            questionList: [],
            customQuestionList: [],

            customQuestion: null,
            selectedType: null,
            customMark: null,

            detail: null,
            total_mark: 0,
            total_custom_mark: 0,
            test:null,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken']),
        async getDetail() {
            let response = await getApiData({ url: `/api/hr/interviews/${this.interviewId}`, token: this.getToken() });
            if (response.data) {
                this.detail = response.data;
                setTimeout(() => {
                    // this.addDetail(response.data);
                    this.questionList = response.data.grouped_exam_questions
                }, 500);
            }
        },
        handleRadioChange(answer,q){
            // this.questionList[first].questions[second].answers.forEach((a, i) => {
            //     a.checked = i === third
            // })
            // answer.checked = 'yellow';
            q.answer_id = answer.id
            this.total_mark = this.totalMark
        },
        btnAddCustomQuestion(){
            if(!this.customQuestion){
                this.alertValidationMessage(`Question`);
                return 1;
            }
            if(!this.selectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            if(this.customMark < 1){
                this.alertValidationMessage(`Mark`);
                return 1;
            }
            let isSameType = false;
            let isSameCustom = false;
            let customIndex = null;
            this.questionList.forEach((question,index) => {
                if(question.length>0 && question[0].type === this.selectedType.value){
                    console.log( '1 ' + question[0].type)
                    question.push({
                        question: this.customQuestion,
                        type: this.selectedType.value,
                        mark: this.customMark,
                        is_custom: true,
                    })
                    isSameType = true;
                }
                if(question.type_name){
                    isSameCustom = true;
                    customIndex = index
                }
                
            });
            if(!isSameType && !isSameCustom){
                this.questionList.push({
                    type_name: this.selectedType.value,
                    is_custom: true,
                    question: [
                        {
                        question: this.customQuestion,
                        type: this.selectedType.value,
                        mark: this.customMark
                    }
                    ]
                })
            };
            if(!isSameType && isSameCustom){
                this.questionList[customIndex].question.push({
                    question: this.customQuestion,
                    type: this.selectedType.value,
                    mark: this.customMark
                })
            };
            // this.customQuestionList.push({
            //     question: this.customQuestion,
            //     type: this.selectedType.value,
            //     mark: this.customMark
            // })
            this.total_custom_mark += this.customMark
            this.customQuestion = null;
            this.selectedType = null;
            this.customMark = 0;
        },
        deleteItem(index,list,item){
            this.total_custom_mark -= item.mark
            if(index != -1){
                list.splice(index, 1);
            }
        },
        deleteCustomQuestion(questionIndex,customIndex,item){
            this.total_custom_mark -= item.mark
            if(questionIndex != -1){
                if(this.questionList[questionIndex].question.length < 2){
                    this.questionList.splice(questionIndex, 1);
                }
                else{
                    if(customIndex != -1){
                        this.questionList[questionIndex].question.splice(customIndex, 1);
                    }
                }
            }
        },
        
        



        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
        btnClickedCreateInterview() {
            let isValid = true;
            this.questionList.forEach(question => {
                if(Array.isArray(question)){
                    question.forEach(q => {
                        if (!q.is_custom && (!q.selected || !q.selected.id)) {
                            isValid = false;
                        }
                    });
                }
            })
            console.log(isValid)
            if(!isValid){
                this.alertValidationMessage(`Answer`);
                return 1;
            }
            
            this.createInterview();
        },

        async createInterview() {
            // formData.append('department_id', this.selectedDepartment.id);
            // formData.append('skills', JSON.stringify(this.selectedSkills));
            let interview_answers = [];
            let custom_questions = [];
            if(Array.isArray(this.questionList)){
                this.questionList.forEach((question)=>{
                    if(question.is_custom && Array.isArray(question.question)){
                        question.question.forEach(cus => {
                            custom_questions.push({
                                question: cus.question,
                                type: cus.type,
                                mark: cus.mark
                            })
                        });
                    }
                    else if (Array.isArray(question)){
                        question.forEach(q => {
                            if(q.is_custom){
                                custom_questions.push({
                                    question: q.question,
                                    type: q.type,
                                    mark: q.mark
                                })
                            }
                            else{
                                interview_answers.push({
                                    exam_question_id: q.id,
                                    answer_id: q.answer_id
                                })
                            }
                        });
                    }
                });
            }
            let total = 0;
            total = this.total_mark + this.total_custom_mark;
            let formData = new FormData();
            formData.append('staff_id', this.cvId);
            formData.append('exam_id', this.interviewId);
            formData.append('total_mark', total);
            formData.append('interview_answers', JSON.stringify(interview_answers));
            formData.append('custom_questions', JSON.stringify(custom_questions));
            let response = await postApiData({ url: '/api/hr/interviews', form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace('/interview/result');
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
    },
    computed: {
        selectedAnswers() {
            return this.questionList
            .flat() // Flatten the 2D array into a 1D list of questions
            .filter(q => q.selected) // Only include questions that have a selected answer
            .map(q => ({
                question_id: q.id,
                answer_id: q.selected.id,
                mark: q.selected.mark
            }));
        },
        totalMark() {
            // Flatten the 2D array
            const flatQuestions = this.questionList.flat()
            // Sum up the selected marks
            return flatQuestions.reduce((sum, q) => {
                return sum + (q.selected?.mark || 0)
            }, 0)
        }
    },
    created() {
        this.getDetail();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
