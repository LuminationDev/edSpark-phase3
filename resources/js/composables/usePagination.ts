import {Ref, ref} from 'vue';

interface PaginationData {
    current_page: number;
    per_page: number;
    total_items: number;
    total_pages: number;
}

interface UsePaginationReturn {
    currentPage: Ref<number>;
    perPage: Ref<number>;
    totalItems: Ref<number>;
    totalPages: Ref<number>;
    handleChangePageNumber: (newPageNumber: number) => void;
    updatePaginationData: (newPaginationData: PaginationData) => void;
}


export default function usePagination(current_page, per_page): UsePaginationReturn {
    const currentPage = ref(current_page || 1);
    const perPage = ref(per_page || 20);
    const totalItems = ref(0);
    const totalPages = ref(0);

    const updatePaginationData = (newPaginationData: PaginationData) : void => {
        currentPage.value = newPaginationData.current_page || 1;
        perPage.value = newPaginationData.per_page || 0;
        totalItems.value = newPaginationData.total_items || 0;
        totalPages.value = newPaginationData.total_pages || 1;
    };
    const handleChangePageNumber = (newPageNumber: number): void => {
        currentPage.value = newPageNumber;
        scrollToTop();
    };
    const scrollToTop = () => {
        window.scrollTo({top: 0, behavior: 'smooth'});
    };

    return {
        currentPage,
        perPage,
        totalItems,
        totalPages,
        handleChangePageNumber,
        updatePaginationData
    };
}
