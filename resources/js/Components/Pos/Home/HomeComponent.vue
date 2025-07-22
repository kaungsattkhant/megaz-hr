<template>
    <div class="">
        <div class=" bg-gray-100 min-h-screen w-full">
            <div class="w-full pt-9 px-6 ">
                <ul class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0" role="tablist" data-te-nav-ref>
                    <li v-for="(area, index) in areaList" :key="index" role="presentation" @click="btnClickedArea(area.id,area.area_type.type,area)">
                        <a href="#tabs-profile" class="my-2 mr-3 text-white block  px-7 pb-2.5 rounded-full
                            pt-3 text-xs  hover:isolate bg-[#F0C094]
                            hover:bg-[#f7a559] focus:isolate data-[te-nav-active]:bg-[#F19E51]"
                            :class="area.id == selectedAreaId ? 'bg-[#F19E51]' : 'bg-[#F0C094]'">
                            {{ area.name }}
                        </a>
                    </li>
                </ul>
                <div v-show="areaType == 'bar_and_restaurant'" class="contents">
                    <!-- <button class="p-10 bg-red-600 text-white" @click="callTest()">
                        bar
                    </button> -->
                    <PosTableComponent v-if="selectedAreaId" :table-area-id="selectedAreaId" :area="selectedArea" ref="posTable" @callParent="parentFunction"
                    :selling-extras="sellingExtras" :remarks="orderRemarks" />
                </div>
                <div v-show="areaType == 'ktv'" class="contents">
                    <!-- <button class="p-10 bg-red-600 text-white" @click="callTest()">
                        ktv
                    </button> -->
                    <PosRoomComponent v-if="selectedAreaId" :room-area-id="selectedAreaId" :area="selectedArea" ref="posRoom" @callParent="parentFunction"
                    :selling-extras="sellingExtras" />
                </div>

                <!-- <div class="mb-6 hidden">
                    <div class="opacity-100 transition-opacity duration-150 ease-linear">

                    </div>
                </div> -->
            </div>
        </div>

        <!-- Create Customer modal -->
        <div data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="create_customer_modal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-modal="true"
            role="dialog">
            <div data-te-modal-dialog-ref
                class="pointer-events-none relative flex min-h-[calc(100%-1rem)] w-auto translate-y-[-50px] items-center opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:min-h-[calc(100%-3.5rem)] min-[576px]:max-w-[500px]">
                <div
                    class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Create Customer
                        </p>
                        <button type="button" class="absolute top-4 right-4 focus:shadow-none focus:outline-none
                        " id="closeCustomerModal" data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative px-16 py-4" data-te-modal-body-ref>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Customer Name
                            </label>
                            <input type="text" placeholder="Customer Name" v-model="name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Phone Number
                            </label>
                            <input type="text" placeholder="Phone Number" v-model="ph_number"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Email
                            </label>
                            <input type="text" placeholder="Email" v-model="email"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Gender
                            </label>
                            <select name="" id="" v-model="selectedGender"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                                <option :value="gender.id" v-for="(gender, genderIndex) in genderList"
                                    :key="genderIndex"> {{ gender.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Birthdate
                            </label>
                            <input type="date" placeholder="Birthdate" v-model="date"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class=" mb-4">
                            <label for="" class="text-sm text-black mb-2 block">
                                Division
                            </label>
                            <select
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                v-model="selectedDivision" @change="divisionSelectChanged">
                                <option class="text-sm" :value="division"
                                    v-for="(division, divisionIndex) in divisionList" :key="divisionIndex">
                                    {{ division.name }}
                                </option>

                            </select>
                        </div>

                        <div class=" mb-4">
                            <label for="" class="text-sm text-black mb-2 block">
                                Township
                            </label>
                            <select
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                v-model="selectedTownship" @change="townshipSelectChanged">
                                <option class="text-sm" :value="township" v-for="(township, index) in townshipList"
                                    :key="index">
                                    {{ township.name }}
                                </option>

                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Address Name
                            </label>
                            <input type="text" placeholder="Address Name" v-model="address_name"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Address
                            </label>
                            <textarea v-model="address"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                name="" id="" cols="30" rows="10"></textarea>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 mb-6">
                        <button @click="createCustomerBtnClicked()" class="pos-add-btn focus:outline-none focus:ring-0 ">
                            Create
                        </button>
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
    import { getCurrentTime } from "../../../utilities/datetime-helpers";
    import PosTableComponent from "./PosTableComponent.vue";
    import PosRoomComponent from "./PosRoomComponent.vue";

    export default {
        components:{
            PosRoomComponent,
            PosTableComponent,
        },
        data() {
            return {
                areaList: [],

                selectedAreaId:null,
                selectedArea:null,

                name: null,
                ph_number: null,
                email: null,
                genderList: [],
                date: null,
                duration: null,
                selectedDivision: null,
                divisionList: null,
                selectedTownship: null,
                townshipList: null,
                address_name: null,
                address: null,
                selectedGender: null,

                authUser:null,
                isCashier :false,

                areaType:null,

                orderRemarks: [],
                sellingExtras: [],
            };
        },

        methods: {
            ...mapGetters(['getToken']),

            getPosSellingExtras(){
                getApiData({url: `/api/pos/selling_extras`, token: this.getToken()})
                .then((response)=>{
                    if(response.success){
                        this.sellingExtras = response.data;
                    }
                });
            },

            getRemarks(){
                getApiData({url: `/api/remarks`, token: this.getToken()})
                .then((response)=>{
                    if(response.success){
                        this.orderRemarks = response.data;
                    }
                });
            },
            // callTest(){
            //     if(this.areaType == 'bar_and_restaurant'){
            //         this.$refs.posTable.getTableList()
            //     }
            //     else{
            //         this.$refs.posRoom.getRoomList()
            //     }
            // },
            parentFunction() {
                console.log('Parent function called from child!');
                // Place your desired functionality here.
                this.getGendersList();
                this.getDivisionList();
            },
            async getAreaList() {
                // let url = '/api/areas?area_category_id=2'
                let url = '/api/sellings_areas?is_pos=1'
                const response = await getApiData({ url: url, token: this.getToken() });
                if (response.data) {
                    this.areaList = response.data;
                    this.selectedAreaId = this.areaList[0].id
                    this.selectedArea = this.areaList[0]
                    this.areaType = response.data[0].area_type.type

                    if(response.data[0].area_type.type == 'bar_and_restaurant' && response.data[0].id){
                        this.$nextTick(() => {
                            if (this.$refs.posTable) { // Check if posTable is defined
                                this.$refs.posTable.getTableList(response.data[0]);
                                let num = 0
                                num += 1
                                console.log(num)
                            } else {
                                console.warn("posTable component is not available in $refs.");
                                console.log("posTable component is not available in $refs.");
                            }
                        });
                    }
                    else{
                        this.$nextTick(() => {
                            if (this.$refs.posRoom) { // Check if posRoom is defined
                                this.$refs.posRoom.getRoomList(response.data[0]);
                                let num = 0
                                num += 1
                                console.log(num)
                            } else {
                                console.warn("posRoom component is not available in $refs.");
                                console.log("posRoom component is not available in $refs.");
                            }
                        });
                    }

                }
            },
            btnClickedArea(areaId,areaType,area){
                this.selectedAreaId = areaId
                this.areaType = areaType
                this.selectedArea = area;
            },

            // create customer
            async getGendersList() {
                const response = await getApiData({ url: '/api/genders', token: this.getToken() });
                if (response.data) {
                    this.genderList = response.data;
                }
            },

            async getDivisionList() {
                const response = await getApiData({ url: '/api/divisions', token: this.getToken() });
                if (response.data) {
                    this.divisionList = response.data;
                }
            },
            divisionSelectChanged() {
                this.getTownShipList();
            },
            async getTownShipList() {
                this.townshipList = this.divisionList.find(x => x.id === this.selectedDivision.id).townships;
            },

            createCustomerBtnClicked() {
                this.createCustomer();
            },
            async createCustomer() {
                console.log(this.email);
                // return 1;
                let formData = new FormData();
                formData.append('gender_id', this.selectedGender);
                formData.append('name', this.name);
                if (this.email != null) {
                    formData.append('email', this.email);
                }
                formData.append('phone_number', this.ph_number);
                formData.append('address_name', this.address_name);
                formData.append('address', this.address);
                formData.append('birthdate', this.date);
                formData.append('township_id', this.selectedTownship.id);
                let response = await postApiData({ url: '/api/customers', form_data: formData, token: this.getToken() });
                console.log(this.selectedGender + ',' + this.name + ',' + this.email + ',' + this.ph_number + ',' + this.address + ',' + this.date)
                if (response.success) {
                    // this.customerList.push(response.data);
                    if(this.areaType == 'bar_and_restaurant'){
                        this.$refs.posTable.getCustomerList();
                    }
                    if(this.areaType == 'ktv'){
                        this.$refs.posRoom.getCustomerList();
                    }
                    // this.$refs.posTable.selectedCustomer = response.data;
                    // this.selectedCustomer = response.data;
                    // console.log("success customer")
                    this.closeModal('closeCustomerModal');
                    this.clearCustomerForm();
                    // window.location.reload()
                }
                else {
                    console.log('some errors occur');
                }
            },

            closeModal(modalId) {
                document.getElementById(modalId).click();
            },
            clearCustomerForm() {
                this.name = null
                this.ph_number = null
                this.email = null
                this.selectedGender = null
                this.date = null
                this.selectedDivision = null
                this.selectedTownship = null
                this.address_name = null
                this.address = null
            },

            testtime(){
                let targetTime = '16:00'
                const currentTime = new Date();
                const [targetHour, targetMinute] = targetTime.split(':').map(Number);

                // Create a new Date object with the current date and target time
                const targetDateTime = new Date();
                targetDateTime.setHours(targetHour, targetMinute, 0, 0);

                // Check if current time is past the target time
                let test_time = currentTime > targetDateTime;
                console.log(test_time)

            }
        },


        watch: {

        },
        created(){
            this.getAreaList();
            // this.getGendersList();
            // this.getDivisionList();
            // this.getCustomerList();
            // this.getGendersList();
            // this.getMenuList();
            // this.getDivisionList();
            // this.testtime();
            this.getPosSellingExtras();
            this.getRemarks();
        },
        mounted()
        {
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
