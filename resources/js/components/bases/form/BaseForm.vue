<script setup>
import ImageUploaderForm from "@/js/components/bases/form/ImageUploaderForm.vue";
import TagsInput from "@/js/components/bases/form/TagsInput.vue";
import TrixRichEditor from "@/js/components/bases/form/TrixRichEditor.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import {watchDebounced} from "@vueuse/core";
import {capitalize} from "vue";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {ref, computed, reactive, onBeforeMount} from 'vue'

const props = defineProps({
    baseData:{
        type: Object, required: true
    },
    additionalData: {
        type: Object, required: false, default: () => {
        }
    },
    additionalValidation: {
        type: Object, required: false, default: () => {
        }
    },
    itemType: {
        type: String, required: true
    }
})
const FormAction = {
    CREATE: 'CREATE',
    EDIT: 'EDIT'

}
const FormStatus = {
    UNSAVED: 'UNSAVED',
    AUTOSAVED: 'AUTOSAVED',
    DRAFT: 'DRAFT',
    SAVED: 'SAVED'
}


const emits = defineEmits(['updateParentBaseData'])
/*
Base form will have
title: text input
excerpt: text input
content: rich text editor
cover image: upload
author name:
tags:
 */
const state = reactive({
    title: '',
    excerpt: '',
    content: '',
    coverImage: '',
    authorName: '',
    tags: [],
})

const rules = {
    title: {required},
    excerpt: {required},
    content: {required},
    coverImage: {required},
    authorName: {required},
    tags:{}
}

const v$ = useVuelidate(rules,state)

onBeforeMount(() =>{
    state.title = props.baseData?.title || ''
    state.excerpt = props.baseData?.excerpt || ''
    state.content = props.baseData?.content || ''
    state.coverImage = props.baseData?.cover_image || ''
    state.authorName = props.baseData?. authorName || ''
    state.tags = props.baseData?.tags || []
})

watchDebounced(state, () =>{
    console.log('watchDebounced is called')
    emits('updateParentBaseData', state)
}, {debounce: 1000 , maxWait: 2000})

const handleTrixInputContent = (data) =>{
    v$.value.content.$model = data
}

const handleTrixInputExcerpt = (data) =>{
    v$.value.excerpt.$model = data
}
</script>

<template>
    <div class="BaseFormContainer border-[1px] flex flex-col mt-12 mx-5 p-4 rounded-2xl text-black md:!mx-10 lg:!mx-20">
        <div class="Introduction formHeader my-4">
            {{ capitalize(props.itemType) }} Form
            <slot name="formHeader" />
        </div>
        <TextInput
            ref="titleInputRef"
            v-model="v$.title.$model"
            :v$="v$.title"
            field-id="titleInput"
            class="my-2"
            placeholder=""
        >
            <template #label>
                Title
            </template>
        </TextInput>
        <div class="ContainerTemp my-2 richContent">
            <label> Excerpt</label>
            <TrixRichEditor
                :src-content="v$.excerpt.$model"
                @input="handleTrixInputExcerpt"
            />
        </div>
        <div class="ContainerTemp my-2 richContent">
            <label> Content</label>
            <TrixRichEditor
                :src-content="v$.content.$model"
                class="border-gray-300"
                @input="handleTrixInputContent"
            />
        </div>
        <div class="containerTempImageUploader my-2">
            <label> Cover image (1 image file)</label>
            <ImageUploaderForm :item-type="props.itemType" />
        </div>
        <TextInput
            v-model="v$.authorName.$model"
            :v$="v$.authorName"
            field-id="excerpt input"
            class="my-2"
            placeholder=""
        >
            <template #label>
                Author's name
            </template>
        </TextInput>
        <TagsInput
            v-model="v$.tags.$model"
            :field-id="'tag-selector'"
            :v$="v$.tags"
        >
            <template #label>
                Tag
            </template>
        </TagsInput>
        <div class="itemType">
            <slot name="itemType" />
        </div>
        <div class="extraContentSection">
            <slot name="extraContent" />
        </div>
    </div>
</template>
