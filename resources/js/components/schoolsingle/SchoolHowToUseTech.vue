<script setup>
import {computed,ref} from 'vue'

import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import ImageUploaderInput from "@/js/components/bases/ImageUploaderInput.vue";
import {schoolPartnerTech,schoolTech} from "@/js/constants/schoolTech";


const props = defineProps({
    techUsed:{
        type: Object,
        required: true
    },
    techLandscape:{
        type: Object,
        required: true
    }
})

// create data structure
const createHowToDataStructure = itemName => {
    return ({
        name: itemName,
        text: '',
        images: []
    })
}

const howToUseData = ref([])

// handle existing data
if(props.techLandscape && props.techLandscape.length > 0){
    console.log('current data fouund, cloning and will perform check later')
    howToUseData.value = _.cloneDeep(props.techLandscape)

    // do a check based on tech used, filters out data no longer inside tech used and add skeleton for new tech

} else {
    console.log('no existing data from techLandscape')
    console.log('creating skeleton here')
    howToUseData.value = [...schoolTech, ...schoolPartnerTech].map(item => {
        return createHowToDataStructure(item.name);
    })
}

const handleTinyMceContent = (name) =>{
    console.log('data for ' + name)
}


const emits = defineEmits([])



</script>

<template>
    <div class="HowToUseTechContainer flex justify-center items-center flex-col px-5 py-2 text-genericDark md:!px-10">
        <div
            v-for="(item,index) in howToUseData"
            :key="index"
            class="HowToUseItem flex justify-center flex-col mt-6 w-full  md:!flex-row"
        >
            <div class="HowToUseText w-full">
                <div class="font-semibold howToTitle mb-6 text-2xl">
                    {{ item.name }}
                </div>
                <TinyMceRichTextInput
                    :src-content="item.text"
                    @emit-tiny-rich-content="() => handleTinyMceContent(item.name)"
                />
            </div>


            <div class="HowToUseImage flex justify-center items-center flex-col h-full mt-6 px-10 w-full">
                <label> Image gallery <span class="font-light text-xs"> (500px x 500px recommended. Max 4 MB. Accepted format: JPG, JPEG, PNG & SVG.)</span></label>
                <ImageUploaderInput
                    :item-type="''"
                    :current-media="''"
                    :max="5"
                    @emit-uploaded-media="()=>{}"
                />
            </div>
        </div>
    </div>
</template>
