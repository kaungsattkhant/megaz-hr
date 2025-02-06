<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Supplier Brands Pricing
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container"></div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>UOM</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(brand, index) in brandsList" :key="index">
                                <tr class="">
                                    <td class="">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ brand.brand.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="brand.item_price"> {{ brand.item_price.uom_price.toLocaleString() }} </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span v-if="brand.item_price"> {{ brand.item_price.uom.name }} </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button id="price-edit-btn" class="pr-2" data-te-toggle="modal"
                                        data-te-target="#priceUpdateModal" @click="brandBtnClicked(brand)" >
                                            <i class="fas fa-tag"></i>
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

    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="priceUpdateModal" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto mb-12 translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">

                <div class="relative flex justify-between py-2 px-6 border-b">
                    <h5 class="text-base text-center mt-2 font-semibold leading-normal font-inter"
                        id="create_modalLabel">
                        Update Item Price
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

                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Price
                        </label>
                        <input type="number" placeholder="Price" v-model="price"
                        class="input-ui">
                    </div>
                    <div class="mb-4">
                        <label for="" class="label-form mb-3">
                            Uom
                        </label>
                        <select name="" id="" v-model="selectedUom" class="input-ui">
                            <option :value="uom" v-for="(uom, index) in uomList" :key="index"> {{ uom.uom_name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 px-6 mb-6 pt-4">
                    <button type="button" class="cancel-btn focus:shadow-none focus:outline-none" data-te-modal-dismiss
                        aria-label="Close">
                        Cancel
                    </button>
                    <button type="button" class="add-btn focus:outline-none focus:ring-0 "
                        @click="confirmUpdatePriceBtnClicked">
                        Update Price
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->
<button disabled hidden type="button"
    data-te-toggle="modal"
    data-te-target="#exampleModal">
    Launch demo modal
</button>

    <!-- Modal -->
<div
  data-te-modal-init
  class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
  id="exampleModal"
  tabindex="-1"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div
    data-te-modal-dialog-ref
    class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
    <div
      class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none dark:bg-surface-dark">
      <div
        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4 dark:border-white/10">
        <h5
          class="text-xl font-medium leading-normal text-surface dark:text-white"
          id="exampleModalLabel">
          Modal title
        </h5>
        <button
          type="button"
          class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-neutral-300"
          data-te-modal-dismiss
          aria-label="Close">
          <span class="[&>svg]:h-6 [&>svg]:w-6">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="currentColor"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12" />
            </svg>
          </span>
        </button>
      </div>

      <!-- Modal body -->
      <div class="relative flex-auto p-4" data-te-modal-body-ref>
        Modal body text goes here.
      </div>

      <!-- Modal footer -->
      <div
        class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 p-4 dark:border-white/10">
        <button
          type="button"
          class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-primary-700 transition duration-150 ease-in-out hover:bg-primary-accent-200 focus:bg-primary-accent-200 focus:outline-none focus:ring-0 active:bg-primary-accent-200 dark:bg-primary-300 dark:hover:bg-primary-400 dark:focus:bg-primary-400 dark:active:bg-primary-400"
          data-te-modal-dismiss
          data-te-ripple-init
          data-te-ripple-color="light">
          Close
        </button>
        <button
          type="button"
          class="ms-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
          data-te-ripple-init
          data-te-ripple-color="light">
          Save changes
        </button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import { Modal, Ripple, initTE, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { convertToFriendlyDateTime } from "../../utilities/datetime-helpers";
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    props: ["supplierId","itemId"],
    data() {
        return {
            brandsList: [],

            uomList:[],
            selectedUom:null,
            baseUomId: null,
            price: null,
            supplierItemId: null,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        async getSupplierBrands(pageNumber){
            if(pageNumber){
                this.currentPage = pageNumber;
            }
            let url = `/api/brand_by_supplier?item_id=${this.itemId}&supplier_id=${this.supplierId}`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.success){
                this.brandsList = response.data;
            }
        },

        brandBtnClicked(brand){
            this.selectedUom = null;
            this.uomList = [];
            this.price = null;
            this.supplierItemId = brand.id;
            this.baseUomId = brand.item.base_uom_id;
            this.uomList.push(
                {
                    base_uom_id : brand.item.base_uom_id,
                    uom_name : brand.item.base_uom_name,
                    uom_conversion : brand.item.uom_conversion
                },
                {
                    uom_id : brand.item.uom_id,
                    uom_name : brand.item.item_uom,
                    uom_conversion : brand.item.uom_conversion
                }
            )
        },

        async confirmUpdatePriceBtnClicked(){
            if(!(this.price > 0)){
                this.alertValiationMessage(`Price`);
                return;
            }
            if(!this.selectedUom){
                this.alertValiationMessage(`Uom`);
                return;
            }
            let formData = new FormData();
            formData.append('supplier_item_id', this.supplierItemId);
            formData.append('uom_price', this.price);
            formData.append('base_uom_id', this.baseUomId);
            if(this.selectedUom.base_uom_id){
                formData.append('type', 'base_uom');
                formData.append('uom_id', this.selectedUom.base_uom_id);
            }
            if(this.selectedUom.uom_id){
                formData.append('type', 'uom');
                formData.append('uom_id', this.selectedUom.uom_id);
            }
            formData.append('uom_conversion', this.selectedUom.uom_conversion);
            let url = `/api/add_item_price_by_supplier_item`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.$notify({
                    text: `Item price set for supplier successfully`,
                    type: "info"
                });
                document.getElementById('priceUpdateModal').click();
                this.price = null;
                this.supplierItemId = null;
                this.baseUomId = null;
                this.selectedUom = null;
                this.getSupplierBrands();
            }
        },
    },

    created() {
        this.getSupplierBrands();
    },

    mounted() {
        initTE({ Modal, Ripple, Select, Dropdown });
    }
}
</script>
