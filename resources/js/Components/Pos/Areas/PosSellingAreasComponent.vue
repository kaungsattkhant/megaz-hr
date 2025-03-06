<template>
    <div class="h-full">
        <notifications position="top center" />
        <div class="h-full">
            <div class="flex flex-wrap gap-x-4 gap-y-4 items-center  h-full">
                <div 
                    class=" flex-shrink-0 flex-grow-0 p-6 w-1/4 aspect-[5/4] h-fit rounded-lg" >
                    <button 
                        class="relative flex flex-col justify-between h-full w-full bg-black rounded-lg">
                        <div class=" flex justify-between flex-col h-full">
                            <div>
                                <p class="text-sm text-white">Start Time :  </p>
                                <p class="text-sm text-white">Start Time :  </p>
                            </div>
                            <p class="text-base text-left text-white">
                                asdf
                            </p>
                        </div>
                        <div class="absolute bottom-0 w-full flex justify-end">
                            <p class="text-base text-white font-semibold">
                                asdf
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
            selectedAreaId: null,
            
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
            let url = '/api/sellings_areas'
            const response = await getApiData({ url: url, token: this.getToken() });
            if (response.data) {
                this.areaList = response.data;
            }
        },
        
        
        
        async createCustomer() {
            let formData = new FormData();
            formData.append('gender_id', this.selectedGender);
            formData.append('township_id', this.selectedTownship.id);
            let response = await postApiData({ url: '/api/sellings_areas', form_data: formData, token: this.getToken() });
            console.log(this.selectedGender + ',' + this.name + ',' + this.email + ',' + this.ph_number + ',' + this.address + ',' + this.date)
            if (response.success) {
                this.customerList.push(response.data);
                this.selectedCustomer = response.data;
                console.log("success customer")
                this.closeCustomerModal();
                this.clearCustomerForm();
            }
            else {
                console.log('some errors occur');
            }
        },



        
    },
    watch: {
        
    },
    mounted() {
        // this.getAreaList();
        this.getAuthUser();
        initTE({ Modal, Select, Ripple, Tab });

    }
}
</script>
