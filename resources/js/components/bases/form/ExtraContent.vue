<script setup>
import ExtraContentHeader from "@/js/components/bases/form/ExtraContentHeader.vue";
import ExtraResource from "@/js/components/bases/form/ExtraResource.vue";
import ExtraTemplate from "@/js/components/bases/form/ExtraTemplate.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Add from "@/js/components/svg/Add.vue";
import Trash from "@/js/components/svg/Trash.vue";
import {ref, computed, reactive} from 'vue'

const props = defineProps({
    resourceData: {
        type: Array, required: false, default: () => []
    },
    templateData: {
        type: Array, required: false, default: () => []
    }

})

const showSelectorPopup = ref(false)

const extraContentType = [{key: 'resource', value: 'New extra resource'}, {key: 'template', value: 'New template'}]
const resourceItem = {heading: '', content: ''}
const resource = {title: "", content: [Object.assign({},resourceItem)]}
const templateItem = {icon: '', heading: '', content: ''}
const template = {template: 'numbered items', content: [Object.assign({},templateItem)]}
const state = reactive({
    resourceData: props.resourceData,
    templateData: props.templateData
})


const handleClickAddItem = () => {
    console.log('clicked add')
    showSelectorPopup.value = true
}
const handleClickSelectedPopupItem = (item) => {
    console.log(item)
    if(item === 'resource'){
        handleAddNewResource()
    } else if(item === 'template'){
        handleAddNewTemplate()
    }

    showSelectorPopup.value = false
}

const availableAddContent = computed(() => {
    return [{key: 'resource', value: 'New extra resource'}, {key: 'template', value: 'New template'}]
})


const handleAddNewResource = () => {
    state.resourceData.push(resource)
}
const handleAddNewTemplate = () => {
    state.templateData.push(template)
}
const handleAddItemResource = (index) => {
    console.log('item res')
    console.log(index)
    console.log(state.resourceData[index])
    state.resourceData[index].content.push(resourceItem)

}
const handleAddItemTemplate = (index) => {
    console.log('item template')
    state.templateData[index].content.push(templateItem)

}

const handleDeleteResource = (index) => {
    state.resourceData.splice(index, 1)
}
const handleDeleteResourceItem = (resIndex, itemIndex) => {
    state.resourceData[resIndex].content.splice(itemIndex, 1)


}
const handleDeleteTemplate = (index) => {
    state.templateData.splice(index, 1)

}
const handleDeleteTemplateItem = (templateIndex, itemIndex) => {
    state.templateData[templateIndex].content.splice(itemIndex, 1)

}


</script>

<template>
    <div class="border-[1px] border-gray-300 formExtraContentContainer p-2">
        <ExtraContentHeader click-callback="handleDeleteResource">
            <template #headingLeft>
                Extra Content
            </template>
        </ExtraContentHeader>

        <div class="formExtraContentBody">
            <ExtraResource
                :data="state.resourceData"
                @add-new-item="handleAddItemResource"
                @delete-item-at="handleDeleteResourceItem"
                @add-new-resource="handleAddNewResource"
                @delete-resource-at="handleDeleteResource"
            />
            <ExtraTemplate
                :data="state.templateData"
                @add-new-item="handleAddItemTemplate"
                @delete-item-at="handleDeleteTemplateItem"
                @add-new-template="handleAddNewTemplate"
                @delete-template-at="handleDeleteTemplate"
            />
        </div>
        <div
            class="flex justify-center flex-row"
        >
            <div
                class="addNewItemButton border-[1px] border-main-teal flex items-center flex-row gap-2 relative rounded-2xl"
            >
                <GenericButton
                    class="bg-main-teal flex flex-row px-4 py-2"
                    :disabled="showSelectorPopup"
                    :callback="handleClickAddItem"
                >
                    <Add class="h-6 mr-2 w-6" />
                    Add new
                </GenericButton>
                <div
                    v-if="showSelectorPopup"
                    class="absolute top-full left-0 addItemSelectorPopup bg-white rounded-2xl w-48"
                >
                    <ul class="availableAddItemList border-[1px] border-main-darkTeal flex flex-col rounded-xl">
                        <li
                            v-for="(item,index) in availableAddContent"
                            :key="index"
                            class="
                                px-4
                                py-2
                                first:rounded-t-xl
                                hover:bg-main-teal
                                hover:cursor-pointer
                                hover:text-white
                                last:rounded-b-xl
                                "
                            @click="() => handleClickSelectedPopupItem(item.key)"
                        >
                            {{ item.value }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
