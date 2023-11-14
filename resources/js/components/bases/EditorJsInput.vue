<script setup lang="ts">
import EditorJS from '@editorjs/editorjs';

import {editorJsTools} from "@/js/constants/editorJsTools";
import {EditorJSDataType} from "@/js/types/EditorJsTypes";

const props = defineProps({
    existingData: {
        type: Object as () => EditorJSDataType,
        required: false,
        default: () => ({})
    },
    editMode: {
        type: Boolean,
        required: false,
        default: false
    }
})

const emits = defineEmits(['sendEditorjsData'])

const editor = new EditorJS({
    holder: 'editorJs',
    tools: editorJsTools,
    autofocus: true,
    onReady: (): void => {
        if (props.existingData) {
            for (const block of props.existingData.blocks) {
                editor.blocks.insert(block.type, block.data)
            }
            editor.blocks.delete(0);
        }
    },
});

const handleEditorSave = async (): Promise<void> => {
    await editor.save().then(outputData => {
        emits('sendEditorjsData', outputData)
    }).catch(err => {
        console.log('error has happened ' + err)
    })
}

const handleEditorRerender = async (newEditorJsData: EditorJSDataType): Promise<void> => {
    await editor.render(newEditorJsData)
}

defineExpose({
    handleEditorSave,
    handleEditorRerender
})

</script>
<template>
    <div
        v-if="$slots.editorjsControl"
        class="editorjsTitle font-semibold mb-4 text-xl"
    >
        EditorJs Control Center
    </div>
    <div class="editorJsCoontrolCentre">
        <slot name="editorjsControl" />
    </div>
    <div
        id="editorJs"
        class="editor flex-col mt-8 rounded-lg text-genericDark"
    />
</template>

<style>
.ce-toolbar__content {
    margin: 0 0 0 -65px;

}
.ce-popover__custom-content div{
    display: flex;
    flex-direction: row;
    justify-content: center;
}
.codex-editor .codex-editor__redactor {
    margin-right: 50px !important;
}

.ce-block__content {
    max-width: 100% !important;
    padding: 0.5rem 0.5rem !important;
}

.ce-block {
    margin-bottom: 1rem !important;
    overflow: hidden;
}

.ce-block--focused {
    border: 1px solid #d9d9d9 !important;
    border-radius: 0.3rem !important;
    margin-right: 0 !important;
    padding-right: 0 !important;
    -webkit-box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
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
