<script setup>
import {computed, ref} from 'vue'

const props = defineProps({
    cardDescObj: {
        type: Object,
        required: true
    }
})


const descEntries = Object.entries(props.cardDescObj)
</script>

<template>
    <div class="catCardDesc">
        <template
            v-for="(desc,index) in descEntries"
            :key="index"
        >
            <span
                class="capitalize font-semibold"
            > {{ desc[0] }}</span> {{ desc[1] + " " }}
        </template>
    </div>
</template>


