<script>
    import { computed } from 'vue';
    import DashboardHero from '../components/dashboard/DashboardHero.vue';
    import SectionHeader from '../components/global/SectionHeader.vue';
    import ContentSection from '../components/global/ContentSection.vue';
    import axios from 'axios';

    export default {
        setup() {

        },

        components: {
            DashboardHero,
            SectionHeader,
            ContentSection
        },

        data() {
            return {
                posts: []
            }
        },

        created() {
            this.getPosts();
        },

        methods: {
            async getPosts() {
                await axios.get('/api/category')
                    .then((response) => {
                        this.posts = response.data;
                    })
                    .catch((error) => {
                        console.log('---ERROR LOGGING---', error);
                    })
                // let response = await axios.get('/api/category');
                // console.log(response.data)
                // return response.data

            }
        },

        mounted() {
            // this.getPosts();
            // console.log(this.getPosts);
            // console.log(this.posts)
        }
    }
</script>

<template>
    <div>
        <DashboardHero class="-mt-[140px]" />

        <div class="px-[81px] mt-20">
            <SectionHeader />
        </div>

        <div class="px-[81px] mt-20">
            <ContentSection :posts="this.posts" />
        </div>

    </div>
</template>
