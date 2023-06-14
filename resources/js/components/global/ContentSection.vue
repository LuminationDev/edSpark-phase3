<script setup>
    /**
     * IMPORT SVG's
     */
import Like from '../svg/Like.vue';
import BookMark from '../svg/BookMark.vue';

const props = defineProps({
    techUsed:{
        type: Array,
        required: false
    },
    schoolName:{
        type: String, required: true
    }
});

</script>

<template>
    <router-link :to="`/schools/${props.schoolName}`">
        <!-- CARD BODY -->
        <div class="relative h-36 group-hover:h-0 transition-all">
            <!-- CARD HEADER -->
            <slot name="cover" />
            <slot name="typeTag" />
        </div>
        <div class="px-6 py-4 relative transition-all">
            <!-- CARD CONTENT -->
            <div class="card-content_title min-h-[72px] transition-all group-hover:mr-24">
                <!-- CARD CONTENT HEADER -->
                <h5 class="text-xl font-medium transition-all flex justify-between place-items-center group-hover:mr-8">
                    <slot name="title" />
                </h5>
            </div>
            <slot name="negotiatedDeals" />
            <div class="card-content_body transition-all">
                <!-- CARD CONTENT BODY-->
                <!-- Scope slot props -->
                <slot
                    name="techUsed"
                    :tech-used="props.techUsed"
                />
                <p class="transition-all">
                    <small><slot name="created_at" /></small>
                </p>
                <p class="transition-all">
                    <slot name="description" />
                </p>
            </div>
        </div>
    </router-link>
            
    <div class="px-[24px] py-[18px] mt-auto flex gap-4">
        <!-- CARD FOOTER -->
        <Like />
        <BookMark />
    </div>
</template>

<style scoped>
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

    .card-content_body {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
    }

    .card_parent:hover .card-content_title {
        -webkit-line-clamp: 4 !important;
    }
</style>
