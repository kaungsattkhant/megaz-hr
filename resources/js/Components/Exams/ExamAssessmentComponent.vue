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
            customQuestionList: [],

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
                setTimeout(() => {
                    this.questionList = response.data[0].exam_questions
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
        
        deleteItem(index,list,item){
            this.total_custom_mark -= item.mark
            if(index != -1){
                list.splice(index, 1);
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
            if(Array.isArray(this.questionList)){
                this.questionList.forEach((question)=>{
                    exam_answers.push({
                        exam_question_id: question.id,
                        answer_id: question.answer_id
                    })
                });
            }
            let total = 0;
            total = this.total_mark;
            let formData = new FormData();
            formData.append('staff_id', this.staffId);
            formData.append('exam_id', this.examId);
            formData.append('total_mark', total);
            formData.append('answers', JSON.stringify(exam_answers));
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
