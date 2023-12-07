import {headingButton, subheadingButton, underlineButton} from "@/js/components/bases/frontendform/EditorButtonIcon";

const alignLeftButtonConfig = {
    icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-align-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M4 12l10 0" /><path d="M4 18l14 0" /></svg>',
    group: 'text',
    position: 'beforeend',
    title: 'Align Left',
    trixAttribute: {
        type: 'block',
        data: {
            styleProperty: 'text-align',
            value: 'left',
            inheritable: true,
            tagName: 'left-div',
            parse: false,
            nestable: false,
            exclusive: true,
        }
    }
};

const alignCenterButtonConfig = {
    icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-align-center" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M8 12l8 0" /><path d="M6 18l12 0" /></svg>',
    group: 'text',
    position: 'beforeend',
    title: 'Align Center',
    trixAttribute: {
        type: 'block',
        data: {
            styleProperty: 'text-align',
            value: 'center',
            inheritable: true,
            tagName: 'center-div',
            parse: false,
            nestable: false,
            exclusive: true,
        }
    }
};

const alignRightButtonConfig = {
    icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-align-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M10 12l10 0" /><path d="M6 18l14 0" /></svg>',
    group: 'text',
    position: 'beforeend',
    title: 'Align Right',
    trixAttribute: {
        type: 'block',
        data: {
            styleProperty: 'text-align',
            value: 'right',
            inheritable: true,
            tagName: 'right-div',
            parse: false,
            nestable: false,
            exclusive: true,
        }
    }
};

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

export {alignRightButtonConfig, alignLeftButtonConfig,alignCenterButtonConfig,underlineButtonConfig, h1ButtonConfig,h2ButtonConfig}