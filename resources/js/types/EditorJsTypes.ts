export type EditorJSDataType = {
    time: number;
    blocks: EditorJsBlock[];
    version: string;
};

export type EditorJsBlock =
    | HeaderBlock
    | ParagraphBlock
    | ImageBlock
    | VideoBlock
    | ListBlock;

type HeaderBlock = {
    id: string;
    type: 'header';
    data: {
        text: string;
        level: number;
    };
};

type ParagraphBlock = {
    id: string;
    type: 'paragraph';
    data: {
        text: string;
    };
};

type ImageBlock = {
    id: string;
    type: 'image';
    data: {
        file: {
            url: string;
        };
        caption: string;
        withBorder: boolean;
        stretched: boolean;
        withBackground: boolean;
    };
};

type VideoBlock = {
    id: string;
    type: 'video';
    data: {
        file: {
            url: string;
        };
        title: string;
    };
};

type ListBlock = {
    id: string;
    type: 'list';
    data: {
        style: 'ordered' | 'unordered'; // Assuming there's also 'unordered' based on the pattern, adjust if not
        items: string[];
    };
};

export type StateDescriptionType = {
    new: string;
    pending_available: string;
    pending_loaded: string;
}