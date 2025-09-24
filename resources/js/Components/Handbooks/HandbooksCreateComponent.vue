<template>
    <div class="px-0">
        <div class="mb-4 ">
            <p class="text-lg font-semibold font-inter">
                Create Handbook
            </p>
        </div>


        <div class="grid !grid-cols-10 gap-x-8 bg-white p-8 rounded-md shadow-md mb-8">
            <div class="mb-3 col-span-3 pb-0 rounded-md">
                <label for="" class="label-form mb-3">
                    Title
                </label>
                <input type="text" v-model="selectedTitle" class="input-ui ">
            </div>
            <div class="mb-3 col-span-3 pb-0 rounded-md">
                <label for="" class="label-form mb-3">
                    Description
                </label>
                <input type="text" v-model="selectedDescription" class="input-ui ">
            </div>
            <div class="col-span-4"></div>
            <div class="mb-4 col-span-3 rounded-md">
                <label for="" class="block text-sm text-black mb-3">
                    Images
                </label>
                <div class="">
                    <input
                        type="file"
                        @change="handleFileChange"
                        class="input-ui w-full !p-1 text-xs"
                    />
                </div>
            </div>
            <div class="col-span-7"></div>
            <!-- <div class="mb-4 col-span-6">
                <label for="" class="labelform mb-3">
                    Detail
                </label>
                <textarea v-model="detail" class="text-sm border border-gray-300 input-ui w-full bg-transparent rounded-lg focus:ring-0"
                     name="" id="" cols="30" rows="10"></textarea>

            </div> -->
            <div class="col-span-10">
                <textarea id="editor" v-model="test"></textarea>
            </div>
            
            
        </div>

        
        <div>
            <button class="add-btn" @click="btnClickedCreateHandbook()">
                Create Handbook
            </button>
        </div>
    </div>
</template>

<script>
import { Modal, Ripple, initTE, Tab, Select } from "tw-elements";
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';
import { each } from "lodash";

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            test: null,
            editorInstance: null,

            selectedTitle:null,
            selectedDescription:null,
            detail:null,
            selectedImage:null,
            
            selectedSopList: [],
        };
    },

    methods: {
        ...mapGetters(['getToken']),
        
        
        handleFileChange(event) {
            const selectedFile = event.target.files[0];
            this.selectedImage = selectedFile;
        },
        btnClickedCreateHandbook(){
            if(!this.selectedTitle){
                this.alertValidationMessage(`Title`);
                return 1;
            }
            else if(!this.selectedDescription){
                this.alertValidationMessage(`Description`);
                return 1;
            }
            else if(!this.selectedImage){
                this.alertValidationMessage(`Image`);
                return 1;
            }
            else if(!this.detail){
                this.alertValidationMessage(`Detail`);
                return 1;
            }
            else{
                this.createHandbook();
            }
        },
        async createHandbook(){
            let formData = new FormData();
            formData.append('title',this.selectedTitle);
            formData.append('description',this.selectedDescription);
            formData.append('detail',JSON.stringify(this.detail));
            formData.append('image',this.selectedImage);
            let response = await postApiData({url:`/api/hr/hand-books`, form_data:formData, token:this.getToken()})
            if(response.success){
                console.log('successed')
                window.location.replace(`/handbooks`);
            }else {
                this.$notify({
                    title: `Input validation`,
                    text: response.message,
                    type: "warn"
                });
            }
        },

        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn"
            });
        },
    },

    watch: {
    },

    async created() {

    },

    mounted() {
        this.editorInstance = CKEDITOR.replace("editor");

        // Sync data when editor content changes
        this.editorInstance.on("change", () => {
            this.detail = this.editorInstance.getData();
        });
        initTE({ Modal, Select, Tab, Ripple });
    }
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
