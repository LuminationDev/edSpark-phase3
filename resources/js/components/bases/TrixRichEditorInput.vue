<script setup>
// https://gist.github.com/nomadjimbob/db6d3cdb630a931722595f9adf75ca53ss - taken from this link by nomadjimbob


import Trix from 'trix'
import {computed, ref, watch} from 'vue';

import {headingButton, subheadingButton, underlineButton} from "@/js/components/bases/frontendform/EditorButtonIcon";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

const props = defineProps({
    disabledEditor: {
        type: Boolean,
        required: false,
        default: false
    },
    srcContent: {
        type: String,
        required: false,
        default: ''
    },
    inputId: {
        type: String,
        required: false,
        default: ''
    },
    inputName: {
        type: String,
        required: false,
        default: 'content'
    },
    placeholder: {
        type: String,
        required: false,
        default: ''
    },
    label: {
        type: String,
        required: false,
        default: ''
    },
    required: {
        type: Boolean,
        required: false
    },
})
const trix = ref(null);
const editorContent = ref(props.srcContent)
const isActive = ref(null)
const isInitalized = ref(false)
const initalizeQueue = ref([])
const emits = defineEmits(['input', 'update', 'update:srcContent', 'trix-file-accept', 'trix-attachment-add', 'trix-attachment-remove', 'trix-selection-change', 'trix-initialize', 'trix-before-initialize', 'trix-focus', 'trix-blur'])

const handleContentChange = (event) => {
    editorContent.value = event.srcElement ? event.srcElement.value : event.target.value
    emits('input', editorContent.value)
}
const handleInitialize = () => {
    isInitalized.value = true
    initalizeQueue.value.forEach(item => item())
    decorateDisabledEditor(props.disabledEditor)
    emits('trix-initialize')
}
const handleInitialContentChange = (newContent, oldContent) => {
    newContent = newContent === undefined ? '' : newContent
    if (trix.value && trix.value.innerHTML !== newContent) {
        editorContent.value = newContent
    }
    if (!isActive.value) {
        reloadEditorContent(editorContent.value)
    }
}

const handleEmbedVideo = () => {
    // Create a container for the modal
    const modalContainer = document.createElement('div');
    modalContainer.classList.add('embed-modal');

    // Find the position of the embed video button
    const embedToolbarButton = document.querySelector('.trix-button[data-trix-attribute="embedVideo"]');
    const buttonRect = embedToolbarButton.getBoundingClientRect();

    // Set the position of the modal
    modalContainer.style.position = 'absolute';
    modalContainer.style.top = `${buttonRect.bottom + window.scrollY}px`;
    modalContainer.style.left = `${buttonRect.left + window.scrollX}px`;

    // Create input field
    const inputField = document.createElement('input');
    inputField.setAttribute('type', 'text');
    inputField.setAttribute('placeholder', 'Insert link to images or video');

    // Create Embed and Cancel buttons
    const embedButton = document.createElement('button');
    embedButton.textContent = 'Embed';
    embedButton.addEventListener('click', () => {
        const videoUrl = inputField.value;
        if (videoUrl) {
            const attachment = new Trix.Attachment({
                content: `<iframe src="${videoUrl}" width="560" height="315" frameborder="0" allowfullscreen></iframe>`,
                contentType: 'application/x-trix-attachment-embed',
                embedContent: true,
            });

            trix.value.editor.insertAttachment(attachment);
            editorContent.value = trix.value.editor.getDocument().toString();
        }

        // Remove the modal after embedding or canceling
        modalContainer.remove();
    });

    const cancelButton = document.createElement('button');
    cancelButton.textContent = 'Cancel';
    cancelButton.addEventListener('click', () => {
        // Remove the modal if canceled
        modalContainer.remove();
    });

    // Append elements to the modal container
    modalContainer.appendChild(inputField);
    modalContainer.appendChild(embedButton);
    modalContainer.appendChild(cancelButton);

    // Append the modal container to the body
    document.body.appendChild(modalContainer);
};


