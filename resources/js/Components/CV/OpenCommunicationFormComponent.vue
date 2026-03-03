<template>
	<div class="mt-4">
		<div class="card-shadow pt-2 pb-2">
			<div>
                <p class=" page-title">
                    Open Communication Form
                </p>
            </div>
		</div>

		<div class="box-container-table">
			<div class="overflow-x-auto">
				<div class="table-container">
					<table class="primary-table" v-if="ocfForm" >
						<thead>
							<tr>
								<th scope="col" class="">
                                    Attitude Evaluation
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.attitude_evaluation }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Brief Plan for Success
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.brief_plan_for_success }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Career Objective
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.career_object }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Challenge Expectation and Commitment
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.challenge_expectation_and_commitment }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Core Value
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.core_value }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Declaration
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.declaration }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Education Background
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.education_background }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Expected Salary
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.expected_salary }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Future Plan (first 3 month)
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.first_three_month_future_plan }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Future Plan
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.future_plan }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Future Review
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.future_review }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Hobby
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.hobby }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Job Source
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.job_source }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Life Ambitions
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.life_ambitions }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Life Audit
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.life_audit }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Must Do Daily Activity
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.must_do_daily_activity }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Overcoming Obstacles
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.overcoming_obstacles }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Parallel Feature
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.parallel_feature }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Personal Info:
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.personal_information }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Skill Competence
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.skill_competence }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Social Factor
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.social_factor }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    SWOT Analysis
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.swot_analysis }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Value
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.value }}
                                </th>
							</tr>							

							<tr>
								<th scope="col" class="">
                                    Why Apply This Position
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.why_applying_position }}
                                </th>
							</tr>

							<tr>
								<th scope="col" class="">
                                    Work Exp:
                                </th>
                                <th scope="col" class="">
                                    {{ ocfForm.work_experience }}
                                </th>
							</tr>
						</thead>
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
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
	props: ['staffId'],
    components: {
        Multiselect,
        TableSkeleton
    },
    data() {
        return {
            ocfForm: null,
        };
    },

    methods: {
        ...mapGetters(['getUser', 'getDepartment', 'getToken', 'getFeature']),

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });
        },

        getOCF(){
        	getApiData({url: `/api/hr/communication_form/${this.staffId}`, token: this.getToken()})
        	.then((response)=>{
        		if(response.data){
        			this.ocfForm = response.data;
        			console.log(response.data);
        		}
        	});
        },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });

    },
    created() {
        this.getOCF();
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>