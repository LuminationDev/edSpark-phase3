<script setup>

import {onBeforeUnmount, onMounted, ref} from "vue";

import iconArrowCircleLeft from '@/assets/images/dma/icons/arrow-circle-left.svg';
import iconInfoCircle from '@/assets/images/dma/icons/info-circle.svg';
import AnswerButton from "@/js/components/dma/AnswerButton.vue";
import BackButton from "@/js/components/dma/BackButton.vue";
import PrimaryActionButton from "@/js/components/dma/PrimaryActionButton.vue";


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
    disabled: {
        type: Boolean,
        required: false,
    },
    blurBg: {
        type: Boolean,
        required: false,
    }
})

const emit = defineEmits(['answer','previous']);

const REASON = {
    NOT_APPLICABLE: "It is not applicable to my school",
    AMBIGUOUS: "Statement is too ambiguous",
    NOT_HELPFUL: "The examples are not helpful",
};

const keyList = ['1','2','3','4'];

let questionObserver = null;

const isUnsure = ref(false);
const answerText = ref(null);

const scrollableRef = ref(null);
const questionRef = ref(null);
const screenRef = ref(null);
const bottomRef = ref(null);

const tooltipRef = ref(null);
const tooltipTarget = ref(null);
const tooltipContent = ref('');

const activeKey = ref(null);


const handleKeyDown = (event) => {
    if (!props.disabled && answerText.value == null && keyList.includes(event.key)) {
        activeKey.value = event.key;
    }
}
const handleKeyUp = (event) => {
    // capture keypresses if screen is not disabled and not currently typing an answer
    if (!props.disabled && answerText.value == null && keyList.includes(event.key)) {
        if(!isUnsure.value) {
            if (event.key === '1') handleAnswer(1);
            if (event.key === '2') handleAnswer(0);
            if (event.key === '3') isUnsure.value = true;
        } else {
            if (event.key === '1') handleAnswer(2, REASON.NOT_APPLICABLE);
            if (event.key === '2') handleAnswer(2, REASON.AMBIGUOUS);
            if (event.key === '3') handleAnswer(2, REASON.NOT_HELPFUL);
            if (event.key === '4') handleShowAnswerField();
        }
        activeKey.value = null;
    }
}

const handleShowUnsure = (show = true, scrollDown = true) => {
    isUnsure.value = show;
    handleShowAnswerField(false);
    setTimeout(() => {
        if(scrollDown) {
            bottomRef.value.scrollIntoView({behavior: 'smooth'});
        } else {
            scrollableRef.value.scrollTop = 0;
        }
    },10)
}

const handleShowAnswerField = (show = true) => {
    answerText.value = show ? '' : null;
}

const handlePrevious = () => {
    emit('previous');
}

const handleAnswer = (answer, text) => {
    emit('answer', answer, text);
}

const handleQuestionChange =() => {
    handleShowUnsure(false, false);
    handleShowAnswerField(false);

    const anchors = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    anchors.forEach(anchor => {
        // prevent clicking on anchors from scrolling page to top
        anchor.addEventListener('click', (event) => { event.preventDefault() });
        // add tooltip handler
        const tooltip = anchor.dataset.bsTitle;
        anchor.addEventListener('mouseover', (event) => handleShowTooltip(event));
        anchor.addEventListener('focus', (event) => handleShowTooltip(event));
        anchor.addEventListener('mouseout', (event) => handleHideTooltip(event));
        anchor.addEventListener('blur', (event) => handleHideTooltip(event));
    });
}

const handleShowTooltip = (event) => {
    tooltipTarget.value = event.target;
    tooltipContent.value = event.target.dataset.bsTitle;

    const screenRect = screenRef.value.getBoundingClientRect();
    const targetRect = event.target.getBoundingClientRect();

    tooltipRef.value.style.display = 'block';
    tooltipRef.value.style.left = `${targetRect.left - screenRect.left}px`;
    tooltipRef.value.style.top = `${targetRect.bottom - screenRect.top + 5}px`;
}

