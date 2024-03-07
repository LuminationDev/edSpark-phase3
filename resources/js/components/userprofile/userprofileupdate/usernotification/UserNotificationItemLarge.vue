<script setup>

import {computed} from "vue";

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
    },displayAuthor:{
        type: String,
        required:true
    },
    displayAction:{
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
    switch (categoryText) {
    case 'SOFTWARE':
        return '!border-purple-500 !border-2 !text-purple-500 !bg-purple-50';
    case 'ADVICE':
        return '!border-green-600 !border-2 !text-green-700 !bg-green-50';
    case 'EVENT':
        return '!border-blue-500 !border-2 !text-blue-700 !bg-blue-50';
    default:
        return 'border';
    }
};

const formattedString = computed(() => {
    return props.displayAction.charAt(0).toUpperCase() + props.displayAction.slice(1).toLowerCase();
});

const formattedCategoryTextString = computed(() => {
    return props.categoryText.charAt(0).toUpperCase() + props.categoryText.slice(1).toLowerCase();
});

</script>

<template>
    <div
        class="
            bg-gray-50
            border-[1.5px]
            cursor-pointer
            flex
            items-center
            flex-row
            h-20
            mt-4
            notificationItemSmallContainer
            rounded-[43px]
            rounded-r-xl
            w-full
            hover:!bg-gray-100
            "
        @click="clickCallback"
    >
        <div
            class="bg-blue-50 border-2 border-adminTeal ml-6 p-2 rounded-3xl"
            :class="['border', getCategoryStyling(categoryText)]"
        >
            <div class="text-center w-32">
                {{ formattedCategoryTextString }}
            </div>
        </div>
        <div class="p-2">
            <div class="m-1 ml-6 mr-6 text-gray-500">
                {{ formatDateToDayTime(timeDate) }}
            </div>
            <div class="m-1 ml-6 mr-6 text-gray-500">
                Created by: {{ displayAuthor }}
            </div>
        </div>
        <div class="p-2">
            <div class="ml-8 mr-6">
                {{ displayHeading }}
            </div>
            <div class="ml-8 mr-6 text-gray-500">
                {{ formattedString }}
            </div>
        </div>
    </div>
</template>
