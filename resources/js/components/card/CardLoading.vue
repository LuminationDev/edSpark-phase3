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
});
</script>

<template>
    <!--    <div class="w-full">-->
    <div
        v-for="(row,index) in numberOfRows"
        :key="index"
        class="flex justify-center flex-row overflow-hidden w-full"
    >
        <div
            v-for="(count,index) in numberPerRow"
            :key="index"

            class="
                GenericCardContainer
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
                min-w-[200px]
                mx-[29px]
                pointer-events-none
                transition-all
                w-full
                hover:shadow-2xl
                lg:!min-w-[200px]
                xl:!min-w-[300px]
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
                    transition-all
                    "
            />
            <div
                class="bg-white cardContent flex flex-col gap-6 h-full loadingCard-content overflow-hidden p-4 transition-all"
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
