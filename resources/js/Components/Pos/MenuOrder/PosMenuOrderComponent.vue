<template>
    <div>
        <notifications position="top center" />
        <div class="">
            <div class="w-full pt-9 px-6">
                <div class="flex justify-between mb-4">
                    <div class="flex gap-x-3">
                        <button class="pos-add-btn">
                            Date
                        </button>
                    </div>
                    <div class="flex gap-x-3">
                        <button class="pos-add-btn" data-te-toggle="modal" data-te-target="#create_cashbook_modal">
                            Add
                            </button>
                    </div>
                </div>

                <div>
                    <div class="bg-white px-4 pb-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Id</th>
                                    <th scope="col" class="px-6 py-4">Date & Time</th>
                                    <th scope="col" class="px-6 py-4">Customer Name</th>
                                    <th scope="col" class="px-6 py-4">Menu</th>
                                    <th scope="col" class="px-6 py-4">Address</th>
                                    <th scope="col" class="px-6 py-4">Ph Number</th>
                                    <th scope="col" class="px-6 py-4">Total</th>
                                    <th scope="col" class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr class="">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        1
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        Jul 15 3 PM
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        Khant
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        Fried Chicken Rice            
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        22nd 87 Corner Mandalay
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        0920123454
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        15,000 MMks
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <button type="button" class="bg-green-600 px-6 py-2 text-white rounded-full"> 
                                            Confirm
                                        </button>
                                    </td>
                                </tr> -->
                                <div class="contents" v-for="(foodOrder,index) in foodOrderlist">
                                    <tr class="">
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3 font-medium">
                                            {{ index+1 }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            {{ foodOrder.date_time }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            {{ foodOrder.customer.name }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            <!-- <div v-if="foodOrder.food_order_items">
                                                <div v-if="foodOrder.food_order_items < 2">
                                                    {{ foodOrder.food_order_items[0].name }}
                                                </div> 
                                                <div v-else>
                                                    Food Menu
                                                </div>
                                            </div> -->
                                            <div v-if="foodOrder.food_order_items.length > 0" class="flex">
                                                <div v-for="(foi,index) in foodOrder.food_order_items">
                                                    {{ foi.menu.name }} {{ index < foodOrder.food_order_items.length - 1  ? ', ' : '' }}
                                                </div>
                                            </div>
                                            <div v-else>
                                                 All items are Rejected
                                            </div>
                                            
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            {{ foodOrder.customer.addresses[0].address }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            {{ foodOrder.customer.phone_number }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                            {{ foodOrder.total_price.toLocaleString() }} MMks
                                        </td>
                                        <td class="whitespace-nowrap px-6  text-center">
                                            <button v-if="foodOrder.allItemStatus == 'pending'" type="button" class="bg-[#fb923c] px-6 py-2 text-white rounded-full"> 
                                                Pending
                                            </button>
                                            <button v-else type="button" @click="btnClickedGetOrderId(foodOrder.id)" class="bg-green-600 px-6 py-2 text-white rounded-full"
                                                data-te-toggle="modal" data-te-target="#confirm_modal"> 
                                                Confirm
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="contents" v-if="foodOrder.food_order_items.length > 0">
                                        <tr class="" v-for="(menuOrder,index) in foodOrder.food_order_items">
                                            <td colspan="3" class="whitespace-nowrap px-6 py-3 font-medium">
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-3">
                                                {{ menuOrder.menu.name }}         
                                            </td>
                                            <td colspan="3" class="whitespace-nowrap px-6 py-3 select-parent">
                                                <select name="" id="" class="text-xs pl-0 border-0 focus:shadow-none focus:outline-none focus:ring-0 select-box"
                                                    placeholder="Select Area">
                                                    <option :selected="!menuOrder.area_id" disabled selected>Select Area</option>
                                                    <option v-for="(area,index) in menuOrder.menu.areas" :value="area.id" class="">
                                                        {{ area.name }}
                                                    </option>
                                                </select>       
                                            </td>
                                            <!-- <td colspan="2" class="whitespace-nowrap px-6 py-3"></td> -->
                                            <td class="whitespace-nowrap px-6 py-3 text-center button-parent" v-if="menuOrder.status == 'received'">
                                                <button @click="btnClickedAcceptMenu($event,menuOrder.id)">
                                                    <i class="fal fa-check text-sm mr-2"></i>
                                                </button>
                                                <button @click="btnClickedRejectMenu($event,menuOrder.id)">
                                                    <i class="fal fa-times text-sm"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">&nbsp;</td> <!-- for btn space -->
                                        </tr>
                                    </div>
                                </div>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="confirm_modal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-fit translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                    
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <button type="button"
                        class="absolute -top-2 -right-2 rounded-full bg-white border-black border p-1 text-xs focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="pt-8 pb-4 px-8">
                        <p class="text-lg text-center font-semibold">
                            Confirm {{ selectedRoom }} ?
                        </p>
                    </div>
                    <div class="flex justify-center px-24 py-6 gap-x-8 mb-2">
                        <button type="button" class="focus:shadow-none focus:outline-none !px-16 !py-3"
                            @click="confirmFoodOrder('0')">
                            Cancel
                        </button>
                        <button @click="confirmFoodOrder('1')" class="pos-add-btn !px-16 !py-3 focus:outline-none focus:ring-0 ">
                            Confirm
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
                foodOrderlist:[],
                selectedAreaId: '',
                selectedArea:null,
                selectedFoodOrderId:null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getFoodOrderList() {
                const response = await getApiData({ url: '/api/food_orders', token: this.getToken() });
                if (response.data) {
                    this.foodOrderlist = response.data;
                    this.getOrderStatus();
                }
            },
            btnClickedAcceptMenu(event,id) {
                const button = $(event.target);
                const buttonParent = button.closest('.button-parent');
                const selectParent = buttonParent.prev('.select-parent');
                const selectElement = selectParent.find('.select-box');
                this.selectedAreaId = selectElement.val();
                console.log('Selected Value:', this.selectedAreaId);
                console.log('area = ' , id)
                if(!this.selectedAreaId){
                    this.$notify({
                        title: `Not valid`,
                        text: 'Select Area First Please',
                        type: "warn"
                    });
                }
                else{
                    this.acceptMenu(id);
                }
                
            },
            async acceptMenu(id){
                let formData = new FormData();
                formData.append('is_confirm', 1);
                formData.append('area_id', this.selectedAreaId);
                let response = await postApiData({ url: '/api/food_order_items/' + id, form_data: formData, token: this.getToken() });
                if (response.success) {
                    console.log('ok')
                    this.selectedAreaId = null
                    this.getFoodOrderList()
                }
            },

            btnClickedRejectMenu(event,id) {
                const button = $(event.target);
                const buttonParent = button.closest('.button-parent');
                const selectParent = buttonParent.prev('.select-parent');
                const selectElement = selectParent.find('.select-box');
                this.selectedAreaId = selectElement.val();
                
                this.rejectMenu(id);
            },
            async rejectMenu(id){
                let formData = new FormData();
                formData.append('is_confirm', 0);
                formData.append('area_id', this.selectedAreaId);
                let response = await postApiData({ url: '/api/food_order_items/' + id, form_data: formData, token: this.getToken() });
                if (response.success) {
                    console.log('ok')
                    this.selectedAreaId = null
                    this.getFoodOrderList()
                }
            },

            // for pending or confirm button
            getOrderStatus() {
                this.foodOrderlist.forEach((fo)=>{
                    const allConfirmed = fo.food_order_items.every(item => item.status === 'confirmed');
                    if(allConfirmed){
                        fo.allItemStatus = 'confirmed';
                    }
                    else{
                        fo.allItemStatus = 'pending';
                    }
                });
            },
            btnClickedGetOrderId(orderId){
                this.selectedFoodOrderId = orderId;
            },
            async confirmFoodOrder(is_confirm_order){
                let formData = new FormData();
                formData.append('is_confirm', is_confirm_order);
                let response = await postApiData({ url: '/api/food_orders/' + this.selectedFoodOrderId, form_data: formData, token: this.getToken() });
                if (response.success) {
                    console.log('confirm ok')
                    this.selectedFoodOrderId = null,
                    this.getFoodOrderList()
                }
                else{
                    console.log(response.error)
                }
            }

        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        },

        created(){
            this.getFoodOrderList();
        }
    }
</script>
