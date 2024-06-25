import axios from 'axios'

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {cardDataWithGuid} from "@/js/helpers/cardDataHelper";

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
    fetchAllSoftware: () => {
        return axios.get(API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS,).then(res =>{
            return cardDataWithGuid(res.data)
        })
    },
    fetchSoftwareTypes: () =>{
        return axios.get(API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_TYPES).then(res =>{
            return res.data
        })
    }

}