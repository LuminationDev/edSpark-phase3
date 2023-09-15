import axios from 'axios'

interface ContentItem {
    icon?: string; // Assuming icons are strings, change the type if not
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

// Assuming your simpleData is of this format:
interface SimpleDataItem {
    template: 'Numbereditems' | 'Dateitems' | 'Extraresource';
    content: ContentItem[];
}


export const softwareService = {
    fetchSoftware: () => {
    },

}