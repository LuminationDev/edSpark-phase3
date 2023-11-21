import {Ref, ref} from 'vue';

type ErrorMessageObject = {
    status: boolean,
    code: number,
    message: string

}

type ErrorMessageComposable = {
    error: Ref<ErrorMessageObject | null>;
    setError: (status: boolean, code: number, message?: string) => void;
    clearError: () => void;
};
export default function useErrorMessage(): ErrorMessageComposable {
    const error = ref<ErrorMessageObject>({
        status: false,
        code: 200,
        message: ""
    });

    const setError = (code, message = ''): void => {
        error.value = {status: true, code: code, message: message};
    };

    const clearError = (): void => {
        error.value = {status: false, code: 200, message: ''};
    };

    return {
        error,
        setError,
        clearError
    };
}