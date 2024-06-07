<script setup>
import {computed, ref} from 'vue'

const props = defineProps({
    tagType: {
        type: String,
        required: true
    }
})

const emits = defineEmits([])

const tagColor = computed(() => {
    const lowerTag = props.tagType.toLowerCase()
    if (lowerTag.includes('limited')) {
        return 'bg-secondary-banana text-secondary-bananaDark border-secondary-bananaDark '
    } else if (lowerTag.includes('available now')) {
        return 'bg-green-100 text-secondary-green border-secondary-green '
    } else if (lowerTag.includes('bundle offer')) {
        return 'bg-purple-200 text-secondary-grapeDark border-secondary-grapeDark '
    } else {
        return 'bg-blue-200 text-secondary-blueberryWeb border-secondary-blueberryWeb '

    }
})

</script>

<template>
    <div
        class="border-[1px] font-medium px-2 rounded-lg"
        :class="tagColor"
    >
        {{ tagType }}
    </div>
</template>
