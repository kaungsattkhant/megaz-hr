<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Menu Categories
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
                    data-te-toggle="modal" data-te-target="#create_modal">
                    Add New
                </button>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Name
                                </th>
                                <th scope="col" class="">
                                    Image
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- looping start -->
                            <div class="contents" v-for="(category, index) in categoryList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ perPage * (currentPage - 1) + (++index) }}

                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ category.name }}
                                    </td>

                                    <td class="whitespace-nowrap  ">
                                        <div
                                            class="relative flex border rounded text-center shrink-0 overflow-hidden rounded-md h-12 w-12">
                                            <img width="80" height="100" style="aspect-ratio: 4/3; object-fit: cover;"
                                                :src="category.image_url" alt="Menu image">
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        <!-- <button @click="deleteBtnClicked(category.id)"
                                        data-te-toggle="modal" data-te-target="#deleteModal" id="edit-btn" class="pr-1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button> -->
                                        <button @click="editBtnClicked(category.id)" data-te-toggle="modal"
                                            data-te-target="#edit_modal" id="edit-btn" class="pr-2">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <input :checked="category.is_active == 1" @change="isActiveToggled(category.id)"
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

                                    </td>
                                </tr>
                            </div>

                            <!-- looping end -->
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getMenuCategoryList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getMenuCategoryList(currentPage + 1)">
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

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Create Menu Category
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Name
                                </label>
                                <input type="text" placeholder="Category Name" v-model="name" class="input-ui">
                            </div>

                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Image
                                </label>
                                <input type="file" class="input-ui" @change="handleFileChange"
                                    accept="image/png, image/gif, image/jpeg" ref="image">
                            </div>
                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button data-te-modal-dismiss type="button" @click="createBtnClicked"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="edit_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div
                        class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Edit Menu Category
                            </h5>
                            <button type="button" class="text-xs focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Name
                                </label>
                                <input type="text" placeholder="Category Name" v-model="name" class="input-ui">
                            </div>

                            <div class="mb-4">
                                <label for="" class="label-form mb-3">
                                    Image
                                </label>
                                <input type="file" class="input-ui" @change="handleFileChange"
                                    accept="image/png, image/gif, image/jpeg" ref="image">
                            </div>
                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button data-te-modal-dismiss type="button" @click="confirmEditBtnClicked"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Edit
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
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
    </div>

</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            categoryList: [],
            editId: null,
            deleteId: null,

            name: null,
            selectedImage: null,

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        };
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

        async getMenuCategoryList(pageNumber) {
            const response = await getApiData({ url: `/api/menu_categories?page=${pageNumber}`, token: this.getToken() });
            if (response.data) {
                this.categoryList = response.data.data;
                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },

        handleFileChange(event) {
            const selectedFile = event.target.files[0];
            this.selectedImage = selectedFile;
        },

        createBtnClicked() {
            if (!this.name) {
                this.alertValiationMessage(`category name`);
                return 1;
            }
            if (!this.selectedImage) {
                this.alertValiationMessage(`category image`);
                return 1;
            }
            this.createMenuCategory();
        },

        async createMenuCategory() {
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('image', this.selectedImage);
            let response = await postApiData({ url: '/api/menu_categories', form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Menu category created successfully`,
                    type: "info"
                });
                this.getMenuCategoryList(1);
                this.name = null;
                this.selectedImage = null;
            }
            else {
                this.$notify({
                    text: `Menu category create failed`,
                    type: "error"
                });
            }
        },

        editBtnClicked(id) {
            this.editId = id;
            let index = this.categoryList.findIndex(category => category.id == id);
            if (index != -1) {
                this.name = this.categoryList[index].name;
            }
        },

        async confirmEditBtnClicked() {
            let formData = new FormData();
            formData.append('name', this.name);
            if (this.selectedImage) {
                formData.append('image', this.selectedImage);
            }
            let response = await postApiData({ url: `/api/menu_categories/${this.editId}`, form_data: formData, token: this.getToken() });
            if (response.success) {
                this.$notify({
                    text: `Menu category edited successfully`,
                    type: "info"
                });
                this.getMenuCategoryList(1);
                this.name = null;
                this.selectedImage = null;
            }
            else {
                this.$notify({
                    text: `Menu category edit failed`,
                    type: "error"
                });
            }
        },

        isActiveToggled(id) {
            let index = this.categoryList.findIndex(category => category.id == id);
            if (index != -1) {
                if (this.categoryList[index].is_active == 1) {
                    this.categoryList[index].is_active = 0;
                }
                else {
                    this.categoryList[index].is_active = 1;
                }

                let url = `/api/is_active`;
                let formData = new FormData();
                formData.append('id', id);
                formData.append('type', 'menu_category');
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
            }
        },

        deleteBtnClicked(id) {
            this.deleteId = id;
        },

        async confirmDeleteBtnClicked() {
            let url = `/api/menu_categories/${this.deleteId}`;
            let response = await deleteApiData({ url: url, token: this.getToken() });
            if (response.success) {
                this.getMenuCategoryList(1);
            }
        }

    },

    created() {
        this.getMenuCategoryList(1);
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
