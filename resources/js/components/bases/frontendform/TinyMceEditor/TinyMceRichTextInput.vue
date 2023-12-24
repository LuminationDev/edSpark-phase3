<script setup>

import Editor from '@tinymce/tinymce-vue'
import {watchDebounced} from "@vueuse/core";
import {ref} from "vue";

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import {API_ENDPOINTS, IMAGE_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

const props = defineProps({
    srcContent: {
        type: String,
        required: true,
    },
    minHeight: {
        type: Number,
        required: false,
        default: 300
    },
    v$: {
        type: Object,
        required: false,
        default: () => {
        }
    }
})

const emits = defineEmits(['emitTinyRichContent'])
const editorContent = ref(props.srcContent)

const emitContent = () => {
    emits('emitTinyRichContent', editorContent.value)
}

watchDebounced(editorContent, emitContent, {debounce: 200, maxWait: 1000})

</script>

<template>
    <editor
        v-model="editorContent"
        :init="{
            min_height: props.minHeight,
            placeholder: '<p>hehehe</p>',
            menubar: false,
            plugins: 'advlist autoresize codesample directionality emoticons fullscreen image link lists media table wordcount',
            toolbar: 'undo redo removeformat |  styles fontsize | bold italic | alignjustify alignleft aligncenter  alignright | numlist bullist | forecolor backcolor | blockquote table hr | image link media codesample emoticons | wordcount',
            images_upload_url: IMAGE_ENDPOINTS.IMAGE.IMAGE_UPLOAD_TINYMCE,
            convert_urls: false,
            toolbar_sticky: true,
            toolbar_sticky_offset: 45,
            image_caption: true,
            image_advtab: true,
            content_css: '/css/filament/font/font.css',
            skin: false,
            content_style: `body {font-family: MuseoSans;} html {font-family: MuseoSans;} .mce-offscreen-selection{display: none;}`
        }"
    />
    <ErrorMessages :v$="props.v$" />
</template>
<style>
:deep(.mce-offscreen-selection){
    display: none;
}

table {
    border-collapse: collapse
}

table:not([cellpadding]) td, table:not([cellpadding]) th {
    padding: .4rem
}

table[border]:not([border="0"]):not([style*=border-width]) td, table[border]:not([border="0"]):not([style*=border-width]) th {
    border-width: 1px
}

table[border]:not([border="0"]):not([style*=border-style]) td, table[border]:not([border="0"]):not([style*=border-style]) th {
    border-style: solid
}

table[border]:not([border="0"]):not([style*=border-color]) td, table[border]:not([border="0"]):not([style*=border-color]) th {
    border-color: #ccc
}

figure {
    display: table;
    margin: 1rem auto
}

figure figcaption {
    color: #999;
    display: block;
    margin-top: .25rem;
    text-align: center
}

hr {
    border-color: #ccc;
    border-style: solid;
    border-width: 1px 0 0 0
}

code {
    background-color: #e8e8e8;
    border-radius: 3px;
    padding: .1rem .2rem
}

.mce-content-body:not([dir=rtl]) blockquote {
    border-left: 2px solid #ccc;
    margin-left: 1.5rem;
    padding-left: 1rem
}

.mce-content-body[dir=rtl] blockquote {
    border-right: 2px solid #ccc;
    margin-right: 1.5rem;
    padding-right: 1rem
}
</style>