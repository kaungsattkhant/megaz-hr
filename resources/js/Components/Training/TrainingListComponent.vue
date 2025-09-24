<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Training
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8 text-[13px] font-inter" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8 text-[13px] font-inter" @click="clearSearchBtnClicked()">Clear</button>
                </div>
                <div class="flex justify-end flex-col">
                    <a href="/training/create" class="add-btn " v-if="feature.includes('training.create')">
                        Add New
                    </a>

                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class=" ">
                                    #
                                </th>
                                <th scope="col" class=" ">
                                    Date
                                </th>
                                <th scope="col" class=" ">
                                    Title
                                </th>
                                <th scope="col" class=" ">
                                    Department
                                </th>
                                <th scope="col" class=" ">
                                    Place
                                </th>
                                <th scope="col" class=" ">
                                    From
                                </th>
                                <th scope="col" class=" ">
                                    To
                                </th>
                                <th scope="col" class=" ">
                                    Type
                                </th>
                                <th scope="col" class=" ">
                                    Trained By
                                </th>
                                <th scope="col" class="" v-show="['training.delete', 'training.edit'].some(f => feature.includes(f))">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(training, trainingIndex) in trainingList" :key="trainingIndex">
                                <tr class="">
                                    <td class="  font-medium ">
                                        {{ perPage * (currentPage - 1) + (++trainingIndex) }}

                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ training.date_time }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ training.title }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        <!-- {{ training.date_time }} -->
                                          department
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        
                                        {{ training.place }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        
                                        {{ training.from_date }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        
                                        {{ training.to_date }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ training.type ? training.type.name : '' }}                                        
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        
                                        {{ training.trained_by.name }}
                                    </td>
                                    <td class="whitespace-nowrap "  v-show="['training.delete', 'training.edit'].some(f => feature.includes(f))">
                                        <a class="pr-2" :href="'/training/' + training.id + '/edit'" v-if="feature.includes('training.edit')">
                                            <i class="fal fa-pen"></i>
                                        </a>

                                        <button data-te-toggle="modal" data-te-target="#deleteModal" id="edit-btn"
                                            @click="deleteBtnClicked(training.id)" v-if="feature.includes('training.delete')"
                                            class="pl-2">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getTrainingList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getTrainingList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
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

</template>

<script>
import { Modal, Ripple, initTE, Input } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, deleteApiData } from '../../utilities/ajax-helpers';

export default {
    data() {
        return {
            trainingList: [],

            searchInput:null,

            deleteId:null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            feature: this.getFeature(),
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getTrainingList(pageNumber) {
            let url = `/api/trainings`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.trainingList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
                // this.totalData = response.data.total;

            }
        },
        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        async confirmDeleteBtnClicked() {
            let url = `/api/trainings/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.getTrainingList()
            }
        },
    },

    created() {
        this.getTrainingList(1);
    },

    mounted() {

    }
}
</script>
