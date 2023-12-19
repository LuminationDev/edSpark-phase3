<script setup>
import {computed, onBeforeUnmount, onMounted, ref} from 'vue';

const props = defineProps({
    itemArray: {
        type: Array,
        required: true
    }
});

const numberedListContent = computed(() =>
    Array.isArray(props.itemArray) ? props.itemArray : Object.values(props.itemArray)
);

const top = ref('');
const distanceBetweenEls = ref('');
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

</script>

<template>
    <div class="extraContent relative">
        <div
            class="absolute left-[12.4%] bg-black connectingLine hidden w-1 z-10 md:!flex"
            :style="`height: ${distanceBetweenEls}px; top: ${top}px;`"
        />
        <div
            v-for="(item,index) in numberedListContent"
            :key="index"
            class="eachContent py-2 w-full"
        >
            <div class="flex flex-row w-full">
                <div class="extraContentIcon hidden relative w-1/4  items-center justify-center md:!flex">
                    <div
                        class="
                            absolute
                            bg-white
                            border-4
                            border-black
                            font-bold
                            grid
                            place-items-center
                            h-16
                            p-4
                            rounded-full
                            text-2xl
                            w-16
                            z-20
                            md:!h-24
                            md:!w-24
                            "
                        :class="uniqueContainerClass"
                    >
                        {{ index + 1 }}
                    </div>
                </div>
                <div
                    class="flex flex-col w-full md:!w-3/4"
                >
                    <div class="font-semibold heading mb-2 mt-6 text-xl">
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
