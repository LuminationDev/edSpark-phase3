<script setup>
import {computed } from 'vue'
const props = defineProps({
    typeTag: {
        type: String, required: true
    }
})

const typeTagColor = computed(() => {
    switch(props.typeTag){
    case 'D.A.G advice':
        return 'bg-yellow-600';
        break;
    default:
        return 'bg-green-800'

    }
})

</script>

<template>
    <div
        class="TypeTag absolute -right-6 top-4 p-1 px-2 bg-adviceGreen text-white rounded"
        :class="typeTagColor"
    >
        {{ typeTag }}
    </div>
</template>