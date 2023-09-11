<script setup>
import BaseForm from "@/js/components/bases/form/BaseForm.vue";
import ExtraContent from "@/js/components/bases/form/ExtraContent.vue";
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

const updateParentExtraContent = (content) => {
    console.log('updating highest level tempalte and Resource')
    if (content.resourceData) {
        extraContentData.resourceData = content.resourceData
    }
    if (content.templateData) {
        extraContentData.templateData = content.templateData
    }
    console.log(extraContentData)
}

</script>


<template>
    <BaseForm
        :base-data="baseData"
        item-type="software"
    >
        <template #extraContent>
            <ExtraContent
                :extra-content-data="extraContentData"
                @update-parent-extra-content="updateParentExtraContent"
            />
        </template>
    </BaseForm>
</template>
