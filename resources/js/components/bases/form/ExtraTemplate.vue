<script setup>
import ExtraContentHeader from "@/js/components/bases/form/ExtraContentHeader.vue";
import TrixRichEditor from "@/js/components/bases/form/TrixRichEditor.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Add from "@/js/components/svg/Add.vue";
import {ref, computed, reactive, onMounted, onBeforeMount} from 'vue'

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    availableTemplates:{
        type: Object, required: false, default: () => {}
    }
})

const emits = defineEmits(['addNewItem', 'addNewTemplate','deleteTemplateAt', 'deleteItemAt','changeTemplateType'])


const handleClickAddItem = (templateIndex, templateType) => {
    emits('addNewItem', templateIndex, templateType)
}

const handleClickDeleteTemplate = (templateIndex) =>{
    emits('deleteTemplateAt', templateIndex)
}

const handleClickDeleteItem = (templateIndex, itemIndex) =>{
    emits('deleteItemAt', templateIndex, itemIndex)
}

const handleTemplateTypeChange = (template,templateIndex) =>{
    console.log('inside template type change')
    const templateType = event.target.value
    emits('changeTemplateType',templateIndex,templateType)
}

</script>

<template>
    <div
        v-for="(template, templateIndex) in props.data"
        :key="templateIndex"
        class="FormExtratemplateContainer border-[1px] flex flex-col mb-2 pb-4 rounded-2xl"
    >
        <div class="FormExtratemplateArrayContainer">
            <ExtraContentHeader :click-callback="() => handleClickDeleteTemplate(templateIndex)">
                <template #headingLeft>
                    Template {{ " " + (+templateIndex + 1) }}
                </template>
            </ExtraContentHeader>
            <div class="formBody px-4">
                <label for="templateDropdown">
                    Choose template type
                </label>
                <select
                    :id="`templateSelector` + templateIndex"
                    class="border-[1px] border-gray-300 mb-4 mr-1 p-2 rounded templateDropdown"
                    @change="() =>handleTemplateTypeChange(template, templateIndex)"
                >
                    <option
                        value=""
                        disabled
                        selected
                    >
                        Please select
                    </option>
                    <option
                        v-for="(templateOpt,index) in availableTemplates"
                        :key="index + templateIndex"
                        :value="templateOpt.type"
                    >
                        {{ templateOpt.displayText }}
                    </option>
                </select>
                <div
                    v-for="(item, itemIndex) in template.content"
                    :key="itemIndex"
                    class="FormExtraTemplateArrayItem border-[1px] border-gray-300 mb-4 pb-4 rounded-2xl"
                >
                    <ExtraContentHeader :click-callback="() =>handleClickDeleteItem(templateIndex,itemIndex)">
                        <template #headingLeft>
                            {{ "item " + itemIndex }}
                        </template>
                    </ExtraContentHeader>
                    <div class="formItemBody px-4">
                        <TextInput
                            v-if="item?.icon !== undefined"
                            v-model="item.icon"
                            :field-id="'IconField_' + itemIndex"
                            :v$="{}"
                        >
                            <template #label>
                                Icon
                            </template>
                        </TextInput>
                        <TextInput
                            v-if="item?.heading !== undefined"
                            v-model="item.heading"
                            :field-id="'textInputHeading_' + itemIndex"
                            :v$="{}"
                        >
                            <template #label>
                                Heading
                            </template>
                        </TextInput>
                        <div
                            v-if="item?.content !== undefined"
                            class="ContainerTemp my-2 richContent"
                        >
                            <TrixRichEditor
                                label="Content"
                                :src-content="item.content"
                                class="border-gray-300"
                                @input="(contentData) => item.content = contentData"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex justify-center flex-row">
                    <GenericButton
                        class="bg-main-teal flex flex-row px-4 py-2"
                        :callback="() => handleClickAddItem(templateIndex, template.template)"
                    >
                        <Add class="h-6 mr-2 w-6" />
                        Add item to template
                    </GenericButton>
                </div>
            </div>
        </div>
    </div>
</template>
