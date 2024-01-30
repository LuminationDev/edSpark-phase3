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
    // if (schoolColorKeys.includes(props.colorTheme)) {
    //     return "text-[" + schoolColorTheme[props.colorTheme]['light'] + "]";
    //
    // } else {
    //     return "text-[" + schoolColorTheme['teal']['light'] + "]";
    // }
    return 'text-white'

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
    <div class="flex grow flex-row gap-2 mb-4 mt-6 place-items-center text-sm w-full">
        <router-link to="/dashboard">
            <p
                class="text-white"
                :class="customTextHover"
            >
                Home
            </p>
        </router-link>
        <ChevronRight class="h-3 w-3" />
        <router-link
            class="flex grow"
            :to="`/${props.parentPageLink ? props.parentPageLink : props.parentPage}`"
        >
            <div
                class="flex grow text-white"
                :class="{'w-32 ' : props.parentPage.split('').length > 15, customTextHover : true}"
            >
                {{ props.parentPage }}
            </div>
        </router-link>
        <ChevronRight class="h-3 w-3" />

        <p
            class="truncate w-full"
            :class="customText"
        >
            {{ props.childPage }}
        </p>
    </div>
</template>
<style scoped>
a {
    text-decoration: none !important
}
</style>