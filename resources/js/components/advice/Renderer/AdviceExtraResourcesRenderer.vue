<script setup>
const props = defineProps({
    itemArray: {
        type: Array,
        required: true

    }
});

console.log(props.itemArray);
</script>

<template>
    <div class="extraResourcesRenderer mb-20 w-full">
        <div class="flex flex-row gap-4 mb-2 place-items-baseline">
            <h1 class="font-bold text-[24px] text-black whitespace-nowrap">
                Extra Resources
            </h1>
            <div class="bg-black h-[3px] w-full" />
        </div>

        <div class="bg-main-navy px-6 py-12 text-white">
            <template
                v-for="(res,index) in itemArray"
                :key="index"
            >
                <h3 class="font-bold mb-3 text-[21px]">
                    {{ res.heading }}
                </h3>
                <div v-html="res.content" />
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


