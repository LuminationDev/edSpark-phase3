export type BasePostType = {
    content: string;
    title: string;
    cover_image: string | null;
    excerpt: string;
    extra_content: string | null;
    id: number;
    type: string;
    post_type: string;
    created_at: string;
    updated_at: string;
    isLikedByUser?: boolean;
    isBookmarkedByUser?: boolean;
};
