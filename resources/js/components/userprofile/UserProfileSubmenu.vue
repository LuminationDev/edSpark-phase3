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
    <div
        class="
            UserProfileSubmenuContainer
            bg-main-darkTeal
            flex
            justify-center
            items-center
            flex-row
            font-semibold
            h-16
            rounded-t-xl
            text-lg
            text-white
            uppercase
            w-full
            md:!justify-start
            "
    >
        <div
            v-for="(item,index) in props.submenuItems"
            :key="index"
            class="mx-4 submenuItems hover:cursor-pointer hover:text-primary-lightTeal"
            @click="() => handleClickSubmenu(item)"
        >
            {{ item }}
            <span v-show="item === 'Messages'">
                ({{ notifications.count }})
            </span>
        </div>
    </div>
</template>