const emitEditorState = (value) => {
    emits('update', editorContent.value)
    emits('update:srcContent', editorContent.value)
}
const emitFileAccept = (file) => {
    emits('trix-file-accept', file)
}
const emitAttachmentAdd = async (event) => {
    const attachment = event.attachment;

    if (!attachment.file && attachment.getURL()) {
        try {
            const response = await fetch(attachment.getURL());
            const blob = await response.blob();

            // Form data for uploading image
            const formData = new FormData();
            formData.append('image', blob, `${randomId()}.jpg`);

            // Upload the image to your endpoint
            const uploadResponse = await fetch(API_ENDPOINTS.IMAGE.IMAGE_UPLOAD_EDITOR_JS, {
                method: 'POST',
                body: formData
            });

            if (!uploadResponse.ok) {
                throw new Error('Failed to upload image');
            }

            const data = await uploadResponse.json();
            const imageUrl = data.file.url;

            // Set the new image URL as the attachment attributes
            attachment.setAttributes({
                url: imageUrl,
                href: imageUrl
            });
            console.log(imageUrl)

        } catch (error) {
            console.error('Error processing the image:', error);
            // Handle the error further if needed
        }
    }
}
const emitAttachmentRemove = (file) => {
    emits('trix-attachment-remove', file)
}
const emitSelectionChange = (event) => {
    emits('trix-selection-change', trix.value.editor, event)
}
const emitBeforeInitialize = async (event) => {
    whenInitalized(() => {
        emits('trix-before-initialize', trix.value.editor, event)
    })
}
const processTrixFocus = (event) => {
    isActive.value = true
    emits('trix-focus', trix.value.editor, event)
}
const processTrixBlur = (event) => {
    isActive.value = false
    emits('trix-blur', trix.value.editor, event)
}
const whenInitalized = (func) => {
    if (isInitalized.value) {
        func()
    } else {
        initalizeQueue.value.push(func)
    }
}
const reloadEditorContent = async (newContent) => {
    whenInitalized(() => {
        trix.value.editor.loadHTML(newContent)
        trix.value.editor.setSelectedRange(getContentEndPosition())
    })
}
const decorateDisabledEditor = async (editorState) => {
    whenInitalized(() => {
        if (editorState) {
            trix.value.toolbarElement.style['pointer-events'] = 'none'
            trix.value.contentEditable = false
            trix.value.style['background'] = '#e9ecef'
        } else {
            trix.value.toolbarElement.style['pointer-events'] = 'unset'
            trix.value.style['pointer-events'] = 'unset'
            trix.value.style['background'] = '#ffffff'
        }
    })
}
const getContentEndPosition = () => {
    return trix.value.editor.getDocument().toString().length - 1
}
const randomId = () => {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16)
    })
}
const generatedId = computed(() => {
    return randomId()
})
const computedId = computed(() => {
    return props.inputId || generatedId.value
})
const initialContent = computed(() => {
    return props.srcContent
})
const isDisabled = computed(() => {
    return props.disabledEditor
})
watch(editorContent, emitEditorState)
watch(initialContent, handleInitialContentChange)
watch(isDisabled, decorateDisabledEditor)
/** Extra Toolbar Buttons **/
const buildButtonHtml = (options, name, func) => {
    const style = options.customStyle ? `style="${options.customStyle}"` : '';
    const buttonClass = options.divWrap ? '' : `class="trix-button trix-button--icon"`;
    const onClickAttr = func ? `onClick="Trix.$extensions.${name}(event, '${name}', '${options.id}', 'click')"` : '';
    const titleAttr = options.title ? `title="${options.title}"` : '';

    return `
        ${options.divWrap ? '<div style="position:relative" class="trix-button trix-button--icon">' : ''}
            <button type="button" ${style} ${buttonClass} data-trix-attribute="${name}" ${onClickAttr} ${titleAttr}>
                ${options.icon}
            </button>
            ${options.html || ''}
        ${options.divWrap ? '</div>' : ''}
    `;
}

const addToolbarButton = async (name, options, func) => {
    const {
        icon = '?',
        group = 'text',
        position = 'beforeend',
        id = randomId(),
        trixAttribute,
        html,
        ...otherOptions
    } = options;

    whenInitalized(() => {
        if (trixAttribute?.type && trixAttribute?.data && Trix.config[trixAttribute.type + 'Attributes']) {
            Trix.config[trixAttribute.type + 'Attributes'][name] = trixAttribute.data;
        }

        let processedHtml = html?.replace(/%id%/ig, id);
        if (func) {
            processedHtml = processedHtml?.replace(/%func\((.*?)\)%/ig, `Trix.$extensions.${name}(event, '${name}', '${id}', $1)`);
            if (!Trix.$extensions) Trix.$extensions = {};
            Trix.$extensions[name] = func;
        }

        const toolbarId = trix.value.getAttribute('toolbar');
        document.getElementById(toolbarId)
            .querySelector(`.trix-button-group.trix-button-group--${group}-tools`)
            .insertAdjacentHTML(position, buildButtonHtml({...otherOptions, icon, html: processedHtml}, name, func));
    });
}


const underlineButtonConfig = {
    icon: underlineButton,
    group: 'text',
    position: 'beforeend',
    title: 'Underline',
    trixAttribute: {
        type: 'text',
        data: {
            styleProperty: 'textDecoration',
            value: 'underline',
            inheritable: true
        }
    }
};
const h1ButtonConfig = {
    icon: headingButton,
    group: 'text',
    position: 'beforeend',
    customStyle: "width:80px",
    title: 'Heading 1',
    trixAttribute: {
        type: 'block',
        data: {
            tagName: 'h1'
        }
    }
};

