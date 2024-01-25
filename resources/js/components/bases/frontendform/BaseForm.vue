<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {maxLength, required} from "@vuelidate/validators";
import {storeToRefs} from "pinia";
import {capitalize, computed, onBeforeMount, onBeforeUnmount, onMounted, reactive, ref} from "vue";
import {onBeforeRouteLeave, useRouter} from "vue-router";
import {toast} from "vue3-toastify";

import FormExitConfirmationPopup from "@/js/components/bases/frontendform/FormExitConfirmationPopup.vue";
import LabelsSelector from "@/js/components/bases/frontendform/LabelsSelector.vue";
import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import ImageUploaderInput, {MediaType} from "@/js/components/bases/ImageUploaderInput.vue";
import TagsInput from "@/js/components/bases/TagsInput.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {FormStatus} from "@/js/composables/useAutoSave";
import useErrorMessage from "@/js/composables/useErrorMessage";
import {differenceObjects} from "@/js/helpers/jsonHelpers";
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
        type: Object,
        required: false,
        default: () => {
        }
    },
    itemType: {
        type: String,
        required: true
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

enum ContentOrigin {
    NEW = 'NEW',
    DRAFT = 'DRAFT'
}

const baseFormFieldErrorMessage = {
    type: 'publication type',
    cover_image: 'cover image',
    excerpt: 'tagline',
    title: 'title',
    content: 'main content',
    location: 'location',
    start_date: 'start date',
    end_date: 'end_date'
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
    labels: {},
    content_origin: ContentOrigin.NEW,
    existing_id: 0
})

const rules = {
    title: {required},
    excerpt: {
        required,
        maxLength: maxLength(150)
    },
    content: {required},
    cover_image: {required},
    tags: {},
    labels: {},
    existing_id: {},
    content_origin: {}

}


const v$ = useVuelidate(rules, state)

const currentAction = ref<FormAction>(FormAction.CREATE)
const {error, setError, clearError} = useErrorMessage()
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)
const router = useRouter()
const isSaving = ref(false)
const formStatusDisplay = ref(FormStatus.NEW)
const showExitConfirmation = ref(false)
const byPassExitConfirmation = ref(false)

const populateLocalStateFromWindowStateDraftData = (data): void => {
    state.title = data.title || ""
    state.excerpt = data.excerpt || ""
    state.content = data.content || ""
    state.cover_image = data.cover_image || ""
    state.author_name = data.authorName || ""
    state.tags = data.tags || []
    state.existing_id = data.id || 0
    state.labels = data.labels || 0
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
        state.content_origin = ContentOrigin.DRAFT
        formStatusDisplay.value = FormStatus.EDITING
    }
})

onBeforeUnmount(() => {
    // do auto save as draft here
})

onMounted(() => {
    const baseFormDiv = document.getElementById('BaseFormParent')
    if (baseFormDiv) {
        baseFormDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'nearest',
        });
    }
})

const handleReceiveMediaFromUploader = (media: MediaType[]): void => {
    if (media && media.length === 1 && media[0]) {
        v$.value.cover_image.$model = media[0].remoteUrl
    } else {
        v$.value.cover_image.$model = null
    }
}

const generateErrorMessageFromvErrors = (arrayOfMissingFields, draftOrModeration) => {
    const fieldsListString = []
    const endingString = draftOrModeration.toLowerCase() === "draft" ? ' to create a draft' : ' to submit for moderation'
    arrayOfMissingFields.forEach(field => {
        console.log(baseFormFieldErrorMessage[field.$property || field])
        fieldsListString.push(baseFormFieldErrorMessage[field.$property || field])
    })
    return "Please provide " + fieldsListString.join(", ") + ' ' + endingString
}

