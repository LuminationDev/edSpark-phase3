<script setup>

import {onBeforeUnmount, onMounted, ref} from "vue";

import AnswerButton from "@/js/components/dma/AnswerButton.vue";

const props = defineProps({
    theme: {
        type: String,
        required: true,
    },
    allowUnsure: {
        type: Boolean,
        required: false,
        default: true,
    },
    showPrevious: {
        type: Boolean,
        required: false,
    },
})

const emit = defineEmits(['answer','previous']);

const isUnsure = ref(false);

const handleKeypress = (event) => {
    const keyList = ['1','2','3','4'];
    if (keyList.includes(event.key)) {
        if(!isUnsure.value) {
            if (event.key === '1') handleAnswer(1);
            if (event.key === '2') handleAnswer(0);
            if (event.key === '3') isUnsure.value = true;
        } else {
            if (event.key === '1') handleAnswer(2);
            if (event.key === '2') handleAnswer(3);
            if (event.key === '3') handleAnswer(4);
            if (event.key === '4') handleAnswer(5);
        }
    }
}

const handleAnswer = (answer) => {
    emit('answer', answer);
    isUnsure.value = false;
}

onMounted(() => {
    // globally capture number keypress events
    window.addEventListener('keydown', handleKeypress);
})
onBeforeUnmount(() => {
    // release keypress event
    window.removeEventListener('keydown', handleKeypress);
})

</script>

<template>
    <div
        class="flex justify-start items-center flex-row h-full question-screen w-full"
    >
        <div
            class="basis-1/3 flex flex-col h-full p-10 pt-28"
            :class="`bg-${props.theme}-flat`"
        >
            <div class="flex-1">
                <slot name="contentTop" />
            </div>
            <div>
                <slot name="contentBottom" />
            </div>
        </div>
        <div class="basis-2/3 bg-black flex flex-col h-full p-16 pt-10">
            <div class="flex justify-between h-5 question-controls">
                <span>
                    <button
                        v-if="props.showPrevious"
                        @click="emit('previous')"
                    >
                        &lt; Previous
                    </button>
                </span>
                <span
                    v-if="$slots.info"
                    class="cursor-help info-icon relative"
                >
                    <span>ⓘ</span>
                    <div class="absolute top-6 right-0 bg-gray-600 info-tooltip p-2 rounded-lg w-[200px]">
                        <slot name="info" />
                    </div>
                </span>
            </div>
            <div class="flex-1 mt-3 question text-2xl">
                <slot name="question" />
            </div>
            <div
                v-if="!isUnsure"
                class="answers"
            >
                <AnswerButton
                    hint="Press 1"
                    @click="handleAnswer(1)"
                >
                    YES
                </AnswerButton>
                <AnswerButton
                    hint="Press 2"
                    @click="handleAnswer(0)"
                >
                    NO
                </AnswerButton>
                <AnswerButton
                    v-if="props.allowUnsure"
                    hint="Press 3"
                    outline
                    @click="isUnsure = true"
                >
                    UNSURE
                </AnswerButton>
            </div>
            <div
                v-else
                class="answers"
            >
                <AnswerButton
                    hint="Press 1"
                    @click="handleAnswer(2)"
                >
                    It is not applicable to my school
                </AnswerButton>
                <AnswerButton
                    hint="Press 2"
                    @click="handleAnswer(3)"
                >
                    Statement is too ambiguous
                </AnswerButton>
                <AnswerButton
                    hint="Press 3"
                    @click="handleAnswer(4)"
                >
                    The examples are not helpful
                </AnswerButton>
                <AnswerButton
                    hint="Press 4"
                    @click="handleAnswer(5)"
                >
                    Other
                </AnswerButton>
            </div>
        </div>
    </div>
</template>

<style scoped>
.info-tooltip {
    display: none;
}
.info-icon:hover .info-tooltip {
    display: block;
}
</style>
