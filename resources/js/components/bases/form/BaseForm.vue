<script setup lang="ts">
import ImageUploaderForm from "@/js/components/bases/form/ImageUploaderForm.vue";
import TagsInput from "@/js/components/bases/form/TagsInput.vue";
import TrixRichEditor from "@/js/components/bases/form/TrixRichEditor.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import {autosaveService} from "@/js/service/autosaveService";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import {capitalize, onMounted, onUnmounted} from "vue";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {ref, computed, reactive, onBeforeMount} from 'vue'
import GenericButton from "@/js/components/button/GenericButton.vue";
import {formService} from "@/js/service/formService";


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
// const FormAction = {
//     CREATE: 'CREATE',
//     EDIT: 'EDIT'
// }


const FormStatus = {
    NEW: 'New',
    UNSAVED: 'Unsaved',
    AUTOSAVED: 'Autosaved',
    DRAFT: 'Draft',
    SAVED: 'Saved',
    LOADED_AUTOSAVE: 'Autosave loaded'
}

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
    tags: {}
}

const v$ = useVuelidate(rules, state)

const autosaveTimer = ref(null);
const isSaving = ref(false);
const formStatusDisplay = ref('')
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)


const autosave = () => {
    const data = {
        ...state,
        ...props.additionalData
    }
    autosaveService.savePost(currentUser.value.id, props.itemType, data).then(res => {
        isSaving.value = false
        formStatusDisplay.value = FormStatus.AUTOSAVED
    }).catch(() => {
        formStatusDisplay.value = FormStatus.UNSAVED
    })
}
const startAutosaveTimer = (): void => {
    // If there's already a timer running, clear it
    if (autosaveTimer.value) {
        clearTimeout(autosaveTimer.value);
    }

    // Start a new timer
    autosaveTimer.value = setTimeout(() => {
        isSaving.value = true;
        autosave();
    }, 5000);
}

const handleActivity = () => {
    formStatusDisplay.value = FormStatus.UNSAVED
    if (isSaving.value) return;
    startAutosaveTimer();
}

onMounted(() => {
    document.addEventListener('input', handleActivity);
    document.addEventListener('click', handleActivity);

    try {
        autosaveService.getAutosave(currentUser.value.id, props.itemType).then(res => {
            formStatusDisplay.value = FormStatus.DRAFT
        }).catch(err => {
            formStatusDisplay.value = FormStatus.NEW
        })
    } catch {
        console.log('error occured')
    }
});

onUnmounted(() => {
    document.removeEventListener('input', handleActivity);
    document.removeEventListener('click', handleActivity);
});

const handleTrixInputContent = (data) => {
    v$.value.content.$model = data
}

const handleTrixInputExcerpt = (data) => {
    v$.value.excerpt.$model = data
}


const handleClickSave = () =>{
    console.log("Clicked save")
    formService.handleSaveForm(state, currentUser.value.id, props.additionalData,props.itemType ).then(() =>{
        console.log('kinda succedd from base form')
    })
}
</script>

<template>
    <div class="BaseFormContainer border-[1px] flex flex-col mt-12 mx-5 p-4 rounded-2xl text-black md:!mx-10 lg:!mx-20">
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
        <GenericButton :callback="handleClickSave">
            Save
        </GenericButton>
    </div>
</template>
