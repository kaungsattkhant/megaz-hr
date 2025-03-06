<template>
    <div class="h-full">
        <notifications position="top center" />
        <div class="h-full">

            <div class="text-center mt-[10%]">
                <p class="mb-2 text-xl text-black">
                    Choose Area
                </p>
                <p class="mb-2 text-base text-gray-400">
                    Click Area You have to take care
                </p>
            </div>
            <p>
                {{ selectedAreaId }}
            </p>




            <div class="grid grid-cols-4">
                <div v-for="(area,index) in areaList" :key="index"
                    class="p-6 aspect-[5/4] h-fit rounded-lg" >
                    <button @click="btnClickedArea(area)"
                        class="relative flex flex-col justify-between h-full w-full bg-[#BD127959] rounded-lg">
                        
                        <div class="flex justify-end items-end  text-right h-full w-full">
                            <p class="text-base text-white font-semibold p-4">
                                {{ area.name }}
                            </p>
                        </div>
                    </button>
                </div>
                
            </div>
            
        </div>


        

    </div>

</template>
<script>
import { Modal, Ripple, Select, initTE, Tab } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../../utilities/ajax-helpers';
import { getCurrentTime, getCurretDateTime } from '../../../utilities/datetime-helpers';
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            areaList: [],
            selectedArea:null,
            selectedAreaId: null,
            typeList: [],
            selectedAreaType:null,
        };
    },

    methods: {
        ...mapGetters(['getToken','getUser','getDepartment','getRoles']),

        async getAuthUser()
        {
            this.authUser = this.getUser();
            this.authUser.department = this.getDepartment();
            this.authUser.roles = this.getRoles();

            if(this.authUser.department.name == "Finance" && this.authUser.roles[0].name=='Cashier')
            {
                isCashier = true;
            }
        },

        async getAreaList() {
            let url = '/api/sellings_areas?is_pos=1'
            const response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.areaList = response.data.data;
            }
        },
        btnClickedArea(area){
            this.selectedAreaId = area;
            localStorage.setItem('local_area_id', area.id);
            let areaType = null;
            areaType = this.typeList.find(id => id.id == area.area_type_id)
            this.selectedAreaType = areaType
            localStorage.setItem('local_area_type', JSON.stringify(areaType));
            this.selectedArea = area;
            localStorage.setItem('local_selected_area', JSON.stringify(area));

        },
        async getTypeList() {
            const response = await getApiData({ url: '/api/area_types', token: this.getToken() });
            if (response.data) {
                this.typeList = response.data;
            }
        },
    },
    watch: {
        
    },
    mounted() {
        this.getAreaList();
        this.getAuthUser();
        this.getTypeList();
        initTE({ Modal, Select, Ripple, Tab });

    }
}
</script>
