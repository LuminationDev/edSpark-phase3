<script setup lang="ts">

import {computed, ref} from "vue";

//important props used
const props = defineProps({
    sendCategoryTextValue:{
        type: String,
        required: true
    },
    sendTimeDateValue:{
        type: String,
        required: true
    },
    sendNotificationHeadingValue:{
        type: String,
        required: true
    },
    sendNotifications:{
        type:Array,
        required: true
    }
})

const categoryText = ref(props.sendCategoryTextValue)
const notificationTimeDate = ref(props.sendTimeDateValue)
const notificaitonHeading = ref(props.sendNotificationHeadingValue)

const categoryStyling = computed(() => {
    switch (categoryText.value) {
    case 'Software':
        return '!border-purple-500 !border-2 !text-purple-500 !bg-purple-50'
    case 'Guide':
        return '!border-green-600 !border-2 !text-green-700 !bg-green-50'
    case 'Event':
        return '!border-blue-500 !border-2 !text-blue-700 !bg-blue-50'
    case 'DAG':
        return '!border-adminTeal !border-2 !text-adminTeal !bg-blue-50'
    default:
        return 'border'
    }
})

// Listing notificaiton system using Arrays and For loop
const notifications = ref(props.sendNotifications);

const getCategoryStyling = (categoryText) => {
    switch (categoryText) {
    case 'Software':
        return '!border-purple-500 !border-2 !text-purple-500 !bg-purple-50';
    case 'Guide':
        return '!border-green-600 !border-2 !text-green-700 !bg-green-50';
    case 'Event':
        return '!border-blue-500 !border-2 !text-blue-700 !bg-blue-50';
    default:
        return 'border';
    }
};

</script>

<template>
    <!--Testing the listing using hardcoded value -->
    <div class="HardCodedNotificationLayout">
        <div
            class="bg-gray-50 flex items-center flex-row m-2 mt-4 rounded-3xl"
        >
            <span
                class="bg-blue-50 border-2 border-adminTeal p-2 rounded-3xl"
                :class="['border', categoryStyling]"
            >
                <div class="text-center w-32">{{ categoryText }}</div>
            </span>
            <span class="p-2">
                <div class="ml-6 mr-6 text-gray-500">{{ notificationTimeDate }}</div>
            </span>
            <div class="p-2">
                <div class="ml-6 mr-6">
                    {{ notificaitonHeading }}
                </div>
            </div>
        </div>
    </div>
    <div class="border-2 border-black mb-36 mt-20" />
    <!--Testing the listing using for loop -->
    <div class="ListingNotificationLayout">
        <div
            v-for="(notification, index) in notifications"
            :key="index"
            class="bg-gray-50 flex items-center flex-row m-2 mt-4 rounded-3xl"
        >
            <span :class="['bg-blue-50 w-40 border-2 border-adminTeal p-2 rounded-3xl', getCategoryStyling(notification.categoryText)]">
                <div class="text-center">{{ notification.categoryText }}</div>
            </span>
            <div class="flex justify-center">
                <span class="p-2">
                    <div class="ml-12 text-gray-500 w-40">{{ notification.notificationTimeDate }}</div>
                </span>
                <div class="p-2">
                    <div class="">
                        {{ notification.notificationHeading }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
