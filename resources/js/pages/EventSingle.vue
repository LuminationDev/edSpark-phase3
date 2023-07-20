<script setup>
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import EventSingleExtraContentRenderer from "@/js/components/events/EventSingleExtraContentRenderer.vue";
import purify from "dompurify";
import {imageURL} from "@/js/constants/serverUrl";
import TimeIcon from "@/js/components/svg/event/TimeIcon.vue";
import CalendarIcon from "@/js/components/svg/event/CalendarIcon.vue";
import LocationIcon from "@/js/components/svg/event/LocationIcon.vue";
import {useRouter} from "vue-router";
import EventsLocation from "@/js/components/events/EventsLocation.vue";
import EventsRsvp from "@/js/components/events/EventsRsvp.vue";

const router = useRouter()
const handleClickViewProfile = (author_id, author_type) => {
    router.push(`/${author_type}/${author_id}` )
}
</script>
<template>
    <BaseSingle content-type="event">
        <template #hero="{contentFromBase}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
                :swoosh-color-theme="'red'"
            >
                <template #titleText>
                    {{ contentFromBase['event_title'] }}
                </template>

                <template #additionalTags>
                    <div class=" flex flex-row typeAndTags text-white">
                        <div class="EventTypeTag bg-pink-300 py-2 px-8 rounded-2xl mr-2 font-semibold">
                            {{ contentFromBase['event_type'] }}
                        </div>
                        <div
                            v-for="(tag, index) in ['Advice', 'AR', 'VR']"
                            :key="index"
                            class="EventTags bg-slate-300 py-2 px-8 rounded-2xl mr-2 font-semibold"
                        >
                            {{ tag }}
                        </div>
                    </div>
                </template>

                <template #authorName>
                    <div
                        v-if="contentFromBase['author'] && contentFromBase['author']"
                        class="EventHeroAuthorContainer flex flex-col "
                    >
                        <div class="flex flex-row items-center">
                            <div class="smallPartnerLogo w-24 h-20 flex items-center mx-4">
                                <img
                                    :src="`${imageURL}/${String(contentFromBase['author']['author_logo'])}`"
                                    alt="logo"
                                >
                            </div>
                            <div class="authorName flex flex-col pt-6">
                                <div class="mb-2 text-2xl">
                                    {{ contentFromBase['author']['author_name'] }}
                                </div>
                                <div
                                    v-if="!(contentFromBase['author']['author_type'] === 'user')"
                                    class="hover:text-red-200 hover:cursor-pointer "
                                >
                                    <button @click="() => handleClickViewProfile(contentFromBase['author']['author_id'],contentFromBase['author']['author_type'])">
                                        View Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template #subtitleText2>
                    <div class="eventDetails here flex gap-2 flex-col">
                        <div class="flex flex-row items-center">
                            <CalendarIcon class="mr-2" />
                            {{ new Date(Date.parse(contentFromBase['start_date'])).toLocaleDateString('en-GB', {
                                day: '2-digit', month: 'long', year: 'numeric'
                            }) }}
                        </div>
                        <div class="flex flex-row items-center">
                            <TimeIcon class="mr-2 flex justify-center items-center " />
                            {{ new Date(Date.parse(contentFromBase['start_date'])).toLocaleString('en-US',{ hour: 'numeric', minute: 'numeric', hour12: true } ) }}
                            {{ "-" }}
                            {{ new Date(Date.parse(contentFromBase['end_date'])).toLocaleString('en-US',{ hour: 'numeric', minute: 'numeric', hour12: true } ) }}
                        </div>
                        <div class="flex flex-row items-center">
                            <LocationIcon class="mr-2" />
                            <!--                            {{ contentFromBase['event_type'] === 'in person' ? contentFromBase['event_location']['address'] : contentFromBase['event_type'] }}-->
                            {{ contentFromBase['event_location']['address'] ? contentFromBase['event_location']['address'] : 'Online' }}
                        </div>
                    </div>
                    <!--                    <div v-html="purify.sanitize(contentFromBase['event_excerpt'])" />-->
                </template>
            </BaseHero>
        </template>

        <template #content="{contentFromBase}">
            <div
                class="eventSingleContent py-20 px-8 flex flex-row w-full overflow-hidden"
            >
                <!--    Content of the Advice    -->
                <div class="w-2/3 flex flex-col flex-wrap px-2">
                    <div class="text-2xl flex border-b-4 border-black border-dashed font-semibold uppercase">
                        Details
                    </div>
                    <div
                        class="text-lg flex content-paragraph overflow-hidden max-w-full"
                        v-html="purify.sanitize(contentFromBase['event_content'])"
                    />
                    <template
                        v-for="(content,index) in contentFromBase['extra_content']"
                        :key="index"
                    >
                        <EventSingleExtraContentRenderer :content="content" />
                    </template>
                </div>
                <!--      Curated Content      -->
                <div class="w-1/3 flex flex-col p-4">
                    <EventsLocation
                        :location-type="contentFromBase['event_type']"
                        :location-info="contentFromBase['event_location']"
                    />
                    <EventsRsvp
                        :author-info="contentFromBase['author']"
                        :event-id="contentFromBase['event_id']"
                        :location-type="contentFromBase['event_type']"
                        :event-start-date="contentFromBase['start_date']"
                        :event-end-date="contentFromBase['end_date']"
                    />
                </div>
            </div>
            <div class="overflow-scroll flex" />
        </template>
    </BaseSingle>
</template>


<style scoped>
:deep(p){
    margin-top: 16px;
    text-align: justify;
}

</style>