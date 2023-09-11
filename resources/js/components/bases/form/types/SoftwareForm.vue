<script setup>
import BaseForm from "@/js/components/bases/form/BaseForm.vue";
import ExtraContent from "@/js/components/bases/form/ExtraContent.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {reactive, ref} from "vue";

const props = defineProps({

})

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
const baseData = reactive(createNewBaseData())

const extraContentData = reactive({
    resourceData: [],
    templateData: []
})

const softwareDataToDatabaseFields = () =>
    ({
        post_title: baseData.title,
        post_excerpt: baseData.excerpt,
        post_content: baseData.content,
        post_status: 'Published',
        author_id: 61,
        cover_image: '',
        softwaretype_id: [2],
        template: '',
        extra_content: transformData([...extraContentData.templateData, ...extraContentData.resourceData])
    })

const updateParentExtraContent = (content) => {
    if (content.resourceData) {
        extraContentData.resourceData = content.resourceData
    }
    if (content.templateData) {
        extraContentData.templateData = content.templateData
    }
    console.log({baseData,extraContentData})
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
                @update-parent-extra-content="updateParentExtraContent"
            />
        </template>
    </BaseForm>
</template>
