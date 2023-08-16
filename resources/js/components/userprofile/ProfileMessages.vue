<script setup>
/**
 * Import dependencies
 */
import { ref, computed, onMounted } from 'vue';
import { storeToRefs} from "pinia";

/**
 * import stores
 */
import { useUserStore} from "../../stores/useUserStore";

const userStore = useUserStore();

const { currentUser, notifications } = storeToRefs(userStore)

onMounted(() => {
    userStore.fetchAllNotifications(currentUser.value.id)
})
</script>
<template>
    <div class="bg-main-teal/80 flex flex-col h-44 min-h-44 profileMessageContainer pt-4 px-6 py-6">
        <div
            v-for="(item, index) in notifications.result"
            :key="index"
            class="bg-green-100 py-2"
        >
            <p class="font-semibold mx-4">
                {{ item.data }}
            </p>
        </div>
    </div>
</template>
