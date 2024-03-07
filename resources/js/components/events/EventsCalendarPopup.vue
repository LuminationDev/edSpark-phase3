<script setup lang="ts">
import {onClickOutside} from "@vueuse/core";
import {Ref,ref} from "vue";
import {useRouter} from "vue-router";

import { imageURL } from '@/js/constants/serverUrl';
import {EventType} from "@/js/types/EventTypes";
import Close from "@/js/components/svg/Close.vue";

const props = defineProps({
    dayEvents: {
        type: Array as () => EventType[],
        required: true
    },
    date: {
        type: String,
        required: true
    }
});

const emits = defineEmits(['emitClosePopup']);
const calendarPopupBox: Ref<HTMLDivElement | null> = ref(null);

const router = useRouter();

const formatDate = (theDate: string): string => {
    return new Date(theDate).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"});
}

const formatTime = (theDate: string): string => {
    return new Date(theDate).toLocaleTimeString('en-us', { hour: 'numeric', minute: 'numeric', hour12: true });
}

const checkIfMultiDay = (startDate: string, endDate: string): string | undefined => {
    const start = new Date(startDate);
    const end = new Date(endDate);

    if (start.getDate() !== end.getDate()) {
        return ' | Multi-day';
    }
}

const handleEmitClosePopup = (): void => {
    emits('emitClosePopup');
}

const handleClickEventFromPopup = (eventId: number): void => {
    console.log('Clicked the event with id: ', eventId);
    document.body.style.overflow = 'auto';
    router.push({
        name:"event-single",
        params: { id: eventId },
    });
}

onClickOutside(calendarPopupBox,() => {
    emits('emitClosePopup');
});
</script>

<template>
    <div
        ref="calendarPopupBox"
        class="
            -translate-x-1/2
            -translate-y-1/2
            overflow-y-auto
            bg-white
            fixed
            inset-1/2
            flex
            justify-center
            h-fit
            max-h-[90vh]
            p-6
            shadow-lg
            w-full
            max-w-[800px]
            z-50
            "
    >
        <div class="flex flex-col gap-4 w-full">
            <div class="flex justify-between flex-row">
                <h4 class="text-[24px]">
                    {{ formatDate(date) }}
                </h4>

                <Close
                    class="absolute top-6 right-4 z-50 md:!right-8 md:!top-6 h-[15px] w-[15px]" 
                    @click="handleEmitClosePopup"
                />

                <!-- <button @click="handleEmitClosePopup">
                    
                X
                </button> -->
            </div>
            <div class="eventPopupContent flex flex-col gap-6 pb-4 md:!gap-4 w-full">
                <div
                    v-for="(event, index) in dayEvents"
                    :key="index"
                >
                    <div
                        class="
                            cursor-pointer
                            flex
                            flex-row
                            h-[168px]
                            place-items-center
                            px-6
                            py-2
                            w-full
                            gap-4
                            hover:bg-slate-100
                            bg-slate-50
                            "
                        @click="handleClickEventFromPopup(event.customData.id)"
                    >
                        <div
                            class="max-h-[100px] max-w-[100px] min-h-[100px] min-w-[100px]"
                            :style="`background: url('${imageURL}/${event.customData.cover_image}') center center / cover no-repeat;`"
                        />
                        <div class="flex flex-col h-full shortDescription overflow-hidden w-full"> <!-- w-[91vw] max-w-[95%]  -->
                            <div class="flex justify-between flex-col md:flex-row gap-1 pb-2"> <!-- max-w-[95%] -->
                                <h5 class="font-semibold"> <!-- max-w-[60vw] -->
                                    {{ event.customData.title }}
                                </h5>
                                <p class="text-[12px]">
                                    {{ formatTime(event.customData.start_date) }} to {{ formatTime(event.customData.end_date) }} <span
                                        class="font-semibold"
                                    >{{ checkIfMultiDay(event.customData.start_date, event.customData.end_date) }}</span>
                                </p>
                            </div>

                            <div
                                class="eventExcerptText"
                                v-html="event.customData.excerpt"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .eventExcerptText {
        /* max-width: 90%; */
    }

    .eventExcerptText :deep(p) {
        /* max-width: 90%; */
        /* width: 588px; */
        overflow: hidden;
        text-overflow: ellipsis;
        height: 100px;
    }
</style>
