<script setup>
import purify from "dompurify";

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
    }
});

</script>

<template>
    <div class="extraResourcesRenderer mb-10 mt-4 w-full">
        <div class="bg-main-navy px-6 py-6 text-white">
            <div class="flex flex-row gap-4 pb-6 place-items-baseline">
                <h1
                    class="font-bold text-3xl whitespace-nowrap"
                >
                    {{ props.itemTitle || "Extra Resources" }}
                </h1>
                <div class="bg-white h-1 w-full" />
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

    a {
        color: #FCFCFD;
        opacity: .75;

        &:hover {
            text-decoration: underline !important;
        }
    }

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


