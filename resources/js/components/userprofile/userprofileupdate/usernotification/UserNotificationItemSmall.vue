<script setup>

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
</script>

<template>
    <div
        class="flex items-center flex-row m-2 mt-4 notificationItemSmallContainer w-full"
        @click="clickCallback"
    >
        <div
            class="bg-blue-50 border-2 border-adminTeal p-2 rounded-3xl"
            :class="['border', getCategoryStyling(categoryText)]"
        >
            <div class="text-center w-32">
                {{ categoryText }}
            </div>
        </div>
        <div class="p-2">
            <div class="ml-6 mr-6 text-gray-500">
                {{ formatDateToDayTime(timeDate) }}
            </div>
        </div>
        <div class="p-2">
            <div class="ml-6 mr-6">
                {{ displayHeading }}
            </div>
        </div>
    </div>
</template>
