<script setup>

import {onBeforeUnmount, onMounted, ref} from "vue";

import imgLeading from '@/assets/images/dma/Leading.png';
import imgLearning from '@/assets/images/dma/Learning.png';
import imgManaging from '@/assets/images/dma/Managing.png';
import imgTeaching from '@/assets/images/dma/Teaching.png';
import AnswerButton from "@/js/components/dma/AnswerButton.vue";
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
    }
})

const emit = defineEmits(['answer','previous']);

const REASON = {
    NOT_APPLICABLE: "It is not applicable to my school",
    AMBIGUOUS: "Statement is too ambiguous",
    NOT_HELPFUL: "The examples are not helpful",
};

const domainImages = {
    triage: imgTeaching, // TODO triage image needed
    teaching: imgTeaching,
    learning: imgLearning,
    leading: imgLeading,
    managing: imgManaging
};

let questionObserver = null;

const isUnsure = ref(false);
const answerText = ref(null);
const questionRef = ref(null);

const screenRef = ref(null);
const tooltipRef = ref(null);
const tooltipTarget = ref(null);
const tooltipContent = ref('');

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

const handleAnswer = (answer, answerText) => {
    emit('answer', answer, answerText);
    isUnsure.value = false;
}

const handleBindQuestionTooltips =() => {
    const anchors = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    anchors.forEach(anchor => {
        // prevent clicking on anchors from scrolling page to top
        anchor.addEventListener('click', (event) => { event.preventDefault() });
        // add tooltip handler
        const tooltip = anchor.dataset.bsTitle;
        anchor.addEventListener('mouseover', (event) => handleShowTooltip(event));
        anchor.addEventListener('mouseout', (event) => handleHideTooltip(event));
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
    window.addEventListener('keydown', handleKeypress);

    // observe changes to the question slot and handle tooltips on anchors
    // TODO not very Vue friendly
    handleBindQuestionTooltips();
    questionObserver = new MutationObserver(handleBindQuestionTooltips);
    questionObserver.observe(questionRef.value, {
        childList: true,
        subtree: true
    });
})
onBeforeUnmount(() => {
    // release keypress event
    window.removeEventListener('keydown', handleKeypress);

    // disconnect from observing the question slot
    questionObserver.disconnect();
})

</script>

<template>
    <div
        ref="screenRef"
        class="flex justify-start items-center flex-row h-full question-screen w-full"
    >
        <div
            class="basis-1/3 flex flex-col h-full p-10 pt-28"
            :class="`bg-${props.theme}-img`"
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
                    <div class="h-8">ⓘ</div>
                    <div
                        class="
                            absolute
                            top-[25px]
                            right-[3px]
                            backdrop-blur
                            bg-gray-500/70
                            cursor-default
                            h-[10px]
                            hidden
                            info-tooltip-arrow
                            rotate-45
                            w-[10px]
                            "
                    />
                    <div
                        class="
                            absolute
                            top-[30px]
                            right-[-30px]
                            backdrop-blur
                            bg-gray-500/70
                            cursor-default
                            hidden
                            info-tooltip
                            p-5
                            rounded-lg
                            w-[300px]
                            "
                    >
                        <slot name="info" />
                    </div>
                </span>
            </div>
            <div
                ref="questionRef"
                class="flex-1 font-light mt-5 question text-2xl"
            >
                <slot name="question" />
            </div>
            <div
                v-if="!isUnsure"
                class="answers"
            >
                <AnswerButton
                    hint="Press 1"
                    :disabled="props.disabled"
                    @click="handleAnswer(1)"
                >
                    YES
                </AnswerButton>
                <AnswerButton
                    hint="Press 2"
                    :disabled="props.disabled"
                    @click="handleAnswer(0)"
                >
                    NO
                </AnswerButton>
                <AnswerButton
                    v-if="props.allowUnsure"
                    hint="Press 3"
                    outline
                    :disabled="props.disabled"
                    @click="isUnsure = true"
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
                    :disabled="props.disabled"
                    @click="handleAnswer(2, REASON.NOT_APPLICABLE)"
                >
                    {{ REASON.NOT_APPLICABLE }}
                </AnswerButton>
                <AnswerButton
                    hint="Press 2"
                    :disabled="props.disabled"
                    @click="handleAnswer(2, REASON.AMBIGUOUS)"
                >
                    {{ REASON.AMBIGUOUS }}
                </AnswerButton>
                <AnswerButton
                    hint="Press 3"
                    :disabled="props.disabled"
                    @click="handleAnswer(2, REASON.NOT_HELPFUL)"
                >
                    {{ REASON.NOT_HELPFUL }}
                </AnswerButton>
                <AnswerButton
                    hint="Press 4"
                    :disabled="props.disabled"
                    @click="answerText = ''"
                >
                    Other
                </AnswerButton>
            </div>
            <div v-else>
                <textarea
                    v-model="answerText"
                    rows="8"
                    :disabled="props.disabled"
                    class="bg-gray-800 resize-none rounded-3xl"
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
        </div>
        <div
            ref="tooltipRef"
            class="absolute top-0 left-0 hidden w-[300px]"
        >
            <div
                class="
                    absolute
                    top-0
                    left-[30px]
                    backdrop-blur
                    bg-gray-500/70
                    cursor-default
                    h-[10px]
                    info-tooltip-arrow
                    rotate-45
                    w-[10px]
                    "
            />
            <div
                class="backdrop-blur bg-gray-500/70 cursor-default floating-tooltip mt-[5px] p-5 rounded-lg w-full"
                v-html="tooltipContent"
            />
        </div>
    </div>
</template>

<style scoped lang="scss">
:deep {
    ul {
        list-style: revert;
        margin-top: 0.5em;
        padding-left: 1em;
    }
    a {
        text-decoration: underline;
        &[data-bs-toggle='tooltip'] {
            cursor: help;
        }
    }
}
.info-icon:hover {
    .info-tooltip, .info-tooltip-arrow {
        display: block;
    }
}

</style>
