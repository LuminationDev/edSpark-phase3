import axios, {AxiosResponse} from 'axios'

import {keyToFieldTypes, templates} from "@/js/components/bases/frontendform/templates/formTemplates";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";


export type TemplateType = 'Extraresource' | 'Numbereditems' | 'Dateitems';

interface ContentItem {
    icon?: string;
    start_date?: string
    content?: string;
    heading?: string;
}

interface TransformedData {
    data: {
        template: string;
        extra_content: {
            [key: string]: {
                item: ContentItem[];
                title?: string;
            };
        };
    };
    type: string;
}

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
        return simpleData.map(item => {
            const matchedTemplate = templates.find(t => t.type === item.template || t.filamentType === item.template);

            if (!matchedTemplate) {
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
                                    ...(contentItem?.icon ? {"icon": contentItem.icon} : {}),
                                    ...(contentItem?.start_date ? {"start_date": contentItem.start_date} : {}),
                                    ...(contentItem?.content ? {"content": contentItem.content} : {}),
                                    ...(contentItem?.heading ? {"heading": contentItem.heading} : {})
                                };
                            }),
                            "title": item.title
                        },
                    }
                },
                "type": "templates"
            };
        }).filter(Boolean); // This filter will remove any undefined items, which might be introduced if a matching template isn't found.
    },
    transformFilamentFormatToSimpleData: (filamentData: TransformedData[]): SimpleDataItem[] => {
        return filamentData.map((item): SimpleDataItem => {
            const template = item.data.template;
            const itemDirectory = Object.keys(item.data.extra_content)[0];
            const content = item.data.extra_content[itemDirectory].item.map((contentItem) => {
                return {
                    ...(contentItem?.icon ? {"icon": contentItem.icon} : {}),
                    ...(contentItem?.start_date ? {"start_date": contentItem.start_date} : {}),
                    ...(contentItem?.content ? {"content": contentItem.content} : {}),
                    ...(contentItem?.heading ? {"heading": contentItem.heading} : {})
                };
            }).filter(Boolean); // Filter out properties with undefined values

            const title = item.data.extra_content[itemDirectory].title;
            return {
                template,
                content,
                title,
            };
        });
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
        let createURL = ''
        let combinedData
        const data = {
            post_title: state.title,
            post_excerpt: state.excerpt,
            post_content: state.content,
            post_status: 'Pending',
            author_id: user_id,
            cover_image: state.cover_image,
            template: ''
        }
        if (itemType === 'software') {
            const formattedAddtionalData = {
                extra_content: formService.transformSimpleDataToFilamentFormat(additionalData['extra_content']),
                softwaretype_id: additionalData['type']
            }
            combinedData = {...data, ...formattedAddtionalData}
            createURL = API_ENDPOINTS.SOFTWARE.CREATE_SOFTWARE_POST
        } else if (itemType === 'advice') {
            const formattedAddtionalData = {
                extra_content: formService.transformSimpleDataToFilamentFormat(additionalData['extra_content']),
                advicetype_id: additionalData['type']
            }
            combinedData = {...data, ...formattedAddtionalData}
            createURL = API_ENDPOINTS.ADVICE.CREATE_ADVICE_POST

        } else if (itemType === 'event') {
            const eventData = {
                event_title: state.title,
                event_excerpt: state.excerpt,
                event_content: state.content,
                event_status: 'Pending',
                author_id: user_id,
                cover_image: state.cover_image,
                template: ''
            }
            const formattedAddtionalData = {
                extra_content: formService.transformSimpleDataToFilamentFormat(additionalData['extra_content']),
                eventtype_id: additionalData['type'],
                start_date: additionalData['startTime'],
                end_date: additionalData['endTime'],
                event_location: JSON.stringify(additionalData['eventLocation'])
            }
            combinedData = {...eventData, ...formattedAddtionalData}
            createURL = API_ENDPOINTS.EVENT.CREATE_EVENT_POST
        }

        return axios.post(createURL, combinedData).then(res => {
            console.log(res)
        })

    }
}