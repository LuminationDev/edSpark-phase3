<script setup>
import {computed, onMounted, ref} from 'vue'

import ChevronRight from "@/js/components/svg/ChevronRight.vue";
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";

const props = defineProps({
    parentPage: {
        type: String,
        required: true
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

const gradientBg = ref('')

onMounted(() => {
    console.log('Pre-calc: '+props.colorTheme);
    var useCustomColor = false;

    if(schoolColorKeys.includes(props.colorTheme)){
        useCustomColor = true;
    } 

    console.log('Calculated: '+useCustomColor);

    if(useCustomColor){
        textColorTheme.value = "text-[" + schoolColorTheme[props.colorTheme]['light'] + "]";
        textHoverColorTheme.value = "hover:text-[" + schoolColorTheme[props.colorTheme]['light'] + "]";
    } else {
        textColorTheme.value = "text-[" + schoolColorTheme['teal']['light'] + "]";
        textHoverColorTheme.value = "hover:text-[" + schoolColorTheme['teal']['light'] + "]";
    }

})

const customText = computed(() => {
    return textColorTheme.value;
})

const customTextHover = computed(() => {
    return textHoverColorTheme.value;
})


// onMounted(() => {   
    
//     if (schoolColorKeys.includes(props.colorTheme)) {
//         textColorTheme.value = "text-[" + schoolColorTheme[props.colorTheme]['light'] + "]"
//     } else {
//         textColorTheme.value = "text-[" + schoolColorTheme['teal']['light'] + "]"
//         // textColorTheme.value = 'text-main-teal'
//     }

//     if (schoolColorKeys.includes(props.colorTheme)) {
//         textHoverColorTheme.value = "hover:text-[" + schoolColorTheme[props.colorTheme]['light'] + "]"
//     } else {
//         textHoverColorTheme.value = "hover:text-[" + schoolColorTheme['teal']['light'] + "]"
//         // textHoverColorTheme.value = 'hover:text-main-teal'
//     }
    

// })


</script>

<template>
    <div class="flex mb-4">
        <div class="flex flex-row gap-2 h-[32px] mt-6 place-items-center text-[10px] md:!text-sm">
            <router-link to="/dashboard">
                <p
                    class="text-white"
                    :class="textHoverColorTheme"
                >
                    Home
                </p>
            </router-link>
            <ChevronRight class="h-3 w-3" />
            <router-link :to="`/${props.parentPage}`">
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
