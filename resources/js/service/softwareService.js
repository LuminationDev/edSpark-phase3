import axios from 'axios'


export const softwareService={
    fetchSoftware: () => {},

    /**
     *  Take simple data array and format them into filament format
     *  Todo: add extra resource title
     * @param simpleData
     * @returns {any}
     */
    transformSimpleDataToFilamentFormat : (simpleData) => {
        return simpleData.map(item => {
            let templatePath = ''
            let itemDirectory = ''
            if (item.template === 'Numbereditems') {
                templatePath = "App\\Filament\\PageTemplates\\Numbereditems"
                itemDirectory = "numbereditems"
            } else if (item.template === 'Dateitems') {
                templatePath = "App\\Filament\\PageTemplates\\Dateitems"
                itemDirectory = "date_items"
            } else if (item.template === 'Extraresource') {
                templatePath = "App\\Filament\\PageTemplates\\Extraresource"
                itemDirectory = "extraresource"
            }
            return {
                "data": {
                    "template": templatePath,
                    "extra_content": {
                        [itemDirectory]: {
                            "item": item.content.map(contentItem => {
                                return {
                                    "icon": contentItem?.icon || null,
                                    "content": contentItem.content,
                                    "heading": contentItem.heading
                                };
                            })
                        }
                    }
                },
                "type": "templates"
            };
        });
    }
}