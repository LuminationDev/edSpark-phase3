<script setup>
import {ref, computed} from 'vue'
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";

const props = defineProps({

})

const emits = defineEmits([])
</script>

<template>
    <BaseSingle content-type="partner">
        <template #hero="{contentFromBase}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
            >
                <template #titleText>
                    {{ contentFromBase['name'] }}
                </template>
                <template #authorName>
                    {{ contentFromBase['name'] }}
                </template>
            </BaseHero>
        </template>

        <!--        <template #content="{contentFromBase}">-->
        <!--            <div-->
        <!--                class="adviceSingleContent p-4 px-8 flex flex-row w-full overflow-hidden mt-14"-->
        <!--            >-->
        <!--                &lt;!&ndash;    Content of the Advice    &ndash;&gt;-->
        <!--                <div class="w-2/3 flex flex-col flex-wrap py-4 px-2">-->
        <!--                    <div class="text-2xl flex font-bold uppercase">-->
        <!--                        Getting started-->
        <!--                    </div>-->
        <!--                    <div-->
        <!--                        class="text-lg flex flex-col content-paragraph overflow-hidden max-w-full"-->
        <!--                        v-html="contentFromBase['post_content']"-->
        <!--                    />-->
        <!--                    <template-->
        <!--                        v-for="(content,index) in contentFromBase['extra_content']"-->
        <!--                        :key="index"-->
        <!--                    >-->
        <!--                        <AdviceSingleExtraContentRenderer :content="content" />-->
        <!--                    </template>-->
        <!--                </div>-->
        <!--                &lt;!&ndash;      Curated Content      &ndash;&gt;-->
        <!--                <div class="w-1/3 flex flex-col">-->
        <!--                    <AdviceSingleCuratedContent />-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </template>-->
    </BaseSingle>
</template>
