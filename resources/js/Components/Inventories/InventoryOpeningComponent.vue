<template>
    
    <div class="margin-bg">
        <div class="card-shadow pb-4">
            <div>
                <p class=" page-title">
                    Inventory Stocks
                </p>
            </div>
            
        </div>
        <div class="box-container-table">
            <div class="btn-container pt-10">
            
                <div class=" flex pr-0 gap-x-4">
                    
                    <div class="relative">
                        <label for="search" class="border border-gray-200 rounded bg-white text-xs mx-2 px-2 py-2 absolute left-0 ml-0 -top-[90%] border-b-0"> Date </label>
                        <input type="date" v-model="selectedDate" class="search-input rounded">
                    </div>

                </div>
                <div>
                    <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Inventory"
                            data-te-select-filter="true" name="" id="" v-model="selectedInventory" class="input-ui">
                            <option :value="inventory" v-for="(inventory, inventoryIndex) in inventoryList"
                                :key="inventoryIndex"> {{ inventory.name }} </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">

                <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col">
                                    #
                                </th>
                                <th scope="col">
                                    Item 
                                </th>
                                <th scope="col" class="text-center">
                                    Base UOM Quantity
                                </th>
                                <th scope="col" class="text-center">
                                    UOM Quantity
                                </th>
                                
                                <th scope="col">

                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody v-else>
                            <div class="contents" v-for="(item, index) in visibleItems" :key="index">
                                <tr>
                                    <td>
                                        <!-- {{ perPage ? (currentPage - 1) * perPage + index + 1 : index+1 }} -->
                                        {{ index+1 }}
                                    </td>
                                    <td>
                                        {{ item.name }}
                                    </td>
                                    <td>
                                        <div class="flex gap-x-4 items-center justify-center">
                                            <input type="text" class="input-ui max-w-xs" v-model="item.base_uom_quantity">
                                            <span>
                                                {{ item.base_uom.name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex gap-x-4 items-center justify-center">
                                            <input type="text" class="input-ui max-w-xs" v-model="item.uom_quantity">
                                            <span>
                                                {{ item.uom.name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                       
                                    </td>
                                </tr>
                                
                            </div>

                            <tr class=" !text-center" v-if="primaryList.length < 1 && !loading">
                                <td class="" colspan="10">
                                    No Data Here
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="w-full text-center">
                        <button
                            v-if="limit < primaryList.length"
                            @click="loadMore"
                            class="mt-4 px-4 py-2 text-sm underline underline-offset-2"
                        >
                            More
                        </button>
                    </div>
                    <!-- pagination -->
                    <!-- <div class="flex justify-center">

                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === 1" @click="getInventoryLegderList(currentPage - 1)">«</button>

                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                    class="text-gray-400">{{
                                        lastPage }}</span>
                            </button>

                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getInventoryLegderList(currentPage + 1)"> »</button>
                        </div>
                    </div> -->
                </div>




            </div>

            <div class="text-right px-8">
                <button class=" add-btn" @click="btnClickedCreateInventoryOpening()">
                    Create
                </button>
                <LoadingButton
                    :loading="buttonLoading"
                    text="Createasdf"
                    loadingText="Creating..."
                    @onClick="btnClickedCreateInventoryOpening()"
                />
            </div>


            
        </div>
        <div>

        </div>
    </div>
    

    

</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import 'tw-elements';
import TableSkeleton from "../Common/TableSkeleton.vue";
import LoadingButton from "../Common/LoadingButton.vue";

export default {
    components: {
        TableSkeleton,
        LoadingButton
    },
    data() {
        return {
            primaryList: [],
            inventoryList: [],

            selectedInventory: null,
            selectedDate: null,
            selectedData: {
                date: null,
                items: [],
            },
            selectedList: [],

            limit: 20,
            
            loading: true,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });

        },
        async getData() {
            this.loading = true;    
            const response = await getApiData({ url: '/api/get_items', token: this.getToken() });
            if (response.data) {
                this.primaryList = response.data;
                this.loading = false;
            }
        },
        async getInventoryList() {
            const response = await getApiData({ url: '/api/get_inventory', token: this.getToken() });
            if (response.data) {
                this.inventoryList = response.data;
            }
        },
        loadMore() {
            this.limit += 20
        },
        btnClickedCreateInventoryOpening(){
            if(!this.selectedDate){
                this.alertValidationMessage(`Date`);
                return 1;
            }
            if(!this.selectedInventory){
                this.alertValidationMessage(`Inventory`);
                return 1;
            }
            this.selectedData.date = this.selectedDate
            this.primaryList.forEach(inventory => {
                if(inventory.base_uom_quantity && inventory.uom_quantity){
                    this.selectedData.items.push({
                        item_id: inventory.id,
                        inventory_id: this.selectedInventory.id,
                        base_uom_id: inventory.base_uom_id,
                        uom_id: inventory.uom_id,
                        base_uom_quantity: inventory.base_uom_quantity,
                        uom_quantity: inventory.uom_quantity
                    })
                }
            });
            this.createInventoryOpening();
        },
        async createInventoryOpening(){

            this.buttonLoading = true;
            console.log('items type:', Array.isArray(this.selectedData.items));
            console.log('items type with json:', Array.isArray(JSON.stringify(this.selectedData.items)));
            let formData = new FormData();
            formData.append('date', this.selectedData.date);

            // this.selectedData.items.forEach((item,index)=>{
            //     formData.append(`items[${index}]`, item);
            // });

            // this.selectedData.items.forEach((item, index) => {
            //     formData.append(`items[${index}][item_id]`, item.item_id);
            //     formData.append(`items[${index}][inventory_id]`, item.inventory_id);
            //     formData.append(`items[${index}][base_uom_id]`, item.base_uom_id);
            //     formData.append(`items[${index}][uom_id]`, item.uom_id);
            //     formData.append(`items[${index}][base_uom_quantity]`, item.base_uom_quantity);
            //     formData.append(`items[${index}][uom_quantity]`, item.uom_quantity);
            // });


            formData.append('items', JSON.stringify(this.selectedData.items));
            let url = `/api/create_inventory_opening`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                this.$notify({
                    text: `Success`,
                    type: 'info'
                });
                this.getInventoryLegderList(1);
                this.buttonLoading = false

            }
            else{
                this.buttonLoading = false
                this.$notify({
                    text: response.message,
                    type: 'info'
                });
            }
        },
        
        
        
    },
    computed: {
        visibleItems() {
            return this.primaryList.slice(0, this.limit)
        }
    },

    created() {
        this.getInventoryList();
        this.getData();
    },

    mounted() {
        initTE({ Modal, Select, Ripple });
    }
}
</script>
