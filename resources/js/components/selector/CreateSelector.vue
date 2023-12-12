<script setup lang="ts">
import {markRaw, ref} from 'vue'
import {useRoute, useRouter} from "vue-router";

import CreateSelectorItem from "@/js/components/selector/CreateSelectorItem.vue";
import AdviceBackgroundIcon from "@/js/components/svg/createSelectorIcons/AdviceBackgroundIcon.vue";
import EventBackgroundIcon from "@/js/components/svg/createSelectorIcons/EventBackgroundIcon.vue";
import HardwareBackgroundIcon from "@/js/components/svg/createSelectorIcons/HardwareBackgroundIcon.vue";
import SoftwareBackgroundIcon from "@/js/components/svg/createSelectorIcons/SoftwareBackgroundIcon.vue";

interface SelectorItemType {
    type: string,
    name: string,
    bgClass: string,
    iconComponent: any,
    strokeClass: string,
    link: string
}


const props = defineProps({})

const emits = defineEmits([])

const router = useRouter()
const route = useRoute()
const items = ref(<SelectorItemType[]>[
    {
        type: "Guide",
        name: "createGuide",
        bgClass: "bg-adviceGreen/60",
        iconComponent: markRaw(AdviceBackgroundIcon),
        strokeClass: "stroke-adviceGreen",
        link: "/create/guide"
    },
    {
        type: "Software",
        name: "createSoftware",
        bgClass: "bg-secondary-blue/60",
        iconComponent: markRaw(SoftwareBackgroundIcon),
        strokeClass: "stroke-secondary-blue",
        link: "/create/software"
    },
    {
        type: "Hardware",
        name: "createHardware",
        bgClass: "bg-adviceGreen/60",
        iconComponent: markRaw(HardwareBackgroundIcon),
        strokeClass: "stroke-adviceGreen",
        link: "/create/hardware"
    },
    {
        type: "Event",
        name: "createEvent",
        bgClass: "bg-secondary-mbRose/60",
        iconComponent: markRaw(EventBackgroundIcon),
        strokeClass: "stroke-secondary-mbRose",
        link: "/create/event"
    }
])

const handleClickSelector = (link: string): void => {
    router.push(link)
}

const isDisabled = (item: SelectorItemType) => {
    const currentPath = route.name
    if (route.name === "userPosts") {
        return false
    }
    return !(currentPath === item.name)
}


</script>

<template>
    <div class="flex justify-center flex-row gap-4">
        <CreateSelectorItem
            v-for="item in items"
            :key="item.type"
            :bg-color-class="item.bgClass"
            :click-callback="() => handleClickSelector(item.link)"
            :disabled="isDisabled(item)"
        >
            <template #selectorText>
                {{ item.type }}
            </template>
            <template #backgroundImage>
                <component
                    :is="item.iconComponent"
                    :class="item.strokeClass"
                />
            </template>
        </CreateSelectorItem>
    </div>
</template>

