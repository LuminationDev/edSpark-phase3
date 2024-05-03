import { onMounted,ref } from 'vue';

export default function useIsLoading(fetchFunction : () => Promise<any>) {
    const data = ref(null);
    const error = ref(null);
    const isLoading = ref(true);

    const fetchData = async (): void => {
        try {
            isLoading.value = true;
            console.log('beofre fetch')
            const response = await fetchFunction();
            console.log('after fetch')
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