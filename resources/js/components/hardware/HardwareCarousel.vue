<script setup>
import 'vue3-carousel/dist/carousel.css';
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel';
import {ref} from 'vue';

const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;

const props = defineProps({
    slideItems: {
        type: Object,
        required: true
    }
});

const carouselItemIndex = ref(null);

const handleImageGallery = (index) => {
    carouselItemIndex.value = index;
}

</script>

<template>
    <div class="flex justify-around flex-col flex-wrap gap-2 place-items-center lg:!flex-row lg:!gap-8">
        <div class="max-w-[500px] w-full lg:!w-1/2">
            <carousel
                :items-to-show="1"
                :wrap-around="true"
                :model-value="carouselItemIndex"
            >
                <Slide
                    v-for="slide in slideItems['gallery']"
                    :key="slide"
                >
                    <img
                        :src="`${imageURL}/${slide}`"
                        :alt="slideItems.product_excerpt"
                        class="min-w-[300px] rounded-lg w-fit w-full"
                    >
                </Slide>

                <template #addons>
                    <navigation />
                    <pagination />
                </template>
            </carousel>
        </div>
        <!-- <div class="w-auto lg:!w-[30%]">
            <div class="border border-black grid grid-cols-2 w-full">
                <div
                    v-for="(item, index) in slideItems['gallery']"
                    class="col-span-1 group h-[140px] relative"
                >
                    <img
                        :src="`${imageURL}/${item}`"
                        :alt="slideItems.product_excerpt"
                        class="group-hover:cursor-pointer h-full min-w-full object-cover"
                        @click="handleImageGallery(index)"
                    >
                    <div
                        class="absolute top-0 left-0 group-hover:bg-black/20 h-full pointer-events-none w-full"
                    />
                </div>
            </div>
        </div> -->
    </div>
</template>

<style scoped>

.carousel__pagination-button::after,
:deep(.carousel__pagination-button::after) {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }


</style>