<template>
    <div>
        <p class=" text-lg font-semibold font-inter">
            Product Tree
        </p>
    </div>
    <div class="mt-4 bg-white">
        <div class="btn-container">
            <notifications position="top center" />

            <div class=" flex gap-x-4">
                <label for="search" class="search-input">
                    <input type="text" class="input-search" placeholder="Search">
                    <i class="fal fa-search"></i>
                </label>
                <div class="w-full !text-sm" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Room" v-model="selectedRoom" @change="roomchange(1)"
                    data-te-select-filter="true" class="input-ui w-full">
                        <option value="">All</option>
                        <option v-for="(room,index) in roomList" :key="index" :value="room"> {{ room.name }} </option>
                    </select>
                </div>
            </div>


            <div class="flex pr-0 gap-x-4">
                
                <a href="/ktv_product_tree/create"
                    class="add-btn whitespace-nowrap h-8 focus:outline-none focus:ring-0 ">
                    Add New
                </a>
            </div>
        </div>
        <div class="box-container-table">
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="primary-table">
                        <thead>
                            <tr>
                                <th scope="col" class="">
                                    #
                                </th>
                                <th scope="col" class="">
                                    Room
                                </th>
                                <th scope="col" class="">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- looping start -->
                            <div class="contents" v-for="(productTree, index) in productTreeList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        {{ perPage * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class=" font-medium ">
<<<<<<< HEAD
                                        {{ productTree.entity.name }}
=======
                                        {{productTree.entity.name}}
                                        <!-- {{ roomList.find(room=>room.id = productTree.entity_id)?.name }} -->
>>>>>>> dcc52e9d7134023ee3ceba3f9ede0b0c402d8006
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <a :href="'/ktv_product_tree/' + productTree.id + '/edit'">
                                            <i class="far fa-pen cursor-pointer mr-3"></i>
                                        </a>
                                        <i class="far fa-trash-alt cursor-pointer"
                                            @click="deleteqkr(qkr.id)"></i>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="flex justify-center">
                        <div v-if="totalData != 0" class=" bg-white  flex justify-center mt-5 py-3">
                            <button class="rounded px-6 py-1 border  hover:bg-slate-200" :disabled="currentPage === 1"
                                @click="getCookingPlaces(currentPage - 1)">«</button>
                            <button class=" text-sm px-5 border">
                                Page <span @dblclick="showInput">{{ currentPage }}</span> / <span class="text-gray-400">{{
                                    lastPage }}</span>
                            </button>
                            <button class=" rounded px-6  py-1 border  hover:bg-slate-200"
                                :disabled="currentPage === lastPage" @click="getCookingPlaces(currentPage + 1)">
                                »</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            productTreeList: [],
            roomList:[],

            searchInput:null,
            selectedDepartment:null,
            selectedRole:null,
            selectedDate:null,

            url:'/api/ktv/objective_trees?page=',
            url_search:'',
            url_department:'',
            url_role:'',

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        async getRoomList(){
            let response = await getApiData({url: `/api/ktv/entity_room`, token: this.getToken()});
            if(response.data){
                this.roomList = response.data;
            }
        },

        async getProductTree(pageNumber) {
            let url = this.url + pageNumber + this.url_search + this.url_department + this.url_role;
            let response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.productTreeList = response.data.data;

                this.lastPage = response.data.last_page;
                this.currentPage = pageNumber;
                this.perPage = response.data.per_page;
                this.totalData = response.data.total;
            }
        },





        async deleteProductTree(id) {
            let response = await deleteApiData({ url: `/api/cooking_places/` + id, token: this.getToken() });
            if (response.success) {
                this.getQKR(1);
            }
            else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getRoomList();
        this.getProductTree(1);
    }
}
</script>
