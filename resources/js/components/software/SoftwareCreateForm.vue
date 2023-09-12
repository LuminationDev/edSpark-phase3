<script setup>
import BaseForm from "@/js/components/bases/form/BaseForm.vue";
import ExtraContent from "@/js/components/bases/form/ExtraContent.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import {reactive, ref} from "vue";

// this will be passed in later -- instead of hardcoding here only temp
// const extraResourceData = reactive([{
//     title: 'extra resource',
//     content: [{heading: 'This is the heading number 1 of the extra resource', content: ''}, {heading: 'dis tha heading number 2', content: ''}]
// }])
// const extraTemplateData = reactive([{
//     template: "numbered items",
//     content: [{icon: "1", heading: 'haha', content: ''}, {icon: "2",heading: 'hehe', content: ''}]
// }])
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

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
        return {
            "data": {
                "template": "App\\Filament\\PageTemplates\\Extraresource",
                "extra_content": {
                    "extraresource": {
                        "item": item.content.map(contentItem => {
                            return {
                                "icon": null,
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
        post_status: 'Published',
        author_id: currentUser.value.id,
        cover_image: '',
        softwaretype_id: 7,
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
</script>


<template>
    <BaseForm
        :base-data="baseData"
        item-type="software"
        @update-parent-base-data="updateParentBaseData"
    >
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
