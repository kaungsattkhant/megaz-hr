<template>
    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title pt-6">
                    Complaints
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

                    <button type="button" v-show="feature.includes('complain.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal">
                        Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table  ">
                        <thead class="  ">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>
                                <th scope="col" class="  ">
                                    Title
                                </th>
                                <th scope="col" class="">
                                    Description
                                </th>

                                <th scope="col" class="">
                                    Status
                                </th>

                                <th scope="col" class="">
                                    Category
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>

                            <!-- looping start -->
                            <div v-for="(complain, index) in complainList" :key="index" class="contents">
                                <tr class="">
                                    <td class="  ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <a href="#">
                                            {{ complain.title }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <a href="#">
                                            {{ complain.description }}
                                        </a>
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        <a href="#">
                                            {{ complain.status }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <a href="#">
                                            {{ complain.complaint_category.name }}
                                        </a>
                                    </td>

                                    <td class="whitespace-nowrap  flex justify-center gap-5">
                                        <!-- <button id="edit-btn" class="pr-3"
                                    data-te-toggle="modal" data-te-target="#stageModal">
                                        <i class="fal fa-bars"></i>
                                    </button> -->

                                        <button id="edit-btn" class="pr-1" @click="statusChangeClick(complain.id)"
                                            data-te-toggle="modal" data-te-target="#statusChange" v-show="feature.includes('complaint.update')"
                                            :disabled="complain.status === 'Done'">
                                            <i class="far fa-info-circle"></i>
                                        </button>


                                        <button id="edit-btn" class="pr-1" @click="deleteBtnClicked(complain.id)" v-show="feature.includes('complaint.delete')"
                                            data-te-toggle="modal" data-te-target="#deleteModal">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>


                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="complainList.length < 1 && !loading">
                                <td class="" colspan="5">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getComplain(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getComplain(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Create Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Create Complain
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_create_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Complain Title
                                </label>
                                <input type="text" placeholder="Complain Title" v-model="title" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Category
                                </label>
                                <select name="" id="" v-model="selectedCategory" class="input-ui">
                                    <option v-for="(category, index) in complainCategory" :key="index"
                                        :value=category.id>{{
                                            category.name }}</option>

                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Complain Description
                                </label>
                                <textarea v-model='description' class="input-ui" name="" id="" cols="30" rows="10"
                                    placeholder="Complain Description"></textarea>

                            </div>
                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" @click="createBtnClicked"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- status change model -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="statusChange" tabindex="-1" aria-labelledby="statusChangeLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Complain Status
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close" id="close_status_change_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <select type="text" placeholder="Complain Title" v-model="change_status"
                                    data-te-select-init data-te-select-placeholder="Select Roles"
                                    data-te-select-filter="true" class="input-ui">
                                    <option value="In Progress">In Progress</option>
                                    <option value="Done">Done</option>

                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-4 pt-6">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button data-te-modal-dismiss type="button" @click="statusChange"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
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
                                Delete ?
                            </h5>
                            <button type="button"
                                class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
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
                            <button @click="confirmDeleteBtnClicked" type="button" data-te-toggle="modal"
                                data-te-target="#deleteModal"
                                class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                                Delete
                            </button>
                        </div>
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
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            complainList: [],
            typeList: [],
            areaList: [],
            departmentList: [],
            name: null,
            deleteId: null,

            complainCategory: [],
            title: null,
            selectedCategory: null,
            description: null,
            statusChange_id: null,
            change_status: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            feature: this.getFeature(),
            loading: false,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getComplain(pageNumber) {
            this.loading = true;
            let url = `/api/complaints?page=${pageNumber}`
            const response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.complainList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        createBtnClicked() {
            this.createComplain();
        },

        async getComplainCategory() {
            let response = await getApiData({ url: '/api/complaint_categories' });
            this.complainCategory = response.data;

        },

        async createComplain() {
            let formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('complaint_category_id', this.selectedCategory);
            let response = await postApiData({ url: '/api/complaints', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getComplain(1);
                document.getElementById("close_create_modal").click();
                this.clearForm();
            }
            else {
                this.$notify({
                    title: 'Error',
                    text: response.message,
                    type: 'error'
                });
            }
        },

        statusChangeClick(id) {
            this.statusChange_id = id;
        },

        async statusChange() {
            if (!this.change_status) {
                alert('Please select the status');
            }
            let formData = new FormData();
            formData.append('status', this.change_status);
            let response = await postApiData({ url: `/api/complaints/${this.statusChange_id}/update_status`, form_data: formData, token: this.getToken() })
            this.change_status == "";
            if (response.success == true) {
                this.getComplain(1);
                document.getElementById('close_status_change_modal').click();
            } else {
                this.$notify({
                    title: 'Error',
                    text: response.message,
                    type: 'error'
                });
            }
        },

        closeModal() {
            document.getElementById("close").click();
        },
        clearForm() {
            this.title = null,
                this.selectedCategory = null,
                this.description = null
        },
        deleteBtnClicked(id) {
            this.deleteId = id;
        },
        async confirmDeleteBtnClicked() {
            let url = `/api/complaints/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    title: 'Error',
                    text: 'Deleted',
                    type: 'error'
                });
            }
        },
        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

    },
    mounted() {

        this.getComplain(1);
        this.getComplainCategory();

        initTE({ Modal, Select, Ripple });
    }
}
</script>
