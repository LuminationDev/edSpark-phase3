 <script setup>

import {computed, onMounted, onUnmounted} from "vue";

const props = defineProps({
    numberPerRow: {
        type: Number,
        required: false,
        default: 3
    },
    numberOfRows: {
        type: Number,
        required: false,
        default: 1
    },
    additionalClasses: {
        type: String,
        required: false,
        default: ''
    }
});
let loadingContainerWidth = 0
const getContainerWidth = () => {
    const loadingContainer = document.getElementsByClassName("LoadingCardRowContainer")[0];
    if(loadingContainer){
        loadingContainerWidth = loadingContainer.offsetWidth;
    }
}
getContainerWidth()

const numberOfCardsPerRowWithSafety = computed(() =>{
    if(loadingContainerWidth){
        return Math.floor(loadingContainerWidth/450)
    } else{
        return props.numberPerRow
    }
})


onMounted(() =>{
    window.addEventListener('resize', getContainerWidth)
})

onUnmounted(() =>{
    window.removeEventListener('resize',getContainerWidth)
})
</script>

<template>
    <div
        v-for="(row,index) in numberOfRows"
        :key="index"
        class="LoadingCardRowContainer flex justify-around flex-row gap-4 mx-auto overflow-hidden w-full"
        style="max-width: 100vw !important; margin: auto;"
    >
        <div
            v-for="(count,colIndex) in numberOfCardsPerRowWithSafety"
            :key="colIndex"
            class="
                border-[0.5px]
                border-slate-100
                card_parent
                flex
                flex-col
                group
                loadingCard
                max-h-[480px]
                max-w-[250px]
                mb-4
                min-h-[480px]
                min-w-[300px]
                pointer-events-none
                rounded
                transition-all
                w-full
                hover:shadow-2xl
                "
        >
            <div
                class="
                    bg-center
                    bg-cover
                    cardTopCoverImage
                    group-hover:h-0
                    group-hover:min-h-[0%]
                    loadingCard-image
                    min-h-[35%]
                    relative
                    rounded-t
                    transition-all
                    "
            />
            <div
                class="bg-white cardContent flex flex-col gap-6 h-full loadingCard-content overflow-hidden p-4 rounded transition-all"
            >
                <div class="h-[2.25rem] loadingCard-title rounded-xl w-full" />

                <div class="h-full loadingCard-description rounded-xl w-full" />
            </div>
        </div>
    </div>
    <!--    </div>-->
</template>

<style scoped lang="scss">
.loadingCard {
    .loadingCard-image,
    .loadingCard-title,
    .loadingCard-description {
        background: #eee;
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        background-size: 200% 100%;
        animation: 1.5s shine linear infinite;
    }
}

@keyframes shine {
    to {
        background-position-x: -200%;
    }
}
</style>
