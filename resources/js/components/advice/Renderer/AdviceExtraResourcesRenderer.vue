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
    <div class="extraResourcesRenderer w-full mb-20">
        <div class="flex flex-row gap-4 place-items-baseline mb-2">
            <h1 class="text-[24px] font-bold text-black uppercase whitespace-nowrap">
                Extra Resources
            </h1>
            <div class="w-full h-[3px] bg-black" />
        </div>

        <div class="bg-primary-navy px-6 py-12 text-white">
            <h3 class="text-[21px] font-bold mb-3">
                {{ itemArray.heading }}
            </h3>
            <div v-html="itemArray.content" />
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
