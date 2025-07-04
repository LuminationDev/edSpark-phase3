<script setup lang="ts">

import DateTimeInput from "@/js/components/bases/DateTimeInput.vue";
import ExtraContentHeader from "@/js/components/bases/frontendform/ExtraContentHeader.vue";
import {templateFields} from "@/js/components/bases/frontendform/templates/formTemplates";
import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import IconPickerInput from "@/js/components/bases/IconPickerInput.vue";
import NumberInput from "@/js/components/bases/NumberInput.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Add from "@/js/components/svg/Add.vue";
import {formService} from "@/js/service/formService";

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    availableTemplates: {
        type: Object, required: false, default: () => {
        }
    }
})

const emits = defineEmits<{
    (e: 'addNewItem', index: number, type: string)
    (e: 'addNewTemplate', index: number, type: string)
    (e: 'deleteItemAt', templateIndex: number, itemIndex: number)
    (e: 'deleteTemplateAt', templateIndex: number)
    (e: 'changeTemplateType', index: number, type: string)
}>()

const handleClickAddItem = (templateIndex, templateType): void => {
    emits('addNewItem', templateIndex, templateType)
}

const handleClickDeleteTemplate = (templateIndex): void => {
    emits('deleteTemplateAt', templateIndex)
}

const handleClickDeleteItem = (templateIndex, itemIndex): void => {
    emits('deleteItemAt', templateIndex, itemIndex)
}

const handleTemplateTypeChange = (
    template: string,
    templateIndex: number
): void => {
    const target = event.target as HTMLInputElement;
    const templateType = target.value;
    emits('changeTemplateType', templateIndex, templateType);
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
                    Template type
                </label>
                <select
                    :id="`templateSelector` + templateIndex"
                    class="border-[1px] border-gray-300 mb-4 mr-1 p-2 rounded templateDropdown"
                    @change="() =>handleTemplateTypeChange(template, templateIndex)"
                >
                    <option
                        :value="template.template"
                        disabled
                        selected
                    >
                        {{ template.template.split("\\").slice(-1)[0] }}
                    </option>
                </select>
                <TextInput
                    v-if="template.title !== undefined"
                    v-model="template.title"
                    field-id="title"
                    :v$="{}"
                >
                    <template #label>
                        Title
                    </template>
                </TextInput>
                <div
                    v-for="(item, itemIndex) in template.content"
                    :key="itemIndex"
                    class="FormExtraTemplateArrayItem border-[1px] border-gray-300 mb-4 pb-4 rounded-2xl"
                >
                    <ExtraContentHeader :click-callback="() => handleClickDeleteItem(templateIndex,itemIndex)">
                        <template #headingLeft>
                            {{ "item " + itemIndex }}
                        </template>
                    </ExtraContentHeader>
                    <div class="formItemBody px-4">
                        <!--  Need to loop Object.keys(item) for v-for="key in item")                      -->
                        <template v-for="(key,index) in Object.keys(item)">
                            <template v-if="formService.getFieldTypeByKey(key) === templateFields.TEXT_FIELD">
                                <TextInput
                                    :key="itemIndex + index"
                                    v-model="item[key]"
                                    :field-id="'key' + itemIndex"
                                    :v$="{}"
                                >
                                    <template #label>
                                        <span class="capitalize">{{ key }}</span>
                                    </template>
                                </TextInput>
                            </template>
                            <template v-if="formService.getFieldTypeByKey(key) === templateFields.ICON_PICKER">
                                <div
                                    :key="itemIndex + index"
                                    class="flex justify-center iconPickerContainer"
                                >
                                    <div
                                        class="
                                            border-[1px]
                                            flex
                                            justify-center
                                            items-center
                                            h-24
                                            iconPickerButton
                                            rounded-xl
                                            w-36
                                            "
                                    >
                                        <IconPickerInput
                                            v-model="item[key]"
                                            class="grid place-items-center h-full w-full"
                                        />
                                    </div>
                                </div>
                            </template>
                            <template v-if="formService.getFieldTypeByKey(key) === templateFields.NUMBER_FIELD">
                                <NumberInput
                                    :key="itemIndex + index"
                                    v-model="item[key]"
                                    :field-id="'key' + itemIndex"
                                    :v$="{}"
                                >
                                    <template #label>
                                        <span class="capitalize">{{ key }}</span>
                                    </template>
                                </NumberInput>
                            </template>
                            <template v-if="formService.getFieldTypeByKey(key) === templateFields.DATE_TIME_FIELD">
                                <DateTimeInput
                                    :key="itemIndex + index"
                                    v-model="item[key]"
                                    :field-id="'key' + itemIndex"
                                    :v$="{}"
                                >
                                    <template #label>
                                        <span class="capitalize">{{ key }}</span>
                                    </template>
                                </DateTimeInput>
                            </template>
                            <template v-if="formService.getFieldTypeByKey(key) === templateFields.RICH_TEXT">
                                <div
                                    :key="itemIndex + index"
                                    class="ContainerTemp my-2 richContent"
                                >
                                    <TinyMceRichTextInput
                                        :src-content="item.content"
                                        @emit-tiny-rich-content="(contentData) => item.content = contentData"
                                    />
                                    <!--                                    <TrixRichEditorInput-->
                                    <!--                                        label="Content"-->
                                    <!--                                        :src-content="item.content"-->
                                    <!--                                        class="border-gray-300"-->
                                    <!--                                        @input="(contentData) => item.content = contentData"-->
                                    <!--                                    />-->
                                </div>
                            </template>
                        </template>
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
    </div>
</template>
