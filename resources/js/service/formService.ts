import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import axios, {AxiosResponse} from 'axios'
import {templates, keyToFieldTypes} from "@/js/components/bases/form/templates/formTemplates";
import {addStateToken} from "@okta/okta-auth-js/types/lib/authn/util/stateToken";


export type TemplateType = 'Extraresource' | 'Numbereditems' | 'Dateitems';

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
            title?: string;
        };
    };
    type: string;
}

// Assuming your simpleData is of this format:
interface SimpleDataItem {
    template: TemplateType;
    title?: string;
    content: ContentItem[];
}

const templateFields = {
    TEXT_FIELD: "",
    RICH_TEXT: "",
    NUMBER_FIELD: 0,
    DATE_TIME_FIELD: new Date() // or some default date-time value
};

export const formService = {
    sendAutoSave: async (): Promise<AxiosResponse<any>> => {
        return axios.post(API_ENDPOINTS.AUTOSAVE.AUTOSAVE)
    },
    getAutoSave: async (): Promise<AxiosResponse<any>> => {
        return axios.get(API_ENDPOINTS.AUTOSAVE.AUTOSAVE)
    },
    getTypes: async (url): Promise<AxiosResponse<any>> => {
        return axios.get(url)
    },
    transformSimpleDataToFilamentFormat: (simpleData: SimpleDataItem[]): TransformedData[] => {
        console.error(simpleData)
        return simpleData.map(item => {
            const matchedTemplate = templates.find(t => t.type === item.template || t.filamentType === item.template);

            if (!matchedTemplate) {
                // Handle the case where a matching template isn't found if necessary.
                return;
            }

            const templateFilamentType = matchedTemplate.filamentType;
            const itemDirectory = matchedTemplate.itemDirectory
            return {
                "data": {
                    "template": templateFilamentType,
                    "extra_content": {
                        [itemDirectory]: {
                            "item": item.content.map(contentItem => {
                                return {
                                    "icon": contentItem?.icon || null,
                                    "content": contentItem?.content || null,
                                    "heading": contentItem?.heading || null
                                };
                            }),
                            "title": item.title,
                        }
                    }
                },
                "type": "templates"
            };
        }).filter(Boolean); // This filter will remove any undefined items, which might be introduced if a matching template isn't found.
    },
    generateEmptyTemplate: (type: TemplateType): object => {
        const template = templates.find(t => t.type === type);
        if (!template) return null;

        const createEmptyContent = (contentStructure: any): any => {
            const result = {};

            for (const key in contentStructure) {
                if (key === 'itemRepeat') {
                    result['content'] = [createEmptyContent(contentStructure[key])];
                } else if (typeof contentStructure[key] === 'object' && !Array.isArray(contentStructure[key])) {
                    result[key] = createEmptyContent(contentStructure[key]);
                } else {
                    result[key] = templateFields[contentStructure[key]];
                }
            }
            return result;
        };
        const emptyContent = createEmptyContent(template.content)
        emptyContent['template'] = template.filamentType
        return emptyContent;
    },
    generateEmptyItem: (type: TemplateType): object => {
        const template = templates.find(t => t.type === type || t.filamentType === type);
        if (!template || !template.content.itemRepeat) return null;

        const createEmptyField = (fieldType: keyof typeof templateFields): any => {
            return templateFields[fieldType];
        };

        const result = {};

        for (const key in template.content.itemRepeat) {
            result[key] = createEmptyField(template.content.itemRepeat[key]);
        }

        return result;
    },

    getFieldTypeByKey: (key: string): string => {
        if (keyToFieldTypes[key]) {
            return keyToFieldTypes[key]
        } else {
            return keyToFieldTypes['other']
        }
    },
    handleSaveForm: (state: any, user_id: number, additionalData: any, itemType: string): Promise<void | AxiosResponse<any>> => {
        if (itemType === 'software') {
            const data = {
                post_title: state.title,
                post_excerpt: state.excerpt,
                post_content: state.content,
                post_status: 'Pending',
                author_id: user_id,
                cover_image: state.cover_image,
                template: ''
            }
            const formattedAddtionalData = {
                extra_content: formService.transformSimpleDataToFilamentFormat(additionalData['extraContentData']),
                softwaretype_id: additionalData['selectedSoftwareTypes']
            }
            const combinedData = {...data, ...formattedAddtionalData}
            console.log(combinedData)
            return axios.post(API_ENDPOINTS.SOFTWARE.CREATE_SOFTWARE_POST, combinedData).then(res => {
                console.log(res)
            })
        }

    }
}