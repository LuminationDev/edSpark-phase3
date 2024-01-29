<script setup lang="ts">
import {ref} from 'vue'
import {onBeforeRouteLeave} from "vue-router";

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
    },
    tooltipOnHover:{
        type: String, required: false, default: ""
    }
})
const onClick = async () => {
    spinner.value = true
    console.log('inside on click')
    await asyncCall();
    spinner.value = false
}

const asyncCall = () => {
    return new Promise(async (resolve, reject) => {
        try {
            console.log('before callback')
            const result = props.callback();
            if (result instanceof Promise) {
                result.then(resolve).catch(reject);
            } else {
                resolve(result);
            }

        } catch (error) {
            reject(error);
        }
    })
}


</script>
<template>
    <button
        :id="buttonId"
        v-tippy="tooltipOnHover"
        :class="{
            'h-auto rounded-lg text-white text-base bg-main-teal hover:bg-main-navy' : true,
            'my-3 hover:bg-blue-400': type === 'school',
            'pointer-events-none !bg-slate-300': disabled,
            '!text-black bg-white border-0 !p-0': type === 'plain',
            '!h-fit !text-white px-12 py-2 w-64' : type === 'teal',
            '!h-fit !text-white px-12 py-2 w-64 !bg-secondary-grapeDark' : type === 'purple',
            '!h-fit !text-white px-12 py-2 w-64 !bg-secondary-cherry' : type === 'red',
            '!h-fit !text-white px-12 py-2 w-64 !bg-secondary-blueberry' : type === 'blue'
        }"
        class="flex justify-center items-center p-2"
        :disabled="disabled"
        @click="onClick()"
    >
        <Spinner v-if="spinner" />
        <slot v-else />
    </button>
</template>
<style></style>
