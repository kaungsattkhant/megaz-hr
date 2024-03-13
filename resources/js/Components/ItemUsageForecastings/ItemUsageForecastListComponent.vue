<template>
    <div class="flex justify-between mb-3">
        <div class=" flex">
            <label for="search" class="search-input">
                <input type="text" class="input-search" placeholder="Search">
                <i class="fal fa-search"></i>
            </label>
        </div>
        <div class="flex justify-end flex-col">
            <a href="/item_usage_forecasts/create" class="add-btn ">
                Add New
            </a>

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
                                Department
                            </th>
                            <th scope="col" class=" px-6 py-4 ">
                                Month
                            </th>

                            <th scope="col" class=" px-6 py-4 ">
                                Forecasting Amount
                            </th>
                            <th scope="col" class="px-6 py-4">

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <div class="contents" v-for="(itemForecast, itemForecastIndex) in itemForecastList" :key="itemForecastIndex">
                            <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <td class=" px-6 py-4 font-medium ">
                                    {{ ++itemForecastIndex }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    <!-- {{ itemForecast.name }} -->Department
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ itemForecast.month }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ itemForecast.amount }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <a :href="'/item_usage_forecasts/'+itemForecast.id+'/detail'" id="" class="pr-4">
                                        <i class="far fa-bars"></i>
                                    </a>

                                    <button data-te-toggle="modal" data-te-target="#deleteModal"
                                    id="edit-btn" class="" @click="deleteBtnClicked(itemForecast.id)">
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
        id="deleteModal"
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
                        Delete Item Usage Forecast
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
                    <button @click="confirmDeleteBtnClicked" type="button" data-te-toggle="modal" data-te-target="#deleteModal"
                    class="ml-1 inline-block rounded bg-red-600 px-6 pb-2 pt-2.5 text-xs  text-white   focus:outline-none focus:ring-0 ">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Modal, Ripple, initTE, Input, Tab, Select } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { convertToMonth } from "../../utilities/datetime-helpers";
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                itemForecastList: [],
                deleteId: null,
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            deleteBtnClicked(id){
                this.deleteId = id;
            },

            async confirmDeleteBtnClicked(){
                let url = `/api/item_usage_forecasts/${this.deleteId}`;
                let response = await deleteApiData({url: url, token: this.getToken()});
                if(response.success){
                    let index = this.itemForecastList.findIndex(itemForecast => itemForecast.id === this.deleteId);
                    this.itemForecastList.splice(index, 1);
                }
            },

            async getItemForecastsList(){
                let response = await getApiData({url: `/api/item_usage_forecasts`, token: this.getToken()});
                if(response.data){
                    this.itemForecastList = response.data.data;
                    this.itemForecastList.forEach((forecast)=>{
                        forecast.month = convertToMonth(forecast.date);
                        forecast.amount = 0;
                        forecast.quantity = 0;
                        forecast.forecast_items.forEach((forecastItem)=>{
                            forecast.amount += forecastItem.amount;
                            forecast.quantity += forecastItem.quantity;
                        });
                    });
                }
            },
        },

        created(){
            this.getItemForecastsList();
        },

        mounted(){
            initTE({ Modal, Select, Tab, Ripple });
        }
    }
</script>
