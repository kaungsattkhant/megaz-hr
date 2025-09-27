<template>

    <div class="mt-4 bg-white" v-show="!isShow">
        <div class="card-shadow">
            <div>
                <p class="page-title">
                    Department
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search">
                        <i class="fal fa-search"></i>
                    </label>
                </div>
                <div class="flex justify-end flex-col">
                    <button type="button" v-if="feature.includes('department.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        @click="btnClickedAddDepartment">
                        Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
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
                                <th v-show="feature.includes('department.edit')"></th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(department, index) in departmentList" :key="index">
                                <tr class="">
                                    <td class="">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ department.name }}
                                    </td>
                                    <!-- <td class="whitespace-nowrap">
                                        <div class=" h-14 overflow-hidden relative feature-list">
                                            <div v-for="feature in department.features">
                                                {{ feature.name }},
                                            </div>
                                            <div class="absolute bottom-0 left-0 right-0 h-5 blur-box" style="background-image: linear-gradient(#fff0, #fffa);"></div>
                                        </div>
                                        <button @click="seeMore($event,index)" class="see-more">See More</button>
                                        <button @click="seeLess($event,index)" class="hidden see-less">See Less</button>
                                    </td> -->
                                    <td>
                                        <div class="relative inline-block">
                                            <span class=""
                                                v-for="(item, itemIndex) in getVisibleItems(department.features, index)"
                                                :key="itemIndex">
                                                {{ item.name }}<span
                                                    v-if="itemIndex < getVisibleItems(department.features, index).length - 1">,
                                                </span>
                                            </span>
                                            <div v-if="department.features.length > defaultVisibleCount && !expandedRows.includes(index)"
                                                class="absolute bottom-0 left-0 right-0 h-5 blur-box"
                                                style="background-image: linear-gradient(to right,#fff0, #fffa);"></div>
                                        </div>
                                        <button
                                            v-if="department.features.length > defaultVisibleCount && !expandedRows.includes(index)"
                                            @click="expandRow(index)" class="see-more-button pt-2 font-semibold text-gray-700">
                                            ... See More
                                        </button>
                                        <!-- "See Less" button for expanded rows -->
                                        <button v-if="expandedRows.includes(index)" @click="collapseRow(index)"
                                            class="see-less-button pt-2 font-semibold pl-2 text-gray-700">
                                            See Less
                                        </button>
                                    </td>
                                    <td class="whitespace-nowrap" v-show="feature.includes('department.edit')">
                                        <button v-if="feature.includes('department.edit')"
                                            @click="editBtnClicked(department)" id="edit-btn" class="pr-1">
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
                            <tr class=" !text-center" v-if="departmentList.length < 1">
                                <td class="" colspan="4">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-4 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getDepartmentList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getDepartmentList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
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
                                <multiselect v-model="selectedFeatures" :options="featureList" :multiple="true"
                                    :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                                    placeholder="Select features" label="name" track-by="id" :preselect-first="true">
                                    <template #selection="{ values, search, isOpen }">
                                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{
                                            values.length }} features selected</span>
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
                                <multiselect v-model="selectedFeatures" :options="featureList" :multiple="true"
                                    :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                                    placeholder="Select features" label="name" track-by="id" :preselect-first="true">
                                    <template #selection="{ values, search, isOpen }">
                                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{
                                            values.length }} features selected</span>
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

    <transition enter-active-class="fade-out duration-[200ms]" enter-from-class="opacity-0" enter-to-class="opacity-100"
        leave-active-class="fade-in duration-[300ms]" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-show="isShow" 
            class="fixed top-0 left-0 right-0 bottom-0 w-[100vw] h-[100vh] z-40 overflow-y-auto bg-[#0008]"
            @click="btnClickedChangeFeature">
            <div class="container-card pb-4 px-8 m-16 z-50 overflow-hidden" @click.stop>
                <div class="mb-6 flex justify-between">
                    <p class="text-lg font-semibold font-inter">
                        Create Department
                    </p>
                    <div>
                        <button type="button" class="add-btn focus:shadow-none focus:outline-none"
                            @click="btnClickedDone">
                            Done
                        </button>
                    </div>
                </div>
                <div>
                    <div class="pb-8 mb-4 border-b border-gray-200">
                        <label for="" class="label-form mb-3 font-semibold">
                            Department Name
                        </label>
                        <input type="text" placeholder="Department Name" v-model="name" class="input-ui !w-1/3">
                    </div>
                    <div v-for="(module, index) in featureList" class="mb-4 pb-6 px-2 border-b border-gray-200 flex">
                        <p class=" capitalize mb-4 font-semibold w-[20%]">
                            {{ module.module }}
                        </p>
                        <div class="w-[80%] grid grid-cols-4 text-sm text-gray-600 flex-wrap gap-x-4 gap-y-6">
                            <div v-for="feature in module.features" class="">

                                <label class="block items-center space-x-2 cursor-pointer">
                                    <span class="text-black break-all capitalize block mb-2">{{ feature.slug }}</span>
                                    <input type="checkbox" :value="feature.id" v-model="selectedFeatures"
                                        class="form-checkbox h-4 w-4 text-[#845adf] rounded focus:shadow-none focus:ring-0 cursor-pointer" />

                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="add-btn focus:shadow-none focus:outline-none"
                        @click="btnClickedDone">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </transition>

</template>