const h2ButtonConfig = {
    icon: subheadingButton,
    group: 'text',
    position: 'beforeend',
    customStyle: "width:100px",
    title: 'Heading 2',
    trixAttribute: {
        type: 'block',
        data: {
            tagName: 'h2'
        }
    }
};

const embedVideoButtonConfig = {
    icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-paperclip" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" /></svg>`,
    group: 'block',
    position: 'beforeend',
    title: 'Embed Video',
};


addToolbarButton('underline', underlineButtonConfig)
addToolbarButton('h1', h1ButtonConfig);
addToolbarButton('h2', h2ButtonConfig);
addToolbarButton('embedVideo', embedVideoButtonConfig, handleEmbedVideo);

</script>
<template>
    <div class="editor">
        <label
            v-if="label"
            class="ml-2"
            :class="{required: required}"
        >{{ label }}</label>
        <trix-editor
            ref="trix"
            :contenteditable="!disabledEditor"
            :class="['trix-content'] "
            :placeholder="placeholder"
            :input="computedId"
            @trix-change="handleContentChange"
            @trix-file-accept="emitFileAccept"
            @trix-attachment-add="emitAttachmentAdd"
            @trix-attachment-remove="emitAttachmentRemove"
            @trix-selection-change="emitSelectionChange"
            @trix-initialize="handleInitialize"
            @trix-before-initialize="emitBeforeInitialize"
            @trix-focus="processTrixFocus"
            @trix-blur="processTrixBlur"
        />
        <input
            :id="computedId"
            type="hidden"
            :name="inputName"
            :value="editorContent"
        >
    </div>
</template>


<style lang="scss">
/* Extra Trix Styles to support the above code*/
.trix-button-group {
    .trix-button {
        text-align: -webkit-center;
        text-align: -moz-center;

        svg {
            height: 20px;
            width: 20px;
            opacity: 0.8;
            display: block;

        }

        /* For buttons inside divWrap option */
        button {
            display: block;
            border: none;
            background: transparent;
            height: 100%;
            width: 100%;
        }
    }
}

/* My own theme */
.editor {
    width: 100%;

    trix-editor {
        border-radius: 0 0 10px 10px;
        border-color: gray;
        min-height: 6rem;
        padding: 6px 12px;
        background-color: #fff;
        a{
            text-decoration: underline;
        }
        s {
            text-decoration: line-through;
        }

        strong {
            font-weight: bolder;
        }

        em {
            font-style: italic;
        }

        code {
            font-family: monospace;
            background-color: #f0f0f0; /* You can adjust the background color for code blocks */
            padding: 0.2em 0.4em;
            border-radius: 4px;
        }

        pre {
            white-space: pre-wrap;
            background-color: #f0f0f0; /* You can adjust the background color for code blocks */
            padding: 0.5em;
            border-radius: 4px;
        }

        blockquote {
            border-left: 2px solid #999; /* You can adjust the color and width of the blockquote border */
            margin: 0;
            padding-left: 1em;
        }

        ul {
            list-style: disc;

            ul {
                list-style: circle;
            }

            ol {
                list-style: decimal;
            }
        }

        ol {
            list-style: decimal;

            ul {
                list-style: disc;
            }

            ol {
                list-style: upper-alpha;
            }
        }

    }


    .trix-button-group {
        margin-top: 5px;
        margin-bottom: 5px;
        margin-right: 10px;
        border-radius: 10px;
        border: 0;
        background-color: #fff;

        .trix-button {
            margin-right: 4px;
            margin-left: 4px;
            min-height: 30px;
            min-width: 40px;
            border: 0;

            &::before {
                background-size: 50%;
            }

            &:hover:not(:disabled) {
                background-color: rgba(0, 0, 0, 0.1);
                border-radius: 10px;
            }
        }
    }

    trix-toolbar .trix-button--icon {
        max-width: 100% !important;
    }

    trix-toolbar .trix-button.trix-active {
        border-radius: 10px !important;
    }

    .trix-button-row {
        border: 1px solid gray;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }


    .trix-button--icon-increase-nesting-level,
    .trix-button--icon-decrease-nesting-level,
    .trix-button--icon-heading-1,
    .trix-button--icon-attach,
    {
        display: none;
    }
}

trix-editor .attachment__toolbar .trix-button-row {
    border: 0 !important
}


.embed-modal {
    position: absolute;
    padding: 10px;
    background-color: #fff;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.embed-modal input {
    width: 100%;
    margin-bottom: 10px;
}
</style>

