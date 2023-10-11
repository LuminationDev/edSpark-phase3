<script setup lang="ts">

import {delegatesUser} from "@/js/components/schools/delegatesPanel/SchoolDelegationPanel.vue";
import {imageURL} from "@/js/constants/serverUrl";

const props = defineProps({
    user:{
        type: Object as () => delegatesUser,
        required : true
    },
    clickCallback : {
        type : Function,
        required: true
    }
})

</script>

<template>
    <div
        class="bg-white hover:bg-gray-200 flex flex-row innerListItem mb-2 px-2 rounded-xl w-24 w-full"
        @click="() => clickCallback(user.id)"
    >
        <div class="basis-1/4 containerPictureProfile">
            <img
                v-if="user.userAvatar"
                :src="`${imageURL}/${user.userAvatar}`"
                alt="photo"
                class="h-20 object-center object-cover rounded-full w-20"
            >
            <div
                v-else
                class="grid place-items-center h-20 object-center object-cover rounded-full w-20"
            >
                {{ user.display_name }}
            </div>
        </div>
        <div class="basis-3/4 flex justify-center flex-col nameAndTitle">
            <div class="Name font-semibold">
                {{ user.name }}
            </div>
            <div class="font-semibold roleTitle">
                {{ user.role }}
            </div>
        </div>
    </div>
</template>
