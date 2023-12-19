import { onMounted,ref } from 'vue';

export default function useIsLoading(fetchFunction : () => Promise<any>) {
    const data = ref(null);
    const error = ref(null);
    const isLoading = ref(false);

    const fetchData = async (): void => {
        try {
            isLoading.value = true;
            const response = await fetchFunction();
            data.value = response;
        } catch (err) {
            error.value = err;
        } finally {
            isLoading.value = false;
        }
    };

    onMounted(async () => {
        await fetchData();
    });

    return {
        data,
        error,
        isLoading,
        fetchData,
    };
}