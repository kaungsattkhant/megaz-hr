<template>
    <div>
        <div class="">
            <div class="w-full pt-9 px-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <button class="pos-add-btn">
                            Date
                        </button>
                    </div>
                    <div class="flex gap-x-3">
                        <!-- <button class="pos-add-btn !bg-[#F15181]">
                            Close
                        </button> -->
                        <button class="pos-add-btn" data-te-toggle="modal" data-te-target="#create_cashbook_modal">

                            Add

                        </button>
                    </div>
                </div>

                <div>
                    <div class="bg-white px-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Id</th>
                                    <th scope="col" class="px-6 py-4">Title</th>
                                    <th scope="col" class="px-6 py-4">Type</th>
                                    <th scope="col" class="px-6 py-4">Amount</th>
                                    <th scope="col" class="px-6 py-4">Remark</th>
                                    <th scope="col" class="px-6 py-4">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(cashbook,index) in cashbookList.cashbook_list">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ index+1 }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">

                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        3
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        4
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        5
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        6
                                    </td>
                                </tr>

                                <tr class="" v-for="(cashbook, index) in bankbookList.cashbook_list">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ cashbookLength }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">

                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        3
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        4
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        5
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        6
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_cashbook_modal" tabindex="-1" aria-labelledby="createCashbookModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Create Cashbook
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none
                        " id="closeModal" data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-16 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Title
                            </label>
                            <input type="text" placeholder="Title"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Type
                            </label>
                            <select name="" id=""
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option value="1"> type </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Amount
                            </label>
                            <input type="text" placeholder="Amount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Remark
                            </label>
                            <textarea
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                name="" id="" cols="30" rows="10"></textarea>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button class="pos-add-btn !px-16 focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>







    </div>

</template>
<script>
    import { Modal, Ripple, Select, Datepicker, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                cashbookList:[],
                bankbookList:[],
                totalBookList:null,
                cashAccountList:null,
                cashbookId:null,
                bankbookId:null,
                cashbookLength:null,

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getCashAccount(){
                const response = await getApiData({ url: '/api/get_cash_account', token: this.getToken() });
                if (response.data) {
                    this.cashAccountList = response.data;

                    let posCashAccount = this.cashAccountList.find(account => account.account_code === '2-1011');
                    if (posCashAccount) {
                        this.cashBookId = posCashAccount.id;
                        console.log(this.cashbookId)
                    }
                    let posBankAccount = this.cashAccountList.find(account => account.account_code === '2-1012');
                    if (posBankAccount) {
                        this.bankBookId = posBankAccount.id;
                        console.log(this.bankbookId)
                    }
                    // this.cashbookId = this.cashAccountList.find(cashAccountList => cashAccountList.account_code == '2-1011').id;
                    // this.bankbookId = this.cashAccountList.find(cashAccountList => cashAccountList.account_code == '2-1012').id;
                }
            },

            async getCashbookList(){
                if(this.cashbookId != null){
                    const response = await getApiData({ url: '/api/cash_books?cash_account_id=' + this.cashbookId, token: this.getToken() });

                    if (response.data) {
                        this.cashbookList = response.data;
                        this.cashbookLength = this.cashbookList.length;
                        console.log('got cashbook')
                    }
                }

                // console.log(this.cashbookId)
                
            },
            async getBankbookList() {
                const response = await getApiData({ url: '/api/cash_books?cash_account_id=' + this.bankbookId , token: this.getToken() });
                if (response.data) {
                    this.bankbookList = response.data;
                    console.log('got bankbook')
                }
            },
            async getTotalBookList() {
                Array.prototype.push.apply(this.cashbookList, this.bankbookList);

            }

                // for close transaction
            // let url = '/api/close_cashbook_transaction?cash_account_id=298';
            //     let response = await getApiData({ url: url, token: this.getToken() });
            //     let url = '/api/close_cashbook_transaction?cash_account_id=298';
            //     let response = await getApiData({ url: url, token: this.getToken() });
            //     if(response.data){
            //         this.subAccountList = response.data;
            //     }
        },
        mounted()
        {
            this.getCashAccount();
            
            this.getTotalBookList();
            initTE({ Modal, Select, Ripple, Datepicker });
        },
        created(){
            this.getCashbookList();
            this.getBankbookList();
        }
    }
</script>
