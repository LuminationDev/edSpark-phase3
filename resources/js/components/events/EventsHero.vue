<script>
import BasePageHero from "@/js/components/bases/BasePageHero.vue";

export default {
    components: {BasePageHero}

}
</script>

<template>
    <BasePageHero
        class="bg-gradient-to-r from-[#002858] via-[#0158AD] to-[#DE4668]/50"
        title="Events"
        subtitle="The department have partnered with trusted companies to provide you with the best digital tool at your fingertips. Find out more about our partners and access a range of curated resources."
    >
        <template #pageIconSVG>
            <img
                class="absolute right-0"
                src="../../../assets/images/eventsCalendar.png"
                alt="Industry Partners connecting pieces of the puzzle"
            >
        </template>
    </BasePageHero>
</template>
