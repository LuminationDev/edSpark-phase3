<script setup>
import BaseForm from "@/js/components/bases/form/BaseForm.vue";
import ExtraContent from "@/js/components/bases/form/ExtraContent.vue";
import ExtraResource from "@/js/components/bases/form/ExtraResource.vue";
import ExtraTemplate from "@/js/components/bases/form/ExtraTemplate.vue";
import {reactive, ref} from "vue";

// this will be passed in later -- instead of hardcoding here only temp
const extraResourceData = reactive([{
    title: 'extra resource',
    content: [{heading: 'This is the heading number 1 of the extra resource', content: ''}, {heading: 'dis tha heading number 2', content: ''}]
}])
const extraTemplateData = reactive([{
    template: "numbered items",
    content: [{icon: "1", heading: 'haha', content: ''}, {icon: "2",heading: 'hehe', content: ''}]
}])

const handleUpdateResourceAndTemplate = () => {
    console.log('updating highest level tempalte and Resource')
}
</script>

<template>
    <BaseForm item-type="software">
        <template #extraContent>
            <ExtraContent
                :resource-data="extraResourceData"
                :template-data="extraTemplateData"
            />
        </template>
    </BaseForm>
</template>
