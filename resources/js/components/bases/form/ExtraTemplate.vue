<script setup lang="ts">
import ExtraContentHeader from "@/js/components/bases/form/ExtraContentHeader.vue";
import TrixRichEditor from "@/js/components/bases/form/TrixRichEditor.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Add from "@/js/components/svg/Add.vue";
import {watch} from "vue";
import {formService} from "@/js/service/formService";
import {templateFields} from "@/js/components/bases/form/templates/formTemplates";

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

watch(props.data, () =>{
    console.log(props.data)
})
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
                    <!--                    <option-->
                    <!--                        v-for="(templateOpt,index) in availableTemplates"-->
                    <!--                        :key="index + templateIndex"-->
                    <!--                        :value="templateOpt.type"-->
                    <!--                    >-->
                    <!--                        {{ templateOpt.displayText }}-->
                    <!--                    </option>-->
                </select>
                <div
                    v-for="(item, itemIndex) in template.content"
                    :key="itemIndex"
                    class="FormExtraTemplateArrayItem border-[1px] border-gray-300 mb-4 pb-4 rounded-2xl"
                >
                    <pre>{{ item }}</pre>
                    <ExtraContentHeader :click-callback="() => handleClickDeleteItem(templateIndex,itemIndex)">
                        <template #headingLeft>
                            {{ "item " + itemIndex }}
                        </template>
                    </ExtraContentHeader>
                    <div class="formItemBody px-4">
                        <!--  Need to loop Object.keys(item) for v-for="key in item")                      -->
                        <template v-for="(key,index) in Object.keys(item)">
                            <!--                             we have item[key] probably-->
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
                        </template>



                        <!--                        <TextInput-->
                        <!--                            v-if="item?.icon !== undefined"-->
                        <!--                            v-model="item.icon"-->
                        <!--                            :field-id="'IconField_' + itemIndex"-->
                        <!--                            :v$="{}"-->
                        <!--                        >-->
                        <!--                            <template #label>-->
                        <!--                                {{ key }}-->
                        <!--                            </template>-->
                        <!--                        </TextInput>-->
                        <!--                        <TextInput-->
                        <!--                            v-if="item?.heading !== undefined"-->
                        <!--                            v-model="item.heading"-->
                        <!--                            :field-id="'textInputHeading_' + itemIndex"-->
                        <!--                            :v$="{}"-->
                        <!--                        >-->
                        <!--                            <template #label>-->
                        <!--                                Heading-->
                        <!--                            </template>-->
                        <!--                        </TextInput>-->
                        <!--                        <div-->
                        <!--                            v-if="item?.content !== undefined"-->
                        <!--                            class="ContainerTemp my-2 richContent"-->
                        <!--                        >-->
                        <!--                            <TrixRichEditor-->
                        <!--                                label="Content"-->
                        <!--                                :src-content="item.content"-->
                        <!--                                class="border-gray-300"-->
                        <!--                                @input="(contentData) => item.content = contentData"-->
                        <!--                            />-->
                        <!--                        </div>-->
                        <!--                    </div>-->
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
