<script setup>
import SoftwareHero from "@/js/components/software/SoftwareHero.vue";
import DeptNegotiatedIcon from "@/js/components/svg/software/DeptNegotiatedIcon.vue";
import DeptApprovedIcon from "@/js/components/svg/software/DeptApprovedIcon.vue";
import DeptProvidedIcon from "@/js/components/svg/software/DeptProvidedIcon.vue";
import SectionHeader from "@/js/components/global/SectionHeader.vue";
import SoftwareCardGallery from "@/js/components/software/SoftwareCardGallery.vue";
import {useRouter} from "vue-router";

const router = useRouter()
const handleBrowseAllSoftware = () => {
    router.push('/browse/software')
}
</script>

<template>
    <SoftwareHero />
    <div class="SoftwareContentContainer">
        <div class="HowItWorksContainer flex flex-col px-20">
            <div class="howItWorksTitle uppercase text-xl font-semibold">
                How it works
            </div>
            <div class="subtitle font-normal w-1/2">
                Use this page to explore software which has the department tick of approval. Find out more information, see use casesm steps to purchase and possible costs.
            </div>
            <div class="flex flex-row justify-around mt-6">
                <div class="flex flex-col justify-center basis-1">
                    <DeptProvidedIcon />
                    <div class="font-semibold mt-10 mb-4 text-center">
                        Department Provided
                    </div>
                    <div class="text-center">
                        Still risk assessed, these applications have an agreement in place for schools to receive better value. Schools will need to fund the purchases
                    </div>
                </div>
                <div class="flex flex-col justify-center basis-1">
                    <DeptApprovedIcon />
                    <div class="font-semibold mt-10 mb-4 text-center">
                        Department Approved
                    </div>
                    <div class="text-center">
                        Still risk assessed, these applications have an agreement in place for schools to receive better value. Schools will need to fund the purchases
                    </div>
                </div>
                <div class="flex flex-col justify-center basis-1">
                    <DeptNegotiatedIcon />
                    <div class="font-semibold mt-10 mb-4 text-center">
                        Approved and Negotiatied
                    </div>
                    <div class="text-center">
                        Still risk assessed, these applications have an agreement in place for schools to receive better value. Schools will need to fund the purchases
                    </div>
                </div>
            </div>
        </div>
        <div class="softwareListContainer">
            <SectionHeader
                :classes="'bg-[#002858]'"
                :section="'software'"
                title="Software"
                button-text="View all Software"
                :button-callback="handleBrowseAllSoftware"
            />
            <SoftwareCardGallery />
        </div>
    </div>
</template>
