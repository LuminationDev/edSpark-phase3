<script setup lang="ts">
import { useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import FourOFourRobot from "@/js/components/svg/404Robot/FourOFourRobot.vue";
import ChevronLeftNavIcon from "@/js/components/svg/ChevronLeftNavIcon.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";

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

const handleButtonClick = (): void => {
    if (props.buttonCallback) {
        props.buttonCallback();
    } else {
        router.push({name: "dashboard"});
    }
}

</script>


<template>
    <BaseLandingHero
        :title="LandingHeroText['404']['title']"
        :title-paragraph="LandingHeroText['404']['subtitle']"
        :background-color="'navy'"
    >
        <template #additionalText>
            <span
                class="cursor-pointer flex items-end flex-row text-white underline"
                @click="handleButtonClick"
            >
                <chevron-left-nav-icon class="h-5 pb-1 w-5" />
                {{ `Go back to dashboard` }}

            </span>
        </template>
        <template #robotIllustration>
            <FourOFourRobot class="absolute top-16 left-36" />
        </template>
    </BaseLandingHero>
</template>

