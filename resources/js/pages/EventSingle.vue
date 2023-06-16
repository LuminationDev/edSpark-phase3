<script setup>
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import EventSingleExtraContentRenderer from "@/js/components/events/EventSingleExtraContentRenderer.vue";


</script>
<template>
    <BaseSingle content-type="event">
        <template #hero="{contentFromBase}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
            >
                <template #titleText>
                    {{ contentFromBase['event_title'] }}
                </template>
                <template #subtitleText1>
                    {{ contentFromBase['created_at'] }}
                </template>
                <template #subtitleText2>
                    <div v-html="contentFromBase['event_excerpt']" />
                </template>
            </BaseHero>
        </template>

        <template #content="{contentFromBase}">
            <div
                class="eventSingleContent p-4 px-8 flex flex-row w-full overflow-hidden"
            >
                <!--    Content of the Advice    -->
                <div class="w-2/3 flex flex-col flex-wrap py-4 px-2">
                    <div class="text-2xl flex font-bold uppercase">
                        Details
                    </div>
                    <div
                        class="text-lg flex content-paragraph overflow-hidden max-w-full"
                        v-html="contentFromBase['event_content']"
                    />
                    <template
                        v-for="(content,index) in contentFromBase['extra_content']"
                        :key="index"
                    >
                        <EventSingleExtraContentRenderer :content="content" />
                    </template>
                </div>
                <!--      Curated Content      -->
                <div class="w-1/3 flex flex-col">
                    <!--                   <AdviceSingleCuratedContent />-->
                </div>
            </div>
        </template>
    </BaseSingle>
</template>
