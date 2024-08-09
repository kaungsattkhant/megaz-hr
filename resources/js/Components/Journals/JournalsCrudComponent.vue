<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Journals
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
                <div class=" table-container ">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Account
                                </th>
                                <th scope="col" class="">
                                    Particular
                                </th>
                                <th scope="col" class="">
                                    Debit
                                </th>
                                <th scope="col" class="">
                                    Credit
                                </th>
                                <th scope="col" class="">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="contents" v-for="(joural, index) in journalList" :key="index">
                                <tr class="">
                                    <td class="  ">
                                        {{ ++index }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ journal }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{ journal }}
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Modal -->
            <div data-te-modal-init
                class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                id="create_modal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
                <div data-te-modal-dialog-ref
                    class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                    <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                        <div class="relative flex justify-between py-2 px-6 border-b">
                            <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                                id="create_modalLabel">
                                Create Journal
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
                                    Particular
                                </label>
                                <input type="text" placeholder="Particular" v-model="particular" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Amount</label>
                                <input type="Number" placeholder="Amount" v-model="amount" class="input-ui">
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Credit Sub Account</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedCreditSubAcc"
                                    @change="getCreditAccList()" >
                                    <option class="text-sm" :value="sub" v-for="(sub,index) in subAccList" :key="index">
                                        {{ sub.name }}
                                    </option>

                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Credit Account</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedCreditAcc">
                                    <option class="text-sm" :value="credit" v-for="(credit,index) in creditAccList" :key="index">
                                        {{ credit.name }}
                                    </option>

                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Credit Sub Account</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedDebitSubAcc"
                                    @change="getDebitAccList()" >
                                    <option class="text-sm" :value="sub" v-for="(sub,index) in subAccList" :key="index">
                                        {{ sub.name }}
                                    </option>

                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="label-form mb-3">Credit Account</label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedDebitAcc">
                                    <option class="text-sm" :value="debit" v-for="(debit,index) in debitAccList" :key="index">
                                        {{ debit.name }}
                                    </option>

                                </select>
                            </div>

                        </div>
                        <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                            <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                                data-te-modal-dismiss aria-label="Close">
                                Cancel
                            </button>
                            <button type="button" @click="btnClickedCreateJournal()"
                                class="add-btn focus:outline-none focus:ring-0 ">
                                Create
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
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect
        },
        data() {
            return {

                journalList:[],
                subAccList:[],
                creditAccList:[],
                debitAccList:[],

                
                selectedCreditSubAcc:null,
                selectedCreditAcc:null,
                selectedDebitSubAcc:null,
                selectedDebitAcc:null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getJournalList(selectedMonth){
                const response = await getApiData({ url: '/api/journals?month=' + selectedMonth , token: this.getToken() });
                if(response.data){
                    this.journalList = response.data;
                }
            },
            async getSubAccList(){
                const response = await getApiData({ url: '/api/sub_accounts', token: this.getToken() });
                if(response.data){
                    this.subAccList = response.data;
                }
            },
            async getCreditAccList(){
                const response = await getApiData({ url: '/api/account_by_sub_account/' + this.selectedCreditSubAcc.id , token: this.getToken()});
                if(response.data){
                    this.creditAccList = response.data;
                }
            },
            async getDebitAccList(){
                const response = await getApiData({ url: '/api/account_by_sub_account/' + this.selectedDebitSubAcc.id , token: this.getToken()});
                if(response.data){
                    this.debitAccList = response.data;
                }
            },
            btnClickedCreateJournal(){
                this.createJournal();
            },

            async createJournal()
            {
                let formData = new FormData();
                formData.append('particular', this.particular);
                formData.append('amount', this.amount);
                formData.append('credit_account_id', this.selectedCreditSubAcc.id);
                formData.append('debit_account_id', this.selectedDebitSubAcc.id);
                let response = await postApiData({url: '/api/journals', form_data: formData, token: this.getToken()});
                if(response.success){
                    // this.getJournalList();
                    console.log('journal created')
                }
                else{

                }
            },

            // isActiveToggled(id){
            //     let index = this.areaList.findIndex(area => area.id == id);
            //     if(index != -1){
            //         if(this.areaList[index].is_active == 1){
            //             this.areaList[index].is_active = 0;
            //         }
            //         else{
            //             this.areaList[index].is_active = 1;
            //         }

            //         let url = `/api/is_active`;
            //         let formData = new FormData();
            //         formData.append('id', id);
            //         formData.append('type', 'area');
            //         let response = postApiData({url: url, form_data: formData, token: this.getToken()});
            //     }
            // },

            // deleteBtnClicked(id){
            //     this.deleteId = id;
            // },

            // async confirmDeleteBtnClicked(){
            //     let url = `/api/areas/${this.deleteId}`;
            //     let response = await deleteApiData({url: url, token: this.getToken()});
            //     if(response.success){
            //         this.getAreasList(null);
            //         console.log(`deleted`);
            //     }
            // }

        },
        mounted()
        {
            
            initTE({ Modal,Select, Ripple });
        },
        created(){
            this.getJournalList(1);
            this.getSubAccList();
        }
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
