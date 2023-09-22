// useAutoSave.ts
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
    CHECKING_AUTOSAVE = "Checking autosave",
    FOUND_AUTOSAVE = "Autosave Found!"
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
        extraContentData: any[]; // You may want to specify a more detailed type here if you know the structure
    } | string;
    exp_date: string; // This can be further refined to Date if you're going to convert it to a Date object
    is_active: boolean;
    created_at: string; // This can be further refined to Date if you're going to convert it to a Date object
    updated_at: string; // This can be further refined to Date if you're going to convert it to a Date object
};

export function useAutoSave(
    autoSaveService: AutoSaveService,
    currentUser: Ref<{ value: User }>,
    itemType: string,
    getState: () => Record<string, any>,
    formId: string
) {
    const isSaving = ref(false);
    const formStatusDisplay = ref<FormStatus>(FormStatus.CHECKING_AUTOSAVE);
    // temporary ref to store before loading data into form
    // "private-ish" ref
    const autoSaveContentBuffer = ref<AutoSaveDataType>({})
    // attached to the actual form component. load data to autoSaveContent when sure
    const autoSaveContent = ref<AutoSaveDataType>({})

    const autosave = debounce(async (): Promise<void> => {
        const data = {
            ...getState(),
        }
        if (data['title']) {
            try {
                await autoSaveService.savePost(currentUser.value.id, itemType, data);
                isSaving.value = false;
                formStatusDisplay.value = FormStatus.AUTOSAVED;
            } catch {
                formStatusDisplay.value = FormStatus.EDITING;

            }
        }
    }, 5000);

    const handleActivity = () => {
        formStatusDisplay.value = FormStatus.EDITING;
        if (!isSaving.value) {
            autosave();
        }
    }

    const loadAutoSaveData = () =>{
        autoSaveContent.value = Object.assign({}, autoSaveContentBuffer.value)
    }

    onMounted(() => {
        const formElement = document.getElementById(formId);

        if (formElement) {
            formElement.addEventListener('input', handleActivity);
            formElement.addEventListener('click', handleActivity);
        }
        autoSaveService.getAutoSave(currentUser.value.id, itemType).then(res => {
            const formattedResponse: AutoSaveDataType = {...res.data.data}
            if (formattedResponse['content']) {
                if (typeof formattedResponse['content'] === 'string') {
                    formattedResponse['content'] = JSON.parse(<string>formattedResponse['content'])
                }
            }
            /** check if title and (excerpt or content) exists, if they do, autosave is valid and will be used
             *  otherwise, ignore and go on making new posts
             */
            if (formattedResponse.content?.title && (formattedResponse.content?.excerpt || formattedResponse.content?.content)) {
                autoSaveContentBuffer.value = formattedResponse
                formStatusDisplay.value = FormStatus.FOUND_AUTOSAVE
            } else {
                formStatusDisplay.value = FormStatus.NEW
            }


        }).catch(err => {
            formStatusDisplay.value = FormStatus.NEW
        })
    });

    onUnmounted(() => {
        const formElement = document.getElementById(formId);
        if (formElement) {
            formElement.removeEventListener('input', handleActivity);
            formElement.removeEventListener('click', handleActivity);
        }
    });

    return {
        formStatusDisplay,
        isSaving,
        autoSaveContent,
        loadAutoSaveData
    };
}
