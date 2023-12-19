import axios from 'axios'

interface ContentItem {
    icon?: string;
    content: string;
    heading: string;
}

interface TransformedData {
    data: {
        template: string;
        extra_content: {
            [key: string]: {
                item: ContentItem[];
            };
        };
    };
    type: string;
}

interface SimpleDataItem {
    template: 'Numbereditems' | 'Dateitems' | 'Extraresource';
    content: ContentItem[];
}


export const softwareService = {
    fetchSoftware: () => {
    },

}