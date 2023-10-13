<script setup>
import AttachesTool from '@editorjs/attaches';
import EditorJS from '@editorjs/editorjs';
/**
 * EditorJS Plugins
 */
import Header from '@editorjs/header';
import ImageTool from '@editorjs/image'
import List from '@editorjs/list';
import Paragraph from '@editorjs/paragraph';
import SimpleImage from '@editorjs/simple-image';
import FontSize from 'editorjs-inline-font-size-tool';
// import VideoRecorder from '../../constants/customVideoBlock';
// import VideoRecorder from '../../constants/editorJsCustomVideoRecorder.js';
import {onMounted} from 'vue';

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {serverURL} from "@/js/constants/serverUrl";

import CustomAttachesTool from '../../constants/attachesExtension';

const props = defineProps({
    existingData: {
        type: Object,
        required: false
    },
    editMode: {
        type: Boolean,
        required: false
    }
})

const emits = defineEmits(['sendSchoolData'])

const editorJsTools = {
    header: {
        class: Header,
        inlineToolbar: true,
        config: {
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
                icon: '<svg width="25" height="19" viewBox="0 0 25 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.70313 18.5V3.875H0.890625V0.5H16.0031V3.875H10.1906V18.5H6.70313ZM17.1656 18.5V9.5H13.6781V6.125H24.1406V9.5H20.6531V18.5H17.1656Z" fill="white"/></svg>',
                title: 'Text'
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
        config: {
            endpoints: {
                byFile: API_ENDPOINTS.IMAGE.IMAGE_UPLOAD_EDITOR_JS
            }
        }
    },
    video: {
        class: CustomAttachesTool,
        config: {
            endpoint: API_ENDPOINTS.IMAGE.IMAGE_UPLOAD_EDITOR_JS
        }
    }
}

const editor = new EditorJS({
    holder: 'editorJs',
    tools: editorJsTools,
    autofocus: true,
    onReady: () => {
        console.log('EditorJs in the chamber and ready to be emptied 🔫')
        if (props.existingData) {
            for (const block of props.existingData.blocks) {
                editor.blocks.insert(block.type, block.data)
            }
            // Add an auto delete first block due editorjs automatically create 1 empty block on init
            // when props exists, delete first empty block to ensure consistent representation of data
            editor.blocks.delete(0);
        }
    },
    onChange: async (api, event) => {
        const blockCount = editor.blocks.getBlocksCount();
    }
});

const editorJsEvent = (customEvent) => {
    // customEvent.type
    switch (customEvent.type) {
    case 'block-added':
        console.log('handler for blockadded')
        break;
    case 'block-changed':
        console.log('handler for block changed')
        break;
    }

}

const handleEditorSave = async () => {
    await editor.save().then(outputData => {
        emits('sendSchoolData', outputData)
    }).catch(err => {
        console.log('error has happened ' + err)
    })
}

const handleEditorRerender = async (newJsonData) => {
    await editor.render(newJsonData)
}

defineExpose({
    handleEditorSave,
    handleEditorRerender
})

</script>
<template>
    <div
        id="editorJs"
        class="editor flex-col mt-8 rounded-lg text-genericDark"
    />
</template>

<style>
.ce-toolbar__content {
    margin: 0 0 0 -65px;

}

.codex-editor .codex-editor__redactor {
    margin-right: 50px !important;
}

.ce-block__content {
    max-width: 100% !important;
    padding: 0.5rem 1rem !important;
}

.ce-block {
    margin-bottom: 2rem !important;
    overflow: hidden;
}

.ce-block--focused {
    border: 1px solid #d9d9d9 !important;
    border-radius: 0.75rem !important;
    margin-right: 0 !important;
    padding-right: 0 !important;
    -webkit-box-shadow: 0px 0px 36px -8px rgba(0, 0, 0, 0.15);
    box-shadow: 0px 0px 36px -8px rgba(0, 0, 0, 0.15);
}

/* Interaction buttons (add block, edit block, etc) */
.ce-toolbar__actions {
    left: 2rem !important;
    flex-direction: column-reverse;
    max-width: 26px !important;
}

.ce-toolbar__settings-btn {
    margin-left: 0 !important;
    width: 26px !important;
}

.ce-popover {
    left: 0 !important;
    right: unset !important;
    border: solid 1px #d9d9d9 !important;
}

.ce-settings .ce-popover {
    left: 26px !important;
}

.ce-popover--opened {
    background-color: #ffffff;
}

.ce-inline-toolbar {
    left: 23px !important;
    /* top: -40px !important; */
}

/* New block editor placehoolder override */
.ce-paragraph[data-placeholder]:empty::before,
.ce-header[data-placeholder]:empty::before {
    color: #d9d9d9 !important;
    opacity: 1 !important;
    font-weight: 300 !important;
    font-size: 14px !important;
}

/* Block heading sizes */
.ce-header {
    padding-top: 3px !important;
}

/* HEADING 1 */
.ce-block__content h1 {
    font-size: 36px;
}

.ce-block__content h2 {
    font-size: 33px;
}

.ce-block__content h3 {
    font-size: 30px;
}

.ce-block__content h4 {
    font-size: 27px;
}

.ce-block__content h5 {
    font-size: 24px;
}

.ce-block__content h6 {
    font-size: 21px;
}
</style>
