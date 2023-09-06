<script setup>
import TrixRichEditor from "@/js/components/bases/form/TrixRichEditor.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {ref, computed, reactive, onMounted} from 'vue'

const extraResourceItem = {
    heading: 'test',
    content: ''
}

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
})

const emits = defineEmits([])

const state = reactive({
    resourceTitle: null,
    resourceDataArray: null
})

const rules = {
    resourceTitle: required,
    resourceDataArray: []
}
onMounted(() => {
    state.resourceTitle = props.data.title
    state.resourceDataArray = props.data.content
})
const v$ = useVuelidate(rules, state)


const handleClickAddItem = () => {
    console.log('clicked add')
    state.resourceDataArray.push(extraResourceItem)
}
</script>

<template>
    <div class="FormExtraResourceContainer flex flex-col p-4">
        <TextInput
            field-id="resourceTitle"
            :model-value="v$.resourceTitle.$model"
            :v$="v$.resourceTitle"
        >
            <template #label>
                Resource Title
            </template>
        </TextInput>
        <div class="FormExtraResourceArrayContainer">
            <div
                v-for="(item, index) in state.resourceDataArray"
                :key="index"
                class="FormExtraResourceArrayItem border-2 border-gray-300 my-8 p-4 rounded-2xl"
            >
                <TextInput
                    v-model="item.heading"
                    :field-id="'textInputHeading' + index"
                    :v$="v$"
                >
                    <template #label>
                        Heading {{ index }}
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
            <div @click="handleClickAddItem">
                add more item
            </div>
        </div>
    </div>
</template>
