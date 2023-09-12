<script setup>
import BaseForm from "@/js/components/bases/form/BaseForm.vue";
import ExtraContent from "@/js/components/bases/form/ExtraContent.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import ItemTypeCheckboxes from "@/js/components/selector/ItemTypeCheckboxes.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import {reactive, ref} from "vue";
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)
const selectedSoftwareTypes = ref([])

// fetch software types

const templates = [
    {
        type: "Extraresource",
        displayText: "Extra Resources",
        value: "App\\Filament\\PageTemplates\\Extraresource"
    },
    {
        type: 'Numbereditems',
        displayText: "Numbered Items",
        value: "App\\Filament\\PageTemplates\\Numbereditems"
    },
    {
        type: "Dateitems",
        displayText: "Date and Time Items",
        value: "App\\Filament\\PageTemplates\\dateItems"
    }
]

const createNewBaseData = () => {
    return {
        title: '',
        excerpt: '',
        content: '',
        coverImage: '',
        authorName: '',
        tags: []
    }
}
/*
 * Transform data into Filament friendly's format to ensure editability from backend before saving
 */
const transformData = (simpleData) => {
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
};


const softwareDataToDatabaseFields = () =>
    ({
        post_title: baseData.title,
        post_excerpt: baseData.excerpt,
        post_content: baseData.content,
        post_status: 'Pending',
        author_id: currentUser.value.id,
        cover_image: '',
        softwaretype_id: selectedSoftwareTypes.value,
        template: '',
        extra_content: transformData([...extraContentData.templateData, ...extraContentData.resourceData])
    })

const baseData = reactive(createNewBaseData())
const extraContentData = reactive({
    resourceData: [],
    templateData: []
})

const updateParentExtraContent = (content) => {
    if (content.resourceData) {
        extraContentData.resourceData = content.resourceData
    }
    if (content.templateData) {
        extraContentData.templateData = content.templateData
    }
}

const updateParentBaseData = (content) => {
    baseData.title = content.title
    baseData.excerpt = content.excerpt
    baseData.content = content.content
    baseData.coverImage = content.coverImage
    baseData.authorName = content.authorName
    baseData.tags = content.tags
}

const printTransformedData = () => {
    const transformedData = softwareDataToDatabaseFields()
    console.log(transformedData)
    axios.post(API_ENDPOINTS.SOFTWARE.CREATE_SOFTWARE_POST, transformedData).then(res => {
        console.log(res)
    })
}

const handleReceiveTypes = (typeArray) => {
    selectedSoftwareTypes.value = typeArray
}
</script>


<template>
    <BaseForm
        :base-data="baseData"
        item-type="software"
        @update-parent-base-data="updateParentBaseData"
    >
        <template #itemType>
            <ItemTypeCheckboxes
                :type-api-link="API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_TYPES"
                label="Select software type"
                @send-selected-types-as-array="handleReceiveTypes"
            />
        </template>
        <template #extraContent>
            <ExtraContent
                :extra-content-data="extraContentData"
                :available-templates="templates"
                @update-parent-extra-content="updateParentExtraContent"
            />
        </template>
    </BaseForm>
    <GenericButton :callback="printTransformedData">
        Print to console
    </GenericButton>
</template>
