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
        <div class="HowItWorksContainer flex flex-col pt-4 px-5 lg:!pt-16 lg:!px-10 xl:!px-20">
            <div class="grid grid-cols-6 mt-6 lg:!flex lg:!flex-row lg:!justify-around">
                <div
                    class="
                        col-span-6
                        grid
                        gap-2
                        place-items-center
                        hover:cursor-pointer
                        md:!col-span-3
                        lg:!basis-1
                        lg:!flex
                        lg:!flex-col
                        lg:!gap-0
                        "
                    @click="router.push('/browse/software/provided')"
                >
                    <DeptProvidedIcon />
                    <div class="font-semibold mb-4 mt-10 text-center">
                        Department Provided
                    </div>
                    <div class="text-center">
                        These applications are provided by the department at no cost to schools
                    </div>
                </div>
                <div
                    class="
                        col-span-6
                        grid
                        place-items-center
                        hover:cursor-pointer
                        md:!col-span-3
                        lg:!basis-1
                        lg:!flex
                        lg:!flex-col
                        "
                    @click="router.push('/browse/software/approved')"
                >
                    <DeptApprovedIcon />
                    <div class="font-semibold mb-4 mt-10 text-center">
                        Department Approved
                    </div>
                    <div class="text-center">
                        These applications have been risk assessed and can be safely used in schools.
                    </div>
                </div>
                <div
                    class="col-span-6 grid place-items-center hover:cursor-pointer lg:!basis-1 lg:!flex lg:!flex-col"
                    @click="router.push('/browse/software/negotiated')"
                >
                    <DeptNegotiatedIcon />
                    <div class="font-semibold mb-4 mt-10 text-center">
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
