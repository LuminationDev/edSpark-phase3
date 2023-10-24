<script setup>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {computed, onMounted, ref} from "vue";

const props = defineProps({
    itemArray: {
        type: Array,
        required: true

    }
})

const numberedListContent = computed(() => {
    if (typeof props.itemArray == "object") {
        return Object.values(props.itemArray)
    } else {
        return props.itemArray
    }
});

const top = ref('');
const distanceBetweenEls = ref('');
const floatingLineClasses = ref('');
const uniqueContainerClass = ref(`numberListcontainer${Math.floor(Math.random() * 100000)}`)

const getConnectingLinePositions = () => {
    const listContainers = document.querySelectorAll(`.${uniqueContainerClass.value}`);
    const firstContainer = listContainers[0];
    const lastContainer = listContainers[listContainers.length - 1];
    if (firstContainer && lastContainer) {
        distanceBetweenEls.value = getDistanceBetweenElements(
            firstContainer,
            lastContainer
        );

        const firstElHeight = firstContainer.offsetHeight
        top.value = firstContainer.offsetTop + firstElHeight / 2;
        floatingLineClasses.value = `top-[${top.value}] h-[${distanceBetweenEls.value}px]`

    }

}

onMounted(() => {
    if (props.itemArray) {
        window.addEventListener('resize', () => getConnectingLinePositions());
        getConnectingLinePositions();
    }
    ;
})

const getPositionAtCenter = (element) => {
    if (element) {
        const {top, left, width, height} = element.getBoundingClientRect();
        return {
            x: left + width / 2,
            y: top + height / 2
        }

    } else {
        return {
            x: 0,
            y: 0
        }
    }
}

const getDistanceBetweenElements = (a, b) => {
    const aPosition = getPositionAtCenter(a);
    const bPosition = getPositionAtCenter(b);

    return Math.hypot(aPosition.x - bPosition.x, aPosition.y - bPosition.y);
}


// replaces fas- with fa- to match FontAwesome vue requirements
const fontAwesomeNameFormatter = (nameFromFilament) => {
    return nameFromFilament.replace('fas-', 'fa-')
}

</script>
<template>
    <div class="extraContent iconListRenderer relative">
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
                            flex
                            justify-center
                            items-center
                            font-bold
                            h-16
                            p-4
                            rounded-[50%]
                            text-2xl
                            w-16
                            z-20
                            md:!h-24
                            md:!w-24
                            "
                        :class="uniqueContainerClass"
                    >
                        <FontAwesomeIcon :icon="`${fontAwesomeNameFormatter(item.icon)}`" />
                    </div>
                </div>
                <div
                    class="flex flex-col w-full md:!w-3/4"
                >
                    <div class="font-semibold heading mb-2 mt-6 text-xl">
                        {{ item.heading }}
                    </div>

                    <div
                        class="flex flex-wrap htmlRenderer"
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
