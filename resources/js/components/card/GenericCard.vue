<script setup>
import {computed, ref} from "vue";
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
    }

})

const formattedDate = computed(() =>{
    const date = new Date(Date.parse(props.displayDate))
    return date.toDateString()
})
const tempCoverImage ='http://localhost:8000/storage//uploads\/school\/edspark-school-afc6de98542b2aaac72bc1402a51530c.webp'

const cardHoverToggle = ref(false)
</script>

<template>
    <div
        :class="{'w-[30%]': numberPerRow === 3,
                 'w-[22%]': numberPerRow === 4,
                 'w-[40%]': numberPerRow === 2
        }"
        class="GenericCardContainer border-2 border-black rounded mx-2 mb-4 flex flex-col min-h-[500px] max-w-[400px] max-h-[500px] group transition-all card_parent cursor-pointer"
        @mouseenter="cardHoverToggle = true"
    >
        <div
            class="cardTopCoverImage relative min-h-[35%] bg-cover group-hover:min-h-[0%] group-hover:h-0 transition-all"
            :class="`bg-[url(${tempCoverImage})]`"
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
                class="icon absolute right-2 bottom-2"
            >
                <slot name="icon" />
            </div>
        </div>
        <div class="cardContent flex flex-col p-4 overflow-hidden transition-all">
            <div
                v-if="props.title"
                class="cardTitle card-content_title text-xl font-bold uppercase transition-all"
            >
                {{ props.title }}
            </div>
            <div class="flex flex-col card-content_body">
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
                >
                    <p>{{ props.displayContent }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.card-content_body {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 6;
    -webkit-box-orient: vertical;
}

.card_parent:hover .card-content_body {
    -webkit-line-clamp: 14;
}

.card-content_title {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.card_parent:hover .card-content_title {
    -webkit-line-clamp: 4 !important;
}
</style>