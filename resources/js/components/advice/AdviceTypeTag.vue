<script setup>
import { computed } from 'vue'
const props = defineProps({
    typeTag: {
        type: Array, required: true
    }
})
const typeTagColor = computed(() => {
    switch (props.typeTag) {
    case 'DAG advice':
        return 'bg-yellow-600';
        break;
    default:
        return 'bg-green-800'

    }
})

</script>

<template>
    <!-- class="absolute rounded bg-[#FFC836] min-w-[136px] h-[39px] text-white flex flex-row justify-around gap-3 place-items-center -right-3 top-3 px-4" -->
    <div       
        class="TypeTag bg-adviceGreen flex gap-4 h-[39px] p-1 place-items-center px-6 rounded text-base text-white w-fit mx-3 my-2"
        :class="typeTagColor"
    >
        {{ typeTag[0] }}
    </div>
</template>
