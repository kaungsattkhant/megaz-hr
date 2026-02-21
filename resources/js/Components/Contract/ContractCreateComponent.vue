<template>
    <div class="px-0">
        
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white p-8 rounded-md">
            <div class="mb-6 col-span-12">
                <p class="text-lg font-semibold font-inter">
                    Add New Contract
                </p>
            </div>
            <!-- <div class="mb-4 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Exam Name
                </label>
                <input type="text" v-model="name"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div> -->
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
            </div><div class="col-span-6"></div>
            
                

            <div class="mb-6 col-span-3 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Category
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select category" v-model="selectedCategory" class="input-ui !text-black"
                    data-te-select-filter="true" >
                        <option :value="category.value" v-for="(category, categoryIndex) in categoryList" :key="categoryIndex">
                            {{ category.name }}
                        </option>
                    </select>
                </div>
            </div>
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
            </div><div class="col-span-6"></div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label class="label-form mb-3">Company</label>
                <multiselect
                v-model="selectedCompany"
                :options="companyList"
                :multiple="false"
                :close-on-select="true"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Company"
                label="skill" class=" capitalize"
                track-by="id"
                :preselect-first="false">
                </multiselect>
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label class="label-form mb-3">Witness</label>
                <multiselect
                v-model="selectedWitness"
                :options="witnessList"
                :multiple="false"
                :close-on-select="true"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Witness"
                label="skill" class=" capitalize"
                track-by="id"
                :preselect-first="false">
                </multiselect>
            </div>
            <div class="col-span-6"></div>
            <div class="mb-0 col-span-6 rounded-md">
                <label for="" class="label-form mb-3">
                    Description
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px]"
                    data-te-select-wrapper-ref>
                    <textarea type='text' v-model='description' class="input-ui w-full !p-1 text-xs" rows="8" placeholder="Description" ></textarea>
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
            id="add_category_modal" tabindex="-1" aria-labelledby="add_category_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="add_category_modalLabel">
                            Category
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
                        <div class="mb-4 pb-0 rounded-md">
                            <label for="" class="block text-sm text-black mb-3">
                                Answer
                            </label>
                            <input type="text" v-model="answer" autocomplete="off"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="createCategory()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
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
            categoryList:[],
            typeList: [
                {value: 'orientation', name: 'Orientation'},
                {value: 'occasional', name: 'Occasional'}
            ],
            companyList: [],
            witnessList: [],

            selectedDepartment: null,
            selectedRoles: null,
            selectedType: null,
            selectedCategory: null,

            categoryName: null,

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
        },

        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },


        async createBtnClicked(){
            if(!this.department){
                this.alertValidationMessage(`Department`);
                return 1;
            }
            if(!this.selectedRoles){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            if(!this.selectedCategory){
                this.alertValidationMessage(`Category`);
                return 1;
            }
            if(!this.selectedType){
                this.alertValidationMessage(`Type`);
                return 1;
            }
            
            let formData = new FormData();
            formData.append("department_id", this.selectedDepartment.id);
            formData.append("role_id", this.selectedRoles.id);
            formData.append("type", this.selectedType);
            formData.append("category", this.selectedCategory);
            
            let url = `/api/`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                window.location.replace("/contract");
            }else {
                this.$notify({
                    text: response.message,
                    type: "error"
                });
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
