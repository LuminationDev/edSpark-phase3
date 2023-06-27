<script setup>

import SoftwareCard from '../software/SoftwareCard.vue';
import SoftwareRobot from "@/js/components/svg/SoftwareRobot.vue";

import CardLoading from '../card/CardLoading.vue';
import GenericCard from '../card/GenericCard.vue';
import SoftwareCardIcon from '../software/SoftwareCardIcon.vue';

import { storeToRefs } from 'pinia';
import { useUserStore } from '../../stores/useUserStore';

const {currentUser } = storeToRefs(useUserStore())

const props = defineProps({
    softwares: {
        type: Array,
        required: true
    },
    softwareLoading: {
        type: Boolean,
        rqeuired: true
    }
});

const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;

const getLikeBookmarkData = (cardData) => {
    return {
        post_id: cardData.event_id,
        user_id: currentUser.value.id, // to be replaced with userId from userStore
        post_type: 'event'
    }
};

const handleClickCard = () => {

}

</script>

<template>
    <div class="py-8 px-huge">
        <div class="grid grid-cols-12 gap-[24px] w-full h-full relative group/bg">
            <div
                class="absolute softwareRobot -z-10 transition-all duration-700 opacity-10 top-1/2 -translate-y-1/2 left-1/3 group-hover/bg:left-[15%] group-hover/bg:scale-125"
            >
                <SoftwareRobot />
            </div>
            <div class="col-span-5 flex place-items-center">
                <div class="w-full h-[700px] grid grid-cols-6 grid-rows-2">
                    <div class="col-span-1 row-span-1">
                        <img
                            class=""
                            src="../../../assets/images/departmentProvidedAndApproved.png"
                            alt="Department Approved And Provided"
                        >
                    </div>
                    <div class="col-span-5 row-span-1 grid grid-rows-3 h-full">
                        <div class="row-span-1 px-8">
                            <h5 class="text-[21px] font-semibold pt-4">
                                Department Provided
                            </h5>
                            <p class="w-9/12">
                                These applications are provided by the department
                                at no cost to schools
                            </p>
                        </div>
                        <div class="row-span-1" />
                        <div class="row-span-1 flex flex-col h-full px-8">
                            <h5 class="text-[21px] font-semibold mt-auto">
                                Department Approved
                            </h5>
                            <p class="pb-4 w-9/12">
                                These applications have been risk assessed and can
                                be safely used in schools
                            </p>
                        </div>
                    </div>
                    <div class="col-span-2 row-span-1 flex">
                        <img
                            class="h-full pt-12 pb-8 ml-auto"
                            src="../../../assets/images/negotiatedDeals.png"
                            alt="Negotiated Deals"
                        >
                    </div>
                    <div class="col-span-3 row-span-1 flex flex-col pl-8">
                        <h5 class="text-[18px] font-semibold mt-auto">
                            Negotiated Deals
                        </h5>
                        <p class="pb-4 w-[85%]">
                            Still risk assessed, these applications have an agreement
                            in place for schools to receive better value. Schools will
                            need to fund purchases
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-span-7" v-if="!softwareLoading">
                <div class="flex flex-row flex-1 flex-wrap justify-end gap-[24px] w-full h-full">
                    <GenericCard
                        v-for="(software, index) in softwares.slice(0, colCount)"
                        :key="software.post_id"
                        :title="software.post_title"
                        :display-content="software.post_excerpt"
                        :display-author="software.post_author"
                        :display-date="software.created_at"
                        :cover-image="software.cover_image"
                        :like-bookmark-data="getLikeBookmarkData(software)"
                        :click-callback="handleClickCard"
                    >
                    <template #icon>
                        <SoftwareCardIcon
                            class="icon absolute -top-6 -right-6 "
                            :software-icon-name="software['software_type'][0]"
                        />
                    </template>
                    </GenericCard>
                </div>
            </div>

            <div
                class="col-span-7"
                v-else
            >
                <div class="flex flex-row flex-1 gap-4 w-full h-full">
                    <CardLoading class="" :number-per-row="2" :number-of-rows="2" :additional-classes="' w-full'" />
                </div>
            </div>
        </div>
    </div>
</template>
