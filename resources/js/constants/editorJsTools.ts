import Header from "@editorjs/header";
import ImageTool from "@editorjs/image"
import List from "@editorjs/list";
import Paragraph from "@editorjs/paragraph";
import FontSize from "editorjs-inline-font-size-tool";
import AlignmentTuneTool from 'editorjs-text-alignment-blocktune'

import {IMAGE_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import CustomAttachesTool from "@/js/constants/attachesExtension";

export const editorJsTools = {
    header: {
        class: Header,
        inlineToolbar: true,
        config: {
            placeholder: "Insert Header"
        },
        tunes: ['alignmentTunes']

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
        ],
        tunes: ['alignmentTunes']

    },
    list: {
        class: List,
        inlineToolbar: true,
        tunes: ['alignmentTunes']

    },
    image: {
        class: ImageTool,
        config: {
            endpoints: {
                byFile: IMAGE_ENDPOINTS.IMAGE.IMAGE_UPLOAD_EDITOR_JS
            }
        },
        tunes: ['alignmentTunes']

    },
    video: {
        class: CustomAttachesTool,
        config: {
            endpoint: IMAGE_ENDPOINTS.IMAGE.IMAGE_UPLOAD_EDITOR_JS
        },
        tunes: ['alignmentTunes']
    },
    alignmentTunes: {
        class: AlignmentTuneTool,
        config: {
            default: "left",
            blocks: {
                header: 'center',
                list: 'left',
                paragraph: 'left',
                video: 'center'
            }
        },
    }
}