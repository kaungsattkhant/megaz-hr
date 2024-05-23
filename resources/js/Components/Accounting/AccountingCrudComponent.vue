<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Accounting
        </p>
    </div>
    
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <div class=" flex">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                    <i class="fal fa-search"></i>
                </label>

                <button class="add-btn" @click="searchBtnClicked">Search</button>
                <button class="add-btn" @click="clearSearchBtnClicked">Clear</button>
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
                <div class="overflow-hidden ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    Code
                                </th>
                                <th scope="col" class="">
                                    Account Name
                                </th>
                                <th scope="col" class="">
                                    Debit Amount
                                </th>
                                <th scope="col" class="">
                                    Credit Amount
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(account, index) in accountList" :key="index">
                                <tr class="">
                                    <td class="">
                                        {{ account.account_code }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ account.name }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ account.debit_amount }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ account.credit_amount }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button data-te-toggle="modal" data-te-target="#editModal" id="edit-btn" class="pr-3"
                                        @click="editBtnClicked(account.id, index)">
                                            <i class="fal fa-pen"></i>
                                        </button>
                                        <!-- <button data-te-toggle="modal" data-te-target="#deleteModal" id="edit-btn" class="pr-1" @click="deleteBtnClicked(account.id, index)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button> -->
                                        <input
                                        :checked="account.is_active == 1"
                                        @change="isActiveToggled(account.id)"
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
                                        role="switch"/>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                </div>

                <div class="mt-2 ml-2">
                    <ul v-if="paginationGroupsCount > 1" class="list-style-none flex">
                        <!-- <li>
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                            hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="firstPaginationGroupBtnClicked">
                                First
                            </button>
                        </li> -->
                        <li v-if="!isFirstGroup">
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                            hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="previousPaginationGroupBtnClicked"
                            :disabled="isFirstGroup">
                                Previous
                            </button>
                        </li>

                        <!-- loop link 1 , 2 ,3  ... replace normal-pagination with active-pagination for active pagination page-->
                        <li v-for="(pageNumber, pageNumberIndex) in groupedPageNumbers[currentGroup]" :key="pageNumberIndex"
                            :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                            <button v-if="pageNumber == currentPage"
                                class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                                <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                    (current)
                                </span>
                            </button>
                            <button v-else
                                class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                            </button>
                        </li>
                        <li v-if="!isLastGroup">
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                            hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="nextPaginationGroupBtnClicked"
                            :disabled="isLastGroup">
                                Next
                            </button>
                        </li>
                        <!-- <li>
                            <button class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300
                            hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white" @click="lastPaginationGroupBtnClicked">
                                Last
                            </button>
                        </li> -->
                    </ul>

                    <ul v-else class="list-style-none flex">
                        <li v-for="(pageNumber, pageNumberIndex) in pageNumbers" :key="pageNumberIndex"
                            :aria-current="(pageNumber == currentPage) ? 'page' : ''">
                            <button v-if="pageNumber == currentPage"
                                class="relative block rounded bg-neutral-800 px-3 py-1.5 text-sm font-medium text-neutral-50 transition-all duration-300 dark:bg-neutral-900"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                                <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                    (current)
                                </span>
                            </button>
                            <button v-else
                                class="normal-pagination relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                                :id="'paginationBtn-' + pageNumberIndex" @click="pageBtnClicked(pageNumber)">
                                {{ pageNumber }}
                            </button>
                        </li>
                    </ul>
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
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter">
                            Create Account
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
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Account Name
                            </label>
                            <input type="text" placeholder="Account Name" v-model="name"
                                class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Account Code
                            </label>
                            <input type="text" placeholder="Account Code" v-model="code"
                                class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Head Account
                            </label>
                            <div class="w-full" data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Head Account" v-model="selectedHeadAccount"
                                data-te-select-filter="true" @change="headAccountSelectChanged" class="input-ui w-full">
                                    <option :value="headAccount" v-for="(headAccount, headAccountIndex) in headAccountList"> {{ headAccount.name }} </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Sub Account
                            </label>
                            <div class="w-full" data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Sub Account" v-model="selectedSubAccount"
                                data-te-select-filter="true" class="input-ui">
                                    <option :value="subAccount" v-for="(subAccount, subAccountIndex) in subAccountList"> {{ subAccount.name }} </option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
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

                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter">
                            Edit Account
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
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Account Name
                            </label>
                            <input type="text" placeholder="Account Name" v-model="nameEdit"
                                class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Account Code
                            </label>
                            <input type="text" placeholder="Account Name" v-model="codeEdit"
                                class="input-ui">
                        </div>

                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Sub Account
                            </label>
                            <div class="w-full" data-te-select-wrapper-ref>
                                <select data-te-select-init data-te-select-placeholder="Select Sub Account" v-model="selectedSubAccount"
                                data-te-select-filter="true" class="input-ui">
                                    <option :value="subAccount" v-for="(subAccount, subAccountIndex) in subAccountList"> {{ subAccount.name }} </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button data-te-modal-dismiss type="button" class="add-btn focus:outline-none focus:ring-0 " @click="confirmEditBtnClicked">
                            Update
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
                headAccountList: [],
                subAccountList: [],

                code: null,
                name: null,
                selectedHeadAccount: null,
                selectedSubAccount: null,

                nameEdit: null,
                codeEdit: null,
                editId: null,
                editIndex: null,

                deleteId: null,
                deleteIndex: null,

                searchInput: null,

                per_page: 20,
                currentPage: 1,
                pageNumbers: [],
                paginationGroupsCount: 1,
                groupedPageNumbers: [],
                currentGroup: 0,
                isFirstGroup: true,
                isLastGroup: false,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getHeadAccountList(){
                let url = `/api/head_accounts`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.headAccountList = response.data;
                }
            },

            async headAccountSelectChanged(){
                this.getSubAccountList(this.selectedHeadAccount.id);
            },

            async getSubAccountList(headAccountId){
                if(headAccountId){
                    let url = `/api/sub_account_by_head_account/${headAccountId}`;
                    let response = await getApiData({url: url, token: this.getToken()});
                    if(response.data){
                        this.subAccountList = response.data;
                    }
                }
                else{
                    let url = `/api/sub_accounts/`;
                    let response = await getApiData({url: url, token: this.getToken()});
                    if(response.data){
                        this.subAccountList = response.data;
                    }
                }
            },

            async getAccountList(pageNumber){
                if(pageNumber){
                    this.currentPage = pageNumber;
                }
                let url = `/api/accounts?page=${this.currentPage}`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.accountList = response.data.data;
                    this.per_page = response.data.per_page;

                    this.pageNumbers = [];
                    this.lastPageNumber = response.data.last_page;

                    for(let i=1; i<=response.data.last_page; i++){
                        this.pageNumbers.push(i);
                    }

                    if(this.pageNumbers.length > 10){
                        this.groupedPageNumbers = [];
                        this.paginationGroupsCount = this.pageNumbers.length % 10;
                        for(let i=0; i<this.pageNumbers.length; i+=10){
                            let chunk = this.pageNumbers.slice(i, i+10);
                            this.groupedPageNumbers.push(chunk);
                        }

                        let lastGroupIndex = this.groupedPageNumbers.length - 1;
                        this.isFirstGroup = (this.currentGroup === 0);
                        this.isLastGroup = (lastGroupIndex === this.currentGroup);
                    }
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

            async editBtnClicked(id, index){
                let url = `/api/sub_accounts/`;
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.subAccountList = response.data;
                }
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

            isActiveToggled(id){
                let index = this.accountList.findIndex(account => account.id == id);
                if(index != -1){
                    if(this.accountList[index].is_active == 1){
                        this.accountList[index].is_active = 0;
                    }
                    else{
                        this.accountList[index].is_active = 1;
                    }

                    let url = `/api/is_active`;
                    let formData = new FormData();
                    formData.append('id', id);
                    formData.append('type', 'account');
                    let response = postApiData({url: url, form_data: formData, token: this.getToken()});
                }
            },

            // deleteBtnClicked(id, index){
            //     this.deleteId = id;
            //     this.deleteIndex = index;
            // },

            // async confirmDeleteBtnClicked(){
            //     let formData = new FormData();
            //     formData.append("id", this.deleteId);
            //     formData.append("type", "account");
            //     let response = await postApiData({ url: `/api/is_active`, form_data: formData, token: this.getToken() });
            //     if(response.data){
            //         this.accountList.splice(this.deleteIndex, 1);
            //     }

            //     this.deleteId = null;
            //     this.deleteIndex = null;
            // },

            async searchBtnClicked(){
                let url = null;
                if(this.searchInput){
                    url = `/api/accounts?search_input=${this.searchInput}&page=1`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.accountList = response.data.data;
                }
            },

            clearSearchBtnClicked(){
                this.searchInput = null;
                this.searchCategory = null;
                this.getAccountList(null);
            },

            pageBtnClicked(pageNumber){
                this.currentPage = pageNumber;
                this.getAccountList(this.currentPage);
            },

            nextPaginationGroupBtnClicked(){
                this.currentGroup += 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getAccountList(this.currentPage);
            },

            previousPaginationGroupBtnClicked(){
                this.currentGroup -= 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getAccountList(this.currentPage);
            },

            firstPaginationGroupBtnClicked(){
                this.currentGroup = 0;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][0]);
                this.getAccountList(this.currentPage);
            },

            lastPaginationGroupBtnClicked(){
                this.currentGroup = this.paginationGroupsCount - 1;
                let lastIndex = this.groupedPageNumbers[this.currentGroup].length - 1;
                this.currentPage = (this.groupedPageNumbers[this.currentGroup][lastIndex]);
                this.getAccountList(this.currentPage);
            }
        },

        created(){
            this.getHeadAccountList();
            // this.getSubAccountList();
            this.getAccountList();
        },

        mounted()
        {
            initTE({ Modal,Select, Ripple, Dropdown });
        }
    }
</script>
