<script setup lang="ts">
import {onClickOutside, watchDebounced, watchOnce} from "@vueuse/core";
import {reactive, Ref, ref} from 'vue'

import ExtraContentHeader from "@/js/components/bases/frontendform/ExtraContentHeader.vue";
import ExtraTemplate from "@/js/components/bases/frontendform/ExtraTemplate.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Add from "@/js/components/svg/Add.vue";
import {formService, TemplateType} from "@/js/service/formService";

const props = defineProps({
    extraContentData: {
        type: Object, required: true
    },
    availableTemplates: {
        type: Object, required: false, default: () => {
        }
    }
})

const state = reactive({
    templateData: props.extraContentData
})

const emits = defineEmits(['updateParentExtraContent'])


const showSelectorPopup = ref<boolean>(false)
const addExtraContentItemSelector: Ref<HTMLDivElement | null> = ref(null);

const populateLocalStateFromBaseProps = (): void => {
    state.templateData = props.extraContentData
}

watchOnce(props, () => {
    console.log('inside extraContet watcher once')
    populateLocalStateFromBaseProps()
}, {deep: true})

watchDebounced(state, () => {
    emits('updateParentExtraContent', state.templateData)
}, {debounce: 1000, maxWait: 2000})

onClickOutside(addExtraContentItemSelector, () => {
    showSelectorPopup.value = false
})


const handleClickAddItem = (): void => {
    showSelectorPopup.value = true
}

const handleClickSelectedPopupItem = (templateType: TemplateType) => {
    const generatedTemplate = formService.generateEmptyTemplate(templateType)
    state.templateData.push(generatedTemplate)

    showSelectorPopup.value = false
}

const handleAddNewTemplate = () => {
    state.templateData.push({template: '', content: []});
}

const handleAddItemTemplate = (index, templateType) => {
    const newTemplateItem = formService.generateEmptyItem(templateType)
    state.templateData[index].content.push(newTemplateItem);
}

const handleDeleteTemplate = (index) => {
    state.templateData.splice(index, 1)
}

const handleDeleteTemplateItem = (templateIndex, itemIndex) => {
    state.templateData[templateIndex].content.splice(itemIndex, 1)
}
const handleChangeTemplateType = (templateIndex, templateNewType) => {
    state.templateData[templateIndex].template = templateNewType
    state.templateData[templateIndex].content = [formService.generateEmptyItem(templateNewType)]

}

</script>

<template>
    <div class="formExtraContentContainer">
        <ExtraContentHeader>
            <template #headingLeft>
                Extra Content
            </template>
        </ExtraContentHeader>
        <div class="border-[1px] border-gray-300 p-4 rounded-lg">
            <div class="formExtraContentBody px-4">
                <ExtraTemplate
                    :data="state.templateData"
                    :available-templates="props.availableTemplates"
                    @change-template-type="handleChangeTemplateType"
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
                    class="addNewItemButton border-[1px] border-main-teal flex items-center flex-row relative rounded-xl  gap-2"
                >
                    <GenericButton
                        class="bg-main-teal flex flex-row px-12 py-2"
                        :disabled="showSelectorPopup"
                        :callback="handleClickAddItem"
                    >
                        <Add class="h-6 mr-2 w-6" />
                        Add new
                    </GenericButton>
                    <div
                        v-if="showSelectorPopup"
                        ref="addExtraContentItemSelector"
                        class="absolute top-full left-0 addItemSelectorPopup bg-white bg-white rounded-2xl w-48 z-50"
                    >
                        <ul class="availableAddItemList border-[1px] border-main-darkTeal flex flex-col rounded-xl">
                            <li
                                v-for="(item,index) in props.availableTemplates"
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
                                @click="() => handleClickSelectedPopupItem(item.type)"
                            >
                                {{ item.displayText }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
