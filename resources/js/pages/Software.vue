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
    router.push('/browse/softwares')
}
</script>

<template>
    <SoftwareHero />
    <div class="SoftwareContentContainer">
        <div class="HowItWorksContainer flex flex-col px-20 pt-16">
            <div class="flex flex-row justify-around mt-6">
                <div class="flex flex-col basis-1">
                    <DeptProvidedIcon />
                    <div class="font-semibold mt-10 mb-4 text-center">
                        Department Provided
                    </div>
                    <div class="text-center">
                        These applications are provided by the department at no cost to schools
                    </div>
                </div>
                <div class="flex flex-col basis-1">
                    <DeptApprovedIcon />
                    <div class="font-semibold mt-10 mb-4 text-center">
                        Department Approved
                    </div>
                    <div class="text-center">
                        These applications have been risk assessed and can be safely used in schools.
                    </div>
                </div>
                <div class="flex flex-col basis-1">
                    <DeptNegotiatedIcon />
                    <div class="font-semibold mt-10 mb-4 text-center">
                        Approved and Negotiatied
                    </div>
                    <div class="text-center">
                        Still risk assessed, these applications have an agreement in place for schools to receive better
                        value. Schools will need to fund the purchases
                    </div>
                </div>
            </div>
        </div>
        <div class="softwareListContainer">
            <SectionHeader
                :classes="'bg-[#002858]'"
                :section="'software'"
                title="Software"
                button-text="View all software"
                :button-callback="handleBrowseAllSoftware"
            />
            <SoftwareCardGallery />
        </div>
    </div>
</template>
