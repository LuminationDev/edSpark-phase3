<script setup>
import EditorJS from '@editorjs/editorjs';
/**
 * EditorJS Plugins
 */
import Header from '@editorjs/header';
import Paragraph from '@editorjs/paragraph';
import List from '@editorjs/list';
import ImageTool from '@editorjs/image'
import {serverURL} from "@/js/constants/serverUrl";

import SimpleImage from '@editorjs/simple-image';
import FontSize from 'editorjs-inline-font-size-tool';
import VideoRecorder from '../../constants/customVideoBlock';

import { onMounted } from 'vue';

const props = defineProps({
    existingData:{
        type: Object,
        required: false
    },
    editMode:{
        type: Boolean,
        required: false
    }
})

const emits = defineEmits(['sendSchoolData'])

const editorJsTools = {
    videoRecorder: {
        class: VideoRecorder,
        // inlineToolbar: true,
        // autofocus: true,
        // config: {
        //     placeholder: 'Record Video'
        // },
    },
    header:{
        class: Header,
        inlineToolbar: true,
        config:{
            placeholder: "Insert Header"
        }
    },
    fontSize: FontSize,

    paragraph: {
        class: Paragraph,
        inlineToolbar: true,
        config: {
            placeholder: "Paragraph"
        },
        toolbox: [
            {
                icon : '<svg width="25" height="19" viewBox="0 0 25 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.70313 18.5V3.875H0.890625V0.5H16.0031V3.875H10.1906V18.5H6.70313ZM17.1656 18.5V9.5H13.6781V6.125H24.1406V9.5H20.6531V18.5H17.1656Z" fill="white"/></svg>',
                title : 'Text'
            }
        ]
    },
    list: {
        class: List,
        inlineToolbar: true,
    },
    // image: SimpleImage
    image: {
        class: ImageTool,
        config:{
            endpoints:{
                byFile: `${serverURL}/uploadImageEditorjs`
            }
        }
    }
}

const editor = new EditorJS({
    holder: 'editorJs',
    tools: editorJsTools,
    autofocus: true,
    onReady: () => {
        console.log('EditorJs in the chamber and ready to be emptied 🔫')
        if(props.existingData){
            for(let block of props.existingData.blocks){
                editor.blocks.insert(block.type, block.data)
            }
            // Add an auto delete first block due editorjs automatically create 1 empty block on init
            // when props exists, delete first empty block to ensure consistent representation of data
            editor.blocks.delete(0);
        }
    },
    onChange: async (api, event) =>{
        const blockCount = editor.blocks.getBlocksCount();
    }
});

const editorJsEvent = (customEvent) => {
    // customEvent.type
    switch(customEvent.type){
    case 'block-added':
        console.log('handler for blockadded')
        break;
    case 'block-changed':
        console.log('handler for block changed')
        break;
    }

}

const handleEditorSave = async () =>{
    await editor.save().then(outputData => {
        emits('sendSchoolData', outputData)
    }).catch(err =>{
        console.log('error has happened ' + err)
    })
}

defineExpose({
    handleEditorSave,
})

</script>
<template>
    <div
        id="editorJs"
        class="text-genericDark mt-8 rounded-lg editor flex-col"
    />
</template>
