<script setup>
import ExtraContentHeader from "@/js/components/bases/form/ExtraContentHeader.vue";
import TrixRichEditor from "@/js/components/bases/form/TrixRichEditor.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Add from "@/js/components/svg/Add.vue";
import {number} from "@noble/hashes/_assert";
import {ref, computed, reactive, onMounted, onBeforeMount} from 'vue'

const extraResourceItem = {
    heading: 'test',
    content: ''
}

const props = defineProps({
    data: {
        type: Array,
        required: true
    }
})

const emits = defineEmits(['addNewItem','addNewResource'])

const state = reactive({
    resourceArray: []
})

onBeforeMount(()=>{
    state.resourceArray = props.data
})


const handleClickAddItem = (resourceIndex) => {
    emits('addNewItem', resourceIndex)
}
</script>

<template>
    <div
        v-for="(resource, resourceIndex) in state.resourceArray"
        :key="resourceIndex"
        class="FormExtraResourceContainer border-[1px] border-gray-300 flex flex-col mb-2 pb-4 rounded-2xl"
    >
        <ExtraContentHeader click-callback="handleDeleteResource">
            <template #headingLeft>
                Extra Resource
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
                    class="FormExtraResourceArrayItem border-[1px] border-gray-300 mb-8 rounded-2xl"
                >
                    <ExtraContentHeader click-callback="handleDeleteResource">
                        <template #headingLeft>
                            {{ "item " + (+itemIndex + 1) }}
                        </template>
                    </ExtraContentHeader>
                    <div class="formItemBody px-2">
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
                        :callback="() => handleClickAddItem(resourceIndex)"
                    >
                        <Add class="h-6 mr-2 w-6" />  Add item to resource
                    </GenericButton>
                </div>
            </div>
        </div>
    </div>
</template>
