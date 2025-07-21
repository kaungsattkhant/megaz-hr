<template>
    <div class="mt-4 bg-white">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Selling Extras
                </p>
            </div>
            <div class="btn-container">
                <div class=" flex gap-x-4 ">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                </div>
                <div class="flex justify-end gap-x-4">

                </div>
            </div>
            <div class="box-container-table">
                <div class="overflow-x-auto">
                    <div class="table-container">
                        <table class="primary-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Item Code</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th scope="col" class="">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <div class="contents" v-for="(item, itemIndex) in itemList" :key="itemIndex"></div> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            tagList: [],
            selectedTag: null,
            itemList: [],
        }
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature']),

        alertValiationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        getExtraTaggedItems() {
            getApiData({ url: `/api/tags`, token: this.getToken() }).then((response) => {
                if (response.data) {
                    response.data.forEach(tag => {
                        if(tag.name == 'Extra'){
                            this.selectedTag = tag;
                            getApiData({url: `/api/items?tag_id=${this.selectedTag.id}`, token: this.getToken()}).then((response)=>{
                                if(response.data){
                                    this.itemList = response.data;
                                }
                            });
                        }
                    });
                }
            });
        },
    },

    created(){
        this.getExtraTaggedItems();
    },

    mounted(){
        initTE({ Modal, Ripple, Select, Dropdown });
    },
}
</script>
