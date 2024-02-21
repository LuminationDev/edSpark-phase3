<script setup>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {computed, onBeforeUnmount, onMounted, ref} from 'vue';

const props = defineProps({
    itemArray: {
        type: Array,
        required: true
    },
    itemTitle:{
        type: String,
        required: false,
        default: ""
    }
});
console.log(props.itemTitle)
const iconListContent = computed(() =>
    Array.isArray(props.itemArray) ? props.itemArray : Object.values(props.itemArray)
);

const top = ref('');
const distanceBetweenEls = ref('');
const floatingLineClasses = ref('');
const uniqueContainerClass = ref(`numberListcontainer${Math.floor(Math.random() * 100000)}`);

// Encapsulated handlers for better readability
const updateLinePositions = () => {
    const {firstEl, lastEl, distance, newTop} = calculatePositions();
    distanceBetweenEls.value = distance;
    top.value = newTop;
};

onMounted(() => {
    if (props.itemArray.length) {
        window.addEventListener('resize', updateLinePositions);
        updateLinePositions();
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', updateLinePositions);
});

const calculatePositions = () => {
    const listContainers = document.querySelectorAll(`.${uniqueContainerClass.value}`);
    const firstEl = listContainers[0];
    const lastEl = listContainers[listContainers.length - 1];

    const distance = firstEl && lastEl ? getDistanceBetweenElements(firstEl, lastEl) : 0;
    const newTop = firstEl ? firstEl.offsetTop + firstEl.offsetHeight / 2 : 0;

    return {firstEl, lastEl, distance, newTop};
};

const getDistanceBetweenElements = (a, b) => {
    const {x: ax, y: ay} = getPositionAtCenter(a);
    const {x: bx, y: by} = getPositionAtCenter(b);
    return Math.hypot(ax - bx, ay - by);
};

const getPositionAtCenter = (element) => {
    const {top, left, width, height} = element.getBoundingClientRect();
    return {
        x: left + width / 2,
        y: top + height / 2
    };
};
const fontAwesomeNameFormatter = (nameFromFilament) => {
    return nameFromFilament.replace('fas-', 'fa-')
}

const fontAwesomeDefaultColor = (itemColor) => {
    if (!itemColor) return '#1C5CA9' // fallback light teal color
    else return itemColor
}
</script>

<template>
    <div class="extraContent mb-10 relative">
        <div
            class="absolute left-[12.4%] bg-black bg-secondary-darkBlue connectingLine hidden w-0.5 z-10 md:!flex"
            :style="`height: ${distanceBetweenEls}px; top: ${top+40}px;`"
        />
        <div class="flex flex-col">
            <div
                v-if="props.itemTitle"
                class="flex flex-row gap-4 mb-5 place-items-baseline"
            >
                <h1
                    class="font-bold text-3xl whitespace-nowrap"
                >
                    {{ props.itemTitle }}
                </h1>
                <div class="bg-black h-1 w-full" />
            </div>
            <div
                v-for="(item,index) in iconListContent"
                :key="index"
                class="eachContent min-h-[180px] py-2 w-full"
            >
                <div class="flex flex-row w-full">
                    <div class="extraContentIcon hidden justify-center mt-6 relative w-1/4 md:!flex">
                        <div
                            class="
                                absolute
                                bg-white
                                flex
                                justify-center
                                items-center
                                rounded-full
                                text-2xl
                                w-16
                                z-20
                                md:!h-24
                                md:!w-24
                                "
                            :class="uniqueContainerClass"
                        >
                            <FontAwesomeIcon
                                :icon="`${fontAwesomeNameFormatter(item.icon)}`"
                                class="h-full stroke-main-teal w-full"
                                :color="fontAwesomeDefaultColor(item.color)"
                            />
                        </div>
                    </div>
                    <div
                        class="flex flex-col mt-6 w-full md:!w-3/4"
                    >
                        <div class="font-semibold heading mb-2 text-xl">
                            {{ item.heading }}
                        </div>

                        <div
                            class="flex flex-col htmlRenderer"
                            v-html="item.content"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>
.htmlRenderer ul {
    list-style: disc !important;
    padding-left: 36px !important;
}

.htmlRenderer :deep(p) {
    word-wrap: break-word;
}
</style>
