<script>
    /**
     * IMPORT SVG's
     */
    import Like from '../svg/Like.vue';
    import BookMark from '../svg/BookMark.vue';

    export default {
        props: {

        },

        components: {
            Like,
            BookMark,
        },

        methods: {
            checkData() {
                // console.log(this.posts);
            }
        },

        mounted() {
            this.checkData();
        },
    }
</script>

<template>
    <div class="h-full flex flex-col"> <!-- CARD BODY -->
        <div class="relative h-36 group-hover:h-0 transition-all"> <!-- CARD HEADER -->
            <slot name="cover"></slot>
            <slot name="typeTag"></slot>
        </div>
        <div class="px-[24px] py-[18px] relative transition-all"> <!-- CARD CONTENT -->
            <div class="card-content_title min-h-[72px] transition-all"> <!-- CARD CONTENT HEADER -->
                <h5 class="mr-[36px] text-[24px] font-medium group-hover:mr-28 transition-all">
                    <slot name="title"></slot>
                </h5>
            </div>
            <slot name="negotiatedDeals"></slot>
            <div class="card-content_body transition-all"> <!-- CARD CONTENT BODY-->
                <slot name="techUsed"></slot>
                <p class="transition-all"><small><slot name="created_at"></slot></small></p>
                <p class="transition-all"><slot name="description"></slot></p>
            </div>
        </div>
        <div class="px-[24px] py-[18px] mt-auto flex gap-4"> <!-- CARD FOOTER -->
            <Like />
            <bookMark />
        </div>
    </div>
</template>

<style>
    .card-content_body {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 8;
        -webkit-box-orient: vertical;
    }

    .card_parent:hover .card-content_body {
        -webkit-line-clamp: 14 !important;
    }

    .card-content_title {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .card_parent:hover .card-content_title {
        -webkit-line-clamp: 4 !important;
    }
</style>
