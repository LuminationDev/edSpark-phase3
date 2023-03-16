<script setup>
import EditorJS from '@editorjs/editorjs';
/**
 * EditorJS Plugins
 */
import Header from '@editorjs/header';
import Paragraph from '@editorjs/paragraph';
import List from '@editorjs/list';
import ImageTool from '@editorjs/image';

const editorJsTools = {
    header:{
        class: Header,
        inlineToolbar: true,
        config:{
            placeholder: "Insert Header"
        }
    },
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
    image: {
        class: ImageTool,
        config: {
            actions: [
                {
                    name: 'new_button',
                    icon: '<svg>...</svg>',
                    title: 'New Button',
                    toggle: true,
                    action: (name) => {
                        alert(`${name} button clicked`);
                    }
                }
            ],
            uploader: {
                async uploadByFile(file) {
                    /**
                     * Need to upload with form multipart during save
                     * POST request should do it
                     * TODO - Once API from server is ready
                     */
                }

            }
        },
        toolbox: [
            {
                icon: '<svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.71563 20.625C2.07625 20.625 1.52871 20.4049 1.07301 19.9646C0.618087 19.5236 0.390625 18.9937 0.390625 18.375V2.625C0.390625 2.00625 0.618087 1.47637 1.07301 1.03537C1.52871 0.595125 2.07625 0.375 2.71563 0.375H13.1781V2.625H2.71563V18.375H18.9906V8.25H21.3156V18.375C21.3156 18.9937 21.0882 19.5236 20.6332 19.9646C20.1775 20.4049 19.63 20.625 18.9906 20.625H2.71563ZM16.6656 7.125V4.875H14.3406V2.625H16.6656V0.375H18.9906V2.625H21.3156V4.875H18.9906V7.125H16.6656ZM3.87813 16.125H17.8281L13.4688 10.5L9.98125 15L7.36563 11.625L3.87813 16.125Z" fill="white"/></svg>'
            }
        ]
    }

}

const editor = new EditorJS({
    holder: 'editorJs',
    tools: editorJsTools,
    autofocus: true,
    onReady: () => {
        console.log('EditorJs in the chamber and ready to be emptied 🔫')
    },
    onChange: (api, event) =>{
        // console.log(api.blocks)
        // console.log(event)
        // console.log(event.detail.target.holder.innerText)
        // console.log(event.detail.index + ' is the index emitting the onchange')
    }
})

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


const handleEditorSaveClick = () =>{
    editor.save().then(outputData => {
        console.log('we have ' + JSON.stringify(outputData))
    }).catch(err =>{
        console.log('error has happened ' + err)
    })
}
</script>
<template>
    <div
        id="editorJs"
        class="text-genericDark mt-8 rounded-lg editor"
    />
    <button
        class="w-18 rounded-lg px-2 py-4  bg-slate-500"
        @click="handleEditorSaveClick"
    >
        Save Content
    </button>
</template>