<script setup>
import axios from 'axios'
import {onBeforeMount, ref, computed, onMounted} from 'vue'
import {serverURL} from "../../constants/serverUrl";
import {storeToRefs} from "pinia";
import {useUserStore} from "../../stores/useUserStore";

const props = defineProps({
    contentType:{
        type: String,
        required: true
    }
})

const {currentUser} = storeToRefs(useUserStore())

const biography = ref('')
const interests = ref('')
const subjects = ref('')
const yearLevels = ref([])
const userAvatar = ref('')

const editBio = ref(false)
const editYearLevels = ref(false)

function camelize(str) {
    return str.replace(/(?:^\w|[A-Z]|\b\w)/g, function(word, index) {
        return index === 0 ? word.toLowerCase() : word.toUpperCase();
    }).replace(/\s+/g, '');
}

const formattedKey = computed( () => {
    console.log(camelize(props.contentType))
    return camelize(props.contentType)
})

const displayContent = computed(() => {
    if(currentUser.value.metadata.filter(n => n['user_meta_key'] === camelize(props.contentType))[0]){
        return currentUser.value.metadata.filter(n => n['user_meta_key'] === camelize(props.contentType))[0]['user_meta_value']
    }else return 'No content available'
})

function handleMetaData() {
    if(currentUser.value) {
        currentUser.value.metadata.forEach(meta => {
            switch (meta.user_meta_key) {
            case 'biography':
                if ( typeof meta.user_meta_value === 'string') {
                    biography.value = meta.user_meta_value
                } else {
                    biography.value = meta.user_meta_value.join(', ');
                }
                break;
            case 'yearLevels':
                yearLevels.value = meta.user_meta_value
                break;
            case 'interests':
                interests.value = meta.user_meta_value
                break;
            case 'subjects':
                subjects.value = meta.user_meta_value
                break;
            case 'userAvatar':
                userAvatar.value = meta.user_meta_value
                break;

            default:
                biography.value = null;
                yearLevels.value = null;
                interests.value = null;
                subjects.value = null;
                break;
            }
        })
    }
}


onMounted(() => {
    handleMetaData()
})

</script>

<template>
    <div class="h-40 min-h-44 profileContent">
        <!--        Biography-->
        <div v-if="contentType === 'Biography'">
            <div v-if="biography">
                <div
                    v-if="!editBio"
                    @click.prevent="editBio = !editBio"
                >
                    <p class="col-span-3 font-medium mx-6 my-6 text-[18px] text-black">
                        {{ biography }}
                    </p>
                </div>
                <div
                    v-if="editBio"
                    class="flex flex-col gap-6"
                >
                    <label for="bio">Edit your bio</label>
                    <textarea
                        id="bio"
                        ref="bioTextarea"
                        v-model="biography"
                        name="bio"
                        class="min-h-[190px]"
                    />

                    <div class="flex justify-self-end flex-row gap-4 ml-auto">
                        <button
                            class="
                                bg-transparent
                                hover:bg-[#1C5CA9]
                                border-2
                                border-[#1C5CA9]
                                px-4
                                py-2
                                rounded-lg
                                text-[#1C5CA9]
                                hover:text-white
                                "
                            @click.prevent="editBio = !editBio"
                        >
                            Cancel
                        </button>

                        <button
                            class="
                                bg-[#1C5CA9]
                                hover:bg-[#143965]
                                border-2
                                hover:border-[#143965]
                                border-[#1C5CA9]
                                px-4
                                py-2
                                rounded-lg
                                text-white
                                "
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--        year Levels -->
        <div v-else-if="contentType === 'Year levels'">
            <div v-if="yearLevels">
                <div
                    v-if="!editYearLevels"
                    @click.prevent="editYearLevels = !editYearLevels"
                >
                    <p
                        v-for="(year,index) in yearLevels"
                        :key="index"
                        class="bg-gray-100 flex justify-center h-[24px] mx-6 my-6 place-items-center rounded-full w-[24px]"
                    >
                        {{ year }}
                    </p>
                </div>
                <div
                    v-if="editYearLevels"
                    class="flex flex-col gap-6"
                >
                    <label for="yearLevels">What year levels do you teach?</label>
                    <div class="flex flex-col gap-6">
                        <div class="flex flex-row gap-3 w-full">
                            <div
                                v-for="(year, index) in yearLevels"
                                :key="index"
                            >
                                <label :for="year">{{ year }}</label>
                                <input
                                    :id="year"
                                    type="checkbox"
                                    :value="year"
                                >
                            </div>
                        </div>

                        <div class="flex justify-self-end flex-row gap-4 ml-auto">
                            <button
                                class="
                                    bg-transparent
                                    hover:bg-[#1C5CA9]
                                    border-2
                                    border-[#1C5CA9]
                                    px-4
                                    py-2
                                    rounded-lg
                                    text-[#1C5CA9]
                                    hover:text-white
                                    "
                                @click.prevent="editYearLevels = !editYearLevels"
                            >
                                Cancel
                            </button>

                            <button
                                class="
                                    bg-[#1C5CA9]
                                    hover:bg-[#143965]
                                    border-2
                                    hover:border-[#143965]
                                    border-[#1C5CA9]
                                    px-4
                                    py-2
                                    rounded-lg
                                    text-white
                                    "
                                @click="updateYearLevels(year)"
                            >
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--        subjects-->
        <div v-else-if="contentType === 'Subjects'">
            <div v-if="subjects">
                <p
                    v-for="(subject,index) in subjects"
                    :key="index"
                    class="bg-gray-100 flex justify-center h-[24px] mx-6 my-6 place-items-center rounded-full w-[24px]"
                >
                    {{ subject }}
                </p>
            </div>
        </div>
        <!--interests-->
        <div v-else-if="contentType === 'Interests'">
            <div v-if="interests">
                <p
                    class="mx-6 my-6"
                >
                    Interests goes here
                </p>
            </div>
        </div>
    </div>
</template>

