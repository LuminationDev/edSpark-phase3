<script setup>
import BaseForm from "@/js/components/bases/form/BaseForm.vue";
import ExtraContent from "@/js/components/bases/form/ExtraContent.vue";
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