const handleClickSubmitForModeration = async () => {
    isSaving.value = true
    await v$.value.$validate();
    await props.additionalValidation.$validate();
    console.log(props.additionalValidation.$errors)
    if (v$.value.$error || props.additionalValidation.$error) {
        // Only using parent's additionalValidation due to automatic collection from useVuelidate
        setError(1, generateErrorMessageFromvErrors(props.additionalValidation.$errors, 'moderation'))
        isSaving.value = false
        return
    }
    return formService.handleSubmitPostForModeration(state, currentUser.value.id, props.additionalData, props.itemType).then((res) => {
        formStatusDisplay.value = FormStatus.SAVED
        byPassExitConfirmation.value = true
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
    if (v$.value.$error) {
        v$.value.$reset()
        clearError();
    }
    if (props.additionalValidation.$error) {
        props.additionalValidation.$reset()
    }
    const draftError = []

    if (v$.value.title.$model.length < 1) {
        draftError.push('title')
    }
    if (v$.value.content.$model.length < 1) {
        draftError.push('content')
    }
    if (draftError && draftError.length > 0) {
        console.log(draftError)
        setError(1, generateErrorMessageFromvErrors(draftError, 'draft'))
        return
    }

    isSaving.value = true
    return formService.handleSubmitPostAsDraft(state, currentUser.value.id, props.additionalData, props.itemType).then((res) => {
        formStatusDisplay.value = FormStatus.SAVED
        byPassExitConfirmation.value = true
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
    v$.value.content.$model = data
}
const handleSelectedLabels = (data) => {
    v$.value.labels.$model = data
}

const handleClickExitOverlay = () => {
    showExitConfirmation.value = false
}

let innerResolver
let innerButtonPromise = new Promise(res => {
    innerResolver = res
})

const handleClickStayPage = async () => {
    innerResolver(false)
}

const handleClickLeavePage = async () => {
    innerResolver(true)
}


const handleShowConfirmationPopup = async () => {
    return new Promise(async (resolve) => {
        showExitConfirmation.value = true
        const buttonConfirmationResult = await innerButtonPromise
        resolve(buttonConfirmationResult)
    })
}

onBeforeRouteLeave(async (to, from) => {
    if (!v$.value.$anyDirty && !props.additionalValidation.$anyDirty) {
        return true
    }
    if(byPassExitConfirmation.value) return true
    const confirmationDecision = await handleShowConfirmationPopup()
    if (!confirmationDecision) {
        showExitConfirmation.value = false
    }
    //reset the promise
    innerButtonPromise = new Promise(res => {
        innerResolver = res
    })
    return confirmationDecision
})

</script>

<template>
    <div
        v-if="showExitConfirmation"
        class="exitConfirmationPopup"
    >
        <FormExitConfirmationPopup
            :overlay-callback="handleClickExitOverlay"
            :stay-callback="handleClickStayPage"
            :leave-callback="handleClickLeavePage"
        />
    </div>
    <div
        id="BaseFormParent"
        class="BaseFormContainer flex flex-col mt-12 mx-5 p-8 rounded-2xl text-black"
    >
        <div class="Introduction formHeader my-4">
            <div class="flex justify-between flex-row">
                <div class="font-semibold mb-8 text-3xl">
                    {{ titleGenerator }}
                </div>
                <div class="flex flex-col smallAutoSaveHeaderSection">
                    <div class="statusDisplay text-md">
                        {{ statusGenerator }}
                    </div>
                    <slot name="formHeader" />
                </div>
            </div>
            <div class="my-8">
                <TextInput
                    ref="titleInputRef"
                    v-model="v$.title.$model"
                    :v$="v$.title"
                    field-id="titleInput"
                    class="my-2"
                    placeholder="Enter a title for your publication"
                >
                    <template #label>
                        Title
                    </template>
                </TextInput>
            </div>
            <div class="ContainerTemp my-8 richContent">
                <TextInput
                    v-model="v$.excerpt.$model"
                    field-id="excerptInput"
                    :v$="v$.excerpt"
                    class="my-2"
                    placeholder="Enter a tagline for your publication (150 characters or less)"
                >
                    <template #label>
                        Tagline
                    </template>
                </TextInput>
            </div>
            <div class="itemType">
                <slot name="itemType" />
            </div>
            <div class="ContainerTemp my-8 richContent">
                <label> Main content</label>
                <TinyMceRichTextInput
                    :src-content="v$.content.$model"
                    :v$="v$.content"
                    @emit-tiny-rich-content="handleTinyRichContent"
                />
            </div>
            <div class="extraContentSection mb-8">
                <slot name="extraContent" />
            </div>
            <div class="my-6">
                <LabelsSelector
                    v-model="v$.labels.$model"
                    @emit-selected-options="handleSelectedLabels"
                />
            </div>
            <div class="my-6">
                <TagsInput
                    v-model="v$.tags.$model"
                    :field-id="'tag-selector'"
                    :v$="v$.tags"
                >
                    <template #label>
                        Tag
                    </template>
                </TagsInput>
            </div>

            <div class="containerTempImageUploader my-8">
                <label> Cover image <span class="font-light text-xs"> (500px x 500px recommended. max. 5 MB. Accepted format: JPG, JPEG, PNG & SVG.)</span></label>
                <ImageUploaderInput
                    :item-type="props.itemType"
                    :current-media="v$.cover_image.$model"
                    :max="1"
                    :v$="v$.cover_image"
                    @emit-uploaded-media="handleReceiveMediaFromUploader"
                />
            </div>
            <div
                class="italic my-8 text-red-600"
            >
                {{ error.message || ' ' }}
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
