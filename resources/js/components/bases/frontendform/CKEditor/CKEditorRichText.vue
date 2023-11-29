<script setup lang="ts">

import Editor from "ckeditor5-custom-build";

const props = defineProps({
    srcContent: {
        type: String,
        required: false,
        default: ''
    }
})

import {ref} from "vue";

const editor = Editor
const editorContent = ref(props.srcContent)
const intialContent = ref(null)
const editorConfig = {
}

</script>

<template>
    <Ckeditor
        v-model="intialContent"
        :editor="editor"
        :config="editorConfig"
    />
</template>
<style>
.ck .ck-powered-by{
    display: none !important;

}

</style>