<script setup>

import Editor from '@tinymce/tinymce-vue'
import {watchDebounced} from "@vueuse/core";
import {ref, watch} from "vue";

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

const editorStyleFormat = [
    { title: 'Normal Text', format: 'p' },
    { title: 'Headings', items: [
        { title: 'Heading 1', format: 'h1' },
        { title: 'Heading 2', format: 'h2' },
        { title: 'Heading 3', format: 'h3' },
        { title: 'Heading 4', format: 'h4' },
        { title: 'Heading 5', format: 'h5' },
        { title: 'Heading 6', format: 'h6' }
    ]},
    { title: 'Inline', items: [
        { title: 'Underline', format: 'underline' },
        { title: 'Strikethrough', format: 'strikethrough' },
        { title: 'Superscript', format: 'superscript' },
        { title: 'Subscript', format: 'subscript' },
    ]},
    { title: 'Blocks', items: [
        { title: 'Blockquote', format: 'blockquote' },
        { title: 'Code', format: 'code' }
    ]},
]

const tinyMCE = ref(null)

const emits = defineEmits(['emitTinyRichContent'])
const editorContent = ref(props.srcContent)
const emitContent = () => {
    emits('emitTinyRichContent', editorContent.value)
}

watchDebounced(editorContent, emitContent, {debounce: 200, maxWait: 1000})

const fontStyling = `
h1 {
    display: block;
    /* font-size: 2em; */
    margin-block-start: 0.67em;
    margin-block-end: 0.67em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    margin-top: 32px;
    margin-bottom: 16px;
    font-size: xx-large;
}

h2 {
    display: block;
    /* font-size: 1.5em; */
    margin-block-start: 0.83em;
    margin-block-end: 0.83em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    margin-top: 28px;
    margin-bottom: 16px;
    font-size: x-large;
}

h3 {
    display: block;
    /* font-size: 1.17em; */
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    margin-top: 24px;
    margin-bottom: 16px;
    font-size: larger;
}

h4 {
    display: block;
    margin-block-start: 1.33em;
    margin-block-end: 1.33em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 14px;
    font-size: large;
}

h5 {
    display: block;
    /* font-size: 0.83em; */
    margin-block-start: 1.67em;
    margin-block-end: 1.67em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: 500;
    margin-top: 20px;
    margin-bottom: 14px;
    font-size: large;
}

h6 {
    display: block;
    /* font-size: 0.67em; */
    margin-block-start: 2.33em;
    margin-block-end: 2.33em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: 500;
    margin-top: 20px;
    margin-bottom: 14px;
    font-size: medium;
}

p {
    font-weight: 100;
    padding-bottom: 16px;
    line-height: 1.5;
    font-size: large;
}
`


</script>

<template>
    <editor
        ref="tinyMCE"
        v-model="editorContent"
        :init="{
            min_height: props.minHeight,
            placeholder: '<p>Insert text here</p>',
            menubar: false,
            plugins: 'advlist autoresize codesample directionality emoticons fullscreen image link lists media table wordcount',
            toolbar: 'undo redo removeformat |  styles fontsize | bold italic | alignjustify alignleft aligncenter  alignright | numlist bullist | forecolor backcolor | blockquote table hr | image link media codesample emoticons | wordcount',
            images_upload_url: IMAGE_ENDPOINTS.IMAGE.IMAGE_UPLOAD_TINYMCE,
            convert_urls: false,
            toolbar_sticky: true,
            toolbar_sticky_offset: 45,
            image_caption: true,
            image_advtab: true,
            browser_spellcheck : true,
            content_css: '/css/filament/font/font.css',
            link_default_target:'_blank',
            skin: false,
            content_style: `body {font-family: MuseoSans;} html {font-family: MuseoSans;} .mce-offscreen-selection{display: none;} ${fontStyling}`,
            style_formats: editorStyleFormat,
            contextmenu: 'copy cut paste image link',
            license_key: 'gpl'
        }"
    />
    <ErrorMessages
        v-if="props.v$"
        :v$="props.v$"
    />
</template>
<style>
.tox-tinymce{
    border-radius: 3px !important;
    border-width: 1px !important;
}
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
}

figure figcaption {
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
.mce-content-body p{
    font-weight: 300
}
</style>