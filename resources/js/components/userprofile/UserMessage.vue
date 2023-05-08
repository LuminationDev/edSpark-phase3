<script setup>
    /**
     * Import Dependencies
     */
import { ref,computed,onMounted } from 'vue';
import { storeToRefs } from 'pinia';

/**
 * Import Stores
 */
import { useUserStore } from '../../stores/useUserStore';
import { useSiteStore } from '../../stores/useSiteStore';

/**
 * Import Components
 */
import UserInfoProfileFields from '../../components/global/UserInfoProfileFields.vue';

/**
 * SVG's
 */
import Save from '../../components/svg/Save.vue';
import Close from '../../components/svg/Close.vue';
import Edit from '../../components/svg/Edit.vue';


const userStore = useUserStore();
const siteStore = useSiteStore();
const isEditAvatar = ref(false);
const updatedName = ref('');

const handleSaveChange = () => {
    console.log('Handle save values here');
    // userStore.updateUser(updatedName.value);
};

const { currentUser, notifications } = storeToRefs(userStore)

onMounted(() => {
    userStore.fetchAllNotifications(currentUser.value.id)
})


</script>
<template>
    <div class="h-full w-full bg-white">
        <div class="w-full flex flex-col">
            <div class="col-span-12 row-span-2 grid grid-cols-6 justify-center mt-[100px] px-[81px]">
                <div
                    class="col-span-1 cursor-pointer h-[200px] w-[200px] bg-orange-500 rounded-full flex justify-center place-items-center relative"
                    @mouseenter="isEditAvatar = !isEditAvatar"
                    @mouseleave="isEditAvatar = !isEditAvatar"
                >
                    <h1 class="text-[72px] text-white font-bold" />
                    <div
                        v-if="isEditAvatar"
                        class="absolute w-[48px] h-[48px] rounded-full bg-white flex justify-center place-items-center top-0 right-[24px] border border-black border-2"
                    >
                        <Edit class="w-[24px] h-24px" />
                    </div>
                </div>

                <!-- User information component -->
                <!-- Use slots -->
            </div>

            <div class="col-span-12 row-span-4 mx-20">
                <!-- Notifications -->
                <div class="mt-[100px] px-[81px]">
                    <h3 class="text-[24px] font-normal">
                        Notifications ({{ notifications.count }})
                    </h3>
                    <div v-for="(item, index) in notifications.result">
                        {{ item.data }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
