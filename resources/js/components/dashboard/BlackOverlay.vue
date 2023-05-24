<script setup>

const props = defineProps({
    isFirstVisit: {
        type: Boolean,
        required: true
    },
});

console.log(props.isFirstVisit)


</script>

<template>
    <div
        :class="props.isFirstVisit ? 'bg-black' : 'bg-transparent -z-50 hidden' "
        class="absolute w-full h-screen z-50 transition-colors duration-1000 left-0 right-0 top-0 bottom-0"
    />
</template>
