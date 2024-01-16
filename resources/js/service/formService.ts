import axios, {AxiosResponse} from 'axios'
import {capitalize} from "vue";

import {keyToFieldTypes, templates} from "@/js/components/bases/frontendform/templates/formTemplates";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import Advice from "@/js/models/_advice";
import Software from "@/js/models/_software";
import {GroupedLabel, LabelSelectorItem} from "@/js/types/GlobalLabelTypes";
import {SimpleDataItem, TemplateType, TransformedData} from "@/js/types/PostTypes";

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

    getCreateUrl: (itemType: string) => {
        if (itemType === 'software') {
            return API_ENDPOINTS.SOFTWARE.CREATE_SOFTWARE_POST
        } else if (itemType === 'advice') {
            return API_ENDPOINTS.ADVICE.CREATE_ADVICE_POST
        } else if (itemType === 'event') {
            return API_ENDPOINTS.EVENT.CREATE_EVENT_POST
        }
    },
    formatData: (state, user_id, additionalData, itemType, status): Advice | Event | Software => {
        const commonData = {
            post_title: state.title,
            post_excerpt: state.excerpt,
            post_content: state.content,
            post_status: status,
            author_id: user_id,
            cover_image: state.cover_image,
            tags: state.tags,
            template: '',
            labels: state.labels,
            content_origin: state.content_origin,
            existing_id: state.existing_id
        };

        if (itemType === 'software' || itemType === 'advice' || itemType === 'event') {
            const formattedAdditionalData = {
                extra_content: formService.transformSimpleDataToFilamentFormat(additionalData['extra_content'])
            };

            if (itemType === 'software') {
                formattedAdditionalData.softwaretype_id = additionalData['type'];
            } else if (itemType === 'advice') {
                formattedAdditionalData.advicetype_id = additionalData['type'];
            } else if (itemType === 'event') {
                const eventData = {
                    event_title: state.title,
                    event_excerpt: state.excerpt,
                    event_content: state.content,
                    event_status: status,
                    start_date: additionalData['start_date'],
                    end_date: additionalData['end_date'],
                    event_location: JSON.stringify(additionalData['location']),
                    tags: state.tags,
                    author_id: user_id,
                    cover_image: state.cover_image,
                    labels: state.labels,
                    content_origin: state.content_origin,
                    existing_id: state.existing_id
                };
                formattedAdditionalData.eventtype_id = additionalData['type'];

                return {...eventData, ...formattedAdditionalData};
            }

            return {...commonData, ...formattedAdditionalData};
        }
    },
    sendRequest: (createURL, combinedData): Promise<void | AxiosResponse<any>> => {
        return axios.post(createURL, combinedData).then(res => {
            console.log(res);
        });
    },


    handleSubmitPostForModeration: (state, user_id, additionalData, itemType): Promise<AxiosResponse<any>> => {
        const status = 'Pending'
        const combinedData = formService.formatData(state, user_id, additionalData, itemType, status)
        const createUrl = formService.getCreateUrl(itemType)
        return formService.sendRequest(createUrl, combinedData)
    },
    handleSubmitPostAsDraft: (state, user_id, additionalData, itemType): Promise<AxiosResponse<any>> => {
        const status = 'Draft'
        const combinedData = formService.formatData(state, user_id, additionalData, itemType, status)
        const createUrl = formService.getCreateUrl(itemType)
        return formService.sendRequest(createUrl, combinedData)
    },
    fetchAllLabels: (): Promise<AxiosResponse<any>> => {
        return axios.get(API_ENDPOINTS.LABEL.FETCH_ALL_LABELS)
    },
    groupLabelByType: (data : LabelSelectorItem[]) : GroupedLabel => {
        const groupedData: GroupedLabel = {} as GroupedLabel;

        // Loop through the array and group items by their 'type'
        data.forEach((item) => {
            const { label_type, label_id, label_value, label_description } = item;

            if (!groupedData[label_type]) {
                groupedData[label_type] = [];
            }
            // group to format to match format accepted by multiselectfilter
            groupedData[label_type].push({id: label_id, value: label_value, name: capitalize(label_value) });
        });

        return groupedData;
}
}