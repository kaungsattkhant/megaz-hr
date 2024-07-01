<template>
    <div>

        <div class="">
            <div class="w-full pt-9 px-6">

                <div class="bg-white rounded w-2/4 mx-auto">
                    <div class="relative  p-4">
                        <p class="text-xl w-full text-center">
                            Create Customer
                        </p>
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
                                <option :value="gender.id" v-for="(gender, genderIndex) in genderList" :key="genderIndex" > {{ gender.name }} </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="" class="block text-sm text-black mb-3">
                                Birthdate
                            </label>
                            <input type="date" placeholder="2000-02-02" v-model="date"
                                class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
                        </div>
                        <div class=" mb-4">
                                <label for="" class="text-sm text-black mb-2 block">
                                    Division
                                </label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedDivision"
                                    @change="divisionSelectChanged" >
                                    <option class="text-sm" :value="division" v-for="(division,divisionIndex) in divisionList" :key="divisionIndex">
                                        {{ division.name }}
                                    </option>

                                </select>
                        </div>

                        <div class=" mb-4">
                                <label for="" class="text-sm text-black mb-2 block">
                                    Township
                                </label>
                                <select class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                    v-model="selectedTownship"
                                    @change="townshipSelectChanged" >
                                    <option class="text-sm" :value="township" v-for="(township,index) in townshipList" :key="index">
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
                            <textarea v-model="address" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                                 name="" id="" cols="30" rows="10"></textarea>
                        </div>

                    </div>

                    <div class="flex justify-center px-12 pb-8">
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

    export default {
        data() {
            return {
                name: null,
                ph_number:null,
                email:null,
                genderList: [],
                date:null,
                address:null,
                selectedGender:null,
                deleteId: null,
                selectedDivision:null,
                divisionList:null,
                selectedTownship:null,
                townshipList:null,
                address_name:null,

            };
        },

        methods: {
            ...mapGetters(['getToken']),

            async getGendersList(){
                const response = await getApiData({ url: '/api/genders', token: this.getToken() });
                if(response.data){
                    this.genderList = response.data;
                }
            },
            async getDivisionList(){
                const response = await getApiData( { url: '/api/divisions', token: this.getToken() } );
                if(response.data){
                    this.divisionList = response.data;
                }
            },
            divisionSelectChanged(){
                this.getTownShipList();
            },
            async getTownShipList(){
                this.townshipList = this.divisionList.find(x => x.id === this.selectedDivision.id).townships;
            },
            createCustomerBtnClicked(){
                this.createCustomer();
            },

            async createCustomer()
            {
                let formData = new FormData();
                formData.append('gender_id', this.selectedGender);
                formData.append('name', this.name);
                formData.append('email', this.email);
                formData.append('phone_number', this.ph_number);
                formData.append('address_name', this.address_name);
                formData.append('address', this.address);
                formData.append('birthdate', this.date);
                formData.append('township_id', this.selectedTownship.id);
                let response = await postApiData({url: '/api/customers', form_data: formData, token: this.getToken()});
                console.log(this.selectedGender+','+this.name+','+this.email+','+this.ph_number+','+this.address+','+this.date)
                if(response.success){
                    window.location.replace('/pos/customer');
                    console.log("success")
                }
                else{
                    alert('some errors occur');
                }
            },
        },
        mounted()
        {   
            this.getDivisionList();
            this.getGendersList();
            initTE({ Modal, Select, Ripple, Datepicker });
        }
    }
</script>
