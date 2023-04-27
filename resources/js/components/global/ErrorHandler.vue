<script setup>


    const props = defineProps({
        errorMessage: {
            type: String,
            required: true
        }
    });


</script>

<template>

    <p class="text-red-400 font-medium text-[15px] pt-2">
        {{ props.errorMessage }}
    </p>
</template>
