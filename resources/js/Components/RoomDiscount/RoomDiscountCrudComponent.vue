<template>
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Room Discounts
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
                    <button type="button"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
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
                                    Discount Name
                                </th>
                                <th scope="col" class="">
                                    From Date
                                </th>
                                <th scope="col" class="">
                                    To Date
                                </th>
                                <th scope="col" class="">
                                    Used Sessions
                                </th>
                                <th scope="col" class="">
                                    Free Sessions
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(discount, index) in discountList" :key="index">
                                <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                    <td class=" px-6 py-4 font-medium ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ discount.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ discount.from_date }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ discount.to_date }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ discount.session }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 ">
                                        {{ discount.free_session }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <!-- <button data-te-toggle="modal"
                                            data-te-target="#editModal" id="edit-btn" class="pr-1">
                                            <i class="fas fa-pen"></i>
                                        </button> -->

                                        <button data-te-toggle="modal" data-te-target="#deleteModal" class="pr-1"
                                            @click="deleteBtnClicked(discount.id)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="discountList.length < 1 && !loading">
                                <td class="" colspan="7">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getMenuList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getMenuList(currentPage + 1)"> »</button>
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
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Create Room Discount
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Name
                            </label>
                            <input type="text" v-model="name" placeholder="Discount Name" class="input-ui">
                        </div>

                        <div class="mb-4">
                            <!-- <label for="" class="label-form mb-3"> Room </label>
                            <div class="text-sm w-full bg-transparent rounded-lg focus:ring-0" data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select room" v-model="selectedRoom" data-te-select-filter="true"
                                name="" id="" class="input-ui" @change="roomSelectChanged" >
                                    <option v-for="(room, roomIndex) in roomList" :value="room" > {{ room.name }}</option>
                                </select>
                            </div> -->
                            <div>
                                <label class="label-form mb-3">Rooms</label>
                                <multiselect v-model="selectedRooms" :options="roomList" :multiple="true"
                                    :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                                    placeholder="Select Rooms" label="name" track-by="id" :preselect-first="true">
                                    <template #selection="{ values, search, isOpen }">
                                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">
                                            {{ values.length }} rooms selected
                                        </span>
                                    </template>
                                </multiselect>
                                <div class="flex gap-x-2 flex-wrap mt-1">
                                    <span class="font-inter after-coma" v-for="selectedRoom in selectedRooms"
                                        :key="selectedRoom">{{ selectedRoom.name
                                        }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Used Session
                            </label>
                            <input type="number" placeholder="Used Session" v-model="session" class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Free Session
                            </label>
                            <input type="number" placeholder="Free Session" v-model="freeSession" class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                From
                            </label>
                            <input type="date" placeholder="From" v-model="startDate" :min="today" class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                To
                            </label>
                            <input type="date" placeholder="To" v-model="endDate" :min="today" class="input-ui">
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="createBtnClicked" class="add-btn focus:outline-none focus:ring-0 "
                            data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
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

    </div>
</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { getCurrentDate } from '../../utilities/datetime-helpers';
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
            today: getCurrentDate(),

            discountList: [],

            roomList: [],
            selectedRooms: [],
            selectedRoom: null,

            name: null,
            session: null,
            freeSession: null,
            startDate: null,
            endDate: null,

            deleteId: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData:0,

            loading: false,
        }
    },

    methods: {
        ...mapGetters(['getToken']),

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        async getDiscountList(pageNumber) {
            this.loading = true;
            if (pageNumber) {
                this.currentPage = pageNumber;
            }
            let url = `/api/room_discounts?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.discountList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        async getRoomList() {
            let url = `/api/entities?type=room`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.roomList = response.data;
            }
        },

        async createBtnClicked() {
            if (!this.name) {
                this.alertValiationMessage(`discount name`);
                return 1;
            }
            if (this.selectedRooms.length < 1) {
                this.alertValiationMessage(`discount applicable rooms`);
                return 1;
            }
            if (!this.session) {
                this.alertValiationMessage(`used session`);
                return 1;
            }
            if (!this.freeSession) {
                this.alertValiationMessage(`free session`);
                return 1;
            }
            if (!this.startDate) {
                this.alertValiationMessage(`discount start date`);
                return 1;
            }
            if (!this.endDate) {
                this.alertValiationMessage(`discount end date`);
                return 1;
            }

            let roomIds = [];
            this.selectedRooms.forEach((room) => {
                roomIds.push(room.id);
            });

            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('from_date', this.startDate);
            formData.append('to_date', this.endDate);
            formData.append('session', this.session);
            formData.append('free_session', this.freeSession);
            formData.append('roomIds', JSON.stringify(roomIds));
            let url = `/api/room_discounts`;
            let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Discount created successfully`,
                    type: "info"
                });
            }
            else {
                this.$notify({
                    text: `Discount create failed`,
                    type: "error"
                });
            }

            this.selectedRooms = [];
            this.getDiscountList(1);
        },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        async confirmDeleteBtnClicked() {
            let url = `/api/room_discounts/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Discount deleted successfully`,
                    type: "info"
                });
            }
            else {
                this.$notify({
                    text: `Discount delete failed`,
                    type: "error"
                });
            }

            this.deleteId = null;
            this.getDiscountList(1);
        },
    },

    created() {
        this.getRoomList();
        this.getDiscountList(1);
    },

    mounted() {

    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
