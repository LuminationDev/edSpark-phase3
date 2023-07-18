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
        document.addEventListener('resize', getConnectingLinePositions());
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
    <div class="extraContent relative ">
        <div
            class="connectingLine absolute w-1 bg-black z-10 left-[12.4%]"
            :style="`height: ${distanceBetweenEls}px; top: ${top}px;`"
        />
        <div
            v-for="(item,index) in numberedListContent"
            :key="index"
            class="eachContent py-2"
        >
            <div class="flex flex-row w-full">
                <div class="extraContentIcon w-1/4 flex justify-center items-center relative">
                    <div class="numberListcontainer font-bold text-2xl border-4 bg-white border-black p-4 w-24 h-24 rounded-[50%] flex justify-center items-center absolute z-20">
                        {{ index + 1 }}
                    </div>
                </div>
                <div
                    class="w-3/4 flex flex-col"
                >
                    <div class="heading font-semibold mt-6 text-xl mb-2">
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
