<script setup lang="ts">
import {storeToRefs} from "pinia";

import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    avatarUrl: {
        type: String,
        required: true,
    },
    clickCallback: {
        type: Function,
        required: false,
        default: () => {
        },
    },
});

const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore);
const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;
</script>

<template>
    <li
        class="
            cursor-pointer
            decoration-4
            decoration-[#B8E2DC]
            first-letter:uppercase
            flex
            justify-between
            items-center
            h-full
            mr-3
            py-4
            relative
            transition-all
            lg:!py-0
            "
        @click="clickCallback"
    >
        <div class="absolute h-12 w-12">
            <div
                class="bg-slate-200 cursor-pointer flex h-full relative rounded-full w-full z-50 hover:shadow-2xl"
                @click="clickCallback"
            >
                <img
                    v-if="props.avatarUrl"
                    class="object-center object-cover rounded-full w-full"
                    :src="`${imageURL}/${props.avatarUrl}`"
                    alt=""
                >
                <p
                    v-else
                    class="font-bold m-auto text-[1.25rem] text-white"
                >
                    {{ currentUser.display_name }}
                </p>
            </div>
        </div>
    </li>
</template>
