<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">

                <i class="fal fa-search"></i>
            </label>
        </div>
        <div class="flex justify-end flex-col">

            <button type="button" class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                data-te-toggle="modal" data-te-target="#create_modal">
                Add New
            </button>
        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Inventory Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Departments/Areas
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- looping start -->
                        <div class="contents" v-for="(inventory, index) in inventoryList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ perPage * (currentPage - 1) + (++index) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ inventory.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    <div v-for="inventoryable in inventory.inventoryable" :key="inventoryable">
                                        {{ inventoryable.inventoryable.name }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <!-- <button id="edit-btn" class="pr-1" @click="deleteBtnClicked(inventory.id)"
                                    data-te-toggle="modal" data-te-target="#deleteModal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> -->
                                    <input :checked="inventory.is_active == 1" @change="isActiveToggled(inventory.id)"
                                        class="me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                    before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5
                                    after:w-5 after:rounded-full after:border-none after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                    after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                    checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                    checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                    focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                    focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                    checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                    checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-white/25 dark:after:bg-surface-dark dark:checked:bg-primary dark:checked:after:bg-primary"
                                        type="checkbox" role="switch" />

                                    <button data-te-toggle="modal" data-te-target="#editModal" id="edit-btn"
                                        class="pr-3 ml-2" @click="editBtnClicked(inventory.id)">
                                        <i class="fal fa-pen"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>

                        <!-- looping end -->
                    </tbody>
                </table>

                <!-- pagination -->
                <div class="flex justify-center">

                    <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                        <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                            @click="getInventoryList(currentPage - 1)">«</button>

                        <button class=" text-sm px-5 border">
                            Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                lastPage }}</span>
                        </button>

                        <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                            :disabled="currentPage === lastPage" @click="getInventoryList(currentPage + 1)">
                            »</button>
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

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black"
                            id="create_modalLabel">
                            Create Inventory
                        </h5>
                        <button type="button" id="close"
                            class="absolute top-4 right-4 focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Inventory Name
                            </label>
                            <input type="text" placeholder="Inventory Name" v-model="name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Start Time
                            </label>
                            <input type="time" placeholder="Start Time" v-model="start_time"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                End Time
                            </label>
                            <input type="time" placeholder="End Time" v-model="end_time"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Department / Area
                            </label>
                            <select name="" id="" v-model="selectedInventoryType" @change="inventoryableTypeChanged()"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="area">Area</option>
                                <option value="department">Department</option>
                            </select>
                        </div>

                        <div class="mb-4" v-if="inventoryableList.length > 0">
                            <label v-if="selectedInventoryType == 'area'" for="" class="block text-sm text-black mb-3">
                                Area
                            </label>
                            <label v-if="selectedInventoryType == 'department'" for=""
                                class="block text-sm text-black mb-3">
                                Department
                            </label>
                            <select name="" id="" v-model="selectedInventoryable"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                @change="inventoryableSelectChanged">
                                <option :value="inventoryable" v-for="(inventoryable, index) in inventoryableList"
                                    :key="index">{{ inventoryable.name }}</option>
                            </select>

                            <span v-for="inventoryableId in inventoryableIds" :key="inventoryableId"> {{
                                inventoryableId.name }}, </span>
                        </div>

                    </div>
                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" @click="createBtnClicked"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
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

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black"
                            id="create_modalLabel">
                            Create Inventory
                        </h5>
                        <button type="button" id="close"
                            class="absolute top-4 right-4 focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Inventory Name
                            </label>
                            <input type="text" placeholder="Inventory Name" v-model="nameEdit"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Start Time
                            </label>
                            <input type="time" placeholder="Start Time" v-model="edit_start_time"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                End Time
                            </label>
                            <input type="time" placeholder="End Time" v-model="edit_end_time"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div class="mb-4" v-if="inventoryableListEdit.length > 0">
                            <label v-if="selectedInventoryTypeEdit == 'area'" for=""
                                class="block text-sm text-black mb-3">
                                Area
                            </label>
                            <label v-if="selectedInventoryTypeEdit == 'department'" for=""
                                class="block text-sm text-black mb-3">
                                Department
                            </label>
                            <select name="" id="" v-model="selectedInventoryableEdit"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                @change="inventoryableEditSelectChanged">
                                <option :value="inventoryable" v-for="(inventoryable, index) in inventoryableListEdit"
                                    :key="index">{{ inventoryable.name }}</option>
                            </select>

                            <span v-for="inventoryableId in inventoryableIdsEdit" :key="inventoryableId"> {{
                                inventoryableId.name }}, </span>
                        </div>
                    </div>
                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" @click="confirmEditBtnClicked"
                            class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                            Update
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
import { Modal, Ripple, Select, initTE, Input, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            inventoryList: [],
            inventoryableList: [],
            areaList: [],
            departmentList: [],
            name: null,
            selectedInventoryType: null,
            selectedInventoryable: null,
            inventoryableIds: [],

            inventoryableListEdit: [],
            editId: null,
            inventoryEdit: null,
            nameEdit: null,
            selectedInventoryTypeEdit: null,
            selectedInventoryableEdit: null,
            inventoryableIdsEdit: [],

            deleteId: null,

            start_time: null,
            end_time: null,
            edit_start_time: null,
            edit_end_time: null,
            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        inventoryableSelectChanged() {
            if (this.inventoryableIds.length < 1) {
                this.inventoryableIds.push(this.selectedInventoryable);
            }
            else {
                let index = this.inventoryableIds.findIndex(inventoryable => inventoryable.id == this.selectedInventoryable.id);
                if (index != -1) {
                    this.inventoryableIds.splice(index, 1);
                }
                else {
                    this.inventoryableIds.push(this.selectedInventoryable);
                }
            }
        },

        inventoryableEditSelectChanged() {
            if (this.inventoryableIdsEdit.length < 1) {
                this.inventoryableIdsEdit.push(this.selectedInventoryableEdit);
            }
            else {
                let index = this.inventoryableIdsEdit.findIndex(inventoryable => inventoryable.id == this.selectedInventoryableEdit.id);
                if (index != -1) {
                    this.inventoryableIdsEdit.splice(index, 1);
                }
                else {
                    this.inventoryableIdsEdit.push(this.selectedInventoryableEdit);
                }
            }
        },

        async getInventoryList(pageNumber) {
            const response = await getApiData({ url: `/api/inventories?page=${pageNumber}`, token: this.getToken() });
            if (response.data) {
                this.inventoryList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        async getAreaList() {
            const response = await getApiData({ url: '/api/areas', token: this.getToken() });
            if (response.data) {
                this.inventoryableList = response.data;
                this.inventoryableIds = [];
            }
        },
        async getDepartmentList() {
            const response = await getApiData({ url: '/api/departments', token: this.getToken() });
            if (response.data) {
                this.inventoryableList = response.data;
                this.inventoryableIds = [];
            }
        },

        inventoryableTypeChanged() {
            // alert(this.selectedInventoryType + ' test');
            if (this.selectedInventoryType == 'area') {
                this.getAreaList();
            }
            if (this.selectedInventoryType == 'department') {
                this.getDepartmentList();
            }
        },

        async getinventoryListForEdit(type) {
            const response = await getApiData({ url: `/api/${type}s`, token: this.getToken() });
            if (response.data) {
                this.inventoryableListEdit = response.data;
                // console.log(this.inventoryableListEdit);
                // this.inventoryableIdsEdit = [];
            }
        },

        createBtnClicked() {
            console.log(this.name);

            console.log(this.selectedInventoryType);
            console.log(this.inventoryableIds);
            this.createInventory();
        },

        async createInventory() {
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('inventoryable_type', this.selectedInventoryType);
            formData.append('start_time', this.start_time);
            formData.append('end_time', this.end_time);
            this.inventoryableIds.forEach((inventoryable) => {
                formData.append('inventoryable_id[]', inventoryable.id);
            });
            let response = await postApiData({ url: '/api/inventories', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getInventoryList(1)
                window.location.reload();
                this.closeModal();
                this.clearForm();
            }
            else {
                alert('some errors occur');
            }
        },

        editBtnClicked(id) {
            this.editId = id;
            let inventory = this.inventoryList.find(inventory => inventory.id == this.editId);
            if (inventory) {
                this.inventoryEdit = inventory;
                console.log(this.inventoryEdit);
                this.nameEdit = this.inventoryEdit.name;
                this.edit_start_time = this.inventoryEdit.start_time;
                this.edit_end_time = this.inventoryEdit.end_time;
                this.selectedInventoryTypeEdit = this.inventoryEdit.inventoryable[0].inventoryable_type;
                console.log(this.nameEdit);
                console.log(this.selectedInventoryTypeEdit);
                this.getinventoryListForEdit(this.selectedInventoryTypeEdit);
                this.inventoryEdit.inventoryable.forEach((inventoryable) => {
                    // console.log(inventoryable.inventoryable);
                    this.inventoryableIdsEdit.push(inventoryable.inventoryable);
                });
                // console.log(this.inventoryableIdsEdit);
            }
        },

        async confirmEditBtnClicked() {
            let formData = new FormData();
            formData.append('id', this.editId);
            formData.append('name', this.nameEdit);
            formData.append('start_time', this.edit_start_time);
            formData.append('end_time', this.edit_end_time);
            formData.append('inventoryable_type', this.selectedInventoryTypeEdit);
            this.inventoryableIdsEdit.forEach((inventoryable) => {
                formData.append('inventoryable_id[]', inventoryable.id);
            });
            let response = await postApiData({ url: '/api/inventories', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.getInventoryList(1);
                console.log("success")
                this.closeModal();
                this.clearForm();
            }
            else {
                alert('some errors occur');
            }
        },

        closeModal() {
            document.getElementById("close").click();
        },

        clearForm() {
            this.name = null,
                this.selectedInventoryType = null,

                this.selectedInventoryable = null
            this.typeList = []
        },

        isActiveToggled(id) {
            let index = this.inventoryList.findIndex(inventory => inventory.id == id);
            if (index != -1) {
                if (this.inventoryList[index].is_active == 1) {
                    this.inventoryList[index].is_active = 0;
                }
                else {
                    this.inventoryList[index].is_active = 1;
                }

                let url = `/api/is_active`;
                let formData = new FormData();
                formData.append('id', id);
                formData.append('type', 'inventory');
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
            }
        },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        async confirmDeleteBtnClicked() {
            let url = `/api/inventories/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                alert(`deleted`);
            }
        }

    },

    created() {
        this.getInventoryList(1);
    },

    mounted() {
        initTE({ Modal, Select, Ripple, Dropdown });
    }
}
</script>
