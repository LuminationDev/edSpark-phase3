<script setup>
import {useRouter} from "vue-router";

import BaseBreadcrumb from "@/js/components/bases/BaseBreadcrumb.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseSingleProfilePicture from "@/js/components/bases/BaseSingleProfilePicture.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import EventsEMS from "@/js/components/events/EventsEMS.vue";
import EventSingleExtraContentRenderer from "@/js/components/events/EventSingleExtraContentRenderer.vue";
import EventsLocation from "@/js/components/events/EventsLocation.vue";
import EventTypeTag from "@/js/components/events/EventTypeTag.vue";
import LabelRowContentDisplay from "@/js/components/global/LabelRowContentDisplay.vue";
import CalendarIcon from "@/js/components/svg/event/CalendarIcon.vue";
import LocationIcon from "@/js/components/svg/event/LocationIcon.vue";
import TimeIcon from "@/js/components/svg/event/TimeIcon.vue";
import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";

const router = useRouter()

const handleClickViewProfile = (author_id, author_type) => {
    router.push(`/${author_type}/${author_id}`)
}

const handleSendFeedbackButton = () => {
    console.log('Feedback Button Pressed!')
}
</script>
<template>
    <BaseSingle content-type="event">
        <template #hero="{contentFromBase}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
            >
                <template #breadcrumb>
                    <BaseBreadcrumb
                        :child-page="contentFromBase.title"
                        parent-page="events"
                        parent-page-link="browse/event"
                    />
                </template>
                <template #titleText>
                    {{ contentFromBase['title'] }}
                </template>

                <template #additionalTags>
                    <div
                        class="gap-2 hidden max-w-full typeAndTags w-fit"
                    >
                        <div class="bg-white h-fit rounded-full w-fit">
                            <EventTypeTag
                                class="!m-0"
                                :event-type="contentFromBase.type"
                            />
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
                        class="EventHeroAuthorContainer flex flex-col gap-8 mt-2"
                    >
                        <div class="flex items-center flex-row gap-8 my-4">
                            <BaseSingleProfilePicture
                                :author-name="contentFromBase['author']['author_name']"
                                :author-logo-url="String(contentFromBase['author']['author_logo'])"
                            />
                            <div class="authorName flex flex-col pt-6">
                                <div class="mb-2 text-2xl">
                                    {{ contentFromBase['author']['author_name'] }}
                                </div>
                                <div
                                    v-if="!(contentFromBase['author']['author_type'] === 'user')"
                                    class="hover:cursor-pointer"
                                >
                                    <button
                                        class="bg-secondary-coolGrey px-3 py-1 rounded text-black text-sm"
                                        @click="() => handleClickViewProfile(contentFromBase['author']['author_id'],contentFromBase['author']['author_type'])"
                                    >
                                        View profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template #subtitleText2>
                    <div class="eventDetails flex flex-col gap-2 here">
                        <div class="flex items-center flex-row">
                            <CalendarIcon class="fill-white mr-2" />
                            {{
                                new Date(Date.parse(contentFromBase['start_date'])).toLocaleDateString('en-GB', {
                                    day: '2-digit', month: 'long', year: 'numeric'
                                })
                            }}
                        </div>
                        <div class="flex items-center flex-row">
                            <TimeIcon class="fill-white flex justify-center items-center mr-2" />
                            {{
                                new Date(Date.parse(contentFromBase['start_date'])).toLocaleString('en-US', {
                                    hour: 'numeric',
                                    minute: 'numeric',
                                    hour12: true
                                })
                            }}
                            {{ "-" }}
                            {{
                                new Date(Date.parse(contentFromBase['end_date'])).toLocaleString('en-US', {
                                    hour: 'numeric',
                                    minute: 'numeric',
                                    hour12: true
                                })
                            }}
                        </div>
                        <div class="flex items-center flex-row">
                            <LocationIcon class="fill-white mr-2" />
                            <!--                            {{ contentFromBase['type'] === 'in person' ? contentFromBase['location']['address'] : contentFromBase['type'] }}-->
                            {{
                                contentFromBase['location']['address'] ? contentFromBase['location']['address'] : 'Online'
                            }}
                        </div>
                        <LabelRowContentDisplay :labels-array="contentFromBase['labels']" />
                    </div>
                    <!--                    <div v-html="purify.sanitize(contentFromBase['excerpt'])" />-->
                </template>
            </BaseHero>
        </template>

        <template #content="{contentFromBase}">
            <div
                class="eventSingleContent flex flex-col overflow-hidden px-8 w-full lg:!flex-row"
            >
                <!--    Content of the Advice    -->
                <div class="flex flex-col flex-wrap pl-6 px-12 w-full lg:!w-2/3">
                    <div class="border-b-2 border-black flex font-semibold text-2xl">
                        Details
                    </div>
                    <div
                        class="flex content-paragraph flex-col max-w-full overflow-hidden text-lg"
                        v-html="edSparkContentSanitizer(contentFromBase['content'])"
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
                    <EventsEMS
                        :author-info="contentFromBase['author']"
                        :event-id="contentFromBase['id']"
                        :location-type="contentFromBase['type']"
                        :event-start-date="contentFromBase['start_date']"
                        :event-end-date="contentFromBase['end_date']"
                    />
                </div>
            </div>
        </template>
    </BaseSingle>
</template>


<style scoped>
.eventSingleContent :deep(p) {
    margin-top: 16px;
    text-align: justify;
}


</style>
