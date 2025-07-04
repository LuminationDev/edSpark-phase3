<script setup>

import {computed, onBeforeUnmount, onMounted, ref} from "vue";

import {formatDateToDayTime} from "@/js/helpers/dateHelper";

const props = defineProps({
    categoryText: {
        type: String,
        required: true
    },
    timeDate: {
        type: String,
        required: true
    },
    displayHeading: {
        type: String,
        required: true
    },
    clickCallback: {
        type: Function,
        required: false,
        default: () => {
        }
    }
})

const emits = defineEmits([])

const getCategoryStyling = (categoryText) => {
    const lowerCaseCategory = categoryText.toLowerCase();
    switch (lowerCaseCategory) {
    case 'software':
        return '!border-purple-500 !border-2 !text-purple-500 !bg-purple-50';
    case 'school':
        return '!border-purple-500 !border-2 !text-purple-500 !bg-purple-50';
    case 'advice':
        return '!border-green-600 !border-2 !text-green-700 !bg-green-50';
    case 'event':
        return '!border-blue-500 !border-2 !text-blue-700 !bg-blue-50';
    default:
        return 'border';
    }
};

const formattedCategoryTextString = computed(() => {
    return props.categoryText.charAt(0).toUpperCase() + props.categoryText.slice(1).toLowerCase();
})

const shouldSliceDisplayHeading = ref(false);
const truncatedDisplayHeading = computed(() => {
    return props.displayHeading.slice(0, getSliceLength()) + '...';
});

const handleResize = () => {
    shouldSliceDisplayHeading.value = window.innerWidth < 1536;
};

const getSliceLength = () => {
    const screenWidth = window.innerWidth;
    if (screenWidth < 1536) {
        return 21;
    }
    if (screenWidth < 836) {
        return 10;
    }
    else {
        return 100;
    }
};

onMounted(() => {
    handleResize();
    window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize);
});

</script>

<template>
    <div
        class="
            bg-gray-50
            cursor-pointer
            flex
            items-center
            flex-row
            m-2
            mr-4
            mt-4
            notificationItemSmallContainer
            rounded-3xl
            w-full
            hover:!bg-gray-100
            "
        @click="clickCallback"
    >
        <div
            class="bg-blue-50 border-2 border-adminTeal p-2 rounded-3xl"
            :class="['border', getCategoryStyling(categoryText)]"
        >
            <div class="text-center w-16 sm:!w-32">
                {{ formattedCategoryTextString }}
            </div>
        </div>
        <div class="p-2 w-auto">
            <div class="ml-6 mr-0 text-gray-500 w-16 sm:!mr-0 sm:!w-48 md:!mr-6">
                {{ formatDateToDayTime(timeDate).includes('Invalid') ? "" : formatDateToDayTime(timeDate) }}
            </div>
        </div>
        <div class="p-2 w-auto">
            <div class="ml-0 mr-6 sm:!ml-0 md:!ml-6">
                {{ shouldSliceDisplayHeading ? truncatedDisplayHeading : displayHeading }}
            </div>
        </div>
    </div>
</template>
