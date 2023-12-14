<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {maxLength, required} from "@vuelidate/validators";
import {storeToRefs} from "pinia";
import {capitalize, computed, onBeforeMount, onMounted, reactive, ref} from "vue";
import {useRouter} from "vue-router";
import {toast} from "vue3-toastify";

import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import ImageUploaderInput, {MediaType} from "@/js/components/bases/ImageUploaderInput.vue";
import TagsInput from "@/js/components/bases/TagsInput.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {FormStatus, useAutoSave} from "@/js/composables/useAutoSave";
import useErrorMessage from "@/js/composables/useErrorMessage";
import {differenceObjects} from "@/js/helpers/jsonHelpers";
import {autoSaveService} from "@/js/service/autoSaveService";
import {formService} from "@/js/service/formService";
import {useUserStore} from "@/js/stores/useUserStore";
import {AdviceAdditionalData} from "@/js/types/AdviceTypes";
import {EventAdditionalData} from "@/js/types/EventTypes";
import {SoftwareAdditionalData} from "@/js/types/SoftwareTypes";

const props = defineProps({
    additionalData: {
        type: Object as () => SoftwareAdditionalData | AdviceAdditionalData | EventAdditionalData,
        required: false,
        default: () => {
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
    cover_image: '',
    author_name: '',
    tags: [],
})

const rules = {
    title: {required},
    excerpt: {
        required,
        maxLength: maxLength(150)
    },
    content: {required},
    cover_image: {required},
    tags: {}
}



const v$ = useVuelidate(rules, state)

const currentAction = ref<FormAction>(FormAction.CREATE)
const {error, setError, clearError} = useErrorMessage()
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)
const router = useRouter()
const {
    formStatusDisplay,
    isSaving,
    autoSaveContent, loadAutoSaveData
} = useAutoSave(autoSaveService, currentUser, props.itemType, () => ({...state, ...props.additionalData}), 'BaseFormParent')

const populateLocalStateFromWindowStateDraftData = (data): void => {
    state.title = data.title || ""
    state.excerpt = data.excerpt || ""
    state.content = data.content || ""
    state.cover_image = data.cover_image || ""
    state.author_name = data.authorName || ""
    state.tags = data.tags || []
}

const baseEmitsExtraContentFromDraftData = (data): void => {
    const addtContent = differenceObjects(data, state)
    emits('baseEmitsAddtContent', addtContent)
}

onBeforeMount(() => {
    if (window.history.state.draftContent) {
        const draftData = JSON.parse(window.history.state.draftContent)
        populateLocalStateFromWindowStateDraftData(draftData)
        baseEmitsExtraContentFromDraftData(draftData)
    }
})
onMounted(() =>{
    formService.fetchAllLabels().then(res =>{
        console.log(res.data.data)
    })
})

const handleReceiveMediaFromUploader = (media: MediaType[]): void => {
    console.log(media)
    if (media && media.length === 1 && media[0]) {
        v$.value.cover_image.$model = media[0].remoteUrl
    } else {
        v$.value.cover_image.$model = null
    }
}

const handleClickSubmitForModeration = () => {
    isSaving.value = true
    v$.value.$validate();
    if (!v$.value.$errors) {
        console.log(v$.value.$errors)
        setError(1, 'Please provide title, tagline, content and cover image')
        isSaving.value = false
        return
    }
    formService.handleSubmitPostForModeration(state, currentUser.value.id, props.additionalData, props.itemType).then((res) => {
        formStatusDisplay.value = FormStatus.SAVED
        router.push('/create').then(() => {
            toast('Successfully submitted ' + props.itemType + ' for moderation!')
        })
    }).catch(e => {
        console.log(e.response.data)
        setError(1, 'Please provide all the required information')


    }).finally(() => {
        isSaving.value = false
    })
}

const handleClickSaveAsDraft = () => {
    if(v$.value.title.$model.length < 1 ||v$.value.content.$model.length < 1){
        setError(2, 'Please provide at least title and content to save post as a draft')
        return
    }
    isSaving.value = true
    formService.handleSubmitPostAsDraft(state, currentUser.value.id, props.additionalData, props.itemType).then((res) => {
        formStatusDisplay.value = FormStatus.SAVED
        router.push('/create').then(() => {
            toast('Successfully saved ' + props.itemType + ' as a draft!')
        })
    }).catch(e => {
        setError(2, 'Please provide at least title to save post as a draft')
        console.error('Error during saving')
    }).finally(() => {
        isSaving.value = false
    })
}

const titleGenerator = computed((): string => {
    if (currentAction.value === FormAction.CREATE) {
        return "Create " + capitalize(props.itemType == 'advice' ? 'guide' : props.itemType)

    } else if (currentAction.value === FormAction.EDIT) {
        return "Edit " + capitalize(props.itemType)
    }
})

const statusGenerator = computed(() => {
    if (isSaving.value) {
        return "Saving data"
    } else if (formStatusDisplay.value) {
        return formStatusDisplay.value
    } else {
        return ''
    }

})

const handleTinyRichContent = (data) => {
    console.log('base form received ' + data)
    v$.value.content.$model = data
}
</script>

<template>
    <div
        id="BaseFormParent"
        class="BaseFormContainer border-[1px] flex flex-col mt-12 mx-5 p-8 rounded-2xl text-black md:!mx-10"
    >
        <div class="Introduction formHeader my-4">
            <div class="flex justify-between flex-row">
                <div class="font-semibold text-xl">
                    {{ titleGenerator }}
                </div>
                <div class="flex flex-col smallAutoSaveHeaderSection">
                    <div class="statusDisplay text-md">
                        {{ statusGenerator }}
                    </div>
                    <slot name="formHeader" />
                </div>
            </div>
            <TextInput
                ref="titleInputRef"
                v-model="v$.title.$model"
                :v$="v$.title"
                field-id="titleInput"
                class="my-2"
                placeholder="Title"
            >
                <template #label>
                    Title
                </template>
            </TextInput>
            <div class="ContainerTemp my-2 richContent">
                <TextInput
                    v-model="v$.excerpt.$model"
                    field-id="excerptInput"
                    :v$="v$.excerpt"
                    class="my-2"
                    placeholder="150 characters or less"
                >
                    <template #label>
                        Tagline
                    </template>
                </TextInput>
            </div>
            <div class="ContainerTemp my-2 richContent">
                <label> Content</label>
                <TinyMceRichTextInput
                    :src-content="v$.content.$model"
                    :v$="v$.content"
                    @emit-tiny-rich-content="handleTinyRichContent"
                />
            </div>
            <div class="containerTempImageUploader my-2">
                <label> Cover image (1 image file)</label>
                <ImageUploaderInput
                    :item-type="props.itemType"
                    :current-media="v$.cover_image.$model"
                    :max="1"
                    :v$="v$.cover_image"
                    @emit-uploaded-media="handleReceiveMediaFromUploader"
                />
            </div>
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
            <div class="extraContentSection mb-4">
                <slot name="extraContent" />
            </div>
            <div
                v-show="error.status"
                class="italic text-red-600"
            >
                {{ error.message }}
            </div>
            <div class="flex justify-center gap-6 saveButtonContainer">
                <GenericButton
                    class="px-6 py-2"
                    :callback="handleClickSaveAsDraft"
                >
                    Save as draft
                </GenericButton>
                <GenericButton
                    class="px-6 py-2"
                    :callback="handleClickSubmitForModeration"
                >
                    Submit for moderation
                </GenericButton>
            </div>
        </div>
    </div>
</template>
