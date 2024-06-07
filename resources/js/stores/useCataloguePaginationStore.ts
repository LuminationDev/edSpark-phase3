// stores/pagination.ts
import { defineStore } from 'pinia';
import { Ref,ref } from 'vue';

interface PaginationData {
    current_page: number;
    per_page: number;
    total_items: number;
    total_pages: number;
}

interface UsePaginationStore {
    currentPage: Ref<number>;
    perPage: Ref<number>;
    totalItems: Ref<number>;
    totalPages: Ref<number>;
    handleChangePageNumber: (newPageNumber: number) => void;
    updatePaginationData: (newPaginationData: PaginationData) => void;
}

export const useCataloguePaginationStore = defineStore('cat-pagination', (): UsePaginationStore => {
    const currentPage = ref<number>(1);
    const perPage = ref<number>(16);
    const totalItems = ref<number>(0);
    const totalPages = ref<number>(0);

    const updatePaginationData = (newPaginationData: PaginationData): void => {
        currentPage.value = newPaginationData.current_page || 1;
        perPage.value = newPaginationData.per_page || 20;
        totalItems.value = newPaginationData.total_items || 0;
        totalPages.value = newPaginationData.total_pages || 1;
    };

    const handleChangePageNumber = (newPageNumber: number): void => {
        currentPage.value = newPageNumber;
        scrollToTop();
    };

    const scrollToTop = (): void => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    return {
        currentPage,
        perPage,
        totalItems,
        totalPages,
        handleChangePageNumber,
        updatePaginationData,
    };
});
