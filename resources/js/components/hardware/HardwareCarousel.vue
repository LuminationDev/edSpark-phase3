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
    <div class="flex flex-row gap-8 flex-wrap place-items-center justify-around">
        <div class="w-[50%] max-w-[500px]">
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
                        class="min-w-[300px] w-full rounded-lg w-fit"
                    >
                </Slide>

                <template #addons>
                    <navigation />
                    <pagination />
                </template>
            </carousel>
        </div>
        <div class="w-[30%]">
            <div class="w-full border border-black grid grid-cols-2">
                <div
                    v-for="(item, index) in slideItems['gallery']"
                    class="col-span-1 group relative h-[140px]"
                >
                    <img
                        :src="`${imageURL}/${item}`"
                        :alt="slideItems.product_excerpt"
                        class="min-w-full h-full group-hover:cursor-pointer object-cover"
                        @click="handleImageGallery(index)"
                    >
                    <div
                        class="group-hover:bg-black/20 absolute w-full h-full top-0 left-0 pointer-events-none"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
