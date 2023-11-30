<script setup>
// import 'tinymce/plugins/advlist';
// import 'tinymce/plugins/code';
// import 'tinymce/plugins/emoticons';
// import 'tinymce/plugins/emoticons/js/emojis';
// import 'tinymce/plugins/link';
// import 'tinymce/plugins/lists';
// import 'tinymce/plugins/table';

import Editor from '@tinymce/tinymce-vue'
import {watchDebounced} from "@vueuse/core";
import {ref} from "vue";

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
            height:500,
            menubar: false,
            plugins: 'advlist autoresize codesample directionality emoticons fullscreen image  link lists media table wordcount',
            toolbar: 'undo redo removeformat | formatselect fontsizeselect | bold italic | alignjustify  aligncenter alignleft alignright | numlist bullist | forecolor backcolor | blockquote table hr | image link media codesample emoticons | wordcount fullscreen'
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