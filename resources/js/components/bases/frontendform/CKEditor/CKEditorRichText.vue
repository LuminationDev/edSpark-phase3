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
const initialContent = ref(null)
const editorConfig = {}

const handleInputEditor = () =>{
    console.log(initialContent.value)
}

</script>

<template>
    <Ckeditor
        v-model="initialContent"
        :editor="editor"
        :config="editorConfig"
        @input="handleInputEditor"
    />
</template>
<style>
.ck .ck-powered-by{
    display: none !important;

}

</style>