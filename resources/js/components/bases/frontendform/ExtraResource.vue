<script setup lang="ts">
import {onBeforeMount,reactive} from 'vue'

import ExtraContentHeader from "@/js/components/bases/frontendform/ExtraContentHeader.vue";
import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Add from "@/js/components/svg/Add.vue";

const props = defineProps({
    data: {
        type: Array,
        required: true
    }
})

const emits = defineEmits(['addNewItem', 'addNewResource', 'deleteResourceAt', 'deleteItemAt'])

const state = reactive({
    resourceArray: []
})

onBeforeMount(() => {
    state.resourceArray = props.data
})

const handleClickAddItem = (resourceIndex: number): void => {
    emits('addNewItem', resourceIndex)
}

const handleClickDeleteResource = (resourceIndex: number): void => {
    emits('deleteResourceAt', resourceIndex)
}

const handleClickDeleteItem = (resourceIndex: number, itemIndex: number): void => {
    emits('deleteItemAt', resourceIndex, itemIndex)
}
</script>

<template>
    <div
        v-for="(resource, resourceIndex) in state.resourceArray"
        :key="resourceIndex"
        class="FormExtraResourceContainer border-[1px] border-gray-300 flex flex-col mb-2 pb-4 rounded-2xl"
    >
        <ExtraContentHeader :click-callback="() => handleClickDeleteResource(resourceIndex)">
            <template #headingLeft>
                Extra Resource {{ " " + (+resourceIndex + 1) }}
            </template>
        </ExtraContentHeader>
        <div class="formBody px-4">
            <TextInput
                v-model="resource.title"
                field-id="resourceTitle"
                :v$="{}"
            >
                <template #label>
                    Title
                </template>
            </TextInput>
            <div class="FormExtraResourceArrayContainer">
                <div
                    v-for="(item, itemIndex) in resource.content"
                    :key="itemIndex"
                    class="FormExtraResourceArrayItem border-[1px] border-gray-300 mb-4 rounded-2xl"
                >
                    <ExtraContentHeader :click-callback="() => handleClickDeleteItem(resourceIndex,itemIndex)">
                        <template #headingLeft>
                            {{ "item " + (+itemIndex + 1) }}
                        </template>
                    </ExtraContentHeader>
                    <div class="formItemBody px-4">
                        <TextInput
                            v-model="item.heading"
                            :field-id="'textInputHeading' + itemIndex"
                            :v$="{}"
                        >
                            <template #label>
                                Heading
                            </template>
                        </TextInput>
                        <div class="ContainerTemp my-2 richContent">
                            <TinyMceRichTextInput
                                :src-content="item.content"
                                @emit-tiny-rich-content="(contentData) => item.content = contentData"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex justify-center flex-row">
                    <GenericButton
                        class="bg-main-teal flex flex-row px-4 py-2"
                        :callback="() => handleClickAddItem(resourceIndex)"
                    >
                        <Add class="h-6 mr-2 w-6" />
                        Add item to resource
                    </GenericButton>
                </div>
            </div>
        </div>
    </div>
</template>
