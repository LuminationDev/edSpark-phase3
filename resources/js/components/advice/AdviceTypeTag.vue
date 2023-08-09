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
        class="
            TypeTag
            absolute
            top-4
            right-0
            bg-adviceGreen
            flex
            h-10
            p-1
            md:px-6
            place-items-center
            px-2
            rounded
            text-white
            sm:!-right-4
            md:!-right-6
            "
        :class="typeTagColor"
    >
        {{ typeTag[0] }}
    </div>
</template>
