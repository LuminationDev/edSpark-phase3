<script setup>
import BaseBreadcrumb from "@/js/components/bases/BaseBreadcrumb.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import EventSingleExtraContentRenderer from "@/js/components/events/EventSingleExtraContentRenderer.vue";
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";
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
const getEventColorTheme = (eventType) => {
    if(eventType === 'Virtual'){
        return 'red'
    } else if(eventType === 'Hybrid'){
        return 'purple'
    } else{
        return 'blue'
    }
}

const getEventBackgroundColorTheme = (eventType) => {
    let colorKey = getEventColorTheme(eventType)
    return "bg-[" + schoolColorTheme[colorKey]['med'] + "]"
}


</script>
<template>
    <BaseSingle content-type="event">
        <template #hero="{contentFromBase}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
                :swoosh-color-theme="getEventColorTheme(contentFromBase.type)"
            >
                <template #breadcrumb>
                    <BaseBreadcrumb
                        :child-page="contentFromBase.title"
                        parent-page="events"
                        :color-theme="getEventColorTheme(contentFromBase.type)"
                    />
                </template>
                <template #titleText>
                    {{ contentFromBase['title'] }}
                </template>

                <template #additionalTags>
                    <div
                        class="
                            grid
                            grid-cols-3
                            gap-2
                            place-items-center
                            max-w-full
                            text-white
                            typeAndTags
                            w-full
                            md:!grid-cols-4
                            lg:!grid-cols-5
                            xl:!grid-cols-6
                            ">
                        <div
                            class="EventTypeTag font-semibold grid place-items-center mb-2 mr-2 py-2 rounded-2xl w-full"
                            :class="getEventBackgroundColorTheme(contentFromBase['type'])"
                        >
                            {{ contentFromBase['type'] }}
                        </div>
                        <div
                            v-for="(tag, index) in contentFromBase['tags']"
                            :key="index"
                            class="
                                EventTags
                                bg-gray-50
                                font-semibold
                                grid
                                place-items-center
                                mb-2
                                mr-2
                                py-2
                                rounded-2xl
                                text-black
                                w-full
                                "
                        >
                            {{ tag }}
                        </div>
                    </div>
                </template>

                <template #authorName>
                    <div
                        v-if="contentFromBase['author'] && contentFromBase['author']"
                        class="EventHeroAuthorContainer flex flex-col"
                    >
                        <div class="flex items-center flex-row">
                            <div class="flex items-center h-20 mx-4 smallPartnerLogo w-24">
                                <img
                                    :src="`${imageURL}/${String(contentFromBase['author']['author_logo'])}`"
                                    alt="logo"
                                    class="bg-center h-24 object-contain rounded-full w-24"
                                >
                            </div>
                            <div class="authorName flex flex-col pt-6">
                                <div class="mb-2 text-2xl">
                                    {{ contentFromBase['author']['author_name'] }}
                                </div>
                                <div
                                    v-if="!(contentFromBase['author']['author_type'] === 'user')"
                                    class="hover:cursor-pointer hover:text-red-200"
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
                    <div class="eventDetails flex flex-col gap-2 here">
                        <div class="flex items-center flex-row">
                            <CalendarIcon class="mr-2" />
                            {{ new Date(Date.parse(contentFromBase['start_date'])).toLocaleDateString('en-GB', {
                                day: '2-digit', month: 'long', year: 'numeric'
                            }) }}
                        </div>
                        <div class="flex items-center flex-row">
                            <TimeIcon class="flex justify-center items-center mr-2" />
                            {{ new Date(Date.parse(contentFromBase['start_date'])).toLocaleString('en-US',{ hour: 'numeric', minute: 'numeric', hour12: true } ) }}
                            {{ "-" }}
                            {{ new Date(Date.parse(contentFromBase['end_date'])).toLocaleString('en-US',{ hour: 'numeric', minute: 'numeric', hour12: true } ) }}
                        </div>
                        <div class="flex items-center flex-row">
                            <LocationIcon class="mr-2" />
                            <!--                            {{ contentFromBase['type'] === 'in person' ? contentFromBase['location']['address'] : contentFromBase['type'] }}-->
                            {{ contentFromBase['location']['address'] ? contentFromBase['location']['address'] : 'Online' }}
                        </div>
                    </div>
                    <!--                    <div v-html="purify.sanitize(contentFromBase['excerpt'])" />-->
                </template>
            </BaseHero>
        </template>

        <template #content="{contentFromBase}">
            <div
                class="eventSingleContent flex flex-col overflow-hidden px-8 py-20 w-full lg:!flex-row"
            >
                <!--    Content of the Advice    -->
                <div class="flex flex-col flex-wrap px-2 w-full lg:!w-2/3">
                    <div class="border-b-4 border-black border-dashed flex font-semibold text-2xl uppercase">
                        Details
                    </div>
                    <div
                        class="flex content-paragraph flex-col max-w-full overflow-hidden text-lg"
                        v-html="purify.sanitize(contentFromBase['content'])"
                    />
                    <template
                        v-for="(content,index) in contentFromBase['extra_content']"
                        :key="index"
                    >
                        <EventSingleExtraContentRenderer :content="content" />
                    </template>
                </div>
                <!--      Curated Content      -->
                <div class="flex flex-col p-4 w-full lg:!w-1/3">
                    <EventsLocation
                        :location-type="contentFromBase['type']"
                        :location-info="contentFromBase['location']"
                    />
                    <EventsRsvp
                        :author-info="contentFromBase['author']"
                        :event-id="contentFromBase['id']"
                        :location-type="contentFromBase['type']"
                        :event-start-date="contentFromBase['start_date']"
                        :event-end-date="contentFromBase['end_date']"
                    />
                </div>
            </div>
            <div class="flex overflow-scroll" />
        </template>
    </BaseSingle>
</template>


<style scoped>
.eventSingleContent :deep(p){
    margin-top: 16px;
    text-align: justify;
}

</style>