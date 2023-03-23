<script setup lang="ts">
import { ref } from 'vue'
import Spinner from '../spinner/Spinner.vue'

const spinner = ref(false)
const props = defineProps({
    type: {
        type: String,
        required: false
    },
    callback: {
        type: Function,
        required: true
    },
    disabled: {
        type: Boolean,
        required: false
    },
    customClass: {
        type: String,
        required: false,
        default: ''
    },
    buttonId: {
        type: String,
        required: false,
        default: 'button'
    }
})
const onClick = async (): Promise<void> => {
    await asyncCall()
}

const asyncCall = (): Promise<void> => {
    return new Promise((resolve) => {
        resolve(props.callback())
    }).then()
}
</script>
<template>
    <button
        :id="buttonId"
        :class="{
            'h-auto rounded-lg text-white text-base bg-blue-600': true,
            'my-3 hover:bg-blue-400': type === 'school',
        }"
        class="p-2 flex justify-center items-center"
        :disabled="disabled"
        @click="onClick()"
    >
        <Spinner v-if="spinner" />
        <slot v-else />
    </button>
</template>
<style></style>
