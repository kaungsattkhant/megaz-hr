<template>

    <div class="margin-bg">
        <div class="card-shadow">
            <div>
                <p class=" page-title">
                    Day Off
                </p>
            </div>
            <div class="btn-container">
                <notifications position="top center" />
                <div class=" flex gap-x-4">
                    <label for="search" class="search-input">
                        <input type="text" class="input-search" placeholder="Search" v-model="searchInput">
                        <i class="fal fa-search"></i>
                    </label>
                    <button class="add-btn h-8" @click="searchBtnClicked()">Search</button>
                    <button class="add-btn h-8" @click="clearSearchBtnClicked()">Clear</button>
                </div>
                <div class="flex pr-0 gap-x-4">
                    <!-- <div class="w-full !text-sm" data-te-select-wrapper-ref>
                        <select data-te-select-init data-te-select-placeholder="Select Type" @change="selectedTypeChanged()"
                            data-te-select-filter="true" name="" id="" v-model="selectedType" class="input-ui">
                            <option :value="type.value" v-for="(type, typeIndex) in typeList"
                                :key="typeIndex"> {{ type.name }} </option>
                        </select>
                    </div> -->
                    <!-- <button type="button" v-show="feature.includes('off-day.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_holiday_modal"
                        @click="addHolidayModalBtnClicked">
                        Add Holiday
                    </button>
                    <button type="button" v-show="feature.includes('off-day.create')"
                        class="add-btn transition duration-150 ease-in-out focus:outline-none focus:ring-0 "
                        data-te-toggle="modal" data-te-target="#create_modal" @click="addBtnClicked">
                        Add Off Day
                    </button> -->
                </div>
            </div>
        </div>
        <!-- {{ offDayList }}
        <br>
        {{ offDaySetting }} -->
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
                                    Type
                                </th>
                                <th scope="col" class="">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <TableSkeleton
                        v-if="loading"
                        :rows="20"
                        :cols="6"
                        />
                        <tbody>
                            <div class="contents" v-for="(offDay, index) in offDayList" :key="index">
                                <tr class="">
                                    <td class=" font-medium ">
                                        <!-- {{ perPage * (currentPage - 1) + (++index) }} -->
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ offDay.type }}
                                    </td>
                                    
                                    <td class="whitespace-nowrap">
                                        <input :checked="offDay.type == 'custom'" @change="isActiveToggled(offDay.id)"
                                            class="me-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-black/25 before:pointer-events-none before:absolute before:h-3.5
                                            before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5
                                            after:w-5 after:rounded-full after:border-none after:bg-white after:shadow-switch-2 after:transition-[background-color_0.2s,transform_0.2s]
                                            after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ms-[1.0625rem]
                                            checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-switch-1
                                            checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:before:scale-100
                                            focus:before:opacity-[0.12] focus:before:shadow-switch-3 focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s]
                                            focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-['']
                                            checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ms-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-switch-3
                                            checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-white/25 dark:after:bg-surface-dark dark:checked:bg-primary dark:checked:after:bg-primary"
                                            type="checkbox" role="switch" />
                                    </td>
                                </tr>
                            </div>
                            <tr class=" !text-center" v-if="offDayList.length < 1 && !loading">
                                <td class="" colspan="5">
                                    No Data Here
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



</template>

<script>
import Multiselect from 'vue-multiselect';
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters, mapMutations } from "vuex";
import TableSkeleton from "../Common/TableSkeleton.vue";

export default {
    components: {
        Multiselect,
        TableSkeleton
    },
    data() {
        return {
            offDayList: [],
            

            currentPage: 0,
            perPage: 0,
            lastPage: 0,
            totalData: 0,

            searchInput: null,



            url: '/api/hr/off_day_settings',
            url_search: '',
            url_department: '',
            url_role: '',
            deleteId: null,



            feature: this.getFeature(),
            loading: false,

            test: null,
            offDaySetting: this.getOffDaySetting(),

        };
    },

    methods: {
        ...mapGetters(['getToken', 'getFeature' ,'getOffDaySetting']),
        ...mapMutations([ 'setOffDaySetting']),
        
        async getPrimaryList(pageNumber) {
            this.loading = true;
            // let url = this.url + pageNumber + this.url_search + this.url_department + this.url_role;
            let url = this.url;
            let response = await getApiData({ url: url, token: this.getToken() });
            console.log(response)
            if (response.data) {
                this.loading = false;
                this.offDayList = response.data;
            }
        },
        isActiveToggled(id) {
            let index = this.offDayList.findIndex(off => off.id == id);
            if (index != -1) {
                if (this.offDayList[index].type == 'custom') {
                    this.offDayList[index].type = 'default';
                }
                else {
                    this.offDayList[index].type = 'custom';
                }

                let url = `/api/hr/off_day_settings/toggle`;
                let formData = new FormData();
                formData.append('id', id);
                formData.append('type', this.offDayList[index].type);
                let response = postApiData({ url: url, form_data: formData, token: this.getToken() });
                // if(response.data){
                //     this.getPrimaryList();
                //     let off_day_setting = null;
                //     off_day_setting = JSON.parse(JSON.stringify(response.data.type));
                //     this.setOffDaySetting(off_day_setting);
                // }

                this.getPrimaryList();
                let off_day_setting = null;
                off_day_setting = JSON.parse(JSON.stringify(this.offDayList[index].type));
                this.setOffDaySetting(off_day_setting);
                console.log(off_day_setting)
                this.offDaySetting = this.getOffDaySetting();
            }// "off_day_setting": "custom"
        },


        


        alertValidationMessage(field) {
            this.$notify({
                title: 'Input validation',
                text: `You forgot to provide ${field}, please try again`,
                type: 'warn'
            });
        },

        




    },
    mounted() {
        initTE({ Modal, Select, Ripple });
    },
    created() {
        this.getPrimaryList(1);
    }
}
</script>
<style scoped src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
