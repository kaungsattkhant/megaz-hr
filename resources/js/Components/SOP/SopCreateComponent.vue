<template>
    <div class="px-0">
        


        <div class="grid !grid-cols-10 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-4 col-span-10">
                <p class="text-lg font-semibold font-inter">
                    Create SOP
                </p>
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Department
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" @change="changeDepartment()"
                        data-te-select-filter="true" name="" id="" v-model="selectedDepartment" class="input-ui !text-black text-sm">
                        <option :value="department" v-for="(department, index) in departmentList"
                            :key="index"> {{ department.name }} </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    Role
                </label>

                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" @change="roleChange()"
                        data-te-select-filter="true" name="" id="" v-model="selectedRole" class="input-ui !text-black text-sm">
                        <option :value="role" v-for="(role, index) in roleList"
                            :key="index"> {{ role.name }} </option>
                    </select>
                </div>
            </div>
            <div class="col-span-4"></div>
            
            <div class="mb-3 col-span-6 pb-3 rounded-md">
                <label for="" class="label-form mb-3">
                    JD
                </label>
                <div class="bg-white mb-0 w-full inline-block h-[34px] dark:bg-white !text-black !text-sm"
                    data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select JD"
                        data-te-select-filter="true" name="" id="" v-model="selectedJd" class="input-ui !text-black text-sm">
                        <option :value="jd" v-for="(jd, index) in jdList"
                            :key="index"> {{ jd.job_description }} </option>
                    </select>
                </div>
            </div><div class="col-span-4"></div>
            <div class="mb-3 col-span-6 pb-0 rounded-md">
                <label for="" class="label-form mb-3">
                    SOP
                </label>
                <input type="text" v-model="selectedSop" class="input-ui ">
            </div>
            <div class="col-span-4">
                <label for="" class="label-form mb-3">
                    &nbsp;
                </label>
                <button class="add-btn py-[9px]" @click="btnClickedAddSop()">
                    Add
                </button>
            </div>
        </div>

        <div class=" bg-white py-8 px-8 rounded-md shadow-md mb-8">
            <div class="table-container">
                <table class="primary-table">
                    <thead class="!text-left">
                        <tr>
                            <th scope="col" class="">
                                SOP
                            </th>
                            <th scope="col" class="">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="!text-left">
                        <tr class="" v-for="(sop, sopIndex) in selectedSopList"
                            :key="sopIndex">
                            <td class="">
                                {{ sop.sop ? sop.sop : '--' }}
                            </td>
                            <td class="text-center">
                                <button @click="removeSop(sopIndex)">
                                    <i class="fas fa-times  pr-3"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class=" !text-center" v-if="selectedSopList.length < 1">
                            <td class="" colspan="3">
                                No Data Here
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                <button class="add-btn" @click="btnClickedCreateSOP()">
                    Create SOP
                </button>
            </div>
        </div>
        
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Tab, Select } from "tw-elements";
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { each } from "lodash";

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            departmentList:[],
            roleList:[],
            jdList:[],

            selectedDepartment:null,
            selectedRole:null,
            selectedJd:null,
            selectedSop:null,
            
            selectedSopList: [],
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getJdList(){
            let response = await getApiData({ url: '/api/job-descriptions', token: this.getToken() });
            if (response.data) {
                this.jdList = response.data;
            }
        },
        async getDepartmentList(){
            let response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.departmentList = response.data;
            }
        },
        changeDepartment(){
            this.selectedRole = null;
            this.roleList = this.selectedDepartment.roles;
        },
        async roleChange(){
            let response = await getApiData({ url: '/api/job-descriptions?role_id=' + this.selectedRole.id, token: this.getToken() });
            if (response.data) {
                this.jdList = response.data;
            }
        },

        btnClickedAddSop(){
            if(!this.selectedSop){
                this.alertValidationMessage(`SOP`);
                return 1;
            }
            else{
                this.addSop();
            }
        },
        async addSop(){
            
            this.selectedSopList.push({
                sop: this.selectedSop ? this.selectedSop : null,
                role_id: this.selectedRole ? this.selectedRole.id : null,
            })
            this.selectedSop = null;
        },
        removeSop(index){
            this.selectedSopList.splice(index, 1);
        },

        btnClickedCreateSOP(){
            if(!this.selectedDepartment){
                this.alertValidationMessage(`Department`);
                return 1;
            }
            else if(!this.selectedRole){
                this.alertValidationMessage(`Role`);
                return 1;
            }
            else if(!this.selectedJd){
                this.alertValidationMessage(`Job Description`);
                return 1;
            }
            else if(this.selectedSopList.length < 1){
                this.alertValidationMessage(`SOP`);
                return 1;
            }
            else{
                this.createSOP();
            }
        },
        async createSOP(){
            let formData = new FormData();
            formData.append('sops',JSON.stringify(this.selectedSopList));
            // formData.append('role_id',this.selectedRole.id);
            formData.append('job_description_id',this.selectedJd.id);
            let response = await postApiData({url:`/api/sops`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/SOP`);
            }else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
    },

    watch: {
    },

    async created() {

    },

    mounted() {
        this.getDepartmentList();
        // this.getJdList();
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
