<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
        </div>
        <div class="flex justify-end flex-col">
            <a href="/purchase_orders/create" class="add-btn ">
                Add New
            </a>

        </div>
    </div>
    <div class="block rounded-xl">
        <div class="overflow-x-auto">
            <div class="overflow-hidden ">
                <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                    <thead class="border-b font-medium ">
                        <tr>
                            <th scope="col" class=" px-6 py-4 ">
                                #
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Date
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Purchase Order Id
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Status
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Manager Check
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Financial Check
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Md Check
                            </th>


                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="contents" v-for="(purchaseOrder, index) in purchaseOrderList" :key="index">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ purchaseOrder.date }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ purchaseOrder.po_id }}
                                </td>

                                <td class=" px-6 py-4 ">
                                    {{ purchaseOrder.status }}
                                </td>

                                <td  class="px-6 py-4">
                                    {{ purchaseOrder.financial_check_id !== null ? 'Yes' : 'No' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ purchaseOrder.financial_check_id !== null ? 'Yes' : 'No' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ purchaseOrder.financial_check_id !== null ? 'Yes' : 'No' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 space-x-4">
                                    <button class="pr-1" @click="checkPurchaseOrderBtnClicked(purchaseOrder.id)" data-te-toggle="modal" data-te-target="#checkModal">
                                        <i class="far fa-check"></i>
                                    </button>
                                    <!-- <a :href="'/purchase_orders/'+purchaseOrder.id+'/confirm'" id="" class="pr-1">
                                        <i class="far fa-check"></i>
                                    </a> -->

                                    <a :href="'/purchase_orders/'+purchaseOrder.id+'/confirm'" id="" class="pr-1">
                                        <i class="far fa-bars"></i>
                                    </a>
                                    <button data-te-toggle="modal" id="edit-btn" class="pr-1"
                                    data-te-target="#deleteModal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr class="">
                                <td class=" py-2 "></td>
                            </tr>
                        </div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--Check Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="checkModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px]
            items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7
            min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col
                rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                <div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                        Check Item
                    </h5>
                    <!--Close button-->
                    <button type="button" class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                <div class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 ">
                    <button type="button" class="inline-block px-6 pb-2 pt-2.5 text-xs focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                        Close
                    </button>
                    <button @click="confirmCheckPurchaseOrderBtnClicked" type="button" data-te-toggle="modal" data-te-target="#checkModal"
                    class="ml-1 inline-block rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Modal, initTE } from "tw-elements";

    import { mapGetters } from 'vuex';
    import { getApiData, postApiData } from '../../utilities/ajax-helpers';
    import { convertToFriendlyDate } from '../../utilities/datetime-helpers';


    export default {
        data() {
            return {
                purchaseOrderList: [],
                checkId: null,
                isManager: false,
                isMD: false,
            };
        },

        methods: {
            ...mapGetters(['getToken','getUser', 'getRoles', 'getDepartment']),

            async getPurhaseOrderList(pageNumber){
                let url = `/api/purchase_orders`;
                if(pageNumber){
                    url = `${url}?page=${pageNumber}`;
                }
                let response = await getApiData({url: url, token: this.getToken()});
                if(response.data){
                    this.purchaseOrderList = response.data.data;
                    this.purchaseOrderList.forEach((po)=>{
                        po.date = convertToFriendlyDate(po.date);
                    });
                }
            },

            checkPurchaseOrderBtnClicked(purchaseOrderId){
                this.checkId = purchaseOrderId;
            },

            async confirmCheckPurchaseOrderBtnClicked(){
                if(this.checkId){
                    let url = `/api/updateIsCheck`;
                    let formData = new FormData();
                    formData.append('type', 'purchase_order');
                    formData.append('id',this.checkId);
                    formData.append('value', 1);
                    let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
                    if(response.success){
                        alert('PO checked');
                        window.location.reload();
                    }
                    else{
                        alert(response.message);
                    }
                }

                this.checkId = null;
            },
        },

        created()
        {
            this.getRoles().forEach((role)=>{
                if(role.name == 'Manager'){
                    this.isManager = true;
                }
                if(role.name == 'MD'){
                    this.isMD = true;
                }
            });
            this.getPurhaseOrderList(null);
        },

        mounted()
        {
            initTE({Modal});
        }
    }
</script>
