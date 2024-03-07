<script setup>
import purify from "dompurify";
import {computed} from "vue";

import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";

const props = defineProps({
    itemArray: {
        type: Array,
        required: true
    },
    itemTitle: {
        type: String,
        required: false,
        default: ''
    },
    itemBackColor:{
        type: String,
        required: false,
        default: ''
    }
});
console.log(props.itemBackColor)

const extraResourceBackground = computed(() =>{
    console.log("Got colour? "+props.itemBackColor)
    
    //light teal
    if (props.itemBackColor && props.itemBackColor == '#B2F5EA'){
        return `bg-white border-2 border-main-darkTeal text-main-darkTeal [&>div>div.h-1]:bg-main-darkTeal [&>div>div.h-1]:h-[2px]`

    //light grape
    } else if (props.itemBackColor && props.itemBackColor == '#DBCCF5'){
        return `bg-white border-2 border-secondary-grapeDark text-secondary-grapeDark [&>div>div.h-1]:bg-secondary-grapeDark [&>div>div.h-1]:h-[2px]`


    //light blueberry
    } else if (props.itemBackColor && props.itemBackColor == '#AEDCF3'){
        return `bg-white border-2 border-secondary-blueberryWeb text-secondary-blueberryWeb [&>div>div.h-1]:bg-secondary-blueberryWeb [&>div>div.h-1]:h-[2px]`


    //light navy
    } else if (props.itemBackColor && props.itemBackColor == '#6e99ce'){
        return `bg-white border-2 border-main-navy text-main-navy [&>div>div.h-1]:bg-main-navy [&>div>div.h-1]:h-[2px]`

    //cool grey
    } else if (props.itemBackColor && props.itemBackColor != '#D9DAE4'){
        return `bg-[${props.itemBackColor}] text-white`

    } else {
        return 'bg-secondary-coolGrey text-black' //was bg-main-navy
    }
})

const extraResourceLine = computed(() =>{
    if (props.itemBackColor && props.itemBackColor != '#D9DAE4'){
        return `bg-white`

    } else {
        return 'bg-black' 
    }
})

</script>

<template>
    <div class="extraResourcesRenderer mb-10 mt-4 w-full">
        <div
            class="px-6 py-6"
            :class="extraResourceBackground"
        >
            <div class="flex flex-row gap-4 pb-6 place-items-baseline">
                <h1
                    class="font-bold text-3xl whitespace-nowrap"
                >
                    {{ props.itemTitle || "Extra resources" }}
                </h1>
                <div class="h-1 w-full" 
                    :class="extraResourceLine"
                />
            </div>
            <template
                v-for="(res,index) in itemArray"
                :key="index"
            >
                <h3 class="font-bold mb-2 text-xl">
                    {{ res.heading }}
                </h3>
                <div
                    class="flex flex-col mb-5"
                    v-html="edSparkContentSanitizer(res.content)"
                />
            </template>
        </div>
    </div>
</template>

<style lang="scss">
.extraResourcesRenderer {
    ul {
        list-style: disc;
        margin: 0;
        padding: 0 0 0 36px;

        li {
            margin-bottom: 6px;
        }
    }

    ol {
        list-style: decimal;
        margin: 0;
        padding: 0 0 0 36px;

        li {
            margin-bottom: 6px;

            &::marker {
                margin-right: 6px !important;
            }
        }
    }

    // a {
    //     // color: #FCFCFD;
    //     // opacity: .75;

    //     &:hover {
    //         text-decoration: underline !important;
    //     }
    // }

    p {
        margin-bottom: 21px;

        &:nth-of-type(1) {
            margin-bottom: 0;
        }
    }

    pre {
        background: #2a2a2a;
        padding: 12px 18px;
    }
}
</style>


