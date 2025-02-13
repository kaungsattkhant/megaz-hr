<template>
    <div class="px-0">
        <div class="mb-4">
            <p class="text-lg font-semibold font-inter">
                Create Meeting
            </p>
        </div>
        <div class="grid !grid-cols-12 gap-x-4 mb-6 bg-white p-8 rounded-md">
            <div class="mb-4 col-span-9 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Title
                </label>
                <input type="text" v-model="title"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Date
                </label>
                <input type="text" v-model="selectedDate"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    From
                </label>
                <input type="number" v-model="selectedFrom"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    To
                </label>
                <input type="text" v-model="selectedTo"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0">
            </div>
            <div class="col-span-3"></div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Department
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Department" v-model="selectedDepartment" class="input-ui !text-black"
                    data-te-select-filter="true" @change="departmentChange()" >
                        <option :value="department" v-for="(department, departmentIndex) in departmentList" :key="departmentIndex">
                            {{ department.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Role
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Role" v-model="selectedRole" class="input-ui !text-black"
                    data-te-select-filter="true" @change="roleChange()" >
                        <option :value="role" v-for="(role, roleIndex) in roleList" :key="roleIndex">
                            {{ role.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label class="label-form mb-3">Staff</label>
                <multiselect
                v-model="selectedStaff"
                :options="staffList"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select Staff"
                label="name"
                track-by="id"
                :preselect-first="false">
                    <template #selection="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length" v-show="!isOpen">{{ values.length }}
                        Staff selected</span>
                    </template>
                </multiselect>
            </div>
            <div class="col-span-3"></div>

            <div class="mb-6 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Place
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select Place" v-model="selectedPlace" class="input-ui !text-black"
                    data-te-select-filter="true">
                        <option :value="place" v-for="(place, placeIndex) in placeList" :key="placeIndex">
                            {{ place.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-4 col-span-3 pb-0 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Chaired By
                </label>
                <div class="bg-white mb-0 w-full text-sm inline-block h-[34px] select-custom2" data-te-select-wrapper-ref>
                    <select data-te-select-init data-te-select-placeholder="Select" v-model="selectedChairedBy" class="input-ui !text-black"
                    data-te-select-filter="true">
                        <option :value="item" v-for="(item, itemIndex) in chairedByList" :key="itemIndex">
                            {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-span-6"></div>



            <div class="mb-4 col-span-6 pb-6 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Description
                </label>
                <textarea name="" v-model="description"
                    class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg" id="" cols="30"
                    rows="6"></textarea>
            </div>
        </div>
        <div>
            <button class="add-btn" @click="createBtnClicked">
                Create Meeting
            </button>
        </div>
    </div>
    
</template>

<script>
import { Modal, Ripple, initTE, Input, Select, Dropdown } from "tw-elements";
import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            departmentList:[],
            roleList:[],
            staffList:[],
            placeList:[],
            chairedByList:[],

            title:null,
            selectedDate:null,
            selectedFrom:null,
            selectedTo:null,
            selectedDepartment:null,
            selectedRole:null,
            selectedStaff:null,
            selectedPlace:null,
            selectedChairedBy:null,
            description:null,

            typeList:[
                {value:'phone',name:'Phone'},
                {value:'kpay',name:'Kpay'}
            ]
        };
    },

    methods: {
        ...mapGetters(['getToken']),

        

        async getDepartmentList(){
            let url = `/api/departments`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.departmentList = response.data;
            }
        },
        async getRoleList(){
            let url = `/api/roles_department/`+this.selectedDepartment.id;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.roleList = response.data;
            }
        },
        async getStaffList(){
            let url = `/api/staff_by_department/`+this.selectedDepartment.id+`/role/`+this.selectedRole.id;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.staffList = response.data;
            }
        },

        

        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },

        async itemSelected(){
            this.selectedItemBrands = [];
            let url = `/api/get_brand_by_item?item_ids[]=${this.selectedItem.id}`;
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.success){
                this.itemBrandsList = response.data;
            }
        },
        
        async createBtnClicked(){
            if(!this.name){
                this.alertValidationMessage(`Supplier Same`);
                return 1;
            }
            if(!this.shopName){
                this.alertValidationMessage(`Supplier Shop`);
                return 1;
            }
            if(!this.address){
                this.alertValidationMessage(`Supplier Address`);
                return 1;
            }
            if(this.selectedItems.length < 1){
                this.alertValidationMessage(`Supplier selling Items`);
                return 1;
            }
            if(this.phoneNumberList.length < 1){
                this.alertValidationMessage(`Supplier Phone Number`);
                return 1;
            }
            // if(this.bankAccountList.length < 1){
            //     this.alertValidationMessage(`supplier Bank Account`);
            //     return 1;
            // }
            if(!this.maxCredit){
                this.alertValidationMessage(`Supplier Credit Limit`);
                return 1;
            }
            if(!this.selectedAccount){
                this.alertValidationMessage(`Supplier Account Payable`);
                return 1;
            }
            if(!this.lead_time){
                this.alertValidationMessage(`Lead Time`);
                return 1;
            }
            if(!this.credit_terms){
                this.alertValidationMessage(`Credit Terms`);
                return 1;
            }
            if(!this.selectedCreditAccount){
                this.alertValidationMessage(`Credit account`);
                return 1;
            }

            let formData = new FormData();
            formData.append("name", this.name);
            formData.append("shop_name", this.shopName);
            // formData.append("phone_number", this.phoneNumber);
            formData.append("credit_limit", this.maxCredit);
            formData.append("lead_time", this.lead_time);
            formData.append("credit_terms", this.credit_terms);
            formData.append("address", this.address);
            formData.append("account_id", this.selectedAccount.id);
            formData.append("creditor_account_id", this.selectedCreditAccount.id);
            let itemBrandList = [];
            this.selectedItems.forEach((item)=>{
                // formData.append("items[]", item.id);
                item.brands.forEach((brand)=>{
                    itemBrandList.push({
                        item_id:item.id,
                        brand_id:brand.id
                    })
                    // formData.append("brands[]", brand.id);
                });
            });

            formData.append("supplier_items", JSON.stringify(itemBrandList));
            formData.append("supplier_phones", JSON.stringify(this.phoneNumberList));
            if(this.bankAccountList.length > 0){
                formData.append("supplier_bank_accounts", JSON.stringify(this.bankAccountList));
            }
            
            let url = `/api/suppliers`;
            let response = await postApiData({url: url, form_data: formData, token: this.getToken()});
            if(response.success){
                window.location.replace("/suppliers");
            }
        },

        

        deleteSelectedItemBtnClicked(index){
            // let index = this.selectedItems.findIndex(item => item.id == id);
            if(index != -1){
                this.selectedItems.splice(index, 1);
            }
        },
        deleteSeleted(index,list){
            if(index != -1){
                list.splice(index, 1);
            }
        },
    },

    created(){
        this.getDepartmentList();
    },

    mounted() {
        initTE({Modal, Ripple, Input, Select, Dropdown});
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
