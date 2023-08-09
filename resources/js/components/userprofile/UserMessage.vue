<script setup>
    /**
     * Import Dependencies
     */
import { ref,computed,onMounted } from 'vue';
import { storeToRefs } from 'pinia';

/**
 * Import Stores
 */
import { useUserStore } from '@/js/stores/useUserStore';
import { useSiteStore } from '@/js/stores/useSiteStore';

/**
 * SVG's
 */
import Edit from '../../components/svg/Edit.vue';


const userStore = useUserStore();
const siteStore = useSiteStore();
const isEditAvatar = ref(false);
const updatedName = ref('');

const { currentUser, notifications } = storeToRefs(userStore)

onMounted(() => {
    userStore.fetchAllNotifications(currentUser.value.id)
})


</script>
<template>
    <div class="bg-white h-full w-full">
        <div class="flex flex-col w-full">
            <div class="col-span-12 grid grid-cols-6 justify-center mt-[100px] px-[81px] row-span-2">
                <div
                    class="
                        bg-orange-500
                        col-span-1
                        cursor-pointer
                        flex
                        justify-center
                        h-[200px]
                        place-items-center
                        relative
                        rounded-full
                        w-[200px]
                        "
                    @mouseenter="isEditAvatar = !isEditAvatar"
                    @mouseleave="isEditAvatar = !isEditAvatar"
                >
                    <h1 class="font-bold text-[72px] text-white" />
                    <div
                        v-if="isEditAvatar"
                        class="
                            absolute
                            top-0
                            right-[24px]
                            bg-white
                            border
                            border-2
                            border-black
                            flex
                            justify-center
                            h-[48px]
                            place-items-center
                            rounded-full
                            w-[48px]
                            "
                    >
                        <Edit class="h-24px w-[24px]" />
                    </div>
                </div>
            </div>

            <div class="col-span-12 mx-20 row-span-4">
                <!-- Notifications -->
                <div class="mt-[100px] px-[81px]">
                    <h3 class="font-normal text-[24px]">
                        Notifications ({{ notifications.count }})
                    </h3>
                    <div
                        v-for="(item, index) in notifications.result"
                        :key="index"
                    >
                        {{ item.data }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
