// useAutoSave.ts
import {AxiosResponse} from "axios";
import {debounce} from 'lodash';
import {onMounted, onUnmounted, Ref,ref} from 'vue';

export enum FormStatus { 
    NEW = 'New',
    UNSAVED = 'Unsaved',
    AUTOSAVED = 'Autosaved',
    DRAFT = 'Draft',
    SAVED = 'Saved',
    LOADED_AUTOSAVE = 'Autosave loaded'
}

interface AutoSaveService {
    savePost: (userId: number, itemType: string, data: Record<string, any>) => Promise<void>;
    getAutoSave: (userId: number, type: string) => Promise<AxiosResponse<any>>
}

interface User {
    id: number;
}

export function useAutoSave(
    autoSaveService: AutoSaveService,
    currentUser: Ref<{ value: User }>,
    itemType: string,
    getState: () => Record<string, any>,
    formId: string
) {
    const isSaving = ref(false);
    const formStatusDisplay = ref<FormStatus>(FormStatus.NEW);
    const autoSaveContent = ref<any>({})

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
                formStatusDisplay.value = FormStatus.UNSAVED;

            }
        }
    }, 5000);

    const handleActivity = () => {
        formStatusDisplay.value = FormStatus.UNSAVED;
        if (!isSaving.value) {
            autosave();
        }
    }

    onMounted(() => {
        const formElement = document.getElementById(formId);

        if (formElement) {
            formElement.addEventListener('input', handleActivity);
            formElement.addEventListener('click', handleActivity);
        }
        autoSaveService.getAutoSave(currentUser.value.id, itemType).then(res => {
            formStatusDisplay.value = FormStatus.LOADED_AUTOSAVE
            console.log(res.data)
            const formattedResponse = {...res.data.data}
            if (formattedResponse['content']) {
                if (typeof formattedResponse['content'] === 'string') {
                    formattedResponse['content'] = JSON.parse(formattedResponse['content'])
                }
            }
            autoSaveContent.value = formattedResponse
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
        autoSaveContent
    };
}
