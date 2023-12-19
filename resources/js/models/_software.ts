import Post from "@/js/models/post";

export default class Software extends Post {
    softwaretype_id: Array<number>
    constructor(title: string, excerpt: string, content: string, coverImage: string, authorName: string, tags: Array<string>, extra_content: string, softwaretype_id: Array<number>) {
        super(title, excerpt, content, coverImage, authorName, tags, extra_content)
        this.softwaretype_id = softwaretype_id
    }

}
