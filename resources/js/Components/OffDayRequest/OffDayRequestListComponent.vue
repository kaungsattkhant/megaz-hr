<template>

    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Day Off / Change Shift Requests
                </p>
            </div>
            <div class="btn-container">
                <notifications position="top center" />
                <div class=" flex gap-x-4">

                </div>
                <div class="flex pr-0 gap-x-4">

                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Date
                                </th>
                                <th scope="col" class="">
                                    Type
                                </th>
                                <th scope="col" class="">
                                    Shift
                                </th>
                                <th scope="col" class="">
                                    Staff
                                </th>
                                <th scope="col" class="">
                                    Status
                                </th>
                                <th scope="col" class=""
                                    v-show="['off-day.edit'].some(f => feature.includes(f))">
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(request, index) in offDayRequests" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        {{ perPage * (currentPage - 1) + (index + 1) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ request.staff_timeshift.date_time }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ request.type }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ request.staff_timeshift.timeshift.shift.name }} ({{ request.staff_timeshift.timeshift.from_time }} to {{ request.staff_timeshift.timeshift.to_time }})
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ request.staff_timeshift.staff.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ request.status }}
                                    </td>
                                    <td class="whitespace-nowrap"
                                        v-show="(['off-day.edit'].some(f => feature.includes(f))) && request.status == 'pending'">
                                        <button data-te-toggle="modal" data-te-target="#handleModal" id="handle-btn"
                                            class="pr-3" @click="handleBtnClicked(request.id, index)"
                                            v-show="feature.includes('off-day.edit')">
                                            <i class="fal fa-exclamation-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="offDayRequests.length < 1 && !loading">
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
                                @click="getOffDayRequests(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                        lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOffDayRequests(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="handleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                    <div
                        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                        <!--Modal title-->
                        <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                            Approve?
                        </h5>
                        <!--Close button-->
                        <button type="button" id="close-handle-modal"
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
                        <label for="" class="label-form mb-3">
                            Action
                        </label>
                        <multiselect v-model="selectedAction" :options="['confirmed','rejected']"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="false"
                            placeholder="Select Action"
                            :preselect-first="false" >
                        </multiselect>
                    </div>

                    <!--Modal footer-->
                    <div
                        class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                        <button type="button"
                            class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 "
                            data-te-modal-dismiss>
                            Close
                        </button>
                        <LoadingButton
                            :loading="buttonLoading"
                            text="Confirm"
                            loadingText="Loading..."
                            @click="confirmBtnClicked"
                        />
                        <!-- <button @click="confirmBtnClicked()" type="button" data-te-toggle="modal" data-te-target="#handleModal"
                            class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                            Confirm
                        </button> -->
                    </div>
                </div>
            </div>
        </div>

<!-- Button trigger modal -->
<button
  type="button"
  class=""
  data-te-toggle="modal"
  data-te-target="#exampleModal"
  data-te-ripple-init
  data-te-ripple-color="light"
  hidden
  disabled>
  Launch demo modal
</button>

<!-- Modal -->
<div
  data-te-modal-init
  class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
  id="exampleModal"
  tabindex="-1"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div
    data-te-modal-dialog-ref
    class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
    <div
      class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none dark:bg-surface-dark">
      <div
        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4 dark:border-white/10">
        <h5
          class="text-xl font-medium leading-normal text-surface dark:text-white"
          id="exampleModalLabel">
          Modal title
        </h5>
        <button
          type="button"
          class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-neutral-300"
          data-te-modal-dismiss
          aria-label="Close">
          <span class="[&>svg]:h-6 [&>svg]:w-6">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="currentColor"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12" />
            </svg>
          </span>
        </button>
      </div>

      <!-- Modal body -->
      <div class="relative flex-auto p-4" data-te-modal-body-ref>
        Modal body text goes here.
      </div>

      <!-- Modal footer -->
      <div
        class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 p-4 dark:border-white/10">
        <button
          type="button"
          class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-primary-700 transition duration-150 ease-in-out hover:bg-primary-accent-200 focus:bg-primary-accent-200 focus:outline-none focus:ring-0 active:bg-primary-accent-200 dark:bg-primary-300 dark:hover:bg-primary-400 dark:focus:bg-primary-400 dark:active:bg-primary-400"
          data-te-modal-dismiss
          data-te-ripple-init
          data-te-ripple-color="light">
          Close
        </button>
        <button
          type="button"
          class="ms-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
          data-te-ripple-init
          data-te-ripple-color="light">
          Save changes
        </button>
      </div>
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
import TableSkeleton from "../Common/TableSkeleton.vue";
import LoadingButton from '../Common/LoadingButton.vue';

export default {
    components: {
        Multiselect,
        TableSkeleton,
        LoadingButton
    },
    data() {
        return {
            offDayRequests: [],

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,

            url: '/api/hr/off_day_requests',
            url_search: '',
            url_department: '',
            url_role: '',
            deleteId: null,

            selectedDateRange: null,


            feature: this.getFeature(),
            loading: false,

            editId: null,
            editIndex: null,

            buttonLoading: false,

            selectedAction: null,
        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        async getOffDayRequests(pageNumber) {
            this.loading = true;
            let url = `${this.url}?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.offDayRequests = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });
        },

        showToastMessage(message, type='warn', title=null){
            this.$notify({
                title: title,
                text: message,
                type: type
            });
        },

        handleBtnClicked(id, index){
            this.editId = id;
            this.editIndex = index;
        },

        confirmBtnClicked(){
            if(!this.selectedAction){
                this.alertValidationMessage('selected action');
                return;
            }
            let formData = new FormData();
            formData.append('status', this.selectedAction);
            this.buttonLoading = true;
            postApiData({url: `/api/hr/off_day_requests/${this.editId}/status`, form_data: formData, token: this.getToken()})
            .then((response)=>{
                this.buttonLoading = false;
                if(response.success){
                    this.getOffDayRequests(1);
                }else{
                    this.showToastMessage(response.message, 'error');
                }

                document.getElementById('close-handle-modal').click();
                this.selectedAction = null;
                this.editId = null;
                this.editIndex = null;
            });
        },
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    },

    created() {
        this.getOffDayRequests(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
