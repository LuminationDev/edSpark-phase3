<script setup>

const props = defineProps({
    numberPerRow: {
        type: Number,
        required: false
    },
    numberOfRows: {
        type: Number,
        required: false,
        default: 1
    },
    additionalClasses: {
        type: String,
        required: false
    }
})
</script>

<template>
    <div class="pt-8">
        <div
            v-for="row in numberOfRows"
            class="flex flex-row flex-1 flex-wrap justify-between"
            :class="additionalClasses"
        >
            <div
                v-for="count in numberPerRow"
                :class="{'!w-[30%]': numberPerRow === 3,
                        '!w-[22%]': numberPerRow === 4,
                        '!w-[45%]': numberPerRow === 2,
                        '!w-[95%]' : numberPerRow === 1
                }"
                class="GenericCardContainer loadingCard w-full border-[0.5px] border-black hover:shadow-2xl mx-2 mb-4 flex flex-col min-h-[480px] max-w-[400px] max-h-[480px] group transition-all card_parent pointer-events-none"
            >
                <div
                    class="cardTopCoverImage loadingCard-image relative min-h-[35%] bg-cover bg-center group-hover:min-h-[0%] group-hover:h-0 transition-all"
                />
                <div
                    class="cardContent loadingCard-content h-full flex flex-col gap-6 p-4 overflow-hidden transition-all"
                >
                    <div class="loadingCard-title h-[2.25rem] w-full rounded-xl" />

                    <div class="loadingCard-description h-full w-full rounded-xl" />
                </div>
            </div>
        </div>
    </div>
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
