<script setup>
    const props = defineProps({
        data: {
            type: Object,
            required: true
        }
    });

    console.log(props.data.data.file);
</script>

<template>
    <div class="videoRendererWrapper">
        <video
            controls
        >
            <source
                :src="data.data.file"
            />
        </video>
    </div>
</template>
