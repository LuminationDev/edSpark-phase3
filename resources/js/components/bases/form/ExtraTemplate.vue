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
    }
})

const emits = defineEmits(['addNewItem', 'addNewTemplate','deleteTemplateAt', 'deleteItemAt'])

const state = reactive({
    templateArray: []
})

onBeforeMount(() => {
    state.templateArray = props.data
})

const handleClickAddItem = (templateIndex) => {
    emits('addNewItem', templateIndex)
}

const handleClickDeleteTemplate = (templateIndex) =>{
    emits('deleteTemplateAt', templateIndex)
}

const handleClickDeleteItem = (templateIndex, itemIndex) =>{
    emits('deleteItemAt', templateIndex, itemIndex)
}
</script>

<template>
    <div
        v-for="(template, templateIndex) in state.templateArray"
        :key="templateIndex"
        class="FormExtratemplateContainer border-[1px] flex flex-col mb-2 pb-4 rounded-2xl"
    >
        <div class="FormExtratemplateArrayContainer">
            <ExtraContentHeader :click-callback="() => handleClickDeleteTemplate(templateIndex)">
                <template #headingLeft>
                    Template
                </template>
            </ExtraContentHeader>
            <div class="formBody px-4">
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
                    <div class="formItemBody px-2">
                        <TextInput
                            v-model="item.icon"
                            :field-id="'IconField_' + itemIndex"
                            :v$="{}"
                        >
                            <template #label>
                                Icon
                            </template>
                        </TextInput>
                        <TextInput
                            v-model="item.heading"
                            :field-id="'textInputHeading_' + itemIndex"
                            :v$="{}"
                        >
                            <template #label>
                                Heading
                            </template>
                        </TextInput>
                        <div class="ContainerTemp my-2 richContent">
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
                        :callback="() => handleClickAddItem(templateIndex)"
                    >
                        <Add class="h-6 mr-2 w-6" />
                        Add item to template
                    </GenericButton>
                </div>
            </div>
        </div>
    </div>
</template>
