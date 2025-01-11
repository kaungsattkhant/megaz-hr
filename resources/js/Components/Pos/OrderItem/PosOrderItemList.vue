<template>
    <div>
        <notifications position="top center" />
        <div class="">
            <div class="w-full pt-9 px-6">
                <div class="flex justify-between mb-4">
                    <div class="flex gap-x-3">
                        <button class="pos-add-btn">Date</button>
                    </div>
                    <div class="flex gap-x-3">
                        <a href="/food_orders/create" class="pos-add-btn">
                            Add
                        </a>
                    </div>
                </div>
                <div>
                    <div class="bg-white px-4 pb-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Id</th>
                                    <th scope="col" class="px-6 py-4">
                                        Date & Time
                                    </th>
                                    <th scope="col" class="px-6 py-4">Menu</th>
                                    <th scope="col" class="px-6 py-4">
                                        Status
                                    </th>

                                    <th scope="col" class="px-6 py-4">Total</th>
                                    <th scope="col" class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <div
                                    class="contents"
                                    v-for="(
                                        foodOrder, index
                                    ) in PosOrderItemList"
                                    :key="index"
                                >
                                    <tr class="">
                                        <td
                                            class="whitespace-nowrap px-6 pt-4 pb-3 font-medium"
                                        >
                                            {{ index + 1 }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 pt-4 pb-3 font-medium"
                                        >
                                            {{ foodOrder.date }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 pt-4 pb-3"
                                        >
                                            {{ foodOrder.menu.name }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 pt-4 pb-3"
                                        >
                                            {{ foodOrder.status }}
                                        </td>

                                        <td
                                            colspan="3"
                                            class="whitespace-nowrap px-6 py-3 select-parent"
                                        >
                                            <select
                                                name=""
                                                class="text-xs pl-0 border-0 focus:shadow-none focus:outline-none focus:ring-0 select-box area-select-box"
                                                placeholder="Select Area"
                                            >
                                                <option disabled selected>
                                                    Select Area
                                                </option>
                                                <option
                                                    v-for="(
                                                        area, index
                                                    ) in foodOrder.menu.areas"
                                                    :key="index"
                                                    :value="area.id"
                                                    class=""
                                                >
                                                    {{ area.name }}
                                                </option>
                                            </select>
                                        </td>

                                        <!-- <td class="whitespace-nowrap px-6 pt-4 pb-3">
                                           <div v-if="foodOrder.food_order_items.length > 0" class="flex">
                                                <div v-for="(foi,index) in foodOrder.food_order_items">
                                                    {{ foi.menu.name }} {{ index < foodOrder.food_order_items.length - 1  ? ', ' : '' }}
                                                </div>
                                            </div>
                                            <div v-else>
                                                 All items are Rejected
                                            </div>
                                        </td> -->

                                        <td
                                            class="whitespace-nowrap px-6 pt-4 pb-3"
                                        >
                                            {{ foodOrder.price }} MMks
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-3 text-center button-parent"
                                        >
                                            <button
                                                @click="
                                                    btnClickedAcceptMenu(
                                                        $event,
                                                        foodOrder.id
                                                    )
                                                "
                                            >
                                                <i
                                                    class="fal fa-check text-sm mr-4"
                                                ></i>
                                            </button>
                                            <button
                                                @click="
                                                    btnClickedRejectMenu(
                                                        $event,
                                                        foodOrder.id
                                                    )
                                                "
                                            >
                                                <i
                                                    class="fal fa-times text-sm"
                                                ></i>
                                            </button>
                                        </td>
                                    </tr>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div
            data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="confirm_modal"
            tabindex="-1"
            aria-labelledby="confirmModalLabel"
            aria-modal="true"
            role="dialog"
        >
            <div
                data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-fit translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]"
            >
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none"
                >
                    <button
                        type="button"
                        class="absolute -top-2 -right-2 rounded-full bg-white border-black border p-1 text-xs focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss
                        aria-label="Close"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                    <div class="pt-8 pb-4 px-8">
                        <p class="text-lg text-center font-semibold">
                            Confirm {{ selectedRoom }} ?
                        </p>
                    </div>
                    <div class="flex justify-center px-24 py-6 gap-x-8 mb-2">
                        <button
                            data-te-modal-dismiss=""
                            type="button"
                            class="focus:shadow-none focus:outline-none !px-16 !py-3 hover:text-red-500 duration-500"
                            @click="confirmFoodOrder('0')"
                        >
                            Reject
                        </button>
                        <button
                            data-te-modal-dismiss
                            @click="confirmFoodOrder('1')"
                            class="pos-add-btn !px-16 !py-3 focus:outline-none focus:ring-0"
                        >
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
import {
    getApiData,
    postApiData,
    deleteApiData,
} from "../../../utilities/ajax-helpers";
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            PosOrderItemList: [],
            selectedAreaId: "",
            selectedArea: null,
            selectedFoodOrderId: null,
            area_value: "",
        };
    },

    methods: {
        ...mapGetters(["getToken"]),

        async getPosOrderItemList() {
            const response = await getApiData({
                url: "/api/pos_order_items",
                token: this.getToken(),
            });
            if (response.data) {
                this.PosOrderItemList = response.data;
                // this.getOrderStatus();
            }
        },
        btnClickedAcceptMenu(event, id) {
            const button = $(event.target);
            const row = button.closest("tr");
            const selectElement = row.find(".select-box");
            const selectedValue = selectElement.val();
            this.selectedAreaId = selectedValue;
            if (!this.selectedAreaId) {
                this.$notify({
                    title: `Not valid`,
                    text: "Select Area First Please",
                    type: "warn",
                });
            } else {
                this.acceptMenu(id);
            }
        },
        async acceptMenu(id) {
            let formData = new FormData();
            formData.append("status", "pos_confirmed");
            formData.append("area_id", this.selectedAreaId);
            if (this.selectedAreaId == null) {
                this.$notify({
                    title: `Not valid`,
                    text: "Select Area First Please",
                    type: "warn",
                });
                return;
            }
            let response = await postApiData({
                url: "/api/pos_order_items/" + id + "/status",
                form_data: formData,
                token: this.getToken(),
            });
            if (response.success) {
                console.log("ok");
                this.selectedAreaId = null;
                this.area_value = null;
                this.getPosOrderItemList();

                this.$notify({
                    title: `Order Item`,
                    text: "Order Item successfully confirmed",
                    type: "info",
                });
            }
        },

        btnClickedRejectMenu(event, id) {
            const button = $(event.target);
            const row = button.closest("tr");
            const selectElement = row.find(".select-box");
            const selectedValue = selectElement.val();
            this.selectedAreaId = selectedValue;
            if (!this.selectedAreaId) {
                this.$notify({
                    title: `Not valid`,
                    text: "Select Area First Please",
                    type: "warn",
                });
            }
            this.rejectMenu(id);
        },

        async rejectMenu(id) {
            let formData = new FormData();
            formData.append("status", "rejected");
            formData.append("area_id", this.selectedAreaId);
            if (this.selectedAreaId == null) {
                this.$notify({
                    title: `Not valid`,
                    text: "Select Area First Please",
                    type: "warn",
                });
                return;
            }
            let response = await postApiData({
                url: "/api/pos_order_items/" + id + "/status",
                form_data: formData,
                token: this.getToken(),
            });
            if (response.success) {
                console.log("ok");
                this.selectedAreaId = null;
                this.getPosOrderItemList();
                this.area_value = null;

                this.$notify({
                    title: `Order Item`,
                    text: "Order Item rejected",
                    type: "warn",
                });
            }
        },

        // for pending or confirm buttoncl
        // getOrderStatus() {
        //     this.PosOrderItemList.forEach((fo) => {
        //         const allConfirmed = fo.food_order_items.every(item => item.status === 'confirmed');
        //         if (allConfirmed) {
        //             fo.allItemStatus = 'confirmed';
        //         }
        //         else {
        //             fo.allItemStatus = 'pending';
        //         }
        //     });
        // },
        btnClickedGetOrderId(orderId) {
            this.selectedFoodOrderId = orderId;
        },
        // async confirmFoodOrder(is_confirm_order) {
        //     let formData = new FormData();
        //     if (is_confirm_order == 1) {
        //         formData.append('status', 'pos_confirmed');
        //     } else {
        //         formData.append('status', 'rejected');
        //     }

        //     let response = await postApiData({ url: `/api/pos_order_items/${this.selectedFoodOrderId}/status`, form_data: formData, token: this.getToken() });
        //     if (response.success) {

        //         this.selectedFoodOrderId = null,
        //             this.getPosOrderItemList()
        //     }
        //     else {
        //         console.log(response.error)
        //     }
        // }
    },
    mounted() {
        initTE({ Modal, Select, Ripple, Datepicker });
    },

    created() {
        this.getPosOrderItemList();
    },
};
</script>
