<script setup>
import { imageURL } from '../../constants/serverUrl';
import {useRouter} from "vue-router";

const props = defineProps({
    dayEvents: {
        type: Array,
        required: true
    },
    date: {
        type: String,
        required: true
    }
});

const emits = defineEmits(['emitClosePopup']);
const router = useRouter()

const formatDate = (theDate) => {
    return new Date(theDate).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})
}

const formatTime = (theDate) => {
    return new Date(theDate).toLocaleTimeString('en-us', { hour: 'numeric', minute: 'numeric', hour12: true });
}

const checkIfMultiDay = (startDate, endDate) => {
    let start = new Date(startDate);
    let end = new Date(endDate);

    if (start.getDate() === end.getDate()) {
        return;
    } else {
        return ' | Multi-day'
    }
}

const handleEmitClosePopup = () => {
    emits('emitClosePopup');
}

const handleClickEventFromPopup = (eventId) => {
    console.log('Clicked the event with id: ', eventId);
    document.body.style.overflow = 'auto';
    router.push({
        name:"event-single",
        params: { id: eventId},
    })
}

const print = (thing) => {
    console.log(thing.customData.cover_image);
}
</script>

<template>
    <div
        class="
            -translate-x-1/2
            -translate-y-1/2
            bg-white
            fixed
            inset-1/2
            flex
            justify-center
            items-center
            h-fit
            max-h-[900px]
            p-6
            shadow-lg
            w-[800px]
            z-50
            "
    >
        <div class="flex flex-col gap-4 w-full">
            <div class="flex justify-between flex-row">
                <h4 class="text-[24px]">
                    {{ formatDate(date) }}
                </h4>

                <button @click="handleEmitClosePopup">
                    Close
                </button>
            </div>
            <div class="eventPopupContent flex flex-col gap-4 w-full">
                <div
                    v-for="(event, index) in dayEvents"
                    :key="index"
                >
                    {{ print(event) }}
                    <div
                        class="
                            cursor-pointer
                            flex
                            flex-row
                            h-[146px]
                            place-items-center
                            px-6
                            py-2
                            w-full
                            gap-4
                            hover:bg-slate-50
                            "
                        @click="handleClickEventFromPopup(event.customData.id)"
                    >
                        <div
                            class="max-h-[100px] max-w-[100px] min-h-[100px] min-w-[100px]"
                            :style="`background: url('${imageURL}/${event.customData.cover_image}') center center / cover no-repeat;`"
                        />
                        <div class="flex flex-col h-full shortDescription w-[588px]">
                            <div class="flex justify-between flex-row pb-2">
                                <h5 class="font-semibold">
                                    {{ event.customData.title }}
                                </h5>
                                <p class="text-[12px]">
                                    {{ formatTime(event.customData.start_date) }} to {{ formatTime(event.customData.end_date) }} <span
                                        class="font-semibold"
                                    >{{ checkIfMultiDay(event.customData.start_date, event.customData.end_date) }}</span>
                                </p>
                            </div>

                            <div
                                class="eventEvcerptText"
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
    .eventEvcerptText {
        width: 588px;
    }

    .eventEvcerptText >>> p {
        max-width: 588px;
        width: 588px;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 100px;
    }
</style>
