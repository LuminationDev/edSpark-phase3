<script setup>
import { computed, onMounted, ref } from "vue";

const props = defineProps({
    itemArray: {
        type: Array,
        required: true

    }
})

const numberedListContent = computed(() => {
    if(typeof props.itemArray == "object"){
        return Object.values(props.itemArray)
    }else{
        return props.itemArray
    }
});

const top = ref('');
const distanceBetweenEls = ref('');
const floatingLineClasses = ref('');

const getConnectingLinePositions = () => {
    let listContainers = document.querySelectorAll('.numberListcontainer');
    let firstContainer = listContainers[0];
    let lastContainer = listContainers[listContainers.length -1];

    distanceBetweenEls.value = getDistanceBetweenElements(
        firstContainer,
        lastContainer
    );

    let firstElHeight = firstContainer.offsetHeight
    top.value = firstContainer.offsetTop + firstElHeight / 2;
    floatingLineClasses.value = `top-[${top.value}] h-[${distanceBetweenEls.value}px]`
}

onMounted(() => {
    if (props.itemArray) {
        window.addEventListener('resize', () => getConnectingLinePositions());
        getConnectingLinePositions();
    };
})

const getPositionAtCenter = (element) => {
    const {top, left, width, height} = element.getBoundingClientRect();
    return {
        x: left + width / 2,
        y: top + height / 2
    };
}

const getDistanceBetweenElements = (a, b) => {
    const aPosition = getPositionAtCenter(a);
    const bPosition = getPositionAtCenter(b);

    return Math.hypot(aPosition.x - bPosition.x, aPosition.y - bPosition.y);
}




</script>
<template>
    <div class="extraContent relative">
        <div
            class="absolute left-[12.4%] bg-black connectingLine hidden w-1 z-10 md:!block"
            :style="`height: ${distanceBetweenEls}px; top: ${top}px;`"
        />
        <div
            v-for="(item,index) in numberedListContent"
            :key="index"
            class="eachContent py-2"
        >
            <div class="flex flex-row w-full">
                <div class="extraContentIcon flex justify-center items-center hidden relative w-1/4 md:!block">
                    <div
                        class="
                            absolute
                            bg-white
                            border-4
                            border-black
                            flex
                            justify-center
                            items-center
                            font-bold
                            h-16
                            numberListcontainer
                            p-4
                            rounded-[50%]
                            text-2xl
                            w-16
                            z-20
                            md:!h-24
                            md:!w-24
                            "
                    >
                        {{ index + 1 }}
                    </div>
                </div>
                <div
                    class="flex flex-col w-full md:!w-3/4"
                >
                    <div class="font-semibold heading mb-2 mt-6 text-xl">
                        <span class="font-bold text-2xl">{{ `${index + 1}. ` }}</span>
                        {{ item.heading }}
                    </div>

                    <div
                        class="htmlRenderer"
                        v-html="item.content"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .htmlRenderer ul {
        list-style: disc !important;
        padding-left: 36px !important;
    }
</style>
