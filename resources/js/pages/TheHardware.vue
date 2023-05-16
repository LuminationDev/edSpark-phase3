<script setup>
// Named TheHardware as eslint doesn't like single word filename
import HardwareLanding from "@/js/components/svg/hardware/HardwareLanding.vue";
import HardwareHero from "@/js/components/hardware/HardwareHero.vue";
import SectionHeader from "@/js/components/global/SectionHeader.vue";
import HardwareLaptopSection from "@/js/components/svg/hardware/HardwareLaptopSection.vue";





</script>

<template>
    <HardwareHero />
    <div class="HardwareContentContainer">
        <div class="BorrowOrPurchaseContainer flex flex-row mt-10">
            <div class="flex flex-col basis-1/2 items-center justify-center">
                <HardwareLanding />
            </div>
            <div class="flex flex-col basis-1/2 px-6 pt-6 justify-center">
                <div class="title text-2xl font-semibold uppercase">
                    borrow or purchase
                </div>
                <div class="subtitle pt-6 text-lg pr-20">
                    {{ `Discover what is available to purchase or borrow. Request to borrow loan items and generate a quote for purchase which can then be processed by your school business manager` }}
                </div>

                <div class="learn-more text-green-900 underline pt-6">
                    Learn more
                </div>
            </div>
        </div>
        <!--  end of first section    -->
        <SectionHeader
            :classes="'bg-[#048246]'"
            title="Laptops"
            section="laptops"
            button-text="View all laptops"
        />
        <HardwareLaptopSection />
    </div>
</template>


