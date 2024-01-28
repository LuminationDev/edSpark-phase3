<script setup>
import {computed} from 'vue'

import ChevronRight from "@/js/components/svg/ChevronRight.vue";
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";

const props = defineProps({
    parentPage: {
        type: String,
        required: true
    },
    parentPageLink: {
        type: String,
        required: false,
        default: ''
    },
    childPage: {
        type: String, required: true
    },
    colorTheme: {
        type: String,
        required: false,
        default: 'teal'
    }
})

const customText = computed(() => {
    if (schoolColorKeys.includes(props.colorTheme)) {
        return "text-[" + schoolColorTheme[props.colorTheme]['light'] + "]";

    } else {
        return "text-[" + schoolColorTheme['teal']['light'] + "]";
    }

})

const customTextHover = computed(() => {
    if (schoolColorKeys.includes(props.colorTheme)) {
        return "hover:text-[" + schoolColorTheme[props.colorTheme]['light'] + "]";
    } else {
        return "hover:text-[" + schoolColorTheme['teal']['light'] + "]";
    }
})

</script>

<template>
    <div class="flex mb-4">
        <div class="flex flex-row gap-2 mb-4 mt-6 place-items-center text-[14px] w-full">
            <router-link to="/dashboard">
                <p
                    class="text-white"
                    :class="customTextHover"
                >
                    Home
                </p>
            </router-link>
            <ChevronRight class="h-3 w-3" />
            <router-link :to="`/${props.parentPageLink ? props.parentPageLink : props.parentPage}`">
                <p
                    class="capitalize text-white"
                    :class="customTextHover"
                >
                    {{ props.parentPage }}
                </p>
            </router-link>
            <ChevronRight class="h-3 w-3" />

            <p
                class="capitalize truncate w-full"
                :class="customText"
            >
                {{ props.childPage }}
            </p>
        </div>
    </div>
</template>
<style scoped>
a {
    text-decoration: none
}
</style>