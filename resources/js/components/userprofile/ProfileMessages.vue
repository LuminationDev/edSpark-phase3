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
    <div class="profileMessageContainer flex flex-col pt-4 px-6 py-6 bg-blue-200">
        <div
            v-for="(item, index) in notifications.result"
            :key="index"
            class="py-2 bg-green-100"
        >
            <p class="font-semibold mx-4">
                {{ item.data }}
            </p>
        </div>
    </div>
</template>
