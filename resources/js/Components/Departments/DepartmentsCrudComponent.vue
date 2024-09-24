<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Department
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>
            </div>
            <div class="flex justify-end flex-col">
                <button type="button"
                    class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                    data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
                    Add New
                </button>
            </div>
        </div>
        <div class="block mx-4 mt-4 pb-4">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class=" ">
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Features
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(department, index) in departmentList" :key="index">
                                <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                    <td class=" px-6 py-4 font-medium ">
                                        {{ ++index }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ department.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        <div v-for="feature in department.features">
                                            {{ feature.name }},
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <button @click="editBtnClicked(department.id)" data-te-toggle="modal"
                                            data-te-target="#editModal" id="edit-btn" class="pr-1">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <!-- <button @click="deleteBtnClicked(department.id)"
                                    data-te-toggle="modal" data-te-target="#deleteModal"
                                    class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> -->
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <!--Modal title-->
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create Department
                        </h5>
                        <!--Close button-->
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!--Modal body-->
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Department Name
                            </label>
                            <input type="text" placeholder="Department Name" v-model="name" class="input-ui">
                        </div>

                        <div class="mb-4">
                            <div>
                                <label class="block text-sm text-black mb-3">Department Features</label>
                                <multiselect v-model="selectedFeatures" :options="featureList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                                            :preserve-search="true" placeholder="Select features" label="name" track-by="id" :preselect-first="true">
                                <template #selection="{ values, search, isOpen }">
                                    <span class="multiselect__single"
                                        v-if="values.length"
                                        v-show="!isOpen">{{ values.length }} features selected</span>
                                </template>
                                </multiselect>
                                <!-- <pre class="language-json" v-for="selectedFeature in selectedFeatures" ><code>{{ selectedFeature.name }}</code></pre> -->
                            </div>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="confirmCreateBtnClicked"
                            class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
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
                        <!--Modal title-->
                        <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                            Delete ?
                        </h5>
                        <!--Close button-->
                        <button type="button"
                            class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!--Modal body-->
                    <div class="relative flex-auto p-4" data-te-modal-body-ref>
                        <p>
                            Are you sure ?
                        </p>
                    </div>
                    <!--Modal footer-->
                    <div
                        class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                        <button type="button"
                            class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                            data-te-modal-dismiss>
                            Close
                        </button>
                        <button @click="confirmDeleteBtnClicked" type="button" data-te-toggle="modal"
                            data-te-target="#deleteModal"
                            class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="editModal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <!--Modal title-->
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Edit Department
                        </h5>
                        <!--Close button-->
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!--Modal body-->
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Department Name
                            </label>
                            <input type="text" placeholder="Department Name" v-model="editName" class="input-ui">
                        </div>

                        <div class="mb-4">
                            <div>
                                <label class="block text-sm text-black mb-3">Department Features</label>
                                <multiselect v-model="selectedFeatures" :options="featureList" :multiple="true" :close-on-select="false" :clear-on-select="false"
                                            :preserve-search="true" placeholder="Select features" label="name" track-by="id" :preselect-first="true">
                                <template #selection="{ values, search, isOpen }">
                                    <span class="multiselect__single"
                                        v-if="values.length"
                                        v-show="!isOpen">{{ values.length }} features selected</span>
                                </template>
                                </multiselect>
                                <!-- <pre class="language-json" v-for="selectedFeature in selectedFeatures" ><code>{{ selectedFeature.name }}</code></pre> -->
                            </div>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="confirmEditBtnClicked"
                            class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
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
                name: null,

                editName: null,
                editId: null,

                deleteId: null,

                featureList: [],
                selectedFeatures: [],


            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getDepartmentList(){
                const response = await getApiData({ url: '/api/departments', token: this.getToken() });
                if(response.data){
                    this.departmentList = response.data;
                }
            },

            async getFeatureList(){
                let response = await getApiData({ url: `/api/features`, token: this.getToken() });
                if(response.data){
                    this.featureList = response.data;
                }
            },

            alertValidationMessage(field) {
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to provide ${field}, please try again`,
                    type: 'warn'
                });
            },

            editBtnClicked(id){
                this.selectedFeatures = [];
                this.editId = id;
                let index = this.departmentList.findIndex(department => department.id == this.editId);
                if(index != -1){
                    this.editName = this.departmentList[index].name;
                    this.selectedFeatures = this.departmentList[index].features;
                }
            },

            async confirmEditBtnClicked(){
                if(!this.editName){
                    this.alertValidationMessage('name');
                    return 1;
                }
                let featureIds = [];
                if(this.selectedFeatures.length > 0){
                    this.selectedFeatures.forEach((selectedFeature)=>{
                        featureIds.push(selectedFeature.id);
                    });
                }
                let formData = new FormData();
                formData.append('name', this.editName);
                if(featureIds.length > 0){
                    formData.append('featureIds', JSON.stringify(featureIds));
                }
                let url = `/api/departments/${this.editId}`;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getDepartmentList(null);
                }

                this.selectedFeatures = [];
                this.editId = null;
                this.editName = null;
            },

            addBtnClicked(){
                this.selectedFeatures = [];
            },

            confirmCreateBtnClicked(){
                if(!this.name){
                    this.alertValidationMessage('name');
                    return 1;
                }
                if(this.selectedFeatures.length < 1){
                    this.alertValidationMessage('department features');
                    return 1;
                }

                this.createDepartment();
            },

            async createDepartment()
            {
                let featureIds = [];
                this.selectedFeatures.forEach((selectedFeature)=>{
                    featureIds.push(selectedFeature.id);
                });
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('featureIds', JSON.stringify(featureIds));

                let response = await postApiData({url: '/api/departments', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getDepartmentList(null);
                    this.selectedFeatures = [];
                }
                else{
                    this.$notify({
                        text: `Some errors occur`,
                        type: 'error'
                    });
                }

            },

            deleteBtnClicked(id){
                this.deleteId = id;
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/departments/${this.deleteId}`;
                alert(url)
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    alert(`deleted`);
                }
            }

        },
        mounted()
        {
            this.getDepartmentList();
            this.getFeatureList();
            initTE({ Modal,Select, Ripple });
        }
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
