<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Order Items by Procurement Team
                </p>
            </div>
            <div class="btn-container">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
                </div>
                <div class="flex pr-0 gap-x-4">
                    <!-- <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type" @change="selectedTypeChanged()"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type.value" v-for="(type, typeIndex) in typeList"
                                :key="typeIndex"> {{ type.name }} </option>
                        </select>
                    </div> -->
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
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Item
                                </th>
                                <th scope="col" class="">
                                    PO Number
                                </th>
                                <th scope="col" class="">
                                    Qty ( UOM )
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(contact, index) in orderList" :key="index">
                                <tr class="" data-te-collapse-init :data-te-target="'#orderCollapse'+index"
                                aria-expanded="false" aria-controls="collapseExample">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ contact.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ contact.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ contact.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#edit_modal" id="edit-btn" class="pr-3"
                                            @click="editBtnClicked(contact, index)">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <button @click="deleteBtnClicked(contact.id)"
                                            data-te-toggle="modal" data-te-target="#deleteModal" id="delete-btn" class="pr-1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="!visible text-center hidden" :id="'orderCollapse'+index" data-te-collapse-item>
                                    <td colspan="2"></td>
                                    <td>101</td>
                                    <td>70</td>
                                    <td>{{ index }}</td>
                                </tr>
                                <tr class="!visible text-center hidden" :id="'orderCollapse'+index" data-te-collapse-item>
                                    <td colspan="2"></td>
                                    <td>101</td>
                                    <td>70</td>
                                    <td>{{ index }}</td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getOkrList(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getOkrList(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- create modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="con" tabindex="-1" aria-labelledby="edit_modalLabel" aria-hidden="true">    
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="edit_modalLabel">
                        Add Contact
                    </h5>
                    <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss id="close_create_modal"
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                    <!-- <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Phone Number
                        </label>
                        <input type="text" placeholder="Phone Number" v-model="ph_number" class="input-ui">
                    </div> -->
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                    </button>
                    <button type="button" @click="btnClickedCreateContact()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                    </button>
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
            invoiceList: [],

            searchInput:null,

            url:'/api/contacts',
            url_search:'',
            deleteId:null,   

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

        };
    },

    methods: {
        ...mapGetters(['getToken']),
        async getInvoiceList(pageNumber) {
            let url = this.url;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.invoiceList = response.data;
                // this.lastPage = response.data.last_page;
                // this.currentPage = pageNumber;
                // this.perPage = response.data.per_page;
            }
        },

        async editBtnClicked(contact, index){
            let url = `/api/contacts/` + contact.id;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.editDetail = response.data;
                this.ph_number_edit = response.data.phone_number;
            }
            
        },

        btnClickedEditGps(){
            if(!this.ph_number_edit){
                this.alertValidationMessage(`Phone Number`);
                return 1;
            }
            else{
                this.editGps();
            }
        },
        async editGps(){
            let formData = new FormData();
            formData.append('phone_number',this.ph_number_edit);
            formData.append('id',this.editDetail.id);
            let response = await postApiData({url:`/api/contacts`, form_data:formData, token:this.getToken()})
            if(response.success){
                this.getInvoiceList();
                document.getElementById("close_edit_modal").click();
            }
        },

        

        // async searchBtnClicked() {
        //     this.url_search = '&search=' + this.searchInput
        //     this.getGpsList(1);
        // },
        // clearSearchBtnClicked() {
        //     this.searchInput = null;
        //     this.url_search = '';
        //     this.getGpsList(1);
        // },
        
        alertValidationMessage(field) {
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to provide ${field}, please try again`,
                    type: 'warn'
                });
            },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getInvoiceList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>