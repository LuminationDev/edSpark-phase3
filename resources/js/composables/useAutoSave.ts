// useAutoSave.ts
import {auto} from "@popperjs/core";
import {AxiosResponse} from "axios";
import {debounce} from 'lodash';
import {onMounted, onUnmounted, Ref, ref} from 'vue';

export enum FormStatus {
    NEW = 'New',
    EDITING = 'Editing',
    AUTOSAVED = 'Autosaved',
    DRAFT = 'Draft',
    SAVED = 'Saved',
    LOADED_AUTOSAVE = 'Autosave loaded',
    CHECKING_AUTOSAVE = "Checking for autosave",
    FOUND_AUTOSAVE = "Autosave Found!",
    NONE = ''
}

interface AutoSaveService {
    savePost: (userId: number, itemType: string, data: Record<string, any>) => Promise<void>;
    getAutoSave: (userId: number, type: string) => Promise<AxiosResponse<any>>
}

interface User {
    id: number;
}

type AutoSaveDataType = {
    id: number;
    post_id: number;
    user_id: number;
    post_type: string;
    status: string;
    content: {
        title: string;
        excerpt: string;
        content: string;
        coverImage: string;
        authorName: string;
        tags: string[];
        extraContentData: any[];
    } | string;
    exp_date: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
};

export function useAutoSave(
    autoSaveService: AutoSaveService,
    currentUser: Ref<{ value: User }>,
    itemType: string,
    getState: () => Record<string, any>,
    formId: string
) {
    const isSaving = ref(false);
    const formStatusDisplay = ref<FormStatus>(FormStatus.NONE);
    // temporary ref to store before loading data into form
    // "private-ish" ref
    const autoSaveContentBuffer = ref<AutoSaveDataType>({})
    // attached to the actual form component. load data to autoSaveContent when sure
    const autoSaveContent = ref<AutoSaveDataType>({})

    const autosave = async (): Promise<void> => {
        const data = {
            ...getState(),
        }
        if (data['title'] && formStatusDisplay.value !== FormStatus.SAVED && !isSaving.value) {
            try {
                await autoSaveService.savePost(currentUser.value.id, itemType, data);
                isSaving.value = false;
                formStatusDisplay.value = FormStatus.AUTOSAVED;
            } catch {
                formStatusDisplay.value = FormStatus.EDITING;

            }
        }
    }

    const handleActivity = () => {
        formStatusDisplay.value = FormStatus.EDITING;
        const formElement = document.getElementById(formId);
        if (formElement) {
            formElement.removeEventListener('input', handleActivity);
        }
    }

    const loadAutoSaveData = () => {
        autoSaveContent.value = Object.assign({}, autoSaveContentBuffer.value)
    }

    onMounted(() => {
        const formElement = document.getElementById(formId);
        if (formElement) {
            formElement.addEventListener('input', handleActivity);
        }
    });

    onUnmounted(async () => {

        if(formStatusDisplay.value === FormStatus.EDITING){
            await autosave()
        }


    });

    return {
        formStatusDisplay,
        isSaving,
        autoSaveContent,
        loadAutoSaveData
    };
}
