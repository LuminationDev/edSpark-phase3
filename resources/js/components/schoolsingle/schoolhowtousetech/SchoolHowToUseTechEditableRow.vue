<script setup>
import {computed,ref} from 'vue'

import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import ImageUploaderInput from "@/js/components/bases/ImageUploaderInput.vue";
import {guid} from "@/js/helpers/guidGenerator";

const props = defineProps({
    howToUseTechItem: {
        type: Object,
        required: true
    }
})

const emits = defineEmits(['emitTinyMceContent', 'emitImagesArray'])

const displayTitle = (code) => {
    switch (code) {
    case "IoT":
        return "Internet of Things"
    case "VR":
        return "Virtual Reality"
    case "AR":
        return "Augmented Reality"
    default:
        return code
    }
}

const handleEmitImage = (itemName, urlsArray) =>{
    console.log( 'emmitting ' + itemName )
    emits('emitImagesArray',itemName, urlsArray)
}


</script>

<template>
    <div class="HowToUseText richTextContentContainer w-full">
        <div class="font-semibold howToTitle mb-6 text-xl">
            {{ displayTitle(howToUseTechItem.name) }}
        </div>
        <TinyMceRichTextInput
            :src-content="howToUseTechItem.text"
            @emit-tiny-rich-content="(data) => emits('emitTinyMceContent',howToUseTechItem.name,data)"
        />
    </div>
    <div class="HowToUseImage flex justify-center items-center flex-col h-full mt-2 w-full">
        <div class="flex mb-4 self-start">
            Image gallery (Up to 5 images)
        </div>
        <ImageUploaderInput
            :key="guid()"
            :item-type="'HowToUseTech'"
            :current-media="howToUseTechItem.images"
            :max="5"
            @emit-uploaded-media="(urlsArray)=> handleEmitImage(howToUseTechItem.name, urlsArray)"
        />
    </div>
</template>
