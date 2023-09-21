<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {watchOnce} from "@vueuse/core";
import {storeToRefs} from "pinia";
import {capitalize, ref, watch} from "vue";
import {reactive} from 'vue'

import ImageUploaderForm, {MediaType} from "@/js/components/bases/form/ImageUploaderForm.vue";
import TagsInput from "@/js/components/bases/form/TagsInput.vue";
import TrixRichEditor from "@/js/components/bases/form/TrixRichEditor.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {useAutoSave} from "@/js/composables/useAutoSave";
import {differenceObjects} from "@/js/helpers/jsonHelpers";
import {autoSaveService} from "@/js/service/autoSaveService";
import {formService} from "@/js/service/formService";
import {useUserStore} from "@/js/stores/useUserStore";


const props = defineProps({
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

/**
 * If form is creating, post_id will be set as 0
 * If form is editing, post_id will be set to the edited post's id
 * @type {{CREATE: string, EDIT: string}}
 */
enum FormAction {
    CREATE = 'CREATE',
    EDIT = 'EDIT'
}


const emits = defineEmits<{
    (e: 'baseEmitsAddtContent', content): void
}>()


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
    tags: {}
}

const v$: any = useVuelidate(rules, state)

const currentAction = ref<FormAction>(FormAction.CREATE)

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const {
    formStatusDisplay,
    isSaving,
    autoSaveContent
} = useAutoSave(autoSaveService, currentUser, props.itemType, () => ({...state, ...props.additionalData}), 'BaseFormParent')

const handleTrixInputContent = (data) => {
    v$.value.content.$model = data
}

const handleTrixInputExcerpt = (data) => {
    v$.value.excerpt.$model = data
}

const baseEmitsExtraContent = (): void => {
    const addtContent = differenceObjects(autoSaveContent.value.content,state)
    emits('baseEmitsAddtContent', addtContent)
}

// copy data from autosave to localState. will only run once under watchOnce
const populateLocalStateFromAutoSave = (): void => {
    state.title = autoSaveContent.value.content?.title || ""
    state.excerpt = autoSaveContent.value.content?.excerpt || ""
    state.content = autoSaveContent.value.content?.content || ""
    state.coverImage = autoSaveContent.value.content?.coverImage || ""
    state.authorName = autoSaveContent.value.content?.authorName || ""
    state.tags = autoSaveContent.value.content?.tags || []
}

watchOnce(autoSaveContent, () => {
    if (currentAction.value === FormAction.CREATE) {
        populateLocalStateFromAutoSave()
        baseEmitsExtraContent()
    }
})

const handleReceiveMediaFromUploader = (media : MediaType[]): void =>{
    console.log(media)
    if(media.length === 1){
        state.coverImage = media[0].remoteUrl
    }
}

const handleClickSave = () => {
    console.log("Clicked save")
    try {
        formService.handleSaveForm(state, currentUser.value.id, props.additionalData, props.itemType).then(() => {
            console.log('kinda succedd from base form')
        })
    } catch (e) {
        console.log('failed to create ')
    }

}
</script>

<template>
    <div
        id="BaseFormParent"
        class="BaseFormContainer border-[1px] flex flex-col mt-12 mx-5 p-4 rounded-2xl text-black md:!mx-10 lg:!mx-20"
    >
        <div class="Introduction formHeader my-4">
            {{ capitalize(props.itemType) }} Form {{ isSaving ? "Saving..." : (formStatusDisplay || '') }}
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
            <ImageUploaderForm
                :item-type="props.itemType"
                :current-media="state.coverImage"
                :max="1"
                @emit-uploaded-media="handleReceiveMediaFromUploader"
            />
        </div>
        <!--        <TextInput-->
        <!--            v-model="v$.authorName.$model"-->
        <!--            :v$="v$.authorName"-->
        <!--            field-id="excerpt input"-->
        <!--            class="my-2"-->
        <!--            placeholder=""-->
        <!--        >-->
        <!--            <template #label>-->
        <!--                Author's name-->
        <!--            </template>-->
        <!--        </TextInput>-->
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
        <GenericButton :callback="handleClickSave">
            Save
        </GenericButton>
        <pre> {{ autoSaveContent }}</pre>
    </div>
</template>
