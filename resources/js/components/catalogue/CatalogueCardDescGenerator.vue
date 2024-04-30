<script setup>

const props = defineProps({
    cardDescObj: {
        type: Object,
        required: true
    }
})

const descEntries = Object.entries(props.cardDescObj)
</script>

<template>
    <div class="catCardDesc line-clamp-2 text-gray-600">
        <template
            v-for="(desc,index) in descEntries"
            :key="index"
        >
            <span
                v-if="desc[1]"
                class="capitalize font-semibold"
            > {{ desc[0] + ":" }}</span> {{ desc[1] + " " }}
        </template>
    </div>
</template>


