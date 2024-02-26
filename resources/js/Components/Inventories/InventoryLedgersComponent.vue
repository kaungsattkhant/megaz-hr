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
                                Item
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Opening
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                In
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Out
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Balance
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <div class="contents" v-for="(ledger, index) in inventoryLegderList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ ledger[0].item.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ ledger.opening }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ ledger.in }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ ledger.out }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ ledger.closing }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ ledger.closing * 1000}}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button id="edit-btn" class="pr-1"
                                        @click="transferBtnClicked(ledger[0].item.id)"
                                        data-te-toggle="modal" data-te-target="#transfer_modal">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <button class="pl-2"
                                        data-te-toggle="modal" data-te-target="#defect_modal">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="">
                                <td class=" col-span-full py-2 "></td>
                            </tr>
                        </div>

                            <!-- looping end -->
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Transfer Modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="transfer_modal" tabindex="-1" aria-labelledby="transfer_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                    <div class="relative  p-4">
                        <h5 class="text-xl text-center mt-2 font-medium leading-normal text-black" id="create_modalLabel">
                            Transfer
                        </h5>
                        <button type="button" id="close" class="absolute top-4 right-4 focus:shadow-none focus:outline-none"
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
                                Store
                            </label>
                            <select name="" id="" v-model="selectedInventory"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="store.id" v-for="(store,index) in inventoryList">{{ store.name }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Amount
                            </label>
                            <input type="text" placeholder="Amount" v-model="amount"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>

                    </div>
                    <div class="flex justify-center px-12 mb-6">
                        <button type="button" @click="confirmTransferBtnClicked"
                        class="add-btn focus:outline-none focus:ring-0 ">
                            Transfer
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

    export default {
        data() {
            return {
                inventoryLegderList:[],
                inventoryList:[],
                testopening:10,
                selectedInventory:null,
                amount:null,
                itemId:null,


                deleteId: null,
            };
        },
        props:['inventory_id'],

        methods: {
            async getInventoryLegderList(){
                const response = await getApiData({ url: '/api/inventories/'+this.inventory_id+'/ledgers' });
                if(response.data){
                    this.inventoryLegderList = response.data;
                    // calculate opening, incoming, outgoing and balance
                    for(let i=0; i<this.inventoryLegderList.length; i++){
                        this.inventoryLegderList[i]['opening'] = 0;
                        this.inventoryLegderList[i]['in'] = 0;
                        this.inventoryLegderList[i]['out'] = 0;
                        this.inventoryLegderList[i]['closing'] = 0;
                        for(let j=0; j<this.inventoryLegderList[i].length; j++){
                            if(this.inventoryLegderList[i][j].type == 'previous incoming'){
                                this.inventoryLegderList[i]['opening'] += this.inventoryLegderList[i][j].quantity;
                            }
                            if(this.inventoryLegderList[i][j].type == 'previous outgoing'){
                                this.inventoryLegderList[i]['opening'] -= this.inventoryLegderList[i][j].quantity;
                            }
                            if(this.inventoryLegderList[i][j].type == 'incoming'){
                                this.inventoryLegderList[i]['in'] += this.inventoryLegderList[i][j].quantity;
                            }
                            if(this.inventoryLegderList[i][j].type == 'outgoing'){
                                this.inventoryLegderList[i]['out'] += this.inventoryLegderList[i][j].quantity;
                            }
                        }
                        this.inventoryLegderList[i]['closing'] = (this.inventoryLegderList[i]['opening'] + this.inventoryLegderList[i]['in']) - this.inventoryLegderList[i]['out'];
                        console.log(this.inventoryLegderList[i]);
                    }
                }
            },
            async getInventoryList(){
                const response = await getApiData({ url: '/api/inventories'});
                if(response.data){
                    this.inventoryList = response.data;
                }
            },

            transferBtnClicked(id){
                this.itemId = id;
            },
            confirmTransferBtnClicked(){
                this.transferInventory();
            },

            async transferInventory()
            {
                let formData = new FormData();
                formData.append('source_inventory_id', this.inventory_id);
                formData.append('destination_inventory_id', this.selectedInventory);
                formData.append('quantity', this.amount);
                formData.append('item_id', this.itemId);
                let response = await postApiData({url: '/api/transfers', form_data: formData});
                if(response.success){
                    this.getInventoryLegderList(null);
                    console.log(this.inventory_id)
                    console.log(this.selectedInventory)
                    console.log(this.amount)
                    console.log(this.itemId)
                    this.closeModal();
                    this.clearForm();
                }
                else{
                    alert('some errors occur');
                }
            },

            closeModal() {
                document.getElementById("close").click();
            },

            clearForm() {
                this.selectedInventory = null,
                this.amount = null
            },



        },
        mounted()
        {
            this.getInventoryLegderList();
            this.getInventoryList();
            initTE({ Modal, Select, Ripple });
        }
    }
</script>
