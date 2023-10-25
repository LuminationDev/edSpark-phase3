export const templateFields = {
    NUMBER_FIELD: "NUMBER_FIELD",
    TEXT_FIELD: "TEXT_FIELD",
    RICH_TEXT: "RICH_TEXT",
    ICON_PICKER: "ICON_PICKER",
    DATE_TIME_FIELD: "DATE_TIME_FIELD"
}

export const keyToFieldTypes = {
    heading: templateFields.TEXT_FIELD,
    content: templateFields.RICH_TEXT,
    number: templateFields.NUMBER_FIELD,
    start_date: templateFields.DATE_TIME_FIELD,
    other: templateFields.TEXT_FIELD,
    icon: templateFields.ICON_PICKER
}

export const templates = [
    {
        type: "Extraresource",
        displayText: "Extra Resources",
        filamentType: "App\\Filament\\PageTemplates\\ExtraResource",
        itemDirectory: "extra_resource",
        content: {
            title: templateFields.TEXT_FIELD,
            itemRepeat: {
                heading: templateFields.TEXT_FIELD,
                content: templateFields.RICH_TEXT
            }
        }
    },
    {
        type: 'Numbereditems',
        displayText: "Numbered Items",
        filamentType: "App\\Filament\\PageTemplates\\NumberedList",
        itemDirectory: "numbered_list",
        content: {
            title: templateFields.TEXT_FIELD,
            itemRepeat: {
                number: templateFields.NUMBER_FIELD,
                heading: templateFields.TEXT_FIELD,
                content: templateFields.RICH_TEXT
            }
        }
    },
    {
        type: "Dateitems",
        displayText: "Date and Time Items",
        filamentType: "App\\Filament\\PageTemplates\\DateList",
        itemDirectory: "date_list",
        content: {
            title: templateFields.TEXT_FIELD,
            itemRepeat: {
                start_date: templateFields.DATE_TIME_FIELD,
                heading: templateFields.TEXT_FIELD,
                content: templateFields.RICH_TEXT
            }
        }
    }, {
        type: "Iconlist",
        displayText: "Icon List",
        filamentType: "App\\Filament\\PageTemplates\\IconList",
        itemDirectory: "icon_list",
        content: {
            title: templateFields.TEXT_FIELD,
            itemRepeat: {
                icon: templateFields.ICON_PICKER,
                heading: templateFields.TEXT_FIELD,
                content: templateFields.RICH_TEXT
            }
        }
    },

]