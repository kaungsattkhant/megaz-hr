<template>
    <notifications position="top center" />
    
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Delivery Charges
                </p>
            </div>
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
                        data-te-toggle="modal" data-te-target="#create_modal" @click="(delivery_charge = null, selectedDivision = null, selectedTownship = null)">
                        Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class=" ">
                                    Township Name
                                </th>
                                <th scope="col" class="">
                                    Charges
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg" v-for="(delivery,index) in deliveryChargeList" :key="index">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ index+1 }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ delivery.township.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ delivery.amount }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button
                                        data-te-toggle="modal" data-te-target="#create_modal" id="edit-btn" class="pr-1">
                                            <i class="fas fa-pen"></i>
                                    </button>

                                        <!-- <button @click="deleteBtnClicked(department.id)"
                                    data-te-toggle="modal" data-te-target="#deleteModal"
                                    class="pr-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                            Create Delivery Charges
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class=" mb-4">
                            <label for="" class="text-sm text-black mb-2 block">
                                Division
                            </label>
                            <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                v-model="selectedDivision"
                                @change="divisionSelectChanged" >
                                <option class="text-sm" :value="division" v-for="(division,divisionIndex) in divisionList" :key="divisionIndex">
                                    {{ division.name }}
                                </option>

                            </select>
                        </div>
                        <div class=" mb-4">
                            <label for="" class="text-sm text-black mb-2 block">
                                Township
                            </label>
                            <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                v-model="selectedTownship"
                                @change="townshipSelectChanged" >
                                <option class="text-sm" :value="township" v-for="(township,index) in townshipList" :key="index">
                                    {{ township.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Delivery Charge
                            </label>
                            <input type="text" placeholder="Delivery Charge" v-model="delivery_charge" class="input-ui">
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="createDeliveryChargeBtnClicked()"
                            class="add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete Modal -->
        <!-- <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none ">
                    <div
                        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 ">
                        <h5 class="text-xl font-medium leading-normal text-neutral-800 " id="exampleModalLabel">
                            Delete ?
                        </h5>
                        <button type="button"
                            class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative flex-auto p-4" data-te-modal-body-ref>
                        <p>
                            Are you sure ?
                        </p>
                    </div>
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
        </div> -->

        <!-- Edit Modal -->
        <!-- <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="editModal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                    class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative flex justify-between py-2 px-6 border-b">
                        <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                            id="create_modalLabel">
                            Edit 
                        </h5>
                        <button type="button" class="text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                            aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative px-6 py-4 border-b" data-te-modal-body-ref>
                        <div class=" mb-4">
                            <label for="" class="text-sm text-black mb-2 block">
                                Division
                            </label>
                            <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                v-model="selectedDivision"
                                @change="divisionSelectChanged" >
                                <option class="text-sm" :value="division" v-for="(division,divisionIndex) in divisionList" :key="divisionIndex">
                                    {{ division.name }}
                                </option>

                            </select>
                        </div>
                        <div class=" mb-4">
                                <label for="" class="text-sm text-black mb-2 block">
                                    Township
                                </label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedTownship"
                                    @change="townshipSelectChanged" >
                                    <option class="text-sm" :value="township" v-for="(township,index) in townshipList" :key="index">
                                        {{ township.name }}
                                    </option>

                                </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="label-form mb-3">
                                Delivery Charge
                            </label>
                            <input type="text" placeholder="Delivery Charge" v-model="delivery_charge" class="input-ui">
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                        <button type="button" class="cancel-btn focus:shadow-none focus:outline-none"
                            data-te-modal-dismiss aria-label="Close">
                            Cancel
                        </button>
                        <button type="button" @click="confirmEditBtnClicked"
                            class="add-btn focus:outline-none focus:ring-0 " data-te-modal-dismiss>
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div> -->
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
                deliveryChargeList:null,
                divisionList:null,
                selectedDivision:null,
                townshipList: null,
                selectedTownship:null,
                delivery_charge:500,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getDeliveryChargeList(){
                const response = await getApiData( { url: '/api/delivery_charges', token: this.getToken() } );
                if(response.data){
                    this.deliveryChargeList = response.data.data;
                }
            },
            addBtnClicked(){
                this.selectedDivision = null;
                this.selectedTownship = null;
                this.delivery_charge = null;
            },
            async getDivisionList(){
                const response = await getApiData( { url: '/api/divisions', token: this.getToken() } );
                if(response.data){
                    this.divisionList = response.data;
                }
            },
            divisionSelectChanged(){
                this.getTownShipList();
            },
            async getTownShipList(){
                this.townshipList = this.divisionList.find(x => x.id === this.selectedDivision.id).townships;
            },

            createDeliveryChargeBtnClicked(){
                this.createDeliveryCharge();
            },

            async createDeliveryCharge()
            {
                if(!this.delivery_charge){
                    this.alertValidationMessage('Delivery Charge');
                }
                
                else if(!this.selectedTownship){
                    this.alertValidationMessage('Township');
                }
                else{
                    let formData = new FormData();
                    formData.append('amount', this.delivery_charge);
                    formData.append('township_id', this.selectedTownship.id);
                    let response = await postApiData({url: '/api/delivery_charges', form_data: formData, token: this.getToken()});
                    if(response.success){
                        window.location.reload();
                    }
                    else{
                        alert('some errors occur');
                    }
                }
                
            },



            editDeliveryChargeBtnClicked(){
                this.editDeliveryCharge();
            },

            async editDeliveryCharge()
            {
                if(!this.delivery_charge){
                    this.alertValidationMessage('Delivery Charge');
                }
                
                else if(!this.selectedTownship){
                    this.alertValidationMessage('Township');
                }
                else{
                    let formData = new FormData();
                    formData.append('amount', this.delivery_charge);
                    formData.append('township_id', this.selectedTownship.id);
                    let response = await postApiData({url: '/api/delivery_charges', form_data: formData, token: this.getToken()});
                    if(response.success){
                        window.location.reload();
                    }
                    else{
                        alert('some errors occur');
                    }
                }
                
            },


            alertValidationMessage(field) {
                this.$notify({
                    title: 'Input validation',
                    text: `You forgot to provide ${field}, please try again`,
                    type: 'warn'
                });
            },

            

        },
        mounted()
        {
            this.getDeliveryChargeList();
            this.getDivisionList();
            initTE({ Modal,Select, Ripple });
        }
    }
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
