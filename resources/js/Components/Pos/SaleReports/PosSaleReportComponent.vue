<template>
    <div>
        <div class="">
            <div class="w-full pt-9 px-6">
                <div class="flex justify-between mb-4">
                    <div class="flex gap-x-3">
                        <div class=" flex gap-x-4">
                            <input type="date" class="h-8 mr-2 rounded-md" v-model="selectedDate" @change="dateChanged()">
                            <!-- <input type="date" class="h-8 mx-2 rounded-md" v-model="toDate" @change="dateChanged()"> -->
                        </div>
                        
                    </div>
                    <div class="flex gap-x-3">
                        <div class="w-full !text-sm bg-white h-fit" data-te-select-wrapper-ref>
                            <select data-te-select-init data-te-select-placeholder="Select Area" @change="areaChanged"
                                data-te-select-filter="true" name="" id="" v-model="selectedArea" class="input-ui">
                                <option :value="area" v-for="area in areaList"> {{ area.name }} </option>
                            </select>
                        </div>
                        <!-- <button class="pos-add-btn" data-te-toggle="modal" data-te-target="#create_cashbook_modal">

                            Add

                        </button> -->
                    </div>
                </div>

                <div>
                    <div class="bg-white px-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Id</th>
                                    <th scope="col" class="px-6 py-4">Menu Name</th>
                                    <th scope="col" class="px-6 py-4">Count</th>
                                    <th scope="col" class="px-6 py-4">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(item,index) in primaryList">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ index+1 }}
                                    </td>
                                    
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ item.menu.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ item.total_quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ (item.total_amount).toLocaleString() }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td colspan="2"></td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ total_quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ total_amount.toLocaleString() }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- pagination -->
                        <div class="flex justify-center">
                            <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                                <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                    @click="getPrimaryList(currentPage - 1)">«</button>
                                <button class=" text-sm px-5 border">
                                    Page <span @dblclick="showInput">{{ currentPage }}</span> / <span
                                        class="text-gray-400">{{
                                        lastPage }}</span>
                                </button>
                                <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                    :disabled="currentPage === lastPage" @click="getPrimaryList(currentPage + 1)">
                                    »</button>
                            </div>
                        </div>
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
                primaryList:[],
                areaList: [],

                selectedDate: null,
                selectedArea: null,

                total_quantity: 0,
                total_amount: 0,

                currentPage: 0,
                perPage: 0,
                lastPage: 0,
                totalData: 0,
                
                url_date: '',
                url_area: '',
                url: '/api/sale-reports'
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getPrimaryList(pageNumber){
                this.total_quantity = 0;
                this.total_amount = 0;
                let url = this.url + '?page=' + pageNumber + this.url_date + this.url_area;
                const response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    if(response.data.data){
                        this.primaryList = response.data.data;
                        this.primaryList.forEach(item => {
                            this.total_quantity += item.total_quantity;
                            this.total_amount += item.total_amount;
                        });
                        this.currentPage = response.data.current_page;
                        this.perPage = response.data.per_page;
                        this.lastPage = response.data.last_page;
                        this.totalData = response.data.total;
                    }
                    else{
                        this.primaryList = response.data;
                        this.primaryList.forEach(item => {
                            this.total_quantity += item.total_quantity;
                            this.total_amount += item.total_amount;
                        });
                        console.log('Total Quantity =', this.total_quantity);
                    }
                }
            },
            dateChanged(){
                this.selectedArea = null;
                this.url_area = '';
                if(this.selectedDate){
                    this.url_date = '&date=' + this.selectedDate;
                    this.getPrimaryList(1);
                }
                
            },
            areaChanged(){
                // if(this.selectedDate){
                //     this.url_area = '&area_id=' + this.selectedArea.id;
                // }
                // else{
                //     this.url_area = '?area_id=' + this.selectedArea.id;
                // }
                this.url_area = '&area_id=' + this.selectedArea.id;
                this.getPrimaryList(1);
            },
            

            async getAreaList() {
                let url = `/api/sellings_areas`;
                let response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.areaList = response.data;
                }
            },

            
            // async createBtnClicked() {
            //     let formData = new FormData();
            //     formData.append('description', this.description);
            //     formData.append('account_id', this.selectedAccount);
            //     formData.append('value', this.amount);
            //     formData.append('cash_account_id', this.accType.id);
            //     formData.append('action', this.action);
            //     formData.append('is_confirmed', 1);

            //     let url = `/api/transactions`;
            //     let response = await postApiData({ url: url, form_data: formData, token: this.getToken() });
            //     if (response.success) {
            //         window.location.reload();
            //     }
            // },

            
        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        },

        created(){
            this.getPrimaryList(1);
            this.getAreaList();
        }
    }
</script>
