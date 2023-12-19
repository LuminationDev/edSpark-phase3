import PostInterface from "@/js/models/_post_interface";

export default class Post implements PostInterface {
    title: string
    excerpt: string
    content: string
    coverImage: string
    authorName: string
    tags: Array<string>
    extraContent: string;
    updated_at: string;
    isLikedByUser?: boolean;
    isBookmarkedByUser?: boolean;

    constructor(title: string, excerpt: string, content: string, coverImage: string, authorName: string, tags: Array<string>, extra_content: string) {
        this.title = title
        this.excerpt = excerpt
        this.content = content
        this.coverImage = coverImage
        this.authorName = authorName
        this.tags = tags
        this.extraContent = extra_content
    }
}