<script setup>
import {computed, onMounted, ref} from 'vue'

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
        validator: function (value) {
            return schoolColorKeys.includes(value);
        },
        default: 'teal'
    }
})
const textColorTheme = ref('')
const textHoverColorTheme = ref('')

onMounted(() => {
    if (schoolColorKeys.includes(props.colorTheme)) {
        textColorTheme.value = "text-[" + schoolColorTheme[props.colorTheme]['med'] + "]"
    } else {
        textColorTheme.value = 'text-main-teal'
    }

    if (schoolColorKeys.includes(props.colorTheme)) {
        textHoverColorTheme.value = "hover:text-[" + schoolColorTheme[props.colorTheme]['med'] + "]"
    } else {
        textHoverColorTheme.value = 'hover:text-main-teal'
    }

})


</script>

<template>
    <div class="flex mb-4">
        <div class="flex flex-row gap-2 h-[24px] place-items-center text-[10px] md:!text-sm">
            <router-link to="/dashboard">
                <p
                    class="text-white"
                    :class="textHoverColorTheme"
                >
                    Home
                </p>
            </router-link>
            <ChevronRight class="h-3 w-3" />
            <router-link :to="`/${props.parentPageLink ? props.parentPageLink : props.parentPage}`">
                <p
                    class="capitalize text-white"
                    :class="textHoverColorTheme"
                >
                    {{ props.parentPage }}
                </p>
            </router-link>
            <ChevronRight class="h-3 w-3" />

            <p
                class="capitalize w-full"
                :class="textColorTheme"
            >
                {{ props.childPage }}
            </p>
        </div>
    </div>
</template>
