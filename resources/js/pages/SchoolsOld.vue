<script>
    /**
     * Import Components
     */
import SchoolsHero from '../components/schools/SchoolsHero.vue';
import ContentSection from '../components/global/ContentSection.vue';
import SchoolsTech from '../components/schools/SchoolsTech.vue';
import SearchableMap from '../components/schools/SearchableMap.vue';

/**
 * Import SVG's
 */
import Microsoft from '../components/svg/Microsoft.vue';
import ThreeDPrintingIcon from '../components/svg/ThreeDPrintingIcon.vue';
import AppleIcon from '../components/svg/AppleIcon.vue';
import FrogIcon from '../components/svg/FrogIcon.vue';
import IoTIcon from '../components/svg/IoTIcon.vue';
import RoboticsIcon from '../components/svg/RoboticsIcon.vue';
import ARIcon from '../components/svg/ARIcon.vue';
import VRIcon from '../components/svg/VRIcon.vue';

export default {
    components: {
        SchoolsHero,
        ContentSection,
        Microsoft,
        ThreeDPrintingIcon,
        AppleIcon,
        FrogIcon,
        IoTIcon,
        RoboticsIcon,
        ARIcon,
        VRIcon,
        SchoolsTech,
        SearchableMap
    },
        
}
</script>

<template>
  <div>
    <SchoolsHero />

    <div class="px-[81px] py-20">
      <div class="grid grid-cols-4 gap-[24px] w-full">
        <div
          v-for="school in schools"
          class="col-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl"
        >
          <router-link :to="`/schools/${school.title}`">
            <ContentSection>
              <template #cover>
                <div
                  :class="`bg-[url('${school.cover}')]`"
                  class="h-36 group-hover:h-0 transition-all"
                />
              </template>
              <template #title>
                {{ school.title }}
              </template>
              <template #techUsed>
                <p class="pt-6 text-black text-[18px] font-medium">
                  Tech used:
                </p>
                <div class=" pt-4 flex flex-row w-full justify-between place-items-center ">
                  <div
                    v-for="tech in school.techUsed"
                    class="flex"
                  >
                    <div
                      v-if="tech.name === 'Mircosoft Teams'"
                      class="my-auto group/tech schools-tech"
                    >
                      <Microsoft /><SchoolsTech :tech="tech" />
                    </div>
                    <div
                      v-if="tech.name === '3D Printing'"
                      class="my-auto group/tech schools-tech"
                    >
                      <ThreeDPrintingIcon /><SchoolsTech :tech="tech" />
                    </div>
                    <div
                      v-if="tech.name === 'Apple'"
                      class="my-auto group/tech schools-tech"
                    >
                      <AppleIcon /><SchoolsTech :tech="tech" />
                    </div>
                    <div
                      v-if="tech.name === 'Frog'"
                      class="my-auto group/tech schools-tech"
                    >
                      <FrogIcon /><SchoolsTech :tech="tech" />
                    </div>
                    <div
                      v-if="tech.name === 'IoT'"
                      class="my-auto group/tech schools-tech"
                    >
                      <IoTIcon /><SchoolsTech :tech="tech" />
                    </div>
                    <div
                      v-if="tech.name === 'Robotics'"
                      class="my-auto group/tech schools-tech"
                    >
                      <RoboticsIcon /><SchoolsTech :tech="tech" />
                    </div>
                    <div
                      v-if="tech.name === 'AR'"
                      class="my-auto group/tech schools-tech"
                    >
                      <ARIcon /><SchoolsTech :tech="tech" />
                    </div>
                    <div
                      v-if="tech.name === 'VR'"
                      class="my-auto group/tech schools-tech"
                    >
                      <VRIcon /><SchoolsTech :tech="tech" />
                    </div>
                  </div>
                </div>
              </template>
            </ContentSection>
          </router-link>
        </div>
      </div>
    </div>

    <div class="px-[81px] py-20">
      <SearchableMap />
    </div>
  </div>
</template>