<script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import Multiselect from 'vue-multiselect';
    import TableSkeleton from "../Common/TableSkeleton.vue";

    export default {
        components: {
            Multiselect,
            TableSkeleton
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


                currentPage: 0,
                perPage: 0,
                lastPage: 0,
                totalData:0,

                defaultVisibleCount: 3, // Number of items to show by default
                expandedRows: [], // Tracks which rows are expanded

                feature: this.getFeature(),

                isShow:false,
                loading: true,
            };
        },

        methods: {
            ...mapGetters(['getToken', 'getFeature']),

            async getDepartmentList(pageNumber){
                this.loading = true;
                console.log('loading');
                const response = await getApiData({ url: '/api/departments?page=${pageNumber}', token: this.getToken() });
                if(response.data){
                    this.loading = false;
                    this.departmentList = response.data.data;
                    this.lastPage = response.data.last_page;
                    this.currentPage = pageNumber;
                    this.perPage = response.data.per_page;
                    this.totalData = response.data.total;
                    console.log('loading done');
                }
            },

            async getFeatureList(){
                // let response = await getApiData({ url: `/api/features`, token: this.getToken() });
                let response = await getApiData({ url: `/api/feature_by_module`, token: this.getToken() });
                if(response.data){
                    this.featureList = response.data;
                    // const grouped = {};
                    // response.data.forEach(feature => {
                    //     const mod = feature.module;

                    //     if (!grouped[mod]) {
                    //         grouped[mod] = {
                    //             module: mod,
                    //             features: []
                    //         };
                    //     }

                    //     grouped[mod].features.push(feature);
                    // });
                    // this.featureList = Object.values(grouped);
                }
            },

            alertValidationMessage(field) {
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to provide ${field}, please try again`,
                    type: 'warn'
                });
            },

            btnClickedChangeFeature(){
                if(this.isShow){
                    this.isShow = false;
                }
                else{
                    this.isShow = true;
                }
            },
            btnClickedDone(){
                if(this.editId){
                    this.confirmEditBtnClicked();
                }
                else{
                    this.confirmCreateBtnClicked();
                }
            },
            editBtnClicked(detail){
                let featureIds = [];
                detail.features.forEach(item => {
                    featureIds.push(
                        item.id
                    )
                })
                this.selectedFeatures = featureIds;
                this.name = detail.name;
                this.editId = detail.id;
                // let index = this.departmentList.findIndex(department => department.id == this.editId);
                // if(index != -1){
                //     this.editName = this.departmentList[index].name;
                //     this.selectedFeatures = this.departmentList[index].features;
                // }
                this.btnClickedChangeFeature();
            },
            async confirmEditBtnClicked(){
                if(!this.name){
                    this.alertValidationMessage('name');
                    return 1;
                }
                if(this.selectedFeatures.length < 1){
                    this.alertValidationMessage('department features');
                    return 1;
                }
                // if(!this.editName){
                //     this.alertValidationMessage('name');
                //     return 1;
                // }
                // let featureIds = [];
                // if(this.selectedFeatures.length > 0){
                //     this.selectedFeatures.forEach((selectedFeature)=>{
                //         featureIds.push(selectedFeature.id);
                //     });
                // }
                let formData = new FormData();
                // formData.append('name', this.editName);
                formData.append('name', this.name);
                // if(featureIds.length > 0){
                //     formData.append('featureIds', JSON.stringify(this.selectedFeatures));
                // }
                formData.append('featureIds', JSON.stringify(this.selectedFeatures));
                let url = `/api/departments/${this.editId}`;
                let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getDepartmentList(1);
                    this.name = null;
                    this.editId = null;
                    this.btnClickedChangeFeature();
                }

                this.selectedFeatures = [];
                this.editId = null;
                this.editName = null;
            },

            
            btnClickedAddDepartment(){
                this.name = null;
                this.selectedFeatures = [];
                this.editId = null;
                this.btnClickedChangeFeature();
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
                formData.append('featureIds', JSON.stringify(this.selectedFeatures));

                let response = await postApiData({url: '/api/departments', form_data: formData, token: this.getToken()});
                if(response.success){
                    this.getDepartmentList(1);
                    this.selectedFeatures = [];
                    this.btnClickedChangeFeature();
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
            },



            getVisibleItems(items, rowIndex) {
                if (this.expandedRows.includes(rowIndex)) {
                    return items;
                }
                return items.slice(0, this.defaultVisibleCount); // Show only the first few items
            },
            expandRow(rowIndex) {
                if (!this.expandedRows.includes(rowIndex)) {
                    this.expandedRows.push(rowIndex);
                }
            },
            collapseRow(rowIndex) {
                this.expandedRows = this.expandedRows.filter((index) => index !== rowIndex);
            },
            // seeMore(event){
            //     console.log("hello")
            //     const button = $(event.target);
            //     const row = button.closest("tr");
            //     row.find(".feature-list").removeClass('overflow-hidden h-14')
            //     row.find(".blur-box").addClass('hidden')
            //     $(event.target).addClass('hidden')
            //     row.find('.see-less').removeClass('hidden')
            // },
            // seeLess(event){
            //     console.log("hello")
            //     const button = $(event.target);
            //     const row = button.closest("tr");
            //     row.find(".feature-list").addClass('overflow-hidden h-14')
            //     row.find(".blur-box").removeClass('hidden')
            //     $(event.target).addClass('hidden')
            //     row.find('.see-more').removeClass('hidden')
            // }

        },
        mounted()
        {
            this.getDepartmentList(1);
            this.getFeatureList();
            initTE({ Modal,Select, Ripple });
        }
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
