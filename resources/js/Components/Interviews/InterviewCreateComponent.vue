<template>
    <div class="px-0">
        <div class="mb-4 flex justify-between">
            <p class="text-lg font-semibold font-inter text-left">
                CV Detail
            </p>
            <div class="flex justify-end gap-x-4 px-6">
                <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                    @click="btnClickedCancelledCV">
                        Cancel
                </button>
                <button type="button" @click="btnClickedConfirmCV()"
                    class="add-btn focus:outline-none focus:ring-0 ">
                    Confirm
                </button>
            </div>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-4 container-card">

            <div class=" col-span-12 mb-6">
                <p class=" text-xl text-black">
                    Proposed Position
                </p>
            </div>
            
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Question
                </label>
                <input type="text" v-model="customQuestion" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
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
            <div class="mb-4 col-span-1 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Mark
                </label>
                <input type="number" v-model="customMark" autocomplete="off"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>
        </div>
        
        <div class=" mb-6 container-card !pt-0 !mt-0">
            <div class="contents" v-for="(question,groupName,questionIndex) in questionList">
                <div class="  mb-6">
                    <p class=" text-xl text-black">
                        {{ groupName }}
                    </p>
                </div>
                <div class="contents" v-for="(q,qIndex) in question">
                    <div class="  mb-6">
                        <p class=" text-xl text-black">
                            {{ q.question }}
                        </p>
                    </div>
                    <div class="flex gap-x-8 mb-8">
                        <label
                          class="inline-flex items-center space-x-2"
                          v-for="(answer, aIndex) in q.answers"
                          :key="aIndex"
                        >
                          <input
                            type="radio"
                            :checked="answer.checked"
                            @change="handleRadioChange(answer,q)"
                            class="form-radio h-4 w-4 text-[#845adf] focus:ring-0 focus:shadow-none"
                          />
                          <span class="text-gray-600">{{ answer.answer }}</span>
                        </label>
                    </div>

                    <!-- <div class="flex gap-x-8 mb-8">
                        <div class="mb-[0.125rem] me-4 inline-block min-h-[1.5rem] ps-[1.5rem]">
                            <input
                              class="relative float-left -ms-[1.5rem] me-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-secondary-500 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-checkbox before:shadow-transparent before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-black/60 focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-checkbox checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] rtl:float-right dark:border-neutral-400 dark:checked:border-primary"
                              type="radio"
                              name="inlineRadioOptions"
                              id="inlineRadio1"
                              value="option1" />
                            <label
                              class="mt-px inline-block ps-[0.15rem] hover:cursor-pointer"
                              for="inlineRadio1"
                              >1</label
                            >
                        </div>
                        <label class="inline-flex items-center space-x-2" v-for="answer in q.answers">
                            <input
                              type="radio" @change="isActiveToggled(answer)"
                              class="form-checkbox h-4 w-4 text-[#845adf] rounded focus:shadow-none focus:ring-0"
                            />
                            <span class="text-gray-600">{{ answer.answer }}</span>
                        </label>
                    </div> -->
                </div>
            </div>

        </div>
        
        <div class="px-4 mb-8">
            <button class="add-btn" @click="btnClickedCreateCVForm">
                Edit CV
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
    props: ["interviewId"],
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
        async addDetail(detail){

        },
        handleRadioChange(answer,q){
            // this.questionList[first].questions[second].answers.forEach((a, i) => {
            //     a.checked = i === third
            // })
            // answer.checked = 'yellow';
            q.answer_id = answer.id
        },

       
        
        



        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
        btnClickedCreateCVForm() {
            // this.roleIds = [];

            // if(!this.questionList){
            //     this.alertValidationMessage(`Answer`);
            //     return 1;
            // }
            
            this.createCVForm();
        },

        async createCVForm() {
            let formData = new FormData();
            // formData.append('department_id', this.selectedDepartment.id);
            // formData.append('skills', JSON.stringify(this.selectedSkills));
            let interview_answers = [];
            if(this.questionList){
                this.questionList.forEach((question)=>{
                    question.forEach(q => {
                        interview_answers.push({
                            exam_question_id: q.id,
                            answer_id: q.answer_id
                        })
                    });
                });
                console.log('qqqq');
            }
            console.log(this.questionList.length)
            formData.append('skills[]', interview_answers);
            let response = await postApiData({ url: '/api/hr/cvs/wwwwwww' + this.cvId, form_data: formData, token: this.getToken() });
            if (response.success) {
                window.location.replace('/cv');
            }
            else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
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
