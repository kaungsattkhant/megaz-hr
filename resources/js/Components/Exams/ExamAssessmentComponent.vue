<template>
    <div class="px-0">
        <div class="mb-4 flex justify-between title-container-card">
            <p class="text-lg font-semibold font-inter text-left">
                Exam Assessment
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
            <div class="mb-6 col-span-3 pb-0 rounded-md">
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
            <div class="contents" v-for="(q,qIndex) in questionList">
                <div class="mb-8 pl-2">
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
            <div class="contents" v-if="customQuestionList.length > 0">
                <div v-for="(customQuestion,questionIndex) in customQuestionList" :key="questionIndex">
                    <div class="  mb-6">
                        <p class=" text-lg text-black">
                            {{ customQuestion.type }}
                        </p>
                    </div>
                    
                    <div class="flex gap-x-4 mb-5 pl-2" v-for="(q,cqIndex) in customQuestion.questions">
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
            
        </div>
        <div class="px-4 mb-8">
            <button class="add-btn" @click="btnClickedAssessExam">
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
    props: ["staffId","examId"],
    data() {
        return {
            typeList: [
                {value: 'EQ', name: 'EQ'},
                {value: 'IT', name: 'IT'}
            ],
            questionList: [],
            customQuestionList: [
                
            ],

            customQuestion: null,
            selectedType: null,
            customMark: null,

            detail: null,
            total_mark: 0,
            total_custom_mark: 0,
            test:null,
            staffList: [],
            selectedStaff: null,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment','getToken']),
        async getStaffList() {
            let response = await getApiData({ url: `/api/staffs`, token: this.getToken() });
            if (response.data) {
                this.staffList = response.data;
                this.getDetail(response.data);
            }
        },
        async getDetail(list) {
            const staffId = Number(this.staffId);
            const staff = list.find(staff => staff.id === staffId);
            if (!staff || !staff.roles?.length) {
                console.warn('Staff or role not found');
                return;
            }
            const roleId = staff.roles[0].id;
            console.log('staff id ' ,staff)
            let response = await getApiData({ url: `/api/hr/get_exam_by_role/`+ roleId +`/exam_type/exam`, token: this.getToken() });
            if (response.data) {
                this.detail = response.data;
                let index = this.detail.findIndex(item => item.id === Number(this.examId))
                console.log(index)
                setTimeout(() => {
                    this.questionList = response.data[index].exam_questions
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
            // let isSameType = false;
            // let isSameCustom = false;
            // let customIndex = null;
            // this.questionList.forEach((question,index) => {
            //     if(question.length>0 && question[0].type === this.selectedType.value){
            //         console.log( '1 ' + question[0].type)
            //         question.push({
            //             question: this.customQuestion,
            //             type: this.selectedType.value,
            //             mark: this.customMark,
            //             is_custom: true,
            //         })
            //         isSameType = true;
            //     }
            //     if(question.type_name){
            //         isSameCustom = true;
            //         customIndex = index
            //     }
                
            // });
            // if(!isSameType && !isSameCustom){
            //     this.questionList.push({
            //         type_name: this.selectedType.value,
            //         is_custom: true,
            //         question: [
            //             {
            //             question: this.customQuestion,
            //             type: this.selectedType.value,
            //             mark: this.customMark
            //         }
            //         ]
            //     })
            // };
            // if(!isSameType && isSameCustom){
            //     this.questionList[customIndex].question.push({
            //         question: this.customQuestion,
            //         type: this.selectedType.value,
            //         mark: this.customMark
            //     })
            // };
            
            // this.total_custom_mark += this.customMark
            // this.customQuestion = null;
            // this.selectedType = null;
            // this.customMark = 0;


            // 1. Find same type
            let typeGroup = this.customQuestionList.find(item => item.type === this.selectedType.value)

            // 2. If type exists
            if (typeGroup) {
                // Check duplicate (same question + same mark)
                const isDuplicate = typeGroup.questions.some(
                    q => q.question === this.customQuestion && q.mark === this.customMark
                )

                if (isDuplicate) {
                    this.$notify({
                        title: `Input validation`,
                        text: `Duplicate question`,
                        type: "warn"
                    });
                    return
                }

                // Add to existing type
                typeGroup.questions.push({ 
                    question: this.customQuestion,
                    type: this.selectedType.value,
                    mark: this.customMark
                 })
            } 
            // 3. If type does not exist
            else {
                this.customQuestionList.push({
                    type : this.selectedType.value,
                    questions: [{ 
                        question: this.customQuestion,
                        type: this.selectedType.value,
                        mark: this.customMark 
                    }]
                })
            }
            this.total_custom_mark += this.customMark
            this.customQuestion = null;
            this.selectedType = null;
            this.customMark = 0;


            // if(this.selectedType.value === 'EQ'){
            //     this.customQuestionList.push({
            //         question: this.customQuestion,
            //         type: this.selectedType.value,
            //         mark: this.customMark
            //     })
            // }

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
                if(this.customQuestionList[questionIndex].questions.length < 2){
                    this.customQuestionList.splice(questionIndex, 1);
                }
                else{
                    if(customIndex != -1){
                        this.customQuestionList[questionIndex].questions.splice(customIndex, 1);
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
        btnClickedAssessExam() {
            let isValid = true;
            this.questionList.forEach(question => {
                if ((!question.selected || !question.selected.id)) {
                    isValid = false;
                }
            })
            console.log(isValid)
            if(!isValid){
                this.alertValidationMessage(`Answer`);
                return 1;
            }
            
            this.assessExam();
        },

        async assessExam() {
            let exam_answers = [];
            let custom_questions = [];
            if(Array.isArray(this.questionList)){
                this.questionList.forEach((question)=>{
                    exam_answers.push({
                        exam_question_id: question.id,
                        answer_id: question.answer_id
                    })
                });
            }
            if(Array.isArray(this.customQuestionList)){
                this.customQuestionList.forEach((question)=>{
                    question.questions.forEach(q => {
                        custom_questions.push({
                            question: q.question,
                            type: q.type,
                            mark: q.mark
                        })
                    })
                });
            }
            let total = 0;
            total = this.total_mark + this.total_custom_mark;
            let formData = new FormData();
            formData.append('staff_id', this.staffId);
            formData.append('exam_id', this.examId);
            formData.append('total_mark', total);
            formData.append('answers', JSON.stringify(exam_answers));
            formData.append('custom_questions', JSON.stringify(custom_questions));
            let response = await postApiData({ url: '/api/hr/staff_exam/answer', form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace('/staff');
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
            const flatQuestions = this.questionList.flat()
            // Sum up the selected marks
            return flatQuestions.reduce((sum, q) => {
                return sum + (q.selected?.mark || 0)
            }, 0)
        }
    },
    created() {
        // this.getDetail();
        this.getStaffList();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
