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
                                Code
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Account Name
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Debit Amount
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Credit Amount
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- looping start -->
                        <div class="contents" v-for="(account, index) in accountList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ account.account_code }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ account.name }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 ">
                                    unknown
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    unknown
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button data-te-toggle="modal" data-te-target="#editModal" id="edit-btn" class="pr-3"
                                    @click="editBtnClicked(account.id, index)">
                                        <i class="fal fa-pen"></i>
                                    </button>
                                    <button data-te-toggle="modal" data-te-target="#deleteModal" id="edit-btn" class="pr-1" @click="deleteBtnClicked(account.id, index)">
                                        <i class="fas fa-trash-alt"></i>
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

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                            Create Account
                        </h5>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Account Name
                            </label>
                            <input type="text" placeholder="Account Name" v-model="name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Account Code
                            </label>
                            <input type="text" placeholder="Account Name" v-model="code"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Sub Account
                            </label>
                            <div class="bg-white mb-0 w-[100%] text-sm inline-block" data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Sub Account" v-model="selectedSubAccount"
                                data-te-select-filter="true">
                                    <option :value="subAccount" v-for="(subAccount, subAccountIndex) in subAccountList"> {{ subAccount.name }} </option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="flex justify-center px-12 mb-6">
                        <button data-te-modal-dismiss type="button" class="add-btn focus:outline-none focus:ring-0 " @click="createBtnClicked">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                            Edit Account
                        </h5>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-12 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Account Name
                            </label>
                            <input type="text" placeholder="Account Name" v-model="nameEdit"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Account Code
                            </label>
                            <input type="text" placeholder="Account Name" v-model="codeEdit"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Sub Account
                            </label>
                            <div class="bg-white mb-0 w-[100%] text-sm inline-block" data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Sub Account" v-model="selectedSubAccount"
                                data-te-select-filter="true">
                                    <option :value="subAccount" v-for="(subAccount, subAccountIndex) in subAccountList"> {{ subAccount.name }} </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button data-te-modal-dismiss type="button" class="add-btn focus:outline-none focus:ring-0 " @click="confirmEditBtnClicked">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete Modal -->
        <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)]
            min-[576px]:max-w-[500px]">
                    <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                        <div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                            <!--Modal title-->
                            <h5 class="text-xl font-medium leading-normal text-neutral-800" id="exampleModalLabel"> Delete ? </h5>
                            <!--Close button-->
                            <button type="button" class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100
                            focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-6 w-6">
                                    <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
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
                        <div class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2
                        border-neutral-100 border-opacity-100 p-4 ">
                            <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                                Close
                            </button>
                            <button @click="confirmDeleteBtnClicked" type="button" data-te-toggle="modal" data-te-target="#deleteModal"
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
                accountList: [],
                subAccountList: [],

                code: null,
                name: null,
                selectedSubAccount: null,

                nameEdit: null,
                codeEdit: null,
                editId: null,
                editIndex: null,

                deleteId: null,
                deleteIndex: null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getSubAccountList(){
                let url = `/api/sub_accounts`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.subAccountList = response.data;
                }
            },

            async getAccountList(){
                let url = `/api/accounts`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.accountList = response.data;
                }
            },

            alertValidationMessage(field){
                alert(`You forgot to provide ${field}, please try again`);
            },

            async createBtnClicked(){
                if(!this.name || !this.code || !this.selectedSubAccount){
                    if(!this.name){
                        this.alertValidationMessage("account name");
                    }
                    if(!this.code){
                        this.alertValidationMessage("account code");
                    }
                    if(!this.selectedSubAccount){
                        this.alertValidationMessage("sub account");
                    }

                    return 1;
                }

                let formData = new FormData();
                formData.append("id", null);
                formData.append("name", this.name);
                formData.append("account_code", this.code);
                formData.append("sub_account_id", this.selectedSubAccount.id);
                let response = await postApiData({ url: `/api/accounts`, form_data: formData, token: this.getToken() });
                if(response.data){
                    this.accountList.unshift(response.data);
                }

                this.name = null;
                this.code = null;
                this.selectedSubAccount = null;
            },

            editBtnClicked(id, index){
                this.editId = id;
                this.editIndex = index;
                let editAccount = this.accountList[this.editIndex];
                let subAccountIndex = this.subAccountList.findIndex(subAccount => subAccount.id == editAccount.sub_account_id);
                if(subAccountIndex >= 0){
                    this.selectedSubAccount = this.subAccountList[subAccountIndex];
                }
                this.nameEdit = editAccount.name;
                this.codeEdit = editAccount.account_code;
            },

            async confirmEditBtnClicked(){
                if(!this.nameEdit || !this.codeEdit || !this.selectedSubAccount){
                    if(!this.nameEdit){
                        this.alertValidationMessage("account name");
                    }
                    if(!this.codeEdit){
                        this.alertValidationMessage("account code");
                    }
                    if(!this.selectedSubAccount){
                        this.alertValidationMessage("sub account");
                    }

                    return 1;
                }
                let formData = new FormData();
                formData.append("id", this.editId);
                formData.append("name", this.nameEdit);
                formData.append("account_code", this.codeEdit);
                formData.append("sub_account_id", this.selectedSubAccount.id);
                let response = await postApiData({ url: `/api/accounts`, form_data: formData, token: this.getToken() });
                if(response.data){
                    this.accountList[this.editIndex] = response.data;
                }

                this.nameEdit = null;
                this.codeEdit = null;
                this.selectedSubAccount = null;
            },

            deleteBtnClicked(id, index){
                this.deleteId = id;
                this.deleteIndex = index;
            },

            async confirmDeleteBtnClicked(){
                let formData = new FormData();
                formData.append("id", this.deleteId);
                let response = await deleteApiData({ url: `/api/accounts`, form_data: formData, token: this.getToken() });
                if(response.data){
                    this.accountList.splice(this.deleteIndex, 1);
                }

                this.deleteId = null;
                this.deleteIndex = null;
            },

        },

        created(){
            this.getSubAccountList();
            this.getAccountList();
        },

        mounted()
        {
            initTE({ Modal,Select, Ripple, Dropdown });
        }
    }
</script>
