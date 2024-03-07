<script setup>


import {storeToRefs} from "pinia";
import {ref} from "vue";

import UserNotificationItemSmall
    from "@/js/components/userprofile/userprofileupdate/usernotification/UserNotificationItemSmall.vue";
import UserNotificationLinearLayout
    from "@/js/components/userprofile/userprofileupdate/usernotification/UserNotificationLinearLayout.vue";
import UserProfileContentContainer from "@/js/components/userprofile/userprofileupdate/UserProfileContentContainer.vue";
import {useUserStore} from "@/js/stores/useUserStore";

const {currentUser} = storeToRefs(useUserStore())
const leftHeadingNotification = ref('Notifications')
const leftDescriptionNotification = ref('Stay up to date with the latest activities.')
const reloadKey = ref(0)
</script>

<template>
    <div class="UserProfilePage h-full w-full">
        <div
            class="bg-bottom bg-cover border-b-[1px] h-[12vh] profileCoverImage relative top-0 left-0 w-full"
        />
        <div class="flex flex-col profileBodyContainer w-full">
            <div class="flex flex-col min-h-[70vh] pb-10 px-4 md:!px-8 lg:!px-24">
                <div class="UserProfileSelectionMenuContainer flex flex-col mt-1">
                    <div>
                        <UserProfileContentContainer
                            id="reloadableDiv"
                            :key="reloadKey"
                            :left-heading="leftHeadingNotification"
                            :left-description="leftDescriptionNotification"
                        >
                            <template #content>
                                <div class="flex flex-col gap-10">
                                    <div class="ml-4 mt-2">
                                        <div class="flex flex-row">
                                            <div class="ml-1">
                                                Unread Notifications
                                            </div>
                                        </div>
                                        <div>
                                            <UserNotificationLinearLayout
                                                notification-size="large"
                                                notification-unread="unread"
                                            />
                                        </div>
                                    </div>
                                    <div class="ml-4 mt-2">
                                        <div class="flex flex-row">
                                            <div class="ml-1">
                                                Read Notifications
                                            </div>
                                        </div>
                                        <div>
                                            <UserNotificationLinearLayout
                                                notification-size="large"
                                                notification-unread="read"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </userprofilecontentcontainer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
