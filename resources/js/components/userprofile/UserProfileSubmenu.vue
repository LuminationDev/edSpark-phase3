<script setup>
import {useRouter, useRoute} from "vue-router";
import {storeToRefs} from "pinia";
import {onMounted} from "vue";

import { useUserStore} from "../../stores/useUserStore";
const props = defineProps({
    submenuItems:{
        type: Array,
        required: true
    }
})
const userStore = useUserStore();
const { currentUser, notifications } = storeToRefs(userStore)

const router = useRouter()
const route = useRoute()
const handleClickSubmenu = (target) => {
    const userId = route.params.userId
    let targetPageName = ""
    if(target === 'Info'){
        targetPageName= 'userProfileInfo'
    } else if (target === 'Work'){
        targetPageName = 'userProfileWork'
    } else if (target === 'Messages'){
        targetPageName = 'userProfileMessages'
    }
    console.log('inside handle click ')

    router.push({name: targetPageName , params: {userId : userId}})
}

onMounted( () => {
    userStore.fetchAllNotifications(currentUser.value.id)
})

</script>
<template>
    <div class="UserProfileSubmenuContainer flex flex-row w-full h-16 bg-primary-darkTeal rounded-t-xl items-center text-white text-lg uppercase font-semibold">
        <div
            v-for="(item,index) in props.submenuItems"
            :key="index"
            class="submenuItems mx-4 hover:text-primary-lightTeal hover:cursor-pointer"
            @click="() => handleClickSubmenu(item)"
        >
            {{ item }}
            <span v-if="item === 'Messages'">
                ({{ notifications.count }})
            </span>
        </div>
    </div>
</template>
