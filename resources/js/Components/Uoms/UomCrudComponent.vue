<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    UOM
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search">
                        <i class="fal fa-search"></i>
                    </label>

                    <!-- <button class="add-btn h-8 mx-2 " @click="searchBtnClicked">Search</button>
                <button class="add-btn h-8 mx-2 " @click="clearSearchBtnClicked">Clear</button> -->

                </div>
                <div class="flex justify-end flex-col">
                    <div class="flex gap-3">


                        <button type="button"
                            class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                            data-te-toggle="modal" data-te-target="#uom">
                            Create Uom
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="  ">
                                    #
                                </th>

                                <th scope="col" class="  ">
                                    Name
                                </th>

                                <th scope="col" class="">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />

                        <tr class=" !text-center" v-else-if="uomList.length < 1">
                            <td class="" colspan="5">
                                No Data Here
                            </td>
                        </tr>
                        <tbody v-else>
                            <!-- looping start -->
                            <div class="contents" v-for="(uom, itemIndex) in uomList" :key="itemIndex">
                                <tr class="">
                                    <td class="  ">
                                        {{ per_page * (currentPage - 1) + (++itemIndex) }}
                                    </td>
                                    <td class="whitespace-nowrap  ">
                                        {{ uom.name }}
                                    </td>

                                    <td class="whitespace-nowrap ">
                                        <button id="edit-btn" class="pr-1">
                                            <i class="fas fa-pencil-alt" @click="editBtnClicked(uom)"
                                                data-te-toggle="modal" data-te-target="#edit_modal"></i>
                                        </button>
                                        <!-- <input
                                    :checked="item.is_active == 1"
                                    @change="isActiveToggled(item.id)"
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
                                    type="checkbox"
                                    role="switch"/> -->
                                    </td>
                                </tr>
                            </div>

                            <!-- looping end -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal -->

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="uom"
        tabindex="-1" aria-labelledby="uomLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <!--Modal title-->
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Create UOM
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
                            Uom Name
                        </label>
                        <input type="text" placeholder="Uom" v-model="uom_name" class="input-ui">
                    </div>

                </div>

                <!--Modal footer-->
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                        @click="confirmCreateBtnClicked" data-te-modal-dismiss>
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="edit_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative  p-4">
                    <!--Modal title-->
                    <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                        Edit UOM
                    </h5>
                    <!--Close button-->
                    <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative px-12 py-4" data-te-modal-body-ref>

                    <div class="mb-4">
                        <label for="" class="block text-sm text-black mb-3">
                            Uom Name
                        </label>
                        <input type="text" placeholder="Uom" v-model="editUomName"
                            class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                    </div>
                </div>

                <!--Modal footer-->
                <div class="flex justify-center px-12 mb-6">
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                        @click="confirmEditBtnClicked" data-te-modal-dismiss>
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, putApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        TableSkeleton
    },
    data() {
        return {
            uomList: [],

            baseUnit: null,
            conversionUnit: null,
            conversionRate: null,

            baseUnitId: null,
            conversionUnitId: null,
            uom_name:null,
            uomList:[],

            baseUnitedit:null,
            conversionUnitedit:null,
            editUomId:null,
            editUomName:null,

            loading: true,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getUomList(pageNumber) {
            this.loading = true;
            let url = `/api/uoms?page=${pageNumber}`;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.loading = false;
                this.uomList = response.data.uoms;
            }
        },



        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },


        async confirmCreateBtnClicked() {
            if(!this.uom_name){
                this.alertValiationMessage(` uom name`);
                return 1;
            }
            let url = `/api/uoms`;
            let formData = new FormData();
            formData.append('name', this.uom_name);

            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.getUomList(this.currentPage);
                this.uom_name = null;
                // window.location.reload();
            }
        },

        editBtnClicked(uom){
            this.editUomId = uom.id;
            this.editUomName = uom.name;

        },

        async confirmEditBtnClicked(){
            let url = `/api/uoms/${this.editUomId}`;
            let formData = new FormData();
            formData.append('name', this.editUomName);

            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.getUomList(this.currentPage);
                this.editUomName = null;
            }
        },

        clearSearchBtnClicked() {
            this.searchInput = null;
            this.searchCategory = null;
            this.getUomList(null);
        },


    },

    created() {
        this.getUomList(null);
    },

    mounted() {
        initTE({ Modal, Ripple, Select, Dropdown });
    }
}
</script>
