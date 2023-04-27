<script setup>
import {computed, ref} from "vue";
import {imageURL} from "@/js/constants/serverUrl";
const props = defineProps({
    title:{
        type: String,
        required: true
    },
    numberPerRow: {
        type: Number,
        required: false,
        default: 3
    },
    /**
     * To be moved into an object of "content"
     */
    displayAuthor:{
        type: String, required: true
    },
    displayDate:{
        type: String, required: false
    },
    displayContent:{
        type: String, required: true
    },
    coverImage:{
        type: String, required: false
    },
    clickCallback:{
        type: Function,
        required: false,
        default: () => {}
    }

})

const formattedDate = computed(() =>{
    const date = new Date(Date.parse(props.displayDate))
    return date.toDateString()
})

const cardHoverToggle = ref(false)
</script>

<template>
    <div
        :class="{'!w-[30%]': numberPerRow === 3,
                 '!w-[22%]': numberPerRow === 4,
                 '!w-[40%]': numberPerRow === 2,
                 '!w-[95%]' : numberPerRow === 1
        }"
        class="GenericCardContainer w-full border-[0.5px] border-black hover:shadow-2xl mx-2 mb-4 flex flex-col min-h-[450px] max-w-[400px] max-h-[450px] group transition-all card_parent cursor-pointer"
        @mouseenter="cardHoverToggle = true"
        @click="clickCallback"
    >
        <div
            class="cardTopCoverImage relative min-h-[35%] bg-cover bg-center group-hover:min-h-[0%] group-hover:h-0 transition-all"
            :class="`bg-[url('${imageURL}/${coverImage.replace(' ','%20' )}')]`"
        >
            <template
                v-if="$slots.typeTag"
            >
                <slot
                    name="typeTag"
                />
            </template>

            <div
                v-if="$slots.icon"
            >
                <slot name="icon" />
            </div>
        </div>
        <div class="cardContent flex flex-col p-4 overflow-hidden transition-all ">
            <div
                v-if="props.title"
                class="cardTitle text-xl font-bold uppercase transition-all mb-4 group-hover:w-3/4"
            >
                {{ props.title }}
            </div>
            <div class="flex flex-col card-content_body h-full">
                <div
                    v-if="props.displayAuthor"
                    class="cardAuthor text-base font-semibold mt-2 transition-all"
                >
                    {{ props.displayAuthor }}
                </div>
                <div
                    v-if="props.displayDate"
                    class="cardDate text-base  mb-2 transition-all"
                >
                    {{ formattedDate }}
                </div>
                <div
                    v-if="props.displayContent"
                    class="cardDisplayPreview pt-2 font-light text-lg overflow-hidden mt-auto pb-6 transition-all"
                    v-html="props.displayContent"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
