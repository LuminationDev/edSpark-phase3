<script setup>
const props = defineProps({
    title:{
        type: String,
        required: true
    },
    subtitle:{
        type: String,
        required: true
    },
    subtitle2:{
        type: String,
        required: false,
        default: ''
    }
})
</script>
<template>
    <div
        class="-mt-12 lg:!mt-24 lg:!mx-10 lg:!pt-12 xl:!mx-huge bg-main-teal grid grid-cols-8 pb-8 pt-24 px-10"
    >
        <div class="col-span-8 md:!col-span-5 lg:!col-span-5">
            <h1 class="capitalize font-semibold pb-8 text-[36px] text-white">
                <slot name="title" />
                {{ props.title }}
            </h1>
            <p class="font-normal pb-2 text-[16px] text-white">
                <slot name="subtitle" />
                {{ props.subtitle }}
            </p>
            <p class="font-normal text-[16px] text-white">
                <slot name="subtitle2" />
                {{ props.subtitle2 }}
            </p>
        </div>
        <div class="col-span-3 hidden relative md:!block lg:!col-span-3">
            <slot name="pageIconSVG" />
        </div>
    </div>
</template>


<style scoped>

</style>