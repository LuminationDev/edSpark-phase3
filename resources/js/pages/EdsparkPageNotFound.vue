<script setup lang="ts">
import {useRoute, useRouter} from "vue-router";

import GenericButton from "@/js/components/button/GenericButton.vue";

const props = defineProps({
    errorMessage: {
        type: String,
        required: false,
        default: 'Page is not available'
    },
    buttonMessage: {
        type: String,
        required: false,
        default: "Go back to dashboard"
    },
    buttonCallback: {
        type: Function,
        required: false,
        default: null
    }
})
const router = useRouter();
const route = useRoute()

const handleButtonClick = (): void => {
    if (props.buttonCallback) {
        props.buttonCallback();
    } else {
        router.push({name: "dashboard"});
    }
}

console.log(route.query)
</script>


<template>
    <div class="flex justify-center items-center flex-col font-medium mt-20 text-black text-center text-xl">
        {{ props.errorMessage }}
        <GenericButton
            :callback="handleButtonClick"
            type="school"
        >
            <div class="font-semibold px-4 py-2 text-md">
                {{ buttonMessage }}
            </div>
        </GenericButton>
    </div>
</template>

