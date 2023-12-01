<script setup>


import Editor from '@tinymce/tinymce-vue'
import {watchDebounced} from "@vueuse/core";
import {ref} from "vue";

import {API_ENDPOINTS, IMAGE_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

const props = defineProps({
    srcContent: {
        type: String,
        required: false,
        default: ''
    }
})

const emits = defineEmits('emitTinyRichContent')
const editorContent = ref(props.srcContent)

const emitContent = () =>{
    emits('emitTinyRichContent', editorContent.value )
}

watchDebounced(editorContent, emitContent,  {debounce: 200, maxWait: 1000})

</script>

<template>
    <editor
        v-model="editorContent"
        :init="{
            min_height: 300,
            menubar: false,
            plugins: 'advlist autoresize codesample directionality emoticons fullscreen image link lists media table wordcount',
            toolbar: 'undo redo removeformat |  styles fontfamily fontsize | bold italic | alignjustify alignleft aligncenter  alignright | numlist bullist | forecolor backcolor | blockquote table hr | image link media codesample emoticons | wordcount',
            images_upload_url: IMAGE_ENDPOINTS.IMAGE.IMAGE_UPLOAD_TINYMCE,
            convert_urls: false,
            toolbar_sticky: true,
            toolbar_sticky_offset: 45,
            image_caption: true,
            image_advtab: true
        }"
    />
</template>
<style>
.tox-promotion{
    display: none;
}


.tox-statusbar__branding{
    display: none;

}
</style>