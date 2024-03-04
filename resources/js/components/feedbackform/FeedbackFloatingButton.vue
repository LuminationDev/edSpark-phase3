<script setup lang="ts">

</script>

<template>
    <div
        class="
            bg-main-darkTeal
            border-[1px]
            border-white
            flex
            justify-center
            items-center
            flex-col
            h-28
            rounded-lg
            scale-75
            w-20
            hover:!bg-main-teal
            md:!scale-100
            ">
        <img
            class="h-10 ml-2 w-10"
            src="@/assets/images/Feedback-MSG-icon.png"
            alt="icon indicating message"
        >
        <div class="text-center text-white floatingFeedbackBtn">
            Feedback & <br> suggestions
        </div>
    </div>
</template>


<style scoped>
.floatingFeedbackBtn {
    font-size: 0.76rem;
    font-weight: 400;
    line-height: 1.1;
}
</style>