const handleHideTooltip = (event) => {
    if (tooltipTarget.value === event.target) {
        tooltipTarget.value = null;
        tooltipContent.value = '';

        tooltipRef.value.style.display = 'none';
    }
}

onMounted(() => {
    // globally capture number keypress events
    window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('keyup', handleKeyUp);

    // observe changes to the question slot and handle tooltips on anchors
    handleQuestionChange();
    questionObserver = new MutationObserver(handleQuestionChange);
    questionObserver.observe(questionRef.value, {
        childList: true,
        subtree: true
    });
})
onBeforeUnmount(() => {
    // release keypress event
    window.removeEventListener('keydown', handleKeyDown);
    window.removeEventListener('keyup', handleKeyUp);

    // disconnect from observing the question slot
    questionObserver.disconnect();
})

</script>

<template>
    <div
        ref="screenRef"
        class="flex justify-start items-center flex-1 flex-col h-full question-screen w-full md:!flex-row"
    >
        <div
            class="w-full md:w-auto md:basis-1/4 md:h-full lg:basis-1/3"
            :class="`QuestionScreen-bg-${props.theme} ${props.blurBg ?
                'bg-blur' : ''}`"
        >
            <div
                class="flex flex-col h-full px-4 w-full md:!pt-36 md:!px-10 md:!py-16"
            >
                <div class="flex-1 hidden md:!block">
                    <slot name="contentTop" />
                </div>
                <div
                    class="md:!pt-0"
                    :class="`${props.theme==='triage' ? 'pt-12' : 'pt-20'}`"
                >
                    <slot name="contentBottom" />
                </div>
            </div>
        </div>
        <div
            class="bg-black-1 flex flex-1 flex-col pt-4 w-full md:!pt-10 md:!w-auto md:basis-3/4 md:h-full lg:basis-2/3"
        >
            <div class="flex justify-between items-center h-[52px] mb-5 px-4 question-controls md:!px-16">
                <span class="flex items-center h-full">
                    <BackButton
                        v-if="isUnsure"
                        @click="handleShowUnsure(false)"
                    />
                    <button
                        v-else-if="props.showPrevious"
                        class="flex items-center font-bold gap-2 text-h5-caps active:hover:opacity-60 hover:opacity-80"
                        @click="handlePrevious"
                    >
                        <img :src="iconArrowCircleLeft"> Previous
                    </button>
                </span>
                <a
                    v-if="$slots.info"
                    class="cursor-help flex items-center h-full info-icon relative z-10"
                    href="#"
                >
                    <img :src="iconInfoCircle">
                    <div
                        class="
                            absolute
                            top-[45px]
                            right-[7px]
                            backdrop-blur-lg
                            bg-gray-500/70
                            cursor-default
                            h-[10px]
                            hidden
                            info-tooltip-arrow
                            rotate-45
                            w-[10px]
                            z-10
                            "
                    />
                    <div
                        class="
                            absolute
                            top-[50px]
                            right-[-10px]
                            backdrop-blur-lg
                            bg-gray-500/70
                            cursor-default
                            font-medium
                            hidden
                            info-tooltip
                            max-w-[400px]
                            p-5
                            rounded-lg
                            text-base
                            w-[90vw]
                            z-10
                            md:!right-[-30px]
                            md:!w-[400px]
                            "
                    >
                        <slot name="info" />
                    </div>
                </a>
            </div>
            <div class="flex flex-1 flex-col relative md:overflow-hidden">
                <div
                    class="absolute right-6 bottom-0 left-0 bg-gradient-to-b from-transparent to-black h-10 hidden md:!block"
                />
                <div
                    ref="scrollableRef"
                    class="flex flex-1 flex-col pb-6 px-4 md:!pb-16 md:!px-16 md:overflow-x-none md:overflow-y-scroll"
                >
                    <div
                        ref="questionRef"
                        class="flex-1 mb-6 question text-xLarge md:text-medium lg:text-xLarge"
                    >
                        <slot name="question" />
                    </div>
                    <div
                        v-if="!isUnsure"
                        class="answers"
                    >
                        <AnswerButton
                            hint="Press 1"
                            :highlighted="activeKey === '1'"
                            :disabled="props.disabled"
                            @click="handleAnswer(1)"
                        >
                            YES
                        </AnswerButton>
                        <AnswerButton
                            hint="Press 2"
                            :highlighted="activeKey === '2'"
                            :disabled="props.disabled"
                            @click="handleAnswer(0)"
                        >
                            NO
                        </AnswerButton>
                        <AnswerButton
                            v-if="props.allowUnsure"
                            hint="Press 3"
                            :highlighted="activeKey === '3'"
                            outline
                            :disabled="props.disabled"
                            @click="handleShowUnsure(true)"
                        >
                            UNSURE
                        </AnswerButton>
                    </div>
                    <div
                        v-else-if="answerText === null"
                        class="answers"
                    >
                        <AnswerButton
                            hint="Press 1"
                            class="font-semibold"
                            :highlighted="activeKey === '1'"
                            :disabled="props.disabled"
                            @click="handleAnswer(2, REASON.NOT_APPLICABLE)"
                        >
                            {{ REASON.NOT_APPLICABLE }}
                        </AnswerButton>
                        <AnswerButton
                            hint="Press 2"
                            class="font-semibold"
                            :highlighted="activeKey === '2'"
                            :disabled="props.disabled"
                            @click="handleAnswer(2, REASON.AMBIGUOUS)"
                        >
                            {{ REASON.AMBIGUOUS }}
                        </AnswerButton>
                        <AnswerButton
                            hint="Press 3"
                            class="font-semibold"
                            :highlighted="activeKey === '3'"
                            :disabled="props.disabled"
                            @click="handleAnswer(2, REASON.NOT_HELPFUL)"
                        >
                            {{ REASON.NOT_HELPFUL }}
                        </AnswerButton>
                        <AnswerButton
                            hint="Press 4"
                            class="font-semibold"
                            :highlighted="activeKey === '4'"
                            :disabled="props.disabled"
                            @click="handleShowAnswerField"
                        >
                            Other
                        </AnswerButton>
                    </div>
                    <div v-else>
                        <textarea
                            v-model="answerText"
                            rows="7"
                            :disabled="props.disabled"
                            class="
                                bg-black-2
                                border-none
                                px-6
                                py-5
                                resize-none
                                rounded-2xl
                                text-medium
                                focus:outline-none
                                focus:ring
                                md:!px-8
                                md:!py-7
                                "
                            placeholder="Your explanation"
                        />
                        <div class="flex justify-end mt-5">
                            <PrimaryActionButton
                                :disabled="props.disabled"
                                @click="handleAnswer(2, answerText)"
                            >
                                Continue
                            </PrimaryActionButton>
                        </div>
                    </div>
                    <div ref="bottomRef" />
                </div>
            </div>
        </div>
        <div
            ref="tooltipRef"
            class="absolute top-0 left-0 hidden w-[300px] z-20"
        >
            <div
                class="
                    absolute
                    top-0
                    left-[30px]
                    backdrop-blur-lg
                    bg-gray-500/70
                    cursor-default
                    h-[10px]
                    info-tooltip-arrow
                    rotate-45
                    w-[10px]
                    "
            />
            <div
                class="backdrop-blur-lg bg-gray-500/70 cursor-default floating-tooltip mt-[5px] p-5 rounded-lg w-full"
                v-html="tooltipContent"
            />
        </div>
    </div>
</template>

<style scoped lang="scss">
:deep(ul) {
    list-style: revert;
    margin-top: 0.5em;
    padding-left: 1em;
}

:deep(.question a,
.info-tooltip a) {
    text-decoration: underline;
    &[data-bs-toggle='tooltip'] {
        cursor: help;
    }
}

.info-icon:hover, .info-icon:focus-within {
    .info-tooltip, .info-tooltip-arrow {
        display: block;
    }
}

</style>
